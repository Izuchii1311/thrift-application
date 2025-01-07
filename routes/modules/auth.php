<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

# Authentication
Route::controller(LoginController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/login', 'loginView')->name('login_view');
        Route::post('/login', 'login')->name('login');
    });

    Route::middleware('auth')->group(function() {
        Route::post('/logout', 'logout')->name('logout');
    });
});