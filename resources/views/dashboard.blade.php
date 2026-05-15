<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-[#F8FAFC] font-sans text-slate-900 overflow-hidden">
    <div class="w-64 bg-[#0F172A] text-white flex flex-col shadow-2xl z-50">
        <div class="p-6">
            <h2 class="text-2xl font-black text-indigo-400">Aegis-Guard</h2>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">LSPU Campus</p>
        </div>
        <nav class="flex-1 p-4 space-y-2 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 bg-indigo-600/10 text-indigo-400 rounded-xl ring-1 ring-indigo-500/20">
              📡 Real-Time Map
            </a>
            <a href="{{ route('admin.history') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800/50 rounded-xl text-slate-400 transition-all">
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

    <div class="flex-1 overflow-y-auto">
        <header class="bg-white/60 backdrop-blur-xl border-b border-slate-200 p-8 sticky top-0 z-30">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Command Center</h1>
            <p class="text-sm text-slate-500 font-semibold mt-1">Live Environmental Monitoring</p>
        </header>

        <main class="p-8 space-y-8 pb-20">
            @if(session('emergency_success'))
            <div class="mb-8 bg-red-600 text-white p-4 rounded-2xl flex items-center gap-4 shadow-xl shadow-red-600/30 animate-pulse">
                <svg class="w-8 h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h4 class="font-black tracking-widest uppercase text-sm">System Broadcast Active</h4>
                    <p class="text-xs font-medium opacity-90">{{ session('emergency_success') }}</p>
                </div>
            </div>
            @endif

            <div class="bg-white p-6 rounded-3xl border border-red-100 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4 ring-1 ring-red-50">
                <div class="flex items-center gap-5">
                  <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center text-red-600 shrink-0">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2.25m0 0v2.25m0-2.25h2.25m-4.5 0h2.25m-2.25 5.25h4.5m-4.5-10.5h4.5M12 2.25v1.5m0 16.5v1.5m-7.794-7.86l-1.06-1.06m17.708 0l-1.06 1.06M3 12h1.5m15 0h1.5m-3.206-7.86l1.06-1.06M6.354 18.354l-1.06 1.06" /></svg>
                  </div>
                  <div>
                    <h3 class="font-black text-slate-800 text-lg">Emergency Broadcast Panel</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Manually override gateway to dispatch emergency alerts to all LSPU staff.</p>
                  </div>
                </div>
                <form method="POST" action="{{ route('admin.dispatch') }}" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" onclick="return confirm('WARNING: Are you sure you want to trigger a campus-wide evacuation alert?')" class="w-full bg-red-600 hover:bg-red-700 text-white font-black uppercase text-xs px-6 py-3 rounded-xl shadow-lg shadow-red-600/30 transition-all">
                      Dispatch Alert
                    </button>
                </form>
            </div>

            <div class="flex justify-between items-end mb-6 mt-4">
                <h2 class="text-xl font-black text-slate-800">Campus Sensor Map</h2>
                <span class="px-3 py-1 bg-slate-200/50 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-full">Live Database Feed</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach($nodes as $node)
                    @php 
                        $latestLog = $node->logs->first(); 
                        $isCritical = $node->status == 'CRITICAL';
                        $isWarning = $node->status == 'WARNING';
                    @endphp

                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/60 transition-all hover:shadow-xl {{ $isCritical ? 'ring-2 ring-red-500 bg-red-50/20' : ($isWarning ? 'ring-2 ring-amber-400 bg-amber-50/20' : '') }}">
                      <div class="flex justify-between items-start mb-8">
                        <div>
                          <h3 class="text-xl font-black text-slate-800">{{ $node->location_name }}</h3>
                          <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $node->specific_area }}</p>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $isCritical ? 'bg-red-600 text-white animate-pulse' : ($isWarning ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500') }}">
                            {{ $node->status }}
                        </span>
                      </div>

                      <div class="space-y-6">
                        <div>
                          <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Temperature</span>
                            <span class="text-lg font-black text-slate-800">{{ $latestLog->temperature ?? '--' }} °C</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-indigo-500 transition-all duration-500" style="width: {{ $latestLog ? min(($latestLog->temperature / 50) * 100, 100) : 0 }}%"></div>
                          </div>
                        </div>

                        <div>
                          <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-500">Smoke Level</span>
                            <span class="text-lg font-black text-slate-800">{{ $latestLog->smoke_level ?? '--' }} %</span>
                          </div>
                          <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-slate-400 transition-all duration-500" style="width: {{ $latestLog ? min(($latestLog->smoke_level / 30) * 100, 100) : 0 }}%"></div>
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