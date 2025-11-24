<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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
            'city_id' => ['required', 'exists:cities,id'],
            'street' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'details' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.required' => 'The city field is required.',
            'city_id.exists' => 'The selected city does not exist.',

            'street.required' => 'The street field is required.',
            'street.string' => 'The street must be a valid string.',

            'latitude.numeric' => 'Latitude must be a numeric value.',
            'longitude.numeric' => 'Longitude must be a numeric value.',

            'details.string' => 'Details must be a valid string.',
        ];
    }
}
