<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'cross_price' => 'nullable|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            // 'allow_negative_stock' => 'nullable|boolean',
            // 'has_varient' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
            'product_image' => 'required|array',
            'product_image.*' => 'mimes:jpg,jpeg,png,bmp,gif,svg|max:2048',

            // 'varients' => 'required_if:has_varient,true|array',
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Category is required.',
            'brand_id.required' => 'Brand is required.',
            'name.required' => 'Product name is required.',
            'selling_price.required' => 'Selling price is required.',
            'sku.unique' => 'SKU must be unique.',
            'product_image.*.mimes' => 'Only image files (jpg, jpeg, png, bmp, gif, svg) are allowed.',
            'product_image.*.max' => 'Each image must be less than 2MB.',
        ];
    }
}