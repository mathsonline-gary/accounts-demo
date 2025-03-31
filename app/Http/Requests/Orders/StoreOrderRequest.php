<?php

namespace App\Http\Requests\Orders;

use App\Enums\OrderType;
use App\Enums\ReferralCodeType;
use App\Models\Plan;
use App\Rules\ReferralNonce;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Change referral_code to uppercase if provided and not null
        if ($this->input('referral_code') !== null) {
            $this->merge([
                'referral_code' => strtoupper($this->input('referral_code')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(OrderType::cases())],
            'item_id' => ['required', 'integer', Rule::exists(Plan::class, 'id')],
            'referral_code' => [
                'nullable',
                'string',
                'required_with:referral_code_type',
            ],
            'referral_code_type' => [
                'nullable',
                'integer',
                'required_with:referral_code',
                Rule::in(ReferralCodeType::cases()),
            ],
            'referral_nonce' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->input('referral_code') === 'ORIG' && $this->input('referral_code_type') === ReferralCodeType::PROMO->value),
                new ReferralNonce,
            ],
        ];
    }
}
