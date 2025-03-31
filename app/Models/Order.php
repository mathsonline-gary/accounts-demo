<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'item_id',
        'item_price',
        'sales_tax',
        'referral_code',
        'referral_code_type',
        'referral_code_validation_error',
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
            'item_id' => 'integer',
            'item_price' => 'float',
            'sales_tax' => 'float',
            'type' => OrderType::class,
            'status' => OrderStatus::class,
            'referral_code_type' => ReferralCodeType::class,
            'paid_at' => 'datetime',
            'is_hidden' => 'boolean',
        ];
    }

    /**
     * Get the item associated with the order. i.e. plan.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'item_id', 'id');
    }

    /**
     * Get the creator associated with the order.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * Get the recipient associated with the order.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
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
