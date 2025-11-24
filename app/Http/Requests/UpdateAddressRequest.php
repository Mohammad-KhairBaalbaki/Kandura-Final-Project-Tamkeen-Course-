<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::id() == $this->address->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city_id' => ['exists:cities,id'],
            'street' => ['string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'details' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.exists' => 'The selected city does not exist.',

            'street.string' => 'Street must be a valid string.',

            'latitude.numeric' => 'Latitude must be a numeric value.',
            'longitude.numeric' => 'Longitude must be a numeric value.',

            'details.string' => 'Details must be a valid string.',
        ];
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'message' => 'You are not allowed to perform this action.',
            'data' => null,
        ], 403));
    }
}
