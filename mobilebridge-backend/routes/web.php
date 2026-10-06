<?php

use App\Http\Controllers\Web\ComputerWebController;
use App\Http\Controllers\Web\ConnectionWebController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\PhoneWebController;
use App\Http\Controllers\Web\UserWebController;
use App\Http\Controllers\Web\WebAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MobileBridge Web Dashboard Routes
|--------------------------------------------------------------------------
*/

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

// Authenticated Web Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

    // Visual CRUD Resources
    Route::resource('computers', ComputerWebController::class)->except(['show']);
    Route::resource('phones', PhoneWebController::class)->except(['show']);
    Route::resource('users', UserWebController::class)->except(['show']);
    Route::get('connections', [ConnectionWebController::class, 'index'])->name('connections.index');
});
