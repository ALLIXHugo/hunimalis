<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produit->nomproduit }} - Hunimalis Boutique</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'); body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('client.boutique') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-indigo-600 mb-6 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la boutique
        </a>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">
                
                <div class="bg-gray-100 h-96 md:h-auto flex items-center justify-center border-b md:border-b-0 md:border-r border-gray-200 p-8">
                    <div class="text-gray-300 flex flex-col items-center">
                        @if($produit->photo)
                            <img src="{{ asset('storage/' . $produit->photo) }}" alt="{{ $produit->nomproduit }}" class="max-h-96 max-w-full object-contain shadow-xl rounded-lg border border-gray-200">
                        @else
                            <i class="fas fa-camera text-9xl"></i>
                            <p class="mt-4 text-sm text-gray-400">Image du produit non disponible</p>                        
                        @endif
                        
                    </div>
                </div>

                <div class="p-8 md:p-12 flex flex-col justify-center">
                    
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full uppercase tracking-wide">
                            {{ $produit->libellecategoriepro }}
                        </span>
                        @if($produit->stocks > 0)
                            <span class="px-3 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full uppercase tracking-wide">
                                En Stock ({{ $produit->stocks }})
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs font-bold text-red-700 bg-red-100 rounded-full uppercase tracking-wide">
                                Rupture de stock
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">
                        {{ $produit->nomproduit }}
                    </h1>
                    <p class="text-sm text-gray-500 font-mono mb-6">Référence : {{ $produit->numserie ?? 'N/A' }}</p>

                    <div class="flex items-center mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center shadow-sm text-indigo-500">
                            <i class="fas fa-store"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-gray-500 uppercase font-bold">Vendu par</p>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $produit->professionnel ? $produit->professionnel->libelleetablissement : 'Hunimalis Central' }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-2">Description</h3>
                        <div class="text-gray-600 text-sm leading-relaxed">
                            @if(!empty($produit->descriptionprod))
                                {{ $produit->descriptionprod }}
                            @else
                                <span class="italic text-gray-400">Aucune description disponible pour ce produit.</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-baseline mb-6 border-t border-gray-100 pt-6">
                        <span class="text-3xl font-bold text-gray-900 mr-2">
                            {{ number_format($produit->prixvente, 2, ',', ' ') }} €
                        </span>
                        <span class="text-sm text-gray-500">TTC / Unité</span>
                    </div>

                    @if($produit->stocks > 0)
                        <form action="{{ route('produit.addToOrder', $produit->idproduit) }}" method="POST">
                            @csrf
                            
                            <div class="flex items-end gap-4">
                                <div class="w-1/3">
                                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                                    <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $produit->stocks }}" 
                                           class="w-full rounded-lg border-gray-300 border p-3 focus:ring-indigo-500 focus:border-indigo-500 text-center font-bold text-lg">
                                </div>

                                <button type="submit" id="btn-add" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center justify-center">
                                    <i class="fas fa-cart-plus mr-2"></i> Ajouter au panier
                                </button>
                            </div>

                            <p id="stock-error" class="text-red-500 text-sm mt-3 hidden font-medium">
                                <i class="fas fa-exclamation-circle mr-1"></i> 
                                Désolé, il n'y a que {{ $produit->stocks }} articles en stock.
                            </p>
                        </form>
                    @else
                        <div>
                            <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-lg cursor-not-allowed">
                                Indisponible pour le moment
                            </button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const stockError = document.getElementById('stock-error');
            const btnAdd = document.getElementById('btn-add');
            
            const maxStock = {{ $produit->stocks }};

            if(quantityInput) {
                quantityInput.addEventListener('input', function() {
                    let val = parseInt(this.value);

                    if (val < 1 || isNaN(val)) {
                        if(val < 1) this.value = 1; 
                    }

                    if (val > maxStock) {
                        stockError.classList.remove('hidden'); 
                        btnAdd.classList.add('opacity-50', 'cursor-not-allowed');
                        btnAdd.disabled = true; 
                    } else {
                        stockError.classList.add('hidden'); 
                        btnAdd.classList.remove('opacity-50', 'cursor-not-allowed');
                        btnAdd.disabled = false; 
                    }
                });
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
           
           {{-- Badge du nombre d'articles --}}
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