<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes données personnelles | Hunimalis</title>
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
                                    <img src="{{ asset('storage/' . $personne->avatar) }}" class="w-20 h-20 rounded-full shadow-sm border-2 border-white object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-20 h-20 rounded-full shadow-sm border-2 border-white">
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 font-medium break-all">{{ $personne->mail }}</p>
                        </div>
                        <nav class="space-y-1">
                            <a href="{{ route('clients.dashboard') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-regular fa-user w-5 text-center"></i> Mon profil</div>
                            </a>
                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux</div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux ?? 0 }}</span>
                            </a>
                            <a href="{{ route('rdv.client.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-regular fa-calendar w-5 text-center"></i> Rendez-vous</div>
                            </a>
                            <a href="{{ route('client.paiements.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-regular fa-credit-card w-5 text-center"></i> Paiement</div>
                            </a>
                            <a href="{{ route('client.settings') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-gear"></i> Paramètres</div>
                            </a>
                            <a class="flex items-center justify-between px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
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

                <main class="lg:col-span-9 space-y-8">
                    
                    @if (session('success'))
                        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
                            <div class="flex"><div class="flex-shrink-0"><i class="fa-solid fa-check text-green-400"></i></div><div class="ml-3"><p class="text-sm text-green-700">{{ session('success') }}</p></div></div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                            <div class="flex"><div class="flex-shrink-0"><i class="fa-solid fa-circle-exclamation text-red-400"></i></div><div class="ml-3"><p class="text-sm text-red-700">{{ session('error') }}</p></div></div>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h1 class="text-xl font-bold text-gray-900">Mes données personnelles</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Conformément au RGPD, vous disposez d'un droit de regard sur vos données. 
                                Voici l'ensemble des informations stockées par Hunimalis vous concernant.
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donnée</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur enregistrée</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
    
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Identité
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Identification nécessaire à la gestion de votre dossier client et à l'accueil en établissement.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            {{ $personne->prenom }} {{ $personne->nom }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">Obligatoire</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Civilité
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Utilisé pour personnaliser les correspondances (emails, factures) et l'accueil.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                            {{ $personne->civilite ?? 'Non précisé' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-800">Facultatif</span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Email
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Identifiant de connexion sécurisé et canal d'envoi pour vos confirmations de RDV et factures.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $personne->mail }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-800">Obligatoire</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Téléphone
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Permet au professionnel de vous contacter rapidement en cas d'imprévu sur un RDV ou pour la disponibilité d'une commande.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $personne->tel ?? 'Non renseigné' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($personne->tel)
                                                <form action="{{ route('client.data.delete', 'tel') }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:underline text-xs font-medium">Supprimer</button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @php
                                        $clientLoc = \App\Models\Client::where('idpersonne', $personne->idpersonne)->first();
                                        $hasLoc = $clientLoc && ($clientLoc->ville || $clientLoc->code_postal);
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Localisation
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Obligatoire pour l'établissement de factures conformes. Utilisée à des fins statistiques anonymes pour analyser la répartition géographique de la clientèle.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($hasLoc)
                                                {{ $clientLoc->code_postal }} {{ $clientLoc->ville }}
                                            @else
                                                Non renseignée
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($hasLoc)
                                                <form action="{{ route('client.data.delete', 'localisation') }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:underline text-xs font-medium">Supprimer</button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Photo de profil
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Permet de personnaliser votre compte et d'aider le professionnel à vous reconnaître lors de votre venue.
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($personne->avatar) 
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                                                    <span class="text-green-600 text-xs">Enregistrée</span>
                                                </div>
                                            @else 
                                                <span class="text-gray-400">Aucune</span> 
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($personne->avatar)
                                                <form action="{{ route('client.data.delete', 'avatar') }}" method="POST" class="inline">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:underline text-xs font-medium">Supprimer</button>
                                                </form>
                                            @else
                                                <span class="text-gray-300 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>

                                    @if($personne->note)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                            <div class="flex items-center">
                                                Note personnelle
                                                <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                            </div>
                                            <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                Informations complémentaires que vous avez souhaité ajouter à votre profil (ex: disponibilités, préférences).
                                                <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 truncate max-w-xs">
                                            {{ Str::limit($personne->note, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            <form action="{{ route('client.data.delete', 'note') }}" method="POST" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-red-600 hover:text-red-900 hover:underline text-xs font-medium">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endif

                                    @if(isset($animaux) && count($animaux) > 0)
                                        @foreach($animaux as $index => $animal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 group relative cursor-help">
                                                <div class="flex items-center">
                                                    Animal #{{ $index + 1 }}
                                                    <i class="fa-regular fa-circle-question text-gray-400 ml-2 hover:text-blue-500 transition"></i>
                                                </div>
                                                <div class="hidden group-hover:block absolute z-50 left-0 bottom-full mb-2 w-80 bg-gray-800 text-white text-xs rounded p-3 shadow-xl whitespace-normal leading-relaxed">
                                                    Les caractéristiques (Race, Poids, etc.) sont nécessaires pour que le professionnel puisse adapter la prestation.
                                                    <div class="absolute top-100 left-6 -mt-1 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                                        <i class="fa-solid fa-paw"></i>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900 capitalize">{{ $animal->nom1animal ?? $animal->nom }}</div>
                                                        <div class="text-xs text-gray-500 capitalize">
                                                            {{ $animal->espece->libelleespece ?? 'Espèce inconnue' }} 
                                                            &bull; 
                                                            {{ $animal->race->libellerace ?? 'Race non spécifiée' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                                <a href="{{ route('clients.animals') }}" class="text-[#00C497] hover:text-[#008f6d] hover:underline text-xs font-medium">
                                                    Gérer
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <p class="text-xs text-gray-500">
                                <i class="fa-solid fa-info-circle mr-1"></i> Les données obligatoires sont nécessaires à l'exécution du contrat (gestion de votre compte). Pour les supprimer, vous devez supprimer votre compte ci-dessous.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-red-200 shadow-sm overflow-hidden">
                        
                        <div class="px-6 py-5 border-b border-red-100 bg-red-50/50">
                            <h2 class="text-lg font-bold text-red-700 flex items-center gap-2">
                                <i class="fa-solid fa-triangle-exclamation"></i> Suppression définitive du compte
                            </h2>
                        </div>

                        <div class="p-6">
                            @if ($errors->any())
                                <div class="bg-red-50 text-red-600 p-3 rounded text-sm mb-6">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif

                            <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                                <strong>Attention :</strong> Cette action est irréversible. Elle supprimera votre accès, l'historique de vos animaux et anonymisera vos données personnelles. 
                                Cependant, conformément à la loi (Article L123-22 du Code de commerce), vos factures seront conservées pour une durée de 10 ans.
                            </p>

                            <form action="{{ route('client.profile.destroy') }}" method="POST" class="max-w-md mt-6">
                                @csrf
                                @method('DELETE')

                                <div class="mb-4 relative">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Confirmez votre mot de passe pour valider</label>
                                    <div class="relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               placeholder="Votre mot de passe" 
                                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-sm pr-10"
                                               required>
                                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600">
                                            <i class="fa-regular fa-eye-slash" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded text-sm transition-colors shadow-sm w-full sm:w-auto flex items-center justify-center gap-2"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ?');">
                                    <i class="fa-solid fa-trash-can"></i> Supprimer mon compte
                                </button>
                            </form>
                        </div>
                    </div>

                </main>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>