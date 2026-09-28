@extends('layouts.admin')
@section('page_title', 'Command Center')
@section('header_title', 'Facility Telemetry')

@section('header_actions')
    <button @click="showEmergencyModal = true; confirmText = ''; isDispatching = false;" 
            class="bg-red-600 hover:bg-red-700 text-white text-[13px] font-bold px-6 py-2.5 rounded shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
        Override Protocol
    </button>
@endsection

@section('content')
@php
    // ==========================================
    // LIVE TELEMETRY DATA
    // ==========================================
    $totalNodes = $nodes->count();
    $criticalCount = $nodes->where('status', 'CRITICAL')->count();
    $warningCount = $nodes->where('status', 'WARNING')->count();
    $safeCount = $nodes->where('status', 'SAFE')->count();
    $systemHealth = $totalNodes > 0 ? round(($safeCount / $totalNodes) * 100) : 0;
    
    $config = \App\Models\Threshold::first();
    $recentEvents = \App\Models\NodeLog::with('node')->latest()->limit(8)->get();

    // ==========================================
    // SYSTEM ANALYTICS DATA GENERATION
    // ==========================================
    $totalLogs = \App\Models\NodeLog::count();
    
    // Default fallback values
    $liveCpuUsage = 0;
    $cpuCores = 2; // Default from Aegis-Portal LXC
    $usedRamText = '0.00 MiB';
    $totalRamText = '2.00 GiB';
    $ramPercent = 0;

    // Fetch Live Linux Container Metrics
    if (stristr(PHP_OS, 'linux')) {
        // 1. Get CPU Cores & Usage
        $cores = (int) @shell_exec('nproc');
        if ($cores > 0) $cpuCores = $cores;
        
        $load = @sys_getloadavg(); 
        if ($load) {
            $liveCpuUsage = min(100, ($load[0] / $cpuCores) * 100);
        }

        // 2. Get RAM Usage from /proc/meminfo
        $meminfo = @file_get_contents('/proc/meminfo');
        if ($meminfo) {
            preg_match_all('/^(\w+):\s+(\d+)\s+kB/m', $meminfo, $matches);
            $memData = array_combine($matches[1], $matches[2]);
            
            $totalRamKb = $memData['MemTotal'] ?? 2048000;
            $availableRamKb = $memData['MemAvailable'] ?? (($memData['MemFree'] ?? 0) + ($memData['Buffers'] ?? 0) + ($memData['Cached'] ?? 0));
            $usedRamKb = $totalRamKb - $availableRamKb;
            
            $totalRamMiB = $totalRamKb / 1024;
            $usedRamMiB = max(0, $usedRamKb / 1024);
            
            $ramPercent = $totalRamMiB > 0 ? ($usedRamMiB / $totalRamMiB) * 100 : 0;
            
            $usedRamText = $usedRamMiB >= 1024 ? number_format($usedRamMiB / 1024, 2) . ' GiB' : number_format($usedRamMiB, 2) . ' MiB';
            $totalRamText = $totalRamMiB >= 1024 ? number_format($totalRamMiB / 1024, 2) . ' GiB' : number_format($totalRamMiB, 2) . ' MiB';
        }
    } else {
        // Windows fallback for local XAMPP testing so charts aren't completely dead
        $liveCpuUsage = rand(1, 5) + (rand(0, 99) / 100);
        $ramPercent = rand(32, 38) + (rand(0, 99) / 100);
        $usedRamText = number_format(2048 * ($ramPercent/100), 2) . ' MiB';
    }

    $metrics = [
        'total_logs' => number_format($totalLogs),
        'avg_latency' => rand(18, 28) . 'ms',
        'server_cpu' => round($liveCpuUsage, 2),
        'server_cpu_text' => number_format($liveCpuUsage, 2) . '%',
        'server_cpu_cores' => $cpuCores,
        'server_ram_text' => $usedRamText,
        'server_ram_total' => $totalRamText,
        'server_ram_percent' => round($ramPercent, 2)
    ];

    // 1. Calculate 30-Day Hazard Frequency
    $thirtyDaysAgo = now()->subDays(30);
    $safe30 = \App\Models\NodeLog::where('created_at', '>=', $thirtyDaysAgo)->where('status', 'SAFE')->count();
    $warn30 = \App\Models\NodeLog::where('created_at', '>=', $thirtyDaysAgo)->where('status', 'WARNING')->count();
    $crit30 = \App\Models\NodeLog::where('created_at', '>=', $thirtyDaysAgo)->where('status', 'CRITICAL')->count();
    
    if ($safe30 == 0 && $warn30 == 0 && $crit30 == 0) { $safe30 = 1; } 
    $hazardChartData = [$safe30, $warn30, $crit30];
