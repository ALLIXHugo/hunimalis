<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'); body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">



<div class="max-w-6xl mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-shopping-cart text-indigo-600 mr-3"></i> Mon Panier
        </h1>
        <a href="{{ route('client.boutique') }}" class="text-indigo-600 hover:underline font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Continuer les achats
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm flex items-center">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif

    @if(session('panier_client') && count(session('panier_client')) > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Produit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Quantité</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Prix Unit. TTC</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(session('panier_client') as $id => $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item['photo'])
                                        <img class="h-12 w-12 rounded object-cover mr-4 border shadow-sm" src="{{ asset('storage/'.$item['photo']) }}" alt="{{ $item['nom'] }}">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center mr-4 text-gray-400 border">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $item['nom'] }}</div>
                                        <div class="text-xs text-gray-500">Dispo: {{ $item['stock_max'] }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('client.panier.update', $id) }}" method="POST" class="flex items-center justify-center">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="relative w-20">
                                        <input type="number" 
                                            name="quantity" 
                                            value="{{ $item['quantite'] }}" 
                                            min="1" 
                                            max="{{ $item['stock_max'] }}" 
                                            class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 text-center font-bold"
                                            onchange="verifierEtEnvoyer(this, {{ $item['stock_max'] }})"
                                        >
                                    </div>                                    
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">
                                {{ number_format($item['prix'], 2, ',', ' ') }} €
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">
                                {{ number_format($item['prix'] * $item['quantite'], 2, ',', ' ') }} €
                            </td>

                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('client.panier.delete', $id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition p-2 hover:bg-red-50 rounded-full" title="Retirer du panier">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold uppercase text-gray-600 text-sm">Total Commande TTC</td>
                        <td class="px-6 py-4 text-right font-extrabold text-xl text-indigo-700 font-mono">{{ number_format($total, 2, ',', ' ') }} €</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <a href="{{ route('client.panier.vider') }}" class="text-red-600 hover:text-red-800 text-sm font-medium hover:underline flex items-center">
                    <i class="fas fa-trash mr-2"></i> Vider le panier
                </a>
                
                <a href="{{ route('client.paiement') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow-lg transition transform hover:-translate-y-0.5 flex items-center">
                    <i class="fas fa-credit-card mr-2"></i> Passer au paiement
                </a>
            </div>

        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="bg-gray-100 rounded-full h-24 w-24 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-basket text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Votre panier est vide</h2>
            <p class="text-gray-500 mb-8">Vous n'avez pas encore craqué pour nos produits ?</p>
            <a href="{{ route('client.boutique') }}" class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow">
                Découvrir la boutique
            </a>
        </div>
    @endif
</div>
<script>
    function verifierEtEnvoyer(input, maxStock) {
        let valeur = parseInt(input.value);

        if (valeur < 1 || isNaN(valeur)) {
            input.value = 1;
        } 
        else if (valeur > maxStock) {
            input.value = maxStock;
            alert("Désolé, nous n'avons que " + maxStock + " articles en stock.");
        }

        setTimeout(() => {
            input.form.submit();
        }, 200);
    }
</script>
<x-chatbot />
</body>
</html>