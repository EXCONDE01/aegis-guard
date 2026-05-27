<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::post('/telemetry', function (Request $request) {
    // 1. Grab the JSON payload sent by the ESP32
    $nodeId = $request->input('node_id');
    $temp = $request->input('temperature');
    $smoke = $request->input('smoke_level');

    // 2. Write it to our Laravel log to prove we got it!
    Log::info("ESP32 Sensor Hit! Node: {$nodeId} | Temp: {$temp}C | Smoke: {$smoke}");

    // 3. Send the Code 200 Success back to the ESP32
    return response()->json([
        'status' => 'success',
        'message' => 'Aegis-Guard Gateway Acknowledged'
    ], 200);
});