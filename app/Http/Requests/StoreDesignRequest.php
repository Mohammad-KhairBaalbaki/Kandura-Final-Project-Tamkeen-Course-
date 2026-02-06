<?php

namespace App\Http\Requests;

use App\Models\DesignOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreDesignRequest extends FormRequest
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
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.ar' => 'required|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'required|numeric',
            'measurements' => 'required|array',
            'measurements.*' => 'required|exists:measurements,id',
            'images' => 'required|array',
            'images.*' => 'required|image|mimes:png,jpg,jpeg',
            'design_options' => ['required', 'array', 'min:1'],
            'design_options.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('design_options', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $ids = $this->input('design_options', []);
                if (! is_array($ids) || empty($ids)) {
                    return;
                }

                // Option 1 (dynamic): require at least one from EVERY type that exists in DB (you said there are 4)
                $requiredTypes = DesignOption::query()
                    ->where('is_active', true)
                    ->whereNull('deleted_at')
                    ->distinct()
                    ->orderBy('type')
                    ->pluck('type')
                    ->all();

                // Get types for selected IDs
                $selectedTypes = DesignOption::whereIn('id', $ids)
                    ->where('is_active', true)
                    ->whereNull('deleted_at')
                    ->pluck('type')
                    ->unique()
                    ->values()
                    ->all();

                $missing = array_values(array_diff($requiredTypes, $selectedTypes));

                if (! empty($missing)) {
                    $validator->errors()->add(
                        'design_options',
                        'You must select at least one design option from each type. Missing: '.implode(', ', $missing)
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [

            // name
            'name.required' => 'Name is required.',
            'name.array' => 'Name must be an array.',
            'name.en.required' => 'The English name is required.',
            'name.en.string' => 'The English name must be a string.',
            'name.ar.required' => 'The Arabic name is required.',
            'name.ar.string' => 'The Arabic name must be a string.',

            // description
            'description.array' => 'Description must be an array.',
            'description.en.string' => 'The English description must be a string.',
            'description.ar.string' => 'The Arabic description must be a string.',

            // price
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a numeric value.',

            // measurements
            'measurments.required' => 'Measurements are required.',
            'measurments.array' => 'Measurements must be an array.',
            'measurements.*.required' => 'Each measurement value is required.',
            'measurements.*.exists' => 'One or more measurements do not exist in our records.',

            // images
            'images.required' => 'Images are required.',
            'images.array' => 'Images must be sent as an array.',
            'images.*.required' => 'Each image is required.',
            'images.*.mime' => 'Each file must be an image of type: png, jpg, jpeg.',

            // designOptions
            'design_options.required' => 'Design options are required.',
            'design_options.array' => 'Design options must be an array.',
            'design_options.min' => 'You must select at least :min options (one per type).',
            'design_options.*.exists' => 'One or more selected design options are invalid.',
            'design_options.*.distinct' => 'Duplicate design options are not allowed.',
        ];
    }
}
