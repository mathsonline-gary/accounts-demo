<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedTokenController extends Controller
{
    public function store(LoginRequest $request): JsonResponse
    {
        $token = $request->authenticate();

        return response()->json([
            'message' => 'User logged in successfully',
            'data' => [
                'token' => $token,
            ],
        ]);
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

    public function refresh(): JsonResponse
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
