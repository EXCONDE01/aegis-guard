@extends('layouts.admin')
@section('page_title', 'Command Center | FireNet')
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
    $totalNodes = $nodes->count();
    $criticalCount = $nodes->where('status', 'CRITICAL')->count();
    $warningCount = $nodes->where('status', 'WARNING')->count();
    $safeCount = $nodes->where('status', 'SAFE')->count();
    $systemHealth = $totalNodes > 0 ? round(($safeCount / $totalNodes) * 100) : 0;
    
    $config = \App\Models\Threshold::first();
    $recentEvents = \App\Models\NodeLog::with('node')->latest()->limit(8)->get();
@endphp

<!-- CRISP WHITE SUMMARY METRICS -->
<div id="stats-container" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">System Integrity</h3>
        <div class="text-4xl font-black text-gray-900 font-telemetry">{{ $systemHealth }}<span class="text-lg text-gray-500">%</span></div>
    </div>
    <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Active Sensors</h3>
        <div class="text-4xl font-black text-gray-900 font-telemetry">{{ $totalNodes }}</div>
    </div>
    <div class="bg-white border {{ $warningCount > 0 ? 'border-amber-300 bg-amber-50' : 'border-gray-200' }} rounded-lg p-6 shadow-sm transition-all">
        <h3 class="text-[10px] font-bold {{ $warningCount > 0 ? 'text-amber-600' : 'text-gray-500' }} uppercase tracking-widest mb-2">Warning States</h3>
        <div class="text-4xl font-black {{ $warningCount > 0 ? 'text-amber-600' : 'text-gray-900' }} font-telemetry">{{ $warningCount }}</div>
    </div>
    <div class="bg-white border {{ $criticalCount > 0 ? 'border-red-400 bg-red-50 shadow-[0_0_15px_rgba(239,68,68,0.1)]' : 'border-gray-200' }} rounded-lg p-6 shadow-sm transition-all">
        <h3 class="text-[10px] font-bold {{ $criticalCount > 0 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-widest mb-2">Critical Breaches</h3>
        <div class="text-4xl font-black {{ $criticalCount > 0 ? 'text-red-600 animate-pulse' : 'text-gray-900' }} font-telemetry">{{ $criticalCount }}</div>
    </div>
</div>

<!-- LIVE EVENT TICKER -->
<div class="bg-white border border-gray-200 rounded-lg p-2.5 flex items-center gap-4 overflow-hidden whitespace-nowrap shadow-sm mb-6">
    <span class="bg-sky-50 border border-sky-200 px-2 py-1 rounded text-sky-700 uppercase tracking-widest font-bold text-[9px] shrink-0 flex items-center gap-1.5 shadow-sm">
        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-ping"></span> Live Feed
    </span>
    <marquee id="ticker-marquee" class="flex-1 font-telemetry text-xs" scrollamount="5">
        @foreach($recentEvents as $event)
            @php $eventColor = $event->status === 'CRITICAL' ? 'text-red-600 font-bold' : ($event->status === 'WARNING' ? 'text-amber-600 font-bold' : 'text-emerald-600'); @endphp
            <span class="mx-4 text-gray-400">[{{ $event->created_at->format('H:i:s') }}]</span>
            <span class="text-gray-800 font-bold">{{ $event->node->location_name ?? 'Node' }}</span>
            <span class="text-gray-500 ml-1">recorded Temp: {{ $event->temperature }}°C | Smoke: {{ $event->smoke_level }}</span>
            <span class="ml-1 {{ $eventColor }}">[{{ $event->status }}]</span> •
        @endforeach
    </marquee>
</div>

