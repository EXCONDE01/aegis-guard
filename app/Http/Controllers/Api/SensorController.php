<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\Threshold;
use Illuminate\Http\Request;

class SensorController extends Controller {
    
    public function store(Request $request) {
        $request->validate([
            'hardware_id' => 'required|string',
            'temp' => 'required|numeric',
            'smoke' => 'required|numeric',
            'latency' => 'nullable|integer',
            'uptime' => 'nullable|string',
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
        
        if ($request->temp >= $config->temp_critical || $request->smoke >= $config->smoke_critical) { 
            $status = 'CRITICAL';
        } elseif ($request->temp >= $config->temp_warning || $request->smoke >= $config->smoke_warning) { 
            $status = 'WARNING'; 
        }

        // 3. CAPTURE TELEMETRY & Update Node State
        $node->update([
            'status' => $status,
            'ip_address' => $request->ip(),
            'latency' => $request->latency ?? rand(12, 45), 
            'uptime' => $request->uptime ?? '0d 0h'
        ]);

        // 4. Log the environmental reading for historical charting
        $node->logs()->create([
            'temperature' => $request->temp,
            'smoke_level' => $request->smoke,
            'water_level' => $request->water ?? 0,
            'status' => $status,
        ]);

        return response()->json([
            'message' => 'Telemetry & Environmental Data Processed Successfully', 
            'status' => $status
        ], 200);
    }
}