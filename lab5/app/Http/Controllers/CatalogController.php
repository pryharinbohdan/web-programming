<?php

namespace App\Http\Controllers;

use App\Data\CatalogFilterData;
use App\Http\Requests\CatalogFilterRequest;
use App\Models\Product;
use App\Services\CatalogService;

class CatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    // Список продуктів з пошуком, фільтрами, сортуванням і пагінацією
    public function index(CatalogFilterRequest $request)
    {
        $filters = CatalogFilterData::fromArray($request->validated());

        return view('catalog.index', [
            'products' => $this->catalogService->paginate($filters),
            'filters' => $filters,
            'categories' => $this->catalogService->categories(),
            'operations' => Product::OPERATIONS,
        ]);
    }

    // Сторінка продукту; невідомий slug → ModelNotFoundException → оформлена 404
    public function show(string $slug)
    {
        return view('catalog.show', [
            'product' => $this->catalogService->findBySlug($slug),
        ]);
    }
}
