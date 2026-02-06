<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCouponRequest extends FormRequest
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
            'code' => 'required|string|max:255|unique:coupons,code',
            'validate_from' => 'required|date',
            'validate_until' => 'required|date|after_or_equal:validate_from',
            'is_percentage' => 'required|boolean',
            'amount' => [
                'required',
                'numeric',
                'min:1',
                Rule::when($this->boolean('is_percentage'), 'max:100'),
            ],
            'is_active' => 'required|boolean',
            'order_limit_amount' => 'required|numeric|min:0',
            'general_limit' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Code is required.',
            'code.string' => 'Code must be a valid string.',
            'code.max' => 'Code may not be greater than 255 characters.',
            'code.unique' => 'Code has already been taken.',
            'validate_from.required' => 'Start date is required.',
            'validate_from.date' => 'Start date must be a valid date.',
            'validate_until.required' => 'End date is required.',
            'validate_until.date' => 'End date must be a valid date.',
            'validate_until.after_or_equal' => 'End date must be after or equal to start date.',
            'is_percentage.required' => 'Discount type is required.',
            'is_percentage.boolean' => 'Discount type must be true or false.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be numeric.',
            'amount.min' => 'Amount must be at least 1.',
            'amount.max' => 'Percentage cannot be greater than 100.',
            'is_active.required' => 'Status is required.',
            'is_active.boolean' => 'Status must be true or false.',
            'order_limit_amount.required' => 'Order limit amount is required.',
            'order_limit_amount.numeric' => 'Order limit amount must be numeric.',
            'order_limit_amount.min' => 'Order limit amount must be at least 0.',
            'general_limit.required' => 'General limit is required.',
            'general_limit.integer' => 'General limit must be an integer.',
            'general_limit.min' => 'General limit must be at least 0.',
        ];
    }
}
