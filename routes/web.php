<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes: Aegis-Guard Kiosk Mode
|--------------------------------------------------------------------------
*/

// Module 1: Real-Time Campus Map
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Module 5: Hazard History Logs
Route::get('/history', [DashboardController::class, 'history'])->name('admin.history');

// Emergency Override: Manual Alert Dispatch
Route::post('/dispatch', [DashboardController::class, 'dispatchAlert'])->name('admin.dispatch');

// Module 3: Alert Contacts Management
Route::get('/contacts', [DashboardController::class, 'contacts'])->name('admin.contacts');
Route::post('/contacts', [DashboardController::class, 'storeContact'])->name('admin.contacts.store');
Route::delete('/contacts/{id}', [DashboardController::class, 'destroyContact'])->name('admin.contacts.destroy');