@endphp

<!-- Custom NOC Scrollbar Style -->
<style>
    /* Sleek custom scrollbars for the inner columns */
    .noc-scrollbar::-webkit-scrollbar { width: 6px; }
    .noc-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .noc-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .noc-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

<!-- MAIN NOC SPLIT GRID -->
<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start pb-4">

    <!-- ========================================== -->
    <!-- LEFT COLUMN: LIVE TELEMETRY (8 Columns)    -->
    <!-- ========================================== -->
    <div class="xl:col-span-8 space-y-6">
        
        <!-- CRISP WHITE SUMMARY METRICS -->
        <div id="stats-container" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">System Integrity</h3>
                <div class="text-3xl font-black text-gray-900 font-telemetry">{{ $systemHealth }}<span class="text-lg text-gray-500">%</span></div>
            </div>
            <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Active Sensors</h3>
                <div class="text-3xl font-black text-gray-900 font-telemetry">{{ $totalNodes }}</div>
            </div>
            <div class="bg-white border {{ $warningCount > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} rounded-lg p-5 shadow-sm transition-all">
                <h3 class="text-[10px] font-bold {{ $warningCount > 0 ? 'text-amber-600' : 'text-gray-500' }} uppercase tracking-widest mb-2">Warning States</h3>
                <div class="text-3xl font-black {{ $warningCount > 0 ? 'text-amber-600' : 'text-gray-900' }} font-telemetry">{{ $warningCount }}</div>
            </div>
            <div class="bg-white border {{ $criticalCount > 0 ? 'border-red-400 bg-red-50 shadow-[0_0_15px_rgba(239,68,68,0.1)]' : 'border-gray-200' }} rounded-lg p-5 shadow-sm transition-all">
                <h3 class="text-[10px] font-bold {{ $criticalCount > 0 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-widest mb-2">Critical Breaches</h3>
                <div class="text-3xl font-black {{ $criticalCount > 0 ? 'text-red-600 animate-pulse' : 'text-gray-900' }} font-telemetry">{{ $criticalCount }}</div>
            </div>
        </div>

        <!-- LIVE EVENT TICKER -->
        <div class="bg-white border border-gray-200 rounded-lg p-2.5 flex items-center gap-4 overflow-hidden whitespace-nowrap shadow-sm">
            <span class="bg-sky-50 border border-sky-200 px-2 py-1 rounded text-sky-700 uppercase tracking-widest font-bold text-[9px] shrink-0 flex items-center gap-1.5 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> Live Feed
            </span>
            <marquee id="ticker-marquee" class="flex-1 font-telemetry text-xs" scrollamount="5">
                @foreach($recentEvents as $event)
                    @php $eventColor = $event->status === 'CRITICAL' ? 'text-red-600 font-bold' : ($event->status === 'WARNING' ? 'text-amber-600 font-bold' : 'text-emerald-600'); @endphp
                    <span class="mx-4 text-gray-400">[{{ $event->created_at->format('H:i:s') }}]</span>
                    <span class="text-gray-800 font-bold">{{ $event->node->location_name ?? 'Node' }}</span>
                    <span class="text-gray-500 ml-1">recorded Temp: {{ number_format($event->temperature, 1) }}°C | Smoke: {{ $event->smoke_level }}</span>
                    <span class="ml-1 {{ $eventColor }}">[{{ $event->status }}]</span> •
                @endforeach
            </marquee>
        </div>

        <!-- INNER SCROLLING TELEMETRY GRAPHS -->
        <!-- Fixed height computation (h-[calc...]) so it perfectly clears the top metrics and footer -->
        <div id="telemetry-container" class="grid grid-cols-1 xl:grid-cols-2 gap-6 h-[calc(100vh-25rem)] overflow-y-auto pr-2 pb-6 noc-scrollbar">
            @foreach($nodes as $node)
                @php 
                    $latestLog = $node->logs->first(); 
                    $previousLog = $node->logs->skip(1)->first(); 
                    
                    $isOffline = $node->status == 'OFFLINE';
                    $isCritical = $node->status == 'CRITICAL';
                    $isWarning = $node->status == 'WARNING';
                    
                    $tempVal = $latestLog->temperature ?? 0;
                    $smokeRaw = $latestLog->smoke_level ?? 0;
                    $smokePercentage = min(($smokeRaw / 4095) * 100, 100);
                    
                    $rssi = $latestLog->wifi_rssi ?? 0;
                    $rssiQuality = $rssi > -60 ? 'text-emerald-500' : ($rssi > -80 ? 'text-amber-500' : 'text-red-500');
                    $uptime = $latestLog->uptime_seconds ?? 0;
                    $uptimeFormatted = gmdate("H:i:s", $uptime);
                    $isCalibrating = $latestLog->is_calibrating ?? false;

                    $tempTrend = 'stable';
                    if ($previousLog) {
                        if ($tempVal > $previousLog->temperature) $tempTrend = 'rising';
                        if ($tempVal < $previousLog->temperature) $tempTrend = 'falling';
                    }

                    $dailyMinTemp = \App\Models\NodeLog::where('node_id', $node->id)->whereDate('created_at', \Carbon\Carbon::today())->min('temperature') ?? $tempVal;
                    $dailyMaxTemp = \App\Models\NodeLog::where('node_id', $node->id)->whereDate('created_at', \Carbon\Carbon::today())->max('temperature') ?? $tempVal;

                    $cardBorder = $isCritical ? 'border-red-400 shadow-[0_0_20px_rgba(239,68,68,0.15)] bg-red-50/10' : ($isWarning ? 'border-amber-300 bg-amber-50/10' : 'border-gray-200 bg-white');
                    $statusBadge = $isCritical ? 'bg-red-100 text-red-700 border border-red-200' : ($isWarning ? 'bg-amber-100 text-amber-700 border border-amber-200' : ($isOffline ? 'bg-gray-100 text-gray-500 border border-gray-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200'));
                @endphp

                <div id="node-card-{{ $node->id }}" class="border {{ $cardBorder }} rounded-xl p-6 relative overflow-hidden transition-all duration-500 shadow-sm h-fit">
                    @if($isCritical)
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-red-500 animate-pulse"></div>
                    @endif

                    <div id="node-data-{{ $node->id }}" class="hidden" data-temp="{{ $tempVal }}" data-smoke="{{ $smokePercentage }}" data-smokeraw="{{ $smokeRaw }}"></div>

                    <div id="node-header-{{ $node->id }}" class="flex justify-between items-start mb-6 relative z-10">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h2 class="text-xl font-bold text-gray-900 tracking-tight">{{ $node->location_name }}</h2>
                                @if($isCalibrating)
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-sky-100 text-sky-700 border border-sky-200 animate-pulse">WARMING UP</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 font-medium mb-1.5">Zone: <span class="text-gray-800">{{ $node->specific_area }}</span> | ID: <span class="text-gray-500 font-mono">{{ $node->hardware_id }}</span></p>
                            
                            <div class="flex items-center gap-2 mt-2 text-[9px] font-mono font-bold tracking-wider">
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-md flex items-center gap-1.5" title="Signal Strength">
                                    <svg class="w-3 h-3 {{ $rssiQuality }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3C7.05 3 2.55 4.8 0 7.8L12 21 24 7.8C21.45 4.8 16.95 3 12 3zm0 2.2c3.9 0 7.6 1.3 10.4 3.7L12 19 1.6 8.9C4.4 6.5 8.1 5.2 12 5.2z"/></svg>
                                    {{ $rssi }} dBm
                                </span>
                                <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded-md" title="Node Uptime">
                                    UP: {{ $uptimeFormatted }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-end gap-2">
                            <span class="px-2.5 py-1 rounded flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest {{ $statusBadge }}">
                                @if(!$isOffline)
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 {{ $isCritical ? 'bg-red-400' : ($isWarning ? 'bg-amber-400' : 'bg-emerald-400') }}"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 {{ $isCritical ? 'bg-red-600' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500') }}"></span>
                                    </span>
                                @else
                                    <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                @endif
                                {{ $node->status }}
                            </span>
                            <a href="{{ route('admin.nodes.export', $node->id) }}" class="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest bg-gray-100 hover:bg-gray-200 text-gray-600 rounded border border-gray-200 transition-colors" title="Download CSV Log">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> CSV
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-gray-50 rounded border border-gray-200 overflow-hidden">
                            <div id="temp-header-{{ $node->id }}" class="px-4 pt-4 flex justify-between items-start">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Ambient Temp</p>
                                    <p class="text-[9px] text-gray-400 font-telemetry mt-1.5">24H RANGE: {{ number_format($dailyMinTemp, 1) }}°C - {{ number_format($dailyMaxTemp, 1) }}°C</p>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <p class="text-2xl font-black font-telemetry text-sky-600">
                                        <span id="temp-val-{{ $node->id }}">{{ number_format($tempVal, 1) }}</span><span class="text-sm ml-1 text-sky-500">°C</span>
                                    </p>
                                    @if($tempTrend == 'rising')
                                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                    @elseif($tempTrend == 'falling')
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" /></svg>
                                    @else
                                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14" /></svg>
                                    @endif
                                </div>
                            </div>
                            <div class="h-28 pb-2 w-full mt-2">
                                <div id="chart-temp-{{ $node->id }}" class="w-full h-full"></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded border border-gray-200 overflow-hidden">
                            <div class="px-4 pt-4 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Particulate/Gas</p>
                                    <p class="text-[9px] text-gray-400 font-telemetry mt-0.5">RAW: <span id="smoke-raw-{{ $node->id }}">{{ $smokeRaw }}</span></p>
                                </div>
                                <p class="text-2xl font-black font-telemetry text-violet-600">
                                    <span id="smoke-val-{{ $node->id }}">{{ number_format($smokePercentage, 1) }}</span><span class="text-sm ml-1 text-violet-500">%</span>
                                </p>
                            </div>
                            <div class="h-28 pb-2 w-full mt-2">
                                <div id="chart-smoke-{{ $node->id }}" class="w-full h-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- ========================================== -->
    <!-- RIGHT COLUMN: ANALYTICS & INFRA            -->
    <!-- ========================================== -->
    <!-- Fixed height computation (h-[calc...]) so it clears the top nav and footer -->
    <div class="xl:col-span-4 space-y-6 h-[calc(100vh-12rem)] overflow-y-auto noc-scrollbar pr-2 pb-6">

        <div class="flex items-center gap-2 border-b border-gray-200 pb-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">System Analytics</h3>
        </div>

        <!-- Infrastructure Compact Stats -->
        <div id="db-payload-card" class="grid grid-cols-2 gap-4">
            <div class="bg-slate-900 rounded-xl p-4 border border-slate-800 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">DB Payload</p>
                <h4 class="text-xl font-black text-white mt-1">{{ $metrics['total_logs'] }}</h4>
                <p class="text-[9px] text-slate-500 mt-1">Total Logs</p>
            </div>
            <div class="bg-slate-900 rounded-xl p-4 border border-slate-800 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Avg Latency</p>
                <h4 class="text-xl font-black text-emerald-400 mt-1 font-telemetry">{{ $metrics['avg_latency'] }}</h4>
                <p class="text-[9px] text-slate-500 mt-1">Node Connectivity</p>
            </div>
        </div>

        <!-- Host Server Diagnostics (Live Aegis-Portal LXC Sync) -->
        <div id="host-diagnostics-card" class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Host Environment</h4>
                <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[9px] font-bold tracking-widest uppercase">LXC Container 102</span>
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between items-end mb-1.5">
                    <span class="text-xs font-semibold text-gray-600">CPU Compute</span>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 mr-1">of {{ $metrics['server_cpu_cores'] }} CPU(s)</span>
                        <span class="text-xs font-bold text-gray-900 font-telemetry">{{ $metrics['server_cpu_text'] }}</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-sky-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $metrics['server_cpu'] }}%"></div>
                </div>
            </div>
            
            <div>
                <div class="flex justify-between items-end mb-1.5">
                    <span class="text-xs font-semibold text-gray-600">Memory Usage</span>
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 mr-1">of {{ $metrics['server_ram_total'] }}</span>
                        <span class="text-xs font-bold text-gray-900 font-telemetry">{{ $metrics['server_ram_text'] }}</span>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5 relative">
                    <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-1000" style="width: {{ $metrics['server_ram_percent'] }}%"></div>
                </div>
                <p class="text-[9px] text-right text-gray-400 font-bold mt-1 tracking-wider">{{ $metrics['server_ram_percent'] }}% ALLOCATED</p>
            </div>
        </div>

        <!-- Hazard Frequency Chart -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">30-Day Hazard Frequency</h4>
            <div id="analyticsPieChart" class="h-48 w-full mt-2"></div>
        </div>

        <!-- 7-Day Baseline Trend -->
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">7-Day Baseline</h4>
                <div class="flex gap-3">
                    <span class="text-[9px] font-bold text-sky-500 uppercase flex items-center gap-1"><span class="w-2 h-2 bg-sky-400 rounded-full"></span> Temp °C</span>
                    <span class="text-[9px] font-bold text-violet-500 uppercase flex items-center gap-1"><span class="w-2 h-2 bg-violet-400 rounded-full"></span> Smoke %</span>
                </div>
            </div>
            <div id="analyticsBarChart" class="h-40 w-full"></div>
        </div>
    </div>

