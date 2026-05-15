<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Hazard History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-slate-950 font-sans text-slate-300 overflow-hidden">
    
    <div class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col z-50">
        <div class="p-6">
            <h2 class="text-xl font-bold text-white tracking-tight">Aegis-Guard</h2>
            <p class="text-[10px] text-slate-500 uppercase font-semibold tracking-widest mt-1">Command Center</p>
        </div>
        <nav class="flex-1 px-4 space-y-1 text-sm font-medium overflow-y-auto">
            <div class="mb-4">
                <p class="px-3 text-[10px] font-bold tracking-widest text-slate-600 uppercase mb-2">Monitoring</p>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Real-Time Map
                </a>
                <a href="{{ route('admin.history') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Hazard History
                </a>
                <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Alert Contacts
                </a>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-slate-600 uppercase mb-2 mt-6">System Admin</p>
                <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    Hardware Nodes
                </a>
            </div>
            @endif
        </nav>
        
        <div class="p-4 bg-slate-950/50 text-xs font-medium text-slate-400 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full {{ auth()->check() && auth()->user()->role === 'admin' ? 'bg-indigo-500' : 'bg-emerald-500' }}"></span>
                {{ auth()->check() ? auth()->user()->name : 'Command Center' }}
            </div>
            @if(auth()->check())
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-white transition-colors">Logout</button>
            </form>
            @endif
        </div>
    </div>

    <div class="flex-1 flex flex-col h-full overflow-hidden">
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 flex justify-between items-end z-30">
            <div>
                <h1 class="text-2xl font-bold text-white">Hazard History Logs</h1>
                <p class="text-sm text-slate-500 mt-1">Post-disaster analysis & complete audit trail.</p>
            </div>
            <button class="bg-slate-900 border border-slate-700 text-slate-300 px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-wider flex items-center gap-2 hover:bg-slate-800 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Export CSV
            </button>
        </header>

        <main class="p-8 flex-1 overflow-y-auto max-w-7xl mx-auto w-full">
            <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="bg-slate-950/50 text-xs font-semibold text-slate-500 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Timestamp</th>
                            <th class="px-6 py-4">Location</th>
                            <th class="px-6 py-4">Status Event</th>
                            <th class="px-6 py-4">Metrics (Temp / Smoke)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($logs as $log)
                        <tr class="hover:bg-slate-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-300">
                                {{ $log->created_at->format('M d, Y - H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-200">{{ $log->node->location_name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $log->node->specific_area ?? $log->node->hardware_id }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $log->status == 'CRITICAL' ? 'bg-red-500/10 text-red-500' : ($log->status == 'WARNING' ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500') }}">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">
                                <span class="{{ $log->temperature > 40 ? 'text-red-400 font-bold' : 'text-slate-300' }}">{{ $log->temperature }}°C</span> <span class="text-slate-600 px-1">/</span> 
                                <span class="{{ $log->smoke_level > 10 ? 'text-amber-400 font-bold' : 'text-slate-300' }}">{{ $log->smoke_level }}%</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($logs->isEmpty())
                <div class="p-12 text-center text-slate-500 text-sm">
                    No historical logs recorded yet.
                </div>
                @endif
            </div>
            
            <div class="mt-6 dark-pagination">
                {{ $logs->links() }}
            </div>
        </main>
    </div>
</body>
</html>