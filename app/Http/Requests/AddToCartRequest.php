<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ProductSize;

class AddToCartRequest extends FormRequest
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
            "product_id" => "required|exists:products,id",
            "size" => "required|exists:product_sizes,size,product_id," . $this->product_id,
            "quantity" => [
                "required",
                "numeric",
                "min:1",
                function ($attribute, $value, $fail) {
                    $productSize = ProductSize::where('product_id', $this->product_id)
                        ->where('size', $this->size)
                        ->first();
                    if (!$productSize) {
                        $fail('Invalid product size.');
                    } elseif ($value > $productSize->quantity) {
                        $fail('The requested quantity exceeds available stock (' . $productSize->quantity . ').');
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            "product_id.required" => "The product ID is required.",
            "product_id.exists" => "The product ID does not exist.",
            "size.required" => "The size is required.",
            "size.exists" => "The size does not exist.",
            "quantity.required" => "The quantity is required.",
            "quantity.numeric" => "The quantity must be a number.",
            "quantity.min" => "The quantity must be at least 1.",
            "quantity.max" => "The quantity must be at most 100.",
        ];
    }
}
