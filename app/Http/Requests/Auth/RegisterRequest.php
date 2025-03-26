<?php

namespace App\Http\Requests\Auth;

use App\Enums\Brand;
use App\Http\Requests\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'brand_id' => ['required', 'integer', Rule::in(Brand::cases())],
            'type' => ['required', 'string', Rule::in(['customer', 'school'])],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
            'home_phone' => ['nullable', 'string', 'max:50'],
            'mobile_phone' => ['nullable', 'string', 'max:50'],
            'address_line_1' => ['nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'address_city' => ['nullable', 'string', 'max:100'],
            'address_state' => ['nullable', 'string', 'max:100'],
            'address_postal_code' => ['nullable', 'string', 'max:50'],
            'address_country' => ['nullable', 'string', 'max:2'],
            'password' => [
                'required',
                'string',
                'min:8',
                Password::default(),
                'confirmed',
            ],
        ];
    }
}
