<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticatedUserController extends Controller
{
    public function show()
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
