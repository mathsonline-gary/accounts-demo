<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'brand_id' => $this->brand_id,
            'type' => $this->type,
            'code' => $this->code,
            'description' => $this->description,
            'price_original' => $this->price_original,
            'price' => $this->price,
            'price_saved' => $this->price_saved,
            'billing_period' => $this->billing_period,
            'currency' => $this->currency,
            'is_recurring' => $this->is_recurring,
            'student_limit' => $this->student_limit,
        ];
    }
}
