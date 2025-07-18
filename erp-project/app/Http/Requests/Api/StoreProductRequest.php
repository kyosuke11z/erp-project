<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
        // The validation rules should reflect the API's public contract.
        // We validate the keys that the client is expected to send.
        return [
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'category_id' => 'required|integer|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }

    /**
     * Get the validated data ready for creating the model.
     * This method acts as a bridge between the API contract and the database schema.
     *
     * @return array
     */
    public function toModelData(): array
    {
        $validated = $this->validated();
        return [
            'name' => $validated['product_name'],
            'sku' => $validated['sku'],
            'category_id' => $validated['category_id'],
            'selling_price' => $validated['price'],
            'quantity' => $validated['stock'],
        ];
    }
}