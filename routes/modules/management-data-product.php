<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProduct\BrandController;
use App\Http\Controllers\DataProduct\ProductController;
use App\Http\Controllers\DataProduct\CategoryController;

# Management Category
Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{slug}', 'detailJson')->name('detailJson');
    Route::post('/update/{slug}', 'update')->name('update');
    Route::delete('/delete/{slug}', 'destroy')->name('destroy');
});

# Management Brand
Route::prefix('brand')->name('brand.')->controller(BrandController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{slug}', 'detailJson')->name('detailJson');
    Route::post('/update/{slug}', 'update')->name('update');
    Route::delete('/delete/{slug}', 'destroy')->name('destroy');
});

# Management Product
Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/detail/json/{slug}', 'detailJson')->name('detailJson');
    Route::get('/edit/{slug}', 'edit')->name('edit');
    Route::post('/update/{slug}', 'update')->name('update');
    Route::delete('/delete/{slug}', 'destroy')->name('destroy');
});