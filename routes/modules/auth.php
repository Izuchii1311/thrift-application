<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

# Authentication
Route::controller(RegisterController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/register', 'registerView')->name('register_view');
        Route::post('/register', 'register')->name('register');
    });
});

Route::controller(LoginController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/login', 'loginView')->name('login_view');
        Route::post('/login', 'login')->name('login');
    });

    Route::middleware('auth')->group(function() {
        Route::post('/logout', 'logout')->name('logout');
    });
});