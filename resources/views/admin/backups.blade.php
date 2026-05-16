<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | System Backups</title>
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
                <a href="{{ route('admin.network') }}" class="flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/50 rounded-lg text-slate-400 hover:text-slate-200 transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                    Gateway & VLAN
                </a>
                <a href="{{ route('admin.backups.index') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors mt-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
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
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">System Backups</h1>
                <p class="text-sm text-slate-500 mt-1">Data Retention and Disaster Recovery Snapshots</p>
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

            <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 flex flex-col md:flex-row justify-between items-center gap-6 shadow-sm">
                <div>
                    <h2 class="text-lg font-bold text-white">Generate Database Dump</h2>
                    <p class="text-sm text-slate-500 mt-1">Create an immediate .sql snapshot of all environmental logs and configurations.</p>
                </div>
                
                <form action="{{ route('admin.backups.generate') }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                        Trigger Backup
                    </button>
                </form>
            </div>

            <div class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                    <h3 class="font-bold text-white text-base">Recent Archive Files</h3>
                </div>
                <div class="divide-y divide-slate-800">
                    
                    @forelse($backups as $index => $backup)
                    <div class="p-6 flex items-center justify-between hover:bg-slate-800/50 transition-colors group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $index === 0 ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-slate-950 text-slate-500 border border-slate-800' }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ $backup['name'] }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-1">System generated • {{ $backup['size'] }} • {{ $backup['date'] }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.backups.download', $backup['name']) }}" class="{{ $index === 0 ? 'text-indigo-400 hover:text-indigo-300 bg-indigo-500/10' : 'text-slate-400 hover:text-white bg-slate-950 border border-slate-800' }} font-semibold text-xs px-4 py-2 rounded-lg transition-colors">
                            Download
                        </a>
                    </div>
                    @empty
                    <div class="p-12 text-center text-slate-500 text-sm">
                        No system backups found. Click "Trigger Backup" to create an initial snapshot.
                    </div>
                    @endforelse

                </div>
            </div>
        </main>
    </div>
</body>
</html>