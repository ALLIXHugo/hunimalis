<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Paiements | Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-gray-600 bg-white">

    <div class="min-h-full flex flex-col">
        
        <nav class="bg-white border-b border-gray-200 sticky top-0 z-30 h-16 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex justify-between items-center h-full">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 hover:opacity-80 transition">
                            <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <a href="{{ route('client.boutique') }}" class="text-sm font-medium hover:text-[#00C497] transition-colors">Boutique</a>
                        <div class="shrink-0 flex justify-center">
                            @if(!empty($personne->avatar))
                                <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm border-2 border-white object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                            @endif
                        </div>
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
                                    <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full shadow-sm border-2 border-white object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-20 h-20 rounded-full shadow-sm border-2 border-white">
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 font-medium break-all">
                                {{ $personne->mail }}
                            </p>
                        </div>

                        <nav class="space-y-1">
                            <a href="{{ route('clients.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-regular fa-user w-5 text-center text-gray-600"></i> Mon profil
                            </a>
                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux</div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux ?? 0 }}</span>
                            </a>
                            <a href="{{ route('rdv.client.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-regular fa-calendar w-5 text-center"></i> Rendez-vous</div>
                            </a>
                            <a href="{{ route('client.paiements.index') }}" class="flex items-center justify-between px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-regular fa-credit-card w-5 text-center"></i> Paiement</div>
                            </a>
                            <a href="{{ route('client.settings') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3"><i class="fa-solid fa-gear"></i> Paramètres</div>
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

                <main class="lg:col-span-9 space-y-8">
                    
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900">Moyens de paiement</h2>
                        </div>
                        
                        <div class="p-6">
                            @if($cartes->isEmpty())
                                <div class="bg-gray-50 rounded-lg p-6 flex flex-col md:flex-row items-center justify-between border border-gray-100">
                                    <div class="mb-4 md:mb-0 md:mr-6">
                                        <h3 class="font-bold text-gray-900 mb-2">Il semblerait que vous n'ayez encore ajouté aucun moyen de paiement !</h3>
                                        <p class="text-gray-600 text-sm leading-relaxed">
                                            Une fois ajoutés lors d'un paiement pour un établissement ou sur la boutique, ils se retrouveront ici pour faciliter vos futurs achats.
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-32 h-32 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <i class="fa-solid fa-mobile-screen-button text-5xl text-indigo-500"></i>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($cartes as $carte)
                                        <div class="border border-gray-200 rounded-lg p-4 flex items-center justify-between hover:shadow-md transition bg-white">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-8 bg-gray-50 rounded flex items-center justify-center text-gray-500 border border-gray-200">
                                                    @if(strtolower($carte->brand) == 'visa')
                                                        <i class="fa-brands fa-cc-visa text-xl text-blue-700"></i>
                                                    @elseif(strtolower($carte->brand) == 'mastercard')
                                                        <i class="fa-brands fa-cc-mastercard text-xl text-red-600"></i>
                                                    @elseif(strtolower($carte->brand) == 'amex')
                                                        <i class="fa-brands fa-cc-amex text-xl text-blue-500"></i>
                                                    @else
                                                        <i class="fa-regular fa-credit-card text-xl"></i>
                                                    @endif
                                                </div>
                                                
                                                <div>
                                                    <p class="font-bold text-gray-800 text-sm">•••• •••• •••• {{ $carte->last4 }}</p>
                                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                                        <span class="{{ $carte->exp_year < now()->year ? 'text-red-500 font-bold' : '' }}">
                                                            Exp: {{ str_pad($carte->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $carte->exp_year }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <form action="{{ route('client.carte.delete', $carte->id) }}" method="POST" onsubmit="return confirm('Supprimer cette carte ?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-gray-300 hover:text-red-500 transition p-2 rounded-full hover:bg-red-50" title="Supprimer">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900">Paiements & Factures</h2>
                        </div>
                        
                        <div class="p-6">
                            @if($historique->isEmpty())
                                <p class="text-gray-500 text-sm italic text-center py-4">Aucun historique de paiement disponible.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/50">
                                            <tr>
                                                <th class="px-4 py-3 rounded-l-lg">Date</th>
                                                <th class="px-4 py-3">Commande</th>
                                                <th class="px-4 py-3">Établissement</th>
                                                <th class="px-4 py-3">Montant</th>
                                                <th class="px-4 py-3 rounded-r-lg text-right">Facture</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($historique as $commande)
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="px-4 py-3 font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($commande->datecommande)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-gray-500">#{{ $commande->idcommande }}</td>
                                                    
                                                    <td class="px-4 py-3 text-gray-700">
                                                        @php
                                                            $nomEtablissement = 'Hunimalis Shop'; 
                                                            $styleBadge = 'bg-indigo-100 text-indigo-600'; 

                                                            // 1. Chercher via les produits
                                                            $proProduit = \Illuminate\Support\Facades\DB::table('quantite')
                                                                ->join('produit', 'quantite.idproduit', '=', 'produit.idproduit')
                                                                ->join('professionnel', 'produit.idpro', '=', 'professionnel.idpro')
                                                                ->where('quantite.idcommande', $commande->idcommande)
                                                                ->select('professionnel.libelleetablissement')
                                                                ->first();

                                                            if ($proProduit) {
                                                                $nomEtablissement = $proProduit->libelleetablissement;
                                                                $styleBadge = 'bg-emerald-100 text-emerald-600';
                                                            } 
                                                            // 2. Sinon, chercher via les prestations (RDV)
                                                            else {
                                                                $facture = \Illuminate\Support\Facades\DB::table('facture')
                                                                    ->where('idcommande', $commande->idcommande)
                                                                    ->first();

                                                                if ($facture) {
                                                                    $rdv = \Illuminate\Support\Facades\DB::table('appartient')
                                                                        ->join('rdv', 'appartient.idrdv', '=', 'rdv.idrdv')
                                                                        ->join('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
                                                                        ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
                                                                        ->join('professionnel', 'prestation.idpro', '=', 'professionnel.idpro')
                                                                        ->where('appartient.idfacture', $facture->idfacture)
                                                                        ->select('professionnel.libelleetablissement')
                                                                        ->first();

                                                                    if ($rdv) {
                                                                        $nomEtablissement = $rdv->libelleetablissement;
                                                                        $styleBadge = 'bg-emerald-100 text-emerald-600';
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        <div class="flex items-center gap-2">
                                                            <div class="h-6 w-6 rounded-full {{ $styleBadge }} flex items-center justify-center text-xs font-bold flex-shrink-0">
                                                                {{ substr($nomEtablissement, 0, 1) }}
                                                            </div>
                                                            <span class="truncate max-w-[180px] font-medium" title="{{ $nomEtablissement }}">
                                                                {{ $nomEtablissement }}
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="px-4 py-3 font-bold">{{ number_format($commande->total, 2, ',', ' ') }} €</td>
                                                    
                                                    <td class="px-4 py-3 text-right">
                                                        <a href="{{ route('facture.download', $commande->idfacture ?? $commande->idcommande) }}" 
                                                           class="text-red-600 hover:text-red-900 font-medium text-xs inline-flex items-center transition-colors"
                                                           title="Télécharger la facture">
                                                            <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                   
                                        <tfoot class="bg-gray-50 border-t-2 border-gray-200">
                                            <tr>
                                                <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-600 uppercase text-xs tracking-wider">
                                                    Total cumulé des dépenses :
                                                </td>
                                                
                                                <td class="px-4 py-4 font-extrabold text-indigo-700 text-base">
                                                    {{ number_format($historique->sum('total'), 2, ',', ' ') }} €
                                                </td>
                                                
                                                <td colspan="1" class="bg-gray-50"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>
</html>