<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Public/Kiosk Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Secured Routes
Route::middleware('auth')->group(function () {
    Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');
    Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');
    
    // Contacts
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');

    // Hardware Nodes & Telemetry
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::get('/nodes/telemetry', [DashboardController::class, 'nodesTelemetry'])->name('admin.nodes.telemetry');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
    
    // Thresholds
    Route::get('/thresholds', [DashboardController::class, 'thresholds'])->name('admin.thresholds');
    Route::put('/thresholds', [DashboardController::class, 'updateThresholds'])->name('admin.thresholds.update');

    // Gateway & VLAN Infrastructure 
    Route::get('/network', [DashboardController::class, 'network'])->name('admin.network');
    Route::get('/network/telemetry', [DashboardController::class, 'gatewayTelemetry'])->name('admin.network.telemetry');
    Route::post('/network/vlan', [DashboardController::class, 'storeVlan'])->name('admin.network.vlan.store');
    Route::delete('/network/vlan/{id}', [DashboardController::class, 'destroyVlan'])->name('admin.network.vlan.destroy');

    // System SQL Backups
    Route::get('/backups', [DashboardController::class, 'showBackups'])->name('admin.backups.index');
    Route::post('/backups/generate', [DashboardController::class, 'generateBackup'])->name('admin.backups.generate');
    Route::get('/backups/download/{filename}', [DashboardController::class, 'downloadBackup'])->name('admin.backups.download');
    Route::post('/backups/restore/{filename}', [DashboardController::class, 'restoreBackup'])->name('admin.backups.restore');
});

require __DIR__.'/auth.php';