<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'uuid',
        'brand_id',
        'type',
        'stripe_checkout_session_id',
        'stripe_invoice_id',
        'stripe_subscription_id',
        'stripe_checkout_session_client_secret',
        'creator_id',
        'recipient_id',
        'recipient_email',
        'recipient_first_name',
        'recipient_last_name',
        'billing_address_1',
        'billing_address_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'billing_phone',
        'billing_name',
        'billing_email',
        'plan_id',
        'plan_price',
        'sales_tax',
        'referral_code',
        'referral_code_type',
        'status',
        'paid_at',
        'is_hidden',
    ];

    public function casts(): array
    {
        return [
            'brand_id' => 'integer',
            'recipient_id' => 'integer',
            'creator_id' => 'integer',
            'plan_id' => 'integer',
            'plan_price' => 'float',
            'sales_tax' => 'float',
            'type' => OrderType::class,
            'status' => OrderStatus::class,
            'referral_code_type' => ReferralCodeType::class,
            'paid_at' => 'datetime',
            'is_hidden' => 'boolean',
        ];
    }

    /**
     * Scope a query to only include orders of a given status.
     */
    public function scopeOfStatus(Builder $query, OrderStatus $status): void
    {
        $query->where('status', $status);
    }

    /**
     * Scope a query to only include orders of a given type.
     */
    public function scopeOfType(Builder $query, OrderType $type): void
    {
        $query->where('type', $type);
    }

    /**
     * Scope a query to only include orders of a given recipient ID.
     */
    public function scopeOfRecipientId(Builder $query, int $recipientId): void
    {
        $query->where('recipient_id', $recipientId);
    }
}
