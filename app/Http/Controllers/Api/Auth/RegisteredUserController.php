<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\AccountInitializationFailedException;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {}

    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->register([
                'brand_id' => $request->integer('brand_id'),
                'type' => $request->input('type'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'home_phone' => $request->input('home_phone'),
                'mobile_phone' => $request->input('mobile_phone'),
                'address_line_1' => $request->input('address_line_1'),
                'address_line_2' => $request->input('address_line_2'),
                'address_city' => $request->input('address_city'),
                'address_state' => $request->input('address_state'),
                'address_postal_code' => $request->input('address_postal_code'),
                'address_country' => $request->input('address_country'),
                'ip_address' => $request->ip(),
            ]);
        } catch (AccountInitializationFailedException) {
            return response()->json([
                'message' => 'Failed to register',
            ], 500);
        }

        return response()->json([
            'message' => 'User registered successfully',
            'data' => [
                'token' => $token,
            ],
        ], 201);
    }
}
