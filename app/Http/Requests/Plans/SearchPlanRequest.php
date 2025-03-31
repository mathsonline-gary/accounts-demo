<?php

namespace App\Http\Requests\Plans;

use App\Enums\Brand;
use App\Enums\PlanType;
use App\Http\Requests\Request;
use App\Rules\ReferralNonce;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class SearchPlanRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'integer', Rule::in(Brand::cases())],
            'type' => ['nullable', 'integer', Rule::in(PlanType::cases())],
            'is_testing' => ['nullable', 'boolean'],
            'renewal_coupon_code' => [
                'nullable',
                'string',
                'prohibits:promo_code,nonce_code',
            ],
            'promo_code' => [
                'nullable',
                'string',
                'prohibits:renewal_coupon_code',
            ],
            'nonce_code' => [
                'nullable',
                'string',
                Rule::requiredIf(fn () => strtoupper($this->input('promo_code')) === 'ORIG'),
                new ReferralNonce,
            ],
            'pagination' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
        ];
    }
}
