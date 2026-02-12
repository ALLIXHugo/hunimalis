<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Demandes Professionnels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Demandes d'inscription en attente</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('warning') }}
            </div>
        @endif

        @if($demandes->isEmpty())
            <div class="bg-white p-6 rounded shadow text-center text-gray-500">
                <i class="fas fa-check-circle text-4xl mb-3 text-green-500"></i>
                <p>Aucune demande en attente pour le moment.</p>
            </div>
        @else
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Professionnel
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Établissement
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Détails
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $pro)
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex items-center">
                                    <div class="ml-3">
                                        <p class="text-gray-900 whitespace-no-wrap font-bold">
                                            {{ $pro->nompro }} {{ $pro->prenompro }}
                                        </p>
                                        <p class="text-gray-500 whitespace-no-wrap text-xs">
                                            {{ $pro->melpro }}
                                        </p>
                                        <p class="text-gray-500 whitespace-no-wrap text-xs">
                                            {{ $pro->telpro }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ $pro->libelleetablissement }}</p>
                                <span class="relative inline-block px-3 py-1 font-semibold text-blue-900 leading-tight">
                                    <span aria-hidden class="absolute inset-0 bg-blue-200 opacity-50 rounded-full"></span>
                                    <span class="relative text-xs">{{ $pro->libelletypeetablissement }}</span>
                                </span>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-600 text-xs">Siret: {{ $pro->siret }}</p>
                                <p class="text-gray-600 text-xs">{{ $pro->ville }} ({{ $pro->cp }})</p>
                                <p class="text-gray-600 text-xs">Pays: {{ $pro->pays }}</p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <form action="{{ route('admin.pro.accepter', $pro->idpro) }}" method="POST" onsubmit="return confirm('Valider ce compte ?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-3 rounded shadow transition" title="Accepter">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.pro.refuser', $pro->idpro) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette demande ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-3 rounded shadow transition" title="Refuser">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</body>
</html>