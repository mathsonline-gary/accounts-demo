<?php

namespace App\Services;

use App\Enums\ExternalService;
use App\Enums\UserRole;
use App\Events\CustomerCreated;
use App\Exceptions\AccountInitializationFailedException;
use App\Models\User;
use App\Models\UserExternalAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
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
     * @throws AccountInitializationFailedException
     * @throws Throwable
     */
    public function register(array $payload): ?string
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

        if ($payload['type'] === 'customer') {
            $user = $this->initializeUserAccount($payload);

            event(new Registered($user));

            return JWTAuth::fromUser($user);
        }

        return null;
    }

    /**
     * Get the redirect URL for the OAuth provider.
     */
    public function getOAuthRedirectUrl(string $provider, int $brandId): string
    {
        return Socialite::driver($provider)
            ->stateless()
            ->with(['brand_id' => $brandId])
            ->redirect()
            ->getTargetUrl();
    }

    /**
     * Authenticate a user via OAuth.
     */
    public function oauth(string $provider, int $brandId): string
    {
        // Get the user from the authorization code
        $socialiteUser = Socialite::driver($provider)
            ->stateless()
            ->user();

        $userSocialAccount = UserExternalAccount::byServiceAccountId($provider, $socialiteUser->getId())->first();

        if (! $userSocialAccount) {
            $attributes = [
                'brand_id' => $brandId,
                'type' => UserRole::CUSTOMER,
                'email' => $socialiteUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'username' => $socialiteUser->getEmail(), // Using email as username for OAuth users
            ];

            if ($provider === 'google') {
                $attributes['first_name'] = $socialiteUser->user['given_name'];
                $attributes['last_name'] = $socialiteUser->user['family_name'];
            }

            $user = $this->initializeUserAccount($attributes);

            $userSocialAccount = UserExternalAccount::create([
                'user_id' => $user->id,
                'service' => ExternalService::from($provider),
                'service_account_id' => $socialiteUser->getId(),
            ]);

            return JWTAuth::fromUser($user);
        }

        $user = $userSocialAccount->user;

        // TODO: check if the user is still active, if not, throw an exception

        return JWTAuth::fromUser($user);
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
     * @throws AccountInitializationFailedException
     */
    private function initializeUserAccount(array $payload): User
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
                    'role_id' => UserRole::CUSTOMER->value,
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
            Log::error("Failed to initialize customer account: {$e->getMessage()}", [
                'payload' => $payload,
                'exception' => $e,
            ]);

            throw new AccountInitializationFailedException('Failed to initialize customer account.', 0, $e);
        }
    }
}
