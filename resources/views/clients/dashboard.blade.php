<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil | Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#f5f3ff', 100: '#ede9fe', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9' }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        input[type="radio"]:checked { accent-color: #7c3aed; }
        .form-input:focus { border-color: #7c3aed; outline: none; ring: 1px; --tw-ring-color: #7c3aed; }
        
        .suggestions-list {
            position: absolute;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-top: 4px;
        }
        .suggestions-list li {
            padding: 0.75rem 1rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #4b5563;
        }
        .suggestions-list li:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
    </style>
</head>
<body class="h-full antialiased text-gray-600 bg-white">

    <div class="min-h-full flex flex-col">
        
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 h-16 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 hover:opacity-80 transition">
                        <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
                    </a>
                </div>
                <div class="shrink-0 flex justify-center mb-3">
                    @if(!empty($personne->avatar))
                        <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm border-2 border-white object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                    @endif
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
                            <a class="flex items-center gap-3 px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-regular fa-user w-5 text-center text-gray-600"></i> Mon profil
                            </a>
                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux
                                </div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux ?? 0 }}</span>
                            </a>
                            <a href="{{ route('rdv.client.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
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
                                <i class="fa-solid fa-gear"></i></i> Paramètres
                                </div>
                            </a>
                            <a href="{{ route('client.profile.destroy') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-solid fa-shield-halved w-5 text-center"></i> Données & Confidentialité
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
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h1 class="text-xl font-bold text-gray-900">Informations personnelles</h1>
                        </div>

                        <div class="px-6 pt-6">
                            @if ($errors->any())
                                <div class="bg-red-50 text-red-600 p-3 rounded text-sm mb-4">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(session('success'))
                                <div class="bg-green-50 text-green-700 p-3 rounded text-sm mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>

                        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data" class="px-6 pb-6">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center gap-5 mb-8 mt-2">
                                <div class="shrink-0">
                                    @if(!empty($personne->avatar))
                                        <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-16 h-16 rounded-full shadow-sm border-2 border-white object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-16 h-16 rounded-full shadow-sm border-2 border-white">
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-3">
                                        <label for="avatar_upload" class="cursor-pointer bg-gray-100 border border-gray-300 hover:bg-gray-200 text-gray-700 text-xs font-semibold py-1.5 px-3 rounded shadow-sm transition-colors">
                                            Choisir un fichier
                                        </label>
                                        <span class="text-xs text-gray-500">Aucun fichier n'a été sélectionné</span>
                                        <input type="file" id="avatar_upload" name="avatar" class="hidden">
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                                <div>
                                    <label for="prenom" class="block text-sm text-gray-500 mb-1">Prénom</label>
                                    <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $personne->prenom) }}" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 focus:border-purple-500 focus:ring-0 sm:text-sm">
                                </div>
                                <div>
                                    <label for="nom" class="block text-sm text-gray-500 mb-1">Nom</label>
                                    <input type="text" name="nom" id="nom" value="{{ old('nom', $personne->nom) }}" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 focus:border-purple-500 focus:ring-0 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="mail" class="block text-sm text-gray-500 mb-1">Email</label>
                                    <input type="email" name="mail" id="mail" value="{{ old('mail', $personne->mail) }}" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 focus:border-purple-500 focus:ring-0 sm:text-sm">
                                </div>
                                <div>
                                    <label for="tel" class="block text-sm text-gray-500 mb-1">Téléphone</label>
                                    <input type="text" name="tel" id="tel" value="{{ old('tel', $personne->tel) }}" class="form-input block w-full px-3 py-2 rounded border border-gray-300 focus:border-purple-500 focus:ring-0 sm:text-sm">
                                </div>
                            </div>

                            @php
                                $client = \App\Models\Client::where('idpersonne', $personne->idpersonne)->first();
                            @endphp
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div class="relative">
                                    <label for="ville" class="block text-sm text-gray-500 mb-1">Ville</label>
                                    <input type="text" name="ville" id="ville" 
                                           value="{{ old('ville', $client->ville ?? '') }}" 
                                           autocomplete="off"
                                           class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 focus:border-purple-500 focus:ring-0 sm:text-sm"
                                           placeholder="Caen">
                                    <ul id="ville-suggestions" class="suggestions-list hidden"></ul>
                                </div>
                                
                                <div>
                                    <label for="cp" class="block text-sm text-gray-500 mb-1">Code Postal</label>
                                    <input type="text" name="cp" id="cp" 
                                           value="{{ old('cp', $client->code_postal ?? '') }}" 
                                           readonly
                                           class="form-input block w-full px-3 py-2 border border-gray-300 rounded bg-gray-50 text-gray-500 sm:text-sm cursor-not-allowed">
                                </div>
                            </div>

                            <div class="mb-6">
                                <div class="flex items-center gap-6 mt-2">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="civilite" value="autre" class="form-radio h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500" {{ (old('civilite', $personne->civilite) == 'autre') ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Autre</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="civilite" value="monsieur" class="form-radio h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500" {{ (old('civilite', $personne->civilite) == 'monsieur') ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Monsieur</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="civilite" value="madame" class="form-radio h-4 w-4 text-purple-600 border-gray-300 focus:ring-purple-500" {{ (old('civilite', $personne->civilite) == 'madame') ? 'checked' : '' }}>
                                        <span class="ml-2 text-sm text-gray-600">Madame</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="note" class="block text-sm text-gray-500 mb-1">Note</label>
                                <textarea id="note" name="note" rows="2" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 placeholder-gray-400 focus:border-purple-500 focus:ring-0 sm:text-sm resize-none" placeholder="Desc">{{ old('note', $personne->note) }}</textarea>
                            </div>

                            <div class="flex justify-end pt-2">
                                <button type="submit" class="bg-[#334155] text-white px-6 py-2 rounded text-sm font-bold hover:bg-[#1e293b] transition-colors shadow-sm">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        </div>
    </div>

<script>
    document.getElementById('avatar_upload').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var textSpan = this.previousElementSibling; 
        textSpan.textContent = fileName;
    });

    document.addEventListener('DOMContentLoaded', function() {
        const villeInput = document.getElementById('ville');
        const suggestionsList = document.getElementById('ville-suggestions');
        const cpInput = document.getElementById('cp');

        if(villeInput) {
            villeInput.addEventListener('input', function() {
                const query = this.value;
                
                if (query.length < 3) {
                    suggestionsList.classList.add('hidden');
                    suggestionsList.innerHTML = '';
                    return;
                }

               
                fetch(`https://geo.api.gouv.fr/communes?nom=${query}&fields=nom,codesPostaux&format=json&geometry=centre&boost=population&limit=5`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsList.innerHTML = '';
                        
                        if (data.length > 0) {
                            suggestionsList.classList.remove('hidden');
                            
                            data.forEach(item => {
                                const li = document.createElement('li');
                                
                                const cp = item.codesPostaux ? item.codesPostaux[0] : '';
                                
                                li.textContent = `${item.nom} (${cp})`;
                                
                                li.addEventListener('click', function() {
                                    villeInput.value = item.nom;
                                    cpInput.value = cp;
                                    
                                    suggestionsList.classList.add('hidden');
                                    suggestionsList.innerHTML = '';
                                });
                                
                                suggestionsList.appendChild(li);
                            });
                        } else {
                            suggestionsList.classList.add('hidden');
                        }
                    })
                    .catch(error => console.error('Erreur API:', error));
            });

            document.addEventListener('click', function(e) {
                if (!villeInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                    suggestionsList.classList.add('hidden');
                }
            });
        }
    });
</script>

</body>
</html>