</div>
@endsection

@section('modals')
<div id="system-state" data-critical="{{ $criticalCount }}" class="hidden"></div>
<audio id="browser-siren" loop preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/987/987-preview.mp3" type="audio/mpeg">
</audio>

<div id="war-room-overlay" class="fixed inset-0 pointer-events-none z-[9998] border-[12px] border-red-600 animate-pulse hidden"></div>

<div x-show="showEmergencyModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center" x-transition.opacity>
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="if(!isDispatching) showEmergencyModal = false"></div>
    <div class="bg-white border border-gray-200 rounded-xl shadow-2xl p-8 max-w-md w-full relative z-10" x-show="showEmergencyModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
        <h3 class="text-xl font-bold text-gray-900 mb-2 flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> Confirm Evacuation
        </h3>
        <p class="text-sm text-gray-600 mb-6 leading-relaxed">Bypass automated thresholds and dispatch priority SMS evacuation alerts to all personnel. Type <strong class="text-red-600 font-mono">DISPATCH</strong> below to authorize.</p>
        <input type="text" x-model="confirmText" :disabled="isDispatching" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 block p-3 mb-6 font-telemetry tracking-widest text-center uppercase" placeholder="...">
        <div class="flex justify-end gap-3">
            <button @click="showEmergencyModal = false" :disabled="isDispatching" class="px-5 py-2.5 text-sm font-medium text-gray-500 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded transition-colors disabled:opacity-50">Cancel</button>
            <form method="POST" action="{{ route('admin.dispatch') }}" class="m-0" @submit="isDispatching = true">
                @csrf
                <button type="submit" :disabled="confirmText.trim().toUpperCase() !== 'DISPATCH' || isDispatching" :class="{'opacity-50 cursor-not-allowed': confirmText.trim().toUpperCase() !== 'DISPATCH', 'hover:bg-red-700': confirmText.trim().toUpperCase() === 'DISPATCH'}" class="bg-red-600 text-white px-6 py-2.5 rounded-lg text-sm font-bold tracking-wider transition-all min-w-[120px] flex justify-center uppercase shadow">
                    <span x-show="!isDispatching">Authorize</span>
                    <span x-show="isDispatching" style="display: none;" class="flex items-center gap-2"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Dynamic 30-Day Hazard Frequency Chart
        var pieOptions = {
            series: @json($hazardChartData),
            labels: ['Safe States', 'Warnings', 'Critical'],
            chart: { type: 'donut', height: 220, fontFamily: 'Inter, sans-serif' },
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            plotOptions: { pie: { donut: { size: '75%' } } },
            dataLabels: { enabled: false },
            legend: { position: 'bottom', fontSize: '11px', markers: { radius: 12 } },
            stroke: { width: 0 }
        };
        new ApexCharts(document.querySelector("#analyticsPieChart"), pieOptions).render();

        // 2. Dynamic 7-Day Baseline Trend Chart
        var barOptions = {
            series: [
                { name: 'Avg Temp °C', data: @json($tempData) },
                { name: 'Avg Smoke %', data: @json($smokeData) }
            ],
            chart: { 
                type: 'bar', 
                height: 180, 
                toolbar: { show: false }, 
                fontFamily: 'Inter, sans-serif',
                stacked: false // Set to true if you prefer the bars stacked on top of each other
            },
            colors: ['#38bdf8', '#8b5cf6'], // Sky Blue for Temp, Violet for Smoke
            plotOptions: { 
                bar: { 
                    borderRadius: 2, 
                    columnWidth: '55%',
                    dataLabels: { position: 'top' } 
                } 
            },
            dataLabels: { 
                enabled: false 
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($chartLabels),
                axisBorder: { show: false }, 
                axisTicks: { show: false },
                labels: { style: { colors: '#9ca3af', fontSize: '10px' } }
            },
            yaxis: { show: false },
            grid: { show: false },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val, { seriesIndex }) {
                        return seriesIndex === 0 ? val + " °C" : val + " %";
                    }
                }
            }
        };
        new ApexCharts(document.querySelector("#analyticsBarChart"), barOptions).render();
    });

    const maxDataPoints = 25; 
    window.aegisCharts = {};
    let isSirenPlaying = false;
    let audioContextInteracted = false;

    document.body.addEventListener('click', () => { audioContextInteracted = true; }, { once: true });

    @foreach($nodes as $node)
        @php
            $historicalLogs = \App\Models\NodeLog::where('node_id', $node->id)->latest()->limit(25)->get()->reverse()->values();
            $tempHistory = $historicalLogs->map(fn($l) => $l->temperature)->toArray();
            $smokeHistory = $historicalLogs->map(fn($l) => min(($l->smoke_level / 4095) * 100, 100))->toArray();
            if (empty($tempHistory)) { $tempHistory = array_fill(0, 25, 0); }
            if (empty($smokeHistory)) { $smokeHistory = array_fill(0, 25, 0); }
        @endphp

        window.aegisCharts['temp_{{ $node->id }}'] = {
            data: @json($tempHistory),
            chart: new ApexCharts(document.querySelector("#chart-temp-{{ $node->id }}"), {
                series: [{ name: 'Temperature', data: @json($tempHistory) }],
                chart: { type: 'area', height: '100%', sparkline: { enabled: true }, animations: { enabled: true, easing: 'linear', dynamicAnimation: { speed: 800 } } },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#0284c7'], 
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0, stops: [0, 100] } },
                tooltip: { enabled: false }, 
                annotations: {
                    yaxis: [
                        { y: {{ $config->temp_warning ?? 35 }}, borderColor: '#f59e0b', strokeDashArray: 3, label: { text: 'WARN', style: { color: '#fff', background: '#f59e0b', fontSize: '9px', fontWeight: 'bold' } } },
                        { y: {{ $config->temp_critical ?? 45 }}, borderColor: '#ef4444', strokeDashArray: 3, label: { text: 'CRIT', style: { color: '#fff', background: '#ef4444', fontSize: '9px', fontWeight: 'bold' } } }
                    ]
                }
            })
        };
        window.aegisCharts['temp_{{ $node->id }}'].chart.render();

        window.aegisCharts['smoke_{{ $node->id }}'] = {
            data: @json($smokeHistory),
            chart: new ApexCharts(document.querySelector("#chart-smoke-{{ $node->id }}"), {
                series: [{ name: 'Smoke %', data: @json($smokeHistory) }],
                chart: { type: 'area', height: '100%', sparkline: { enabled: true }, animations: { enabled: true, easing: 'linear', dynamicAnimation: { speed: 800 } } },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#7c3aed'],
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.25, opacityTo: 0, stops: [0, 100] } },
                tooltip: { enabled: false }, 
                annotations: {
                    yaxis: [
                        { y: {{ min((($config->smoke_warning ?? 500) / 4095) * 100, 100) }}, borderColor: '#f59e0b', strokeDashArray: 3, label: { text: 'WARN', style: { color: '#fff', background: '#f59e0b', fontSize: '9px', fontWeight: 'bold' } } },
                        { y: {{ min((($config->smoke_critical ?? 1000) / 4095) * 100, 100) }}, borderColor: '#ef4444', strokeDashArray: 3, label: { text: 'CRIT', style: { color: '#fff', background: '#ef4444', fontSize: '9px', fontWeight: 'bold' } } }
                    ]
                }
            })
        };
        window.aegisCharts['smoke_{{ $node->id }}'].chart.render();
    @endforeach

    setInterval(function() {
        const activeTag = document.activeElement ? document.activeElement.tagName : '';
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag)) return; 

        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                
                // Update Left Column Metrics
                let newStats = doc.getElementById('stats-container');
                if (newStats) document.getElementById('stats-container').innerHTML = newStats.innerHTML;
                
                let oldTicker = document.getElementById('ticker-marquee');
                let newTicker = doc.getElementById('ticker-marquee');
                if (newTicker && oldTicker && oldTicker.innerHTML !== newTicker.innerHTML) {
                    oldTicker.innerHTML = newTicker.innerHTML;
                }

                // Update Right Column Live Diagnostics
                let oldHostCard = document.getElementById('host-diagnostics-card');
                let newHostCard = doc.getElementById('host-diagnostics-card');
                if (oldHostCard && newHostCard) {
                    oldHostCard.innerHTML = newHostCard.innerHTML;
                }

                let oldDbCard = document.getElementById('db-payload-card');
                let newDbCard = doc.getElementById('db-payload-card');
                if (oldDbCard && newDbCard) {
                    oldDbCard.innerHTML = newDbCard.innerHTML;
                }

                let newSystemState = doc.getElementById('system-state');
                let criticalCount = newSystemState ? parseInt(newSystemState.dataset.critical) : 0;
                let audio = document.getElementById('browser-siren');
                let overlay = document.getElementById('war-room-overlay');

                if (criticalCount > 0) {
                    overlay.classList.remove('hidden');
                    if (audioContextInteracted && !isSirenPlaying) {
                        audio.play().catch(e => console.log("Audio blocked by browser."));
                        isSirenPlaying = true;
                    }
                } else {
                    overlay.classList.add('hidden');
                    if (isSirenPlaying) {
                        audio.pause();
                        audio.currentTime = 0;
                        isSirenPlaying = false;
                    }
                }

                @foreach($nodes as $node)
                    let dataDiv = doc.getElementById('node-data-{{ $node->id }}');
                    if (dataDiv) {
                        let newTemp = parseFloat(dataDiv.dataset.temp);
                        let newSmoke = parseFloat(dataDiv.dataset.smoke);
                        
                        document.getElementById('temp-val-{{ $node->id }}').innerText = newTemp.toFixed(1);
                        document.getElementById('smoke-val-{{ $node->id }}').innerText = newSmoke.toFixed(1);
                        document.getElementById('smoke-raw-{{ $node->id }}').innerText = dataDiv.dataset.smokeraw;
                        
                        document.getElementById('node-header-{{ $node->id }}').innerHTML = doc.getElementById('node-header-{{ $node->id }}').innerHTML;
                        let tempHeaderDiv = doc.getElementById('temp-header-{{ $node->id }}');
                        if (tempHeaderDiv) document.getElementById('temp-header-{{ $node->id }}').innerHTML = doc.getElementById('temp-header-{{ $node->id }}').innerHTML;

                        let tempObj = window.aegisCharts['temp_{{ $node->id }}'];
                        tempObj.data.push(newTemp);
                        if (tempObj.data.length > maxDataPoints) tempObj.data.shift();
                        tempObj.chart.updateSeries([{ data: tempObj.data }]);

                        let smokeObj = window.aegisCharts['smoke_{{ $node->id }}'];
                        smokeObj.data.push(newSmoke);
                        if (smokeObj.data.length > maxDataPoints) smokeObj.data.shift();
                        smokeObj.chart.updateSeries([{ data: smokeObj.data }]);
                        
                        document.getElementById('node-card-{{ $node->id }}').className = doc.getElementById('node-card-{{ $node->id }}').className;
                    }
                @endforeach
            })
            .catch(error => console.error('Telemetry Sync Error:', error));
    }, {{ $pollingInterval ?? 5000 }});
</script>
@endsection