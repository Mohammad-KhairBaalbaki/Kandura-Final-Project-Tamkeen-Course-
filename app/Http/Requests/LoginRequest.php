<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['nullable', 'email', 'exists:users,email', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'exists:users,phone', 'required_without:email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required_without' => 'Email is required when phone is not provided.',
            'email.email' => 'Email must be a valid email address.',
            'email.exists' => 'Email does not exist in our records.',
            'phone.required_without' => 'Phone is required when email is not provided.',
            'phone.string' => 'Phone must be a valid string.',
            'phone.exists' => 'Phone does not exist in our records.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid string.',
        ];
    }
}
