<?php

use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

// Наявні маршрути каталогу лабораторної №3
Route::get('/catalog', [CatalogController::class, 'index'])->name('api.catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('api.catalog.show');

// Ресурсні маршрути для продуктів і категорій (Лабораторна робота №4)
Route::apiResource('products', ProductController::class)->names('api.products');
Route::apiResource('categories', CategoryController::class)->names('api.categories');
