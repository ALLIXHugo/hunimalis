<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hunimalis - Dev</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="p-10 bg-gray-100" x-data="{ openModal: false, mode: 'login' }">


    <div class="mb-10">
        <h1 class="text-2xl font-bold mb-4">Accueil (Version Dev)</h1>
        <div class="flex space-x-4">
            <button 
                @click="openModal = true; mode = 'login'"
                class="px-6 py-3 bg-white border border-gray-400 rounded shadow hover:bg-gray-50">
                Se connecter
            </button>

            <button 
                @click="openModal = true; mode = 'register'"
                class="px-6 py-3 bg-gray-800 text-white rounded shadow hover:bg-gray-700">
                S'inscrire
            </button>
        </div>
    </div>


    <div class="bg-white p-6 rounded border border-gray-300">
        <h2 class="text-lg font-bold mb-4 border-b pb-2">Liens de développement</h2>
        <div class="grid grid-cols-2 gap-8">
            
            <div>
                <h3 class="font-bold text-emerald-600 mb-2">Côté Pro</h3>
                <ul class="list-disc ml-5 space-y-1">
                    <li><a href="{{ route('produit.index') }}" class="text-blue-600 hover:underline">HU 18.19.20 Liste des produits</a></li>
                    <li><a href="{{ route('animal.index') }}" class="text-blue-600 hover:underline">2. Liste des animaux</a></li>
                    <li><a href="{{ route('prestation.categories.index') }}" class="text-blue-600 hover:underline">14. Liste des categories</a></li>
                    <br>

                    <li><a href="{{ route('professionnel.create') }}" class="text-blue-600 hover:underline">Créer un compte pro</a></li>
                    <li><a href="{{ route('professionnel.abonnement') }}" class="text-blue-600 hover:underline">Gérer l'abonnement</a></li>
                    <li><a href="{{ route('planning.index') }}" class="text-blue-600 hover:underline">Voir les plannings</a></li>
                    <li><a href="{{ route('rdv.employe.planning') }}" class="text-blue-600 hover:underline">Assigner un rendez-vous</a></li>
                    <li><a href="{{ route('rdv.client.index') }}" class="text-blue-600 hover:underline">Voir les rendez-vous</a></li>
                    <li><a href="{{ route('employes.create') }}" class="text-blue-600 hover:underline">Créer un employé</a></li>
                    <li><a href="{{ route('planning-pro.index') }}" class="text-blue-600 hover:underline">Gestion planning pro</a></li>
                    <li><a href="{{ route('postes.create') }}" class="text-blue-600 hover:underline">Ajouter un poste</a></li>
                    <li><a href="{{ route('pro.validation.create') }}" class="text-blue-600 hover:underline">Demande Validation (pro)</a></li>
                    <li><a href="{{ route('client.create') }}" class="text-blue-600 hover:underline">Créer un client (Magasin)</a></li>
                    <li><a href="{{ route('prestation.index') }}" class="text-blue-600 hover:underline">Liste des prestations</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-blue-600 mb-2">Côté Client</h3>
                <ul class="list-disc ml-5 space-y-1">
                    <li><a href="{{ route('register.form') }}" class="text-blue-600 hover:underline">Inscription client (Web)</a></li>
                    <li><a href=""class="text-blue-600 hover:underline">Recherche produit</a></li>
                    <li><a href="{{ route('client.store') }}" class="text-gray-400 text-sm">(POST Store Client)</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-red-600 mb-2">Côté Admin</h3>
                <ul class="list-disc ml-5 space-y-1">
                <li><a href="{{ route('rdv.validation.index') }}" class="text-blue-600 hover:underline">Acceptation Validation</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-purple-600 mb-2">Côté Secrétaire</h3>
                <ul class="list-disc ml-5 space-y-1">
                    <li>
                        <a href="{{ route('rdv.employe.planning') }}" class="text-blue-600 hover:underline">
                            <i class="fas fa-calendar-alt mr-1"></i> 24. Planning Équipe
                        </a>
                    </li>                
                </ul>
            </div>
        </div>
    </div>


    <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md">
            <h3 class="text-xl font-bold text-center mb-6">Je suis un...</h3>
            
            <div class="flex justify-center gap-6 mb-6">
                <a :href="mode === 'login' ? '{{ route('login') }}' : '{{ route('register.form') }}'" 
                   class="flex flex-col items-center justify-center w-32 h-32 border-2 border-gray-200 rounded hover:border-black hover:bg-gray-50 cursor-pointer">
                    <span class="font-bold">Particulier</span>
                </a>

                <a :href="mode === 'login' ? '{{ route('login') }}' : '{{ route('professionnel.create') }}'"
                   class="flex flex-col items-center justify-center w-32 h-32 border-2 border-gray-200 rounded hover:border-black hover:bg-gray-50 cursor-pointer">
                    <span class="font-bold">Professionnel</span>
                </a>
            </div>

            <button @click="openModal = false" class="w-full py-2 bg-gray-200 rounded hover:bg-gray-300">Annuler</button>
        </div>
    </div>

</body>
</html>