<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripeCheckout extends Model
{
    protected $fillable = [
        'order_id',
        'stripe_checkout_session_id',
        'stripe_checkout_session_client_secret',
        'stripe_customer_id',
        'stripe_subscription_id',
        'stripe_invoice_id',
        'status',
        'payment_status',
        'expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    public function casts(): array
    {
        return [
            'order_id' => 'integer',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the order that the checkout belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
