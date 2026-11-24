<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::view('/admin', 'admin.index', [
    'screen' => 'overview',
    'title' => 'Огляд каталогу',
    'description' => 'Кількість позицій і категорій.',
])->name('admin.overview');

Route::view('/admin/products', 'admin.index', [
    'screen' => 'products',
    'title' => 'Позиції каталогу',
    'description' => 'Список товарів із категоріями, способом угоди та ціною.',
])->name('admin.products');

Route::view('/admin/categories', 'admin.index', [
    'screen' => 'categories',
    'title' => 'Категорії',
    'description' => 'Перелік категорій каталогу техніки.',
])->name('admin.categories');