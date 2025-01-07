<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManagementSystem\MenuHakAksesController;
use App\Http\Controllers\ManagementSystem\ManagementMenuController;
use App\Http\Controllers\ManagementSystem\ManagementRoleController;
use App\Http\Controllers\ManagementSystem\ManagementUserController;
use App\Http\Controllers\ManagementSystem\ManagementPermissionController;

# Management User
Route::prefix('management-user')->name('management-user.')->controller(ManagementUserController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{id}', 'detailJson')->name('detailJson');
    Route::post('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('destroy');
});

# Management Role
Route::prefix('management-role')->name('management-role.')->controller(ManagementRoleController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{id}', 'detailJson')->name('detailJson');
    Route::post('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('destroy');
});

# Management Permissions
Route::prefix('management-permission')->name('management-permission.')->controller(ManagementPermissionController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{id}', 'detailJson')->name('detailJson');
    Route::post('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('destroy');
});

# Management Menu
Route::prefix('management-menu')->name('management-menu.')->controller(ManagementMenuController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/store', 'store')->name('store');
    Route::post('/detail/json/{id}', 'detailJson')->name('detailJson');
    Route::post('/update/{id}', 'update')->name('update');
    Route::delete('/delete/{id}', 'destroy')->name('destroy');
});

# Menu dan Hak Akses
Route::prefix('menu-hak-akses')->name('menu-hak-akses.')->controller(MenuHakAksesController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/fetch-menus', 'fetchMenusByRole')->name('fetch-menus');
    Route::post('/update-hak-akses', 'update')->name('update');
});
