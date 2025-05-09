<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Request;
use App\Services\AuthService;
use App\ValueObjects\AuthTokenSet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedTokenController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function store(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $tokenAuth = AuthTokenSet::fromUser(auth()->user(), $request->boolean('remember') ? 7 : 1);

        return response()->json([
            'message' => 'User logged in successfully',
            'data' => [
                'token' => $tokenAuth->accessToken,
            ],
        ])->cookie($tokenAuth->cookie());
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([
            'message' => 'User logged out successfully',
        ])->withoutCookie('refresh_token');
    }

    public function refresh(Request $request): JsonResponse
    {
        $refreshToken = $request->cookie('refresh_token');
        if (! $refreshToken) {
            abort(401);
        }

        $token = JWTAuth::setToken($refreshToken)->refresh();

        return response()->json([
            'message' => 'Token refreshed successfully',
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
