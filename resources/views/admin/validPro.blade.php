<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Validation Comptes Pros</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-50 font-sans">

    <div class="min-h-screen flex">
        <div class="w-64 bg-slate-800 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center font-bold text-xl border-b border-slate-700">
                HUNIMALIS ADMIN
            </div>
            <nav class="flex-1 py-6">
                <a href="{{ route('rdv.validation.index') }}" class="block py-3 px-6 hover:bg-slate-700 text-slate-300">
                    <i class="fas fa-calendar-check w-6"></i> RDV Validation
                </a>
                <a href="{{ route('admin.validPro.index') }}" class="block py-3 px-6 bg-slate-700 text-white border-r-4 border-indigo-500">
                    <i class="fas fa-user-shield w-6"></i> Comptes en attente
                </a>
                <a href="{{ route('home') }}" class="block py-3 px-6 hover:bg-slate-700 text-slate-300 mt-10">
                    <i class="fas fa-arrow-left w-6"></i> Retour au site
                </a>
            </nav>
        </div>

        <div class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Validation des Comptes</h1>
            <p class="text-gray-500 mb-8">Liste des professionnels ayant créé un compte (Statut 2).</p>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('warning'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded">
                    <i class="fas fa-times-circle mr-2"></i> {{ session('warning') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Professionnel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Établissement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacts</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Inscription</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($demandes as $pro)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-gray-900">{{ $pro->nompro }} {{ $pro->prenompro }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $pro->idpro }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $pro->libelleetablissement }}</div>
                                <div class="text-xs text-gray-500">{{ $pro->libelletypeetablissement }}</div>
                                <div class="text-xs text-gray-400 mt-1">SIRET: {{ $pro->siret }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600"><i class="fas fa-envelope w-4"></i> {{ $pro->melpro }}</div>
                                <div class="text-sm text-gray-600"><i class="fas fa-phone w-4"></i> {{ $pro->telpro }}</div>
                                <div class="text-sm text-gray-600"><i class="fas fa-map-marker-alt w-4"></i> {{ $pro->ville }} ({{ $pro->cp }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($pro->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.pro.accepter', $pro->idpro) }}" method="POST" onsubmit="return confirm('Confirmer la validation du compte ?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1 rounded-lg text-sm font-bold transition flex items-center gap-1">
                                            <i class="fas fa-check"></i> Valider
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.pro.refuser', $pro->idpro) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment refuser ce compte ?');">
                                        @csrf
                                        @method('PUT') <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-lg text-sm font-bold transition flex items-center gap-1">
                                            <i class="fas fa-ban"></i> Refuser
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-clipboard-check text-4xl mb-3 text-gray-300"></i>
                                    <p>Aucune inscription en attente.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>