<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{
    public function show(): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            abort(401);
        }

        return response()->json([
            'data' => [
                'user' => $user,
            ],
        ]);
    }
}
