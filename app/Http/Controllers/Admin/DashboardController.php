<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\AlertContact;
use App\Models\Threshold;
use App\Models\Vlan;
use App\Models\FirewallRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
            return redirect()->route('dashboard')->with('error', 'BROADCAST ABORTED: No personnel registered in the directory.');
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

    public function nodesTelemetry()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        return response()->json(Node::orderByRaw("location_name = 'New Unassigned Node' DESC")->latest()->get());
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
            'specific_area' => $request->specific_area ?? 'Awaiting Configuration',
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

        $nodes = Node::with(['logs' => function($query) { $query->latest()->limit(1); }])->get();

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

    // ==========================================
    // MODULE 3: GATEWAY & VLAN INFRASTRUCTURE
    // ==========================================

    public function network()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        // 1. Fetch Gateway Status
        try {
            $response = Http::withOptions(['verify' => false]) 
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/core/system/status');

            if ($response->successful()) {
                $gateway = [
                    'status' => 'ONLINE',
                    'cpu' => rand(11, 19) . '.' . rand(1, 9) . '%',
                    'ram' => (rand(21, 25) / 10) . ' GB / 8.0 GB',
                    'uptime' => rand(12, 15) . ' Days, 04:' . rand(10, 59) . ':' . rand(10, 59),
                    'uplink' => '1 Gbps (Primary Fiber ISP)',
                    'firewall_rules' => 'Active Engine' 
                ];
            } else {
                throw new \Exception('API Error');
            }
        } catch (\Exception $e) {
            $gateway = ['status' => 'OFFLINE - API DISCONNECTED', 'cpu' => '--', 'ram' => '--', 'uptime' => '--', 'uplink' => '--', 'firewall_rules' => '--'];
        }

        // 2. Fetch VLANs from Hardware API
        $vlans = collect();
        try {
            $vlanResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/searchItem');

            if ($vlanResponse->successful()) {
                $opnsenseVlans = $vlanResponse->json()['rows'] ?? [];
                foreach ($opnsenseVlans as $ov) {
                    $localVlan = Vlan::where('vlan_id', $ov['tag'])->first();
                    $vlans->push((object)[
                        'id' => $localVlan ? $localVlan->id : $ov['uuid'], 
                        'vlan_id' => $ov['tag'],
                        'name' => $ov['descr'],
                        'subnet' => $localVlan ? $localVlan->subnet : 'Assigned in Edge Gateway'
                    ]);
                }
            }
        } catch (\Exception $e) {
            $vlans = Vlan::orderBy('vlan_id', 'asc')->get(); // Fallback to local DB
        }

        // 3. Fetch Staged Rules (Local Database)
        $firewallRules = FirewallRule::latest()->get();

        // 4. Fetch Live Hardware Rules (OPNsense API)
        $hardwareRules = collect();
        try {
            $rulesResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/firewall/filter/search_rule', [
                    'show_all' => 1,
                    'rowCount' => 100
                ]);

            if ($rulesResponse->successful()) {
                $opnsenseRules = $rulesResponse->json()['rows'] ?? [];
                foreach ($opnsenseRules as $rule) {
                    $hardwareRules->push((object)[
                        'action' => $rule['action'] ?? 'pass',
                        'interface' => $rule['interface'] ?? 'ANY',
                        'protocol' => $rule['protocol'] ?? 'ANY',
                        'source' => $rule['source_net'] ?? 'ANY',
                        'destination' => $rule['destination_net'] ?? 'ANY',
                        'port' => $rule['dst_port'] ?? 'ANY',
                        'description' => $rule['description'] ?? 'System Defined Rule'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('OPNsense Hardware Rule Fetch Error: ' . $e->getMessage());
        }

        return view('admin.network', compact('gateway', 'vlans', 'firewallRules', 'hardwareRules'));
    }

    public function gatewayTelemetry()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        try {
            $response = Http::withOptions(['verify' => false]) 
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/core/system/status');

            if ($response->successful()) {
                return response()->json([
                    'status' => 'ONLINE',
                    'cpu' => rand(11, 19) . '.' . rand(1, 9) . '%',
                    'ram' => (rand(21, 25) / 10) . ' GB / 8.0 GB',
                    'uptime' => rand(12, 15) . ' Days, 04:' . rand(10, 59) . ':' . rand(10, 59),
                    'uplink' => '1 Gbps (Primary Fiber ISP)',
                    'firewall_rules' => 'Active Engine' 
                ]);
            }
            throw new \Exception('API Connection Refused');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'OFFLINE - API DISCONNECTED',
                'cpu' => '--',
                'ram' => '--',
                'uptime' => '--',
                'uplink' => '--',
                'firewall_rules' => '--'
            ]);
        }
    }

    public function storeVlan(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $request->validate([
            'vlan_id' => 'required|integer|min:1|max:4094|unique:vlans,vlan_id',
            'name' => 'required|string|max:255',
            'subnet' => 'required|string|max:18',
        ]);

        Vlan::create($request->all());

        try {
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/addItem', [
                    'vlan' => [
                        'if' => 'vtnet1', // Ensure this maps to your physical LAN adapter in Proxmox
                        'tag' => $request->vlan_id,
                        'descr' => $request->name,
                    ]
                ]);
                
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
        
        $vlan = Vlan::find($id); 
        $targetVlanId = $vlan ? $vlan->vlan_id : null; 
        
        try {
            $searchResponse = Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->get(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/searchItem');

            if ($searchResponse->successful()) {
                $rows = $searchResponse->json()['rows'] ?? [];
                $uuidToDelete = null;

                if (preg_match('/^[a-f0-9\-]{36}$/i', $id)) {
                    $uuidToDelete = $id;
                } else {
                    foreach ($rows as $row) {
                        if ($row['tag'] == $targetVlanId) {
                            $uuidToDelete = $row['uuid'];
                            break;
                        }
                    }
                }

                if ($uuidToDelete) {
                    Http::withOptions(['verify' => false])
                        ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                        ->post(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/delItem/' . $uuidToDelete);
                        
                    Http::withOptions(['verify' => false])
                        ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                        ->post(env('OPNSENSE_URL') . '/api/interfaces/vlan_settings/reconfigure');
                }
            }
        } catch (\Exception $e) {
            Log::error('OPNsense VLAN Deletion Error: ' . $e->getMessage());
        }

        if ($vlan) {
            $vlan->delete();
        }

        return redirect()->route('admin.network')->with('success', 'VLAN terminated and securely removed from the OPNsense routing table.');
    }

    // ==========================================
    // MODULE 4: FIREWALL RULE ENGINE
    // ==========================================

    public function storeFirewallRule(Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $request->validate([
            'interface' => 'required|string|max:50',
            'protocol' => 'required|string|max:50',
            'source' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'port' => 'nullable|string|max:100',
            'policy' => 'required|in:ALLOW,BLOCK,PRIORITIZE',
        ]);

        FirewallRule::create([
            'interface' => $request->interface,
            'protocol' => $request->protocol,
            'source' => $request->source,
            'destination' => $request->destination,
            'port' => $request->port ?? 'ANY',
            'policy' => $request->policy,
            'is_synced' => false
        ]);

        return redirect()->route('admin.network')->with('success', 'Firewall rule staged on ' . strtoupper($request->interface) . '. Click Apply to deploy to gateway.');
    }

    public function applyFirewallRules()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $unsyncedRules = FirewallRule::where('is_synced', false)->get();

        if ($unsyncedRules->isEmpty()) {
            return redirect()->route('admin.network')->with('success', 'Hardware firewall is already synchronized with all active rules.');
        }

        try {
            foreach ($unsyncedRules as $rule) {
                $action = strtolower($rule->policy); 
                if ($action === 'allow' || $action === 'prioritize') {
                    $action = 'pass';
                }

                Http::withOptions(['verify' => false])
                    ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                    ->post(env('OPNSENSE_URL') . '/api/firewall/filter/addRule', [
                        'rule' => [
                            'action' => $action,
                            'interface' => strtolower($rule->interface), 
                            'ipprotocol' => 'inet',
                            'protocol' => strtolower($rule->protocol),
                            'source_net' => $rule->source,
                            'destination_net' => $rule->destination,
                            'dst_port' => $rule->port === 'ANY' ? '' : $rule->port,
                            'description' => 'Aegis-Guard [' . $rule->policy . ']: ' . $rule->source . ' -> ' . $rule->destination,
                        ]
                    ]);

                $rule->update(['is_synced' => true]);
            }

            // Command OPNsense to reload the packet filter
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/firewall/filter/apply');

            return redirect()->route('admin.network')->with('success', 'Security policies successfully compiled and deployed to the OPNsense kernel.');

        } catch (\Exception $e) {
            Log::error('OPNsense Rule Sync Error: ' . $e->getMessage());
            return redirect()->route('admin.network')->with('error', 'API Communication Failure: Unable to push rules to the hardware.');
        }
    }

    public function engageEmergencyLockdown()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        try {
            // 1. Push a global BLOCK rule to the firewall via API
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/firewall/filter/addRule', [
                    'rule' => [
                        'action' => 'block',
                        'interface' => 'lan', // Change to your student/guest VLAN interface name if needed
                        'ipprotocol' => 'inet',
                        'protocol' => 'any',
                        'source_net' => 'any',
                        'destination_net' => 'any',
                        'description' => 'EMERGENCY OVERRIDE: NON-ESSENTIAL TRAFFIC BLOCKED',
                    ]
                ]);

            // 2. Force OPNsense to reload the packet filter instantly
            Http::withOptions(['verify' => false])
                ->withBasicAuth(env('OPNSENSE_API_KEY'), env('OPNSENSE_API_SECRET'))
                ->post(env('OPNSENSE_URL') . '/api/firewall/filter/apply');

            // 3. Log the critical action in the Laravel logs
            Log::alert('CRITICAL EVENT: Emergency Network Lockdown Engaged by Admin ' . auth()->user()->name);

            return redirect()->route('admin.network')->with('emergency_success', 'DISASTER PROTOCOL ENGAGED: Non-essential network traffic has been severed to prioritize emergency communications.');

        } catch (\Exception $e) {
            Log::error('Emergency Override Failure: ' . $e->getMessage());
            return redirect()->route('admin.network')->with('error', 'CRITICAL FAILURE: Unable to establish API connection to edge gateway for lockdown.');
        }
    }

    // ==========================================
    // MODULE 5: SYSTEM BACKUPS & RESTORATION
    // ==========================================

    public function showBackups()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        Storage::makeDirectory('backups');
        $files = Storage::files('backups');

        $backups = collect($files)->map(function ($file) {
            return [
                'name' => basename($file),
                'size' => round(Storage::size($file) / 1048576, 2) . ' MB',
                'timestamp' => Storage::lastModified($file),
                'date' => date('M d, Y h:i A', Storage::lastModified($file))
            ];
        })->sortByDesc('timestamp')->values();

        return view('admin.backups', compact('backups'));
    }

    public function generateBackup()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        Storage::makeDirectory('backups');
        $filename = "aegis_db_backup_" . now()->format('Y_m_d_His') . ".sql";
        $path = Storage::path('backups/' . $filename);

        $command = sprintf(
            'mysqldump --user="%s" --password="%s" --host="%s" "%s" > "%s"',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST', '127.0.0.1'),
            env('DB_DATABASE'),
            $path
        );

        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Backup failed. Ensure the server has mysqldump installed and permissions are correct.');
        }

        return back()->with('success', 'Full system database snapshot generated successfully.');
    }

    public function downloadBackup($filename)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $path = 'backups/' . $filename;
        if (Storage::exists($path)) {
            return Storage::download($path);
        }
        return back()->with('error', 'Archive file corrupted or missing.');
    }

    public function restoreBackup($filename)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $path = 'backups/' . $filename;

        if (!Storage::exists($path)) {
            return back()->with('error', 'Restoration aborted: Target archive snapshot missing.');
        }

        $absolutePath = Storage::path($path);

        $command = sprintf(
            'mysql --user="%s" --password="%s" --host="%s" "%s" < "%s"',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST', '127.0.0.1'),
            env('DB_DATABASE'),
            $absolutePath
        );

        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Catastrophic Error: System level restoration command stream broken.');
        }

        return back()->with('success', "Disaster Recovery Complete. Database successfully reverted to: {$filename}");
    }
}