<!DOCTYPE html>
<html lang="fr" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres | Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        hunimalis: { DEFAULT: '#00C497', hover: '#00a07b' }, 
                        dog: '#F97316' 
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .form-input:focus { border-color: #00C497; outline: none; ring: 1px; --tw-ring-color: #00C497; box-shadow: 0 0 0 1px #00C497; }
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
                    <div class="shrink-0 flex justify-center mb-3">
                        @if(!empty($personne->avatar))
                            <img src="{{ asset('storage/' . $personne->avatar) }}" class="w-10 h-10 rounded-full shadow-sm border-2 border-white object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" class="w-10 h-10 rounded-full shadow-sm border-2 border-white">
                        @endif
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
                                    <img src="{{ asset('storage/' . $personne->avatar) }}" 
                                         alt="Avatar" 
                                         class="w-20 h-20 rounded-full shadow-sm border-2 border-white object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $personne->prenom }}+{{ $personne->nom }}&background=00C497&color=fff&bold=true" 
                                         class="w-20 h-20 rounded-full shadow-sm border-2 border-white">
                                @endif
                            </div>

                            <p class="text-sm text-gray-500 font-medium break-all">
                                {{ $personne->mail ?? 'email@exemple.com' }}
                            </p>
                        </div>

                        <nav class="space-y-1">
                            <a href="{{ route('clients.dashboard') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors" >
                                <div class="flex items-center gap-3">
                                    <i class="fa-regular fa-user w-5 text-center text-gray-600"></i> Mon profil
                                </div>
                            </a>
                            <a href="{{ route('clients.animals') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-solid fa-paw w-5 text-center"></i> Mes animaux
                                </div>
                                <span class="bg-gray-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $nbAnimaux ?? 0 }}</span>
                            </a>
                            <a href="{{ route('rdv.client.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-regular fa-calendar w-5 text-center"></i> Rendez-vous
                                </div>
                            </a>
                            <a href="{{ route('client.paiements.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded font-medium transition-colors">
                                <div class="flex items-center gap-3">
                                    <i class="fa-regular fa-credit-card w-5 text-center"></i> Paiement
                                </div>
                            </a>
                            <a class="flex items-center gap-3 px-4 py-2.5 bg-gray-200 text-gray-900 rounded font-medium transition-colors">
                                <i class="fa-solid fa-gear w-5 text-center"></i> Paramètres
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

                <main class="lg:col-span-9">
                    
                    @if(session('success'))
                        <div class="bg-green-50 text-green-700 p-4 rounded-lg text-sm mb-6 border border-green-100 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm mb-6 border border-red-100">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h1 class="text-xl font-bold text-gray-900">Modifier votre mot de passe</h1>
                            <p class="mt-1 text-sm text-gray-500">
                                Votre adresse mail actuelle est <span class="font-medium text-gray-900">{{ $personne->mail }}</span>
                            </p>
                        </div>

                        <div class="p-6">
                            <form action="{{ route('client.password.update') }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Ancien mot de passe*</label>
                                    <input type="password" 
                                           name="current_password" 
                                           id="current_password" 
                                           placeholder="Ancien mot de passe*"
                                           class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-hunimalis focus:border-hunimalis sm:text-sm">
                                </div>

                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nouveau mot de passe</label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="form-input block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-hunimalis focus:border-hunimalis sm:text-sm pr-10">
                                        
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer" onclick="togglePassword()">
                                            <i class="fa-regular fa-eye text-gray-400 hover:text-gray-600" id="eye-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="bg-[#1e293b] hover:bg-[#0f172a] text-white px-6 py-2.5 rounded text-sm font-bold transition-colors shadow-sm">
                                        Modifier
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>