<?php

namespace App\Services;

use App\Enums\Brand;
use App\Enums\UserRole;
use App\Events\CustomerCreated;
use App\Exceptions\AccountInitializationFailedException;
use App\Models\User;
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
            $user = $this->initializeCustomerAccount($payload);

            event(new Registered($user));

            return JWTAuth::fromUser($user);
        }

        // TODO: register school account

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

    public function oauth(string $provider, int $brandId): string
    {
        // Get the user from the authorization code
        $socialiteUser = Socialite::driver($provider)
            ->stateless()
            ->user();

        $user = User::bySocialProviderId($provider, $socialiteUser->getId())->first();

        if (! $user) {
            $attributes = [
                'brand_id' => $brandId,
                'type' => UserRole::CUSTOMER,
                'email' => $socialiteUser->getEmail(),
                'password' => Hash::make(Str::random(16)),
                'username' => $socialiteUser->getEmail(), // Using email as username for OAuth users
            ];

            if ($provider === 'google') {
                $attributes['google_id'] = $socialiteUser->getId();
                $attributes['first_name'] = $socialiteUser->user['given_name'];
                $attributes['last_name'] = $socialiteUser->user['family_name'];
            }

            $user = $this->initializeCustomerAccount($attributes);
        }

        return JWTAuth::fromUser($user);
    }

    /**
     * Verify the state parameter and get associated data.
     */
    private function verifyState(string $state): ?array
    {
        $data = cache()->get("oauth_state_{$state}");

        if (! $data) {
            return null;
        }

        // Delete the state from cache
        //        cache()->forget("oauth_state_{$state}");

        if (! is_array($data) || ! isset($data['brand_id']) || ! in_array((int) $data['brand_id'], array_column(Brand::cases(), 'value'))) {
            return null;
        }

        return $data;
    }

    /**
     * Initialize a customer account.
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
    private function initializeCustomerAccount(array $payload): User
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
                // TODO: upsert Stripe customer.

                $user = User::create([
                    'brand_id' => $payload['brand_id'],
                    'role_id' => UserRole::CUSTOMER->value,
                    // 'stripe_customer_id' => $stripeCustomer->id,
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
        } catch (Throwable $e) {
            Log::error("Failed to initialize customer account: {$e->getMessage()}", [
                'payload' => $payload,
                'exception' => $e,
            ]);

            throw new AccountInitializationFailedException('Failed to initialize customer account.', 0, $e);
        }
    }
}
