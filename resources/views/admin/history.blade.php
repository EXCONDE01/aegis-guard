@extends('layouts.admin')
@section('page_title', 'Hazard History | FireNet')
@section('header_title', 'Audit & Compliance Logs')
@section('header_subtitle', 'Historical environmental data and system state changes for incident investigation.')

@section('header_actions')
    <div class="flex items-center gap-4">
        <!-- Live Sync Indicator -->
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase tracking-wider border border-emerald-100 shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> 
            Live Sync: {{ $pollingInterval / 1000 }}s
        </span>

        <!-- Export Button -->
        <a href="{{ route('admin.history.export', request()->all()) }}" 
           class="bg-gray-800 hover:bg-gray-900 text-white text-[13px] font-bold px-5 py-2.5 rounded shadow-sm hover:shadow transition-all flex items-center gap-2 uppercase tracking-wider">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Export CSV Report
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">

    <!-- SUMMARY METRICS -->
    <div id="history-metrics" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Total Logs ({{ $retentionDays }} Days)</h3>
            <div class="text-3xl font-black text-gray-900 font-telemetry">{{ number_format($totalEvents) }}</div>
        </div>
        <div class="bg-white border {{ $criticalBreaches > 0 ? 'border-red-300 bg-red-50' : 'border-gray-200' }} rounded-xl p-6 shadow-sm transition-colors">
            <h3 class="text-[10px] font-bold {{ $criticalBreaches > 0 ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-widest mb-2">Critical Breaches</h3>
            <div class="text-3xl font-black {{ $criticalBreaches > 0 ? 'text-red-600' : 'text-gray-900' }} font-telemetry">{{ number_format($criticalBreaches) }}</div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Most Volatile Zone</h3>
            <div class="text-xl font-bold text-gray-900 mt-2 truncate">{{ $volatileZoneName }}</div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col h-full">
        
        <!-- FILTER BAR -->
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <form method="GET" action="{{ route('admin.history') }}" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-64">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Filter by Zone</label>
                    <select name="node_id" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5 shadow-sm">
                        <option value="">All Zones</option>
                        @foreach($nodes as $node)
                            <option value="{{ $node->id }}" {{ request('node_id') == $node->id ? 'selected' : '' }}>
                                {{ $node->location_name }} ({{ $node->hardware_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="w-full md:w-48">
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">System State</label>
                    <select name="status" class="w-full bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 p-2.5 shadow-sm">
                        <option value="">All States</option>
                        <option value="SAFE" {{ request('status') == 'SAFE' ? 'selected' : '' }}>SAFE</option>
                        <option value="WARNING" {{ request('status') == 'WARNING' ? 'selected' : '' }}>WARNING</option>
                        <option value="CRITICAL" {{ request('status') == 'CRITICAL' ? 'selected' : '' }}>CRITICAL</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide transition-all shadow-sm uppercase">
                        Filter Log
                    </button>
                    @if(request()->has('status') || request()->has('node_id'))
                        <a href="{{ route('admin.history') }}" class="flex-1 md:flex-none bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-lg text-xs font-bold tracking-wide transition-all text-center uppercase shadow-sm">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- DATA TABLE -->
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-[10px] text-gray-500 uppercase tracking-widest bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-bold">Timestamp</th>
                        <th class="px-6 py-4 font-bold">Hardware / Zone</th>
                        <th class="px-6 py-4 font-bold text-right">Ambient Temp</th>
                        <th class="px-6 py-4 font-bold text-right">Particulate Raw</th>
                        <th class="px-6 py-4 font-bold text-center">Trigger State</th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        @php
                            $isCritical = $log->status === 'CRITICAL';
                            $isWarning = $log->status === 'WARNING';
                            $dotColor = $isCritical ? 'bg-red-500' : ($isWarning ? 'bg-amber-500' : 'bg-emerald-500');
                            $badgeBg = $isCritical ? 'bg-red-50 text-red-700 border-red-100' : ($isWarning ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100');
                        @endphp
                        <tr class="hover:bg-sky-50/30 transition-colors bg-white">
                            <td class="px-6 py-4 font-telemetry whitespace-nowrap text-gray-900">
                                {{ $log->created_at->format('Y-m-d') }} <span class="text-gray-400 ml-1">{{ $log->created_at->format('H:i:s') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $log->node->location_name ?? 'Decommissioned Node' }}</div>
                                <div class="font-telemetry text-[10px] text-gray-400 mt-0.5">{{ $log->node->hardware_id ?? '--' }}</div>
                            </td>
                            <td class="px-6 py-4 font-telemetry text-right font-bold {{ $isCritical ? 'text-red-600' : 'text-sky-600' }}">
                                {{ $log->temperature }}°C
                            </td>
                            <td class="px-6 py-4 font-telemetry text-right font-bold {{ $isCritical ? 'text-red-600' : 'text-violet-600' }}">
                                {{ $log->smoke_level }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-[9px] font-bold uppercase tracking-widest border {{ $badgeBg }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ $log->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    <p class="text-sm font-bold text-gray-900">No logs found</p>
                                    <p class="text-xs text-gray-500 mt-1">Adjust your filters or wait for edge devices to transmit data.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
            <div id="history-pagination" class="p-5 border-t border-gray-100 bg-gray-50 shrink-0">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fetch live logs directly respecting the Global Settings Polling Frequency
    setInterval(function() {
        // Halt fetching if the user is currently typing or using the filter dropdowns
        const activeTag = document.activeElement ? document.activeElement.tagName : '';
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(activeTag)) return; 

        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                let doc = new DOMParser().parseFromString(html, 'text/html');
                
                // 1. Seamlessly update the table body
                let newTableBody = doc.getElementById('history-table-body');
                if (newTableBody) document.getElementById('history-table-body').innerHTML = newTableBody.innerHTML;

                // 2. Seamlessly update the summary metrics
                let newMetrics = doc.getElementById('history-metrics');
                if (newMetrics) document.getElementById('history-metrics').innerHTML = newMetrics.innerHTML;

                // 3. Seamlessly update pagination links (if available)
                let newPagination = doc.getElementById('history-pagination');
                let oldPagination = document.getElementById('history-pagination');
                if (newPagination && oldPagination) {
                    oldPagination.innerHTML = newPagination.innerHTML;
                }
            })
            .catch(error => console.error('History Sync Error:', error));

    }, {{ $pollingInterval }}); // Uses the exact dynamic interval from your Settings
</script>
@endsection