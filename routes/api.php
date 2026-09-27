<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Models\Setting; // Added this import

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route the ESP32 hardware telemetry directly to your advanced controller logic
Route::post('/telemetry', [SensorController::class, 'store']);

// NEW: Endpoint for the ESP32 to download global system settings
Route::get('/hardware-config', function () {
    $settings = Setting::first();
    
    return response()->json([
        // Send the interval to the hardware in milliseconds (Default to 5000 if not found)
        'polling_interval' => $settings ? ($settings->sensor_polling_interval * 1000) : 5000
    ]);
});