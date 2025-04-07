<?php

namespace App\Http\Resources;

use App\Enums\PaymentGateway;
use App\Models\StripeCheckout;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource instanceof StripeCheckout) {
            return [
                'gateway' => PaymentGateway::STRIPE->toString(),
                'stripe_checkout_session_id' => $this->stripe_checkout_session_id,
                'stripe_checkout_session_client_secret' => $this->stripe_checkout_session_client_secret,
                'stripe_customer_id' => $this->stripe_customer_id,
                'status' => $this->status,
                'payment_status' => $this->payment_status,
                'expires_at' => $this->expires_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }

        return [];
    }
}
