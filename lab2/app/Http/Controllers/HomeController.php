<?php

namespace App\Http\Controllers;

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
        ]);
    }
}
