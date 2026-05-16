<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Threshold Config</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-slate-950 font-sans text-slate-300 overflow-hidden">
    
<div class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col z-50 shrink-0">
        <div class="p-6">
            <h2 class="text-xl font-bold text-white tracking-tight">Aegis-Guard</h2>
            <p class="text-[10px] text-slate-500 uppercase font-semibold tracking-widest mt-1">Command Center</p>
        </div>
        <nav class="flex-1 px-4 space-y-1 text-sm font-medium overflow-y-auto">
            <div class="mb-4">
                <p class="px-3 text-[10px] font-bold tracking-widest text-slate-600 uppercase mb-2">Monitoring</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Real-Time Map
                </a>
                
                <a href="{{ route('admin.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.history') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Hazard History
                </a>
                
                <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('admin.contacts') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Alert Contacts
                </a>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-indigo-500 uppercase mb-2 mt-6">System Admin</p>
                
                <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 {{ request()->routeIs('admin.nodes') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    Hardware Nodes
                </a>
                
                <a href="{{ route('admin.thresholds') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 {{ request()->routeIs('admin.thresholds') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Threshold Config
                </a>
                
                <a href="{{ route('admin.network') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 {{ request()->routeIs('admin.network') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    Gateway & VLAN
                </a>
                
                <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 {{ request()->routeIs('admin.backups.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694-4.125-8.25-4.125S3.75 8.653 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                    System Backups
                </a>
            </div>
            @endif
        </nav>
        
        <div class="p-4 bg-slate-950/50 text-xs font-medium text-slate-400 border-t border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2 text-indigo-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                System Administrator
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-white transition-colors">Logout</button>
            </form>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto">
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30">
            <h1 class="text-2xl font-bold text-white">Threshold Configuration</h1>
            <p class="text-sm text-slate-500 mt-1">Define global facility boundaries for environmental anomalies.</p>
        </header>

        <main class="p-8 pb-20 max-w-4xl mx-auto w-full">
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl">
                <ul class="text-sm list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.thresholds.update') }}" method="POST" class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-sm">
                @csrf
                @method('PUT')
                
                <div class="p-8 space-y-8">
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-indigo-500/10 rounded-lg">
                                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">Temperature Logic</h3>
                                <p class="text-sm text-slate-500">Triggers for facility heat anomalies.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-950 p-5 rounded-xl border border-slate-800">
                                <label class="block text-xs font-bold uppercase tracking-wider text-amber-500 mb-2">Warning Level (°C)</label>
                                <input type="number" step="0.1" name="temp_warning" value="{{ $threshold->temp_warning }}" required class="w-full bg-slate-900 border border-slate-700 text-white text-base rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 block p-3 font-mono transition-colors">
                                <p class="text-[10px] text-slate-500 mt-2">Triggers amber investigation state.</p>
                            </div>
                            <div class="bg-slate-950 p-5 rounded-xl border border-slate-800">
                                <label class="block text-xs font-bold uppercase tracking-wider text-red-500 mb-2">Critical Level (°C)</label>
                                <input type="number" step="0.1" name="temp_critical" value="{{ $threshold->temp_critical }}" required class="w-full bg-slate-900 border border-slate-700 text-white text-base rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 block p-3 font-mono transition-colors">
                                <p class="text-[10px] text-slate-500 mt-2">Triggers red evacuation protocol.</p>
                            </div>
                        </div>
                    </div>

                    <div class="h-px bg-slate-800 w-full"></div>

                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-slate-800/50 rounded-lg">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-white text-lg">Particulate Logic</h3>
                                <p class="text-sm text-slate-500">Triggers for atmospheric smoke concentration.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-slate-950 p-5 rounded-xl border border-slate-800">
                                <label class="block text-xs font-bold uppercase tracking-wider text-amber-500 mb-2">Warning Level (%)</label>
                                <input type="number" step="0.1" name="smoke_warning" value="{{ $threshold->smoke_warning }}" required class="w-full bg-slate-900 border border-slate-700 text-white text-base rounded-lg focus:ring-1 focus:ring-amber-500 focus:border-amber-500 block p-3 font-mono transition-colors">
                            </div>
                            <div class="bg-slate-950 p-5 rounded-xl border border-slate-800">
                                <label class="block text-xs font-bold uppercase tracking-wider text-red-500 mb-2">Critical Level (%)</label>
                                <input type="number" step="0.1" name="smoke_critical" value="{{ $threshold->smoke_critical }}" required class="w-full bg-slate-900 border border-slate-700 text-white text-base rounded-lg focus:ring-1 focus:ring-red-500 focus:border-red-500 block p-3 font-mono transition-colors">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-slate-950/50 border-t border-slate-800 flex items-center justify-between">
                    <p class="text-xs text-slate-500 font-medium">Changes take effect immediately upon save.</p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-8 py-3 rounded-xl text-sm font-bold tracking-wider transition-all shadow-[0_0_15px_rgba(79,70,229,0.2)] hover:shadow-[0_0_25px_rgba(79,70,229,0.4)]">
                        SAVE CONFIGURATION
                    </button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>