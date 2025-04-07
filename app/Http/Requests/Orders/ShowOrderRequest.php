<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class ShowOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'include' => ['nullable', 'array'],
            'include.*' => ['string', Rule::in(['creator'])],
        ];
    }
}
