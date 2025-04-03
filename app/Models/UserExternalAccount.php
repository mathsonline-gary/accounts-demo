<?php

namespace App\Models;

use App\Enums\ExternalService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserExternalAccount extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'provider_user_id',
    ];

    public function casts(): array
    {
        return [
            'provider' => ExternalService::class,
        ];
    }

    /**
     * Get the user that the external account belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope a query to only include external accounts of the given user.
     */
    public function scopeOfUser(Builder $query, int $userId): void
    {
        $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include external accounts of the given provider.
     */
    public function scopeByProvider(Builder $query, ExternalService $provider): void
    {
        $query->where('provider', $provider);
    }

    /**
     * Scope a query to only include external accounts of the given provider and provider user ID.
     */
    public function scopeByProviderUserId(Builder $query, ExternalService $provider, string $providerUserId): void
    {
        $query->where('provider', $provider)
            ->where('provider_user_id', $providerUserId);
    }
}
