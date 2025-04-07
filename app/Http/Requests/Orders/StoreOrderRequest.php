<?php

namespace App\Http\Requests\Orders;

use App\Enums\Brand;
use App\Enums\OrderType;
use App\Enums\ReferenceCodeType;
use App\Rules\ReferenceNonce;
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
        // Change reference_code to uppercase if provided and not null
        if ($this->input('reference_code') !== null) {
            $this->merge([
                'reference_code' => strtoupper($this->input('reference_code')),
            ]);
        }

        // merge brand_id if user has logged in
        if (auth()->check()) {
            $this->merge([
                'brand_id' => auth()->user()->brand_id,
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
            'brand_id' => ['required', 'integer', Rule::in(Brand::cases())],
            'type_id' => ['required', 'integer', Rule::in(OrderType::cases())],
            'item_id' => ['required', 'integer'],
            'recipient_first_name' => ['required', 'string', 'max:255'],
            'recipient_last_name' => ['required', 'string', 'max:255'],
            'recipient_email' => ['required', 'email', 'max:255'],
            'reference_code' => [
                'nullable',
                'string',
                'required_with:reference_code_type',
            ],
            'reference_code_type_id' => [
                'nullable',
                'integer',
                'required_with:reference_code',
                Rule::in(ReferenceCodeType::cases()),
            ],
            'reference_nonce' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => $this->input('reference_code') === 'ORIG' && $this->input('reference_code_type_id') === ReferenceCodeType::PROMO->value),
                new ReferenceNonce,
            ],
            'include' => ['nullable', 'array'],
            'include.*' => ['string', Rule::in(['creator'])],
        ];
    }
}
