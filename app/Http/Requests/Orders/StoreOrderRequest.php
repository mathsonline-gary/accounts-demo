<?php

namespace App\Http\Requests\Orders;

use App\Enums\OrderType;
use App\Models\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', 'integer', Rule::in(OrderType::cases())],
            'plan_id' => ['required', 'integer', Rule::exists(Plan::class, 'id')],
            'renewal_coupon_code' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string'],
            'nonce_code' => ['nullable', 'string'],
        ];
    }
}
