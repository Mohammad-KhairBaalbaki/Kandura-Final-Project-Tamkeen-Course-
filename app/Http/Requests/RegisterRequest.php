<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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

            'name' => ['required','string'],
            'email'=> ['required','email','unique:users,email'],
            'phone'=> ['required','string','unique:users,phone'],
            'password'=> ['required','string'],
        ];
    }

    public function messages(): array
{
    return [
        'name.required' => 'Name is required.',
        'name.string' => 'Name must be text.',

        'email.required' => 'Email is required.',
        'email.email' => 'Please provide a valid email.',
        'email.unique' => 'Email already exists.',

        'phone.required' => 'Phone number is required.',
        'phone.string' => 'Phone must be valid.',
        'phone.unique' => 'Phone number already exists.',

        'password.required' => 'Password is required.',
        'password.string' => 'Password must be text.',
    ];
}
}
