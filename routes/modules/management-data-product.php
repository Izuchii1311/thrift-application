<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProduct\BrandController;
use App\Http\Controllers\DataProduct\ProductController;
use App\Http\Controllers\DataProduct\CategoryController;

# Management Category
Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/detail/json/{slug}', 'detailJson')->name('detailJson');
});

# Management Brand
Route::prefix('brand')->name('brand.')->controller(BrandController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/detail/json/{slug}', 'detailJson')->name('detailJson');
});

# Management Product
Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/update-price/{slug}', 'updatePrice')->name('updatePrice');
});

# Katalog Produk
Route::prefix('katalog-product')->name('katalog-product.')->controller(ProductController::class)->group(function() {
    Route::post('/json', 'indexKatalogJson')->name('indexKatalogJson');
});

# Route with Check Hak Akses User
Route::middleware(['hak_akses_menu'])->group(function() {
    # Management Category
    Route::prefix('category')->name('category.')->controller(CategoryController::class)->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{slug}', 'update')->name('update');
        Route::delete('/delete/{slug}', 'destroy')->name('destroy');
    });

    # Management Brand
    Route::prefix('brand')->name('brand.')->controller(BrandController::class)->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{slug}', 'update')->name('update');
        Route::delete('/delete/{slug}', 'destroy')->name('destroy');
    });

    # Management Product
    Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{slug}', 'edit')->name('edit');
        Route::post('/update/{slug}', 'update')->name('update');
        Route::delete('/delete/{slug}', 'destroy')->name('destroy');
    });

    # List Product
    Route::prefix('list-product')->name('list-product.')->controller(ProductController::class)->group(function() {
        Route::get('/', 'listProduct')->name('listProduct');
    });

    # Katalog Produk
    Route::prefix('katalog-product')->name('katalog-product.')->controller(ProductController::class)->group(function() {
        Route::get('/', 'indexKatalog')->name('indexKatalog');
        Route::post('/update/{slug}', 'updateKatalog')->name('updateKatalog');
    });
});