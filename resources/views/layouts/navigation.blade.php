<aside class="w-64 bg-[#0B1120] min-h-screen border-r border-gray-800 hidden lg:block">
    <div class="p-6">
        <h1 class="text-blue-500 font-bold text-xl tracking-tighter uppercase">Aegis-Guard</h1>
        <p class="text-[10px] text-yellow-500 font-bold -mt-1">LSPU CAMPUS</p>
    </div>

    <nav class="mt-4 px-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center p-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
            <span class="mr-3">📡</span> Real-Time Map
        </a>
        <a href="{{ route('admin.history') }}" class="flex items-center p-3 text-sm font-medium rounded-lg {{ request()->routeIs('admin.history') ? 'bg-blue-600 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
            <span class="mr-3">📜</span> Hazard History Logs
        </a>
        <a href="#" class="flex items-center p-3 text-sm font-medium text-gray-400 rounded-lg hover:bg-gray-800">
            <span class="mr-3">📞</span> Alert Contacts
        </a>
    </nav>

    <div class="absolute bottom-0 w-64 p-6 border-t border-gray-800">
        <div class="flex items-center space-x-3">
            <div class="h-8 w-8 rounded bg-blue-500 flex items-center justify-center font-bold text-xs">CD</div>
            <div>
                <p class="text-xs font-bold text-white uppercase">Campus Director</p>
                <p class="text-[10px] text-green-500">Active Session</p>
            </div>
        </div>
    </div>
</aside>