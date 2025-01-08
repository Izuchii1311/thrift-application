<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Masterdata\Lokasi\KotaController;
use App\Http\Controllers\Masterdata\Lokasi\ProvinsiController;
use App\Http\Controllers\Masterdata\Lokasi\KecamatanController;
use App\Http\Controllers\Masterdata\Lokasi\KelurahanController;

# Masterdata Provinsi
Route::prefix('masterdata-provinsi')->name('masterdata-provinsi.')->controller(ProvinsiController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
});

# Masterdata Kota
Route::prefix('masterdata-kota')->name('masterdata-kota.')->controller(KotaController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
});

# Masterdata Kecamatan
Route::prefix('masterdata-kecamatan')->name('masterdata-kecamatan.')->controller(KecamatanController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
});

# Masterdata Kelurahan
Route::prefix('masterdata-kelurahan')->name('masterdata-kelurahan.')->controller(KelurahanController::class)->group(function() {
    Route::post('/json', 'indexJson')->name('indexJson');
});

# Route with Check Hak Akses User
Route::middleware(['hak_akses_menu'])->group(function() {
    # Masterdata Provinsi
    Route::prefix('masterdata-provinsi')->name('masterdata-provinsi.')->controller(ProvinsiController::class)->group(function() {
        Route::get('/', 'index')->name('index');
    });

    # Masterdata Kota
    Route::prefix('masterdata-kota')->name('masterdata-kota.')->controller(KotaController::class)->group(function() {
        Route::get('/', 'index')->name('index');
    });

    # Masterdata Kecamatan
    Route::prefix('masterdata-kecamatan')->name('masterdata-kecamatan.')->controller(KecamatanController::class)->group(function() {
        Route::get('/', 'index')->name('index');
    });

    # Masterdata Kelurahan
    Route::prefix('masterdata-kelurahan')->name('masterdata-kelurahan.')->controller(KelurahanController::class)->group(function() {
        Route::get('/', 'index')->name('index');
    });
});

