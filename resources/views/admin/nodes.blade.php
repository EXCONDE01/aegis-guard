<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Live Node Telemetry</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
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
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Real-Time Map
                </a>
                <a href="{{ route('admin.history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Hazard History
                </a>
                <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Alert Contacts
                </a>
            </div>

            @if(auth()->check() && auth()->user()->role === 'admin')
            <div>
                <p class="px-3 text-[10px] font-bold tracking-widest text-indigo-500 uppercase mb-2 mt-6">System Admin</p>
                <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 bg-indigo-500/10 text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    Hardware Nodes
                </a>
                <a href="{{ route('admin.thresholds') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Threshold Config
                </a>
                <a href="{{ route('admin.network') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    Gateway & VLAN
                </a>
                <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 text-slate-400 hover:text-slate-200 hover:bg-slate-800/50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
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

    <div class="flex-1 overflow-y-auto" x-data="nodeManager()" x-init="init()">
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-white">Live Hardware Nodes</h1>
                <p class="text-sm text-slate-500 mt-1">Assign and calibrate physical ESP32 gateway sensors.</p>
            </div>
            <div class="text-sm text-slate-500 flex items-center gap-2 bg-slate-900 border border-slate-800 px-4 py-2 rounded-lg">
                <span class="relative flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                Live Polling Active: <span class="font-mono text-emerald-400 font-bold ml-1">2000ms Interval</span>
            </div>
        </header>

        <main class="p-8 pb-20 max-w-7xl mx-auto">
            @if(session('success'))
            <div class="mb-8 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(node, index) in nodes" :key="node.id">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg transition-all hover:border-slate-700 flex flex-col" x-data="{ editing: false }">
                        
                        <div class="px-5 py-3 border-b border-slate-800 flex justify-between items-center transition-colors" 
                             :class="node.status === 'CRITICAL' ? 'bg-red-500/10 border-red-500/20' : (node.status === 'WARNING' ? 'bg-amber-500/10 border-amber-500/20' : 'bg-slate-950/50')">
                            <div class="flex items-center gap-2">
                                <span class="relative flex h-2.5 w-2.5">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                                          :class="node.status === 'CRITICAL' ? 'bg-red-400' : (node.status === 'WARNING' ? 'bg-amber-400' : 'bg-emerald-400')"></span>
                                    <span class="relative inline-flex rounded-full h-2.5 w-2.5"
                                          :class="node.status === 'CRITICAL' ? 'bg-red-500' : (node.status === 'WARNING' ? 'bg-amber-500' : 'bg-emerald-500')"></span>
                                </span>
                                <span class="text-[10px] font-bold uppercase tracking-wider transition-colors"
                                      :class="node.status === 'CRITICAL' ? 'text-red-400' : (node.status === 'WARNING' ? 'text-amber-400' : 'text-emerald-400')"
                                      x-text="node.status">
                                </span>
                            </div>
                            <span class="font-mono text-xs text-slate-500" x-text="node.hardware_id"></span>
                        </div>

                        <div class="p-6 flex-1 flex flex-col" x-show="!editing">
                            
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                                     :class="node.location_name === 'New Unassigned Node' ? 'bg-amber-500/10 text-amber-500' : 'bg-indigo-500/10 text-indigo-400'">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold" 
                                        :class="node.location_name === 'New Unassigned Node' ? 'text-amber-500' : 'text-white leading-tight'"
                                        x-text="node.location_name === 'New Unassigned Node' ? 'Unassigned Node' : node.location_name"></h3>
                                    <p class="text-sm mt-0.5"
                                       :class="node.location_name === 'New Unassigned Node' ? 'text-slate-500' : 'text-slate-400'"
                                       x-text="node.location_name === 'New Unassigned Node' ? 'Pending Configuration' : node.specific_area"></p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-6 mb-6">
                                <div class="bg-slate-950/50 rounded-lg p-2.5 text-center border border-slate-800/50">
                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest font-bold mb-1">IP Address</p>
                                    <p class="text-xs text-slate-300 font-mono" x-text="node.ip_address || '--'"></p>
                                </div>
                                <div class="bg-slate-950/50 rounded-lg p-2.5 text-center border border-slate-800/50">
                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest font-bold mb-1">Latency</p>
                                    <p class="text-xs font-mono" 
                                       :class="(node.latency || 0) < 50 ? 'text-emerald-400' : 'text-amber-400'"
                                       x-text="(node.latency || '--') + 'ms'"></p>
                                </div>
                                <div class="bg-slate-950/50 rounded-lg p-2.5 text-center border border-slate-800/50">
                                    <p class="text-[9px] text-slate-500 uppercase tracking-widest font-bold mb-1">Uptime</p>
                                    <p class="text-xs text-slate-300 font-mono" x-text="node.uptime || '--'"></p>
                                </div>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-800 flex justify-end">
                                <button @click="editing = true" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 bg-indigo-500/10 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Configure
                                </button>
                            </div>
                        </div>

                        <div class="p-6 flex-1 flex flex-col bg-slate-800/50" x-show="editing" x-cloak>
                            <form :action="'{{ url('/nodes') }}/' + node.id" method="POST" class="flex-1 flex flex-col">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="PUT">
                                
                                <div class="space-y-4 mb-4">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Lab / Room Name</label>
                                        <input type="text" name="location_name" :value="node.location_name === 'New Unassigned Node' ? '' : node.location_name" placeholder="e.g. CCS Lab 1" required class="bg-slate-950 border border-slate-700 text-white text-sm rounded-lg px-3 py-2 w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Specific Position</label>
                                        <input type="text" name="specific_area" :value="node.specific_area === 'Awaiting Configuration' ? '' : node.specific_area" placeholder="e.g. Server Rack A" class="bg-slate-950 border border-slate-700 text-white text-sm rounded-lg px-3 py-2 w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                                    </div>
                                </div>

                                <div class="mt-auto pt-4 border-t border-slate-700 flex justify-end gap-2">
                                    <button type="button" @click="editing = false" class="text-xs font-semibold text-slate-400 hover:text-slate-200 px-3 py-2 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-lg shadow-lg shadow-indigo-500/20 transition-colors">
                                        Save Setup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </template>

                <div x-show="nodes.length === 0" x-cloak class="col-span-full bg-slate-900 border border-slate-800 border-dashed rounded-2xl p-12 text-center">
                    <div class="w-16 h-16 bg-slate-950 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-700">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-300">No Nodes Detected</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Power on an ESP32 hardware node and ensure it is transmitting to the network bridge to automatically register it here.</p>
                </div>

            </div>
        </main>
    </div>

    <script>
        function nodeManager() {
            return {
                // Initialize the page instantly with Blade data so there's no loading screen
                nodes: @json($nodes),
                init() {
                    // Start a background loop to fetch data from the server every 2 seconds
                    setInterval(() => {
                        fetch("{{ route('admin.nodes.telemetry') }}")
                            .then(res => res.json())
                            .then(data => {
                                // Overwriting this variable automatically updates all the HTML cards instantly
                                this.nodes = data;
                            })
                            .catch(err => console.error('Telemetry stream interrupted:', err));
                    }, 2000); 
                }
            }
        }
    </script>
</body>
</html>