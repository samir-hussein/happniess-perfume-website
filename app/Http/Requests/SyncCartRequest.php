<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SyncCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (count($this->products ?? []) > 100) {
            $validator = \Illuminate\Support\Facades\Validator::make([], []);
            $validator->errors()->add('products', "You can't add more than 100 products");
            $this->failedValidation($validator);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "products" => "required|array|max:100",
            "products.*.product_id" => "required|exists:products,id",
            "products.*.size" => "required|exists:product_sizes,size",
            "products.*.quantity" => "required|numeric|min:1|max:100",
        ];
    }

    public function messages(): array
    {
        return [
            "products.max" => "You can't add more than 100 products to the cart",
            "products.*.product_id.exists" => "Product not found",
            "products.*.size.exists" => "Size not found",
            "products.*.quantity.min" => "Quantity must be at least 1",
            "products.*.quantity.max" => "Quantity must be at most 100",
        ];
    }
}
