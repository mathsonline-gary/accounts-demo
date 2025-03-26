<?php

namespace App\Models;

use App\Enums\PlanType;
use App\ValueObjects\BillingPeriod;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'brand_id',
        'stripe_price_id',
        'code',
        'description',
        'price_original',
        'price',
        'price_saved',
        'is_recurring',
        'period_in_months',
        'extra_months',
        'user_limit',
        'type',
        'currency',
    ];

    public function casts(): array
    {
        return [
            'type' => PlanType::class,
            'is_recurring' => 'boolean',
            'price' => 'float',
            'price_original' => 'float',
            'price_saved' => 'float',
            'period_in_months' => 'integer',
            'extra_months' => 'integer',
            'user_limit' => 'integer',
        ];
    }

    /**
     * Interact with the plan's billing period.
     */
    public function billingPeriod(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => new BillingPeriod(
                count: $attributes['period_in_months'] - $attributes['extra_months'],
                interval: BillingPeriod::INTERVAL_MONTH,
                extraCount: $attributes['extra_months'],
            )
        );
    }
}
