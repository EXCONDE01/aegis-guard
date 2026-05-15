<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes: Aegis-Guard
|--------------------------------------------------------------------------
*/

// Public Kiosk Mode (Real-Time Map)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Secured Administrative Routes
Route::middleware('auth')->group(function () {
    
    // Emergency Control & Logs
    Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');
    Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');

    // Alert Contacts
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');

    // Hardware Nodes
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
    
    // Threshold Configuration
    Route::get('/thresholds', [DashboardController::class, 'thresholds'])->name('admin.thresholds');
    Route::put('/thresholds', [DashboardController::class, 'updateThresholds'])->name('admin.thresholds.update');

    // Gateway & VLAN Infrastructure
    Route::get('/network', [DashboardController::class, 'network'])->name('admin.network');
    Route::post('/network/vlan', [DashboardController::class, 'storeVlan'])->name('admin.network.vlan.store');
    Route::delete('/network/vlan/{id}', [DashboardController::class, 'destroyVlan'])->name('admin.network.vlan.destroy');
});

require __DIR__.'/auth.php';