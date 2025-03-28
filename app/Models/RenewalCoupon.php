<?php

namespace App\Models;

use App\Enums\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenewalCoupon extends Model
{
    protected $fillable = [
        'brand_id',
        'campaign_id',
        'code',
        'note',
        'authorized_redeemer_id',
        'expires_at',
        'redeemed_at',
    ];

    public function casts(): array
    {
        return [
            'brand_id' => 'integer',
            'campaign_id' => 'integer',
            'authorized_redeemer_id' => 'integer',
            'expires_at' => 'datetime',
            'redeemed_at' => 'datetime',
        ];
    }

    /**
     * Get the campaign that the renewal coupon belongs to.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    /**
     * Scope a query to only include renewal coupons of the given brand.
     */
    public function scopeOfBrand(Builder $query, Brand $brand): void
    {
        $query->where('brand_id', $brand->value);
    }

    /**
     * Scope a query to only include renewal coupons with the given code.
     */
    public function scopeOfCode(Builder $query, string $code): void
    {
        $query->where('code', $code);
    }

    /**
     * Scope a query to only include redeemable renewal coupons.
     */
    public function scopeRedeemable(Builder $query): void
    {
        $query->where('expires_at', '>', now())
            ->whereNull('redeemed_at');
    }
}
