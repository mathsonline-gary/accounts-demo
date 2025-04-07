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
                'id' => $this->type,
                'name' => $this->type->toString(),
            ],
            'brand_id' => $this->brand_id,
            'item' => $this->whenLoaded(
                'item',
                fn () => $this->item->toArray(),
                $this->item_id
            ),
            'reference_code' => [
                'code' => $this->reference_code,
                'type' => $this->reference_code_type->toString(),
                'validation_error' => $this->reference_code_validation_error,
            ],
            'status' => $this->status,
            'billing' => [
                'address_line_1' => $this->billing_address_line_1,
                'address_line_2' => $this->billing_address_line_2,
                'address_city' => $this->billing_city,
                'address_state' => $this->billing_state,
                'address_postal_code' => $this->billing_postal_code,
                'address_country' => $this->billing_country,
                'phone' => $this->billing_phone,
            ],
            'recipient' => [
                'id' => $this->recipient_id,
                'email' => $this->recipient_email,
                'first_name' => $this->recipient_first_name,
                'last_name' => $this->recipient_last_name,
            ],
            'creator' => $this->whenLoaded(
                'creator',
                fn () => new UserResource($this->creator),
                $this->creator_id
            ),
            'amount' => [
                'subtotal' => $this->amount_subtotal,
                'tax' => $this->amount_tax,
                'total' => $this->amount_total,
            ],
            'source' => $this->source,
            'paid_at' => $this->paid_at,
            'paid_via' => $this->paid_via?->value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'checkout' => $this->whenLoaded('checkout', fn () => $this->checkout->toArray()),
            'payment' => $this->whenLoaded('payment', fn () => $this->payment->toArray()),
        ];
    }
}
