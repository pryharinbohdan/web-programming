<?php

namespace App\Services;

use App\Exceptions\CategoryNotEmpty;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Category::query()->orderBy('id')->paginate($perPage);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        if ($category->products()->exists()) {
            throw new CategoryNotEmpty();
        }

        $category->delete();
    }
}