<!-- SCROLLING TELEMETRY GRAPHS -->
<div id="telemetry-container" class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    @foreach($nodes as $node)
        @php 
            $latestLog = $node->logs->first(); 
            $isOffline = $node->status == 'OFFLINE';
            $isCritical = $node->status == 'CRITICAL';
            $isWarning = $node->status == 'WARNING';
            
            $tempVal = $latestLog->temperature ?? 0;
            $smokeRaw = $latestLog->smoke_level ?? 0;
            $smokePercentage = min(($smokeRaw / 4095) * 100, 100);
            
            $cardBorder = $isCritical ? 'border-red-400 shadow-[0_0_20px_rgba(239,68,68,0.15)] bg-red-50/10' : ($isWarning ? 'border-amber-300 bg-amber-50/10' : 'border-gray-200 bg-white');
            $statusBadge = $isCritical ? 'bg-red-100 text-red-700 border border-red-200' : ($isWarning ? 'bg-amber-100 text-amber-700 border border-amber-200' : ($isOffline ? 'bg-gray-100 text-gray-500 border border-gray-200' : 'bg-emerald-50 text-emerald-600 border border-emerald-200'));
        @endphp

        <!-- Node Card -->
        <div id="node-card-{{ $node->id }}" class="border {{ $cardBorder }} rounded-xl p-6 relative overflow-hidden transition-all duration-500 shadow-sm">
            @if($isCritical)
                <div class="absolute top-0 left-0 w-full h-1.5 bg-red-500 animate-pulse"></div>
            @endif

            <div id="node-data-{{ $node->id }}" class="hidden" data-temp="{{ $tempVal }}" data-smoke="{{ $smokePercentage }}" data-smokeraw="{{ $smokeRaw }}"></div>

            <!-- Header Section -->
            <div id="node-header-{{ $node->id }}" class="flex justify-between items-start mb-6 relative z-10">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1 tracking-tight">{{ $node->location_name }}</h2>
                    <p class="text-xs text-gray-500 font-medium">Zone: <span class="text-gray-800">{{ $node->specific_area }}</span> | ID: <span class="text-gray-500 font-mono">{{ $node->hardware_id }}</span></p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.nodes.export', $node->id) }}" class="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest bg-gray-100 hover:bg-gray-200 text-gray-600 rounded border border-gray-200 transition-colors" title="Download CSV Log">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg> CSV
                    </a>
                    
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
                </div>
            </div>

            <!-- Running Graphs Section -->
            <div class="space-y-4">
                <!-- Temperature Graph -->
                <div class="bg-gray-50 rounded border border-gray-200 relative overflow-hidden h-32">
                    <div class="absolute top-3 left-4 right-4 flex justify-between items-start z-10 pointer-events-none">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Ambient Temp</p>
                        <p class="text-2xl font-black font-telemetry text-sky-600">
                            <span id="temp-val-{{ $node->id }}">{{ $tempVal }}</span><span class="text-sm ml-1 text-sky-500">°C</span>
                        </p>
                    </div>
                    <div class="absolute inset-0 pt-6"><div id="chart-temp-{{ $node->id }}" class="w-full h-full"></div></div>
                </div>

                <!-- Smoke Density Graph -->
                <div class="bg-gray-50 rounded border border-gray-200 relative overflow-hidden h-32">
                    <div class="absolute top-3 left-4 right-4 flex justify-between items-start z-10 pointer-events-none">
                        <div>
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Particulate/Gas</p>
                            <p class="text-[9px] text-gray-400 font-telemetry mt-0.5">RAW: <span id="smoke-raw-{{ $node->id }}">{{ $smokeRaw }}</span></p>
                        </div>
                        <p class="text-2xl font-black font-telemetry text-violet-600">
                            <span id="smoke-val-{{ $node->id }}">{{ number_format($smokePercentage, 1) }}</span><span class="text-sm ml-1 text-violet-500">%</span>
                        </p>
                    </div>
                    <div class="absolute inset-0 pt-6"><div id="chart-smoke-{{ $node->id }}" class="w-full h-full"></div></div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('modals')
<!-- INVISIBLE SYSTEM STATE FOR JS ALERTING & AUDIO -->
<div id="system-state" data-critical="{{ $criticalCount }}" class="hidden"></div>
<audio id="browser-siren" loop preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/987/987-preview.mp3" type="audio/mpeg">
</audio>

<!-- WAR ROOM FLASHING BORDER -->
<div id="war-room-overlay" class="fixed inset-0 pointer-events-none z-[9998] border-[12px] border-red-600 animate-pulse hidden"></div>

<!-- EMERGENCY MODAL (Now correctly placed above everything) -->
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
                tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } },
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
                tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } },
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
                
                let newStats = doc.getElementById('stats-container');
                if (newStats) document.getElementById('stats-container').innerHTML = newStats.innerHTML;
                
                let oldTicker = document.getElementById('ticker-marquee');
                let newTicker = doc.getElementById('ticker-marquee');
                if (newTicker && oldTicker && oldTicker.innerHTML !== newTicker.innerHTML) {
                    oldTicker.innerHTML = newTicker.innerHTML;
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
                        
                        document.getElementById('temp-val-{{ $node->id }}').innerText = newTemp;
                        document.getElementById('smoke-val-{{ $node->id }}').innerText = newSmoke.toFixed(1);
                        document.getElementById('smoke-raw-{{ $node->id }}').innerText = dataDiv.dataset.smokeraw;
                        document.getElementById('node-header-{{ $node->id }}').innerHTML = doc.getElementById('node-header-{{ $node->id }}').innerHTML;

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
    }, 4000);
</script>
@endsection