<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DownloadInvoicesZipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_ids' => ['required', 'array', 'min:1'],
            'order_ids.*' => ['integer', 'exists:orders,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'order_ids.required' => 'Orders are required.',
            'order_ids.array' => 'Orders must be an array.',
            'order_ids.min' => 'At least one order must be selected.',
            'order_ids.*.integer' => 'Each order id must be a valid number.',
            'order_ids.*.exists' => 'One or more selected orders do not exist.',
        ];
    }
}
