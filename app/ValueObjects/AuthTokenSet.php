<?php

namespace App\ValueObjects;

use App\Models\User;
use Symfony\Component\HttpFoundation\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTokenSet
{
    /**
     * The short-lived access token.
     */
    public string $accessToken;

    /**
     * The long-lived HTTP-only refresh token.
     */
    public string $refreshToken;

    /**
     * Number of days until the refresh token expires.
     */
    protected int $expiresInDays;

    public static function fromUser(User $user, int $days = 1): self
    {
        $instance = new self;
        $instance->accessToken = JWTAuth::fromUser($user);
        $instance->refreshToken = JWTAuth::claims([
            'exp' => now()->addDays($days)->timestamp,
        ])->fromUser($user);
        $instance->expiresInDays = $days;

        return $instance;
    }

    public function cookie(): Cookie
    {
        return cookie('refresh_token', $this->refreshToken, 60 * 24 * $this->expiresInDays, '/')
            ->withSameSite(app()->environment('local') ? Cookie::SAMESITE_LAX : Cookie::SAMESITE_NONE)
            ->withSecure(! app()->environment('local'));
    }
}
