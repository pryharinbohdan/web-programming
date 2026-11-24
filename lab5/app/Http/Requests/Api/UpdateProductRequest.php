<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'slug' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product),
            ],
            'category_id' => ['sometimes', 'required', 'integer', 'exists:categories,id'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0', 'max:9999999999.99', 'decimal:0,2'],
            'operation' => ['sometimes', 'string', Rule::in(Product::OPERATIONS)],
            'currency' => ['sometimes', 'string', 'max:10'],
            'short_description' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'image' => ['sometimes', 'nullable', 'string'],
            'contact_name' => ['sometimes', 'nullable', 'string'],
            'contact_phone' => ['sometimes', 'nullable', 'string', 'max:32'],
            'contact_email' => ['sometimes', 'nullable', 'email'],
            'status' => ['sometimes', 'nullable', 'string', 'max:40'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'attributes' => ['sometimes', 'array'],
            'attributes.*' => ['sometimes'],
        ];
    }
}
