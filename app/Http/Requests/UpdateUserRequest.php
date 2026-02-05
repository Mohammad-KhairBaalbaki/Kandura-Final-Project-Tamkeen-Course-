<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
            'name' => ['string'],
            'email' => ['email', 'unique:users,email,'.$this->user->id],
            'phone' => ['string', 'unique:users,phone,'.$this->user->id],
            'is_active' => ['boolean'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'super_admin_password' => ['required_with:new_password','nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Name must be a valid string.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email has already been taken.',
            'phone.string' => 'Phone must be a valid string.',
            'phone.unique' => 'Phone has already been taken.',
            'is_active.boolean' => 'Status must be true or false.',
            'roles.array' => 'Roles must be an array.',
            'roles.*.string' => 'Each role must be a valid string.',
            'roles.*.exists' => 'One or more selected roles are invalid.',
            'new_password.string' => 'New password must be a string.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'New password confirmation does not match.',
            'super_admin_password.required_with' => 'Super admin password is required when setting a new password.',
            'super_admin_password.string' => 'Super admin password must be a string.',
        ];
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'You are not allowed to perform this action.',
            'data' => null,
        ], 401));
    }
}
