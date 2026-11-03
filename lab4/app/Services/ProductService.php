<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::query()->with('category')->latest('id')->paginate($perPage);
    }

    public function create(array $data): Product
    {
        // Заповнення системних значень за замовчуванням для обов'язкових колонок таблиці products
        $data['operation'] ??= Product::OPERATIONS[0];
        $data['currency'] ??= '₴';
        $data['short_description'] ??= $data['title'];
        $data['description'] ??= $data['title'];
        $data['image'] ??= 'images/catalog-atlas.png';
        $data['contact_name'] ??= 'Пригарін Богдан';
        $data['contact_phone'] ??= '+380 99 123 45 67';
        $data['contact_email'] ??= 'author@example.com';
        $data['status'] ??= 'В наявності';
        $data['published_at'] ??= now()->format('Y-m-d');
        $data['attributes'] ??= [];

        $product = Product::create($data);

        return $product->load('category');
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh()->load('category');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
