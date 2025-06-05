<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlaceOrderRequest extends FormRequest
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
            'payment' => 'required|in:cash_on_delivery,card,wallet',
            'coupon' => 'nullable|exists:promotional_codes,code',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clients', 'phone')->ignore(request()->user()->id),
                'regex:/^01[0125][0-9]{8}$/',
            ],
            'approve_replacement_policy' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'payment.required' => __('Payment method is required'),
            'payment.in' => __('Invalid payment method'),
            'coupon.exists' => __('Invalid coupon code'),
            'name.required' => __('Name is required'),
            'name.string' => __('Name must be a string'),
            'name.max' => __('Name must not exceed 255 characters'),
            'address.required' => __('Address is required'),
            'address.string' => __('Address must be a string'),
            'address.max' => __('Address must not exceed 255 characters'),
            'city.required' => __('City is required'),
            'city.string' => __('City must be a string'),
            'city.max' => __('City must not exceed 255 characters'),
            'phone.required' => __('Phone number is required'),
            'phone.string' => __('Phone number must be a string'),
            'phone.max' => __('Phone number must not exceed 255 characters'),
            'phone.unique' => __('Phone number already exists'),
            'phone.regex' => __('Phone number is invalid'),
            'approve_replacement_policy.required' => __('You must agree to the return policy'),
        ];
    }

    public function attributes(): array
    {
        return [
            'payment' => __('Payment method'),
            'coupon' => __('Coupon code'),
            'name' => __('Name'),
            'address' => __('Address'),
            'city' => __('City'),
            'phone' => __('Phone number'),
            'approve_replacement_policy' => __('Approve replacement policy'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
