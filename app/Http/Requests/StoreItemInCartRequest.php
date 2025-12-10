<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemInCartRequest extends FormRequest
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
            'design_id' => 'required|exists:designs,id',
            'design_option_ids' => 'required|array',
            'design_option_ids.*' => 'exists:design_options,id',
            'measurement_id' => 'required|exists:measurements,id',
            'quantity' => 'required|numeric|min:1',
        ];
    }
}
