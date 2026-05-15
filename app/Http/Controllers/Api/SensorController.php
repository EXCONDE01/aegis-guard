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
        ]);

        // Auto-register new nodes
        $node = Node::firstOrCreate(
            ['hardware_id' => $request->hardware_id],
            [
                'location_name' => 'New Unassigned Node', 
                'status' => 'SAFE',
                'specific_area' => 'Awaiting Configuration'
            ]
        );

        // Fetch the dynamic thresholds set by the IT Admin
        $config = Threshold::first();

        // Evaluate logic based on dynamic thresholds
        $status = 'SAFE';
        if ($request->temp >= $config->temp_critical || $request->smoke >= $config->smoke_critical) { 
            $status = 'CRITICAL';
        } elseif ($request->temp >= $config->temp_warning || $request->smoke >= $config->smoke_warning) { 
            $status = 'WARNING'; 
        }

        $node->update(['status' => $status]);

        // Log reading
        $node->logs()->create([
            'temperature' => $request->temp,
            'smoke_level' => $request->smoke,
            'water_level' => $request->water ?? 0,
            'status' => $status,
        ]);

        return response()->json(['message' => 'Processing Complete', 'status' => $status]);
    }
}