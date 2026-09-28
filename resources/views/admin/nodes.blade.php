@extends('layouts.admin')
@section('page_title', 'Hardware Nodes')
@section('header_title', 'Hardware Infrastructure')
@section('header_subtitle', 'Manage physical ESP32 endpoints, monitor network health, and provision zones.')

@section('content')
<div x-data="{ search: '' }" class="space-y-6">
    
    <!-- PAGE HEADER & SEARCH -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-sky-500/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        
        <div class="relative z-10">
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Sensor Directory</h2>
            <p class="text-xs text-gray-500 mt-1 font-medium">Provision logical names or decommission damaged hardware.</p>
        </div>
        
        <div class="relative w-full md:w-80 z-10">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
            <input type="text" x-model="search" placeholder="Search by ID, Zone, or Room..." 
                   class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white block w-full pl-10 p-2.5 transition-all shadow-inner">
        </div>
    </div>

    <!-- HARDWARE CARDS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($nodes as $node)
            @php
                $isCritical = $node->status === 'CRITICAL';
                $isWarning = $node->status === 'WARNING';
                $isOffline = $node->status === 'OFFLINE';
                
                $accentColor = $isCritical ? 'bg-red-500' : ($isWarning ? 'bg-amber-500' : ($isOffline ? 'bg-gray-400' : 'bg-emerald-500'));
                $cardBorder = $isCritical ? 'border-red-200 shadow-[0_4px_20px_rgba(239,68,68,0.1)]' : 'border-gray-200 hover:shadow-md hover:border-gray-300';
                $badgeBg = $isCritical ? 'bg-red-50 text-red-700 border-red-100' : ($isWarning ? 'bg-amber-50 text-amber-700 border-amber-100' : ($isOffline ? 'bg-gray-50 text-gray-600 border-gray-200' : 'bg-emerald-50 text-emerald-700 border-emerald-100'));
            @endphp

            <div x-show="search === '' || '{{ strtolower($node->hardware_id . ' ' . $node->location_name . ' ' . $node->specific_area) }}'.includes(search.toLowerCase())" 
                 x-transition.opacity
                 class="bg-white border {{ $cardBorder }} rounded-xl flex flex-col relative overflow-hidden transition-all duration-300 group">
                
                <!-- Status Top Bar -->
                <div class="h-1.5 w-full {{ $accentColor }} {{ $isCritical ? 'animate-pulse' : '' }}"></div>

                <!-- Card Header: ID & Status -->
                <div class="p-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/30">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">MAC Address / ID</p>
                        <span class="font-telemetry text-sm font-bold text-gray-800">{{ $node->hardware_id }}</span>
                    </div>
                    <span class="px-2.5 py-1 rounded-md flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest border {{ $badgeBg }}">
                        @if(!$isOffline)
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $accentColor }}"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 {{ $accentColor }}"></span>
                            </span>
                        @else
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                        @endif
                        {{ $node->status }}
                    </span>
                </div>

                <!-- Card Body: Network Diagnostics -->
                <div class="px-5 py-4 grid grid-cols-3 gap-2 bg-gray-50/50 border-b border-gray-100">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg> IPv4
                        </div>
                        <span class="text-[11px] font-telemetry text-sky-600 font-bold truncate" title="{{ $node->ip_address ?? 'Awaiting Ping' }}">{{ $node->ip_address ?? 'Offline' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 border-l border-gray-200 pl-3">
                        <div class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Uptime
                        </div>
                        <span class="text-[11px] font-telemetry text-gray-700 truncate">{{ $node->uptime ?? '--' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 border-l border-gray-200 pl-3">
                        <div class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase tracking-widest">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> Latency
                        </div>
                        <span class="text-[11px] font-telemetry text-gray-700">{{ $node->latency ?? '--' }}ms</span>
                    </div>
                </div>

                <!-- Card Body: Configuration Forms & Zonal Overrides -->
                <div class="p-5 flex-1 flex flex-col">
                    <form method="POST" action="{{ route('admin.nodes.update', $node->id) }}" id="update-form-{{ $node->id }}" 
                          class="space-y-4 flex-1"
                          x-data="{ 
                              override: {{ $node->has_custom_thresholds ? 'true' : 'false' }},
                              tempWarn: {{ $node->custom_temp_warning ?? 35 }},
                              tempCrit: {{ $node->custom_temp_critical ?? 45 }},
                              smokeWarn: {{ $node->custom_smoke_warning ?? 500 }},
                              smokeCrit: {{ $node->custom_smoke_critical ?? 1000 }}
                          }">
                        @csrf @method('PUT')
                        
                        <!-- Hidden Inputs for Identity so they aren't lost -->
                        <input type="hidden" name="hardware_id" value="{{ $node->hardware_id }}">
                        
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Zone</label>
                                <input type="text" name="location_name" value="{{ $node->location_name }}" required
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white block p-2 transition-all">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5 block">Room</label>
                                <input type="text" name="specific_area" value="{{ $node->specific_area }}" required
                                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 focus:bg-white block p-2 transition-all">
                            </div>
                        </div>

                        <!-- Zonal Override Toggle -->
                        <div class="pt-2 border-t border-gray-100">
                            <label class="flex items-center justify-between cursor-pointer group">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest group-hover:text-sky-600 transition-colors">Enable Zonal Override</span>
                                <div class="relative">
                                    <input type="checkbox" name="has_custom_thresholds" value="1" class="sr-only" x-model="override">
                                    <div class="block w-8 h-4 bg-gray-200 rounded-full transition-colors" :class="override ? 'bg-sky-500' : 'bg-gray-200'"></div>
                                    <div class="dot absolute left-0.5 top-0.5 bg-white w-3 h-3 rounded-full transition-transform" :class="override ? 'transform translate-x-4' : ''"></div>
                                </div>
                            </label>
                        </div>

                        <!-- Custom Sliders (Reveals when toggle is active) -->
                        <div x-show="override" x-transition.opacity style="display: none;" class="space-y-3 pt-2">
                            <!-- Temp -->
                            <div class="bg-amber-50/50 p-2.5 rounded border border-amber-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-bold text-amber-600 uppercase">Warning Temp</span>
                                    <span class="text-[10px] font-telemetry font-bold text-gray-800" x-text="tempWarn + '°C'"></span>
                                </div>
                                <input type="range" name="custom_temp_warning" x-model="tempWarn" min="20" max="80" step="0.1" class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-amber-500 mb-2">
                                
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-bold text-red-600 uppercase">Critical Temp</span>
                                    <span class="text-[10px] font-telemetry font-bold text-red-600" x-text="tempCrit + '°C'"></span>
                                </div>
                                <input type="range" name="custom_temp_critical" x-model="tempCrit" min="30" max="100" step="0.1" class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                            </div>

                            <!-- Smoke -->
                            <div class="bg-violet-50/50 p-2.5 rounded border border-violet-100">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-bold text-amber-600 uppercase">Warning Smoke</span>
                                    <span class="text-[10px] font-telemetry font-bold text-gray-800" x-text="smokeWarn + ' ppm'"></span>
                                </div>
                                <input type="range" name="custom_smoke_warning" x-model="smokeWarn" min="100" max="3000" step="10" class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-amber-500 mb-2">
                                
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-[9px] font-bold text-red-600 uppercase">Critical Smoke</span>
                                    <span class="text-[10px] font-telemetry font-bold text-red-600" x-text="smokeCrit + ' ppm'"></span>
                                </div>
                                <input type="range" name="custom_smoke_critical" x-model="smokeCrit" min="200" max="4095" step="10" class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-red-600">
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Card Footer: Actions -->
                <div class="p-5 border-t border-gray-100 bg-gray-50/30 flex justify-between items-center mt-auto">
                    <!-- Decommission Button -->
                    <form method="POST" action="{{ route('admin.nodes.destroy', $node->id) }}" onsubmit="return confirm('DANGER: Permanently decommission this sensor?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded transition-colors" title="Decommission Hardware">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                    
                    <div class="flex items-center gap-3">
                        <span class="text-[9px] text-gray-400 font-medium hidden sm:block">Sync: {{ $node->updated_at->diffForHumans() }}</span>
                        <!-- Save Button (Attached to form via ID) -->
                        <button type="submit" form="update-form-{{ $node->id }}" class="bg-white border border-gray-300 hover:border-sky-500 hover:bg-sky-50 hover:text-sky-700 text-gray-700 px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all shadow-sm uppercase flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Save
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State spans full width -->
            <div class="col-span-full p-16 flex flex-col items-center justify-center text-center bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="w-16 h-16 bg-gray-50 border border-gray-100 rounded-full flex items-center justify-center mb-4 relative">
                    <div class="absolute inset-0 rounded-full border border-sky-200 animate-ping opacity-50"></div>
                    <svg class="w-8 h-8 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" /></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">Awaiting Edge Devices</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-md">No ESP32 nodes have checked into the network. Power on a physical node, and it will automatically provision itself here.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection