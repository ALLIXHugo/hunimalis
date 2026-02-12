<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche : {{ $animal->nom1animal }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 p-6 pb-24">

    <form action="{{ route('animal.update', $animal->numtatouage) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-1/4">
                <div class="bg-white rounded-lg shadow p-6 text-center sticky top-6">
                    <div class="w-32 h-32 mx-auto bg-orange-200 rounded-full flex items-center justify-center mb-4 text-orange-600 text-4xl">
                        <i class="fas fa-paw"></i>
                    </div>
                   
                    <h2 class="text-2xl font-bold text-gray-800">{{ $animal->nom1animal }}</h2>
                    <p class="text-gray-500 text-sm">#{{ $animal->numtatouage }}</p>
                   
                    <p class="text-gray-500 text-sm mb-4">
                        @php $age = \Carbon\Carbon::parse($animal->datenaissance)->age; @endphp
                        {{ $age }} ans
                    </p>

                    <div class="border-t pt-4">
                        <h3 class="text-left font-bold text-gray-700 mb-2">Propriétaire</h3>
                        
                        <input type="hidden" name="idpersonne" value="{{ $animal->idpersonne }}">
                        @if($animal->personne)
                            <p class="text-left text-blue-600 font-semibold">{{ $animal->personne->nom }} {{ $animal->personne->prenom }}</p>
                        @else
                            <p class="text-red-500">Non assigné</p>
                        @endif
                    </div>

                    <div class="mt-6 flex flex-col gap-2">
                        <button type="submit" class="bg-green-600 text-white py-3 rounded hover:bg-green-700 font-bold shadow-lg transition transform hover:scale-105">
                            <i class="fas fa-save"></i> Enregistrer tout
                        </button>
                        <a href="{{ route('animal.index') }}" class="bg-gray-300 text-gray-700 py-2 rounded hover:bg-gray-400 text-center">Retour liste</a>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-3/4">
               
                <div class="bg-white rounded-t-lg shadow-sm border-b flex overflow-x-auto">
                    <button type="button" onclick="openTab(event, 'tab-infos')"
                        class="tab-link px-6 py-4 font-bold border-b-2 text-blue-600 border-blue-600 bg-blue-50 transition-colors duration-200">
                        <i class="fas fa-info-circle mr-2"></i> Informations
                    </button>

                    <button type="button" onclick="openTab(event, 'tab-sante')"
                        class="tab-link px-6 py-4 font-bold border-b-2 text-gray-600 border-transparent hover:bg-gray-50 transition-colors duration-200">
                        <i class="fa-solid fa-briefcase-medical mr-2"></i> Santé
                    </button>

                    <button type="button" onclick="openTab(event, 'tab-historique')"
                        class="tab-link px-6 py-4 font-bold border-b-2 text-gray-600 border-transparent hover:bg-gray-50 transition-colors duration-200">
                        <i class="fa-solid fa-clock-rotate-left mr-2"></i> Historique
                    </button>
                   
                    <button type="button" class="px-6 py-4 text-gray-400 border-b-2 border-transparent cursor-not-allowed">
                        <i class="fa-solid fa-note-sticky"></i> Note
                    </button>
                    <button type="button" class="px-6 py-4 text-gray-400 border-b-2 border-transparent cursor-not-allowed">
                        <i class="fa-solid fa-file"></i> Fichier
                    </button>
                </div>

                <div class="bg-white p-6 rounded-b-lg shadow min-h-[600px]">
                   
                    <div id="tab-infos" class="tab-content">
                       
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Identité</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom d'origine</label>
                                    <input type="text" name="nom1animal" value="{{ $animal->nom1animal }}" class="w-full border rounded p-2 bg-gray-50 focus:bg-white focus:border-blue-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deuxième nom</label>
                                    <input type="text" name="nom2animal" value="{{ $animal->nom2animal }}" class="w-full border rounded p-2 bg-gray-50 focus:bg-white focus:border-blue-500 focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Espèce</label>
                                    <select name="idespece" id="select-espece" class="w-full border rounded p-2 bg-white focus:border-blue-500">
                                        @foreach($especes as $esp)
                                            <option value="{{ $esp->idespece }}" {{ $animal->idespece == $esp->idespece ? 'selected' : '' }}>
                                                {{ $esp->libelleespece }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Race</label>
                                    <select name="idrace" id="select-race" class="w-full border rounded p-2 bg-white focus:border-blue-500">
                                        <option value="">-- Choisir --</option>
                                        @foreach($races as $race)
                                            <option value="{{ $race->idrace }}" 
                                                    data-espece="{{ $race->idespece }}"
                                                    {{ $animal->idrace == $race->idrace ? 'selected' : '' }}>
                                                {{ $race->libellerace }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Croisement</label>
                                    <select name="rac_idrace" id="select-croisement" class="w-full border rounded p-2 bg-white focus:border-blue-500">
                                        <option value="">Aucun (Non croisé)</option>
                                        @foreach($races as $race)
                                            <option value="{{ $race->idrace }}" 
                                                    data-espece="{{ $race->idespece }}"
                                                    {{ $animal->rac_idrace == $race->idrace ? 'selected' : '' }}>
                                                {{ $race->libellerace }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sexe</label>
                                    <select name="sexe" class="w-full border rounded p-2 bg-white">
                                        <option value="1" {{ $animal->sexe == 1 ? 'selected' : '' }}>Mâle</option>
                                        <option value="0" {{ $animal->sexe == 0 ? 'selected' : '' }}>Femelle</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Date de naissance</label>
                                    <input type="date" name="datenaissance" value="{{ $animal->datenaissance }}" class="w-full border rounded p-2 bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Aspect Physique</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Aspect Pelage</label>
                                    <select name="idaspectpelage" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($aspects as $aspect)
                                            <option value="{{ $aspect->idaspectpelage }}" {{ $animal->idaspectpelage == $aspect->idaspectpelage ? 'selected' : '' }}>{{ $aspect->libelleaspectpelage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Taille du Pelage</label>
                                    <select name="idtaillepelage" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($tailles as $taille)
                                            <option value="{{ $taille->idtaillepelage }}" {{ $animal->idtaillepelage == $taille->idtaillepelage ? 'selected' : '' }}>{{ $taille->libelletaillepelage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Caractéristique</label>
                                    <select name="idpelage" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($caracs as $carac)
                                            <option value="{{ $carac->idpelage }}" {{ $animal->idpelage == $carac->idpelage ? 'selected' : '' }}>{{ $carac->libellecaracpelage }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Oreilles</label>
                                    <select name="idoreille" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($oreilles as $oreille)
                                            <option value="{{ $oreille->idoreille }}" {{ $animal->oreilles->contains('idoreille', $oreille->idoreille) ? 'selected' : '' }}>{{ $oreille->libelleoreilles }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Queue</label>
                                    <select name="idqueue" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($queues as $queue)
                                            <option value="{{ $queue->idqueue }}" {{ $animal->idqueue == $queue->idqueue ? 'selected' : '' }}>{{ $queue->libellequeue }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Couleur des Yeux</label>
                                    <select name="idcouleuryeux" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($yeux as $oeil)
                                            <option value="{{ $oeil->idcouleuryeux }}" {{ $animal->idcouleuryeux == $oeil->idcouleuryeux ? 'selected' : '' }}>{{ $oeil->libellecouleuryeux }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Corpulence</label>
                                    <select name="idcorpulence" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($corpulences as $corp)
                                            <option value="{{ $corp->idcorpulence }}" {{ $animal->idcorpulence == $corp->idcorpulence ? 'selected' : '' }}>{{ $corp->libellecorpulence }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Couleur</label>
                                    <select name="idcouleur" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($couleurs as $c)
                                            <option value="{{ $c->idcouleur }}" {{ $animal->idcouleur == $c->idcouleur ? 'selected' : '' }}>{{ $c->libellecouleur }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dégriffé</label>
                                    <select name="degriffe" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        <option value="1" {{ ($animal->caracteristique && $animal->caracteristique->degriffe == 1) ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ ($animal->caracteristique && $animal->caracteristique->degriffe === 0) ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Signes Distinctifs</label>
                                    <div class="border rounded p-2 bg-white min-h-[50px] flex flex-wrap gap-2" id="tags-container">
                                        @foreach($animal->signes as $signe)
                                            <div class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded flex items-center">
                                                {{ $signe->libellesigne }}
                                                <input type="hidden" name="signes_text[]" value="{{ $signe->libellesigne }}">
                                                <button type="button" class="ml-2 text-blue-600 hover:text-blue-900 font-bold" onclick="removeTag(this)">×</button>
                                            </div>
                                        @endforeach
                                        <input type="text" id="tag-input" placeholder="+ Ajouter (puis Entrée)" class="flex-grow outline-none text-sm min-w-[150px] bg-transparent" onkeydown="handleTagInput(event)">
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">Tapez un signe et appuyez sur ENTRÉE pour l'ajouter.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Comportements</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Traits de caractère</label>
                                    <select name="traitcaractere" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($traitsCaracteres as $tc)
                                            <option value="{{ $tc }}"
                                                {{ ($animal->comportement && $animal->comportement->traitcaractere == $tc) ? 'selected' : '' }}>
                                                {{ $tc }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Hygiène</label>
                                    <select name="hygiene" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($hygienes as $h)
                                            <option value="{{ $h }}"
                                                {{ ($animal->comportement && $animal->comportement->hygiene == $h) ? 'selected' : '' }}>
                                                {{ $h }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-1 md:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Peurs</label>
                                    <div class="border rounded p-2 bg-white min-h-[50px] flex flex-wrap gap-2" id="tags-peurs-container">
                                        @if($animal->comportement && $animal->comportement->peurs)
                                            @foreach($animal->comportement->peurs as $peur)
                                                <div class="bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded flex items-center">
                                                    {{ $peur->libellepeur }}
                                                    <input type="hidden" name="peur_text[]" value="{{ $peur->libellepeur }}">
                                                    <button type="button" class="ml-2 text-blue-600 hover:text-blue-900 font-bold" onclick="removeTag(this)">×</button>
                                                </div>
                                            @endforeach
                                        @endif
                                        <input type="text" id="tag-input-peur" placeholder="+ Ajouter (puis Entrée)" class="flex-grow outline-none text-sm min-w-[150px] bg-transparent" onkeydown="handleTagInputPeur(event)">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sociabilité avec humain</label>
                                    <select name="idsociabilite" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($sociabilites as $sociabilite)
                                            <option value="{{ $sociabilite->idsociabilite }}"
                                            {{ ($animal->comportement && $animal->comportement->idsociabilite == $sociabilite->idsociabilite) ? 'selected' : '' }}>
                                                {{ $sociabilite->libellesociabilite }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sociabilité avec mâles</label>
                                    <select name="soc_idsociabilite" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($sociabilites as $sociabilite)
                                            <option value="{{ $sociabilite->idsociabilite }}"
                                            {{ ($animal->comportement && $animal->comportement->soc_idsociabilite == $sociabilite->idsociabilite) ? 'selected' : '' }}>
                                                {{ $sociabilite->libellesociabilite }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sociabilité avec femelles</label>
                                    <select name="soc_idsociabilite2" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        @foreach($sociabilites as $sociabilite)
                                            <option value="{{ $sociabilite->idsociabilite }}"
                                            {{ ($animal->comportement && $animal->comportement->soc_idsociabilite2 == $sociabilite->idsociabilite) ? 'selected' : '' }}>
                                                {{ $sociabilite->libellesociabilite }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Marche autorisé</label>
                                    <select name="marcheautorise" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        <option value="1" {{ ($animal->comportement && $animal->comportement->marcheautorise == 1) ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ ($animal->comportement && $animal->comportement->marcheautorise === 0) ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Photos autorisé</label>
                                    <select name="photosautorise" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        <option value="1" {{ ($animal->comportement && $animal->comportement->photosautorise == 1) ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ ($animal->comportement && $animal->comportement->photosautorise === 0) ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Protection parasites internes</label>
                                    <select name="protectionparasiteinterne" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        <option value="1" {{ ($animal->comportement && $animal->comportement->protectionparasiteinterne == 1) ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ ($animal->comportement && $animal->comportement->protectionparasiteinterne === 0) ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Protection parasites externes</label>
                                    <select name="protectionparasiteexterne" class="w-full border rounded p-2 bg-white">
                                        <option value="">-- Choisir --</option>
                                        <option value="1" {{ ($animal->comportement && $animal->comportement->protectionparasiteexterne == 1) ? 'selected' : '' }}>Oui</option>
                                        <option value="0" {{ ($animal->comportement && $animal->comportement->protectionparasiteexterne === 0) ? 'selected' : '' }}>Non</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="tab-sante" class="tab-content" style="display:none;">
    
                    <div class="space-y-8"> <div class="bg-white border rounded-lg shadow-sm">
                            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                                <h3 class="text-lg font-bold text-gray-700">Courbe de poids</h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="relative h-64 w-full mb-4">
                                    <canvas id="weightChart"></canvas>
                                </div>
                                
                                <div class="flex justify-between items-center border-t pt-4 mt-2">
                                    <button type="button" onclick="openModal('poids', 'Ajouter un poids', 'kg')" class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700 font-bold text-sm shadow inline-flex items-center gap-2 transition hover:scale-105">
                                        <i class="fa-solid fa-plus"></i> Ajouter une mesure
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border rounded-lg shadow-sm">
                            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                                <h3 class="text-lg font-bold text-gray-700">Courbe de taille</h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="relative h-64 w-full mb-4">
                                    <canvas id="sizeChart"></canvas>
                                </div>
                                
                                <div class="flex justify-between items-center border-t pt-4 mt-2">
                                    <button type="button" onclick="openModal('taille', 'Ajouter une taille', 'cm')" class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700 font-bold text-sm shadow inline-flex items-center gap-2 transition hover:scale-105">
                                        <i class="fa-solid fa-plus"></i> Ajouter une mesure
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border rounded-lg shadow-sm">
                            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                                <h3 class="text-lg font-bold text-gray-700">Courbe de température</h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="relative h-64 w-full mb-4">
                                    <canvas id="tempChart"></canvas>
                                </div>
                                
                                <div class="flex justify-between items-center border-t pt-4 mt-2">
                                    <button type="button" onclick="openModal('temperature', 'Ajouter une température', '°C')" class="bg-slate-800 text-white px-4 py-2 rounded hover:bg-slate-700 font-bold text-sm shadow inline-flex items-center gap-2 transition hover:scale-105">
                                        <i class="fa-solid fa-plus"></i> Ajouter une mesure
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                    <div id="tab-historique" class="tab-content" style="display:none;">
                        <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2">Historique</h3>
                        <p class="text-gray-400">Aucun historique pour le moment.</p>
                    </div>

                </div>
            </div>
        </div>
    </form>
   
    <div id="modalMesure" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" onclick="closeModal(event)">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <i class="fa-solid fa-scale-balanced text-blue-600 text-lg"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2" id="modalTitle">Ajouter une mesure</h3>
               
                <form action="{{ route('mesure.store') }}" method="POST" class="mt-4 text-left">
                    @csrf
                    <input type="hidden" name="numtatouage" value="{{ $animal->numtatouage }}">
                   
                    <input type="hidden" name="type_mesure" id="inputTypeMesure">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Date du relevé</label>
                        <input type="date" name="date_mesure" value="{{ date('Y-m-d') }}" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" id="labelValeur">Valeur</label>
                        <div class="flex">
                            <input type="number" step="0.01" name="valeur" required id="inputValeur"
                                class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <span class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-l-0 border-gray-300 rounded-r" id="unitDisplay">
                                kg
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <button type="button" onclick="closeModal()" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Annuler
                        </button>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function handleTagInput(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const input = e.target;
                const text = input.value.trim();
                if (text.length > 0) {
                    addTag(text);
                    input.value = '';
                }
            }
        }

        function handleTagInputPeur(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
               
                const input = e.target;
                const text = input.value.trim();
                if (text.length > 0) {
                    addTagPeur(text);
                    input.value = '';
                }
                return false;
            }
        }

        function addTagPeur(text) {
            const container = document.getElementById('tags-peurs-container');
            const inputField = document.getElementById('tag-input-peur');
           
            if(!inputField) return;

            const tagDiv = document.createElement('div');
            tagDiv.className = "bg-blue-100 text-blue-800 text-sm font-medium px-2.5 py-0.5 rounded flex items-center";
            tagDiv.innerHTML = `${text} <input type="hidden" name="peur_text[]" value="${text}"> <button type="button" class="ml-2 text-blue-600 hover:text-blue-900 font-bold" onclick="removeTag(this)">×</button>`;
            container.insertBefore(tagDiv, inputField);
        }

        function addTag(text) {
            const container = document.getElementById('tags-container');
            const inputField = document.getElementById('tag-input');
            const tagDiv = document.createElement('div');
            tagDiv.className = "bg-green-100 text-green-800 text-sm font-medium px-2.5 py-0.5 rounded flex items-center";
            tagDiv.innerHTML = `${text} <input type="hidden" name="signes_text[]" value="${text}"> <button type="button" class="ml-2 text-green-600 hover:text-green-900 font-bold" onclick="removeTag(this)">×</button>`;
            container.insertBefore(tagDiv, inputField);
        }

        function removeTag(button) {
            button.parentElement.remove();
        }

        function openTab(evt, tabName) {
            var tabcontents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabcontents.length; i++) {
                tabcontents[i].style.display = "none";
            }
            var tablinks = document.getElementsByClassName("tab-link");
            for (var i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" text-blue-600 border-blue-600 bg-blue-50", " text-gray-600 border-transparent");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className = evt.currentTarget.className.replace(" text-gray-600 border-transparent", " text-blue-600 border-blue-600 bg-blue-50");
        }

        function openModal(type, title, unit) {
            document.getElementById('modalMesure').classList.remove('hidden');
           
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('labelValeur').innerText = "Valeur (" + unit + ")";
            document.getElementById('unitDisplay').innerText = unit;
           
            document.getElementById('inputTypeMesure').value = type;
           
            setTimeout(() => {
                document.getElementById('inputValeur').focus();
            }, 100);
        }

        function closeModal(event) {
            if (!event || event.target.id === 'modalMesure') {
                document.getElementById('modalMesure').classList.add('hidden');
                document.getElementById('inputValeur').value = ""; // Reset valeur
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
           
            const selectEspece = document.getElementById('select-espece');
            const selectRace = document.getElementById('select-race');
            const selectCroisement = document.getElementById('select-croisement');

            const allRacesOptions = Array.from(selectRace.querySelectorAll('option'));
            const allCroisementOptions = Array.from(selectCroisement.querySelectorAll('option'));

            function filterLists() {
                const selectedEspeceId = selectEspece.value;
                const currentRaceVal = selectRace.value;
                selectRace.innerHTML = ''; 
                allRacesOptions.forEach(option => {
                    if (option.value === "" || option.dataset.espece === selectedEspeceId) {
                        selectRace.appendChild(option);
                    }
                });

                selectRace.value = currentRaceVal;
                if (selectRace.value !== currentRaceVal) {
                    selectRace.value = "";
                }

                const currentCroisVal = selectCroisement.value;
                selectCroisement.innerHTML = ''; 

                allCroisementOptions.forEach(option => {
                    if (option.value === "" || option.dataset.espece === selectedEspeceId) {
                        selectCroisement.appendChild(option);
                    }
                });

                selectCroisement.value = currentCroisVal;
                if (selectCroisement.value !== currentCroisVal) {
                    selectCroisement.value = "";
                }
            }

            if(selectEspece && selectRace && selectCroisement) {
                selectEspece.addEventListener('change', function() {
                    selectRace.value = "";
                    selectCroisement.value = "";
                    filterLists();
                });

                filterLists();
            }


            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: false, grid: { color: '#f3f4f6' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } },
                elements: {
                    line: { tension: 0.3, borderWidth: 2, fill: true },
                    point: { radius: 4, hitRadius: 10, hoverRadius: 6 }
                }
            };

            const labelsPoids = @json($animal->priseMesures->whereNotNull('poids')->pluck('date_mesure'));
            const dataPoids = @json($animal->priseMesures->whereNotNull('poids')->pluck('poids'));
            
            new Chart(document.getElementById('weightChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labelsPoids,
                    datasets: [{
                        label: 'Poids (kg)',
                        data: dataPoids,
                        borderColor: '#4338ca',
                        backgroundColor: 'rgba(67, 56, 202, 0.1)',
                        pointBackgroundColor: '#4338ca',
                    }]
                },
                options: commonOptions
            });

            const labelsTaille = @json($animal->priseMesures->whereNotNull('taille')->pluck('date_mesure'));
            const dataTaille = @json($animal->priseMesures->whereNotNull('taille')->pluck('taille'));
            
            new Chart(document.getElementById('sizeChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labelsTaille,
                    datasets: [{
                        label: 'Taille (cm)',
                        data: dataTaille,
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        pointBackgroundColor: '#059669',
                    }]
                },
                options: commonOptions
            });

            const labelsTemp = @json($animal->priseMesures->whereNotNull('temperature')->pluck('date_mesure'));
            const dataTemp = @json($animal->priseMesures->whereNotNull('temperature')->pluck('temperature'));

            new Chart(document.getElementById('tempChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: labelsTemp,
                    datasets: [{
                        label: 'Temp (°C)',
                        data: dataTemp,
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        pointBackgroundColor: '#dc2626',
                    }]
                },
                options: commonOptions
            });
        });
    </script>
</body>
</html>