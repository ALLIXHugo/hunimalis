<div class="flex items-start gap-2 pt-4 border-t border-gray-100 transition-opacity duration-200"
     :class="{ 'opacity-50 pointer-events-none': loading }"> 

    <div class="pt-10">
        @if($isPrevDisabled)
            <button disabled class="p-2 rounded-full text-gray-300 cursor-not-allowed">
                <i class="fa-solid fa-chevron-left text-xl"></i>
            </button>
        @else
            <button @click.prevent="loadCalendar('{{ $linkPrev }}')" 
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-[#2b90c7] transition flex items-center justify-center h-10 w-10 shadow-sm border border-gray-200">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        @endif
    </div>

    <div class="flex-1 grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($prochainsJours as $jour)
            <div class="flex flex-col border-r border-gray-100 last:border-0" x-data="{ expanded: false }">
                
                <div class="text-center mb-4">
                    <div class="font-bold text-gray-900 capitalize">{{ $jour['nom_jour'] }}</div>
                    <div class="text-sm text-gray-500">{{ $jour['jour_mois'] }}</div>
                </div>

                <div class="space-y-2 flex flex-col items-center px-1">
                    @php 
                        $countVisible = 0; 
                        // Tableau pour stocker uniquement les créneaux valides (futurs)
                        $validSlots = [];
                    @endphp

                    @foreach($jour['creneaux'] as $heure)
                        @php
                            // On force le fuseau horaire Paris
                            $timezone = 'Europe/Paris';
                            $now = \Carbon\Carbon::now($timezone);

                            // On crée la date du créneau en forçant Paris
                            $slotDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $jour['date_full'] . ' ' . $heure, $timezone);
                        @endphp
                        
                        @if($slotDateTime->lt($now)) 
                            @continue 
                        @endif

                        @php $validSlots[] = $heure; @endphp
                    @endforeach

                    @forelse($validSlots as $index => $heure)
                        <a href="{{ route('rdv.client.confirm', ['pro' => $pro->idpro, 'date' => $jour['date_full'], 'heure' => $heure]) }}" 
                           x-show="{{ $index }} < 5 || expanded"
                           class="block w-full text-center py-2 rounded bg-[#e3f2fd] text-[#1e88e5] text-sm font-semibold hover:bg-[#1e88e5] hover:text-white transition-colors duration-200 shadow-sm border border-transparent hover:border-[#1e88e5]">
                            {{ $heure }}
                        </a>
                    @empty
                        <div class="w-full h-10 border border-dashed border-gray-300 rounded flex items-center justify-center bg-gray-50">
                            <span class="text-xs text-gray-400">-</span>
                        </div>
                    @endforelse
                    
                    @if(count($validSlots) > 5)
                        <button @click.prevent="expanded = !expanded" 
                                class="text-xs text-gray-500 hover:text-gray-800 underline mt-2 focus:outline-none">
                            <span x-show="!expanded">Voir plus ({{ count($validSlots) - 5 }})</span>
                            <span x-show="expanded" x-cloak>Voir moins</span>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="pt-10">
        <button @click.prevent="loadCalendar('{{ $linkNext }}')" 
                class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-[#2b90c7] transition flex items-center justify-center h-10 w-10 shadow-sm border border-gray-200">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

</div>