<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        // Get the product ID from the route parameter
        $productId = $this->route('product')->id;

        return [
            'product_name' => 'sometimes|required|string|max:255',
            'sku' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ];
    }

    /**
     * Get the validated data ready for updating the model.
     *
     * @return array
     */
    public function toModelData(): array
    {
        $validated = $this->validated();
        return [
            'name' => $validated['product_name'] ?? null,
            'sku' => $validated['sku'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'selling_price' => $validated['price'] ?? null,
            'quantity' => $validated['stock'] ?? null,
        ];
    }
}