<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aegis-Guard | Secure Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B1120] text-slate-300 font-sans h-screen flex items-center justify-center overflow-hidden relative">

    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-indigo-600/20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md bg-[#0F172A]/90 backdrop-blur-xl p-10 rounded-3xl border border-slate-700/50 shadow-2xl z-10 relative">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 mb-5 ring-1 ring-indigo-500/30 shadow-[0_0_30px_rgba(99,102,241,0.2)]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
            </div>
            <h1 class="text-3xl font-black text-white tracking-tight">Aegis-Guard</h1>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">LSPU Secure Command Center</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Administrator Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full bg-[#0B1120] border border-slate-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 px-4 shadow-inner transition-all placeholder:text-slate-600"
                       placeholder="director@lspu.edu.ph">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-xs font-medium" />
            </div>

            <div>
                <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Access Credential</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full bg-[#0B1120] border border-slate-700 text-white text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3.5 px-4 shadow-inner transition-all placeholder:text-slate-600"
                       placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-xs font-medium" />
            </div>

            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded bg-[#0B1120] border-slate-700 text-indigo-500 shadow-sm focus:ring-indigo-500 focus:ring-offset-[#0F172A]" name="remember">
                    <span class="ml-2 text-xs font-medium text-slate-400">Maintain Session Configuration</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-500 focus:ring-4 focus:outline-none focus:ring-indigo-500/50 font-black uppercase text-xs rounded-xl px-5 py-4 text-center transition-all shadow-[0_0_20px_rgba(79,70,229,0.3)] hover:shadow-[0_0_30px_rgba(79,70,229,0.5)] tracking-widest">
                    Initialize Access
                </button>
            </div>
        </form>
    </div>
    
    <div class="fixed bottom-8 text-center w-full pointer-events-none">
        <p class="text-[10px] text-slate-600 font-black uppercase tracking-widest flex justify-center items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
            Authorized Personnel Only • Network Activity Monitored
        </p>
    </div>
</body>
</html>