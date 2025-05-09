<?php

namespace App\Http\Controllers\Auth;

use App\Enums\ExternalService;
use App\Exceptions\Auth\UserAccountAlreadyExistsException;
use App\Exceptions\Auth\UserAccountNotCreatedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\OAuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class OAuthRegisteredUserController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    ) {}

    public function store(OAuthRequest $request, string $provider): JsonResponse
    {
        if (! in_array($provider, ['google'])) {
            return response()->json([
                'message' => 'Invalid OAuth provider.',
            ], 400);
        }

        try {
            $tokenAuth = $this->authService->oauthRegister(ExternalService::from($provider), $request->integer('brand_id'));
        } catch (UserAccountNotCreatedException $e) {
            return response()->json([
                'message' => sprintf('Failed to sign up via %s.', ucfirst($provider)),
            ], 500);
        } catch (UserAccountAlreadyExistsException $e) {
            return response()->json([
                'message' => sprintf('This %s account is already in use. Please login instead or try with another one.', ucfirst($provider)),
            ], 409);
        }

        return response()->json([
            'message' => sprintf('Successfully sign up via %s.', ucfirst($provider)),
            'data' => [
                'token' => $tokenAuth->accessToken,
            ],
        ])->cookie($tokenAuth->cookie());
    }
}
