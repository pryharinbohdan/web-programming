<?php

namespace App\Http\Controllers;

use App\Services\CatalogService;

class CatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    // cписок усіх товарів каталогу
    public function index()
    {
        $items = $this->catalogService->all();

        return view('catalog.index', [
            'items' => $items,
        ]);
    }

    // сторінка конкретного товару
    public function show(string $slug)
    {
        $item = $this->catalogService->findBySlug($slug);

        //якщо не коректний slug помилка 404
        if (! $item) {
            abort(404);
        }

        return view('catalog.show', [
            'item' => $item,
        ]);
    }
}