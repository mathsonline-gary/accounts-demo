<?php

namespace App\Http\Requests\Plans;

use App\Enums\Brand;
use App\Enums\PlanType;
use App\Http\Requests\Request;
use Illuminate\Validation\Rule;

class SearchPlanRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'integer', Rule::in(Brand::cases())],
            'type' => ['nullable', 'integer', Rule::in(PlanType::cases())],
            'is_testing' => ['nullable', 'boolean'],
            'renewal_coupon_code' => ['nullable', 'string'],
            'promo_code' => ['nullable', 'string'],
            'nonce_code' => ['nullable', 'string'],
            'pagination' => ['nullable', 'boolean'],
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
        ];
    }
}
