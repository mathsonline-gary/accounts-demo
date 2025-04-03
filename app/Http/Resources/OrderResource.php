<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'type' => [
                'id' => $this->type_id,
                'name' => $this->type->toString(),
            ],
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->brand->toString(),
            ],

            'item' => $this->whenLoaded(
                'item',
                fn () => $this->item->toArray(),
                $this->item_id
            ),

            // Reference code
            'reference_code' => [
                'code' => $this->reference_code,
                'type' => $this->reference_code_type->toString(),
                'validation_error' => $this->reference_code_validation_error,
            ],
            'status' => $this->status,
            'billing_address' => [
                'line_1' => $this->billing_address_line_1,
                'line_2' => $this->billing_address_line_2,
                'city' => $this->billing_city,
                'state' => $this->billing_state,
                'postal_code' => $this->billing_postal_code,
                'country' => $this->billing_country,
            ],
            'recipient' => [
                'id' => $this->recipient_id,
                'email' => $this->recipient_email,
                'first_name' => $this->recipient_first_name,
                'last_name' => $this->recipient_last_name,
                'phone' => $this->whenLoaded('recipient', fn () => [
                    'home' => $this->recipient->home_phone,
                    'mobile' => $this->recipient->mobile_phone,
                ]),
            ],
            'amount' => [
                'subtotal' => $this->amount_subtotal,
                'tax' => $this->amount_tax,
                'total' => $this->amount_total,
            ],
            'paid_at' => $this->paid_at,
            'paid_via' => $this->paid_via?->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'checkout' => $this->whenLoaded('checkout', fn () => $this->checkout->toArray()),
            'payment' => $this->whenLoaded('payment', fn () => $this->payment->toArray()),
        ];
    }
}
