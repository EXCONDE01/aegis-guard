<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes: Aegis-Guard
|--------------------------------------------------------------------------
*/

// Public Kiosk Mode: Anyone on the local network can view the Live Map
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Secured Administrative Routes
Route::middleware('auth')->group(function () {
    
    // Emergency Override: Manual Alert Dispatch
    Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');

    // Module 5: Hazard History Logs
    Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');

    // Module 3: Alert Contacts Management
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');

    // Module 2: Node/Sensor Management (System Admin)
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
});

require __DIR__.'/auth.php';