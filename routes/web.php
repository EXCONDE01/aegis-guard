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
    Route::get('/history/export', [DashboardController::class, 'exportHistoryCsv'])->name('admin.history.export');
    Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
    Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
    Route::post('/contacts/{id}/test-ping', [DashboardController::class, 'testContactPing'])->name('admin.contacts.test');
    Route::patch('/contacts/{id}/toggle-status', [DashboardController::class, 'toggleContactStatus'])->name('admin.contacts.toggle');
    Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');
    Route::get('/nodes/{id}/export', [DashboardController::class, 'exportCsv'])->name('admin.nodes.export');

    // ==========================================
    // MODULE 2: HARDWARE & SENSORS
    // ==========================================
    Route::get('/nodes', [DashboardController::class, 'nodes'])->name('admin.nodes');
    Route::get('/nodes/telemetry', [DashboardController::class, 'nodesTelemetry'])->name('admin.nodes.telemetry');
    Route::put('/nodes/{id}', [DashboardController::class, 'updateNode'])->name('admin.nodes.update');
    Route::delete('/nodes/{id}', [DashboardController::class, 'destroyNode'])->name('admin.nodes.destroy');
    Route::get('/thresholds', [DashboardController::class, 'thresholds'])->name('admin.thresholds');
    Route::put('/thresholds', [DashboardController::class, 'updateThresholds'])->name('admin.thresholds.update');

    // ==========================================
    // MODULE 3: SYSTEM BACKUPS
    // ==========================================
    Route::get('/backups', [DashboardController::class, 'showBackups'])->name('admin.backups.index');
    Route::post('/backups/generate', [DashboardController::class, 'generateBackup'])->name('admin.backups.generate');
    Route::get('/backups/download/{filename}', [DashboardController::class, 'downloadBackup'])->name('admin.backups.download');
    Route::post('/backups/restore/{filename}', [DashboardController::class, 'restoreBackup'])->name('admin.backups.restore');

    // ==========================================
    // MODULE 4: USER MANAGEMENT (IT ADMIN ONLY)
    // ==========================================
    Route::get('/users', [DashboardController::class, 'users'])->name('admin.users');
    Route::post('/users', [DashboardController::class, 'storeUser'])->name('admin.users.store');
    Route::post('/users/delete/{id}', [DashboardController::class, 'destroyUser'])->name('admin.users.destroy');

    // ==========================================
    // MODULE 5: SYSTEM SETTINGS
    // ==========================================
    Route::get('/settings', [DashboardController::class, 'settings'])->name('admin.settings');
    Route::put('/settings', [DashboardController::class, 'updateSettings'])->name('admin.settings.update');
});

require __DIR__.'/auth.php';