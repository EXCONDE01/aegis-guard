<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Alert Contacts</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen bg-[#F8FAFC] font-sans text-slate-900 overflow-hidden">
    <div class="w-64 bg-[#0F172A] text-white flex flex-col shadow-2xl z-50">
        <div class="p-6">
            <h2 class="text-2xl font-black text-indigo-400">Aegis-Guard</h2>
            <p class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mt-1">LSPU Campus</p>
        </div>
        <nav class="flex-1 p-4 space-y-2 text-sm font-medium">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800/50 rounded-xl text-slate-400 transition-all">
              📡 Real-Time Map
            </a>
            <a href="{{ route('admin.history') }}" class="flex items-center gap-3 p-3 hover:bg-slate-800/50 rounded-xl text-slate-400 transition-all">
              📜 Hazard History Logs
            </a>
            <a href="{{ route('admin.contacts') }}" class="flex items-center gap-3 p-3 bg-indigo-600/10 text-indigo-400 rounded-xl ring-1 ring-indigo-500/20">
              📞 Alert Contacts
            </a>
        </nav>
        <div class="p-6 bg-[#0B1120] text-xs font-bold text-white flex items-center gap-2">
            <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> 
            Campus Director
        </div>
    </div>

    <div class="flex-1 flex flex-col h-full overflow-y-auto">
        <header class="bg-white/60 backdrop-blur-xl border-b border-slate-200 p-8 sticky top-0 z-30">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Alert Contacts Management</h1>
            <p class="text-sm text-slate-500 font-semibold mt-1">Configure automated SMS recipients for emergency broadcasts.</p>
        </header>

        <main class="p-8 space-y-8 pb-20 max-w-6xl">
            @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200/60 lg:col-span-1 h-fit">
                    <h3 class="font-black text-slate-800 text-lg mb-6">Register Personnel</h3>
                    <form action="{{ route('admin.contacts.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Full Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3" placeholder="e.g. John Doe">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Role / Designation</label>
                            <input type="text" name="role" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3" placeholder="e.g. Facility Manager">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500 mb-2">Mobile Number</label>
                            <input type="text" name="phone" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3" placeholder="+639...">
                        </div>
                        <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-black uppercase text-xs rounded-xl px-5 py-3 text-center transition-all shadow-lg shadow-indigo-500/30">
                            Save Contact
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-200/60 lg:col-span-2 overflow-hidden">
                    <div class="p-6 border-b border-slate-100">
                        <h3 class="font-black text-slate-800 text-lg">Active Directory</h3>
                    </div>
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-[10px] uppercase font-black tracking-widest text-slate-400 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4">Personnel</th>
                                <th class="px-6 py-4">Contact Info</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($contacts as $contact)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-slate-800">{{ $contact->name }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $contact->role }}</p>
                                </td>
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-700">
                                    {{ $contact->phone }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-600">
                                        Receiving Alerts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Remove this contact from the broadcast list?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-black text-[10px] uppercase tracking-widest bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition-colors">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($contacts->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <h3 class="text-lg font-black text-slate-800">No personnel registered</h3>
                        <p class="text-sm text-slate-500 mt-2 font-medium">Add a contact to ensure alerts are successfully dispatched.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>