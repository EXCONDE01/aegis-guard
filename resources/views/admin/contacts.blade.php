<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Alert Contacts</title>
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
                <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg transition-colors">
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

    <div class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="bg-slate-950/80 backdrop-blur-sm border-b border-slate-800 p-8 sticky top-0 z-30">
            <h1 class="text-2xl font-bold text-white">Alert Contacts</h1>
            <p class="text-sm text-slate-500 mt-1">Manage personnel authorized to receive automated SMS alerts.</p>
        </header>

        <main class="p-8 space-y-8 pb-20 max-w-7xl mx-auto w-full">
            @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-slate-900 p-6 rounded-2xl border border-slate-800 lg:col-span-1 h-fit shadow-sm">
                    <h3 class="font-bold text-white text-base mb-5">Register Personnel</h3>
                    <form action="{{ route('admin.contacts.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Full Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="e.g. John Doe">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Role / Designation</label>
                            <input type="text" name="role" required class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="e.g. Lab Technician">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Mobile Number</label>
                            <input type="text" name="phone" required class="w-full bg-slate-950 border border-slate-700 text-white text-sm rounded-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 block p-3 transition-colors" placeholder="+639...">
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-500 font-semibold text-sm rounded-lg px-5 py-3 text-center transition-colors mt-2">
                            Save Contact
                        </button>
                    </form>
                </div>

                <div class="bg-slate-900 rounded-2xl border border-slate-800 lg:col-span-2 overflow-hidden shadow-sm h-fit">
                    <div class="p-5 border-b border-slate-800">
                        <h3 class="font-bold text-white text-base">Active Directory</h3>
                    </div>
                    <table class="w-full text-left text-sm text-slate-400">
                        <thead class="bg-slate-950/50 text-xs font-semibold text-slate-500 border-b border-slate-800">
                            <tr>
                                <th class="px-6 py-4">Personnel</th>
                                <th class="px-6 py-4">Contact Info</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800">
                            @foreach($contacts as $contact)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-200">{{ $contact->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $contact->role }}</p>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-300">
                                    {{ $contact->phone }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Remove this contact from the broadcast list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 font-medium text-sm transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($contacts->isEmpty())
                    <div class="p-12 text-center text-slate-500 text-sm">
                        No personnel registered in the directory.
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>