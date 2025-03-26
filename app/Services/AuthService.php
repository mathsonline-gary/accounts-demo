<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Events\CustomerCreated;
use App\Exceptions\AccountInitializationFailedException;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
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

            try {
                $token = JWTAuth::fromUser($user);
            } catch (JWTException $e) {
                Log::error("Failed to create JWT token: {$e->getMessage()}", ['user_id' => $user->id]);

                $token = null;
            }

            return $token;
        }

        // TODO: register school account

        return null;
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
            DB::beginTransaction();

            // TODO: upsert Stripe customer.

            $user = User::create([
                'brand_id' => $payload['brand_id'],
                'role_id' => UserRole::CUSTOMER->value,
                // 'stripe_customer_id' => $stripeCustomer->id,
                'username' => $payload['username'],
                'first_name' => $payload['first_name'],
                'last_name' => $payload['last_name'],
                'email' => $payload['email'],
                'password' => Hash::make($payload['password']),
                'home_phone' => $payload['home_phone'],
                'mobile_phone' => $payload['mobile_phone'],
                'address_line_1' => $payload['address_line_1'],
                'address_line_2' => $payload['address_line_2'],
                'address_city' => $payload['address_city'],
                'address_state' => $payload['address_state'],
                'address_postal_code' => $payload['address_postal_code'],
                'address_country' => $payload['address_country'],
                'ip_address' => $payload['ip_address'],
            ]);

            event(new CustomerCreated($user));

            // TODO: create customer, school, etc.

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            Log::error("Failed to create account: {$e->getMessage()}", ['payload' => $payload]);

            throw new AccountInitializationFailedException;
        }

        return $user;
    }
}
