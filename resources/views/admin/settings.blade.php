@extends('layouts.admin')
@section('page_title', 'System Settings | FireNet')
@section('header_title', 'Global System Configuration')
@section('header_subtitle', 'Manage core institution parameters, telemetry API gateways, and emergency override policies.')

@section('content')
<div class="space-y-8 w-full pb-12">

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- SECTION 1: INSTITUTION & PERFORMANCE -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 tracking-tight">Institution Profile & Performance</h3>
                        <p class="text-[11px] text-gray-500">Core campus identifiers and database operational parameters.</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-2 py-0.5 rounded uppercase tracking-wider">Module Config</span>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Campus Identity -->
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Campus Name / Designation</label>
                    <input type="text" name="campus_name" value="{{ $settings->campus_name }}" required class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Administrative Escalation Email</label>
                    <input type="email" name="admin_email" value="{{ $settings->admin_email }}" required class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50 font-telemetry">
                </div>
                
                <!-- NEW: Sensor Polling -->
                <div class="border-t border-gray-100 pt-5 mt-2 md:col-span-1">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sensor Polling Frequency</label>
                    <select name="sensor_polling_interval" class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50">
                        <option value="3" {{ $settings->sensor_polling_interval == 3 ? 'selected' : '' }}>Aggressive (Every 3 Seconds)</option>
                        <option value="5" {{ $settings->sensor_polling_interval == 5 ? 'selected' : '' }}>Standard (Every 5 Seconds)</option>
                        <option value="10" {{ $settings->sensor_polling_interval == 10 ? 'selected' : '' }}>Balanced (Every 10 Seconds)</option>
                        <option value="30" {{ $settings->sensor_polling_interval == 30 ? 'selected' : '' }}>Eco / Low Bandwidth (Every 30 Seconds)</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Balances network load against real-time emergency responsiveness.</p>
                </div>

                <!-- NEW: DB Retention -->
                <div class="border-t border-gray-100 pt-5 mt-2 md:col-span-1">
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Database Log Retention Policy</label>
                    <select name="log_retention_days" class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50">
                        <option value="15" {{ $settings->log_retention_days == 15 ? 'selected' : '' }}>15 Days (Minimal DB Footprint)</option>
                        <option value="30" {{ $settings->log_retention_days == 30 ? 'selected' : '' }}>30 Days (Standard Audit)</option>
                        <option value="60" {{ $settings->log_retention_days == 60 ? 'selected' : '' }}>60 Days (Extended Audit)</option>
                        <option value="90" {{ $settings->log_retention_days == 90 ? 'selected' : '' }}>90 Days (Enterprise Compliance)</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">Automated pruning of old hazard telemetry to prevent MariaDB bloat.</p>
                </div>
            </div>
        </div>

        <!-- SECTION 2: API GATEWAYS -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 tracking-tight">Pushover Emergency API Gateway</h3>
                        <p class="text-[11px] text-gray-500">External webhook credentials for real-time priority 2 mobile dispatch broadcasts.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Gateway Active
                    </span>
                    <!-- NEW: API Test Button that submits to a DIFFERENT route via formaction -->
                    <button type="submit" formaction="{{ route('admin.settings.test') }}" formmethod="POST" class="bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 px-3 py-1.5 rounded shadow-sm text-[10px] font-bold uppercase tracking-wider transition-colors flex items-center gap-2">
                        <svg class="w-3 h-3 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        Test API Broadcast
                    </button>
                </div>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Application Token</label>
                    <input type="text" name="pushover_app_token" value="{{ $settings->pushover_app_token }}" class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50 font-telemetry tracking-wide">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Group / User Key</label>
                    <input type="text" name="pushover_user_key" value="{{ $settings->pushover_user_key }}" class="w-full text-sm border-gray-200 rounded-lg focus:ring-sky-500 focus:border-sky-500 bg-gray-50/50 font-telemetry tracking-wide">
                </div>
            </div>
        </div>

        <!-- SECTION 3: SYSTEM-WIDE OVERRIDES -->
        <div class="bg-white rounded-xl shadow-sm border border-red-200 overflow-hidden">
            <div class="p-5 border-b border-red-100 bg-red-50/30 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold text-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-red-900 tracking-tight">Emergency Protocol Overrides</h3>
                        <p class="text-[11px] text-red-600/80">Safety interlocks for scheduled maintenance or campus fire drills.</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-red-700 bg-red-100 px-2 py-0.5 rounded uppercase tracking-wider">Critical Control</span>
            </div>
            <div class="p-6 bg-red-50/20">
                <label class="relative flex items-start p-4 rounded-xl border border-red-200 bg-white shadow-sm cursor-pointer hover:bg-red-50/50 transition-all">
                    <div class="flex items-center h-5 mt-0.5">
                        <input type="checkbox" name="alerts_muted" value="1" {{ $settings->alerts_muted ? 'checked' : '' }} class="w-4 h-4 text-red-600 bg-white border-red-300 rounded focus:ring-red-500 focus:ring-2 cursor-pointer">
                    </div>
                    <div class="ml-3">
                        <span class="text-sm font-bold text-red-900 block">Mute Automated Emergency Broadcasts</span>
                        <span class="text-xs text-red-600 font-medium block mt-0.5">When engaged, incoming sensor threshold violations from ESP32 nodes will log locally but <strong class="underline">suppress</strong> external mobile Pushover transmissions.</span>
                    </div>
                </label>
            </div>
        </div>

        <!-- SUBMIT BAR -->
        <div class="flex items-center justify-between pt-2">
            <p class="text-xs text-gray-400 font-medium">Changes take effect immediately across all Proxmox LXC worker nodes.</p>
            <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold py-3 px-6 rounded-lg shadow-sm hover:shadow transition-all uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Commit Global Changes
            </button>
        </div>
    </form>
</div>
@endsection