<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChargeWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function messages(): array
    {
        return [
            'identifier.required' => 'Email or phone is required.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a number.',
            'amount.min' => 'Amount must be at least 0.01.',
        ];
    }
}
