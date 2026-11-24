<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:80', 'unique:categories,name'],
            'slug' => ['required', 'string', 'max:255', 'unique:categories,slug'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Назва категорії є обов’язковою.',
            'name.unique' => 'Категорія з такою назвою вже існує.',
            'slug.required' => 'Slug категорії є обов’язковим.',
            'slug.unique' => 'Такий slug категорії вже зайнятий.',
        ];
    }
}
