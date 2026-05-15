<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Command Center</title>
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
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Real-Time Map
                </a>
                <a href="{{ route('admin.history') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
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

    <div class="flex-1 overflow-y-auto">
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30">
            <h1 class="text-2xl font-bold text-white">Facility Overview</h1>
            <p class="text-sm text-slate-500 mt-1">Live environmental metrics and active alerts.</p>
        </header>

        <main class="p-8 space-y-6 pb-20 max-w-7xl mx-auto">
            @if(session('emergency_success'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-center gap-4">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h4 class="font-bold text-sm">Broadcast Active</h4>
                    <p class="text-xs mt-0.5 opacity-80">{{ session('emergency_success') }}</p>
                </div>
            </div>
            @endif

            <div class="bg-slate-900 p-6 rounded-2xl border border-red-900/30 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h3 class="font-bold text-white">Emergency Override</h3>
                    <p class="text-sm text-slate-500 mt-1">Dispatch manual SMS evacuation alerts to registered personnel.</p>
                </div>
                <form method="POST" action="{{ route('admin.dispatch') }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Trigger facility-wide alert?')" class="bg-red-600 hover:bg-red-500 text-white font-semibold text-sm px-6 py-2.5 rounded-lg transition-colors">
                      Dispatch Alert
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pt-4">
                @foreach($nodes as $node)
                    @php 
                        $latestLog = $node->logs->first(); 
                        $isCritical = $node->status == 'CRITICAL';
                        $isWarning = $node->status == 'WARNING';
                    @endphp

                    <div class="bg-slate-900 p-6 rounded-2xl border {{ $isCritical ? 'border-red-500/50' : ($isWarning ? 'border-amber-500/50' : 'border-slate-800') }}">
                      <div class="flex justify-between items-start mb-6">
                        <div>
                          <h3 class="text-base font-bold text-white">{{ $node->location_name }}</h3>
                          <p class="text-xs text-slate-500 mt-1">{{ $node->specific_area }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $isCritical ? 'bg-red-500/10 text-red-500' : ($isWarning ? 'bg-amber-500/10 text-amber-500' : 'bg-slate-800 text-slate-400') }}">
                            {{ $node->status }}
                        </span>
                      </div>

                      <div class="space-y-5">
                        <div>
                          <div class="flex justify-between items-end mb-1.5">
                            <span class="text-xs font-medium text-slate-400">Temperature</span>
                            <span class="text-sm font-semibold {{ $latestLog && $latestLog->temperature > 40 ? 'text-red-400' : 'text-slate-200' }}">
                                {{ $latestLog->temperature ?? '--' }} °C
                            </span>
                          </div>
                          <div class="w-full bg-slate-800 rounded-full h-1">
                            <div class="h-full rounded-full {{ $latestLog && $latestLog->temperature > 40 ? 'bg-red-500' : 'bg-indigo-500' }}" style="width: {{ $latestLog ? min(($latestLog->temperature / 50) * 100, 100) : 0 }}%"></div>
                          </div>
                        </div>

                        <div>
                          <div class="flex justify-between items-end mb-1.5">
                            <span class="text-xs font-medium text-slate-400">Smoke Level</span>
                            <span class="text-sm font-semibold {{ $latestLog && $latestLog->smoke_level > 10 ? 'text-amber-400' : 'text-slate-200' }}">
                                {{ $latestLog->smoke_level ?? '--' }} %
                            </span>
                          </div>
                          <div class="w-full bg-slate-800 rounded-full h-1">
                            <div class="h-full rounded-full {{ $latestLog && $latestLog->smoke_level > 10 ? 'bg-amber-500' : 'bg-slate-500' }}" style="width: {{ $latestLog ? min(($latestLog->smoke_level / 30) * 100, 100) : 0 }}%"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
</body>
</html>