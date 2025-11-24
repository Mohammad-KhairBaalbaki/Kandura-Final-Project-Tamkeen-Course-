<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDesignOptionRequest extends FormRequest
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
            'type' => [
                'required',
                'in:color,dome,fabric,sleeve',
            ],
            'name' => ['required','array'],
            'name.en' => [
                'required',
                'string',
                Rule::unique('design_options', 'name->en')
                    ->where('type', $this->type)
            ],
            'name.ar' => [
                'required',
                'string',
                Rule::unique('design_options', 'name->ar')
                    ->where('type', $this->type)
            ],

        ];
    }

    public function messages(): array
    {
        return [
        'name.required' => 'The name field is required.',
        'name.en.required' => 'The English name is required.',
        'name.ar.required' => 'The Arabic name is required.',

        'type.required' => 'The type field is required.',
        'type.in' => 'The type must be one of: color, dome, fabric, sleeve.',
    ];
    }
}
