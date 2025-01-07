<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SelectOptionController;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

# Authentication
require __DIR__ . '/modules/auth.php';

Route::middleware(['auth'])->group(function() {
    # Dashboard
    Route::name('dashboard.')->controller(DashboardController::class)->group(function() {
        Route::post('/change-role', 'changeRole')->name('changeRole');
    });
    Route::middleware(['auth'])->group(function() {
    // Route::middleware(['hak_akses_menu'])->group(function() {
        # Dashboard
        Route::name('dashboard.')->controller(DashboardController::class)->group(function() {
            Route::get('/dashboard', 'index')->name('index');
            // Route::post('/change-role', 'changeRole')->name('changeRole');
        });

        require __DIR__ . '/modules/management-system.php';
        require __DIR__ . '/modules/masterdata.php';
        require __DIR__ . '/modules/management-data-product.php';
    });
});

Route::prefix('options')->name('options.')->controller(SelectOptionController::class)->group(function() {
    Route::post('/roles', 'getRoles')->name('getRoles');
    Route::post('/menus', 'getMenus')->name('getMenus');
    Route::post('/main-menus', 'getMainMenus')->name('getMainMenus');
    Route::post('/categories', 'getCategories')->name('getCategories');
    Route::post('/brands', 'getBrands')->name('getBrands');
});

// Route::fallback(function() {
//     return redirect()->route('dashboard.index');
// });
