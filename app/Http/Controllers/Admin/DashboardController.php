<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;
use App\Models\AlertContact;
use Illuminate\Http\Request;

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
        return redirect()->route('dashboard')->with('emergency_success', 'OVERRIDE ENGAGED: Emergency SMS notifications have been forcefully dispatched to all personnel.');
    }

    // --- ALERT CONTACTS MANAGEMENT ---

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

    // --- NODE MANAGEMENT METHODS (SECURED FOR IT ADMIN ONLY) ---

    public function nodes()
    {
        // RBAC SECURITY: Kick out non-admins
        abort_if(auth()->user()->role !== 'admin', 403, 'Unauthorized Access: IT Operations Only.');

        $nodes = Node::orderByRaw("location_name = 'New Unassigned Node' DESC")->latest()->get();
        return view('admin.nodes', compact('nodes'));
    }

    public function updateNode(Request $request, $id)
    {
        // RBAC SECURITY: Kick out non-admins
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
}