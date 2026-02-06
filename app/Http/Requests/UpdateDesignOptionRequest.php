<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignOptionRequest extends FormRequest
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
                'in:color,dome,fabric,sleeve',
            ],
            'is_active' => ['boolean'],
            'name' => ['array'],
            'name.en' => [
                'string',
                Rule::unique('design_options', 'name->en')
                    ->where('type', $this->type)
                    ->whereNull('deleted_at')
                    ->ignore($this->designOption->id),
            ],
            'name.ar' => [
                'string',
                Rule::unique('design_options', 'name->ar')
                    ->where('type', $this->type)
                    ->whereNull('deleted_at')
                    ->ignore($this->designOption->id),
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'The type must be one of: color, dome, fabric, sleeve.',
            'is_active.boolean' => 'Status must be true or false.',
            'name.array' => 'Name must be an array.',
            'name.en.string' => 'The English name must be a string.',
            'name.en.unique' => 'The English name has already been taken for this type.',
            'name.ar.string' => 'The Arabic name must be a string.',
            'name.ar.unique' => 'The Arabic name has already been taken for this type.',
        ];
    }
}
