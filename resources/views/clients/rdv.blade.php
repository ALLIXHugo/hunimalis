<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Rendez-vous | Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="h-full antialiased text-gray-600 bg-white">

    @php
        // 1. On récupère la personne connectée pour l'affichage de la sidebar
        $personne = Auth::user()->personne;

        // 2. On définit la fonction sortLink pour les colonnes du tableau
        function sortLink($col, $label, $currentSort, $currentDir, $currentTab, $currentSearch) {
            $isActive = $currentSort === $col;
            $newDir = ($isActive && $currentDir === 'asc') ? 'desc' : 'asc';
            $icon = 'fa-sort'; 
            if ($isActive) {
                $icon = $currentDir === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
            }
            
            $url = route('rdv.client.index', [
                'tab' => $currentTab,
                'search' => $currentSearch,
                'sort' => $col,
                'direction' => $newDir
            ]);

            return '<a href="'.$url.'" class="group inline-flex items-center space-x-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-900">
                        <span>'.$label.'</span>
                        <span class="ml-2 flex-none rounded text-gray-400 group-hover:visible group-focus:visible">
                            <i class="fa-solid '.$icon.'"></i>
                        </span>
                    </a>';
        }
    @endphp

    <div class="min-h-full flex flex-col">
        
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 h-16 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex justify-between items-center h-full">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 hover:opacity-80 transition">
                            <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('client.boutique') }}" class="text-sm font-medium hover:text-[#00C497] transition-colors">Boutique</a>
                        <div class="shrink-0 flex justify-center">
                            @if(!empty($personne->avatar))
                                <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm border-2 border-white object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>


        <div class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                <aside class="hidden lg:block lg:col-span-3">
                    <div class="sticky top-10">
                        <div class="bg-gray-50/50 rounded-lg p-6 text-center mb-6">
                            <div class="shrink-0 flex justify-center mb-3">
                                @if(!empty($personne->avatar))
                                    <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full shadow-sm border-2 border-white object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-20 h-20 rounded-full shadow-sm border-2 border-white">
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 font-medium break-all">
                                {{ $personne->mail }}
                            </p>
                        </div>

                        <nav class="space-y-1">
                            <a href="{{ route('clients.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-regular fa-user w-5 text-center text-gray-600"></i> Mon profil
                            </a>

                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux
                                </div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux ?? 0 }}</span>
                            </a>

                            <a href="{{ route('rdv.client.index') }}" class="flex items-center justify-between px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-regular fa-calendar w-5 text-center"></i> Rendez-vous
                                </div>
                            </a>

                            <a href="{{ route('client.paiements.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-regular fa-credit-card w-5 text-center"></i> Paiement
                                </div>
                            </a>
                            <a href="{{ route('client.settings') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-shield-halved w-5 text-center"></i> Données & Confidentialité
                                </div>
                            </a>
                            <a href="{{ route('client.profile.destroy') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-solid fa-trash w-5 text-center"></i> Suppression du compte
                            </a>                            
                            <div class="pt-4 mt-4">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-red-500 hover:text-red-700 font-medium transition-colors text-sm">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i> Se déconnecter
                                    </button>
                                </form>
                            </div>
                        </nav>
                    </div>
                </aside>

                <main class="lg:col-span-9">
                    
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex justify-between">
                            <div class="flex items-center"><i class="fa-solid fa-circle-check text-xl mr-3"></i><span>{{ session('success') }}</span></div>
                            <button onclick="this.parentElement.style.display='none'" class="text-green-700"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center flex-wrap gap-4">
                            <h1 class="text-2xl font-bold text-gray-900">Mes réservations</h1>
                            <a href="{{ route('client.recherche') }}" class="bg-[#2c3e50] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-[#1a252f] transition flex items-center gap-2">
                                <i class="fa-solid fa-plus"></i> Nouveau RDV
                            </a>
                        </div>

                        <div class="p-6">
                            
                            <div class="border-b border-gray-200 mb-6">
                                <nav class="-mb-px flex space-x-8 justify-center" aria-label="Tabs">
                                    
                                    <a href="{{ route('rdv.client.index', ['tab' => 'encours', 'search' => $search]) }}" 
                                       class="{{ $tab === 'encours' ? 'border-[#2c3e50] text-[#2c3e50]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                                        <i class="fa-solid fa-hourglass-half {{ $tab === 'encours' ? 'text-blue-500' : '' }}"></i> 
                                        En cours
                                    </a>

                                    <a href="{{ route('rdv.client.index', ['tab' => 'avenir', 'search' => $search]) }}" 
                                       class="{{ $tab === 'avenir' ? 'border-[#2c3e50] text-[#2c3e50]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                                        <i class="fa-solid fa-calendar-check {{ $tab === 'avenir' ? 'text-green-500' : '' }}"></i> 
                                        À venir
                                    </a>

                                    <a href="{{ route('rdv.client.index', ['tab' => 'passe', 'search' => $search]) }}" 
                                       class="{{ $tab === 'passe' ? 'border-[#2c3e50] text-[#2c3e50]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                                        <i class="fa-solid fa-clock-rotate-left {{ $tab === 'passe' ? 'text-gray-400' : '' }}"></i> 
                                        Passées
                                    </a>
                                </nav>
                            </div>

                            <div class="flex justify-end mb-4">
                                <form id="searchForm" action="{{ route('rdv.client.index') }}" method="GET" class="relative w-full md:w-72">
                                    <input type="hidden" name="tab" value="{{ $tab }}">
                                    <input type="hidden" name="sort" value="{{ $sort }}">
                                    <input type="hidden" name="direction" value="{{ $direction }}">

                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                    </div>
                                    <input type="text" id="searchInput" name="search" value="{{ $search }}" 
                                           placeholder="Rechercher..." 
                                           class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border py-2"
                                           {{ request('search') ? 'autofocus' : '' }} 
                                           onfocus="var temp_value=this.value; this.value=''; this.value=temp_value"> 
                                </form>
                            </div>

                            <div class="overflow-x-auto min-h-[300px]">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left">
                                                {!! sortLink('etablissement', 'Etablissement', $sort, $direction, $tab, $search) !!}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left">
                                                {!! sortLink('date', 'Date & Heure', $sort, $direction, $tab, $search) !!}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left">
                                                {!! sortLink('animal', 'Animal', $sort, $direction, $tab, $search) !!}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left">
                                                {!! sortLink('statut', 'Statut', $sort, $direction, $tab, $search) !!}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($rdvs as $rdv)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $rdv->prestations->first()->professionnel->libelleetablissement ?? 'Hunimalis Pro' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $rdv->prestations->first()->nomprestation ?? 'Prestation' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 font-semibold">{{ \Carbon\Carbon::parse($rdv->daterdv)->format('d/m/Y') }}</div>
                                                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($rdv->heurerdv)->format('H:i') }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <span class="text-sm font-medium text-gray-900">{{ $rdv->animal->nom1animal ?? 'Inconnu' }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        // Calcul pour savoir si le RDV est fini (Heure + 1h)
                                                        $dateDebut = \Carbon\Carbon::parse($rdv->daterdv . ' ' . $rdv->heurerdv);
                                                        $dateFin = $dateDebut->copy()->addHour();
                                                        $isFinished = now()->gt($dateFin);
                                                    @endphp

                                                    @if($rdv->idstatut == 3 || $rdv->idstatut == 6)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                                            Annulé
                                                        </span>
                                                    @elseif($isFinished) 
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                            Terminé
                                                        </span>
                                                    @elseif($rdv->idstatut == 1)
                                                        @if($dateDebut->isPast() && !$isFinished)
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200 animate-pulse">
                                                                En cours
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                                À venir
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                                            {{ $rdv->statut->libellestatut ?? 'Autre' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('rdv.client.show', $rdv->idrdv) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" title="Voir détails">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                    @if($rdv->idstatut == 1 && \Carbon\Carbon::parse($rdv->daterdv)->isFuture())
                                                    <form action="{{ route('rdv.cancel', $rdv->idrdv) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-red-400 hover:text-red-700 transition" title="Annuler le RDV">
                                                            <i class="fa-regular fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-10">
                                                    <div class="flex flex-col items-center justify-center text-center text-gray-500">
                                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                            <i class="fa-regular fa-calendar-xmark text-3xl text-gray-400"></i>
                                                        </div>
                                                        <h3 class="text-lg font-medium text-gray-900">Aucun rendez-vous</h3>
                                                        <p class="mt-1">Aucun rendez-vous trouvé dans cette catégorie.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 flex justify-end">
                                {{ $rdvs->links('pagination::tailwind') }}
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(function() {
                    searchForm.submit();
                }, 500); 
            });
        }
    </script>
</body>
</html>