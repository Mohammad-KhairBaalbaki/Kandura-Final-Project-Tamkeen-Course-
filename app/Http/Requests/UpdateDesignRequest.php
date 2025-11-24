<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateDesignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::id() == $this->design->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'array',
            'name.en' => 'string',
            'name.ar' => 'string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'numeric',
            'measurements' => 'array',
            'measurements.*' => 'exists:measurements,id',
            'images' => 'array',
            'images.*' => 'mime:png,jpg,jpeg',
            'design_options' => 'array',
            'design_options.*' => 'exists:design_options,id',
        ];
    }

    public function messages(): array
    {
        return [

            // name
            'name.array' => 'Name must be an array.',
            'name.en.string' => 'The English name must be a string.',
            'name.ar.string' => 'The Arabic name must be a string.',

            // description
            'description.array' => 'Description must be an array.',
            'description.en.string' => 'The English description must be a string.',
            'description.ar.string' => 'The Arabic description must be a string.',

            // price
            'price.numeric' => 'Price must be a numeric value.',

            // measurements
            'measurements.array' => 'Measurements must be an array.',
            'measurements.*.exists' => 'One or more measurements do not exist in our records.',

            // images
            'images.array' => 'Images must be sent as an array.',
            'images.*.mime' => 'Each file must be an image of type: png, jpg, jpeg.',

            // designOptions
            'design_options.array' => 'Design options must be an array.',
            'design_options.*.exists' => 'One or more selected design options do not exist.',
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
