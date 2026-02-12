<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Produit - {{ $produit->nomproduit }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto p-6">
    
    <a href="{{ route('produit.index') }}" class="inline-flex items-center text-gray-600 hover:text-indigo-600 mb-6 transition font-medium text-sm">
        <i class="fa-solid fa-arrow-left mr-2"></i> Retour au catalogue
    </a>

    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        
        <div class="bg-indigo-900 text-white px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl md:text-2xl font-bold flex items-center">
                <i class="fa-solid fa-box mr-3 text-indigo-300"></i> {{ $produit->nomproduit }}
            </h1>
            <span class="bg-indigo-800 text-indigo-100 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide border border-indigo-700">
                {{ $produit->libellecategoriepro }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12">
            
            <div class="md:col-span-4 bg-gray-50 p-6 flex flex-col border-b md:border-b-0 md:border-r border-gray-200">
                
                <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm mb-6">
                    @if($produit->photo)
                        <img src="{{ asset('storage/' . $produit->photo) }}" alt="{{ $produit->nomproduit }}" 
                             class="w-full h-56 object-contain rounded">
                    @else
                        <div class="w-full h-56 bg-gray-100 rounded flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-solid fa-image text-5xl mb-2"></i>
                            <span class="text-sm">Aucune photo</span>
                        </div>
                    @endif
                </div>
                
                <div class="mb-6">
                    <a href="{{ route('produit.edit', $produit->idproduit) }}" class="w-full flex items-center justify-center bg-white border border-gray-300 hover:border-indigo-500 text-gray-700 hover:text-indigo-600 font-bold py-2 px-4 rounded shadow-sm transition">
                        <i class="fa-solid fa-pen mr-2"></i> Modifier le produit
                    </a>
                </div>

                <div class="bg-white p-4 rounded-lg border border-indigo-100 shadow-sm mt-auto">
                    <h3 class="text-xs font-bold text-gray-500 uppercase mb-3 text-center">Ajouter à une commande</h3>
                    
                    @if($produit->stocks > 0)
                        <form action="{{ route('produit.addToOrder', $produit->idproduit) }}" method="POST">
                            @csrf
                            <div class="flex gap-2">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $produit->stocks }}" 
                                       class="w-16 text-center border border-gray-300 rounded font-bold text-lg focus:ring-indigo-500 focus:border-indigo-500"
                                       title="Quantité">
                                
                                <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow transition flex items-center justify-center text-sm">
                                    <i class="fas fa-cart-plus mr-2"></i> Ajouter
                                </button>
                            </div>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-2 px-4 rounded cursor-not-allowed text-sm">
                            Rupture de stock
                        </button>
                    @endif
                </div>

            </div>

            <div class="md:col-span-8 p-6 lg:p-8">
                
                <div class="mb-8">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fa-solid fa-warehouse mr-2"></i> État du stock
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-gray-500 uppercase">En stock</p>
                            <p class="text-2xl font-bold {{ $produit->stocks <= $produit->seuilalerte ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $produit->stocks }}
                            </p>
                        </div>
                        <div class="text-center sm:text-left">
                            <p class="text-xs text-gray-500 uppercase">Seuil Alerte</p>
                            <p class="text-2xl font-bold text-gray-600">{{ $produit->seuilalerte }}</p>
                        </div>
                        <div class="col-span-2 sm:col-span-1 flex items-center justify-center sm:justify-end">
                            @if($produit->stocks <= $produit->seuilalerte)
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-bold flex items-center">
                                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Stock Faible
                                </span>
                            @else
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold flex items-center">
                                    <i class="fa-solid fa-check mr-2"></i> Stock OK
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center">
                        <i class="fa-solid fa-align-left mr-2"></i> Description
                    </h2>
                    <div class="bg-white p-4 border border-gray-100 rounded-lg text-gray-600 text-sm leading-relaxed">
                        @if(!empty($produit->descriptionprod))
                            {{ $produit->descriptionprod }}
                        @else
                            <span class="italic text-gray-400">Aucune description disponible.</span>
                        @endif
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4 flex items-center">
                        <i class="fa-solid fa-tags mr-2"></i> Tarification & Rentabilité
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 border rounded bg-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-1">
                                <i class="fa-solid fa-arrow-down text-gray-100 group-hover:text-red-100 text-3xl"></i>
                            </div>
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Prix Achat HT</span>
                            <span class="text-lg font-mono font-bold text-gray-700">{{ number_format($produit->prixachat, 2, ',', ' ') }} €</span>
                        </div>

                        <div class="p-3 border rounded bg-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-1">
                                <i class="fa-solid fa-arrow-up text-gray-100 group-hover:text-green-100 text-3xl"></i>
                            </div>
                            <span class="block text-xs text-gray-500 uppercase font-semibold">Prix Vente HT</span>
                            <span class="text-lg font-mono font-bold text-indigo-700">{{ number_format($produit->prixvente, 2, ',', ' ') }} €</span>
                        </div>
                        
                        <div class="col-span-2 bg-indigo-50 border border-indigo-100 rounded p-4 flex flex-col sm:flex-row justify-between items-center">
                            <div class="mb-2 sm:mb-0">
                                <span class="text-xs text-indigo-600 font-bold uppercase block">Prix TTC Client (TVA 20%)</span>
                                <span class="text-2xl font-bold text-indigo-900">{{ number_format($produit->prixvente * 1.20, 2, ',', ' ') }} €</span>
                            </div>
                            <div class="text-center sm:text-right border-t sm:border-t-0 sm:border-l border-indigo-200 pt-2 sm:pt-0 sm:pl-6 w-full sm:w-auto">
                                <span class="text-xs text-green-600 font-bold uppercase block">Marge Brute</span>
                                <span class="text-xl font-bold text-green-700">
                                    + {{ number_format($produit->prixvente - $produit->prixachat, 2, ',', ' ') }} €
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fa-solid fa-barcode mr-2"></i> Traçabilité
                    </h2>
                    <div class="flex gap-4 text-sm">
                        <span class="bg-gray-100 px-3 py-1 rounded border border-gray-200 text-gray-600 font-mono">
                            <span class="font-bold text-gray-400 mr-1">S/N:</span> {{ $produit->numserie ?? 'N/A' }}
                        </span>
                        <span class="bg-gray-100 px-3 py-1 rounded border border-gray-200 text-gray-600 font-mono">
                            <span class="font-bold text-gray-400 mr-1">LOT:</span> {{ $produit->numlot ?? 'N/A' }}
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

</body>
</html>