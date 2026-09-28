@extends('layouts.admin')
@section('page_title', 'Thresholds')
@section('header_title', 'Global Hazard Thresholds')
@section('header_subtitle', 'Define the baseline environmental parameters, configure hardware calibration offsets, and monitor compliance.')

@section('content')
<!-- ALPINE.JS STATE MANAGEMENT FOR INTERACTIVE SLIDERS -->
<div x-data="{ 
    tempWarn: {{ $threshold->temp_warning ?? 35 }}, 
    tempCrit: {{ $threshold->temp_critical ?? 45 }},
    smokeWarn: {{ $threshold->smoke_warning ?? 500 }},
    smokeCrit: {{ $threshold->smoke_critical ?? 1000 }}
}">
    <!-- REMOVED max-w-5xl FROM HERE -->
    <form method="POST" action="{{ route('admin.thresholds.update') }}" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden w-full">
        @csrf @method('PUT')
        
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">Threshold Configuration</h2>
                <p class="text-xs text-gray-500 mt-1 font-medium">Modifying these values will dynamically re-evaluate all active nodes instantly.</p>
            </div>
            <button type="submit" class="hidden md:flex bg-sky-600 hover:bg-sky-700 text-white px-5 py-2 rounded-lg text-xs font-bold tracking-wide transition-all shadow-sm uppercase items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Apply Globally
            </button>
        </div>

        <div class="p-8 grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- TEMPERATURE CONFIGURATION -->
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-2 border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-sky-50 border border-sky-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800">Temperature Limits (°C)</h3>
                    </div>
                    <!-- Hardware Calibration Offset -->
                    <div class="flex items-center gap-2">
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Calibration Offset</label>
                        <input type="number" step="0.1" name="temp_offset" value="{{ $threshold->temp_offset ?? 0 }}" class="w-16 bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded p-1 text-center font-telemetry">
                    </div>
                </div>
                
                <!-- Temp Warning Slider -->
                <div class="bg-amber-50/50 border border-amber-100 rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-bold text-amber-600 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Warning Level
                        </label>
                        <span class="text-xl font-telemetry font-bold text-gray-900" x-text="tempWarn + '°C'"></span>
                    </div>
                    <input type="range" x-model="tempWarn" name="temp_warning" min="20" max="80" step="0.1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
                </div>
                
                <!-- Temp Critical Slider -->
                <div class="bg-red-50/50 border border-red-100 rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-bold text-red-600 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Evacuation Level
                        </label>
                        <span class="text-xl font-telemetry font-bold text-red-600" x-text="tempCrit + '°C'"></span>
                    </div>
                    <input type="range" x-model="tempCrit" name="temp_critical" min="30" max="100" step="0.1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                </div>
            </div>

            <!-- SMOKE CONFIGURATION -->
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-2 border-b border-gray-100 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-violet-50 border border-violet-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-sm font-bold text-gray-800">Particulate Density (PPM)</h3>
                    </div>
                    <!-- Hardware Calibration Offset -->
                    <div class="flex items-center gap-2">
                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Calibration Offset</label>
                        <input type="number" step="1" name="smoke_offset" value="{{ $threshold->smoke_offset ?? 0 }}" class="w-16 bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded p-1 text-center font-telemetry">
                    </div>
                </div>
                
                <!-- Smoke Warning Slider -->
                <div class="bg-amber-50/50 border border-amber-100 rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-bold text-amber-600 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Warning Level
                        </label>
                        <span class="text-xl font-telemetry font-bold text-gray-900" x-text="smokeWarn"></span>
                    </div>
                    <input type="range" x-model="smokeWarn" name="smoke_warning" min="100" max="3000" step="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-amber-500">
                </div>
                
                <!-- Smoke Critical Slider -->
                <div class="bg-red-50/50 border border-red-100 rounded-lg p-5">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-bold text-red-600 uppercase tracking-widest flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Evacuation Level
                        </label>
                        <span class="text-xl font-telemetry font-bold text-red-600" x-text="smokeCrit"></span>
                    </div>
                    <input type="range" x-model="smokeCrit" name="smoke_critical" min="200" max="4095" step="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                </div>
            </div>
        </div>
        
        <!-- COMPLIANCE AUDIT TRAIL FOOTER -->
        <div class="p-6 border-t border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Compliance Audit Trail</p>
                    <p class="text-xs text-gray-600 font-medium mt-0.5">
                        Last modified by <strong class="text-gray-900">{{ $threshold->updated_by_name ?? 'System' }}</strong> 
                        on <span class="font-telemetry text-[11px]">{{ $threshold->updated_at ? $threshold->updated_at->format('M d, Y H:i:s') : 'Never' }}</span>
                    </p>
                </div>
            </div>
            
            <button type="submit" class="w-full md:w-auto bg-sky-600 hover:bg-sky-700 text-white px-6 py-3 rounded-lg text-xs font-bold tracking-wide transition-all shadow-sm uppercase flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                Apply Globally
            </button>
        </div>
    </form>
    
    <!-- ADVANCED: ZONAL OVERRIDES PREVIEW -->
    <div class="mt-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden w-full">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-sm font-bold text-gray-900 tracking-tight flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Zonal Overrides
                </h2>
                <p class="text-[11px] text-gray-500 mt-1 font-medium">Nodes configured to bypass these global defaults. (Manage these inside the <a href="{{ route('admin.nodes') }}" class="text-sky-600 hover:underline">Sensor Nodes</a> module).</p>
            </div>
        </div>
        
        @if(isset($overrideNodes) && $overrideNodes->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($overrideNodes as $node)
                    <div class="p-5 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">{{ $node->location_name }} <span class="text-gray-400 mx-1">/</span> {{ $node->specific_area }}</h3>
                            <p class="text-[10px] text-gray-500 font-telemetry mt-1">MAC/ID: {{ $node->hardware_id }}</p>
                        </div>
                        <div class="flex gap-6 bg-white border border-gray-100 rounded p-2.5 shadow-sm">
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-amber-600 uppercase tracking-widest mb-0.5">Temp Limits</p>
                                <p class="text-xs font-bold text-gray-800 font-telemetry">{{ $node->custom_temp_warning }} <span class="text-gray-300">|</span> <span class="text-red-600">{{ $node->custom_temp_critical }}</span> °C</p>
                            </div>
                            <div class="text-right border-l border-gray-100 pl-6">
                                <p class="text-[9px] font-bold text-violet-600 uppercase tracking-widest mb-0.5">Smoke Limits</p>
                                <p class="text-xs font-bold text-gray-800 font-telemetry">{{ $node->custom_smoke_warning }} <span class="text-gray-300">|</span> <span class="text-red-600">{{ $node->custom_smoke_critical }}</span> ppm</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-6 flex flex-col items-center justify-center text-center">
                 <div class="w-12 h-12 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-sm font-bold text-gray-700">All Nodes Utilizing Global Defaults</p>
                <p class="text-xs text-gray-500 mt-1">No custom hardware overrides have been detected on the network.</p>
            </div>
        @endif
    </div>
</div>
@endsection