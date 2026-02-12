<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Panier - Hunimalis Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 font-sans text-gray-800">

@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-cash-register text-indigo-600 mr-3"></i> Caisse / Commande
        </h1>
        <a href="{{ route('produit.index') }}" class="text-indigo-600 hover:underline font-medium">
            <i class="fas fa-arrow-left mr-1"></i> Continuer les achats
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('panier_pro') && count(session('panier_pro')) > 0)
        <div class="bg-white shadow-lg rounded-lg overflow-visible border border-gray-200">
            
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Produit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Quantité</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Prix Unit. HT</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Total Ligne</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(session('panier_pro') as $id => $item)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if(isset($item['photo']) && $item['photo'])
                                        <img class="h-12 w-12 rounded object-cover mr-4 border shadow-sm" src="{{ asset('storage/'.$item['photo']) }}" alt="">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-100 flex items-center justify-center mr-4 text-gray-400 border">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-bold text-gray-900">{{ $item['nom'] }}</div>
                                        <div class="text-xs text-gray-500">Stock max: {{ $item['stock_max'] }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <form action="{{ route('panier.modifier', $id) }}" method="POST" class="flex justify-center" x-data>
                                    @csrf
                                    @method('PATCH')
                                    
                                    
                                    <input type="number" 
                                           name="quantity" 
                                           value="{{ $item['quantite'] }}" 
                                           min="1" 
                                           max="{{ $item['stock_max'] }}" 
                                           @change="$el.form.submit()"
                                           @input.debounce.500ms="$el.form.submit()"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-20 p-2.5 text-center font-bold" 
                                           required>
                                </form>
                            </td>

                            <td class="px-6 py-4 text-right text-sm text-gray-600 font-mono">
                                {{ number_format($item['prix'], 2, ',', ' ') }} €
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 font-mono">
                                {{ number_format($item['prix'] * $item['quantite'], 2, ',', ' ') }} €
                            </td>

                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('panier.supprimer', $id) }}" method="POST" class="inline-block">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition p-2 hover:bg-red-50 rounded-full" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-bold uppercase text-gray-600 text-sm">Total Commande HT</td>
                        <td class="px-6 py-4 text-right font-extrabold text-xl text-indigo-700 font-mono">{{ number_format($total, 2, ',', ' ') }} €</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

            <div class="p-6 bg-gray-100 border-t border-gray-200">
                
                <form action="{{ route('panier.valider') }}" method="POST" class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    @csrf
                    
                    <div class="w-full md:w-1/2 relative" 
                         x-data="{ 
                            query: '', 
                            selectedId: '', 
                            selectedName: '',
                            open: false,
                            clients: {{ json_encode($clientsData) }},
                            
                            get filteredClients() {
                                if (this.query === '') return this.clients;
                                return this.clients.filter(client => {
                                    return client.label.toLowerCase().includes(this.query.toLowerCase());
                                });
                            },

                            selectClient(id, name) {
                                this.selectedId = id;
                                this.selectedName = name;
                                this.query = name;
                                this.open = false;
                            }
                         }">

                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fa-solid fa-user-tag mr-1"></i> Rechercher un client <span class="text-red-500">*</span>
                        </label>

                        <input type="hidden" name="idclient" x-model="selectedId" required>

                        <div class="relative">
                            <input type="text" 
                                   x-model="query"
                                   @input="open = true; selectedId = ''" 
                                   @click="open = true"
                                   @click.away="open = false"
                                   placeholder="Tapez un nom..."
                                   class="w-full pl-10 pr-4 py-3 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   autocomplete="off">
                            
                            <div class="absolute inset-y-0 left-0 flex items-center px-3 text-gray-500">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>

                            <div x-show="selectedId !== ''" class="absolute inset-y-0 right-0 flex items-center px-3 text-green-500" x-cloak>
                                <i class="fa-solid fa-check-circle"></i>
                            </div>
                        </div>

                        <div x-show="open && filteredClients.length > 0" 
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                             x-cloak
                             x-transition.opacity>
                            <ul>
                                <template x-for="client in filteredClients" :key="client.id">
                                    <li @click="selectClient(client.id, client.label)" 
                                        class="px-4 py-2 hover:bg-indigo-50 cursor-pointer text-sm text-gray-700 transition">
                                        <span x-text="client.label"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        <div x-show="open && filteredClients.length === 0" 
                             class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-3 text-sm text-gray-500 text-center"
                             x-cloak>
                            Aucun client trouvé.
                        </div>

                        <p class="text-xs text-red-500 mt-2 ml-1" x-show="selectedId === ''">
                            * Sélection obligatoire pour valider la vente.
                        </p>
                    </div>

                    <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                        <a href="{{ route('panier.vider') }}" class="text-red-600 hover:text-red-800 text-sm font-medium hover:underline flex items-center px-4 py-2" onclick="return confirm('Voulez-vous vraiment vider tout le panier ?')">
                            <i class="fas fa-trash mr-2"></i> Tout vider
                        </a>
                        
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow-lg transition transform hover:-translate-y-0.5 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> Encaisser
                        </button>
                    </div>

                </form>
            </div>

        </div>
    @else
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="bg-gray-100 rounded-full h-24 w-24 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-shopping-basket text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-700 mb-2">Votre panier est vide</h2>
            <p class="text-gray-500 mb-8">Vous n'avez pas encore ajouté de produits à la commande.</p>
            <a href="{{ route('produit.index') }}" class="inline-flex items-center bg-indigo-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow">
                <i class="fa-solid fa-list mr-2"></i> Liste des produits
            </a>
        </div>
    @endif
</div>
@endsection
</body>
</html>