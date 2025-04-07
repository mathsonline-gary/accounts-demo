<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Brand;
use App\Exceptions\Auth\UserAccountNotCreatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OAuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OAuthTokenController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    /**
     * Get the OAuth redirect URL for the specified provider.
     */
    public function index(Request $request, string $provider): JsonResponse
    {
        // Validate the request
        $validated = $request->validate([
            'brand_id' => ['required', 'integer', Rule::in(Brand::cases())],
        ]);

        // Validate the provider
        if (! in_array($provider, ['google'])) {
            return response()->json([
                'message' => 'Invalid OAuth provider.',
                'errors' => [
                    'provider' => ['The provider is invalid.'],
                ],
            ], 400);
        }

        $url = $this->authService->getOAuthRedirectUrl($provider, $validated['brand_id']);

        return response()->json([
            'message' => sprintf('Redirect to %s.', ucfirst($provider)),
            'data' => [
                'url' => $url,
            ],
        ]);
    }

    /**
     * Exchange OAuth code for JWT token.
     */
    public function store(OAuthRequest $request, string $provider): JsonResponse
    {
        if (! in_array($provider, ['google'])) {
            return response()->json([
                'message' => 'Invalid OAuth provider.',
                'errors' => [
                    'provider' => ['The provider is invalid.'],
                ],
            ], 400);
        }

        try {
            $token = $this->authService->oauth($provider, $request->input('brand_id'));
        } catch (UserAccountNotCreatedException $e) {
            return response()->json([
                'message' => 'Failed to authenticate via OAuth.',
            ], 500);
        }

        return response()->json([
            'message' => sprintf('Authenticated via %s.', ucfirst($provider)),
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
