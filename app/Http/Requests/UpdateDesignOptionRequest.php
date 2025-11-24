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
            'name' => ['array'],
            'name.en' => [
                'string',
                Rule::unique('design_options', 'name->en')
                    ->where('type', $this->type)
                    ->ignore($this->designOption->id),
            ],
            'name.ar' => [
                'string',
                Rule::unique('design_options', 'name->ar')
                    ->where('type', $this->type)
                    ->ignore($this->designOption->id),
            ],


        ];
    }
}
