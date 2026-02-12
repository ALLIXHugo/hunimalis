<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hunimalis - Mon Abonnement</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .toggle-checkbox:checked { right: 0; border-color: #68D391; }
        .toggle-checkbox:checked + .toggle-label { background-color: #68D391; }
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 0.3s, transform 0.3s; }
        
        .group-card:hover { transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
    </style>
</head>
<body class="bg-gray-100 font-sans text-slate-800">

    <div class="bg-slate-800 text-white p-4 sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto flex gap-6 text-sm uppercase tracking-wide overflow-x-auto">
            <a href="#" class="text-white font-bold border-b-2 border-white pb-1 flex items-center gap-2">
                <i class="fa-solid fa-euro-sign"></i> Abonnement
            </a>
            </div>
    </div>

    <div class="max-w-7xl mx-auto p-6">
        
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm flex items-center">
                <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                <div><p class="font-bold">Succès</p><p>{{ session('success') }}</p></div>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm">
                <ul>@foreach($errors->all() as $error) <li>• {{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 grid grid-cols-1 md:grid-cols-4 gap-6 text-center divide-x divide-gray-100">
            <div class="flex flex-col items-center justify-center p-2">
                <i class="fa-solid fa-book-open text-3xl mb-2 text-slate-800"></i>
                <span class="text-xs uppercase text-gray-400 font-bold">OFFRE</span>
                <div class="flex flex-col">
                    <span class="text-lg font-bold text-slate-800">{{ $data['type_abo'] }}</span>
                    @if($data['is_essai'])
                        <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full mt-1">Gratuit</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-col items-center justify-center p-2">
                <i class="fa-regular fa-compass text-3xl mb-2 text-slate-800"></i>
                <span class="text-lg font-bold {{ $data['is_essai'] ? 'text-blue-600' : 'text-slate-800' }}">{{ $data['statut'] }}</span>
                <span class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-arrows-rotate"></i> {{ $data['renouvellement'] }}</span>
            </div>
            <div class="flex flex-col items-center justify-center p-2">
                <i class="fa-regular fa-calendar-days text-3xl mb-2 text-slate-800"></i>
                <div class="text-sm text-gray-600">Souscrit le {{ $data['date_souscription'] }}</div>
                <div class="text-sm font-bold text-slate-800 mt-1"><i class="fa-solid fa-bell text-yellow-500"></i> Échéance : {{ $data['prochaine_echeance'] }}</div>
            </div>
            <div class="flex flex-col items-center justify-center p-2">
                <i class="fa-solid fa-receipt text-3xl mb-2 text-slate-800"></i>
                <span class="text-sm text-gray-600">Prochaine facture (estimée)</span>
                <span class="text-2xl font-extrabold text-slate-800 mt-1" id="headerTotal">-- €</span>
            </div>
        </div>

        <form action="{{ route('professionnel.updateAbonnement') }}" method="POST" id="subscriptionForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <div class="lg:col-span-2 space-y-8">
                    
                    <div>
                        <h2 class="text-xl font-bold text-slate-700 uppercase mb-4 flex items-center gap-2 border-b pb-2">
                            <i class="fa-solid fa-cubes text-blue-600"></i> Modules Disponibles
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach($data['modules_disponibles'] as $module)
                                @php
                                    $isInclus = $module->pivot->est_inclus;
                                    // PRIX STANDARD (Table Module)
                                    $prixMensuel = $module->prixmodule; 
                                    $prixAnnuel = $prixMensuel * 12;
                                    $isSubscribed = in_array($module->idmodule, $data['subscribed_ids']);
                                    $isChecked = $isInclus || $isSubscribed;
                                @endphp

                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible transition duration-300 flex flex-col h-full relative group-card">
                                    <div class="h-32 bg-slate-50 flex items-center justify-center border-b border-gray-100 relative">
                                        @if(str_contains(strtolower($module->nommodule), 'stock')) <i class="fa-solid fa-boxes-stacked text-5xl text-blue-300"></i>
                                        @elseif(str_contains(strtolower($module->nommodule), 'facturation')) <i class="fa-solid fa-file-invoice-dollar text-5xl text-green-300"></i>
                                        @elseif(str_contains(strtolower($module->nommodule), 'calendrier')) <i class="fa-regular fa-calendar-check text-5xl text-purple-300"></i>
                                        @elseif(str_contains(strtolower($module->nommodule), 'santé')) <i class="fa-solid fa-heart-pulse text-5xl text-red-300"></i>
                                        @elseif(str_contains(strtolower($module->nommodule), 'box')) <i class="fa-solid fa-warehouse text-5xl text-orange-300"></i>
                                        @else <i class="fa-solid fa-puzzle-piece text-5xl text-gray-300"></i> @endif

                                        @if($isInclus)
                                            <span class="absolute top-3 right-3 bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide shadow-sm">Inclus</span>
                                        @endif
                                    </div>
                                    
                                    <div class="p-5 text-center flex-grow flex flex-col justify-between relative">
                                        <div class="flex justify-center items-center gap-2 mb-4">
                                            <h3 class="font-bold text-slate-800 text-sm leading-tight">{{ $module->nommodule }}</h3>
                                            <div class="group relative flex justify-center">
                                                <i class="fa-solid fa-circle-info text-gray-400 cursor-help hover:text-blue-500 transition"></i>
                                                <div class="absolute bottom-6 w-48 p-3 bg-gray-800 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 text-left leading-relaxed">
                                                    {{ $module->description ?? "Aucune description." }}
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex justify-center mb-4">
                                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                                @if($isInclus)
                                                    <input type="checkbox" checked disabled class="toggle-checkbox opacity-50 cursor-not-allowed absolute block w-6 h-6 rounded-full bg-white border-4 border-gray-300 checked:right-0 checked:border-green-500"/>
                                                    <label class="toggle-label opacity-50 cursor-not-allowed block overflow-hidden h-6 rounded-full bg-gray-300 checked:bg-green-500"></label>
                                                @else
                                                    <input type="checkbox" 
                                                           name="modules[]" 
                                                           value="{{ $module->idmodule }}" 
                                                           id="mod_{{ $module->idmodule }}"
                                                           data-price="{{ $prixMensuel }}" 
                                                           onchange="updateTotal()"
                                                           class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-gray-300 checked:right-0 checked:border-green-500 shadow-sm" 
                                                           {{ $isChecked ? 'checked' : '' }}
                                                    />
                                                    <label for="mod_{{ $module->idmodule }}" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer checked:bg-green-500"></label>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <p class="text-xs text-gray-500 font-medium pt-3 border-t border-gray-50">
                                            @if($isInclus)
                                                <span class="text-green-600 font-bold"><i class="fa-solid fa-check"></i> Compris</span>
                                            @else
                                                {{ number_format($prixMensuel, 2) }} € HT / mois
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-slate-700 uppercase mt-8 mb-4 flex items-center gap-2 border-b pb-2">
                            <i class="fa-solid fa-comment-sms text-blue-600"></i> Crédits SMS
                        </h2>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-100 p-4 rounded-full text-blue-600 shadow-inner"><i class="fa-solid fa-envelope text-2xl"></i></div>
                                <div>
                                    <p class="font-bold text-slate-800 text-xl">{{ $data['pro']->credit_sms ?? 0 }} <span class="text-sm font-normal text-gray-500">crédits</span></p>
                                    <p class="text-xs text-gray-500">Tarif unitaire : <strong>0,075€ HT</strong> / unité</p>
                                </div>
                            </div>
                            <button type="button" onclick="openSmsModal()" class="text-white bg-slate-800 hover:bg-slate-700 px-5 py-2.5 rounded-lg text-sm font-bold transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-cart-plus mr-2"></i> Acheter un pack
                            </button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6 sticky top-6">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                        <div class="bg-slate-800 text-white text-center py-6 relative">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                            <h3 class="font-bold text-xl tracking-widest uppercase">{{ $data['type_abo'] }}</h3>
                            <p class="text-slate-400 text-xs uppercase">Abonnement Socle</p>
                        </div>
                        
                        <div class="p-6 text-center">
                            <div class="flex items-baseline justify-center mb-6">
                                <span class="text-5xl font-extrabold text-slate-800 transition-all duration-300" id="totalPriceDisplay">--- €</span>
                                <span class="text-gray-500 ml-2 font-medium" id="periodDisplay">HT / an</span>
                            </div>

                            <div class="flex items-center justify-center gap-3 mb-8 bg-gray-100 rounded-full p-1.5 w-max mx-auto shadow-inner border border-gray-200">
                                <span class="text-xs font-bold text-gray-500 pl-3 uppercase">Mensuel</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="mode_paiement_annuel" 
                                           value="1" 
                                           id="subscriptionToggle" 
                                           class="sr-only peer" 
                                           {{ $data['is_annuel'] ? 'checked' : '' }} 
                                           onchange="updateTotal()">
                                    <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600 transition-colors duration-300 shadow-sm"></div>
                                </label>
                                <span class="text-xs font-bold text-gray-800 pr-3 uppercase">Annuel</span>
                            </div>

                            <ul class="text-left text-sm space-y-4 mb-8 pl-4 border-t pt-6 border-gray-100">
                                <li class="flex items-center text-gray-700">
                                    <span class="bg-green-100 text-green-600 rounded-full p-1 mr-3 text-xs"><i class="fa-solid fa-check"></i></span>
                                    <span class="font-medium">Gestion des animaux</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <span class="bg-green-100 text-green-600 rounded-full p-1 mr-3 text-xs"><i class="fa-solid fa-check"></i></span>
                                    <span class="font-medium">Gestion de l'agenda</span>
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <span class="bg-green-100 text-green-600 rounded-full p-1 mr-3 text-xs"><i class="fa-solid fa-check"></i></span>
                                    <span class="font-medium">Gestion des contacts</span>
                                </li>
                                <li class="flex items-start text-gray-700">
                                    <span class="bg-green-100 text-green-600 rounded-full p-1 mr-3 text-xs mt-0.5"><i class="fa-solid fa-check"></i></span>
                                    <div>
                                        <span class="font-bold">SMS 0,075€ HT/unité*</span>
                                        <p class="text-[10px] text-gray-400 mt-0.5">* Hors tarif de base, achat via packs</p>
                                    </div>
                                </li>
                            </ul>

                            <button type="button" onclick="openPaymentModal()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 shadow-lg flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-arrow-right"></i> Continuer vers le paiement
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="paymentModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closePaymentModal()"></div>
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg modal-enter" id="modalContent">
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-base font-semibold leading-6 text-gray-900"><i class="fa-solid fa-credit-card text-blue-600 mr-2"></i> Finaliser l'abonnement</h3>
                            <button type="button" onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-500"><i class="fa-solid fa-xmark text-xl"></i></button>
                        </div>

                        <div class="px-4 py-5 sm:p-6">
                            <div class="bg-blue-50 border border-blue-100 rounded-md p-3 mb-6 text-center">
                                <p class="text-sm text-blue-800">Montant total à régler : <span class="font-bold text-lg" id="modalTotalAmount">-- € TTC</span></p>
                                <p class="text-xs text-blue-600 mt-1">Vos modifications seront appliquées immédiatement.</p>
                            </div>

                            <div class="space-y-4">
                                @php 
                                    $hasCard = !empty($data['pro']->cb_numero); 
                                    $maskedCard = $hasCard ? '**** **** **** ' . substr($data['pro']->cb_numero, -4) : ''; 
                                @endphp

                                @if($hasCard)
                                    <div class="bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-sm flex items-start">
                                        <i class="fa-solid fa-check-circle mt-0.5 mr-2"></i>
                                        <div>
                                            <p class="font-bold">Carte enregistrée valide</p>
                                            <p class="text-xs">{{ $maskedCard }} (Exp: {{ $data['pro']->cb_expiration }})</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-3 py-2 rounded text-sm mb-2">
                                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> Veuillez saisir un moyen de paiement.
                                    </div>
                                @endif

                                <div>
                                    <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Numéro de carte</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400"><i class="fa-regular fa-credit-card"></i></div>
                                        <input type="text" name="cb_numero" placeholder="0000 0000 0000 0000" maxlength="16" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 pl-10 transition-colors">
                                    </div>
                                    @if($hasCard) <p class="text-[10px] text-gray-400 mt-1 italic">Laisser vide pour conserver la carte actuelle.</p> @endif
                                </div>

                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">Expiration</label>
                                        <input type="text" name="cb_expiration" placeholder="MM/AA" maxlength="5" value="{{ old('cb_expiration', $data['pro']->cb_expiration) }}" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 text-center transition-colors">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-gray-600 text-xs font-bold mb-1 uppercase">CVV</label>
                                        <input type="text" name="cb_cvv" placeholder="123" maxlength="4" class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 text-center transition-colors">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="submit" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto">Confirmer et Payer</button>
                            <button type="button" onclick="closePaymentModal()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Annuler</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="smsModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeSmsModal()"></div>
            <div class="flex min-h-full items-center justify-center p-4 text-center">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl modal-enter" id="smsModalContent">
                    
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4 flex justify-between items-center text-white">
                        <h3 class="text-lg font-bold flex items-center gap-2"><i class="fa-solid fa-comments"></i> Recharger mes crédits SMS</h3>
                        <button type="button" onclick="closeSmsModal()" class="text-white/80 hover:text-white transition"><i class="fa-solid fa-xmark text-xl"></i></button>
                    </div>
                    
                    <form action="{{ route('professionnel.buySmsPack') }}" method="POST">
                        @csrf
                        <div class="p-8">
                            <p class="text-gray-600 mb-6 text-center text-sm">Sélectionnez un pack (Base 0,075€ HT/unité).</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($data['sms_packs'] as $qty => $pack)
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="pack_quantity" value="{{ $qty }}" class="peer sr-only" required>
                                    <div class="rounded-xl border-2 border-gray-200 p-6 text-center hover:border-blue-400 peer-checked:border-blue-600 peer-checked:bg-blue-50 transition-all duration-200 h-full flex flex-col justify-between">
                                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2"><span class="bg-gray-800 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider group-hover:bg-blue-600 peer-checked:bg-blue-600 transition">{{ $pack['label'] }}</span></div>
                                        <div class="mt-2"><span class="block text-4xl font-extrabold text-slate-800 mb-1">{{ $qty }}</span><span class="text-xs uppercase text-gray-500 font-bold tracking-wide">SMS</span></div>
                                        <div class="mt-4 pt-4 border-t border-gray-200/60"><span class="block text-xl font-bold text-blue-600">{{ number_format($pack['price'], 2) }} €</span><span class="text-[10px] text-gray-400">HT</span></div>
                                    </div>
                                    <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="fa-solid fa-circle-check text-xl"></i></div>
                                </label>
                                @endforeach
                            </div>

                            <div class="mt-8 bg-slate-50 p-4 rounded-lg border border-slate-200 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-white p-2 rounded border border-gray-200"><i class="fa-brands fa-cc-visa text-2xl text-blue-800"></i></div>
                                    <div class="text-xs text-gray-600">
                                        @if(!empty($data['pro']->cb_numero)) Prélèvement sur la carte terminant par <strong>{{ substr($data['pro']->cb_numero, -4) }}</strong>
                                        @else <span class="text-red-500 font-bold">Aucune carte enregistrée</span> @endif
                                    </div>
                                </div>
                                <div class="text-right"><p class="text-xs text-gray-400">TVA 20% applicable</p></div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5">Payer et Créditer</button>
                            <button type="button" onclick="closeSmsModal()" class="w-full sm:w-auto bg-white hover:bg-gray-100 text-gray-700 font-medium py-2 px-6 rounded-lg border border-gray-300 transition">Annuler</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        const basePrixMensuel = {{ $data['prix_base_mensuel'] ?? 0 }};
        const basePrixAnnuel = {{ $data['prix_base_annuel'] ?? 0 }};

        function updateTotal() {
            const isAnnuel = document.getElementById('subscriptionToggle').checked;
            let currentBasePrice = isAnnuel ? basePrixAnnuel : basePrixMensuel;
            let optionsTotalMensuel = 0;
            const checkboxes = document.querySelectorAll('input[name="modules[]"]:checked');
            
            checkboxes.forEach((cb) => {
                let price = parseFloat(cb.getAttribute('data-price'));
                if (!isNaN(price)) {
                    optionsTotalMensuel += price;
                }
            });

            let optionsTotal = isAnnuel ? (optionsTotalMensuel * 12) : optionsTotalMensuel;

            let totalHT = currentBasePrice + optionsTotal;
            let totalTTC = totalHT * 1.20; 

            document.getElementById('totalPriceDisplay').innerText = totalHT.toFixed(2);
            document.getElementById('periodDisplay').innerText = isAnnuel ? "HT / an" : "HT / mois";
            document.getElementById('headerTotal').innerText = totalTTC.toFixed(2) + " €";
            document.getElementById('modalTotalAmount').innerText = totalTTC.toFixed(2) + " € TTC";
        }

        function openPaymentModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            setTimeout(() => { document.getElementById('modalContent').classList.remove('modal-enter'); document.getElementById('modalContent').classList.add('modal-enter-active'); }, 10);
        }
        function closePaymentModal() {
            document.getElementById('modalContent').classList.remove('modal-enter-active'); document.getElementById('modalContent').classList.add('modal-enter');
            setTimeout(() => { document.getElementById('paymentModal').classList.add('hidden'); }, 300);
        }

        function openSmsModal() {
            document.getElementById('smsModal').classList.remove('hidden');
            setTimeout(() => { document.getElementById('smsModalContent').classList.remove('modal-enter'); document.getElementById('smsModalContent').classList.add('modal-enter-active'); }, 10);
        }
        function closeSmsModal() {
            document.getElementById('smsModalContent').classList.remove('modal-enter-active'); document.getElementById('smsModalContent').classList.add('modal-enter');
            setTimeout(() => { document.getElementById('smsModal').classList.add('hidden'); }, 300);
        }

        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>
</body>
</html>