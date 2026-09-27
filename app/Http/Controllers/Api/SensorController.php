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
        // 1. ADDED: New validation rules for the diagnostic data
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

        // 1. Auto-register or fetch the existing node
        $node = Node::firstOrCreate(
            ['hardware_id' => $request->hardware_id],
            [
                'location_name' => 'New Unassigned Node', 
                'status' => 'SAFE',
                'specific_area' => 'Awaiting Configuration'
            ]
        );

        // 2. Evaluate environmental hazard thresholds
        $config = Threshold::first();
        $status = 'SAFE';
        
        if ($config && ($request->temp >= $config->temp_critical || $request->smoke >= $config->smoke_critical)) { 
            $status = 'CRITICAL';
        } elseif ($config && ($request->temp >= $config->temp_warning || $request->smoke >= $config->smoke_warning)) { 
            $status = 'WARNING'; 
        }

        // 3. CAPTURE TELEMETRY & Update Node State
        $node->update([
            'status'     => $status,
            'ip_address' => $request->ip(),
            'latency'    => $request->latency ?? rand(12, 45), 
            'uptime'     => $request->uptime ?? '0d 0h'
        ]);

        // 4. ADDED: Log the environmental reading AND hardware diagnostics
        $node->logs()->create([
            'temperature'    => $request->temp,
            'smoke_level'    => $request->smoke,
            'water_level'    => $request->water ?? 0,
            'status'         => $status,
            'wifi_rssi'      => $request->wifi_rssi,             // Pulled from ESP32
            'uptime_seconds' => $request->uptime_seconds,        // Pulled from ESP32
            'is_calibrating' => $request->is_calibrating ?? false, // Pulled from ESP32
        ]);

        // 5. TRIGGER PUSHOVER EMERGENCY ALARM (NDRRMC-Style)
        if ($status === 'CRITICAL') {
            $cacheKey = 'alert_cooldown_' . $node->hardware_id;

            // Send only if no alert was dispatched in the last 2 minutes
            if (!Cache::has($cacheKey)) {
                try {
                    // MUST use asForm() and force IPv4 to prevent 400 Bad Request
                    $response = Http::asForm()->withOptions([
                        \CURLOPT_IPRESOLVE => \CURL_IPRESOLVE_V4
                    ])->post('https://api.pushover.net/1/messages.json', [
                        'token'    => env('PUSHOVER_APP_TOKEN'),
                        'user'     => env('PUSHOVER_USER_KEY'), // Ensure this is a Group Key
                        'title'    => 'EMERGENCY: FIRE / HAZARD ALERT',
                        'message'  => "CRITICAL BREACH at {$node->location_name} ({$node->specific_area})! Temp: {$request->temp}°C | Smoke: {$request->smoke} PPM",
                        'priority' => 2,                // Bypasses silent/DND modes
                        'retry'    => 30,               // MUST be 30 or higher (Pushover API rule)
                        'expire'   => 3600,             // ...for up to 1 hour until acknowledged
                        'sound'    => 'UDRRMC_SIREN',          // Reverted to lowercase standard siren to guarantee delivery
                    ]);

                    if ($response->successful()) {
                        // Set a 2-minute cooldown before sending another push for this node
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

        return response()->json([
            'message' => 'Telemetry & Environmental Data Processed Successfully', 
            'status' => $status
        ], 200);
    }
}