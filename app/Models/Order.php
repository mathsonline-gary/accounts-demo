<?php

namespace App\Models;

use App\Enums\Brand;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PaymentGateway;
use App\Enums\ReferenceCodeType;
use App\Traits\HasBrand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Interact with the brand attribute.
     */
    public function brand(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => Brand::from($attributes['brand_id']),
            set: null,
        );
    }

    /**
     * Interact with the type attribute.
     */
    public function type(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => OrderType::from($attributes['type_id']),
            set: null,
        );
    }

    /**
     * Interact with the reference code type attribute.
     */
    public function referenceCodeType(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => ReferenceCodeType::from($attributes['reference_code_type_id']),
            set: null,
        );
    }

    /**
     * Interact with the amount total attribute.
     */
    public function amountTotal(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->amount_subtotal + $this->amount_tax ?? 0,
        );
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
}
