<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Keuangan\ManagementKeuanganController;

# Management Keuangan
Route::prefix('management-keuangan')->name('management-keuangan.')->controller(ManagementKeuanganController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
    Route::post('/detail/json/{id}', 'detailJson')->name('detailJson');
});

# Route with Check Hak Akses User
Route::middleware(['hak_akses_menu'])->group(function() {
    # Management Keuangan
    Route::prefix('management-keuangan')->name('management-keuangan.')->controller(ManagementKeuanganController::class)->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::post('/update/{id}', 'update')->name('update');
        Route::delete('/delete/{id}', 'destroy')->name('destroy');
    });
});