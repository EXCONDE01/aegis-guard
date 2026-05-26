<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Network & Routing Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Prevents Alpine.js elements from flashing on page load */
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
                    Gateway & Routing
                </a>
                
                <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors mt-1 {{ request()->routeIs('admin.backups.*') ? 'bg-indigo-500/10 text-indigo-400' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694-4.125-8.25-4.125S3.75 8.653 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25-4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
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

    <div class="flex-1 overflow-y-auto" x-data="{ masterTab: 'vlan', fwTab: 'lan', showRuleForm: false }">
        
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30 flex justify-between items-end">
            <div>
                <h1 class="text-2xl font-bold text-white">Network & Routing Control</h1>
                <p class="text-sm text-slate-500 mt-1">Manage OPNsense VLAN isolation & emergency traffic prioritization.</p>
            </div>
        </header>

        <main class="p-8 pb-20 max-w-7xl mx-auto space-y-8">
            
            @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-amber-500/10 border border-amber-500/20 text-amber-500 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @if(session('emergency_success'))
            <div class="bg-red-500/10 border-2 border-red-500 p-6 rounded-2xl flex items-center justify-between shadow-[0_0_30px_rgba(239,68,68,0.2)] animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="bg-red-500 p-3 rounded-full text-white">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-red-500 uppercase tracking-widest">Lockdown Active</h2>
                        <p class="text-sm text-red-400 font-mono mt-1">{{ session('emergency_success') }}</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-red-500 border border-red-500 px-3 py-1 rounded bg-red-950">QoS: SENSOR PRIORITY</span>
            </div>
            @else
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row items-center justify-between gap-6 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-[repeating-linear-gradient(45deg,#ef4444,#ef4444_10px,#7f1d1d_10px,#7f1d1d_20px)]"></div>
                <div class="pl-4">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        Emergency Network Override
                    </h2>
                    <p class="text-sm text-slate-400 mt-1">Sever non-essential VLAN traffic to guarantee 100% bandwidth for IoT sensors and emergency SMS dispatch.</p>
                </div>
                <form action="{{ route('admin.network.lockdown') }}" method="POST" onsubmit="return confirm('CRITICAL WARNING: This will immediately sever internet access for standard campus networks. Only proceed during a verified disaster event. Continue?');">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-500 text-white font-bold text-sm tracking-widest uppercase px-8 py-4 rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.4)] hover:shadow-[0_0_30px_rgba(220,38,38,0.6)] transition-all border border-red-400 flex items-center gap-3 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        ENGAGE LOCKDOWN
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6"
                 x-data="{ gateway: {{ json_encode($gateway) }} }"
                 x-init="setInterval(() => {
                     fetch('{{ route('admin.network.telemetry') }}')
                     .then(res => res.json())
                     .then(data => gateway = data)
                 }, 2000)">
                 
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center transition-colors"
                             :class="gateway.status === 'ONLINE' ? 'bg-emerald-500/10 border border-emerald-500/20 text-emerald-500' : 'bg-red-500/10 border border-red-500/20 text-red-500'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-lg">OPNsense Security Gateway</h3>
                            <p class="text-xs font-mono mt-0.5 transition-colors" 
                               :class="gateway.status === 'ONLINE' ? 'text-emerald-400' : 'text-red-400'"
                               x-text="'● ' + gateway.status"></p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <p class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">CPU Load</p>
                        <p class="text-xl font-mono text-white mt-1 transition-all" x-text="gateway.cpu"></p>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <p class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">Memory</p>
                        <p class="text-xl font-mono text-white mt-1 transition-all" x-text="gateway.ram"></p>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <p class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">Active Rules</p>
                        <p class="text-xl font-mono text-indigo-400 mt-1" x-text="gateway.firewall_rules"></p>
                    </div>
                    <div class="bg-slate-950 p-4 rounded-xl border border-slate-800">
                        <p class="text-[10px] font-bold tracking-widest text-slate-500 uppercase">WAN Uplink</p>
                        <p class="text-xl font-mono text-white mt-1" x-text="gateway.uplink"></p>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 border-b border-slate-800 pt-4">
                <button @click="masterTab = 'vlan'" 
                        :class="masterTab === 'vlan' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-indigo-500/10' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'" 
                        class="px-6 py-3 font-bold text-sm rounded-t-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Subnet & VLAN Provisioning
                </button>
                <button @click="masterTab = 'firewall'" 
                        :class="masterTab === 'firewall' ? 'text-indigo-400 border-b-2 border-indigo-400 bg-indigo-500/10' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'" 
                        class="px-6 py-3 font-bold text-sm rounded-t-lg transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    Security Policies (Firewall)
                </button>
            </div>

            <div x-show="masterTab === 'vlan'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-3 gap-8 pt-4" x-cloak>
                
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 lg:col-span-1 h-fit shadow-sm">
                    <h3 class="font-bold text-white text-base mb-5">Provision New Subnet</h3>
                    <form action="{{ route('admin.network.vlan.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">VLAN ID (1-4094)</label>
                            <input type="number" name="vlan_id" required min="1" max="4094" class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-mono transition-colors" placeholder="e.g. 10">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Zone Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="e.g. IoT Sensor Mesh">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Subnet Mask</label>
                            <input type="text" name="subnet" required class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-mono transition-colors" placeholder="192.168.10.0/24">
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm rounded-lg px-5 py-3 text-center transition-colors mt-2">
                            Deploy to Gateway
                        </button>
                    </form>
                </div>

                <div class="bg-slate-900 rounded-2xl border border-slate-800 lg:col-span-2 overflow-hidden shadow-sm h-fit">
                    <div class="p-5 border-b border-slate-800 flex justify-between items-center">
                        <h3 class="font-bold text-white text-base">Active Segmentation Table</h3>
                        <span class="text-xs text-slate-500 bg-slate-950 px-2.5 py-1 rounded-md border border-slate-800 font-mono">QoS Engine: ACTIVE</span>
                    </div>
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-950/50 text-xs font-semibold text-slate-500 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-4">VLAN ID</th>
                                <th class="px-6 py-4">Zone Designation</th>
                                <th class="px-6 py-4">Subnet</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($vlans as $vlan)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-400">{{ $vlan->vlan_id }}</td>
                                <td class="px-6 py-4 font-medium text-slate-200">{{ $vlan->name }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">{{ $vlan->subnet }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.network.vlan.destroy', $vlan->id) }}" method="POST" onsubmit="return confirm('WARNING: Terminating this VLAN will drop all network traffic for its subnet. Proceed?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 font-medium text-sm transition-colors">Terminate</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($vlans->isEmpty())
                    <div class="p-12 text-center text-slate-500 text-sm">
                        No virtual LANs detected. Network is currently flat.
                    </div>
                    @endif
                </div>
            </div>

            <div x-show="masterTab === 'firewall'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="pt-4 space-y-8" x-cloak>
                
                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-slate-800 flex justify-between items-center bg-slate-900/50">
                        <div>
                            <h3 class="font-bold text-white text-lg">Firewall Policy Staging</h3>
                            <p class="text-xs text-slate-500 mt-1">Configure and deploy custom access control lists (ACL).</p>
                        </div>
                        <form action="{{ route('admin.network.rules.apply') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-lg shadow-indigo-500/20 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                PUSH TO FIREWALL
                            </button>
                        </form>
                    </div>

                    <div class="flex overflow-x-auto bg-slate-950 border-b border-slate-800">
                        <button @click="fwTab = 'wan'; showRuleForm = false" :class="fwTab === 'wan' ? 'bg-slate-800 text-white border-t-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-900 border-t-2 border-transparent'" class="px-6 py-3 font-semibold text-sm transition-colors whitespace-nowrap">WAN</button>
                        <button @click="fwTab = 'lan'; showRuleForm = false" :class="fwTab === 'lan' ? 'bg-slate-800 text-white border-t-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-900 border-t-2 border-transparent'" class="px-6 py-3 font-semibold text-sm transition-colors whitespace-nowrap">LAN</button>
                        @foreach($vlans as $vlan)
                        <button @click="fwTab = '{{ 'vlan'.$vlan->vlan_id }}'; showRuleForm = false" :class="fwTab === '{{ 'vlan'.$vlan->vlan_id }}' ? 'bg-slate-800 text-white border-t-2 border-indigo-500' : 'text-slate-400 hover:bg-slate-900 border-t-2 border-transparent'" class="px-6 py-3 font-semibold text-sm transition-colors whitespace-nowrap">
                            {{ strtoupper($vlan->name) }} (VLAN {{ $vlan->vlan_id }})
                        </button>
                        @endforeach
                    </div>

                    <div class="bg-slate-800/50 px-6 py-3 border-b border-slate-800 flex justify-between items-center">
                        <span class="text-xs font-mono text-slate-400">Rules applied to interface: <strong class="text-white uppercase" x-text="fwTab"></strong></span>
                        <button @click="showRuleForm = !showRuleForm" class="bg-orange-500 hover:bg-orange-400 text-white text-xs font-bold px-3 py-1.5 rounded flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            ADD RULE
                        </button>
                    </div>

                    <div x-show="showRuleForm" x-collapse x-cloak class="bg-slate-950 border-b border-slate-800 p-6">
                        <form action="{{ route('admin.network.rules.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                            @csrf
                            <input type="hidden" name="interface" x-model="fwTab">
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-slate-500 uppercase mb-2">Policy Action</label>
                                <select name="policy" required class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-2.5">
                                    <option value="ALLOW">Pass</option>
                                    <option value="BLOCK">Block</option>
                                    <option value="PRIORITIZE">Prioritize</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-slate-500 uppercase mb-2">Protocol</label>
                                <select name="protocol" required class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-2.5">
                                    <option value="TCP/UDP">TCP/UDP</option>
                                    <option value="TCP">TCP</option>
                                    <option value="ICMP">ICMP</option>
                                    <option value="ANY">Any</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-slate-500 uppercase mb-2">Source</label>
                                <input type="text" name="source" placeholder="e.g. Any, 192.168.1.10" required class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-2.5">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-slate-500 uppercase mb-2">Destination</label>
                                <input type="text" name="destination" placeholder="e.g. Any" required class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-2.5">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold tracking-widest text-slate-500 uppercase mb-2">Dest. Port</label>
                                <input type="text" name="port" placeholder="e.g. 80,443" class="w-full bg-slate-900 border border-slate-700 text-xs text-slate-300 rounded-lg px-3 py-2.5">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs h-[38px] px-4 rounded-lg transition-colors shadow-lg">SAVE</button>
                            </div>
                        </form>
                    </div>

                    <div class="overflow-x-auto min-h-[300px]">
                        <table class="w-full text-left text-sm text-slate-400">
                            <thead class="bg-slate-950/80 text-[10px] font-bold tracking-wider text-slate-500 uppercase border-b border-slate-800 shadow-sm">
                                <tr>
                                    <th class="px-6 py-3 w-10"></th>
                                    <th class="px-4 py-3">Proto</th>
                                    <th class="px-4 py-3">Source</th>
                                    <th class="px-4 py-3">Port</th>
                                    <th class="px-4 py-3">Destination</th>
                                    <th class="px-4 py-3">Port</th>
                                    <th class="px-4 py-3">Gateway</th>
                                    <th class="px-4 py-3">Description</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800">
                                @foreach($firewallRules as $rule)
                                <tr x-show="fwTab === '{{ strtolower($rule->interface) }}'" class="hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-3 text-center">
                                        @if($rule->policy === 'ALLOW' || $rule->policy === 'PRIORITIZE')
                                            <svg class="w-4 h-4 text-emerald-500 inline-block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                                        @else
                                            <svg class="w-4 h-4 text-red-500 inline-block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $rule->protocol }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-300">{{ $rule->source }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-500">*</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-300">{{ $rule->destination }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-300">{{ $rule->port === 'ANY' ? '*' : $rule->port }}</td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-500">*</td>
                                    <td class="px-4 py-3 text-xs flex items-center gap-2">
                                        @if(!$rule->is_synced)
                                            <span class="w-2 h-2 rounded-full bg-amber-500" title="Staged - Pending Apply"></span>
                                        @endif
                                        <span class="italic text-slate-400">Aegis Rule ({{ $rule->policy }})</span>
                                    </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="8" class="p-8 text-center text-slate-500 text-sm" x-show="!$el.parentElement.querySelector('tr[style=\'display: table-row;\']')">
                                        No custom policies staged for this interface.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-sm opacity-80 mt-8">
                    <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-slate-300 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                Active Hardware Configuration
                            </h3>
                            <p class="text-xs text-slate-500 mt-1">Rules currently enforced by the physical OPNsense Edge Gateway.</p>
                        </div>
                        <span class="text-[10px] font-bold tracking-widest text-slate-500 uppercase px-3 py-1 border border-slate-700 rounded bg-slate-950">Read Only</span>
                    </div>
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="w-full text-left text-sm text-slate-500">
                            <thead class="bg-slate-950/50 text-[10px] font-bold tracking-wider text-slate-600 uppercase border-b border-slate-800 sticky top-0">
                                <tr>
                                    <th class="px-6 py-4">ACTION</th>
                                    <th class="px-6 py-4">INTERFACE</th>
                                    <th class="px-6 py-4">PROTOCOL</th>
                                    <th class="px-6 py-4">SOURCE</th>
                                    <th class="px-6 py-4">DESTINATION</th>
                                    <th class="px-6 py-4">DESCRIPTION</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50">
                                @foreach($hardwareRules as $hRule)
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="px-6 py-3 font-bold text-xs">
                                        @if(strtolower($hRule->action) === 'pass')
                                            <span class="text-emerald-500/70">PASS</span>
                                        @elseif(strtolower($hRule->action) === 'block')
                                            <span class="text-red-500/70">BLOCK</span>
                                        @else
                                            <span class="text-slate-500">{{ strtoupper($hRule->action) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 font-mono text-xs uppercase">{{ $hRule->interface }}</td>
                                    <td class="px-6 py-3 font-mono text-xs">{{ $hRule->protocol }}</td>
                                    <td class="px-6 py-3 font-mono text-xs">{{ $hRule->source }}</td>
                                    <td class="px-6 py-3 font-mono text-xs">{{ $hRule->destination }}</td>
                                    <td class="px-6 py-3 text-xs italic truncate max-w-[200px]">{{ $hRule->description }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</body>
</html>