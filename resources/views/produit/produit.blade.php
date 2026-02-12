<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white text-gray-800 font-sans">

@extends('layouts.app')

@section('content')

@php
    $totalValeurStock = 0;
    $totalVentePotentielle = 0;
@endphp

<div class="w-full mx-auto p-4">
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Liste des Produits</h1>
        
        <div class="flex gap-4">
            <a href="{{ route('pro.panier') }}" class="relative bg-white border border-indigo-600 text-indigo-600 px-4 py-2 rounded shadow hover:bg-indigo-50 transition font-bold flex items-center">
                <i class="fa-solid fa-cart-shopping mr-2"></i> Mon Panier
                @if(session('panier_pro'))
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-2 py-0.5 shadow-sm">
                        {{ count(session('panier_pro')) }}
                    </span>
                @endif
            </a>

            <a href="{{ route('produit.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition font-bold flex items-center">
                <span class="mr-2 text-xl">+</span> Ajouter un produit
            </a>
        </div>
    </div>

    <div class="overflow-x-auto border border-gray-200 rounded-sm">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-4 py-3 font-bold border-r">Nom</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r">Catégorie</th>
                    <th scope="col" class="px-4 py-3 font-bold text-center border-r">Stock</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r text-right">P. Achat</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r text-right">P. Vente HT</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r text-right">P. Vente TTC</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r text-center">Seuil</th>
                    <th scope="col" class="px-4 py-3 font-bold border-r text-right">Val. Stock</th>
                    <th scope="col" class="px-4 py-3 font-bold text-right border-r">Vente Pot.</th>
                    <th scope="col" class="px-4 py-3 font-bold text-center border-r">Actions</th>
                    <th scope="col" class="px-4 py-3 font-bold text-center bg-indigo-50 text-indigo-800">Ajout rapide</th>
                </tr>
            </thead>

            <tbody>
                @foreach($produits as $p)
                    @php
                        $prixTTC = $p->prixvente * 1.20; 
                        $valeurStockLigne = $p->stocks * $p->prixachat;
                        $ventePotentielleLigne = $p->stocks * $p->prixvente;
                        $totalValeurStock += $valeurStockLigne;
                        $totalVentePotentielle += $ventePotentielleLigne;
                    @endphp

                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-4 font-medium border-r">
                            <a href="{{ route('produit.show', $p->idproduit) }}" class="text-indigo-600 hover:underline font-bold">{{ $p->nomproduit }}</a>
                        </td>
                        <td class="px-4 py-4 border-r text-gray-500">{{ $p->libellecategoriepro }}</td>
                        <td class="px-4 py-4 text-center border-r font-bold {{ $p->stocks <= $p->seuilalerte ? 'text-red-600' : 'text-gray-700' }}">{{ $p->stocks }}</td>
                        <td class="px-4 py-4 border-r text-right">{{ number_format($p->prixachat, 2, ',', ' ') }}€</td>
                        <td class="px-4 py-4 border-r text-right">{{ number_format($p->prixvente, 2, ',', ' ') }}€</td>
                        <td class="px-4 py-4 border-r text-right">{{ number_format($prixTTC, 2, ',', ' ') }}€</td>
                        <td class="px-4 py-4 border-r text-center text-gray-500">{{ $p->seuilalerte }}</td>
                        <td class="px-4 py-4 border-r text-right">{{ number_format($valeurStockLigne, 2, ',', ' ') }}€</td>
                        <td class="px-4 py-4 border-r text-right">{{ number_format($ventePotentielleLigne, 2, ',', ' ') }}€</td>

                        <td class="px-4 py-4 text-center border-r">
                            <a href="{{ route('produit.edit', $p->idproduit) }}" class="text-blue-500 hover:text-blue-700 mr-2"><i class="fas fa-edit"></i></a>
                            <form action="..." method="POST" class="inline">@csrf @method('DELETE')<button class="text-red-500"><i class="fas fa-trash-alt"></i></button></form> 
                            
                        </td>

                        <td class="px-4 py-2 text-center bg-indigo-50">
                            @if($p->stocks > 0)
                                <form action="{{ route('panier.ajouter', $p->idproduit) }}" method="POST" class="flex items-center justify-center space-x-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $p->stocks }}" 
                                           class="w-14 text-center border border-gray-300 rounded px-1 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-1.5 rounded shadow transition" title="Ajouter au panier">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </form>
                            @else
                                <span class="text-xs font-bold text-red-500 uppercase">Rupture</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
            <tfoot class="bg-gray-100 font-bold text-gray-700">
                <tr>
                    <td colspan="7" class="px-4 py-3 text-right border-r border-t border-gray-300">TOTAUX :</td>
                    <td class="px-4 py-3 border-r border-t border-gray-300">{{ number_format($totalValeurStock, 2, ',', ' ') }}€</td>
                    <td class="px-4 py-3 border-t border-gray-300">{{ number_format($totalVentePotentielle, 2, ',', ' ') }}€</td>
                    <td colspan="2" class="border-t border-gray-300"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
</body>
</html>