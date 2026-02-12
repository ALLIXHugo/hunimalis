<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous en ligne pour animaux &bull; Hunimalis</title>
    <link rel="shortcut icon" href="{{ URL::asset('img/favicon.webp') }}" type="image/x-icon">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .suggestions-list { 
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
            max-height: 240px;
            overflow-y: auto;
            z-index: 9999 !important; 
            margin-top: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .suggestion-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.875rem;
            color: #374151;
            transition: background-color 0.2s;
        }
        .suggestion-item:last-child { border-bottom: none; }
        .suggestion-item:hover { background-color: #f3f4f6; }
        .suggestion-item i { margin-right: 0.75rem; width: 20px; text-align: center; }
    </style>
</head>

<body x-data="{ openModal: false, mode: 'login' }">

    <header id="header">
        <nav class="navbar">
            <a href="#" class="navbar-brand">
                <img class="navbar-brand-item usual" src="{{ asset('img/logo.webp') }}" alt="Hunimalis">
            </a>
            <button type="button" class="navbar-toggler" aria-label="Menu">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
            <div id="navbarCollapse" class="navbar-collapse">
                <ul id="navbarMenu" class="navbar-nav items-center"> 
                    @guest
                        <li class="navbar-item">
                            <a href="{{ route('professionnel.create') }}" class="nav-btn nav-btn-pro">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="22.01"></line><line x1="15" y1="22" x2="15" y2="22.01"></line><line x1="9" y1="18" x2="9" y2="18.01"></line><line x1="15" y1="18" x2="15" y2="18.01"></line><line x1="9" y1="14" x2="9" y2="14.01"></line><line x1="15" y1="14" x2="15" y2="14.01"></line><line x1="9" y1="10" x2="9" y2="10.01"></line><line x1="15" y1="10" x2="15" y2="10.01"></line><line x1="9" y1="6" x2="9" y2="6.01"></line><line x1="15" y1="6" x2="15" y2="6.01"></line></svg>
                                Je suis un professionnel
                            </a>
                        </li>
                        <li class="navbar-item">
                            <button @click="openModal = true; mode = 'login'" class="nav-btn nav-btn-login">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                                Se connecter
                            </button>
                        </li>
                        <li class="navbar-item">
                            <button @click="openModal = true; mode = 'register'" class="nav-btn nav-btn-register">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                S'inscrire
                            </button>
                        </li>
                    @endguest

                    @auth
                        <li class="navbar-item ml-4">
                            <a href="{{ route('clients.dashboard') }}" class="block hover:opacity-80 transition transform hover:scale-105" title="Accéder à mon espace">
                                @if(isset($personne) && !empty($personne->avatar))
                                    <img src="{{ asset('storage/' . $personne->avatar) }}" 
                                         alt="Mon Profil" 
                                         class="w-12 h-12 rounded-full shadow-md border-2 border-[#00C497] object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $personne->prenom ?? 'U' }}+{{ $personne->nom ?? 'ser' }}&background=00C497&color=fff&bold=true" 
                                         alt="Mon Profil"
                                         class="w-12 h-12 rounded-full shadow-md border-2 border-[#00C497]">
                                @endif
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero relative w-full flex flex-col justify-end items-center z-50 pb-12 md:pb-20" style="min-height: 600px;">

            <div class="hero-overlay absolute inset-0 z-0 bg-black/10"></div>

            <div class="hero-content">
                <div>
                    <h1 class="hero-title">
                        Prenez un rendez-vous avec un <br>
                        professionnel animalier près de chez vous
                    </h1>
                </div>
            </div>

            <div class="relative z-20 w-full max-w-[95%] xl:max-w-7xl px-4 !overflow-visible">
                
                <form class="bg-white rounded-xl shadow-2xl flex flex-col md:flex-row w-full relative !overflow-visible min-h-[80px]" 
                    action="{{ route('client.recherche') }}" 
                    method="GET">
                    
                    <div class="relative flex-1 border-b md:border-b-0 md:border-r border-gray-100 z-30">
                        <div class="flex items-center h-20 px-6">
                            <i class="fa-solid fa-magnifying-glass text-gray-500 mr-4 text-xl"></i>
                            <input 
                                type="text" 
                                name="q" 
                                id="globalSearchInput"
                                value="{{ request('q') }}" 
                                placeholder="Nom, spécialité, établissement..." 
                                autocomplete="off"
                                class="w-full h-full outline-none text-gray-700 placeholder-gray-400 font-medium text-lg bg-transparent"
                            >
                        </div>
                        <ul id="globalSuggestions" class="suggestions-list hidden"></ul>
                    </div>

                    <div class="relative flex-1 border-b md:border-b-0 md:border-r border-gray-100 z-20">
                        <div class="flex items-center h-20 px-6">
                            <i class="fa-solid fa-location-dot text-gray-500 mr-4 text-xl"></i>
                            <input 
                                type="text" 
                                id="villeInput"
                                name="ville" 
                                value="{{ request('ville') }}" 
                                placeholder="Adresse" 
                                autocomplete="off"
                                class="w-full h-full outline-none text-gray-700 placeholder-gray-400 font-medium text-lg bg-transparent"
                            >
                            <i class="fa-solid fa-location-arrow text-gray-400 cursor-pointer hover:text-blue-500 absolute right-6"></i>
                        </div>
                        <ul id="villeSuggestions" class="suggestions-list hidden"></ul>
                    </div>

                    <div class="relative flex-1 z-10">
                        <div class="flex items-center h-20 px-6">
                            <i class="fa-regular fa-calendar text-gray-500 mr-4 text-xl"></i>
                            <input 
                                type="text"
                                placeholder="Quand ?"
                                name="date" 
                                value="{{ request('date') }}" 
                                min="{{ now()->format('Y-m-d') }}" 
                                onfocus="(this.type='date'); this.showPicker();"
                                onblur="if(!this.value) this.type='text'"
                                onkeydown="return false"
                                class="w-full h-full outline-none text-gray-700 placeholder-gray-400 font-medium text-lg bg-transparent cursor-pointer"
                            >
                        </div>
                    </div>

                    <button type="submit" class="h-20 md:h-auto px-10 bg-[#1e293b] text-white font-bold text-lg hover:bg-[#0f172a] transition md:rounded-r-xl rounded-b-xl md:rounded-bl-none w-full md:w-auto shadow-lg whitespace-nowrap">
                        C'est parti
                    </button>
                    
                </form>
            </div>
            <div class="text-center mt-6 relative z-30"> 
                <a href="{{ route('client.boutique') }}" class="inline-flex items-center gap-2 text-white hover:text-gray-200 underline decoration-2 underline-offset-4 transition">
                    <i class="fa-solid fa-bag-shopping"></i>
                    Je recherche un produit plutôt qu'un service
                </a>
            </div>
        </section>

        <section class="how-it-works">
            <div class="container">
                <h2 class="section-title">Comment ça marche ?</h2>
                <div class="steps-grid">
                    <div class="step-item">
                        <div class="step-visual">
                            <div class="step-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            </div>
                            <span class="step-badge">01</span>
                        </div>
                        <h3 class="step-title">Inscription</h3>
                        <p class="step-desc">Un e-mail, un mot de passe, c'est parti !</p>
                        <div class="step-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-visual">
                            <div class="step-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            </div>
                            <span class="step-badge">02</span>
                        </div>
                        <h3 class="step-title">Profil animal</h3>
                        <p class="step-desc">Nom, race, âge, besoins spécifiques... pour un service adapté.</p>
                        <div class="step-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-visual">
                            <div class="step-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                            </div>
                            <span class="step-badge">03</span>
                        </div>
                        <h3 class="step-title">Choix du pro</h3>
                        <p class="step-desc">Toiletteurs, vétérinaires, éducateurs... avec avis et disponibilités.</p>
                        <div class="step-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-visual">
                            <div class="step-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><path d="M9 16l2 2 4-4"></path></svg>
                            </div>
                            <span class="step-badge">04</span>
                        </div>
                        <h3 class="step-title">Réservation</h3>
                        <p class="step-desc">Plus d'appels, plus d'oublis : tout est centralisé et simple.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="professional-search">
            <div class="container">
                <h2 class="section-title">Je recherche un professionnel</h2>
                <div class="carousel-wrapper">
                    <button class="carousel-btn prev-btn" aria-label="Précédent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <div class="carousel-track-container">
                        <ul class="carousel-track">
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=vétérinaire"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/veterinary.svg') }}" alt="Vétérinaires"></div><div class="job-title">Vétérinaires</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=éducateur"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/educator.svg') }}" alt="Éducateurs"></div><div class="job-title">Éducateurs</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=pension"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/pension.svg') }}" alt="Pensions"></div><div class="job-title">Pensions</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=pet+sitter"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/petsitter.svg') }}" alt="Pet-sitters"></div><div class="job-title">Pet-sitters</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=éleveur"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/breeder.svg') }}" alt="Éleveurs"></div><div class="job-title">Éleveurs</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=osthéopathe"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/osteopath.svg') }}" alt="Ostéopathes"></div><div class="job-title">Ostéopathes</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=garderie"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/daycare.svg') }}" alt="Garderies"></div><div class="job-title">Garderies</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=association"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/association.svg') }}" alt="Associations"></div><div class="job-title">Association PA</div></div></a></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=masseur"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/masseur.svg') }}" alt="Masseurs animaliers"></div><div class="job-title">Masseurs animaliers</div></a></div></li>
                            <li class="carousel-slide"><a href="trouver-un-pro?types%5B%5D=toiletteur"><div class="job-card"><div class="job-img-container"><img src="{{ asset('img/jobs/groomer.svg') }}" alt="Toiletteurs"></div><div class="job-title">Toiletteurs</div></div></a></li>
                        </ul>
                    </div>
                    <button class="carousel-btn next-btn" aria-label="Suivant">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
            </div>
        </section>
        
        <section class="stats-section">
            <div class="container">
                <h2 class="section-title">Hunimalis en chiffres</h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 640 640" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M598.1 139.4C608.8 131.6 611.2 116.6 603.4 105.9C595.6 95.2 580.6 92.8 569.9 100.6L495.4 154.8L485.5 148.2C465.8 135 442.6 128 418.9 128L359.7 128L359.3 128L215.7 128C189 128 163.2 136.9 142.3 153.1L70.1 100.6C59.4 92.8 44.4 95.2 36.6 105.9C28.8 116.6 31.2 131.6 41.9 139.4L129.9 203.4C139.5 210.3 152.6 209.3 161 201L164.9 197.1C178.4 183.6 196.7 176 215.8 176L262.1 176L170.4 267.7C154.8 283.3 154.8 308.6 170.4 324.3L171.2 325.1C218 372 294 372 340.9 325.1L368 298L465.8 395.8C481.4 411.4 481.4 436.7 465.8 452.4L456 462.2L425 431.2C415.6 421.8 400.4 421.8 391.1 431.2C381.8 440.6 381.7 455.8 391.1 465.1L419.1 493.1C401.6 503.5 381.9 509.8 361.5 511.6L313 463C303.6 453.6 288.4 453.6 279.1 463C269.8 472.4 269.7 487.6 279.1 496.9L294.1 511.9L290.3 511.9C254.2 511.9 219.6 497.6 194.1 472.1L65 343C55.6 333.6 40.4 333.6 31.1 343C21.8 352.4 21.7 367.6 31.1 376.9L160.2 506.1C194.7 540.6 241.5 560 290.3 560L342.1 560L343.1 561L344.1 560L349.8 560C398.6 560 445.4 540.6 479.9 506.1L499.8 486.2C501 485 502.1 483.9 503.2 482.7C503.9 482.2 504.5 481.6 505.1 481L609 377C618.4 367.6 618.4 352.4 609 343.1C599.6 333.8 584.4 333.7 575.1 343.1L521.3 396.9C517.1 384.1 510 372 499.8 361.8L385 247C375.6 237.6 360.4 237.6 351.1 247L307 291.1C280.5 317.6 238.5 319.1 210.3 295.7L309 197C322.4 183.6 340.6 176 359.6 175.9L368.1 175.9L368.3 175.9L419.1 175.9C433.3 175.9 447.2 180.1 459 188L482.7 204C491.1 209.6 502 209.3 510.1 203.4L598.1 139.4z"/></svg>
                        </div>
                        <div class="stat-number">+800 000</div>
                        <div class="stat-label">Rendez-vous enregistrés</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><path d="M9 22v-4h6v4"></path><path d="M8 6h.01"></path><path d="M16 6h.01"></path><path d="M12 6h.01"></path><path d="M12 10h.01"></path><path d="M12 14h.01"></path><path d="M16 10h.01"></path><path d="M16 14h.01"></path><path d="M8 10h.01"></path><path d="M8 14h.01"></path></svg>
                        </div>
                        <div class="stat-number">+15 000</div>
                        <div class="stat-label">Établissements</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 10c.7-.7 1.69 0 2.5 0a2.5 2.5 0 1 0 0-5 .5.5 0 0 1-.5-.5 2.5 2.5 0 1 0-5 0c0 .81.7 1.8 0 2.5l-7 7c-.7.7-1.69 0-2.5 0a2.5 2.5 0 0 0 0 5c.28 0 .5.22.5.5a2.5 2.5 0 1 0 5 0c0-.81-.7-1.8 0-2.5l7-7z"/></svg>
                        </div>
                        <div class="stat-number">+400 000</div>
                        <div class="stat-label">Animaux inscrits</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="create-profile-section">
            <div class="container">
                <div class="create-profile-content">
                    <div class="create-profile-image">
                        <img src="{{ asset('img/mockup_mobile.webp') }}" alt="Application mobile Hunimalis">
                    </div>
                    <div class="create-profile-text">
                        <h3 class="section-title text-left">Créez la fiche de votre animal</h2>
                        
                        <div class="feature-list">
                            <div class="feature-item">
                                <div class="feature-icon icon-folder">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="9" y1="13" x2="15" y2="13"></line></svg>
                                </div>
                                <div class="feature-info">
                                    <h3>Tout au même endroit</h3>
                                    <p>Un seul espace pour centraliser toutes les infos essentielles : nom, race, poids, carnet de santé, vaccins, allergies, maladies, etc... Plus besoin de tout retenir. Tout est à jour, accessible et partageable avec les professionnels.</p>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon icon-calendar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7.5V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h3.5"></path><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line><circle cx="16" cy="16" r="6"></circle><polyline points="16 14 16 16 18 18"></polyline></svg>
                                </div>
                                <div class="feature-info">
                                    <h3>Gérez vos rendez-vous et vos paiements</h3>
                                    <p>Retrouvez l'historique de tous vos rendez-vous passés et à venir (toilettage, éducation, soins...) avec les détails des prestations, les horaires, les lieux et le suivi des paiements. Pratique pour tout suivre en un clin d'œil.</p>
                                </div>
                            </div>

                            <div class="feature-item">
                                <div class="feature-icon icon-chart">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 20h18"></path><path d="M3 4v16"></path><polyline points="7 15 12 10 16 13 21 5"></polyline><polyline points="16 5 21 5 21 10"></polyline></svg>
                                </div>
                                <div class="feature-info">
                                    <h3>Suivez l'évolution de votre animal</h3>
                                    <p>Notez chaque changement important dans la vie de votre chien : poids, santé, comportement... Tous les rendez-vous sont accompagnés de comptes rendus remplis par les professionnels que vous consultez. Vous gardez une trace de son parcours, de ses progrès et de son bien-être au fil du temps.</p>
                                </div>
                            </div>
                        </div>

                        <a @click.prevent="openModal = true; mode = 'register'" href="#" class="btn-cta">
                            S'inscrire
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="professional-section">
            <div class="container">
                <div class="professional-content">
                    <div class="professional-text">
                        <h3 class="section-title">Vous êtes un professionnel ?</h2>
                        <div class="pro-feature">
                            <div class="pro-icon icon-laptop">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                            </div>
                            <div>
                                <h3>Tous les métiers, un seul logiciel</h3>
                                <p>Que vous soyez toiletteur, éducateur canin, pension, vétérinaire, ostéopathe, pet-sitter ou éleveur, Hunimalis a été conçu pour s'adapter à vos besoins réels. Gagnez du temps, structurez votre activité et développez votre visibilité sans changer votre façon de travailler.</p>
                            </div>
                        </div>
                        <div class="pro-feature">
                            <div class="pro-icon icon-mobile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><path d="M12 18h.01"></path><polyline points="9 10 11 12 15 8"></polyline></svg>
                            </div>
                            <div>
                                <h3>Une application tout-en-un, pensée pour vous</h3>
                                <p>Fini les fichiers Excel, les rappels à la main ou les logiciels bricolés. Avec Hunimalis, votre agenda, vos clients, vos animaux, vos paiements, vos factures et même la prise de rendez-vous en ligne sont réunis au même endroit. Moins de paperasse, plus d'efficacité.</p>
                            </div>
                        </div>
                        <div class="pro-feature">
                            <div class="pro-icon icon-question">
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            </div>
                            <div>
                                <h3>Pourquoi Hunimalis ?</h3>
                                <p>Parce qu'au-delà du logiciel, il y a une vraie équipe de 10 personnes, dont des experts du monde animalier, qui travaillent chaque jour avec des éducateurs, des toiletteurs, des éleveurs ou des associations... Plus de 600 pros du monde animalier nous font déjà confiance. Notre force ? Une équipe de terrain, réactive, disponible, qui connaît vos métiers et qui vous accompagne vraiment.</p>
                            </div>
                        </div>
                        <a href="#" class="btn-cta btn-dark">Voir les tarifs</a>
                    </div>
                    <div class="professional-image">
                        <img src="{{ asset('img/professional_woman.webp') }}" alt="Professionnel animalier">
                    </div>
                </div>
            </div>
        </section>

        <section class="faq-section">
            <div class="container">
                <h2 class="section-title">Questions fréquemment posées</h2>
                
                <div class="faq-list">
                    <details class="faq-item" open>
                        <summary class="faq-question">
                            Je peux télécharger l'application Hunimalis ?
                            <span class="faq-icon"></span>
                        </summary>
                        <div class="faq-answer">
                            <p>Oui, il s'agit d'une Web App, c'est-à-dire une application accessible depuis un navigateur que vous pouvez enregistrer sur votre téléphone comme une vraie appli. Pour cela, ouvrez le site Hunimalis sur votre navigateur (Safari ou Chrome).</p>
                            <ul>
                                <li>Sur iPhone appuyez sur le bouton 'Partager' en bas de l'écran et sélectionnez 'Sur l'écran d'accueil'.</li>
                                <li>Sur Android, ouvrez le menu en haut à droite (trois points) et sélectionnez 'Ajouter à l'écran d'accueil'.</li>
                            </ul>
                            <p>Vous retrouverez Hunimalis comme une application classique sur votre téléphone, sans installation ni mise à jour manuelle.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-question">
                            Dois-je créer un compte pour prendre rendez-vous ?
                            <span class="faq-icon"></span>
                        </summary>
                        <div class="faq-answer">
                            <p>Oui, la création d'un compte est nécessaire. Cela vous permet de retrouver tous vos rendez-vous, vos paiements, et les informations liées à vos animaux.</p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-question">
                            Est-ce que Hunimalis est gratuit ?
                            <span class="faq-icon"></span>
                        </summary>
                        <div class="faq-answer">
                            <p>
                                Oui, Hunimalis est entièrement gratuit pour les particuliers. Vous pouvez créer un compte, ajouter vos animaux, prendre des rendez-vous et utiliser toutes les fonctionnalités sans frais d’inscription ni d’abonnement.
                                <br><br>
                                Pour les professionnels, Hunimalis propose également des abonnements adaptés à chaque métier, avec des outils avancés pour optimiser la gestion de leur activité.
                            </p>
                        </div>
                    </details>

                    <details class="faq-item">
                        <summary class="faq-question">
                            Est-ce que Hunimalis est sécurisé ?
                            <span class="faq-icon"></span>
                        </summary>
                        <div class="faq-answer">
                            <p>Oui, Hunimalis utilise des protocoles de sécurité avancés pour protéger vos données personnelles et celles de vos animaux. Toutes les informations sont stockées de manière sécurisée et accessibles uniquement par vous et les professionnels avec lesquels vous interagissez.</p>
                        </div>
                    </details>
                </div>
            </div>
        </section>

        <div class="container text-center pb-12">
            <div class="bg-gray-50 rounded-xl p-8 border border-gray-100 max-w-3xl mx-auto shadow-sm">
                <h3 class="text-xl font-bold text-gray-800 mb-2">Vous ne trouvez pas la réponse ?</h3>
                <p class="text-gray-600 mb-6">Consultez notre guide complet d'utilisation ou contactez le support.</p>
                
                <a href="{{ route('client.aide') }}" class="inline-flex items-center px-8 py-3 bg-[#00C497] text-white font-bold rounded-full shadow-lg hover:bg-[#00a881] transition transform hover:scale-105 group">
                    <i class="fa-solid fa-book-open mr-2 group-hover:animate-pulse"></i> 
                    Accéder au Centre d'Aide
                </a>
            </div>
        </div>

        
    </main>
    
    <div x-show="openModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="openModal = false" class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md modal-content">
            <h3 class="text-xl font-bold text-center mb-6">Je suis un...</h3>
            <div class="flex justify-center gap-6 mb-6 modal-choices-container">
                <a :href="mode === 'login' ? '{{ route('login') }}' : '{{ route('register.form') }}'" class="modal-choice-card particulier">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Particulier
                </a>
                <a :href="mode === 'login' ? '{{ route('login') }}' : '{{ route('professionnel.create') }}'" class="modal-choice-card professionnel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M12 2v9"></path><path d="M5 22v-4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4"></path></svg>
                    Professionnel
                </a>
            </div>
            <button @click="openModal = false" class="w-full py-2 bg-gray-200 rounded hover:bg-gray-300 modal-cancel-button">
                Annuler
            </button>
        </div>
    </div>

    <div x-data="chatBot()" class="fixed bottom-6 right-6 z-[100] flex flex-col items-end gap-4">
        <div x-show="isOpen" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            class="bg-white w-80 sm:w-96 h-[500px] rounded-2xl shadow-2xl flex flex-col overflow-hidden border border-gray-100"
            style="display: none;">
            
            <div class="bg-[#1e293b] p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <h3 class="font-bold text-sm">Assistant Hunimalis</h3>
                </div>
                <button @click="isOpen = false" class="text-gray-400 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4" id="chatMessages">
                <div class="flex justify-start">
                    <div class="bg-white border border-gray-200 text-gray-700 rounded-tr-xl rounded-br-xl rounded-bl-xl p-3 text-sm shadow-sm max-w-[85%]">
                        Bonjour ! Je suis l'IA Hunimalis 🐶. Comment puis-je vous aider à trouver un professionnel aujourd'hui ?
                    </div>
                </div>
                <template x-for="(msg, index) in messages" :key="index">
                    <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                        <div :class="msg.sender === 'user' 
                            ? 'bg-[#1e293b] text-white rounded-tl-xl rounded-tr-xl rounded-bl-xl' 
                            : 'bg-white border border-gray-200 text-gray-700 rounded-tr-xl rounded-br-xl rounded-bl-xl'"
                            class="p-3 text-sm shadow-sm max-w-[85%]">
                            
                            <div x-html="msg.text" class="prose"></div>
                            
                        </div>
                    </div>
                    </div>
                </template>
                <div x-show="isLoading" class="flex justify-start">
                    <div class="bg-gray-200 text-gray-500 rounded-xl p-3 text-xs flex gap-1 items-center">
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce"></span>
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-100">
                <form @submit.prevent="sendMessage" class="flex gap-2">
                    <input x-model="userInput" 
                        type="text" 
                        placeholder="Posez votre question..." 
                        class="flex-1 bg-gray-100 text-sm rounded-full px-4 py-2 outline-none focus:ring-2 focus:ring-blue-500/50 transition"
                        :disabled="isLoading">
                    
                    <button type="submit" 
                            class="w-10 h-10 bg-[#1e293b] text-white rounded-full flex items-center justify-center hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="isLoading || userInput.trim() === ''">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>
        </div>

        <button @click="isOpen = !isOpen" 
                class="bg-[#1e293b] hover:bg-[#0f172a] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110 active:scale-95 group">
            <i x-show="!isOpen" class="fa-solid fa-comments text-2xl group-hover:animate-bounce"></i>
            <i x-show="isOpen" class="fa-solid fa-chevron-down text-xl" style="display: none;"></i>
        </button>
    </div>

    <div x-data="cookieConsent()" x-init="init()" @open-cookie-modal.window="show = true; details = true" style="display: none;" x-show="show">

    <div x-show="!details" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="translate-y-full opacity-0" 
         x-transition:enter-end="translate-y-0 opacity-100"
         class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 shadow-[0_-10px_40px_-10px_rgba(0,0,0,0.1)] z-[9999] p-4 md:p-6">
        
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8">
            <div class="flex-1 text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                    <img src="{{ asset('img/logo.webp') }}" alt="Hunimalis" class="h-6">
                    <h3 class="font-bold text-gray-900 text-base">Votre vie privée nous tient à cœur</h3>
                </div>
                <p class="text-sm text-gray-600 leading-snug">
                    Nous utilisons des cookies pour sécuriser votre navigation, analyser notre trafic et améliorer votre expérience. 
                    Vous pouvez retirer votre consentement à tout moment.
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto shrink-0">
                <button @click="details = true" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm whitespace-nowrap">
                    Personnaliser
                </button>
                <button @click="refuseAll()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-sm whitespace-nowrap">
                    Tout refuser
                </button>
                <button @click="acceptAll()" class="px-6 py-2.5 bg-[#1e293b] text-white font-bold rounded-lg hover:bg-[#0f172a] shadow-lg transition text-sm whitespace-nowrap">
                    Tout accepter
                </button>
            </div>
        </div>
    </div>

    <div x-show="details" 
         class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
         x-transition.opacity>
        
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] relative"
             @click.away="show = false; details = false">
            
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo.webp') }}" alt="Hunimalis" class="h-6">
                    <h3 class="font-bold text-lg text-gray-900">Préférences cookies</h3>
                </div>
                <button @click="details = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-full hover:bg-gray-200">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-[#FAFAFA]">
                
                <div class="bg-blue-50 text-blue-800 p-4 rounded-xl border border-blue-100 shadow-sm">
                    <h4 class="font-bold text-sm mb-1 flex items-center gap-2">
                        <i class="fa-solid fa-cookie-bite"></i> Qu'est-ce qu'un cookie ?
                    </h4>
                    <p class="text-xs leading-relaxed text-blue-700/80">
                        Un cookie est un petit fichier texte déposé sur votre terminal. Il agit comme une mémoire pour le site et nous aide à comprendre comment le site est utilisé.
                    </p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                    <div class="p-4 flex items-start justify-between gap-4 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-sm text-gray-800">Strictement nécessaires</h4>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-green-600 bg-green-100 px-2 py-0.5 rounded-full border border-green-200">Requis</span>
                            </div>
                            <p class="text-[11px] text-gray-500">Indispensables au fonctionnement du site (connexion, sécurité).</p>
                            <button class="text-[10px] text-[#00C497] font-medium mt-1 flex items-center gap-1 hover:underline">
                                <span x-text="open ? 'Masquer' : 'Voir détails'"></span>
                                <i class="fa-solid text-[9px]" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </div>
                        <div class="relative inline-flex items-center cursor-not-allowed opacity-50">
                            <input type="checkbox" class="sr-only peer" checked disabled>
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#00C497] peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                        </div>
                    </div>
                    <div x-show="open" x-collapse class="border-t border-gray-100 bg-gray-50 p-3">
                        <table class="w-full text-left text-[10px]">
                            <thead class="text-gray-400 font-medium border-b border-gray-200">
                                <tr><th class="pb-1 pl-1">Nom</th><th class="pb-1">Fournisseur</th><th class="pb-1">Finalité</th><th class="pb-1 text-right pr-1">Durée</th></tr>
                            </thead>
                            <tbody class="text-gray-600 divide-y divide-gray-100">
                                <tr><td class="py-1 pl-1 font-mono">hunimalis_session</td><td class="py-1">Hunimalis</td><td class="py-1">Connexion</td><td class="py-1 text-right pr-1">Session</td></tr>
                                <tr><td class="py-1 pl-1 font-mono">hunimalis_consent</td><td class="py-1">Hunimalis</td><td class="py-1">Choix</td><td class="py-1 text-right pr-1">13 mois</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                    <div class="p-4 flex items-start justify-between gap-4 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                        <div class="flex-1">
                            <h4 class="font-bold text-sm text-gray-800 mb-1">Analytiques</h4>
                            <p class="text-[11px] text-gray-500">Mesure d'audience anonyme (Google Analytics).</p>
                            <button class="text-[10px] text-[#00C497] font-medium mt-1 flex items-center gap-1 hover:underline">
                                <span x-text="open ? 'Masquer' : 'Voir détails'"></span>
                                <i class="fa-solid text-[9px]" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" @click.stop>
                            <input type="checkbox" x-model="preferences.analytics" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#00C497] peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-focus:ring-2 peer-focus:ring-[#00C497]/20"></div>
                        </label>
                    </div>
                    <div x-show="open" x-collapse class="border-t border-gray-100 bg-gray-50 p-3">
                        <table class="w-full text-left text-[10px]">
                            <thead class="text-gray-400 font-medium border-b border-gray-200">
                                <tr><th class="pb-1 pl-1">Nom</th><th class="pb-1">Fournisseur</th><th class="pb-1">Finalité</th><th class="pb-1 text-right pr-1">Durée</th></tr>
                            </thead>
                            <tbody class="text-gray-600 divide-y divide-gray-100">
                                <tr><td class="py-1 pl-1 font-mono">_ga</td><td class="py-1">Google</td><td class="py-1">Statistiques</td><td class="py-1 text-right pr-1">13 mois</td></tr>
                                <tr><td class="py-1 pl-1 font-mono">_gid</td><td class="py-1">Google</td><td class="py-1">Navigation</td><td class="py-1 text-right pr-1">24h</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                    <div class="p-4 flex items-start justify-between gap-4 cursor-pointer hover:bg-gray-50 transition" @click="open = !open">
                        <div class="flex-1">
                            <h4 class="font-bold text-sm text-gray-800 mb-1">Marketing</h4>
                            <p class="text-[11px] text-gray-500">Publicités personnalisées et réseaux sociaux (Facebook Pixel).</p>
                            <button class="text-[10px] text-[#00C497] font-medium mt-1 flex items-center gap-1 hover:underline">
                                <span x-text="open ? 'Masquer' : 'Voir détails'"></span>
                                <i class="fa-solid text-[9px]" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </button>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer" @click.stop>
                            <input type="checkbox" x-model="preferences.marketing" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-[#00C497] peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-focus:ring-2 peer-focus:ring-[#00C497]/20"></div>
                        </label>
                    </div>
                    <div x-show="open" x-collapse class="border-t border-gray-100 bg-gray-50 p-3">
                        <table class="w-full text-left text-[10px]">
                            <thead class="text-gray-400 font-medium border-b border-gray-200">
                                <tr><th class="pb-1 pl-1">Nom</th><th class="pb-1">Fournisseur</th><th class="pb-1">Finalité</th><th class="pb-1 text-right pr-1">Durée</th></tr>
                            </thead>
                            <tbody class="text-gray-600 divide-y divide-gray-100">
                                <tr>
                                    <td class="py-1 pl-1 font-mono">_fbp</td>
                                    <td class="py-1">Facebook</td>
                                    <td class="py-1">Publicité / Ciblage</td>
                                    <td class="py-1 text-right pr-1">3 mois</td>
                                </tr>
                                <tr>
                                    <td class="py-1 pl-1 font-mono">fr</td>
                                    <td class="py-1">Facebook</td>
                                    <td class="py-1">Publicité</td>
                                    <td class="py-1 text-right pr-1">3 mois</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="p-4 border-t border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-3">
                
                <button @click="refuseAll()" class="w-full md:w-auto px-4 py-2.5 bg-[#1e293b] text-white font-bold rounded-lg hover:bg-[#0f172a] shadow-lg transition text-xs">
                    Tout refuser
                </button>

                <div class="flex gap-2 w-full md:w-auto">
                    <button @click="acceptAll()" class="flex-1 md:flex-none px-4 py-2.5 bg-[#1e293b] text-white font-bold rounded-lg hover:bg-[#0f172a] shadow-lg transition text-xs">
                        Tout accepter
                    </button>

                    <button @click="savePreferences()" class="flex-1 md:flex-none px-4 py-2.5 bg-[#1e293b] text-white font-bold rounded-lg hover:bg-[#0f172a] shadow-lg transition text-xs">
                        Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
    
    <footer id="footer">
        <div class="container">
            <div class="footer-top">
                <a href="#" class="footer-logo">
                    <img src="{{ asset('img/logo.webp') }}" alt="Hunimalis">
                </a>
            </div>
            <div class="footer-content">
                <div class="footer-section wide">
                    <h3>Trouver un professionnel</h3>
                    <div class="footer-lists-row">
                        <ul>
                            <li><a href="trouver-un-pro?types%5B%5D=toiletteur">Toiletteurs</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=vétérinaire">Vétérinaires</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=éleveur">Éleveurs canins, félins</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=éducateur">Éducateurs</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=pet+sitter">Pet Sitters</a></li>
                        </ul>
                        <ul>
                            <li><a href="trouver-un-pro?types%5B%5D=osthéopathe">Ostéopathes</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=association">Associations / Refuges</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=masseur">Masseurs animaliers</a></li>
                            <li><a href="trouver-un-pro?types%5B%5D=pension">Pensions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>À propos</h3>
                    <ul>
                        <li><a href="register">S'inscrire</a></li>
                        <li><a href="login">Se connecter</a></li>
                        <li><a href="https://www.instagram.com/baptiste.poupeau/">Notre blog</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Liens</h3>
                    <ul>
                        <li><a href="cgu">CGU</a></li>
                        <li><a href="mentionsLegales">Mentions légales</a></li>
                        <li><a href="#" @click.prevent="$dispatch('open-cookie-modal')">Cookies</a></li>
                        <li><a href="#">Foire Aux Questions</a></li>
                        <li><a href="aide">Besoin d'aide ?</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="copyright">&copy; 2016 - 2025 Hunimalis</p>
                <div class="footer-socials">
                    <a href="#" aria-label="Français" class="lang-fr">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 3 2" width="24" height="16">
                            <rect width="3" height="2" fill="#ED2939"/>
                            <rect width="1" height="2" fill="#002395"/>
                            <rect width="1" height="2" x="2" fill="#ED2939"/>
                            <rect width="1" height="2" x="1" fill="#fff"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Facebook" class="social-btn facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" aria-label="Instagram" class="social-btn instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="social-btn linkedin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function cookieConsent() {
            return {
                show: false,
                details: false,
                preferences: {
                    analytics: false,
                    marketing: false
                },

                gaId: 'G-XXXXXXXXXX', 
                pixelId: 'XXXXXXXXXX',

                init() {
                    const savedConsent = this.getCookie('hunimalis_consent');

                    if (!savedConsent) {
                        this.show = true;
                    } else {
                        this.preferences = JSON.parse(savedConsent);
                        this.applyConsent();
                    }
                },

                acceptAll() {
                    this.preferences.analytics = true;
                    this.preferences.marketing = true;
                    this.saveCookie();
                },

                refuseAll() {
                    this.preferences.analytics = false;
                    this.preferences.marketing = false;
                    this.saveCookie();
                    window.location.reload();
                },

                savePreferences() {
                    this.saveCookie();
                    window.location.reload();
                },

                saveCookie() {
                    const d = new Date();
                    d.setTime(d.getTime() + (13 * 30 * 24 * 60 * 60 * 1000)); 
                    let expires = "expires=" + d.toUTCString();
                    document.cookie = "hunimalis_consent=" + JSON.stringify(this.preferences) + ";" + expires + ";path=/;SameSite=Lax";
                    
                    this.show = false;
                    this.details = false;
                    this.applyConsent();
                },

                getCookie(cname) {
                    let name = cname + "=";
                    let decodedCookie = decodeURIComponent(document.cookie);
                    let ca = decodedCookie.split(';');
                    for(let i = 0; i <ca.length; i++) {
                        let c = ca[i];
                        while (c.charAt(0) == ' ') c = c.substring(1);
                        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
                    }
                    return "";
                },

                deleteCookie(name) {
                    document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                    document.cookie = name + '=; Path=/; Domain=' + window.location.hostname + '; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                },

                applyConsent() {
                    console.group("État du Consentement RGPD");
                    
                    if (this.preferences.analytics) {
                        this.loadGoogleAnalytics();
                    } else {
                        this.deleteCookie('_ga_demo'); 
                        this.deleteCookie('_ga');      
                        this.deleteCookie('_gid');
                        this.deleteCookie('_gat');
                    }

                    if (this.preferences.marketing) {
                        this.loadMarketingScripts();
                    } else {
                        this.deleteCookie('_fbp_demo'); 
                        this.deleteCookie('_fbp');      
                    }
                    console.groupEnd();
                },

                loadGoogleAnalytics() {
                    if (document.getElementById('ga-script')) return;

                   
                    document.cookie = "_ga_demo=GA1.1.123456789.123456789; path=/; max-age=31536000";
                },

                loadMarketingScripts() {
                    if (document.getElementById('fb-pixel')) return;
                    
                    document.cookie = "_fbp_demo=fb.1.123456789.123456789; path=/; max-age=7776000";
                }
            }
        }
    </script>

    <script src="js/app.js"></script>
    @php
        $panierSession = session('panier_client', []);
        $quantiteTotale = 0;
        if($panierSession) {
            foreach($panierSession as $item) {
                $quantiteTotale += $item['quantite'];
            }
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