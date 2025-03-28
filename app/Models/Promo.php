<?php

namespace App\Models;

use App\Enums\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    protected $fillable = [
        'brand_id',
        'campaign_id',
        'code',
        'description',
        'expires_at',
        'redemption_count',
        'is_active',
    ];

    public function casts(): array
    {
        return [
            'brand_id' => 'integer',
            'campaign_id' => 'integer',
            'expires_at' => 'datetime',
            'redemption_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the campaign that the promo belongs to.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Scope a query to only include promos of the given brand.
     */
    public function scopeOfBrand(Builder $query, Brand $brand): void
    {
        $query->where('brand_id', $brand->value);
    }

    /**
     * Scope a query to only include promos with the given code.
     */
    public function scopeByCode(Builder $query, string $code): void
    {
        $query->where('code', $code);
    }

    /**
     * Scope a query to only include redeemable promos.
     */
    public function scopeRedeemable(Builder $query): void
    {
        $query->where('is_active', true)
            ->where('expires_at', '>', now());
    }

    /**
     * Check if the promo is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Check if the promo is expired.
     */
    public function isExpired(): bool
    {
        if ($this->expires_at === null) {
            return false;
        }

        return $this->expires_at < now();
    }

    /**
     * Check if the promo is redeemable.
     */
    public function isRedeemable(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }
}
