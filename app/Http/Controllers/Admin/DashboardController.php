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
        return redirect()->route('dashboard')->with('emergency_success', 'OVERRIDE ENGAGED: Emergency SMS notifications have been forcefully dispatched to all LSPU faculty and staff.');
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
}