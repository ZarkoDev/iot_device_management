<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\SensorDataController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Public routes (no authentication required)
 */

// User management
Route::apiResource('users', UserController::class)->only(['index', 'store', 'show', 'destroy']);

// Sensor data recording (public endpoint for devices, with purpose!)
Route::post('sensor-data', [SensorDataController::class, 'store']);

// Authentication API routes
Route::prefix('auth')->group(function (): void {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});


/**
 * Protected routes (authentication required)
 */
Route::middleware('auth:sanctum')->group(function (): void {
    // Device management
    Route::apiResource('devices', DeviceController::class);
    Route::post('devices/{device}/transfer', [DeviceController::class, 'transfer']);

    // Sensor data retrieval
    Route::get('sensor-data', [SensorDataController::class, 'index']);
    Route::get('devices/{device}/sensor-data', [SensorDataController::class, 'show']);
    Route::get('devices/{device}/statistics', [SensorDataController::class, 'statistics']);
});
