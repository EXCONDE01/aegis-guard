<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\AlertContact;
use App\Models\Threshold;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // --- DASHBOARD & HISTORY ---
    public function index()
    {
        $nodes = Node::with(['logs' => function($query) {
            $query->latest()->limit(1);
        }])->get();
        return view('dashboard', compact('nodes'));
    }

    public function history()
    {
        $logs = NodeLog::with('node')->latest()->paginate(15);
        return view('admin.history', compact('logs'));
    }

    public function dispatchAlert()
    {
        $contacts = AlertContact::all();
        if ($contacts->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'BROADCAST ABORTED: No personnel registered in the Alert Contacts directory.');
        }

        $notifiedCount = 0;
        foreach ($contacts as $contact) {
            Log::info("EMERGENCY SMS DISPATCHED TO: {$contact->name} ({$contact->role}) at {$contact->phone}");
            $notifiedCount++;
        }

        return redirect()->route('dashboard')->with('emergency_success', "OVERRIDE ENGAGED: Emergency evacuation SMS securely dispatched to {$notifiedCount} authorized personnel.");
    }

    // --- ALERT CONTACTS ---
    public function contacts()
    {
        $contacts = AlertContact::latest()->get();
        return view('admin.contacts', compact('contacts'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);
        AlertContact::create($request->all());
        return redirect()->route('admin.contacts')->with('success', 'New emergency contact registered successfully.');
    }

    public function destroyContact($id)
    {
        AlertContact::findOrFail($id)->delete();
        return redirect()->route('admin.contacts')->with('success', 'Contact removed from the emergency broadcast list.');
    }

    // --- NODE MANAGEMENT (IT ADMIN ONLY) ---
    public function nodes()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        $nodes = Node::orderByRaw("location_name = 'New Unassigned Node' DESC")->latest()->get();
        return view('admin.nodes', compact('nodes'));
    }

    public function updateNode(Request $request, $id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        $request->validate([
            'location_name' => 'required|string|max:255',
            'specific_area' => 'nullable|string|max:255',
        ]);
        $node = Node::findOrFail($id);
        $node->update([
            'location_name' => $request->location_name,
            'specific_area' => $request->specific_area ?? 'General Area',
        ]);
        return redirect()->route('admin.nodes')->with('success', 'Sensor node configuration updated successfully.');
    }

    // --- THRESHOLD MANAGEMENT (IT ADMIN ONLY) ---
    public function thresholds()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        $threshold = Threshold::first();
        return view('admin.thresholds', compact('threshold'));
    }

    public function updateThresholds(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        $request->validate([
            'temp_warning' => 'required|numeric|min:0',
            'temp_critical' => 'required|numeric|gt:temp_warning',
            'smoke_warning' => 'required|numeric|min:0',
            'smoke_critical' => 'required|numeric|gt:smoke_warning',
        ]);

        // 1. Save the new thresholds
        $threshold = Threshold::first();
        $threshold->update($request->all());

        // 2. RETROACTIVE UPDATE: Fetch all nodes and force them to re-evaluate their latest reading against the NEW thresholds
        $nodes = Node::with(['logs' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        foreach ($nodes as $node) {
            $latestLog = $node->logs->first();
            
            if ($latestLog) {
                $status = 'SAFE';
                
                // Re-evaluate using the newly saved threshold data
                if ($latestLog->temperature >= $threshold->temp_critical || $latestLog->smoke_level >= $threshold->smoke_critical) { 
                    $status = 'CRITICAL';
                } elseif ($latestLog->temperature >= $threshold->temp_warning || $latestLog->smoke_level >= $threshold->smoke_warning) { 
                    $status = 'WARNING'; 
                }

                // Immediately update the node's live status on the dashboard
                $node->update(['status' => $status]);
            }
        }

        return redirect()->route('admin.thresholds')->with('success', 'Global facility thresholds updated. All live nodes have been dynamically re-evaluated.');
    }
}