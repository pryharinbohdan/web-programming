<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CatalogService;

class HomeController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    public function index()
    {
        return view('home', [
            'featuredItems' => $this->catalogService->featured(3),
            'categories' => $this->catalogService->categories(),
            'operations' => Product::OPERATIONS,
            'productsCount' => $this->catalogService->count(),
        ]);
    }
}
