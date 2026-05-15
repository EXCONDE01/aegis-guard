<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Hazard History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-[#F8FAFC] font-sans text-slate-900 overflow-hidden">
    <div class="w-64 bg-[#0F172A] text-white flex flex-col shadow-2xl z-50">
        <div class="p-6">
            <h2 class="text-2xl font-black text-indigo-400">Aegis-Guard</h2>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">LSPU Campus</p>
        </div>
        <nav class="flex-1 p-4 space-y-2 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800/50 rounded-xl text-slate-400 transition-all">
              📡 Real-Time Map
            </a>
            <a href="{{ route('admin.history') }}" class="flex items-center gap-3 p-3 bg-indigo-600/10 text-indigo-400 rounded-xl ring-1 ring-indigo-500/20">
              📜 Hazard History Logs
            </a>
            <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800/50 rounded-xl text-slate-400 transition-all">
              📞 Alert Contacts
            </a>
        </nav>
        <div class="p-6 bg-[#0B1120] text-xs font-bold text-white flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> 
            Campus Director
        </div>
    </div>

    <div class="flex-1 flex flex-col h-full">
        <header class="bg-white/60 backdrop-blur-xl border-b border-slate-200 p-8 sticky top-0 z-30 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Hazard History Logs</h1>
                <p class="text-sm text-slate-500 font-semibold mt-1">Post-Disaster Analysis & Audit Trail</p>
            </div>
            <button class="bg-slate-800 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest flex items-center gap-2 hover:bg-slate-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export CSV
            </button>
        </header>

        <main class="p-8 flex-1 overflow-y-auto">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black tracking-widest text-slate-400 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Status Event</th>
                            <th class="px-6 py-4">Metrics (Temp / Smoke)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">
                                {{ $log->created_at->format('M d, Y - H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-slate-800">{{ $log->node->location_name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $log->node->specific_area ?? $log->node->hardware_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $log->status == 'CRITICAL' ? 'bg-red-100 text-red-600' : ($log->status == 'WARNING' ? 'bg-amber-100 text-amber-600' : 'bg-green-100 text-green-600') }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                <span class="{{ $log->temperature > 40 ? 'text-red-600 font-bold' : '' }}">{{ $log->temperature }}°C</span> / 
                                <span class="{{ $log->smoke_level > 10 ? 'text-red-600 font-bold' : '' }}">{{ $log->smoke_level }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($logs->isEmpty())
                <div class="p-8 text-center text-slate-500 font-medium">
                    No historical logs recorded yet.
                </div>
                @endif
            </div>
            
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </main>
    </div>
</body>
</html>