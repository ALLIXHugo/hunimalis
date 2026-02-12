<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique - Hunimalis</title>
    <link rel="shortcut icon" href="{{ asset('img/favicon.webp') }}" type="image/x-icon">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/fuse.js@6.6.2"></script>
    
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .search-bar-container { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .search-divider { width: 1px; height: 40px; background-color: #e5e7eb; margin: 0 10px; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Succès !</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <nav class="bg-white border-b border-gray-200 h-16 flex items-center px-4 sm:px-8 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto w-full flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('img/logo.webp') }}" alt="HUNIMALIS" class="h-8 w-auto">
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('clients.dashboard') }}" class="flex items-center gap-2">
                        @if(!empty(Auth::user()->personne->avatar))
                            <img src="{{ asset('storage/' . Auth::user()->personne->avatar) }}" class="w-8 h-8 rounded-full border border-gray-200 object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-900 hover:text-[#2b90c7] transition">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow" 
          x-data="boutiqueLogic(@js($produits), @js($categories))"
          x-init="initFilters()">

        <div class="bg-[#2b90c7] pt-12 pb-48 md:pb-32 px-4 relative z-20">
            <div class="max-w-7xl mx-auto text-center space-y-6">
                
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    <span x-text="filteredProducts.length"></span> articles trouvés
                </h1>

                <div class="bg-white rounded-lg p-2 flex flex-col lg:flex-row items-center search-bar-container w-full">
                    
                    <div class="flex-1 w-full lg:w-auto flex items-center px-4 py-2 hover:bg-gray-50 transition-colors rounded-md group">
                        <i class="fa-solid fa-tag text-gray-400 mr-3 text-lg group-hover:text-[#2b90c7] transition-colors"></i>
                        <div class="flex flex-col items-start w-full">
                            <label class="text-xs font-bold text-gray-500 uppercase group-hover:text-[#2b90c7] transition-colors">Article</label>
                            <input type="text" x-model.debounce.300ms="search.article" placeholder="Croquettes, Jouet..." 
                                   class="w-full outline-none text-gray-700 font-medium placeholder-gray-300 bg-transparent py-1">
                        </div>
                    </div>

                    <div class="hidden lg:block search-divider"></div>

                    <div class="flex-1 w-full lg:w-auto flex items-center px-4 py-2 border-t lg:border-t-0 border-gray-100 hover:bg-gray-50 transition-colors rounded-md group relative" x-data="{ open: false }">
                        <i class="fa-solid fa-layer-group text-gray-400 mr-3 text-lg group-hover:text-[#2b90c7] transition-colors"></i>
                        <div class="flex flex-col items-start w-full relative">
                            <label class="text-xs font-bold text-gray-500 uppercase group-hover:text-[#2b90c7] transition-colors">Catégorie</label>
                            
                            <div @click="open = !open" @click.away="open = false" class="w-full cursor-pointer relative z-10">
                                <div class="w-full outline-none text-gray-700 font-medium bg-transparent py-1 focus:text-[#2b90c7] transition-colors flex items-center justify-between">
                                    <span x-text="search.categorie === '' ? 'Toutes les catégories' : search.categorie" class="truncate text-left"></span>
                                    <i class="fa-solid fa-chevron-down text-gray-300 text-xs ml-2 pointer-events-none group-hover:text-[#2b90c7] transition-colors"></i>
                                </div>
                                
                                <ul x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-20 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 text-gray-700 font-medium py-1 ring-1 ring-black ring-opacity-5 focus:outline-none left-0 max-h-60 overflow-auto">
                                    <li @click="search.categorie = ''; open = false" :class="{ 'bg-blue-100 text-blue-800': search.categorie === '' }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                        <span class="font-normal block truncate">Toutes les catégories</span>
                                    </li>
                                    <template x-for="cat in categories" :key="cat.libellecategoriepro">
                                        <li @click="search.categorie = cat.libellecategoriepro; open = false" :class="{ 'bg-blue-100 text-blue-800': search.categorie === cat.libellecategoriepro }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                            <span class="font-normal block truncate" x-text="cat.libellecategoriepro"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="hidden lg:block search-divider"></div>

                    <div class="flex-1 w-full lg:w-auto flex items-center px-4 py-2 border-t lg:border-t-0 border-gray-100 hover:bg-gray-50 transition-colors rounded-md group relative" x-data="{ open: false }">
                        <i class="fa-solid fa-paw text-gray-400 mr-3 text-lg group-hover:text-[#2b90c7] transition-colors"></i>
                        <div class="flex flex-col items-start w-full relative">
                            <label class="text-xs font-bold text-gray-500 uppercase group-hover:text-[#2b90c7] transition-colors">Espèce</label>
                            
                            <div @click="open = !open" @click.away="open = false" class="w-full cursor-pointer relative z-10">
                                <div class="w-full outline-none text-gray-700 font-medium bg-transparent py-1 focus:text-[#2b90c7] transition-colors flex items-center justify-between">
                                    <span x-text="search.espece === '' ? 'Toutes les espèces' : search.espece" class="truncate text-left"></span>
                                    <i class="fa-solid fa-chevron-down text-gray-300 text-xs ml-2 pointer-events-none group-hover:text-[#2b90c7] transition-colors"></i>
                                </div>
                                
                                <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute z-20 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 text-gray-700 font-medium py-1 ring-1 ring-black ring-opacity-5 focus:outline-none left-0">
                                    <li @click="search.espece = ''; open = false" :class="{ 'bg-blue-100 text-blue-800': search.espece === '' }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                        <span class="font-normal block truncate">Toutes les espèces</span>
                                    </li>
                                    <li @click="search.espece = 'Chien'; open = false" :class="{ 'bg-blue-100 text-blue-800': search.espece === 'Chien' }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                        <span class="font-normal block truncate">Chien</span>
                                    </li>
                                    <li @click="search.espece = 'Chat'; open = false" :class="{ 'bg-blue-100 text-blue-800': search.espece === 'Chat' }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                        <span class="font-normal block truncate">Chat</span>
                                    </li>
                                    <li @click="search.espece = 'Lapin'; open = false" :class="{ 'bg-blue-100 text-blue-800': search.espece === 'Lapin' }" class="cursor-pointer select-none relative py-1 pl-3 pr-9 hover:bg-blue-50 mx-1 my-0 rounded-md text-left text-sm">
                                        <span class="font-normal block truncate">Lapin</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="hidden lg:block search-divider"></div>

                    <div class="flex-1 w-full lg:w-auto flex items-center px-4 py-2 border-t lg:border-t-0 border-gray-100 hover:bg-gray-50 transition-colors rounded-md group">
                        <i class="fa-solid fa-store text-gray-400 mr-3 text-lg group-hover:text-[#2b90c7] transition-colors"></i>
                        <div class="flex flex-col items-start w-full">
                            <label class="text-xs font-bold text-gray-500 uppercase group-hover:text-[#2b90c7] transition-colors">Vendeur</label>
                            <input type="text" x-model.debounce.300ms="search.contact" placeholder="Nom du pro..." 
                                   class="w-full outline-none text-gray-700 font-medium placeholder-gray-300 bg-transparent py-1">
                        </div>
                    </div>

                    <div class="hidden lg:block search-divider"></div>

                    <div x-show="isFiltered" x-cloak class="px-2">
                        <button @click="resetFilters()" class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all" title="Réinitialiser">
                            <i class="fa-solid fa-times-circle text-xl"></i>
                        </button>
                    </div>

                </div>

                <div class="mt-4">
                    <a href="{{ route('client.recherche') }}" class="text-white/90 hover:text-white underline text-sm font-medium flex items-center justify-center gap-2">
                        <i class="fa-solid fa-briefcase"></i> Je recherche un service plutôt qu'un produit
                    </a>
                </div>

            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 relative z-10 pb-20">             
            <div x-show="filteredProducts.length === 0" x-cloak class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Aucun article trouvé</h3>
                <p class="text-gray-500 mt-2">Essayez de modifier vos critères de recherche.</p>
                <button @click="resetFilters()" class="mt-4 text-[#2b90c7] hover:underline font-medium">Réinitialiser tout</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="product in filteredProducts" :key="product.idproduit">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col group h-full">
                        
                        <div class="relative h-56 bg-gray-100 overflow-hidden">
                            <a :href="'/boutique/produit/' + product.idproduit" class="block h-full w-full">
                                <template x-if="product.photo">
                                    <img :src="'/storage/' + product.photo" :alt="product.nomproduit" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </template>
                                <template x-if="!product.photo">
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                        <i class="fa-solid fa-image text-4xl"></i>
                                    </div>
                                </template>
                            </a>
                            
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/95 backdrop-blur-sm text-gray-800 text-xs font-bold px-3 py-1.5 rounded-md shadow-sm border border-gray-100 uppercase tracking-wide" x-text="product.libellecategoriepro"></span>
                            </div>
                        </div>

                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center justify-between mb-2">
                                <a :href="'/client/recherche?q=' + (product.professionnel ? product.professionnel.libelleetablissement : '')" 
                                   class="text-xs font-semibold text-[#2b90c7] hover:underline uppercase tracking-wide">
                                    <i class="fa-solid fa-store mr-1"></i>
                                    <span x-text="product.professionnel ? product.professionnel.libelleetablissement : 'Hunimalis'"></span>
                                </a>
                                <span class="text-xs text-gray-400 font-medium" x-text="product.professionnel ? product.professionnel.ville : 'En ligne'"></span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2 leading-tight group-hover:text-[#2b90c7] transition-colors">
                                <a :href="'/boutique/produit/' + product.idproduit" x-text="product.nomproduit"></a>
                            </h3>
                            
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                                <span class="text-2xl font-extrabold text-gray-900" x-text="new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(product.prixvente)"></span>
                                
                                <button class="w-10 h-10 rounded-full bg-gray-50 text-gray-600 hover:bg-[#2b90c7] hover:text-white flex items-center justify-center transition-all shadow-sm group-btn">
                                    <i class="fa-solid fa-cart-plus transition-transform group-btn-hover:scale-110"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </template>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Hunimalis. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
    function boutiqueLogic(productsData, categoriesData) {
        return {
            products: productsData,
            categories: categoriesData,
            fuseInstance: null,
            search: {
                article: '',
                categorie: '',
                contact: '',
                ville: '',
                espece: '' 
            },

            initFilters() {
                const options = {
                    keys: [
                        'nomproduit', 
                        'descriptionprod',
                        'professionnel.libelleetablissement',
                        'professionnel.personne.nom',
                        'professionnel.personne.prenom'
                    ],
                    threshold: 0.4, 
                    ignoreLocation: true,
                    minMatchCharLength: 2
                };
                
                this.fuseInstance = new Fuse(this.products, options);

                const urlParams = new URLSearchParams(window.location.search);
                if(urlParams.has('search')) this.search.article = urlParams.get('search');
                if(urlParams.has('categorie')) this.search.categorie = urlParams.get('categorie');
                if(urlParams.has('espece')) {
                    let espece = urlParams.get('espece').toLowerCase();
                    this.search.espece = espece.charAt(0).toUpperCase() + espece.slice(1);
                }
            },

            cleanPhonetic(term) {
                let clean = term.toLowerCase();

                if (clean.length > 3 && (clean.endsWith('s') || clean.endsWith('x'))) {
                    clean = clean.slice(0, -1);
                }

                clean = clean.replace(/ai/g, 'é'); 
                clean = clean.replace(/ph/g, 'f'); 
                clean = clean.replace(/k/g, 'c'); 

                return clean;
            },

            normalizeString(str) {
                return str ? str.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() : "";
            },

            superClean(term) {
                return this.normalizeString(this.cleanPhonetic(term));
            },

            get isFiltered() {
                return this.search.article !== '' || this.search.categorie !== '' || 
                       this.search.contact !== '' || this.search.ville !== '' || this.search.espece !== '';
            },

            get filteredProducts() {
                let resultList = this.products;

                if (this.search.article !== '') {
                    const optimizedTerm = this.cleanPhonetic(this.search.article);
                    const fuseResults = this.fuseInstance.search(optimizedTerm);
                    resultList = fuseResults.map(res => res.item);
                }

                return resultList.filter(product => {
                    
                    const matchCategory = this.search.categorie === '' || 
                        product.libellecategoriepro === this.search.categorie;

                    let matchContact = true;
                    if (this.search.contact !== '') {
                        const searchContact = this.superClean(this.search.contact);
                        
                        if (product.professionnel) {
                            const etablissement = this.normalizeString(product.professionnel.libelleetablissement);
                            const nom = product.professionnel.personne ? this.normalizeString(product.professionnel.personne.nom) : '';
                            const prenom = product.professionnel.personne ? this.normalizeString(product.professionnel.personne.prenom) : '';

                            matchContact = etablissement.includes(searchContact) || 
                                           nom.includes(searchContact) || 
                                           prenom.includes(searchContact);
                        } else {
                            matchContact = false;
                        }
                    }

                    let matchEspece = true;
                    if (this.search.espece !== '') {
                        const searchEspece = this.superClean(this.search.espece);
                        const nomProd = this.normalizeString(product.nomproduit);
                        const descProd = this.normalizeString(product.descriptionprod || '');
                        matchEspece = nomProd.includes(searchEspece) || descProd.includes(searchEspece);
                    }

                    return matchCategory && matchContact && matchEspece;
                });
            },

            resetFilters() {
                this.search.article = '';
                this.search.categorie = '';
                this.search.contact = '';
                this.search.ville = '';
                this.search.espece = '';
                window.history.pushState({}, document.title, window.location.pathname);
            }
        }
    }
    </script>
    
    @php
    $panierSession = session('panier_client', []);
    $quantiteTotale = 0;
    foreach($panierSession as $item) {
        $quantiteTotale += $item['quantite'];
    }
    @endphp

    <a href="{{ route('client.panier') }}" 
    class="fixed bottom-24 right-6 z-40 bg-white text-indigo-600 hover:bg-indigo-50 w-14 h-14 rounded-full shadow-xl flex items-center justify-center transition-all transform hover:scale-110 border-2 border-indigo-100 group"
    title="Voir mon panier">
        
        <div class="relative">
            <i class="fa-solid fa-cart-shopping text-xl"></i>
            @if($quantiteTotale > 0)
                <span class="absolute -top-3 -right-3 bg-red-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white animate-bounce">
                    {{ $quantiteTotale }}
                </span>
            @endif
        </div>
    </a>
    <x-chatbot />

</body>
</html>