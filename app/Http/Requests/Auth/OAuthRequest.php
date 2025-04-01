<?php

namespace App\Http\Requests\Auth;

use App\Enums\Brand;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OAuthRequest extends FormRequest
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
            'code' => ['required', 'string'],
        ];
    }
}
