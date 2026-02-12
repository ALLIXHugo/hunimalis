<!DOCTYPE html>
<html lang="fr" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Employé | Hunimalis Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { 500: '#7c3aed', 600: '#6d28d9', 900: '#2e2e48' } }
                }
            }
        }
    </script>
    <style>
        .form-label { font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.375rem; display: block; }
        .form-input { 
            width: 100%; border: 1px solid #d1d5db; border-radius: 0.375rem; padding: 0.625rem 0.875rem; 
            font-size: 0.875rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; 
        }
        .form-input:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1); }
        .form-input.error { border-color: #ef4444; background-color: #fef2f2; }
        .error-msg { font-size: 0.75rem; color: #ef4444; margin-top: 0.25rem; font-weight: 500; }
    </style>
</head>
<body class="h-full flex flex-col antialiased text-gray-600 bg-gray-50">

    <div class="bg-brand-900 text-white py-4 shadow-md">
        <div class="max-w-3xl mx-auto px-4 flex items-center gap-3">
            <div class="bg-brand-500 font-bold rounded p-1 text-xl">H</div>
            <h1 class="font-bold text-lg">Gestion des Employés</h1>
        </div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
            
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Ajouter un collaborateur</h2>
                    <a href="#" class="text-sm text-gray-500 hover:text-brand-600 flex items-center gap-1"><i class="fa-solid fa-arrow-left"></i> Retour</a>
                </div>

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-start">
                        <i class="fa-solid fa-circle-check mt-1 mr-3"></i>
                        <div><p class="font-bold">Succès</p><p class="text-sm">{{ session('success') }}</p></div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-start">
                        <i class="fa-solid fa-triangle-exclamation mt-1 mr-3"></i>
                        <div><p class="font-bold">Erreur</p><p class="text-sm">{{ session('error') }}</p></div>
                    </div>
                @endif

                @if($errors->has('contact'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                        <p class="font-bold"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Attention</p>
                        <p class="text-sm">{{ $errors->first('contact') }}</p>
                    </div>
                @endif

                <form action="{{ route('employes.store') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <div class="mb-6">
                        <h3 class="text-sm uppercase tracking-wide text-gray-500 font-bold border-b pb-2 mb-4">Informations Personnelles</h3>
                        
                        <div class="mb-4">
                            <label class="form-label">Civilité *</label>
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="civiliteemploye" value="homme" {{ old('civiliteemploye') == 'homme' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                    <span>Monsieur</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="civiliteemploye" value="femme" {{ old('civiliteemploye') == 'femme' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                    <span>Madame</span>
                                </label>
                            </div>
                            @error('civiliteemploye') <p class="error-msg">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label" for="nom">Nom *</label>
                                <input type="text" name="nom" id="nom" value="{{ old('nom') }}" 
                                       class="form-input @error('nom') error @enderror" placeholder="Ex: Dupont">
                                @error('nom') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label" for="prenom">Prénom *</label>
                                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" 
                                       class="form-input @error('prenom') error @enderror" placeholder="Ex: Marie">
                                @error('prenom') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label" for="mail">Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-envelope"></i></span>
                                    <input type="email" name="mail" id="mail" value="{{ old('mail') }}" 
                                           class="form-input pl-10 @error('mail') error @enderror" placeholder="exemple@email.com">
                                </div>
                                @error('mail') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label" for="tel">Téléphone</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-phone"></i></span>
                                    <input type="text" name="tel" id="tel" value="{{ old('tel') }}" 
                                           class="form-input pl-10 @error('tel') error @enderror" placeholder="06 12 34 56 78">
                                </div>
                                @error('tel') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-8">
                        <h3 class="font-bold text-gray-700 mb-4 border-b pb-2"><i class="fa-solid fa-briefcase mr-2"></i>Poste & Accès</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label" for="idposte">Poste Occupé *</label>
                                <select name="idposte" id="idposte" class="form-input bg-white @error('idposte') error @enderror">
                                    <option value="">-- Sélectionner un poste --</option>
                                    @if(isset($postes))
                                        @foreach($postes as $poste)
                                            <option value="{{ $poste->idposte }}" {{ old('idposte') == $poste->idposte ? 'selected' : '' }}>
                                                {{ $poste->libelleposte ?? 'Poste #'.$poste->idposte }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('idposte') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="form-label">Accès Logiciel *</label>
                                <div class="flex items-center gap-4 mt-2">
                                    <label class="flex items-center gap-2 cursor-pointer border p-2 rounded bg-white w-full hover:border-brand-500 transition">
                                        <input type="radio" name="accesutilisateur" value="true" {{ old('accesutilisateur') == 'true' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                        <span class="text-sm font-medium"><i class="fa-solid fa-check text-green-500 mr-1"></i> Oui</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer border p-2 rounded bg-white w-full hover:border-red-500 transition">
                                        <input type="radio" name="accesutilisateur" value="false" {{ old('accesutilisateur') == 'false' ? 'checked' : '' }} class="text-brand-600 focus:ring-brand-500">
                                        <span class="text-sm font-medium"><i class="fa-solid fa-xmark text-red-500 mr-1"></i> Non</span>
                                    </label>
                                </div>
                                @error('accesutilisateur') <p class="error-msg">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <button type="reset" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-gray-100 transition">
                            Réinitialiser
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-brand-600 rounded-lg hover:bg-brand-500 focus:ring-4 focus:outline-none focus:ring-brand-100 shadow-md transition transform hover:-translate-y-0.5 flex items-center">
                            <i class="fa-solid fa-user-plus mr-2"></i> Créer l'employé
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</body>
</html>