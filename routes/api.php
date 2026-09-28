<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Api\SensorController;
use App\Models\Setting; 

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

// Endpoint for the ESP32 to download global system settings and manual override status
Route::get('/hardware-config', function () {
    $settings = Setting::first();
    
    // Reads the exact override state triggered by the web dashboard button.
    // Defaults to 'false' (silent) if no emergency broadcast is active.
    $overrideStatus = Cache::get('manual_override', false); 
    
    return response()->json([
        // Send the interval to the hardware in milliseconds (Default to 5000 if not found)
        'polling_interval' => $settings ? ($settings->sensor_polling_interval * 1000) : 5000,
        
        // Pass the live manual override flag to the ESP32 buzzer logic
        'override_alarm'   => (bool) $overrideStatus
    ]);
});