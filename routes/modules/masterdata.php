<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Masterdata\Lokasi\KotaController;
use App\Http\Controllers\Masterdata\Lokasi\ProvinsiController;
use App\Http\Controllers\Masterdata\Lokasi\KecamatanController;
use App\Http\Controllers\Masterdata\Lokasi\KelurahanController;

Route::prefix('masterdata-provinsi')->name('masterdata-provinsi.')->controller(ProvinsiController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
});

Route::prefix('masterdata-kota')->name('masterdata-kota.')->controller(KotaController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
});

Route::prefix('masterdata-kecamatan')->name('masterdata-kecamatan.')->controller(KecamatanController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
});

Route::prefix('masterdata-kelurahan')->name('masterdata-kelurahan.')->controller(KelurahanController::class)->group(function() {
    Route::get('/', 'index')->name('index');
    Route::post('/json', 'indexJson')->name('indexJson');
});

