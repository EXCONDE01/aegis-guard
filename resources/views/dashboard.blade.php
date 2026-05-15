<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Command Center</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="flex h-screen bg-slate-950 font-sans text-slate-300 overflow-hidden" 
      x-data="{ time: '', date: '' }" 
      x-init="setInterval(() => { 
          let d = new Date(); 
          time = d.toLocaleTimeString('en-US', { hour12: false }); 
          date = d.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' }); 
      }, 1000)">
    
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
                <p class="px-3 text-[10px] font-bold tracking-widest text-indigo-500 uppercase mb-2 mt-6">System Admin</p>
                <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    Hardware Nodes
                </a>
                <a href="{{ route('admin.thresholds') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Threshold Config
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
        
        <header class="bg-slate-950/80 backdrop-blur-md border-b border-slate-800 p-8 sticky top-0 z-30 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-white">Facility Overview</h1>
                <p class="text-sm text-slate-500 mt-1">Live environmental metrics and active alerts.</p>
            </div>
            <div class="text-right flex flex-col items-end">
                <div class="text-xl font-mono text-white font-bold tracking-widest" x-text="time">00:00:00</div>
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1" x-text="date">Loading...</div>
            </div>
        </header>

        <main class="p-8 space-y-8 pb-20 max-w-7xl mx-auto">
            
            @php
                $totalNodes = $nodes->count();
                $criticalCount = $nodes->where('status', 'CRITICAL')->count();
                $warningCount = $nodes->where('status', 'WARNING')->count();
                $safeCount = $nodes->where('status', 'SAFE')->count();
                $systemHealth = $totalNodes > 0 ? round(($safeCount / $totalNodes) * 100) : 0;
            @endphp

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">System Health</span>
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $systemHealth }}<span class="text-lg text-slate-500">%</span></div>
                    <div class="w-full bg-slate-800 rounded-full h-1 mt-3">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $systemHealth }}%"></div>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Active Nodes</span>
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $totalNodes }}</div>
                    <p class="text-xs text-slate-500 font-medium mt-1">Transmitting live telemetry</p>
                </div>

                <div class="bg-slate-900 border {{ $warningCount > 0 ? 'border-amber-500/50' : 'border-slate-800' }} rounded-2xl p-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Warnings</span>
                        <span class="relative flex h-3 w-3">
                            @if($warningCount > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-3 w-3 {{ $warningCount > 0 ? 'bg-amber-500' : 'bg-slate-700' }}"></span>
                        </span>
                    </div>
                    <div class="text-3xl font-black {{ $warningCount > 0 ? 'text-amber-500' : 'text-white' }}">{{ $warningCount }}</div>
                    <p class="text-xs text-slate-500 font-medium mt-1">Elevated temperatures detected</p>
                </div>

                <div class="bg-slate-900 border {{ $criticalCount > 0 ? 'border-red-500/50' : 'border-slate-800' }} rounded-2xl p-5 flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Critical Threats</span>
                        <span class="relative flex h-3 w-3">
                            @if($criticalCount > 0)
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                            @endif
                            <span class="relative inline-flex rounded-full h-3 w-3 {{ $criticalCount > 0 ? 'bg-red-500' : 'bg-slate-700' }}"></span>
                        </span>
                    </div>
                    <div class="text-3xl font-black {{ $criticalCount > 0 ? 'text-red-500' : 'text-white' }}">{{ $criticalCount }}</div>
                    <p class="text-xs text-slate-500 font-medium mt-1">Immediate action required</p>
                </div>
            </div>

            @if(session('error'))
            <div class="bg-amber-500/10 border border-amber-500/20 text-amber-500 p-4 rounded-xl flex items-center gap-4">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h4 class="font-bold text-sm">System Warning</h4>
                    <p class="text-xs mt-0.5 opacity-80">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if(session('emergency_success'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-center gap-4">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div>
                    <h4 class="font-bold text-sm">Broadcast Active</h4>
                    <p class="text-xs mt-0.5 opacity-80">{{ session('emergency_success') }}</p>
                </div>
            </div>
            @endif

            <div x-data="{ showEmergencyModal: false, confirmText: '', isDispatching: false }">
                <div class="bg-slate-900 p-6 rounded-2xl border border-red-900/30 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="font-bold text-white">Emergency Override</h3>
                        <p class="text-sm text-slate-500 mt-1">Dispatch manual SMS evacuation alerts to registered personnel.</p>
                    </div>
                    <button @click="showEmergencyModal = true; confirmText = ''; isDispatching = false;" class="bg-red-600 hover:bg-red-500 text-white font-semibold text-sm px-6 py-2.5 rounded-lg transition-colors shadow-sm">
                      Dispatch Alert
                    </button>
                </div>

                <div x-show="showEmergencyModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center" x-transition.opacity>
                    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="if(!isDispatching) showEmergencyModal = false"></div>
                    <div class="bg-slate-900 border border-red-500/50 rounded-2xl p-8 max-w-md w-full shadow-2xl relative z-10 scale-100 transform transition-all"
                         x-show="showEmergencyModal"
                         x-transition:enter="ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave="ease-in duration-200"
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                         x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center shrink-0 border border-red-500/20">
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-white tracking-tight">Confirm Override</h3>
                        </div>
                        <p class="text-slate-400 text-sm mb-6 leading-relaxed">
                            You are about to bypass standard protocols and dispatch an emergency evacuation SMS to all personnel. <span class="text-red-400 font-semibold">This action cannot be undone.</span>
                        </p>
                        <div class="bg-slate-950/50 p-4 rounded-xl border border-slate-800 mb-6">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">
                                Type <span class="text-slate-200 select-all font-mono">DISPATCH</span> to verify intent
                            </label>
                            <input type="text" x-model="confirmText" :disabled="isDispatching"
                                   class="w-full bg-slate-900 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 block p-3 text-center tracking-widest uppercase placeholder:text-slate-700 transition-colors font-mono" 
                                   placeholder="...">
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <button @click="showEmergencyModal = false" :disabled="isDispatching" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors disabled:opacity-50">Cancel</button>
                            <form method="POST" action="{{ route('admin.dispatch') }}" class="m-0">
                                @csrf
                                <button type="submit" 
                                        :disabled="confirmText.trim().toUpperCase() !== 'DISPATCH' || isDispatching"
                                        @click.prevent="isDispatching = true; setTimeout(() => $el.closest('form').submit(), 1200)"
                                        :class="{'opacity-30 cursor-not-allowed': confirmText.trim().toUpperCase() !== 'DISPATCH', 'hover:bg-red-500 shadow-[0_0_15px_rgba(220,38,38,0.3)]': confirmText.trim().toUpperCase() === 'DISPATCH'}"
                                        class="bg-red-600 text-white px-5 py-2.5 rounded-lg text-sm font-bold tracking-wider transition-all flex items-center justify-center min-w-[180px]">
                                    <span x-show="!isDispatching">AUTHORIZE</span>
                                    <span x-show="isDispatching" style="display: none;" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        TRANSMITTING...
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Live Telemetry Feeds</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
            </div>
        </main>
    </div>
</body>
</html>