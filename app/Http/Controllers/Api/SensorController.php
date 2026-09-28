<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\Threshold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller {
    
    public function store(Request $request) {
        // 1. Validate incoming diagnostics
        $request->validate([
            'hardware_id'    => 'required|string',
            'temp'           => 'required|numeric',
            'smoke'          => 'required|numeric',
            'latency'        => 'nullable|integer',
            'uptime'         => 'nullable|string',
            'wifi_rssi'      => 'nullable|integer',
            'uptime_seconds' => 'nullable|numeric',
            'is_calibrating' => 'nullable|boolean',
        ]);

        // 2. Auto-register or fetch existing node
        $node = Node::firstOrCreate(
            ['hardware_id' => $request->hardware_id],
            [
                'location_name' => 'New Unassigned Node', 
                'status' => 'SAFE',
                'specific_area' => 'Awaiting Configuration'
            ]
        );

        // 3. FETCH GLOBAL CONFIG
        $globalConfig = Threshold::first();
        
        // 4. APPLY CALIBRATION OFFSETS (The Fix)
        // Default to 0 if no config exists yet
        $tempOffset = $globalConfig ? $globalConfig->temp_offset : 0;
        $smokeOffset = $globalConfig ? $globalConfig->smoke_offset : 0;

        // Create the new calibrated values by adding the offsets
        $calibratedTemp = $request->temp + $tempOffset;
        $calibratedSmoke = $request->smoke + $smokeOffset;

        // 5. ZONAL OVERRIDE LOGIC
        $status = 'SAFE';
        
        // Determine which thresholds to use (Zonal vs Global)
        $tCrit = $node->has_custom_thresholds ? $node->custom_temp_critical : ($globalConfig ? $globalConfig->temp_critical : 45.0);
        $sCrit = $node->has_custom_thresholds ? $node->custom_smoke_critical : ($globalConfig ? $globalConfig->smoke_critical : 1000);
        $tWarn = $node->has_custom_thresholds ? $node->custom_temp_warning : ($globalConfig ? $globalConfig->temp_warning : 40.0);
        $sWarn = $node->has_custom_thresholds ? $node->custom_smoke_warning : ($globalConfig ? $globalConfig->smoke_warning : 800);

        // Evaluate environmental hazard thresholds against the CALIBRATED values
        if ($calibratedTemp >= $tCrit || $calibratedSmoke >= $sCrit) { 
            $status = 'CRITICAL';
        } elseif ($calibratedTemp >= $tWarn || $calibratedSmoke >= $sWarn) { 
            $status = 'WARNING'; 
        }

        // 6. Update Node State & Log Telemetry
        $node->update([
            'status'     => $status,
            'ip_address' => $request->ip(),
            'latency'    => $request->latency ?? rand(12, 45), 
            'uptime'     => $request->uptime ?? '0d 0h'
        ]);

        // Save the CALIBRATED data to the database, not the raw data
        $node->logs()->create([
            'temperature'    => $calibratedTemp,
            'smoke_level'    => $calibratedSmoke,
            'water_level'    => $request->water ?? 0,
            'status'         => $status,
            'wifi_rssi'      => $request->wifi_rssi,             
            'uptime_seconds' => $request->uptime_seconds,        
            'is_calibrating' => $request->is_calibrating ?? false, 
        ]);

        // 7. Trigger Pushover Emergency Alarm
        if ($status === 'CRITICAL') {
            $cacheKey = 'alert_cooldown_' . $node->hardware_id;

            if (!Cache::has($cacheKey)) {
                try {
                    $response = Http::asForm()->withOptions([
                        \CURLOPT_IPRESOLVE => \CURL_IPRESOLVE_V4
                    ])->post('https://api.pushover.net/1/messages.json', [
                        'token'    => env('PUSHOVER_APP_TOKEN'),
                        'user'     => env('PUSHOVER_USER_KEY'), 
                        'title'    => 'EMERGENCY: FIRE / HAZARD ALERT',
                        // Ensure the push notification shows the corrected, calibrated values
                        'message'  => "CRITICAL BREACH at {$node->location_name} ({$node->specific_area})! Temp: {$calibratedTemp}°C | Smoke: {$calibratedSmoke} PPM",
                        'priority' => 2,                
                        'retry'    => 30,               
                        'expire'   => 3600,             
                        'sound'    => 'UDRRMC_SIREN',          
                    ]);

                    if ($response->successful()) {
                        Cache::put($cacheKey, true, now()->addMinutes(2));
                        Log::info("Automated alarm dispatched for Node {$node->hardware_id}");
                    } else {
                        Log::error('Pushover Dispatch Failed: ' . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Pushover Exception: ' . $e->getMessage());
                }
            }
        }

        // 8. Return response to ESP32 containing BOTH status and live override flag
        return response()->json([
            'message'        => 'Telemetry & Environmental Data Processed Successfully', 
            'status'         => $status,
            'override_alarm' => Cache::get('manual_override', false)
        ], 200);
    }
}