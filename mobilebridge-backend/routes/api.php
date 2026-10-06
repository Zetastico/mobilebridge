<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ComputerController;
use App\Http\Controllers\Api\ConnectionController;
use App\Http\Controllers\Api\PhoneController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MobileBridge REST API Routes
|--------------------------------------------------------------------------
*/

// Public Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated Routes (Sanctum Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth & Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Computers API & Agent Control
    Route::post('/computers/register', [ComputerController::class, 'register']);
    Route::post('/computers/heartbeat', [ComputerController::class, 'heartbeat']);
    Route::post('/computers/offline', [ComputerController::class, 'offline']);
    Route::apiResource('computers', ComputerController::class);

    // Phones API & Android Control
    Route::post('/phones/register', [PhoneController::class, 'register']);
    Route::post('/phones/heartbeat', [PhoneController::class, 'heartbeat']);
    Route::post('/phones/offline', [PhoneController::class, 'offline']);
    Route::apiResource('phones', PhoneController::class);

    // Connections History & Status
    Route::apiResource('connections', ConnectionController::class);
});
