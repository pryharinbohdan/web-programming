<?php

namespace App\Services;

use App\Data\CatalogFilterData;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Спільна вибірка для HTML та JSON API. Faker тут не використовується:
 * дані генеруються під час наповнення бази (сидери), а запити їх лише читають.
 */
class CatalogService
{
    /** @return Collection<int, Product> */
    public function featured(int $limit = 3): Collection
    {
        return Product::query()
            ->with('category')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }

    public function paginate(CatalogFilterData $filters): LengthAwarePaginator
    {
        $query = Product::query()->with('category');

        if ($filters->q !== null) {
            $term = '%'.addcslashes($filters->q, '\\%_').'%';
            $query->where(function (Builder $q) use ($term): void {
                $q->where('title', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            });
        }

        if ($filters->category !== null) {
            $query->whereHas('category', fn (Builder $q) => $q->where('name', $filters->category));
        }

        if ($filters->operation !== null) {
            $query->where('operation', $filters->operation);
        }

        // Додатковий ключ id робить порядок стабільним при однакових цінах чи датах.
        match ($filters->sort) {
            'price_asc' => $query->orderBy('price')->orderBy('id'),
            'price_desc' => $query->orderByDesc('price')->orderBy('id'),
            default => $query->orderByDesc('published_at')->orderByDesc('id'),
        };

        return $query
            ->paginate($filters->perPage, ['*'], 'page', $filters->page)
            ->appends($filters->toQuery());
    }

    /** Повертає Product або кидає ModelNotFoundException (→ 404). */
    public function findBySlug(string $slug): Product
    {
        return Product::query()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /** @return Collection<int, Category> */
    public function categories(): Collection
    {
        return Category::query()->orderBy('id')->get();
    }

    public function count(): int
    {
        return Product::query()->count();
    }
}
