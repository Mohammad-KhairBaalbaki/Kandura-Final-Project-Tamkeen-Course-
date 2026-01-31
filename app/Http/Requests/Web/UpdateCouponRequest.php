<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCouponRequest extends FormRequest
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
        $coupon = $this->route('coupon');
        $couponId = $coupon ? $coupon->id : null;

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('coupons', 'code')->ignore($couponId),
            ],
            'validate_from' => 'required|date',
            'validate_until' => 'required|date|after_or_equal:validate_from',
            'is_percentage' => 'required|boolean',
            'amount' => [
                'required',
                'numeric',
                'min:0',
                Rule::when($this->boolean('is_percentage'), 'max:100'),
            ],
            'is_active' => 'required|boolean',
            'order_limit_amount' => 'numeric|min:0',
            'general_limit' => 'integer|min:0',
        ];
    }
}
