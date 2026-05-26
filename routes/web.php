<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Public/Kiosk Dashboard (View Only)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Secured Administrator Routes
Route::middleware('auth')->group(function () {
    
    // ==========================================
    // MODULE 1: MONITORING & ALERTS
    // ==========================================
    Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');
    Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');

    // ==========================================
    // MODULE 2: HARDWARE & SENSORS
    // ==========================================
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::get('/nodes/telemetry', [DashboardController::class, 'nodesTelemetry'])->name('admin.nodes.telemetry');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
    Route::get('/thresholds', [DashboardController::class, 'thresholds'])->name('admin.thresholds');
    Route::put('/thresholds', [DashboardController::class, 'updateThresholds'])->name('admin.thresholds.update');

    // ==========================================
    // MODULE 3: GATEWAY & VLAN INFRASTRUCTURE
    // ==========================================
    Route::get('/network', [DashboardController::class, 'network'])->name('admin.network');
    Route::get('/network/telemetry', [DashboardController::class, 'gatewayTelemetry'])->name('admin.network.telemetry');
    Route::post('/network/vlan', [DashboardController::class, 'storeVlan'])->name('admin.network.vlan.store');
    Route::delete('/network/vlan/{id}', [DashboardController::class, 'destroyVlan'])->name('admin.network.vlan.destroy');

    // ==========================================
    // MODULE 4: FIREWALL RULE ENGINE
    // ==========================================
    Route::post('/network/rules', [DashboardController::class, 'storeFirewallRule'])->name('admin.network.rules.store');
    Route::post('/network/rules/apply', [DashboardController::class, 'applyFirewallRules'])->name('admin.network.rules.apply');
    Route::post('/network/emergency-lockdown', [DashboardController::class, 'engageEmergencyLockdown'])->name('admin.network.lockdown');

    // ==========================================
    // MODULE 5: SYSTEM BACKUPS
    // ==========================================
    Route::get('/backups', [DashboardController::class, 'showBackups'])->name('admin.backups.index');
    Route::post('/backups/generate', [DashboardController::class, 'generateBackup'])->name('admin.backups.generate');
    Route::get('/backups/download/{filename}', [DashboardController::class, 'downloadBackup'])->name('admin.backups.download');
    Route::post('/backups/restore/{filename}', [DashboardController::class, 'restoreBackup'])->name('admin.backups.restore');
});

require __DIR__.'/auth.php';