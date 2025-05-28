<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetCartProductsRequest extends FormRequest
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
            "user_id" => "exists:clients,id|required_without:products",
            "products" => "array|required_without:user_id",
            "products.*.product_id" => "required|exists:products,id",
            "products.*.size" => "required|exists:product_sizes,size",
            "products.*.quantity" => "required|numeric|min:1",
        ];
    }

    public function messages(): array
    {
        return [
            "user_id.exists" => "User not found",
            "products.*.product_id.exists" => "Product not found",
            "products.*.size.exists" => "Size not found",
            "products.*.quantity.min" => "Quantity must be at least 1",
        ];
    }
}
