<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Brand;
use App\Enums\ExternalService;
use App\Exceptions\Auth\UserAccountNotFoundException;
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
            'mode' => ['required', 'string', Rule::in('login', 'register')],
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

        $url = $this->authService->getOAuthRedirectUrl($provider, $validated);

        return response()->json([
            'message' => sprintf('Redirect to %s.', ucfirst($provider)),
            'data' => [
                'url' => $url,
            ],
        ]);
    }

    public function store(OAuthRequest $request, string $provider): JsonResponse
    {
        if (! in_array($provider, ['google'])) {
            return response()->json([
                'message' => 'Invalid OAuth provider.',
            ], 400);
        }

        try {
            $tokenAuth = $this->authService->oauthLogin(ExternalService::from($provider), $request->integer('brand_id'));
        } catch (UserAccountNotFoundException $e) {
            return response()->json([
                'message' => 'Could not find the linked account. Please sign up first or try with another one.',
            ], 404);
        }

        return response()->json([
            'message' => sprintf('Authenticated via %s.', ucfirst($provider)),
            'data' => [
                'token' => $tokenAuth->accessToken,
            ],
        ])->cookie($tokenAuth->cookie());
    }
}
