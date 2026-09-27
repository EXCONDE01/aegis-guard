<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'FireNet | Command Center')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,900|jetbrains-mono:400,700" rel="stylesheet" />
    <style> 
        body { font-family: 'Inter', sans-serif; }
        .font-telemetry { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="flex h-screen bg-[#f4f7f9] text-gray-800 overflow-hidden" 
      x-data="{ time: '', date: '', showEmergencyModal: false, confirmText: '', isDispatching: false }" 
      x-init="setInterval(() => { 
          let d = new Date(); 
          time = d.toLocaleTimeString('en-US', { hour12: false }); 
          date = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); 
      }, 1000)">
    
    <aside class="w-64 bg-[#1e2633] flex flex-col z-40 shrink-0 text-gray-400">
        <div class="h-20 flex items-center px-6 border-b border-[#2a3441]">
            <div class="flex flex-col">
                <span class="text-white font-black text-xl tracking-tight leading-none">FireNet</span>
                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Hazard Detection</span>
            </div>
        </div>

        <nav class="flex-1 py-6 overflow-y-auto text-[13px] space-y-1">
            <div class="px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Monitoring Core</div>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg> Live Telemetry
            </a>
            <a href="{{ route('admin.history') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.history') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg> Hazard History
            </a>

            <div class="mt-8 mb-3 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Emergency Response</div>
            <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.contacts') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg> Alert Directory
            </a>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
            <div class="mt-8 mb-3 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">System Configuration</div>
            <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.nodes') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg> Sensor Nodes
            </a>
            <a href="{{ route('admin.thresholds') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.thresholds') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg> Hazard Thresholds
            </a>
            <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.backups.*') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg> System Backups
            </a>
            <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.users*') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg> User Management
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-6 py-3 transition-all duration-300 {{ request()->routeIs('admin.settings') ? 'bg-[#283243] border-l-4 border-blue-500 text-white' : 'border-l-4 border-transparent text-gray-400 hover:text-white hover:bg-[#2a3441]' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> System Settings
            </a>
            @endif
        </nav>

        <div class="p-4 border-t border-[#2a3441] bg-[#1a212d] flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center text-white font-bold text-xs shrink-0">A</div>
            <div class="flex-1 min-w-0">
                <p class="text-xs text-white font-medium truncate">{{ auth()->user()->name ?? 'Administrator' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0 shrink-0">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-y-auto relative z-50">
        <header class="bg-white border-b border-gray-200 px-8 h-20 flex justify-between items-center shrink-0 sticky top-0 z-30 shadow-sm">
            <div>
                <h1 class="text-[22px] font-bold text-gray-900 tracking-tight">@yield('header_title')</h1>
                <p class="text-xs text-gray-500 mt-1 font-medium">@yield('header_subtitle', 'Live environmental telemetry & system health.')</p>
            </div>
            <div class="flex items-center gap-8">
                <div class="text-right">
                    <div class="text-lg font-telemetry text-gray-900 font-bold tracking-widest" x-text="time">00:00:00</div>
                    <div class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1" x-text="date">Loading...</div>
                </div>
                @yield('header_actions')
            </div>
        </header>

        <div class="p-8 max-w-[1600px] mx-auto w-full space-y-6 pb-20">
            @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <div class="font-medium">{{ session('error') }}</div>
            </div>
            @endif

            @if(session('success') || session('emergency_success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div class="font-medium">{{ session('success') ?? session('emergency_success') }}</div>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- NEW MODALS INJECTION POINT -->
    @yield('modals')
    @yield('scripts')
</body>
</html>