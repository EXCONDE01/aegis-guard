<?php

use App\Http\Controllers\Api\SensorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes: Aegis-Guard Sensor Data
|--------------------------------------------------------------------------
*/

// This route handles the incoming data from your ESP32 nodes [cite: 156, 183]
Route::post('/sensor-data', [SensorController::class, 'store']);