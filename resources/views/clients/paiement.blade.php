<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://js.stripe.com/v3/"></script>
    
    <style> 
        [x-cloak] { display: none !important; } 
        .StripeElement {
            box-sizing: border-box;
            height: 46px;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            background-color: white;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .StripeElement--focus { border-color: #6366f1; box-shadow: 0 0 0 1px #6366f1; }
        .StripeElement--invalid { border-color: #ef4444; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="max-w-4xl mx-auto px-4 py-10">
    <a href="{{ route('client.panier') }}" class="text-gray-500 hover:text-indigo-600 mb-6 inline-block transition">
        <i class="fa-solid fa-arrow-left"></i> Retour au panier
    </a>

    <h1 class="text-3xl font-bold mb-8 text-gray-900">Paiement sécurisé</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
       
        <div class="md:col-span-2 space-y-6">
            @if($reste_a_payer > 0)
                <div x-data="{ mode: '{{ count($cartes) > 0 ? 'saved' : 'new' }}' }">
                    <form action="{{ route('client.paiement.process') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="payment_method" :value="mode === 'saved' ? 'saved_card' : 'new_card'">

                        @if(count($cartes) > 0)
                            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex gap-4 mb-4">
                                <label class="flex items-center cursor-pointer px-4 py-2 rounded-lg hover:bg-gray-50 transition flex-1 border border-transparent has-[:checked]:border-indigo-200 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="mode_switch" value="saved" x-model="mode" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span class="ml-2 font-medium text-gray-700">Mes cartes</span>
                                </label>
                                <label class="flex items-center cursor-pointer px-4 py-2 rounded-lg hover:bg-gray-50 transition flex-1 border border-transparent has-[:checked]:border-indigo-200 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="mode_switch" value="new" x-model="mode" class="text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                                    <span class="ml-2 font-medium text-gray-700">Nouvelle carte</span>
                                </label>
                            </div>

                            <div x-show="mode === 'saved'" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 space-y-4">
                                <h3 class="font-bold text-lg mb-2 text-gray-800">Choisissez une carte</h3>
                                @foreach($cartes as $carte)
                                    <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer hover:bg-gray-50 transition border-gray-200 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 has-[:checked]:shadow-sm">
                                        <div class="flex items-center">
                                            <input type="radio" name="card_id" value="{{ $carte->id }}" class="text-indigo-600 h-5 w-5 mr-4 focus:ring-indigo-500" {{ $loop->first ? 'checked' : '' }}>
                                            <div>
                                                <div class="font-bold flex items-center text-gray-700">
                                                    <span class="uppercase">{{ $carte->brand }}</span> •••• {{ $carte->last4 }}
                                                </div>
                                                <div class="text-xs text-gray-500 mt-1 ml-9">Expire: {{ str_pad($carte->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $carte->exp_year }}</div>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <div x-show="mode === 'new'" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200" x-cloak>
                            <h3 class="font-bold text-lg mb-4 text-gray-800 flex items-center">
                                <i class="fa-regular fa-credit-card mr-2 text-indigo-600"></i> Nouvelle Carte
                            </h3>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du titulaire</label>
                                    <div class="relative">
                                        <input type="text" id="card-holder-name" placeholder="M. JEAN DUPONT" class="w-full pl-10 p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 uppercase placeholder-gray-300">
                                        <i class="fa-solid fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Informations carte</label>
                                    <div id="card-element"></div>
                                    <div id="card-errors" role="alert" class="text-red-500 text-sm mt-2 font-medium"></div>
                                </div>
                                <div class="pt-2">
                                    <label class="flex items-center cursor-pointer group">
                                        <input type="checkbox" name="save_card" value="1" class="rounded text-indigo-600 focus:ring-indigo-500 h-5 w-5 border-gray-300">
                                        <span class="ml-2 text-sm text-gray-700 group-hover:text-indigo-600 transition">Enregistrer cette carte pour la prochaine fois</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="submit-button" class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 flex items-center justify-center text-lg disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-lock mr-2"></i> Payer {{ number_format($reste_a_payer, 2, ',', ' ') }} €
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200 text-center">
                    <div class="inline-flex p-4 rounded-full bg-green-100 text-green-600 mb-4">
                        <i class="fa-solid fa-check-circle text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Commande 100% couverte</h2>
                    <p class="text-gray-500 mb-6">Votre avoir et/ou code promo couvrent la totalité du montant. Aucun paiement bancaire n'est nécessaire.</p>

                    <form action="{{ route('client.paiement.process') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-4 rounded-xl shadow-lg transition transform hover:-translate-y-0.5 text-lg">
                            Confirmer la commande
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="md:col-span-1">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 sticky top-6">
        <h3 class="font-bold text-lg mb-4 text-gray-700 border-b border-gray-100 pb-2">Récapitulatif</h3>
        
        <ul class="space-y-4 mb-6">
            @foreach($panier as $item)
                <li class="flex justify-between items-start text-sm">
                    <div class="text-gray-600 pr-2">
                        <span class="font-bold text-gray-800">{{ $item['quantite'] }}x</span> 
                        {{ \Illuminate\Support\Str::limit($item['nom'], 25) }}
                    </div>
                    <span class="font-medium whitespace-nowrap">{{ number_format($item['prix'] * $item['quantite'], 2) }} €</span>
                </li>
            @endforeach
        </ul>

        <div class="border-t border-gray-100 my-4"></div>

        
        <div class="flex justify-between mb-2 text-slate-600 text-sm">
            <span>Sous-total</span>
            <span>{{ number_format($totalBrut, 2) }} €</span>
        </div>

        @if(session('code_promo'))
            <div class="flex justify-between items-center mb-2 text-green-600 text-sm font-medium bg-green-50 p-2 rounded">
                <div>
                    <span class="block text-xs uppercase font-bold">Promo ({{ session('code_promo')['code'] }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span>-{{ number_format($remisePromo, 2) }} €</span>
                    <a href="{{ route('client.panier.promo.remove') }}" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash"></i></a>
                </div>
            </div>
        @else
            <form action="{{ route('client.panier.promo') }}" method="POST" class="flex gap-2 my-3">
                @csrf
                <input type="text" name="code" placeholder="Code Promo" class="w-full text-xs border-gray-300 rounded focus:ring-indigo-500 focus:border-indigo-500 uppercase font-mono p-2">
                <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded text-xs font-bold hover:bg-gray-700">OK</button>
            </form>
        @endif

        @if($solde_avoir > 0)
            <div class="flex justify-between mb-2 text-amber-600 text-sm font-medium bg-amber-50 p-2 rounded">
                <span>Avoir utilisé</span>
                <span>-{{ number_format($avoir_utilise, 2) }} €</span>
            </div>
            <div class="text-xs text-slate-400 text-right mb-2">Solde restant : {{ number_format($solde_avoir - $avoir_utilise, 2) }} €</div>
        @endif

        <div class="border-t border-gray-200 pt-4 flex justify-between items-center mt-4">
            <span class="font-bold text-lg text-gray-800">À payer</span>
            <span class="font-extrabold text-xl text-indigo-600">{{ number_format($reste_a_payer, 2, ',', ' ') }} €</span>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-center space-x-4 opacity-60 grayscale hover:grayscale-0 transition">
            <i class="fa-brands fa-cc-visa text-3xl text-blue-800"></i>
            <i class="fa-brands fa-cc-mastercard text-3xl text-red-600"></i>
            <i class="fa-brands fa-cc-amex text-3xl text-blue-500"></i>
        </div>
        <div class="text-center text-xs text-gray-400 mt-2 flex items-center justify-center">
            <i class="fa-solid fa-lock mr-1"></i> Paiement 100% Sécurisé SSL
        </div>
    </div>
</div>

    </div>
</div>

@if(session('error'))
    <div x-data="{ show: true }" x-show="show" class="fixed bottom-6 right-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-2xl z-50 flex items-center animate-bounce">
        <i class="fa-solid fa-circle-exclamation mr-3 text-xl"></i>
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="ml-4 text-red-700 hover:text-red-900"><i class="fa-solid fa-xmark"></i></button>
    </div>
@endif

<script>
    var stripe = Stripe('{{ env("STRIPE_KEY") }}');
    var elements = stripe.elements();

    
    var cardElement = document.getElementById('card-element');

    if (cardElement) {
        var style = {
            base: {
                color: '#374151',
                fontFamily: '"Inter", sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': { color: '#d1d5db' }
            },
            invalid: { color: '#ef4444', iconColor: '#ef4444' }
        };

        var card = elements.create('card', {style: style, hidePostalCode: true});
        card.mount('#card-element');

        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            displayError.textContent = event.error ? event.error.message : '';
        });
    }

    var form = document.getElementById('payment-form');
    
    if (form) {
        var submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            
            var paymentModeInput = document.querySelector('input[name="payment_method"]');
            var paymentMode = paymentModeInput ? paymentModeInput.value : 'new_card';

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Traitement...';

            if (paymentMode === 'saved_card') {
                form.submit();
            } else {
                var cardHolderName = document.getElementById('card-holder-name').value;
                
                if (card) {
                    stripe.createToken(card, { name: cardHolderName }).then(function(result) {
                        if (result.error) {
                            var errorElement = document.getElementById('card-errors');
                            errorElement.textContent = result.error.message;
                            
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="fa-solid fa-lock mr-2"></i> Payer {{ number_format($reste_a_payer, 2, ",", " ") }} €';
                        } else {
                            stripeTokenHandler(result.token);
                        }
                    });
                }
            }
        });
    }

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);
        form.submit();
    }
</script>
</body>
</html>