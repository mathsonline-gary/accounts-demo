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
        'service',
        'service_account_id',
    ];

    public function casts(): array
    {
        return [
            'service' => ExternalService::class,
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
     * Scope a query to only include external accounts by service and service account ID.
     */
    public function scopeByServiceAccountId(Builder $query, ExternalService $service, string $serviceAccountId): void
    {
        $query->where('service', $service)
            ->where('service_account_id', $serviceAccountId);
    }

    /**
     * Scope a query to only include external accounts by service and user ID.
     */
    public function scopeByServiceAndUserId(Builder $query, ExternalService $service, int $userId): void
    {
        $query->where('service', $service)
            ->where('user_id', $userId);
    }
}
