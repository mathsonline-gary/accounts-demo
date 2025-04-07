<?php

namespace App\Http\Requests\OrderCheckouts;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderCheckoutRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'url_on_completion' => ['required', 'url'],
        ];
    }
}
