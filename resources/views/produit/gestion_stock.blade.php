@extends('layouts.app')

@section('content')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="min-h-screen bg-gray-100 py-8 font-sans">
    <div class="container mx-auto px-4 max-w-5xl">

        <div class="flex items-center justify-between mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">
                    <i class="fa-solid fa-boxes-stacked text-indigo-600 mr-2"></i>Stock Rapide
                </h1>
                <p class="text-gray-500 text-sm mt-1">Gérez les quantités facilement.</p>
            </div>
            <a href="{{ route('pro.dashboard') }}" class="px-5 py-2 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-700 transition">
                Retour
            </a>
        </div>

        <div class="space-y-4">
            @forelse($produits as $produit)
                @php
                    $isCritical = $produit->stocks <= $produit->seuilalerte;
                    $bgClass = $isCritical ? 'bg-red-50 border-red-200' : 'bg-white border-gray-200';
                @endphp

                <div class="flex flex-col md:flex-row items-center gap-6 p-4 rounded-2xl shadow-sm border {{ $bgClass }} transition hover:shadow-md">
                    
                    <div class="shrink-0 relative">
                        @if($produit->photo)
                            <img src="{{ asset('storage/'.$produit->photo) }}" 
                                 class="h-24 w-24 rounded-xl object-cover border border-gray-200 shadow-sm block bg-white"
                                 style="height: 96px; width: 96px; min-width: 96px;"> 
                        @else
                            <div class="h-24 w-24 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200"
                                 style="height: 96px; width: 96px; min-width: 96px;">
                                <i class="fa-solid fa-image text-3xl"></i>
                            </div>
                        @endif
                        
                        <span class="absolute -bottom-2 -right-2 bg-white px-2 py-1 rounded-md text-[10px] font-bold border border-gray-100 shadow text-indigo-600 uppercase">
                            {{ Str::limit($produit->libellecategoriepro, 10) }}
                        </span>
                    </div>

                    <div class="flex-1 w-full text-center md:text-left">
                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-1">
                            {{ $produit->nomproduit }}
                        </h3>
                        <div class="text-sm text-gray-500 mb-2">
                            @if($produit->numserie) <span class="bg-gray-100 px-2 py-0.5 rounded text-xs font-mono border">Ref: {{ $produit->numserie }}</span> @endif
                            @if($produit->numlot) <span class="ml-2">Lot: {{ $produit->numlot }}</span> @endif
                        </div>
                        <div class="font-bold text-indigo-600">
                            {{ number_format($produit->prixvente, 2) }} €
                        </div>
                    </div>

                    <div class="w-full md:w-auto flex justify-center">
                        @if($isCritical)
                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full animate-pulse border border-red-200">
                                <i class="fa-solid fa-circle-exclamation mr-1"></i> Critique ({{ $produit->stocks }})
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full border border-green-200">
                                <i class="fa-solid fa-check mr-1"></i> En Stock
                            </span>
                        @endif
                    </div>

                    <div class="w-full md:w-auto">
                        <form action="{{ route('produit.stock.update', $produit->idproduit) }}" method="POST" 
                              class="flex items-center justify-center bg-white p-1 rounded-xl border border-gray-300 shadow-sm">
                            @csrf
                            
                            <button type="button" onclick="adjustStock('stock-{{ $produit->idproduit }}', -1)" 
                                    class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-red-100 hover:text-red-600 transition font-bold text-lg">
                                -
                            </button>

                            <input type="number" 
                                   id="stock-{{ $produit->idproduit }}"
                                   name="nouveau_stock" 
                                   value="{{ $produit->stocks }}" 
                                   min="0" 
                                   class="w-16 text-center font-black text-xl text-gray-800 border-none focus:ring-0 p-0 appearance-none bg-transparent"
                                   onfocus="this.select()">

                            <button type="button" onclick="adjustStock('stock-{{ $produit->idproduit }}', 1)" 
                                    class="h-10 w-10 flex items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-green-100 hover:text-green-600 transition font-bold text-lg">
                                +
                            </button>

                            <button type="submit" class="ml-2 h-10 w-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow flex items-center justify-center transition">
                                <i class="fa-solid fa-floppy-disk"></i>
                            </button>
                        </form>
                    </div>

                </div>
            @empty
                <div class="text-center py-10 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400">Aucun produit trouvé.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    function adjustStock(id, amount) {
        let input = document.getElementById(id);
        let val = parseInt(input.value) || 0;
        let newVal = val + amount;
        if(newVal < 0) newVal = 0;
        input.value = newVal;
    }
</script>

<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
</style>
@endsection