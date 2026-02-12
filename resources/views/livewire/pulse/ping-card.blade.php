<x-pulse::card :cols="$cols" :rows="$rows" :class="$class" wire:poll.2s="checkPing">
    <x-pulse::card-header name="Ping Local (Loopback)" title="Latence Réseau" details="{{ $host }}:{{ $port }}">
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z" />
            </svg>
        </x-slot:icon>
        
        <x-slot:actions>
            <button wire:click="checkPing" title="Rafraîchir">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 hover:text-gray-200 transition">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>
        </x-slot:actions>
    </x-pulse::card-header>
    <x-pulse::scroll :expand="true" class="flex flex-col items-center justify-center p-4">
        @if ($isOnline)
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-bold tabular-nums text-gray-900 dark:text-gray-100">
                    {{ $pingTime }}
                </span>
                <span class="text-xl font-medium text-gray-500 dark:text-gray-400">ms</span>
            </div>

            <div class="mt-2 flex items-center gap-2 text-sm font-medium text-emerald-500">
                <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span>Connexion Optimale</span>
            </div>
        @else
            <div class="flex flex-col items-center">
                <span class="text-3xl font-bold text-red-500">OFFLINE</span>
                <div class="mt-2 flex items-center gap-2 text-sm font-medium text-red-400">
                    <div class="h-2 w-2 rounded-full bg-red-500"></div>
                    <span>Port inaccessible</span>
                </div>
            </div>
        @endif
    </x-pulse::scroll>
</x-pulse::card>