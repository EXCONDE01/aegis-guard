<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'FireNet Command Center')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        telemetry: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for Modals and Dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col" x-data="{ userMenuOpen: false, showEmergencyModal: false, confirmText: '', isDispatching: false }">

    <!-- GLOBAL HEADER (Dark Navy) -->
    <header class="bg-slate-900 text-white shrink-0 border-b border-slate-800">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Branding -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-sky-500 rounded flex items-center justify-center shadow-lg shadow-sky-500/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight leading-none text-white">FireNet</h1>
                    <p class="text-[9px] font-bold tracking-widest text-slate-400 uppercase mt-0.5">Hazard Detection</p>
                </div>
            </div>

            <!-- Global Status Center -->
            <div class="hidden md:flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800/50 border border-slate-700/50">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] font-telemetry font-bold text-slate-300 uppercase tracking-widest">System Online</span>
            </div>

            <!-- User Menu -->
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-white">{{ auth()->user()->name ?? 'System Administrator' }}</p>
                    <p class="text-[10px] text-slate-400 uppercase tracking-widest">{{ auth()->user()->role ?? 'Admin' }}</p>
                </div>
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen" class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center border border-slate-600 hover:border-slate-400 transition-colors focus:outline-none">
                        <span class="text-sm font-bold text-white">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                    </button>
                    <!-- Dropdown -->
                    <div x-show="userMenuOpen" @click.away="userMenuOpen = false" style="display: none;" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl py-1 z-50 border border-gray-200">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <hr class="my-1 border-gray-200">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold">Sign Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- MODULE NAVIGATION (Light Bar) -->
    <nav class="bg-white border-b border-gray-200 shadow-sm shrink-0 sticky top-0 z-40">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <ul class="flex items-center gap-1.5 overflow-x-auto hide-scrollbar text-[13px] font-medium py-2.5">
                
                <!-- Monitoring Core -->
                <li class="shrink-0">
                    <a href="{{ route('dashboard') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        Live Telemetry
                    </a>
                </li>
                <li class="shrink-0">
                    <a href="{{ route('admin.history') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.history') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        Hazard History
                    </a>
                </li>

                <div class="w-px h-4 bg-gray-200 mx-2 hidden md:block rounded-full"></div>

                <!-- Emergency Response -->
                <li class="shrink-0">
                    <a href="{{ route('admin.contacts') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.contacts') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                        Alert Directory
                    </a>
                </li>

                <!-- RESTRICTED: IT ADMIN ONLY -->
                @if(auth()->user()->role === 'admin')
                    <div class="w-px h-4 bg-gray-200 mx-2 hidden md:block rounded-full"></div>

                    <!-- System Configuration -->
                    <li class="shrink-0">
                        <a href="{{ route('admin.nodes') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.nodes') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                            Sensor Nodes
                        </a>
                    </li>
                    <li class="shrink-0">
                        <a href="{{ route('admin.thresholds') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.thresholds') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                            Thresholds
                        </a>
                    </li>
                    <li class="shrink-0">
                        <a href="{{ route('admin.backups.index') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.backups.*') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                            System Backups
                        </a>
                    </li>
                    <li class="shrink-0">
                        <a href="{{ route('admin.users') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.users') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                            User Management
                        </a>
                    </li>
                    <li class="shrink-0">
                        <a href="{{ route('admin.settings') }}" class="block px-3.5 py-1.5 rounded-md transition-colors duration-200 {{ request()->routeIs('admin.settings') ? 'bg-sky-50 text-sky-700 font-semibold' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-100' }}">
                            Settings
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- MAIN WORKSPACE -->
    <main class="flex-1 w-full max-w-[1920px] mx-auto p-4 sm:p-6 lg:p-8 flex flex-col">
        
        <!-- GLOBAL FLASH MESSAGES -->
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                 x-transition.opacity.duration.500ms 
                 class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-bold tracking-wide">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms 
                 class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <span class="text-sm font-bold tracking-wide">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Dynamic Header Row (Title + Buttons) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">@yield('header_title')</h2>
                <p class="text-sm text-gray-500 mt-1">Live environmental telemetry & system health.</p>
            </div>
            <div class="flex-shrink-0">
                @yield('header_actions')
            </div>
        </div>

        <!-- Injected View Content -->
        <div class="w-full h-full">
            @yield('content')
        </div>
        
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-200 mt-auto shrink-0">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-2">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aegis-Guard © {{ date('Y') }} Laguna State Polytechnic University</p>
            <p class="text-[10px] font-telemetry text-gray-400">v1.2.0-STABLE</p>
        </div>
    </footer>

    <!-- Modals -->
    @yield('modals')

    <!-- Scripts -->
    @yield('scripts')
    
    <style>
        /* Hide scrollbar for the entire page but keep scrolling enabled */
        html, body {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        html::-webkit-scrollbar, body::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        /* Hide scrollbar for inner horizontal menus using this class */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</body>
</html>