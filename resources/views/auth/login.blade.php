<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Center Access | FireNet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-telemetry { font-family: 'JetBrains Mono', 'Courier New', monospace; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Subtle Background Elements -->
    <div class="absolute top-0 left-0 w-full h-96 bg-sky-600/5 -skew-y-6 transform origin-top-left -z-10"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-4xl w-full bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden flex flex-col md:flex-row z-10">
        
        <!-- Left Side: Branding & Context -->
        <div class="w-full md:w-5/12 bg-gradient-to-br from-sky-700 to-sky-900 p-10 flex flex-col justify-between text-white relative overflow-hidden">
            <!-- Decorative Tech Grid -->
            <div class="absolute inset-0 opacity-10 bg-[linear-gradient(#fff_1px,transparent_1px),linear-gradient(90deg,#fff_1px,transparent_1px)] bg-[size:20px_20px]"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-white/10 border border-white/20 rounded-lg flex items-center justify-center backdrop-blur-sm shadow-sm">
                        <!-- New Flame Icon -->
                        <svg class="w-6 h-6 text-sky-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" /></svg>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight text-white">FIRENET</h1>
                </div>
                <!-- Updated Copywriting -->
                <h2 class="text-3xl font-bold text-white leading-tight mb-4">Campus Fire & Smoke <br>Telemetry</h2>
                <p class="text-sky-100 text-sm font-medium leading-relaxed opacity-90">
                    Real-time environmental monitoring, zonal hardware overrides, and automated disaster recovery operations.
                </p>
            </div>
            
            <div class="relative z-10 mt-12">
                <div class="bg-black/20 border border-white/10 rounded-lg p-4 backdrop-blur-sm">
                    <p class="text-[10px] font-bold text-sky-200 uppercase tracking-widest mb-1 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        System Status: Secure
                    </p>
                    <p class="text-xs text-sky-100 font-medium opacity-80 mt-2">Laguna State Polytechnic University<br>IT Network Administration</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Authentication Form -->
        <div class="w-full md:w-7/12 p-10 lg:p-14 bg-white flex flex-col justify-center">
            
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Authorized Access</h3>
                <p class="text-sm text-gray-500 mt-1">Enter your credentials to access the administrative dashboard.</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span class="text-sm text-red-700 font-bold">Authentication Failed</span>
                    </div>
                    <ul class="mt-2 text-xs text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all shadow-inner placeholder-gray-400" placeholder="admin@firenet.local">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wide">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-sky-600 hover:text-sky-800 hover:underline transition-colors">Forgot password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                               class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-2 focus:ring-sky-500/20 focus:border-sky-500 transition-all shadow-inner placeholder-gray-400" placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500 cursor-pointer">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600 cursor-pointer font-medium">Keep me signed in on this node</label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-sky-600 hover:bg-sky-700 text-white font-bold py-3.5 px-4 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center justify-center gap-2 uppercase tracking-wider text-sm">
                        Initialize Session
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </form>
            
            <p class="text-center text-[11px] text-gray-400 mt-8 font-telemetry">
                &copy; {{ date('Y') }} FireNet Architecture. All physical telemetry encrypted.
            </p>
        </div>
    </div>
</body>
</html>