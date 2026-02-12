<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Animaux | Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        hunimalis: { DEFAULT: '#00C497', hover: '#00a07b' }, 
                        dog: '#F97316' 
                    } 
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .form-input:focus { border-color: #00C497; outline: none; ring: 1px; --tw-ring-color: #00C497; box-shadow: 0 0 0 1px #00C497; }
        option[disabled] { display: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="h-full antialiased text-gray-600 bg-white">

    @php
        $nbAnimaux = $animals->count();
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
                    <div class="shrink-0 flex justify-center mb-3">
                        @if(!empty($personne->avatar))
                            <img src="{{ asset('storage/' . $personne->avatar) }}" class="w-10 h-10 rounded-full shadow-sm border-2 border-white object-cover">
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
                            <p class="text-sm text-gray-500 font-medium break-all">
                                {{ $personne->mail ?? 'email@exemple.com' }}
                            </p>
                        </div>

                        <nav class="space-y-1">
                            <a href="{{ route('clients.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-regular fa-user w-5 text-center text-gray-600"></i> Mon profil
                            </a>

                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux
                                </div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux }}</span>
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
                                    <i class="fa-solid fa-gear"></i> Paramètres
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
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden min-h-[500px]">
                        <div class="px-6 py-6">
                            <h1 class="text-xl font-bold text-gray-900 mb-6">Mes animaux</h1>
                            
                            <button onclick="openNewAnimalForm()" class="w-full bg-hunimalis hover:bg-hunimalis-hover text-white py-3 rounded-lg font-bold flex items-center justify-center gap-2 transition-colors shadow-sm mb-8">
                                <i class="fas fa-plus"></i>
                                <span>Nouvel animal</span>
                            </button>

                            @if(session('success'))
                                <div class="bg-green-50 text-green-700 p-3 rounded text-sm mb-6 border border-green-100 flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="bg-red-50 text-red-600 p-3 rounded text-sm mb-6 border border-red-100">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div id="animal-list" class="space-y-4">
                                @foreach($animals as $animal)
                                <div onclick="openAnimalDetails(this)" data-json="{{ $animal->toJson() }}" class="cursor-pointer border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                                    <div class="flex items-center gap-6">
                                        <div class="w-16 h-16 bg-[#F3F4F6] rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            @if($animal->photo)
                                                <img src="{{ asset('storage/'.$animal->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fas fa-dog text-3xl text-orange-500"></i> 
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg text-gray-900">{{ $animal->nom1animal }}</h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $animal->espece->libelleespece ?? 'Animal' }} - 
                                                {{ $animal->race ? ($animal->race->nom1race ?? $animal->race->libellerace) : 'Race inconnue' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <button 
                                            type="button"
                                            onclick="event.stopPropagation(); if(confirm('Voulez-vous vraiment supprimer cet animal ?')) { document.getElementById('delete-form-{{ $animal->numtatouage }}').submit(); }" 
                                            class="text-gray-400 hover:text-red-600 transition-colors p-2 z-10"
                                            title="Supprimer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                        <i class="fas fa-chevron-right text-gray-300"></i>
                                    </div>

                                    <form id="delete-form-{{ $animal->numtatouage }}" action="{{ route('animal.destroy', $animal->numtatouage) }}" method="POST" class="hidden">
                                        @csrf 
                                        @method('DELETE')
                                    </form>
                                </div>
                                @endforeach
                            </div>

                            @if(count($animals) === 0)
                            <div id="empty-state" class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mx-auto mb-4">
                                     <i class="fas fa-paw text-4xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-1">Aucun animal</h3>
                                <p class="text-gray-500 text-sm mb-6">Ajoutez votre premier compagnon.</p>
                            </div>
                            @endif

                            <div id="animal-form" class="hidden mt-6 bg-white">
                                <div class="border border-gray-200 rounded-lg p-0 relative overflow-hidden">
                                    <div class="p-6">
                                        
                                        <div class="flex border-b border-gray-200 mb-8 overflow-x-auto scrollbar-hide gap-8">
                                            <button type="button" onclick="switchTab('infos')" id="btn-tab-infos" class="flex items-center gap-2 py-3 border-b-2 border-[#1e293b] text-[#1e293b] font-bold text-sm whitespace-nowrap transition-colors">
                                                <i class="fa-regular fa-id-card"></i> Informations
                                            </button>
                                            <button type="button" onclick="switchTab('poids')" id="btn-tab-poids" class="flex items-center gap-2 py-3 border-b-2 border-transparent text-gray-500 hover:text-gray-800 font-medium text-sm whitespace-nowrap transition-colors">
                                                <i class="fa-solid fa-weight-scale"></i> Poids
                                            </button>
                                            <button type="button" class="flex items-center gap-2 py-3 border-b-2 border-transparent text-gray-500 opacity-50 cursor-not-allowed text-sm">
                                                <i class="fa-solid fa-kit-medical"></i> Santé <span class="bg-[#0ea5e9] text-white text-[10px] font-bold px-1.5 py-0.5 rounded ml-1">À venir</span>
                                            </button>
                                        </div>

                                        <div id="tab-content-infos">
                                            <form id="main-animal-form" action="{{ route('animal.store.client') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @if(request()->has('redirect'))
                                                    <input type="hidden" name="redirect_url" value="{{ request('redirect') }}">
                                                @endif

                                                <input type="hidden" name="_method" id="form-method" value="POST">
                                                <input type="hidden" name="_method" id="form-method" value="POST">
                                                <input type="hidden" name="idpersonne" value="{{ $personne->idpersonne }}">

                                                <div class="flex flex-col items-center justify-center mb-8">
                                                    <div class="w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mb-4 overflow-hidden relative">
                                                         <i class="fas fa-dog text-orange-500 text-5xl" id="preview-icon"></i>
                                                         <img id="preview-image" src="" class="w-full h-full object-cover hidden">
                                                    </div>
                                                    <div class="flex items-center border border-gray-300 rounded overflow-hidden">
                                                        <label for="photo" class="cursor-pointer bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium py-2 px-3 border-r border-gray-300 transition-colors">
                                                            Choisir un fichier
                                                        </label>
                                                        <span class="text-xs text-gray-500 px-3 bg-white">Aucun fichier</span>
                                                        <input type="file" name="photo" id="photo" class="hidden" onchange="previewFile()">
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Nom <span class="text-red-500">*</span></label>
                                                        <input type="text" id="nom1animal" name="nom1animal" required class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Espèce <span class="text-red-500">*</span></label>
                                                        <select name="idespece" id="select-espece" required class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                            <option value="" disabled selected>Choisir</option>
                                                            @foreach($especes as $espece)
                                                                <option value="{{ $espece->idespece }}">{{ $espece->libelleespece ?? $espece->nom1espece }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Race</label>
                                                        <select name="idrace" id="select-race" required disabled class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm disabled:bg-gray-50">
                                                            <option value="">D'abord l'espèce</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Croisement</label>
                                                        <select name="rac_idrace" id="select-croisement" disabled class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm disabled:bg-gray-50">
                                                            <option value="">Aucun</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Numéro de puce <span class="text-red-500">*</span></label>
                                                        <input type="text" id="numtatouage" name="numtatouage" required class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Sexe</label>
                                                        <select name="sexe" id="sexe" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                            <option value="true">Mâle</option>
                                                            <option value="false">Femelle</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Date de naissance</label>
                                                        <input type="date" name="datenaissance" id="datenaissance" required class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Stérilisé / Castré ?</label>
                                                        <select name="castre" id="castre" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                            <option value="Indéterminé">Je ne sais pas</option>
                                                            <option value="Oui">Oui</option>
                                                            <option value="Non">Non</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Corpulence</label>
                                                        <select name="idcorpulence" id="idcorpulence" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                            <option value="">Non renseigné</option>
                                                            @foreach($corpulences as $corpulence)
                                                                <option value="{{ $corpulence->idcorpulence }}">{{ $corpulence->libellecorpulence }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-gray-500 mb-1">Taille du pelage</label>
                                                        <select name="idtaillepelage" id="idtaillepelage" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                            <option value="">Non renseigné</option>
                                                            @foreach($tailles as $taille)
                                                                <option value="{{ $taille->idtaillepelage }}">{{ $taille->libelletaillepelage }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mt-8 pt-6 border-t border-gray-200">
                                                    <h3 class="text-sm font-bold text-gray-900 mb-5">Mes professionnels</h3>
                                                    
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                                                        
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1">Toiletteur habituel</label>
                                                            <select name="idpro_toiletteur" id="input-toiletteur" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                                <option value="">Aucun</option>
                                                                @foreach($pros as $pro)
                                                                    @if(stripos($pro->libelletypeetablissement, 'toilett') !== false)
                                                                        <option value="{{ $pro->idpro }}">{{ $pro->libelleetablissement }} ({{ $pro->ville }})</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1">Educateur habituel</label>
                                                            <select name="idpro_educateur" id="input-educateur" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                                <option value="">Aucun</option>
                                                                @foreach($pros as $pro)
                                                                    @if(stripos($pro->libelletypeetablissement, 'educ') !== false || stripos($pro->libelletypeetablissement, 'éduc') !== false || stripos($pro->libelletypeetablissement, 'dress') !== false)
                                                                        <option value="{{ $pro->idpro }}">{{ $pro->libelleetablissement }} ({{ $pro->ville }})</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1">Vétérinaire habituel</label>
                                                            <select name="idpro_veterinaire" id="input-veterinaire" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                                <option value="">Aucun</option>
                                                                @foreach($pros as $pro)
                                                                    @if(stripos($pro->libelletypeetablissement, 'vétérinaire') !== false || stripos($pro->libelletypeetablissement, 'clinique') !== false)
                                                                        <option value="{{ $pro->idpro }}">{{ $pro->libelleetablissement }} ({{ $pro->ville }})</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-500 mb-1">Pension habituelle</label>
                                                            <select name="idpro_pension" id="input-pension" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                                <option value="">Aucun</option>
                                                                @foreach($pros as $pro)
                                                                    @if(stripos($pro->libelletypeetablissement, 'pension') !== false || stripos($pro->libelletypeetablissement, 'gard') !== false)
                                                                        <option value="{{ $pro->idpro }}">{{ $pro->libelleetablissement }} ({{ $pro->ville }})</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="mt-8 flex justify-end gap-2">
                                                    <button type="button" onclick="closeAnimalForm()" class="text-gray-500 hover:text-gray-700 text-sm font-bold px-4 py-2">
                                                        Annuler
                                                    </button>
                                                    <button type="submit" id="submit-btn" class="bg-[#334155] hover:bg-[#1e293b] text-white px-6 py-2.5 rounded text-sm font-bold transition-colors shadow-sm">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="tab-content-poids" class="hidden">
                                            
                                            <div id="weight-error-box" class="hidden mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative text-sm flex items-center gap-2">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span id="weight-error-text">Une erreur est survenue</span>
                                            </div>

                                            <div class="bg-gray-50 rounded-xl p-8 text-center mb-8 border border-gray-100">
                                                <p class="text-gray-500 text-sm font-medium mb-1">Poids actuel</p>
                                                <div class="flex items-baseline justify-center gap-1">
                                                    <span id="display-current-weight" class="text-5xl font-bold text-[#1e293b]">-</span>
                                                    <span class="text-xl text-gray-600 font-medium">kg</span>
                                                </div>
                                                <p id="display-current-date" class="text-gray-400 text-xs mt-2 italic">Aucune mesure</p>
                                            </div>

                                            <form id="form-add-weight" action="/mesures" method="POST" class="flex gap-4 items-end mb-8">
                                                @csrf
                                                <input type="hidden" name="numtatouage" id="weight-numtatouage">
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                                                    <input type="date" name="date_mesure" value="{{ date('Y-m-d') }}" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm">
                                                </div>
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-500 mb-1">Poids (kg)</label>
                                                    <div class="relative">
                                                        <input type="number" step="0.01" name="poids" placeholder="0.00" class="form-input block w-full px-3 py-2 border border-gray-300 rounded text-gray-900 text-sm pl-8">
                                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                            <i class="fa-solid fa-weight-hanging text-gray-400 text-xs"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="bg-[#00C497] hover:bg-[#00a07b] text-white p-2.5 rounded shadow-sm transition-colors h-[38px] w-[38px] flex items-center justify-center">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>

                                            <div class="relative h-64 w-full">
                                                <canvas id="weightChart"></canvas>
                                            </div>
                                            
                                            <div class="mt-8 flex justify-end">
                                                 <button type="button" onclick="closeAnimalForm()" class="text-gray-500 hover:text-gray-700 text-sm font-bold px-4 py-2">
                                                    Fermer
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        const allRacesData = @json($races);
        let weightChartInstance = null;

        const especeSelect = document.getElementById('select-espece');
        const raceSelect = document.getElementById('select-race');
        const croisementSelect = document.getElementById('select-croisement');
        const dateInput = document.getElementById('datenaissance');

        document.addEventListener('DOMContentLoaded', function() {
            if(especeSelect) especeSelect.addEventListener('change', () => updateLists(true)); 
            if(raceSelect) raceSelect.addEventListener('change', updateCroisementVisibility);
            
            const formPoids = document.getElementById('form-add-weight');
            const errorBox = document.getElementById('weight-error-box');
            const errorText = document.getElementById('weight-error-text');

            if(formPoids) {
                formPoids.addEventListener('submit', function(e) {
                    e.preventDefault(); 
                    
                    errorBox.classList.add('hidden');
                    
                    const formData = new FormData(this);
                    const btnSubmit = this.querySelector('button[type="submit"]');
                    const originalContent = btnSubmit.innerHTML;

                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                    fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if(data.mesures.length > 0) {
                                const last = data.mesures[data.mesures.length - 1];
                                document.getElementById('display-current-weight').innerText = last.poids;
                                const d = new Date(last.date_mesure);
                                document.getElementById('display-current-date').innerText = d.toLocaleDateString('fr-FR');
                                updateChart(data.mesures);
                            }
                            this.querySelector('input[name="poids"]').value = '';
                            
                            btnSubmit.className = "bg-green-500 text-white p-2.5 rounded shadow-sm h-[38px] w-[38px] flex items-center justify-center transition-all";
                            btnSubmit.innerHTML = '<i class="fas fa-check"></i>';
                            
                            setTimeout(() => {
                                btnSubmit.className = "bg-hunimalis hover:bg-hunimalis-hover text-white p-2.5 rounded shadow-sm transition-colors h-[38px] w-[38px] flex items-center justify-center";
                                btnSubmit.innerHTML = originalContent;
                                btnSubmit.disabled = false;
                            }, 1500);

                        } else {
                            errorText.innerText = data.message; 
                            errorBox.classList.remove('hidden'); 
                            
                            btnSubmit.disabled = false;
                            btnSubmit.innerHTML = originalContent;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        errorText.innerText = "Une erreur technique est survenue.";
                        errorBox.classList.remove('hidden'); 
                        
                        btnSubmit.disabled = false;
                        btnSubmit.innerHTML = originalContent;
                    });
                });
            }

            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('open')) {
                openNewAnimalForm();
                
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        });


        function openNewAnimalForm() {
            toggleForm(true);
            switchTab('infos');
            resetForm();
        }

        function openAnimalDetails(element) {
            const animalData = JSON.parse(element.getAttribute('data-json'));
            toggleForm(true);
            switchTab('infos');
            populateForm(animalData);
        }

        function populateForm(animal) {
            document.getElementById('submit-btn').innerText = "Enregistrer";
            const form = document.getElementById('main-animal-form');
            form.action = "/animaux/" + animal.numtatouage; 
            document.getElementById('form-method').value = "PUT";

            document.getElementById('nom1animal').value = animal.nom1animal;
            const tatooInput = document.getElementById('numtatouage');
            tatooInput.value = animal.numtatouage;
            tatooInput.setAttribute('readonly', true);
            tatooInput.classList.add('bg-gray-100');

            if(document.getElementById('sexe')) document.getElementById('sexe').value = animal.sexe ? 'true' : 'false';
            if(document.getElementById('idcorpulence')) document.getElementById('idcorpulence').value = animal.idcorpulence || '';
            if(document.getElementById('idtaillepelage')) document.getElementById('idtaillepelage').value = animal.idtaillepelage || '';

            if(animal.datenaissance) {
                const datePart = animal.datenaissance.includes('T') ? animal.datenaissance.split('T')[0] : animal.datenaissance.split(' ')[0];
                document.getElementById('datenaissance').value = datePart;
            }

            if(animal.idespece) {
                especeSelect.value = animal.idespece;
                updateLists(false);
                setTimeout(() => {
                    if(animal.idrace) { raceSelect.value = animal.idrace; updateCroisementVisibility(); }
                    if(animal.rac_idrace) croisementSelect.value = animal.rac_idrace;
                }, 50);
            }

            if(document.getElementById('castre')) {
                let castreVal = "Indéterminé";
                if (animal.caracteristique && animal.caracteristique.sterilise !== null) {
                    castreVal = animal.caracteristique.sterilise ? "Oui" : "Non";
                }
                document.getElementById('castre').value = castreVal;
            }

            if(document.getElementById('input-toiletteur')) document.getElementById('input-toiletteur').value = animal.idpro_toiletteur || '';
            if(document.getElementById('input-educateur')) document.getElementById('input-educateur').value = animal.idpro_educateur || '';
            if(document.getElementById('input-veterinaire')) document.getElementById('input-veterinaire').value = animal.idpro_veterinaire || '';
            if(document.getElementById('input-pension')) document.getElementById('input-pension').value = animal.idpro_pension || '';

            const imgPreview = document.getElementById('preview-image');
            const iconPreview = document.getElementById('preview-icon');
            if(animal.photo) {
                imgPreview.src = "/storage/" + animal.photo; 
                imgPreview.classList.remove('hidden');
                iconPreview.classList.add('hidden');
            } else {
                imgPreview.classList.add('hidden');
                iconPreview.classList.remove('hidden');
            }

            document.getElementById('weight-numtatouage').value = animal.numtatouage;
            const mesures = animal.prise_mesures || []; 
            mesures.sort((a, b) => new Date(a.date_mesure) - new Date(b.date_mesure));

            if (mesures.length > 0) {
                const last = mesures[mesures.length - 1];
                document.getElementById('display-current-weight').innerText = last.poids;
                const d = new Date(last.date_mesure);
                document.getElementById('display-current-date').innerText = d.toLocaleDateString('fr-FR');
            } else {
                document.getElementById('display-current-weight').innerText = "-";
                document.getElementById('display-current-date').innerText = "Aucune mesure";
            }
            updateChart(mesures);
        }

        function updateChart(data) {
            const ctx = document.getElementById('weightChart').getContext('2d');
            const labels = data.map(m => new Date(m.date_mesure).toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit'}));
            const values = data.map(m => m.poids);

            if (weightChartInstance) weightChartInstance.destroy();

            weightChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Poids (kg)',
                        data: values,
                        borderColor: '#F43F5E',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#F43F5E',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: false, grid: { borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        function resetForm() {
            const form = document.getElementById('main-animal-form');
            form.reset(); 
            form.action = "{{ route('animal.store.client') }}"; 
            document.getElementById('form-method').value = "POST";
            document.getElementById('submit-btn').innerText = "Enregistrer";
            
            const tatooInput = document.getElementById('numtatouage');
            tatooInput.removeAttribute('readonly');
            tatooInput.classList.remove('bg-gray-100');
            tatooInput.value = "";

            document.getElementById('preview-image').classList.add('hidden');
            document.getElementById('preview-icon').classList.remove('hidden');

            raceSelect.innerHTML = '<option value="">D\'abord l\'espèce</option>';
            raceSelect.disabled = true;
            croisementSelect.innerHTML = '<option value="">Aucun</option>';
            croisementSelect.disabled = true;
        }

        function toggleForm(show) {
            const list = document.getElementById('animal-list');
            const empty = document.getElementById('empty-state');
            const form = document.getElementById('animal-form');
            
            if (show) {
                if(list) list.classList.add('hidden');
                if(empty) empty.classList.add('hidden');
                form.classList.remove('hidden');
                form.scrollIntoView({ behavior: 'smooth' });
            } else {
                if(list) list.classList.remove('hidden');
                if(!list && empty) empty.classList.remove('hidden'); 
                form.classList.add('hidden');
            }
        }

        function closeAnimalForm() { toggleForm(false); }

        function switchTab(tabName) {
            document.getElementById('tab-content-infos').classList.add('hidden');
            document.getElementById('tab-content-poids').classList.add('hidden');
            document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');

            const btnInfos = document.getElementById('btn-tab-infos');
            const btnPoids = document.getElementById('btn-tab-poids');
            const activeClasses = ['border-[#1e293b]', 'text-[#1e293b]', 'font-bold', 'border-b-2'];
            const inactiveClasses = ['border-transparent', 'text-gray-500', 'font-medium', 'border-b-2'];

            if(tabName === 'infos') {
                btnInfos.classList.add(...activeClasses); btnInfos.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
                btnPoids.classList.remove(...activeClasses); btnPoids.classList.add(...inactiveClasses);
            } else {
                btnPoids.classList.add(...activeClasses); btnPoids.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
                btnInfos.classList.remove(...activeClasses); btnInfos.classList.add(...inactiveClasses);
            }
        }

        function updateLists(resetChildren = true) {
            const selectedEspeceId = especeSelect.value;
            const currentRaceVal = raceSelect.value;
            const currentCroisVal = croisementSelect.value;

            raceSelect.innerHTML = '<option value="">-- Choisir race --</option>';
            croisementSelect.innerHTML = '<option value="">-- Aucun (Non croisé) --</option>';
            
            if(!selectedEspeceId) { raceSelect.disabled = true; croisementSelect.disabled = true; return; }

            raceSelect.disabled = false; croisementSelect.disabled = false;
            const filtered = allRacesData.filter(r => r.idespece == selectedEspeceId);
            
            if(filtered.length === 0) {
                raceSelect.add(new Option("Aucune race trouvée", ""));
            } else {
                filtered.forEach(race => {
                    const label = race.libellerace || race.nom1race || 'Race';
                    raceSelect.add(new Option(label, race.idrace));
                    croisementSelect.add(new Option(label, race.idrace));
                });
            }
            if(!resetChildren && currentRaceVal) {
                 raceSelect.value = currentRaceVal; updateCroisementVisibility(); 
                 if(currentCroisVal) croisementSelect.value = currentCroisVal;
            } else { updateCroisementVisibility(); }
        }

        function updateCroisementVisibility() {
            const selectedRaceId = raceSelect.value;
            Array.from(croisementSelect.options).forEach(function(option) {
                if (option.value == selectedRaceId && selectedRaceId !== "") {
                    option.disabled = true; option.style.display = 'none';
                    if (croisementSelect.value == selectedRaceId) croisementSelect.value = "";
                } else { option.disabled = false; option.style.display = 'block'; }
            });
        }

        function previewFile() {
            const preview = document.getElementById('preview-image');
            const icon = document.getElementById('preview-icon');
            const file = document.getElementById('photo').files[0];
            const reader = new FileReader();
            reader.addEventListener("load", function () {
                preview.src = reader.result; preview.classList.remove('hidden'); icon.classList.add('hidden');
            }, false);
            if (file) reader.readAsDataURL(file);
        }
    </script>
</body>
</html>