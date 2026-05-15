<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes: Aegis-Guard
|--------------------------------------------------------------------------
*/

// Public Kiosk Mode
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Secured Administrative Routes
Route::middleware('auth')->group(function () {
    
    Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');
    Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');

    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');

    // System Admin Tools
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
    
    // NEW: Threshold Management
    Route::get('/thresholds', [DashboardController::class, 'thresholds'])->name('admin.thresholds');
    Route::put('/thresholds', [DashboardController::class, 'updateThresholds'])->name('admin.thresholds.update');
});

require __DIR__.'/auth.php';