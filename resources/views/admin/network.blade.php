<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Network & Routing Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <a href="{{ route('admin.network') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    Gateway & VLAN
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
            <h1 class="text-2xl font-bold text-white">Network & Routing Control</h1>
            <p class="text-sm text-slate-500 mt-1">Manage OPNsense VLAN isolation & emergency traffic prioritization.</p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
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
                                <td class="px-6 py-4 font-mono font-bold text-indigo-400">
                                    {{ $vlan->vlan_id }}
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-200">
                                    {{ $vlan->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                    {{ $vlan->subnet }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.network.vlan.destroy', $vlan->id) }}" method="POST" onsubmit="return confirm('WARNING: Terminating this VLAN will drop all network traffic for its subnet. Proceed?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 font-medium text-sm transition-colors">
                                            Terminate
                                        </button>
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
        </main>
    </div>
</body>
</html>