<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Node;
use App\Models\NodeLog;

class DashboardController extends Controller {
    public function index() {
        // Module 1: Dashboard visualization of active nodes [cite: 63]
        $nodes = Node::with(['logs' => function($q) { $q->latest()->limit(1); }])->get();
        return view('dashboard', compact('nodes'));
    }

    public function history() {
        // Module 5: Incident archive and audit reports [cite: 68, 470]
        $logs = NodeLog::with('node')->latest()->paginate(15);
        return view('admin.history', compact('logs'));
    }
}