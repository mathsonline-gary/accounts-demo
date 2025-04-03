<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StripePayment extends Model
{
    protected $fillable = [
        'order_id',
        'stripe_payment_intent_id',
    ];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     */
    public function casts(): array
    {
        return [
            'order_id' => 'integer',
        ];
    }

    /**
     * Get the order that the payment belongs to.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
