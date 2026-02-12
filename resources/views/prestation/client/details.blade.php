<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pro->libelleetablissement }} - Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans text-gray-600" 
      x-data="{ showRdvModal: {{ request()->has('date') ? 'true' : 'false' }} }">

    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-8 sticky top-0 z-40">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
        </a>
        <div class="ml-8 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-[#2b90c7]">Accueil</a>
            <span class="mx-2">></span>
            <a href="{{ route('client.recherche') }}" class="hover:text-[#2b90c7]">Recherche</a>
            <span class="mx-2">></span>
            <span class="text-gray-800 font-medium">{{ $pro->libelleetablissement }}</span>
        </div>
    </nav>

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 pb-20">

        <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative">
            
            <div class="h-64 bg-gray-200 w-full flex items-center justify-center relative overflow-hidden">
                <div class="absolute bottom-0 w-full flex justify-center opacity-20">
                    <i class="fa-solid fa-mountain text-9xl text-gray-400 transform translate-y-10"></i>
                    <i class="fa-solid fa-mountain text-8xl text-gray-500 transform -translate-x-10 translate-y-10"></i>
                </div>
            </div>

            <div class="px-8 pb-6">
                <div class="flex flex-col md:flex-row items-start gap-6 relative">
                    
                    <div class="-mt-16 relative z-10">
                        <div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-[5px] border-white bg-white shadow-md overflow-hidden flex items-center justify-center">
                            @if(!empty($pro->photo))
                                <img src="{{ asset($pro->photo) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                @php
                                    $icon = 'fa-store';
                                    if (str_contains(strtolower($pro->libelletypeetablissement), 'vétérinaire')) $icon = 'fa-user-doctor';
                                    elseif (str_contains(strtolower($pro->libelletypeetablissement), 'toilett')) $icon = 'fa-soap';
                                @endphp
                                <i class="fa-solid {{ $icon }} text-5xl text-gray-300"></i>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 flex-1">
                        <div class="flex flex-col gap-1">
                            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                                {{ $pro->libelleetablissement }}
                            </h1>
                            
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="bg-[#2c3e50] text-white text-xs px-3 py-1 rounded-md font-bold uppercase">
                                    <i class="fa-solid fa-building mr-1"></i> {{ $pro->libelletypeetablissement }}
                                </span>
                            </div>

                            <div class="mt-2 text-gray-500 flex items-center gap-2 text-sm">
                                <i class="fa-solid fa-location-dot"></i>
                                {{ $pro->ville }} - {{ $pro->cp }} {{ $pro->ville }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0 pt-4">
                        <button @click="showRdvModal = true" class="bg-[#2b90c7] hover:bg-[#207bb0] text-white px-6 py-2.5 rounded-lg font-medium shadow-sm transition flex items-center gap-2 transform hover:scale-105">
                            <i class="fa-regular fa-calendar-check"></i>
                            Prendre rendez-vous
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">

            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">
                        Présentation de la société {{ strtoupper($pro->libelleetablissement) }}
                    </h2>
                    
                    <div class="text-gray-600 leading-relaxed space-y-4 text-justify">
                            <p class="text-gray-400 italic">
                                Établie à {{ $pro->ville }} ({{ $pro->cp }}), elle est spécialisée dans le secteur d'activité des {{ strtolower($pro->libelletypeetablissement) }}s.
                            </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 pb-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Localisation</h3>
                    </div>
                    
                    <div class="h-48 w-full relative group">
                        <iframe 
                            width="100%" 
                            height="100%" 
                            frameborder="0" 
                            scrolling="no" 
                            marginheight="0" 
                            marginwidth="0" 
                            src="https://maps.google.com/maps?q={{ urlencode($pro->adresse . ' ' . $pro->cp . ' ' . $pro->ville) }}&t=&z=13&ie=UTF8&iwloc=&output=embed">
                        </iframe>

                        <div class="absolute bottom-0 w-full flex z-10">
                            <a href="https://waze.com/ul?q={{ urlencode($pro->cp . ' ' . $pro->ville) }}" target="_blank" class="flex-1 bg-[#2c3e50] hover:bg-gray-800 text-white py-2 text-center text-sm font-medium transition border-r border-gray-600 opacity-90 hover:opacity-100">
                                <i class="fa-solid fa-car mr-2"></i> Itinéraire
                            </a>
                            <a href="https://maps.google.com/?q={{ urlencode($pro->cp . ' ' . $pro->ville) }}" target="_blank" class="flex-1 bg-[#10b981] hover:bg-green-700 text-white py-2 text-center text-sm font-medium transition opacity-90 hover:opacity-100">
                                <i class="fa-solid fa-map mr-2"></i> Carte
                            </a>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <i class="fa-solid fa-location-dot mt-1 text-gray-400"></i>
                            <div>
                                <p class="font-medium text-gray-900">{{ $pro->ville }}, {{ $pro->cp }}</p>
                                <p class="text-sm text-gray-500">{{ $pro->pays }}</p>
                            </div>
                        </div>

                        @if(!empty($pro->melpro))
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-envelope mt-1 text-gray-400"></i>
                            <a href="mailto:{{ $pro->melpro }}" class="text-sm text-gray-600 hover:text-[#2b90c7] transition truncate">
                                {{ $pro->melpro }}
                            </a>
                        </div>
                        @endif

                        <button class="w-full border border-gray-300 text-gray-600 font-medium py-2 rounded-lg hover:bg-gray-50 transition flex items-center justify-center gap-2">
                            <i class="fa-solid fa-share-nodes"></i> Partager
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Horaires</h3>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($joursSemaine as $jour)
                            @php
                                $horaire = $horairesDisponibles->get($jour);
                                $isToday = \Carbon\Carbon::now()->locale('fr')->dayName === strtolower($jour);
                                $fontClass = $isToday ? 'font-bold text-gray-900' : 'font-medium text-gray-600';
                            @endphp

                            <div class="flex justify-between items-start text-sm border-b border-gray-50 last:border-0 pb-2 last:pb-0">
                                <span class="{{ $fontClass }} w-24">{{ $jour }}</span>
                                
                                <span class="{{ $fontClass }} text-right flex-1">
                                    @if($horaire)
                                        @if($horaire->heuredebutmatine && $horaire->heurefinmatine)
                                            {{ \Carbon\Carbon::parse($horaire->heuredebutmatine)->format('H:i') }} 
                                            - 
                                            {{ \Carbon\Carbon::parse($horaire->heurefinmatine)->format('H:i') }}
                                        @endif

                                        @if(($horaire->heuredebutmatine && $horaire->heurefinmatine) && ($horaire->heuredebutaprem && $horaire->heurefinaprem))
                                            <span class="mx-1">/</span>
                                        @endif

                                        @if($horaire->heuredebutaprem && $horaire->heurefinaprem)
                                            {{ \Carbon\Carbon::parse($horaire->heuredebutaprem)->format('H:i') }} 
                                            - 
                                            {{ \Carbon\Carbon::parse($horaire->heurefinaprem)->format('H:i') }}
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Fermé</span>
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Moyen de paiement</h3>
                    <p class="text-sm text-gray-500 italic">
                        Aucun moyen de paiement renseigné
                    </p>
                </div>

            </div>

        </div>

    </div>

    <div x-show="showRdvModal" x-cloak class="fixed inset-0 z-[60] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showRdvModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showRdvModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="showRdvModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-200">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Choisir un créneau</h3>
                    <button type="button" @click="showRdvModal = false" class="text-gray-400 hover:text-gray-500">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="px-4 py-5 sm:p-6" x-data="calendarManager()">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                        <p class="text-sm text-gray-500">Sélectionnez une date et une heure pour votre rendez-vous chez <strong>{{ $pro->libelleetablissement }}</strong>.</p>
                        
                        <form action="{{ route('client.pro.details', $pro->idpro) }}" method="GET" class="mt-4 md:mt-0 flex items-center gap-2">
                            <label for="date_custom" class="text-sm text-gray-500 whitespace-nowrap">Aller au :</label>
                            
                            <div class="flex shadow-sm rounded-md">
                                <input type="date" id="date_custom" name="date" 
                                    min="{{ date('Y-m-d') }}"
                                    class="border border-gray-300 rounded-l-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer border-r-0">
                                
                                <button type="submit" class="bg-[#2c3e50] text-white px-3 py-1.5 rounded-r-md text-sm font-medium hover:bg-[#1a252f] transition">
                                    OK
                                </button>
                            </div>
                        </form>
                    </div>

                    <div id="calendar-container">
                        @if(isset($prochainsJours) && count($prochainsJours) > 0)
                            @include('prestation.client.partials.calendar')
                        @else
                            <div class="text-center py-10">
                                <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Aucune disponibilité trouvée.</p>
                                <div class="mt-4">
                                    <button @click.prevent="loadCalendar('{{ $linkNext }}')" class="text-[#2b90c7] hover:underline font-medium">
                                        Chercher la semaine suivante <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showRdvModal = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('calendarManager', () => ({
                loading: false,

                loadCalendar(url) {
                    if(!url || url === '#') return;
                    
                    this.loading = true;

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('calendar-container').innerHTML = html;
                        
                        const urlParams = new URLSearchParams(url.split('?')[1]);
                        const newDate = urlParams.get('date');
                        const dateInput = document.getElementById('date_custom');
                        if(newDate && dateInput) {
                            dateInput.value = newDate;
                        }

                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        this.loading = false;
                    });
                }
            }))
        });
    </script>
    <x-chatbot />

</body>
</html>