<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Node;
use Illuminate\Http\Request;

class SensorController extends Controller {
    public function store(Request $request) {
        $request->validate([
            'hardware_id' => 'required|string',
            'temp' => 'required|numeric',
            'smoke' => 'required|numeric',
        ]);

        // PLUG & PLAY: Automatically create node if it's new to the network [cite: 64, 71]
        $node = Node::firstOrCreate(
            ['hardware_id' => $request->hardware_id],
            [
                'location_name' => 'New Unassigned Node', 
                'status' => 'SAFE',
                'specific_area' => 'Awaiting Configuration'
            ]
        );

        // EVALUATE: Logic based on pre-configured safe thresholds [cite: 383, 567]
        $status = 'SAFE';
        if ($request->temp > 45 || $request->smoke > 15 || ($request->water ?? 0) > 10) { 
            $status = 'CRITICAL'; // Trigger red hazard zone [cite: 385]
        } elseif ($request->temp > 35) { 
            $status = 'WARNING'; 
        }

        $node->update(['status' => $status]);

        // LOG: Save reading for post-disaster analysis [cite: 179, 300]
        $node->logs()->create([
            'temperature' => $request->temp,
            'smoke_level' => $request->smoke,
            'water_level' => $request->water ?? 0,
            'status' => $status,
        ]);

        return response()->json(['message' => 'Processing Complete', 'status' => $status]);
    }
}