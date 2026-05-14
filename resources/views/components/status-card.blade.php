@props(['title', 'value', 'subtitle' => '', 'color' => 'text-blue-500'])

<div class="bg-gray-800 border border-gray-700 p-5 rounded-xl shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold {{ $color }}">{{ $value }}</p>
            @if($subtitle)
                <p class="text-xs text-gray-500 mt-1">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="p-3 bg-gray-900 rounded-lg">
            {{ $slot }}
        </div>
    </div>
</div>