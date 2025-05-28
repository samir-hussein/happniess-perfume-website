<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
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
            'code' => 'required|string|exists:promotional_codes,code',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => __('Coupon code is required'),
            'code.string' => __('Coupon code must be a string'),
            'code.exists' => __('Invalid coupon code'),
        ];
    }
}
