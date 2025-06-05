<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyNowRequest extends FormRequest
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
        ];
    }

    public function messages(): array
    {
        return [
            "product_id.required" => "The product ID is required.",
            "product_id.exists" => "The product ID does not exist.",
            "size.required" => "The size is required.",
            "size.exists" => "The size does not exist.",
        ];
    }
}
