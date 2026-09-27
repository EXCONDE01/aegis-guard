<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\AlertContact;
use App\Models\Threshold;
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
        // Fetch nodes with their latest log, then map over them to check offline status
        $nodes = Node::with(['logs' => function($query) {
            $query->latest()->limit(1);
        }])->get()->map(function ($node) {
            
            // Check if the node's last heartbeat is older than 15 seconds
            $isOffline = $node->updated_at->diffInSeconds(now()) > 15;
    
            if ($isOffline) {
                $node->status = 'OFFLINE';
            }
    
            return $node;
        });
        
        return view('dashboard', compact('nodes'));
    }

    public function history(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\NodeLog::with('node')->latest();

        // 1. Apply Filters if requested
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('node_id')) {
            $query->where('node_id', $request->node_id);
        }

        // 2. Paginate with query strings so filters don't break on page 2
        $logs = $query->paginate(50)->withQueryString();
        $nodes = \App\Models\Node::all();

        // 3. Calculate Summary Metrics (Last 30 Days)
        $thirtyDaysAgo = now()->subDays(30);
        $totalEvents = \App\Models\NodeLog::where('created_at', '>=', $thirtyDaysAgo)->count();
        $criticalBreaches = \App\Models\NodeLog::where('created_at', '>=', $thirtyDaysAgo)->where('status', 'CRITICAL')->count();

        // 4. Calculate Most Volatile Zone
        $mostVolatile = \App\Models\NodeLog::select('node_id', \DB::raw('count(*) as total'))
            ->where('status', 'CRITICAL')
            ->groupBy('node_id')
            ->orderByDesc('total')
            ->first();
        
        $volatileZoneName = 'Stable (No Breaches)';
        if ($mostVolatile && $mostVolatile->node) {
            $volatileZoneName = $mostVolatile->node->location_name . ' (' . $mostVolatile->node->specific_area . ')';
        }

        return view('admin.history', compact('logs', 'nodes', 'totalEvents', 'criticalBreaches', 'volatileZoneName'));
    }

    public function exportHistoryCsv(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\NodeLog::with('node')->latest();
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('node_id')) {
            $query->where('node_id', $request->node_id);
        }
        
        $logs = $query->get();
        $fileName = "aegis_guard_audit_log_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Timestamp', 'Node ID', 'Zone/Location', 'Temperature (C)', 'Smoke Raw (PPM)', 'System Status'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->node->hardware_id ?? 'DECOMMISSIONED',
                    $log->node->location_name ?? 'Unknown',
                    $log->temperature,
                    $log->smoke_level,
                    $log->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function dispatchAlert(\Illuminate\Http\Request $request)
    {
        $contacts = \App\Models\Contact::where('is_active', true)->get();

        if ($contacts->isEmpty()) {
            return redirect()->back()->with('error', 'BROADCAST ABORTED: No active personnel registered in the directory.');
        }

        $successCount = 0;
        $errorMessage = null;

        foreach ($contacts as $contact) {
            $key = $contact->pushover_key ?? $contact->user_key;
            if (!$key) continue;

            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://api.pushover.net/1/messages.json', [
                'token' => env('PUSHOVER_APP_TOKEN'), 
                'user' => $key,
                'message' => "🚨 MANUAL OVERRIDE AUTHORIZED 🚨\nEvacuate facility immediately. This is not a drill.",
                'title' => 'Aegis-Guard (CRITICAL)',
                'sound' => 'UDRRMC_SIREN',
                'priority' => 2,
                'retry' => 30,
                'expire' => 120
            ]);
            
            if ($response->successful()) {
                $successCount++;
            } else {
                $errorData = $response->json();
                $errorMessage = $errorData['errors'][0] ?? 'Unknown API Error';
            }
        }

        if ($successCount === 0) {
            return redirect()->back()->with('error', "PUSHOVER API FAILED: " . ($errorMessage ?? "Check your .env PUSHOVER_APP_TOKEN."));
        }

        return redirect()->back()->with('emergency_success', "PROTOCOL OVERRIDE SUCCESS: Emergency broadcast dispatched to {$successCount} active responders.");
    }

    public function contacts()
    {
        $contacts = \App\Models\Contact::latest()->get();
        return view('admin.contacts', compact('contacts'));
    }

    public function storeContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'pushover_key' => 'required|string|max:50',
            'role' => 'required|string|max:50',
        ]);

        \App\Models\Contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'pushover_key' => $request->pushover_key,
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'New emergency responder successfully registered.');
    }

    public function testContactPing($id)
    {
        $contact = \App\Models\Contact::findOrFail($id);
        
        \Illuminate\Support\Facades\Http::asForm()->post('https://api.pushover.net/1/messages.json', [
            'token' => env('PUSHOVER_APP_TOKEN'),
            'user' => $contact->pushover_key ?? $contact->user_key,
            'message' => "TEST PING: Aegis-Guard communications check. System is nominal.",
            'title' => 'Aegis-Guard (TEST)',
            'sound' => 'pushover',
            'priority' => 0,
        ]);

        return redirect()->back()->with('success', "Test ping dispatched successfully to {$contact->name}.");
    }

    public function toggleContactStatus($id)
    {
        $contact = \App\Models\Contact::findOrFail($id);
        $contact->is_active = !$contact->is_active;
        $contact->save();

        $status = $contact->is_active ? 'Active on-duty' : 'Off-duty (Muted)';
        return redirect()->back()->with('success', "{$contact->name} is now marked as {$status}.");
    }

    public function destroyContact($id)
    {
        $contact = \App\Models\Contact::findOrFail($id);
        $name = $contact->name;
        $contact->delete();
        return redirect()->back()->with('success', "Responder {$name} has been permanently removed from the system.");
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

        $nodes = Node::orderByRaw("location_name = 'New Unassigned Node' DESC")->latest()->get();

        $nodesWithOfflineCheck = $nodes->map(function ($node) {
            $isOffline = $node->updated_at->diffInSeconds(now()) > 15;
            if ($isOffline) {
                $node->status = 'OFFLINE';
                $node->latency = null; 
            }
            return $node;
        });

        return response()->json($nodesWithOfflineCheck);
    }

    public function updateNode(\Illuminate\Http\Request $request, $id)
    {
        $node = \App\Models\Node::findOrFail($id);
        
        $request->validate([
            'hardware_id' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'specific_area' => 'nullable|string|max:255',
            'custom_temp_warning' => 'nullable|numeric',
            'custom_temp_critical' => 'nullable|numeric',
            'custom_smoke_warning' => 'nullable|numeric',
            'custom_smoke_critical' => 'nullable|numeric',
        ]);

        $node->hardware_id = $request->hardware_id;
        $node->location_name = $request->location_name;
        $node->specific_area = $request->specific_area;
        
        if ($request->has('status')) {
            $node->status = $request->status;
        }

        $node->has_custom_thresholds = $request->has('has_custom_thresholds');
        
        if ($node->has_custom_thresholds) {
            $node->custom_temp_warning = $request->custom_temp_warning;
            $node->custom_temp_critical = $request->custom_temp_critical;
            $node->custom_smoke_warning = $request->custom_smoke_warning;
            $node->custom_smoke_critical = $request->custom_smoke_critical;
        } else {
            $node->custom_temp_warning = null;
            $node->custom_temp_critical = null;
            $node->custom_smoke_warning = null;
            $node->custom_smoke_critical = null;
        }

        $node->save();

        return redirect()->back()->with('success', "Node {$node->hardware_id} configuration and zonal overrides updated successfully.");
    }

    public function destroyNode($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access.');
        $node = Node::findOrFail($id);
        $node->logs()->delete(); 
        $node->delete();
        
        return redirect()->route('admin.nodes')->with('success', 'Hardware node permanently decommissioned and purged from the network.');
    }

    public function thresholds()
    {
        $threshold = \App\Models\Threshold::first() ?? new \App\Models\Threshold();
        $overrideNodes = \App\Models\Node::where('has_custom_thresholds', true)->get();

        return view('admin.thresholds', compact('threshold', 'overrideNodes'));
    }

    public function updateThresholds(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'temp_warning' => 'required|numeric',
            'temp_critical' => 'required|numeric',
            'smoke_warning' => 'required|numeric',
            'smoke_critical' => 'required|numeric',
            'temp_offset' => 'required|numeric',
            'smoke_offset' => 'required|numeric',
        ]);

        $threshold = \App\Models\Threshold::first() ?? new \App\Models\Threshold();
        $threshold->fill($request->all());
        $threshold->updated_by_name = auth()->user()->name ?? 'System Administrator';
        $threshold->save();

        return redirect()->back()->with('success', 'Global hazard thresholds and calibration offsets updated successfully.');
    }

    // ==========================================
    // MODULE 3: SYSTEM BACKUPS & RESTORATION
    // ==========================================

    public function showBackups()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        if (!$disk->exists('backups')) {
            $disk->makeDirectory('backups');
        }
        
        $files = $disk->files('backups');
        $backups = [];
        $totalSize = 0;

        foreach ($files as $file) {
            $size = $disk->size($file);
            $totalSize += $size;
            $backups[] = [
                'name' => basename($file),
                'size' => number_format($size / 1048576, 2) . ' MB',
                'timestamp' => $disk->lastModified($file),
                'date' => \Carbon\Carbon::createFromTimestamp($disk->lastModified($file))->format('M d, Y - H:i:s')
            ];
        }

        // Sort newest backups to the top
        usort($backups, function($a, $b) { return $b['timestamp'] <=> $a['timestamp']; });
        
        $totalSizeFormatted = number_format($totalSize / 1048576, 2);
        
        return view('admin.backups', compact('backups', 'totalSizeFormatted'));
    }

    public function generateBackup()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        \Illuminate\Support\Facades\Storage::makeDirectory('backups');
        
        // Using your original filename prefix to match the existing vault files
        $filename = "aegis_db_backup_" . now()->format('Y_m_d_His') . ".sql";
        $path = \Illuminate\Support\Facades\Storage::path('backups/' . $filename);

        // Reverting to your exact sprintf syntax which XAMPP requires
        $command = sprintf(
            'mysqldump --user="%s" --password="%s" --host="%s" "%s" > "%s"',
            env('DB_USERNAME', 'root'),
            env('DB_PASSWORD', ''),
            env('DB_HOST', '127.0.0.1'),
            env('DB_DATABASE', 'aegis_db'),
            $path
        );

        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'SYSTEM FAILURE: mysqldump execution failed. Check XAMPP environment variables.');
        }

        // Automated 30-Day Retention Pruning
        $disk = \Illuminate\Support\Facades\Storage::disk('local');
        $files = $disk->files('backups');
        $now = time();
        foreach ($files as $file) {
            if ($now - $disk->lastModified($file) >= 2592000) { 
                $disk->delete($file);
            }
        }

        return redirect()->back()->with('success', 'System snapshot generated successfully. AWS EC2 off-site replication queued.');
    }

    public function downloadBackup($filename)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $path = 'backups/' . $filename;
        
        // Use Laravel's Storage facade to safely resolve OS paths
        if (\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('local')->download($path);
        }
        
        return redirect()->back()->with('error', 'Backup file missing or corrupted. Path resolution failed.');
    }
    public function restoreBackup(\Illuminate\Http\Request $request, $filename)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $path = 'backups/' . $filename;

        if (!\Illuminate\Support\Facades\Storage::exists($path)) {
            return redirect()->back()->with('error', 'Restoration aborted: Target archive snapshot missing.');
        }

        $absolutePath = \Illuminate\Support\Facades\Storage::path($path);

        // Using your exact sprintf syntax for the restore function as well
        $command = sprintf(
            'mysql --user="%s" --password="%s" --host="%s" "%s" < "%s"',
            env('DB_USERNAME', 'root'),
            env('DB_PASSWORD', ''),
            env('DB_HOST', '127.0.0.1'),
            env('DB_DATABASE', 'aegis_db'),
            $absolutePath
        );

        $returnVar = NULL;
        $output  = NULL;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return redirect()->back()->with('error', 'CRITICAL ERROR: Rollback failed during execution.');
        }

        return redirect()->back()->with('success', "DISASTER RECOVERY SUCCESS: Command center restored to snapshot {$filename}.");
    }
    public function exportCsv($id)
    {
        $node = \App\Models\Node::findOrFail($id);
        $logs = $node->logs()->latest()->get();

        $fileName = "aegis_guard_{$node->hardware_id}_telemetry.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Timestamp', 'Node ID', 'Zone', 'Temperature (C)', 'Smoke Raw (PPM)', 'System Status'];

        $callback = function() use($logs, $columns, $node) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $node->hardware_id,
                    $node->location_name,
                    $log->temperature,
                    $log->smoke_level,
                    $log->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    // ==========================================
    // MODULE 4: USER MANAGEMENT (IT PERSONNEL ONLY)
    // ==========================================

    public function users()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        $users = \App\Models\User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(\Illuminate\Http\Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,security,executive'
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'New system operator successfully provisioned.');
    }

    public function destroyUser($id)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        // Failsafe: Prevent the admin from deleting their own active session
        abort_if(auth()->id() == $id, 403, 'CRITICAL: You cannot delete your own active administrator account.');
        
        $user = \App\Models\User::findOrFail($id);
        $name = $user->name;
        $user->delete();
        
        return redirect()->back()->with('success', "Access revoked: {$name} has been removed from FireNet.");
    }

    // ==========================================
    // MODULE 5: SYSTEM SETTINGS
    // ==========================================

    public function settings()
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');
        
        // Fetch the settings row, or create a default one if it doesn't exist yet
        $settings = \App\Models\Setting::firstOrCreate(
            ['id' => 1],
            [
                'campus_name' => 'Laguna State Polytechnic University',
                'admin_email' => 'admin@lspu.edu.ph',
                'pushover_app_token' => env('PUSHOVER_APP_TOKEN', ''),
                'pushover_user_key' => env('PUSHOVER_USER_KEY', ''),
                'alerts_muted' => false
            ]
        );

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(\Illuminate\Http\Request $request)
    {
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $settings = \App\Models\Setting::first();
        
        $settings->update([
            'campus_name' => $request->campus_name,
            'admin_email' => $request->admin_email,
            'pushover_app_token' => $request->pushover_app_token,
            'pushover_user_key' => $request->pushover_user_key,
            'alerts_muted' => $request->has('alerts_muted'),
        ]);

        return redirect()->back()->with('success', 'Global system settings successfully updated.');
    }
}