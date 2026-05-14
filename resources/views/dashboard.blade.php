<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-status-card title="System Health" value="Stable" color="text-green-500" icon="heroicon-o-cpu-chip" />
        <x-status-card title="Active Clients" value="24 / 40" color="text-blue-500" icon="heroicon-o-users" />
        <x-status-card title="Threats Blocked" value="12" color="text-red-500" icon="heroicon-o-shield-check" />
        <x-status-card title="Bandwidth" value="120 Mbps" color="text-purple-500" icon="heroicon-o-arrow-trending-up" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-gray-800 border border-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-white font-bold mb-4">Academic Lab 01 - Device Map</h3>
            <div class="grid grid-cols-5 gap-4">
                @for ($i = 1; $i <= 20; $i++)
                    <div class="p-4 bg-gray-900 border border-gray-700 rounded-lg text-center">
                        <span class="block text-xs text-gray-400">PC-{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="h-2 w-2 rounded-full bg-green-500 mx-auto mt-2 animate-pulse"></div>
                    </div>
                @endfor
            </div>
        </div>

        <div class="bg-black border border-green-900 overflow-hidden shadow-sm sm:rounded-lg p-6 font-mono text-xs">
            <h3 class="text-green-500 font-bold mb-4 uppercase">Live Threat Feed</h3>
            <div class="space-y-2">
                <p class="text-gray-400">[14:22:01] <span class="text-red-500">BLOCK</span> IP 192.168.20.12 -> Malicious Domain</p>
                <p class="text-gray-400">[14:23:45] <span class="text-green-500">INFO</span> User 'Student_01' Logged In</p>
                <p class="text-gray-400">[14:25:12] <span class="text-yellow-500">WARN</span> High CPU Usage on Gateway</p>
            </div>
        </div>
    </div>
</div>