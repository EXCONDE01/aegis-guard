<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Node Management</title>
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
                <a href="{{ route('admin.nodes') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors">
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
            <h1 class="text-2xl font-bold text-white">Hardware Nodes</h1>
            <p class="text-sm text-slate-500 mt-1">Assign and calibrate physical ESP32 gateway sensors.</p>
        </header>

        <main class="p-8 pb-20 max-w-7xl mx-auto">
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm text-slate-400">
                    <thead class="bg-slate-950/50 text-xs font-semibold text-slate-500 border-b border-slate-800">
                        <tr>
                            <th class="px-6 py-4">Hardware MAC / ID</th>
                            <th class="px-6 py-4">Assigned Location</th>
                            <th class="px-6 py-4">Current Status</th>
                            <th class="px-6 py-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @foreach($nodes as $node)
                        <tr class="hover:bg-slate-800/50 transition-colors" x-data="{ editing: false }">
                            <td class="px-6 py-4 font-mono text-xs">
                                {{ $node->hardware_id }}
                            </td>
                            
                            <td class="px-6 py-4" x-show="!editing">
                                @if($node->location_name == 'New Unassigned Node')
                                    <span class="text-amber-500 font-medium flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                        Needs Assignment
                                    </span>
                                @else
                                    <p class="font-medium text-slate-200">{{ $node->location_name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $node->specific_area }}</p>
                                @endif
                            </td>

                            <td class="px-6 py-4" x-show="editing" style="display: none;" colspan="2">
                                <form action="{{ route('admin.nodes.update', $node->id) }}" method="POST" class="flex gap-3 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="location_name" value="{{ $node->location_name == 'New Unassigned Node' ? '' : $node->location_name }}" placeholder="e.g. CCS Lab 1" required class="bg-slate-950 border border-slate-700 text-white text-sm rounded-lg px-3 py-2 w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <input type="text" name="specific_area" value="{{ $node->specific_area }}" placeholder="e.g. Server Rack A" class="bg-slate-950 border border-slate-700 text-white text-sm rounded-lg px-3 py-2 w-full focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-500 whitespace-nowrap transition-colors">Save</button>
                                    <button type="button" @click="editing = false" class="text-slate-400 hover:text-white px-2 text-sm font-medium transition-colors">Cancel</button>
                                </form>
                            </td>

                            <td class="px-6 py-4" x-show="!editing">
                                <span class="px-2.5 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $node->status == 'CRITICAL' ? 'bg-red-500/10 text-red-500' : ($node->status == 'WARNING' ? 'bg-amber-500/10 text-amber-500' : 'bg-slate-800 text-slate-400') }}">
                                    {{ $node->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right" x-show="!editing">
                                <button @click="editing = true" class="text-indigo-400 hover:text-indigo-300 font-medium text-sm transition-colors">
                                    Configure
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($nodes->isEmpty())
                <div class="p-12 text-center text-slate-500 text-sm">
                    No hardware nodes detected on the network.
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>