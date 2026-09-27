@extends('layouts.admin')
@section('page_title', 'Operator Profile | FireNet')
@section('header_title', 'Operator Settings')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- LEFT COLUMN: OPERATOR CLEARANCE DOSSIER (4 COLS) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Operator Badge Card -->
            <div class="bg-white border border-gray-200/80 rounded-xl p-6 shadow-sm">
                <div class="flex items-center gap-4 pb-6 border-b border-gray-100">
                    <div class="relative">
                        <div class="w-14 h-14 rounded-xl bg-slate-900 text-white font-telemetry font-bold text-xl flex items-center justify-center shadow-md">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white ring-1 ring-emerald-500/20"></span>
                    </div>
                    <div class="overflow-hidden">
                        <h3 class="text-base font-bold text-gray-900 truncate">{{ auth()->user()->name }}</h3>
                        <p class="text-xs text-gray-500 font-telemetry truncate">{{ auth()->user()->email }}</p>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-2 rounded bg-sky-50 border border-sky-200/60 text-[10px] font-bold uppercase tracking-wider text-sky-700">
                            {{ auth()->user()->role ?? 'Admin' }} Clearance
                        </span>
                    </div>
                </div>

                <!-- Telemetry Privileges -->
                <div class="pt-5 space-y-3.5">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">Access Authorization</p>
                    
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">Telemetry Scope</span>
                        <span class="font-semibold text-gray-800">Unrestricted (All Nodes)</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">Emergency Override</span>
                        <span class="font-semibold text-emerald-600 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Armed
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-1.5 border-b border-gray-50">
                        <span class="text-gray-500">Database Restoration</span>
                        <span class="font-semibold text-gray-800">{{ auth()->user()->role === 'admin' ? 'Granted' : 'Restricted' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs py-1.5">
                        <span class="text-gray-500">Session Protocol</span>
                        <span class="font-telemetry text-gray-600 text-[11px]">TLS v1.3 / SECURE</span>
                    </div>
                </div>
            </div>

            <!-- Fast Status Tip -->
            <div class="bg-slate-900 rounded-xl p-5 border border-slate-800 text-white shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-200">Dispatch Routing Notice</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    SMS and cellular alerts are routed through the primary hardware gateway. Ensure the mobile number includes country code formatting (+63) for reliable alert delivery.
                </p>
            </div>
        </div>

        <!-- RIGHT COLUMN: CONFIGURATION FORMS (8 COLS) -->
        <div class="lg:col-span-8 space-y-6">

            <!-- OPERATOR INFORMATION & ALERT TRIGGERS -->
            <div class="bg-white border border-gray-200/80 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4.5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Communication & Alert Triggers</h3>
                        <p class="text-xs text-gray-500">Manage destination contacts and automatic alert routing thresholds.</p>
                    </div>
                    @if (session('status') === 'profile-updated')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 animate-pulse">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Changes saved
                        </span>
                    @endif
                </div>

                <form method="post" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Operator Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block px-3.5 py-2.5 shadow-sm transition">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Official Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block px-3.5 py-2.5 shadow-sm transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Emergency Mobile Hotline (SMS Dispatch)</label>
                        <div class="relative">
                            <input type="text" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" placeholder="+63 900 000 0000"
                                class="w-full bg-white border border-gray-300 text-gray-900 font-telemetry text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block pl-3.5 pr-20 py-2.5 shadow-sm transition">
                            <span class="absolute right-3 top-2.5 text-[10px] font-bold uppercase tracking-wider text-gray-400 bg-gray-100 px-2 py-0.5 rounded">GSM</span>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Directly receives synthesized hazard alerts when thresholds trip.</p>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Automated Trigger Policies</label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Warning Toggle Box -->
                            <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:border-amber-300 bg-white hover:bg-amber-50/20 cursor-pointer transition">
                                <input type="hidden" name="alert_warnings" value="0">
                                <input type="checkbox" name="alert_warnings" value="1" {{ $user->alert_warnings ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 text-amber-500 border-gray-300 rounded focus:ring-amber-500">
                                <div class="ml-3">
                                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> Warning Thresholds
                                    </span>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Route SMS when elevated gas or temperature levels persist above baseline.</p>
                                </div>
                            </label>

                            <!-- Critical Toggle Box -->
                            <label class="relative flex items-start p-4 rounded-xl border border-gray-200 hover:border-red-300 bg-white hover:bg-red-50/20 cursor-pointer transition">
                                <input type="hidden" name="alert_critical" value="0">
                                <input type="checkbox" name="alert_critical" value="1" {{ $user->alert_critical ? 'checked' : '' }}
                                    class="w-4 h-4 mt-0.5 text-red-600 border-gray-300 rounded focus:ring-red-600">
                                <div class="ml-3">
                                    <span class="text-xs font-bold text-gray-900 flex items-center gap-1.5">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Critical Breaches
                                    </span>
                                    <p class="text-[11px] text-gray-500 mt-0.5">Immediate high-priority override dispatch for confirmed hazard events.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition">
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>

            <!-- SECURITY & CREDENTIALS -->
            <div class="bg-white border border-gray-200/80 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4.5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Security Credentials</h3>
                        <p class="text-xs text-gray-500">Update operator authentication passphrase.</p>
                    </div>
                    @if (session('status') === 'password-updated')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded bg-emerald-50 border border-emerald-200 text-xs font-bold text-emerald-700 animate-pulse">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Password updated
                        </span>
                    @endif
                </div>

                <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-4">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Current Password</label>
                            <input type="password" name="current_password" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block px-3.5 py-2.5 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">New Password</label>
                            <input type="password" name="password" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block px-3.5 py-2.5 shadow-sm transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 block px-3.5 py-2.5 shadow-sm transition">
                        </div>
                    </div>

                    <div class="flex justify-end pt-3">
                        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider transition">
                            Update Passphrase
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection