<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche - Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans text-gray-600">

    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-8 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">

            <a href="{{ route('home') }}" class="flex items-center gap-2">
            <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
            </a>
                <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('clients.dashboard') }}" class="flex items-center gap-2">
                        @if(!empty(Auth::user()->personne->avatar))
                            <img src="{{ asset('storage/' . Auth::user()->personne->avatar) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-900 hover:text-[#2b90c7] transition">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

<form action="{{ route('client.recherche') }}" method="GET" id="searchForm">

    <div class="bg-[#2b90c7] pt-10 pb-24 px-4 shadow-sm relative z-50">
        <h1 class="text-2xl md:text-3xl font-bold text-white text-center mb-8 drop-shadow-sm">
            {{ $professionnels->total() }} établissements trouvés
        </h1>

        <div class="max-w-6xl mx-auto bg-white rounded-xl p-2 flex flex-col md:flex-row gap-0 shadow-xl">

            <div class="flex-1 relative border-b md:border-b-0 md:border-r border-gray-100 p-3">
                <label class="block text-xs font-medium text-gray-500 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-hand-holding-heart text-gray-400"></i>
                    Services et établissements
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="globalSearchInput"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Toiletteur, Vétérinaire..."
                        autocomplete="off"
                        class="w-full outline-none text-gray-900 placeholder-gray-400 font-medium text-sm"
                    >

                    <ul
                        id="globalSuggestions"
                        class="hidden absolute z-[100] left-0 top-full w-full bg-white border border-gray-200 rounded-lg shadow-2xl mt-1 max-h-60 overflow-y-auto overflow-x-hidden">
                    </ul>
                </div>
            </div>

            <div class="flex-1 relative border-b md:border-b-0 md:border-r border-gray-100 p-3">
                <label class="block text-xs font-medium text-gray-500 mb-1 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-gray-400"></i>
                    Localisation
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="villeInput"
                        name="ville"
                        value="{{ request('ville') }}"
                        placeholder="Ville"
                        autocomplete="off"
                        class="w-full outline-none text-gray-900 placeholder-gray-400 font-medium text-sm"
                    >

                    <i class="fa-solid fa-crosshairs text-gray-400 cursor-pointer hover:text-blue-500 absolute right-0 top-1/2 -translate-y-1/2"></i>

                    <ul
                        id="villeSuggestions"
                        class="hidden absolute z-[100] left-0 top-full w-full bg-white border border-gray-200 rounded-lg shadow-2xl mt-1 max-h-60 overflow-y-auto overflow-x-hidden">
                    </ul>
                </div>
            </div>

            <div class="flex-1 relative p-3">
                <label class="block text-xs font-medium text-gray-500 mb-1 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-gray-400"></i>
                    Quand ?
                </label>

                <input
                    type="text"
                    placeholder="Date du rendez-vous"
                    name="date"
                    value="{{ request('date') }}"
                    min="{{ now()->format('d-m-Y') }}"
                    onfocus="(this.type='date'); this.showPicker();"
                    onblur="if(!this.value) this.type='text'"
                    onkeydown="return false"
                    class="w-full outline-none text-gray-900 placeholder-gray-400 font-medium text-sm bg-transparent cursor-pointer"
                >
            </div>

            <div class="p-2 flex items-center justify-center">
                <button
                    type="submit"
                    class="bg-[#1e293b] text-white w-12 h-12 rounded-full flex items-center justify-center hover:bg-gray-800 transition-transform transform hover:scale-105 shadow-md">
                    <i class="fa-solid fa-magnifying-glass text-lg"></i>
                </button>
            </div>

        </div>
        <div class="text-center mt-6">
            <a href="{{ route('client.boutique') }}" class="inline-flex items-center gap-2 text-white hover:text-gray-200 underline decoration-2 underline-offset-4 transition">
                <i class="fa-solid fa-bag-shopping"></i>
                Je recherche un produit plutôt qu'un service
            </a>
        </div>
    </div>


    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="hidden lg:block lg:col-span-3 pt-16">
                <div class="flex flex-col gap-6">

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide border-b pb-2">
                            Type d'établissement
                        </h3>

                        <div class="space-y-2.5 max-h-60 overflow-y-auto scrollbar-hide">
                            @php
                                $types = ['toiletteur', 'pension', 'ostéopathe', 'éleveur', 'éducateur', 'vétérinaire', 'pet sitter', 'garderie'];
                            @endphp

                            @foreach($types as $type)
                                <label class="flex items-center gap-3 cursor-pointer group hover:bg-gray-50 p-1 rounded transition">
                                    <input
                                        type="checkbox"
                                        name="types[]"
                                        value="{{ $type }}"
                                        @checked(in_array($type, request('types', [])))
                                        class="peer w-4 h-4 rounded border-gray-300 text-[#2b90c7] focus:ring-[#2b90c7] cursor-pointer"
                                    >
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium select-none">
                                        {{ ucfirst($type) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4 text-sm uppercase tracking-wide border-b pb-2">
                            Espèces
                        </h3>

                        <div class="space-y-2.5">
                            @php
                                $especes = [2 => 'Chien', 1 => 'Chat', 3 => 'Lapin'];
                            @endphp

                            @foreach($especes as $id => $nom)
                                <label class="flex items-center gap-3 cursor-pointer group hover:bg-gray-50 p-1 rounded transition">
                                    <input
                                        type="checkbox"
                                        name="especes[]"
                                        value="{{ $id }}"
                                        @checked(in_array($id, request('especes', [])))
                                        class="w-4 h-4 rounded border-gray-300 text-[#2b90c7] focus:ring-[#2b90c7] cursor-pointer"
                                    >
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 font-medium select-none">
                                        {{ $nom }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gray-100 text-gray-700 font-bold py-2 rounded-lg hover:bg-gray-200 transition">
                        Appliquer les filtres
                    </button>

                    @if(request()->anyFilled(['types', 'especes', 'q', 'ville']))
                        <div class="text-center">
                            <a
                                href="{{ route('client.recherche') }}"
                                class="text-xs text-red-500 hover:text-red-700 underline">
                                Tout effacer
                            </a>
                        </div>
                    @endif

                </div>
            </div>

            <div class="lg:col-span-9 pt-4">

                @if($professionnels->isEmpty())
                    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 mt-12">
                        <i class="fa-solid fa-magnifying-glass text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-bold text-gray-900">Aucun résultat trouvé</h3>
                        <p class="text-gray-500">Essayez d'élargir votre recherche ou de changer de ville.</p>
                    </div>
                @else
                    <div class="space-y-6 mt-12">

                        @foreach($professionnels as $pro)
                            @php
                                $typeLower = strtolower($pro->libelletypeetablissement);
                                $bgClass = 'bg-gray-500';
                                $iconClass = 'fa-store';

                                if (str_contains($typeLower, 'toilett')) {
                                    $bgClass = 'bg-purple-600';
                                    $iconClass = 'fa-soap';
                                } elseif (str_contains($typeLower, 'vétérinaire')) {
                                    $bgClass = 'bg-blue-600';
                                    $iconClass = 'fa-user-doctor';
                                } elseif (str_contains($typeLower, 'éduc')) {
                                    $bgClass = 'bg-orange-500';
                                    $iconClass = 'fa-graduation-cap';
                                } elseif (str_contains($typeLower, 'pens') || str_contains($typeLower, 'gard')) {
                                    $bgClass = 'bg-green-600';
                                    $iconClass = 'fa-house-chimney';
                                }
                            @endphp

                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition duration-300 relative group">
                                <div class="flex flex-col md:flex-row gap-6">

                                    <div class="relative shrink-0 flex justify-center md:justify-start">
                                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden border-4 border-gray-50 flex items-center justify-center {{ $bgClass }}">
                                            <i class="fa-solid {{ $iconClass }} text-4xl text-white opacity-90"></i>
                                        </div>

                                        <span class="absolute -top-2 -right-2 bg-white text-gray-700 text-xs font-bold px-3 py-1 rounded-full border border-gray-200 shadow-sm uppercase tracking-wide">
                                            {{ $pro->libelletypeetablissement }}
                                        </span>
                                    </div>

                                    <div class="flex-1 flex flex-col">

                                        <div class="mb-3">
                                            <h2 class="text-xl font-bold mb-1">
                                                <a href="{{ route('client.pro.details', $pro->idpro ?? $pro->id) }}" class="text-gray-900 hover:text-[#2b90c7] transition-colors">
                                                    {{ $pro->libelleetablissement }}
                                                </a>
                                            </h2>

                                            <p class="text-gray-500 text-sm flex items-center gap-2">
                                                <i class="fa-solid fa-location-dot text-gray-400"></i>
                                                {{ $pro->ville }} ({{ $pro->cp }})
                                            </p>
                                        </div>
                                        
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-auto border-t border-gray-50 pt-4">
                                            <div class="flex items-center gap-2 text-gray-700 font-semibold text-sm">
                                                <i class="fa-solid fa-phone text-gray-400"></i>
                                                {{ $pro->telpro }}
                                            </div>

                                            <a
                                                href="{{ route('client.pro.details', $pro->idpro ?? $pro->id) }}"
                                                class="bg-[#111827] text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-black transition shadow-lg shadow-gray-200 w-full sm:w-auto text-center">
                                                Voir sa fiche
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $professionnels->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</form>

<script>
    const villeInput = document.getElementById('villeInput');
    const suggestionsList = document.getElementById('villeSuggestions');

    villeInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 2) {
            suggestionsList.classList.add('hidden');
            return;
        }

        fetch("{{ route('api.villes.search') }}?q=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsList.innerHTML = '';

                if (data.length > 0) {
                    suggestionsList.classList.remove('hidden');

                    data.forEach(item => {
                        const li = document.createElement('li');
                        const texteVille = `${item.ville} (${item.cp})`;

                        li.textContent = texteVille;
                        li.className = "px-4 py-2 hover:bg-gray-100 cursor-pointer text-sm text-gray-700 transition";

                        li.addEventListener('click', () => {
                            villeInput.value = texteVille;
                            suggestionsList.classList.add('hidden');
                        });

                        suggestionsList.appendChild(li);
                    });
                } else {
                    suggestionsList.classList.add('hidden');
                }
            });
    });

    document.addEventListener('click', function (e) {
        if (!villeInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.classList.add('hidden');
        }
    });

    const globalInput = document.getElementById('globalSearchInput');
    const globalList = document.getElementById('globalSuggestions');

    globalInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length < 2) {
            globalList.classList.add('hidden');
            return;
        }

        fetch("{{ route('api.search.global') }}?q=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                globalList.innerHTML = '';

                if (data.length > 0) {
                    globalList.classList.remove('hidden');

                    data.forEach(item => {
                        const li = document.createElement('li');

                        let iconHtml = '';

                        if (item.type === 'etablissement') {
                            iconHtml = '<i class="fa-solid fa-store text-blue-500 mr-2"></i>';
                        } else {
                            iconHtml = '<i class="fa-solid fa-scissors text-purple-500 mr-3 pl-1"></i>';
                        }

                        li.innerHTML = `
                            <div class="flex items-center">
                                ${iconHtml}
                                <span class="text-gray-700 font-medium">${item.label}</span>
                            </div>
                        `;

                        li.className = "block w-full px-4 py-2 hover:bg-gray-50 cursor-pointer text-sm transition border-b border-gray-100 last:border-0";

                        li.addEventListener('click', () => {
                            globalInput.value = item.label;
                            globalList.classList.add('hidden');
                        });

                        globalList.appendChild(li);
                    });
                } else {
                    globalList.classList.add('hidden');
                }
            });
    });

    document.addEventListener('click', function (e) {
        if (!globalInput.contains(e.target) && !globalList.contains(e.target)) {
            globalList.classList.add('hidden');
        }
    });
</script>
    @php
        $panierSession = session('panier_client', []);
        $quantiteTotale = 0;
        if($panierSession) {
            foreach($panierSession as $item) {
                $quantiteTotale += $item['quantite'];
            }
        }
    @endphp

    <a href="{{ route('client.panier') }}" 
       class="fixed bottom-24 right-6 z-40 bg-white text-indigo-600 hover:bg-indigo-50 w-14 h-14 rounded-full shadow-xl flex items-center justify-center transition-all transform hover:scale-110 border-2 border-indigo-100 group"
       title="Voir mon panier">
       
       <div class="relative">
           <i class="fa-solid fa-cart-shopping text-xl"></i>
           
           @if($quantiteTotale > 0)
               <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white animate-bounce">
                   {{ $quantiteTotale }}
               </span>
           @endif
       </div>
    </a>

    <x-chatbot /> 
</body>
</html>