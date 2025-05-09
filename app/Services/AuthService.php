<?php

namespace App\Services;

use App\Enums\ExternalService;
use App\Enums\UserRole;
use App\Events\CustomerCreated;
use App\Exceptions\Auth\UserAccountAlreadyExistsException;
use App\Exceptions\Auth\UserAccountNotCreatedException;
use App\Exceptions\Auth\UserAccountNotFoundException;
use App\Models\User;
use App\Models\UserExternalAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Cookie;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Register a new account.
     *
     * @param array{
     *     brand_id: int,
     *     type: string,
     *     first_name: string,
     *     last_name: string,
     *     email: string,
     *     username: string,
     *     password: string,
     *     home_phone: string|null,
     *     mobile_phone: string|null,
     *     address_line_1: string|null,
     *     address_line_2: string|null,
     *     address_city: string|null,
     *     address_state: string|null,
     *     address_postal_code: string|null,
     *     address_country: string|null,
     *     ip_address: string|null
     * } $payload
     *
     * @throws UserAccountNotCreatedException
     */
    public function register(array $payload): void
    {
        $payload = Arr::only($payload, [
            'brand_id',
            'type',
            'first_name',
            'last_name',
            'email',
            'username',
            'password',
            'home_phone',
            'mobile_phone',
            'address_line_1',
            'address_line_2',
            'address_city',
            'address_state',
            'address_postal_code',
            'address_country',
            'ip_address',
        ]);

        if (User::byEmail($payload['email'])->first() !== null) {
            throw ValidationException::withMessages([
                'email' => 'This email is already taken. Please choose another one.',
            ]);
        }

        if (User::byUsername($payload['username'])->first() !== null) {
            throw ValidationException::withMessages([
                'username' => 'This username is already taken. Please choose another one.',
            ]);
        }

        $user = $this->createUserAccount($payload);

        event(new Registered($user));
    }

    /**
     * Get the redirect URL for the OAuth provider.
     *
     * @param  "google"  $provider
     * @param array{
     *     brand_id: int,
     *     mode: string
     * }               $parameters
     */
    public function getOAuthRedirectUrl(string $provider, array $parameters): string
    {
        $state = base64_encode(json_encode($parameters));

        return Socialite::driver($provider)
            ->stateless()
            ->with(['state' => $state])
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Register a user via OAuth.
     *
     * @return array{
     *     token: string,
     *     cookie: Cookie,
     * }
     *
     * @throws UserAccountNotCreatedException
     * @throws UserAccountAlreadyExistsException
     */
    public function oauthRegister(ExternalService $provider, int $brandId): array
    {
        // Get the user from the authorization code
        $socialiteUser = Socialite::driver($provider->toString())
            ->stateless()
            ->user();

        // Check whether there is an existing user linked to the social account.
        $userSocialAccount = UserExternalAccount::byProviderUserId($provider, $socialiteUser->getId())->first();
        if ($userSocialAccount) {
            throw new UserAccountAlreadyExistsException;
        }

        // Check whether there is an existing user with the same email.
        $existingUser = User::byEmail($socialiteUser->getEmail())->first();
        if ($existingUser) {
            throw new UserAccountAlreadyExistsException;
        }

        $attributes = [
            'brand_id' => $brandId,
            'type' => UserRole::TEACHER,
            'email' => $socialiteUser->getEmail(),
            'password' => Hash::make(Str::random(16)),
            'username' => $socialiteUser->getEmail(),
        ];

        if ($provider === ExternalService::GOOGLE) {
            $attributes['first_name'] = $socialiteUser->user['given_name'];
            $attributes['last_name'] = $socialiteUser->user['family_name'];
        }

        $user = $this->createUserAccount($attributes);

        UserExternalAccount::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_user_id' => $socialiteUser->getId(),
        ]);

        $accessToke = JWTAuth::fromUser($user);

        $refreshToken = JWTAuth::claims([
            'jti' => Str::uuid(),
            'exp' => now()->addDay()->timestamp,
        ])->fromUser($user);

        return [
            'token' => $accessToke,
            'cookie' => $this->newRefreshTokenCookie($refreshToken, 1),
        ];
    }

    /**
     * Log in a user via OAuth.
     *
     * @return array{
     *     token: string,
     *     cookie: Cookie,
     * }
     *
     * @throws UserAccountNotFoundException
     */
    public function oauthLogin(ExternalService $provider, int $brandId): array
    {
        // Get the user from the authorization code
        $socialiteUser = Socialite::driver($provider->toString())
            ->stateless()
            ->user();

        // Check whether there is an existing user linked to the social account.
        $userSocialAccount = UserExternalAccount::byProviderUserId($provider, $socialiteUser->getId())->first();
        if (! $userSocialAccount) {
            throw new UserAccountNotFoundException;
        }

        // Check whether the user is in the same brand.
        $user = $userSocialAccount->user;
        if ($user->brand_id !== $brandId) {
            Log::debug('User brand id: '.$brandId);
            throw new UserAccountNotFoundException;
        }

        $accessToke = JWTAuth::fromUser($user);

        $refreshToken = JWTAuth::claims([
            'jti' => Str::uuid(),
            'exp' => now()->addDay()->timestamp,
        ])->fromUser($user);

        return [
            'token' => $accessToke,
            'cookie' => $this->newRefreshTokenCookie($refreshToken, 1),
        ];
    }

    /**
     * Initialize a user account.
     *
     * Apart from the user, this method also creates other related models according to the type of user.
     *
     * @param array{
     *     brand_id: int,
     *     username: string,
     *     first_name: string,
     *     last_name: string,
     *     email: string,
     *     password: string,
     *     home_phone: string|null,
     *     mobile_phone: string|null,
     *     address_line_1: string|null,
     *     address_line_2: string|null,
     *     address_city: string|null,
     *     address_state: string|null,
     *     address_postal_code: string|null,
     *     address_country: string|null,
     *     ip_address: string|null
     * } $payload
     *
     * @throws UserAccountNotCreatedException
     */
    private function createUserAccount(array $payload): User
    {
        $payload = Arr::only($payload, [
            'brand_id',
            'google_id',
            'username',
            'first_name',
            'last_name',
            'email',
            'password',
            'home_phone',
            'mobile_phone',
            'address_line_1',
            'address_line_2',
            'address_city',
            'address_state',
            'address_postal_code',
            'address_country',
            'ip_address',
        ]);

        try {
            return DB::transaction(function () use ($payload) {
                $user = User::create([
                    'brand_id' => $payload['brand_id'],
                    'role_id' => UserRole::TEACHER->value,
                    'username' => $payload['username'],
                    'first_name' => $payload['first_name'] ?? null,
                    'last_name' => $payload['last_name'] ?? null,
                    'email' => $payload['email'],
                    'password' => Hash::make($payload['password']),
                    'home_phone' => $payload['home_phone'] ?? null,
                    'mobile_phone' => $payload['mobile_phone'] ?? null,
                    'address_line_1' => $payload['address_line_1'] ?? null,
                    'address_line_2' => $payload['address_line_2'] ?? null,
                    'address_city' => $payload['address_city'] ?? null,
                    'address_state' => $payload['address_state'] ?? null,
                    'address_postal_code' => $payload['address_postal_code'] ?? null,
                    'address_country' => $payload['address_country'] ?? null,
                    'ip_address' => $payload['ip_address'] ?? null,
                ]);

                event(new CustomerCreated($user));

                // TODO: create customer, school, etc.

                return $user;
            }, 5);

            // TODO: dispatch event: UserAccountInitialized
        } catch (Throwable $e) {
            Log::error("Failed to create user account: {$e->getMessage()}", [
                'payload' => $payload,
                'exception' => $e,
            ]);

            throw new UserAccountNotCreatedException;
        }
    }

    /**
     * Create a HTTPONLY cookie for the refresh token.
     */
    public function newRefreshTokenCookie(string $refreshToken, int $days): Cookie
    {
        return cookie('refresh_token', $refreshToken, 60 * 24 * $days, '/')
            ->withSameSite(app()->environment('local') ? Cookie::SAMESITE_LAX : Cookie::SAMESITE_NONE)
            ->withSecure(! app()->environment('local'));
    }
}
