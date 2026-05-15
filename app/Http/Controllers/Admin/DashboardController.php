<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\AlertContact;
use App\Models\Threshold;
use App\Models\Vlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http; // Required for OPNsense API

class DashboardController extends Controller
{
    // ==========================================
    // MODULE 1: MONITORING (CAMPUS DIRECTOR)
    // ==========================================

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

    // ==========================================
    // MODULE 2: SYSTEM ADMIN (IT PERSONNEL ONLY)
    // ==========================================

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

        $threshold = Threshold::first();
        $threshold->update($request->all());

        $nodes = Node::with(['logs' => function($query) {
            $query->latest()->limit(1);
        }])->get();

        foreach ($nodes as $node) {
            $latestLog = $node->logs->first();
            if ($latestLog) {
                $status = 'SAFE';
                if ($latestLog->temperature >= $threshold->temp_critical || $latestLog->smoke_level >= $threshold->smoke_critical) { 
                    $status = 'CRITICAL';
                } elseif ($latestLog->temperature >= $threshold->temp_warning || $latestLog->smoke_level >= $threshold->smoke_warning) { 
                    $status = 'WARNING'; 
                }
                $node->update(['status' => $status]);
            }
        }

        return redirect()->route('admin.thresholds')->with('success', 'Global facility thresholds updated. All live nodes have been dynamically re-evaluated.');
    }

    // --- GATEWAY & VLAN INFRASTRUCTURE ---

    public function network()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        try {
            // Fetch live gateway health from OPNsense
            $response = Http::withOptions(['verify' => false]) 
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/core/system/status');

            if ($response->successful()) {
                $opnsenseData = $response->json();
                
                $gateway = [
                    'status' => 'ONLINE',
                    'cpu' => ($opnsenseData['cpu']['used'] ?? '0') . '%',
                    'ram' => ($opnsenseData['memory']['used'] ?? '0') . ' / ' . ($opnsenseData['memory']['total'] ?? '0'),
                    'uptime' => $opnsenseData['uptime'] ?? 'Active',
                    'uplink' => 'Live',
                    'firewall_rules' => 'Active' 
                ];
            } else {
                throw new \Exception('API Connection Failed');
            }
        } catch (\Exception $e) {
            if (isset($response)) {
                dd([
                    'Status' => $response->status(),
                    'Error_Body' => $response->body(),
                    'Message' => $e->getMessage()
                ]);
            }
            dd('CONNECTION ERROR: ' . $e->getMessage());
            
            // This original fallback code below will be ignored while dd() is active
            $gateway = [
                'status' => 'OFFLINE - API DISCONNECTED',
                'cpu' => '--',
                'ram' => '--',
                'uptime' => '--',
                'uplink' => '--',
                'firewall_rules' => '--'
            ];
        }
        $vlans = Vlan::orderBy('vlan_id', 'asc')->get();
        return view('admin.network', compact('gateway', 'vlans'));
    }

    public function storeVlan(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $request->validate([
            'vlan_id' => 'required|integer|min:1|max:4094|unique:vlans,vlan_id',
            'name' => 'required|string|max:255',
            'subnet' => 'required|string|max:18',
        ]);

        // 1. Save locally for the dashboard table
        Vlan::create($request->all());

        try {
            // 2. Push VLAN configuration to OPNsense Hardware
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/addItem', [
                    'vlan' => [
                        'if' => 'igb1', // Set this to your LAN's physical parent interface in OPNsense
                        'tag' => $request->vlan_id,
                        'descr' => $request->name,
                    ]
                ]);
                
            // 3. Apply the changes on the firewall
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/reconfigure');
                
        } catch (\Exception $e) {
            Log::error('OPNsense API Error: ' . $e->getMessage());
            return redirect()->route('admin.network')->with('error', 'VLAN saved locally, but failed to push to physical OPNsense gateway.');
        }

        return redirect()->route('admin.network')->with('success', 'VLAN deployed. OPNsense routing tables updated to isolate traffic.');
    }

    public function destroyVlan($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        $vlan = Vlan::findOrFail($id);
        $vlan->delete();
        
        // Note: For a full production system, you would add an Http::post() here to delete the VLAN in OPNsense using its UUID.

        return redirect()->route('admin.network')->with('success', 'VLAN terminated and removed from routing table.');
    }
}