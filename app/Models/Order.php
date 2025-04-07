<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentGateway;
use App\Enums\ReferenceCodeType;
use App\Traits\HasBrand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasBrand;

    protected $fillable = [
        'uuid',
        'brand_id',
        'type_id',
        'creator_id',
        'recipient_id',
        'recipient_email',
        'recipient_first_name',
        'recipient_last_name',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'billing_phone',
        'item_id',
        'amount_subtotal',
        'amount_tax',
        'reference_code',
        'reference_code_type_id',
        'reference_code_validation_error',
        'status',
        'paid_at',
        'paid_via',
        'is_hidden',
        'source',
    ];

    /**
     * Get the attributes that should be cast.
     */
    public function casts(): array
    {
        return [
            'brand_id' => 'integer',
            'type_id' => 'integer',
            'creator_id' => 'integer',
            'recipient_id' => 'integer',
            'item_id' => 'integer',
            'amount_subtotal' => 'float',
            'amount_tax' => 'float',
            'status' => OrderStatus::class,
            'reference_code_type_id' => 'integer',
            'paid_at' => 'datetime',
            'paid_via' => PaymentGateway::class,
            'is_hidden' => 'boolean',
        ];
    }

    /**
     * Interact with the type attribute.
     */
    public function type(): Attribute
    {
        return Attribute::make(get: fn (mixed $value, array $attributes) => OrderType::from($attributes['type_id']));
    }

    /**
     * Interact with the reference code type attribute.
     */
    public function referenceCodeType(): Attribute
    {
        return Attribute::make(get: fn (mixed $value, array $attributes) => ReferenceCodeType::from($attributes['reference_code_type_id']));
    }

    /**
     * Interact with the amount total attribute.
     */
    public function amountTotal(): Attribute
    {
        return Attribute::make(get: fn () => $this->amount_subtotal + $this->amount_tax ?? 0);
    }

    /**
     * Interact with the recipient name attribute.
     */
    public function recipientName(): Attribute
    {
        return Attribute::make(get: fn () => "{$this->recipient_first_name} {$this->recipient_last_name}");
    }

    /**
     * Get the brand associated with the order.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
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
     * Get the Stripe checkout associated with the order.
     */
    public function stripeCheckout(): HasOne
    {
        return $this->hasOne(StripeCheckout::class, 'order_id', 'id');
    }

    /**
     * Scope a query to only include the order by UUID.
     */
    public function scopeByUuid(Builder $query, string $uuid): void
    {
        $query->where('uuid', $uuid);
    }

    /**
     * Scope a query to only include orders by status.
     */
    public function scopeByStatus(Builder $query, OrderStatus $status): void
    {
        $query->where('status', $status);
    }

    /**
     * Scope a query to only include orders by type.
     */
    public function scopeOfType(Builder $query, OrderType $type): void
    {
        $query->where('type_id', $type->value);
    }

    /**
     * Scope a query to only include orders of a given recipient email.
     */
    public function scopeByRecipientEmail(Builder $query, string $recipientEmail): void
    {
        $query->where('recipient_email', $recipientEmail);
    }

    /**
     * Check if the order status is pending.
     */
    public function isStatusPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    /**
     * Check if the order status is paid.
     */
    public function isStatusPaid(): bool
    {
        return $this->status === OrderStatus::PAID;
    }

    /**
     * Check if the order status is cancelled.
     */
    public function isStatusCancelled(): bool
    {
        return $this->status === OrderStatus::CANCELLED;
    }

    /**
     * Check if the order status is expired.
     */
    public function isStatusExpired(): bool
    {
        return $this->status === OrderStatus::EXPIRED;
    }

    /**
     * Check if the order type is new.
     */
    public function isTypeNew(): bool
    {
        return $this->type === OrderType::NEW;
    }

    /**
     * Check if the order type is renewal.
     */
    public function isTypeRenewal(): bool
    {
        return $this->type === OrderType::RENEWAL;
    }

    /**
     * Check if the order type is trial.
     */
    public function isTypeTrial(): bool
    {
        return $this->type === OrderType::TRIAL;
    }

    /**
     * Check if the order type is coupon redemption.
     */
    public function isTypeCouponRedemption(): bool
    {
        return $this->type === OrderType::COUPON_REDEMPTION;
    }

    /**
     * Check if the order type is gift.
     */
    public function isTypeGift(): bool
    {
        return $this->type === OrderType::GIFT;
    }

    /**
     * Check if the order type is offline.
     */
    public function isTypeOffline(): bool
    {
        return $this->type === OrderType::OFFLINE;
    }
}
