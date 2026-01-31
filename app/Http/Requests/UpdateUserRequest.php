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

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'You are not allowed to perform this action.',
            'data' => null,
        ], 401));
    }
}
