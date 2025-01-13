<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SelectOptionController;
use App\Http\Controllers\DataProduct\ProductController;

Route::controller(LandingController::class)->group(function() {
    Route::get('/', 'index')->name('landing-index');
    
    Route::middleware(['auth'])->group(function() {
        Route::get('/profile', 'profileView')->name('profileView');
        Route::post('/profile-address/{id}', 'updateProfileAddress')->name('updateProfileAddress');
        Route::post('/profile/detail/json/{id}', 'detailJson')->name('detailJson');
        Route::post('/payment', 'payment')->name('payment');
        Route::get('/payment/{id}', 'handleOrderCallback')->name('handleOrderCallback');
    });
});

Route::prefix('product')->name('product.')->controller(ProductController::class)->group(function() {
    Route::get('/detail/json/{slug}', 'detailJson')->name('detailJson');
});

# Authentication
require __DIR__ . '/modules/auth.php';

Route::middleware(['auth'])->group(function() {
    Route::middleware(['check_role'])->group(function() {
        # Dashboard
        Route::name('dashboard.')->controller(DashboardController::class)->group(function() {
            Route::post('/change-role', 'changeRole')->name('changeRole');
        });
    
        require __DIR__ . '/modules/management-data-product.php';
        require __DIR__ . '/modules/keuangan.php';
        require __DIR__ . '/modules/management-system.php';
        require __DIR__ . '/modules/masterdata.php';
    
        Route::middleware(['hak_akses_menu'])->group(function() {
            # Dashboard
            Route::name('dashboard.')->controller(DashboardController::class)->group(function() {
                Route::get('/dashboard', 'index')->name('index');
            });
        });
    });
});

Route::prefix('options')->name('options.')->controller(SelectOptionController::class)->group(function() {
    Route::post('/roles', 'getRoles')->name('getRoles');
    Route::post('/menus', 'getMenus')->name('getMenus');
    Route::post('/main-menus', 'getMainMenus')->name('getMainMenus');
    Route::post('/categories', 'getCategories')->name('getCategories');
    Route::post('/brands', 'getBrands')->name('getBrands');
    Route::post('/provinsi', 'getProvinsi')->name('getProvinsi');
    Route::post('/kota', 'getKota')->name('getKota');
    Route::post('/kecamatan', 'getKecamatan')->name('getKecamatan');
    Route::post('/kelurahan', 'getKelurahan')->name('getKelurahan');
});

// Route::fallback(function() {
//     return redirect()->route('dashboard.index');
// });
