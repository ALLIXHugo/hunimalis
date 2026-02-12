<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription Professionnel - Hunimalis</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#f5f3ff', 100: '#ede9fe', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9', 900: '#4c1d95' }
                    }
                }
            }
        }
    </script>
    <style>
        .suggestions-list {
            list-style: none; padding: 0; margin: 0;
            border: 1px solid #e5e7eb; max-height: 150px; overflow-y: auto;
            background-color: white; position: absolute; z-index: 50;
            width: 100%; border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .suggestions-list li {
            padding: 0.75rem 1rem; cursor: pointer; font-size: 0.875rem; color: #4b5563;
        }
        .suggestions-list li:hover { background-color: #f5f3ff; color: #6d28d9; }
        
        input:focus, select:focus {
            outline: none; border-color: #7c3aed; ring: 2px; --tw-ring-color: #7c3aed;
        }
    </style>
</head>
<body class="h-full">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:block lg:w-[60%] relative bg-gray-900">
            <img class="absolute inset-0 h-full w-full object-cover" src="{{ asset('img/home_hero.webp') }}" alt="Chien Corgi Automne">
        </div>

        <div class="flex-1 flex flex-col py-12 px-4 sm:px-6 lg:flex-none lg:px-8 xl:px-12 lg:w-[40%] bg-white overflow-y-auto h-screen">
            
            <div class="mx-auto w-full max-w-2xl">
                
                <div class="mb-10 text-center lg:text-left">
                    <img class="h-20 w-auto mx-auto lg:mx-0" src="{{ asset('img/logo.webp') }}" alt="Hunimalis Logo">
                </div>

                <h2 class="mt-6 text-3xl font-extrabold text-gray-900 tracking-tight">
                    Créer un compte professionnel
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Ou <a href="{{ route('login') }}" class="font-medium text-brand-600 hover:text-brand-500 transition-colors">connectez-vous à votre espace</a>
                </p>

                <div class="mt-6">
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4 rounded-r-md shadow-sm">
                            <div class="flex"><div class="ml-3"><p class="text-sm font-medium text-green-700">{{ session('success') }}</p></div></div>
                        </div>
                    @endif
                    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-md shadow-sm">
        <div class="flex">
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Une erreur est survenue</h3>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    </div>
@endif
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4 rounded-r-md shadow-sm">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Erreurs de validation</h3>
                                    <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-8">
                    <form action="{{ route('professionnel.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-5">
                            <div class="flex items-center space-x-2 border-b border-gray-100 pb-3 mb-2">
                                <i class="fas fa-building text-gray-400"></i>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">L'Établissement</h3>
                            </div>
                            
                            <div>
                                <label for="nomEtablissement" class="block text-sm font-medium text-gray-700 mb-1">Nom de l'établissement <span class="text-red-500">*</span></label>
                                <input id="nomEtablissement" name="nomEtablissement" type="text" value="{{ old('nomEtablissement') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="typeEtablissement" class="block text-sm font-medium text-gray-700 mb-1">Activité <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select id="typeEtablissement" name="typeEtablissement" required class="appearance-none block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all bg-white">
                                            <option value="">Sélectionner...</option>
                                            <option value="vétérinaire" {{ old('typeEtablissement') == 'vétérinaire' ? 'selected' : '' }}>Vétérinaire</option>
                                            <option value="toiletteur" {{ old('typeEtablissement') == 'toiletteur' ? 'selected' : '' }}>Toiletteur</option>
                                            <option value="pension" {{ old('typeEtablissement') == 'pension' ? 'selected' : '' }}>Pension</option>
                                            <option value="ostéopathe" {{ old('typeEtablissement') == 'ostéopathe' ? 'selected' : '' }}>Ostéopathe</option>
                                            <option value="éducateur" {{ old('typeEtablissement') == 'éducateur' ? 'selected' : '' }}>Éducateur</option>
                                            <option value="garderie" {{ old('typeEtablissement') == 'garderie' ? 'selected' : '' }}>Garderie</option>
                                            <option value="pet sitter" {{ old('typeEtablissement') == 'pet sitter' ? 'selected' : '' }}>Pet sitter</option>
                                            <option value="éleveur" {{ old('typeEtablissement') == 'éleveur' ? 'selected' : '' }}>Éleveur</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="siret" class="block text-sm font-medium text-gray-700 mb-1">Siret <span class="text-red-500">*</span></label>
                                    <input id="siret" name="siret" type="text" value="{{ old('siret') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all">
                                </div>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-700 mb-2">Assujetti à la TVA ? <span class="text-red-500">*</span></span>
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="tva" value="0.2" {{ old('tva') == '0.2' ? 'checked' : '' }} class="form-radio h-5 w-5 text-brand-600 border-gray-300 focus:ring-brand-500 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Oui (20%)</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="tva" value="0" {{ old('tva') == '0' ? 'checked' : '' }} class="form-radio h-5 w-5 text-brand-600 border-gray-300 focus:ring-brand-500 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Non (0%)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-5">
                            <div class="flex items-center space-x-2 border-b border-gray-100 pb-3 mb-2">
                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Localisation</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="md:col-span-1">
                                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select id="pays" name="pays" class="appearance-none block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all bg-white">
                                            <option value="France" {{ old('pays', 'France') == 'France' ? 'selected' : '' }}>France</option>
                                            <option value="Belgique" {{ old('pays') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                            <option value="Suisse" {{ old('pays') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="md:col-span-1 relative">
                                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                                    <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all" placeholder="Rechercher...">
                                    <ul id="ville-suggestions" class="suggestions-list hidden"></ul>
                                </div>
                                
                                <div class="md:col-span-1">
                                    <label for="cp" class="block text-sm font-medium text-gray-700 mb-1">Code Postal <span class="text-red-500">*</span></label>
                                    <input type="text" name="cp" id="cp" value="{{ old('cp') }}" required readonly class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-gray-50 text-gray-500 sm:text-sm cursor-not-allowed">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-5">
                            <div class="flex items-center space-x-2 border-b border-gray-100 pb-3 mb-2">
                                <i class="fas fa-user text-gray-400"></i>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide">Contact Référent</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom <span class="text-red-500">*</span></label>
                                    <input id="prenom" name="prenom" type="text" value="{{ old('prenom') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all">
                                </div>
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom <span class="text-red-500">*</span></label>
                                    <input id="nom" name="nom" type="text" value="{{ old('nom') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all">
                                </div>
                            </div>

                            <div>
                                <span class="block text-sm font-medium text-gray-700 mb-2">Civilité <span class="text-red-500">*</span></span>
                                <div class="flex items-center space-x-6">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="civilite" value="homme" {{ old('civilite') == 'homme' ? 'checked' : '' }} class="form-radio h-5 w-5 text-brand-600 border-gray-300 focus:ring-brand-500 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Monsieur</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" name="civilite" value="femme" {{ old('civilite') == 'femme' ? 'checked' : '' }} class="form-radio h-5 w-5 text-brand-600 border-gray-300 focus:ring-brand-500 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Madame</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="nationalite" class="block text-sm font-medium text-gray-700 mb-1">Nationalité <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select id="nationalite" name="nationalite" required class="appearance-none block w-full pl-4 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all bg-white">
                                            <option value="France" {{ old('nationalite', 'France') == 'France' ? 'selected' : '' }}>France</option>
                                            <option value="Belgique" {{ old('nationalite') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                            <option value="Suisse" {{ old('nationalite') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone mobile <span class="text-red-500">*</span></label>
                                    <input id="telephone" name="telephone" type="tel" pattern="[0-9]{10}" value="{{ old('telephone') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all" placeholder="0612345678">
                                </div>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email professionnel <span class="text-red-500">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all">
                            </div>
                        </div>

                        <div class="pt-6 pb-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-lg shadow-lg text-base font-bold text-white bg-gray-900 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all transform hover:-translate-y-0.5">
                                Commencer l'essai gratuit
                            </button>
                            <p class="mt-4 text-xs text-center text-gray-500">
                                En vous inscrivant, vous acceptez nos <a href="#" class="text-brand-600 hover:text-brand-800 font-medium underline">conditions générales</a>.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputVille = document.getElementById('ville');
            const inputCP = document.getElementById('cp');
            const suggestionsList = document.getElementById('ville-suggestions');
            let debounceTimeout;

            function fetchSuggestions(query) {
                const apiUrl = `https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}&type=municipality&limit=5`;
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => { displaySuggestions(data.features); })
                    .catch(error => { console.error("Erreur API:", error); });
            }

            function displaySuggestions(features) {
                suggestionsList.innerHTML = '';
                if (features.length === 0) {
                    suggestionsList.classList.add('hidden');
                    return;
                }
                features.forEach(feature => {
                    const postalCode = feature.properties.postcode;
                    const city = feature.properties.city;
                    const li = document.createElement('li');
                    li.textContent = `${city} (${postalCode})`;
                    li.addEventListener('click', function() {
                        inputVille.value = city;
                        inputCP.value = postalCode;
                        suggestionsList.classList.add('hidden');
                    });
                    suggestionsList.appendChild(li);
                });
                suggestionsList.classList.remove('hidden');
            }

            inputVille.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length < 3) {
                    suggestionsList.classList.add('hidden');
                    return;
                }
                clearTimeout(debounceTimeout);
                debounceTimeout = setTimeout(() => { fetchSuggestions(query); }, 300);
            });

            document.addEventListener('click', function(e) {
                if (e.target !== inputVille) {
                    suggestionsList.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>