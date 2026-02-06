<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsNotificationsRequest extends FormRequest
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
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer'],
            'enabled_permissions' => ['nullable', 'array'],
            'enabled_permissions.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'permissions.required' => 'Permissions are required.',
            'permissions.array' => 'Permissions must be an array.',
            'permissions.*.integer' => 'Each permission must be a valid id.',
            'enabled_permissions.array' => 'Enabled permissions must be an array.',
            'enabled_permissions.*.integer' => 'Each enabled permission must be a valid id.',
        ];
    }
}
