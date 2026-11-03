<?php

namespace App\Http\Requests;

use App\Data\CatalogFilterData;
use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CatalogFilterRequest extends FormRequest
{
    /** Якщо HTML-форма некоректна — повернення до каталогу з повідомленнями біля полів. */
    protected $redirectRoute = 'catalog.index';

    public function authorize(): bool
    {
        return true; // публічне читання каталогу
    }

    /** Для /api/* завжди JSON-відповідь 422 (message + errors). */
    public function expectsJson(): bool
    {
        return $this->is('api/*') || parent::expectsJson();
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', Rule::exists('categories', 'name')],
            'operation' => ['nullable', 'string', Rule::in(Product::OPERATIONS)],
            'sort' => ['nullable', 'string', Rule::in(array_keys(CatalogFilterData::SORTS))],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in(CatalogFilterData::PER_PAGE_OPTIONS)],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'q.string' => 'Пошуковий запит має бути текстом.',
            'q.max' => 'Пошуковий запит не може бути довшим за 100 символів.',
            'category.string' => 'Категорія має бути текстом.',
            'category.exists' => 'Обрана категорія не існує.',
            'operation.in' => 'Недопустимий спосіб угоди.',
            'sort.in' => 'Недопустиме сортування. Доступно: newest, price_asc, price_desc.',
            'page.integer' => 'Номер сторінки має бути цілим числом.',
            'page.min' => 'Номер сторінки має бути не меншим за 1.',
            'per_page.integer' => 'Кількість на сторінці має бути цілим числом.',
            'per_page.in' => 'Кількість на сторінці може дорівнювати 9, 12 або 24.',
        ];
    }
}
