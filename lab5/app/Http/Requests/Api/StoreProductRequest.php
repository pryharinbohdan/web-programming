<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:255', 'unique:products,slug'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99', 'decimal:0,2'],
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

    public function messages(): array
    {
        return [
            'title.required' => 'Назва продукту обов’язкова.',
            'slug.unique' => 'Продукт із таким slug уже існує.',
            'category_id.exists' => 'Обрана категорія не знайдена.',
            'price.min' => 'Ціна не може бути від’ємною.',
            'price.decimal' => 'Ціна повинна містити від 0 до 2 десяткових знаків.',
        ];
    }
}
