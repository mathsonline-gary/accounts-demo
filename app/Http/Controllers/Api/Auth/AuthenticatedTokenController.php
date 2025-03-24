<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedTokenController extends Controller
{
    public function store(LoginRequest $request)
    {
        $token = $request->authenticate();

        return response()->json([
            'message' => 'User logged in successfully',
            'data' => [
                'token' => $token,
            ],
        ]);
    }

    public function destroy()
    {
        Auth::logout();

        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }

    public function refresh()
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
