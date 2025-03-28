<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticatedUserController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            $user = Auth::user();
        } catch (JWTException) {
            return response()->json([
                'message' => 'Failed to get user',
            ], 500);
        }

        if (! $user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        return response()->json([
            'message' => 'User found',
            'data' => [
                'user' => $user,
            ],
        ]);
    }
}
