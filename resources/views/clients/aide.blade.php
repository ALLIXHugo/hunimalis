<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'Aide - Hunimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .step-line { position: absolute; left: 24px; top: 40px; bottom: 0; width: 2px; background: #e5e7eb; z-index: 0; }
        .step-circle { position: relative; z-index: 10; width: 50px; height: 50px; background: white; border: 2px solid #4f46e5; color: #4f46e5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.25rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        details > summary { list-style: none; }
        details > summary::-webkit-details-marker { display: none; }
        
        .guide-step {
            position: relative;
            padding-left: 3rem; 
            padding-bottom: 4rem; 
            border-left: 3px solid #e2e8f0; 
        }
        .guide-step:last-child {
            border-left: none; 
            padding-bottom: 0;
        }
        .guide-number {
            position: absolute;
            left: -1.4rem; 
            top: 0;
            width: 2.75rem;
            height: 2.75rem;
            background-color: #4f46e5;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            border: 4px solid #fff; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 10;
        }
        .guide-image {
            width: 100%;      
            height: auto;     
            display: block;
            margin-top: 1.5rem;
            border-radius: 0.5rem; 
            border: 1px solid #e5e7eb; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans leading-relaxed" x-data> 
    <div class="bg-[#1e293b] text-white py-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="#" class="font-bold text-xl flex items-center hover:text-[#00C497] transition">
                    <i class="fa-solid fa-paw mr-2 text-2xl"></i>
                    Centre d'aide Hunimalis
                </a>
            </div>
            <a href="{{ route('home') }}" class="text-sm bg-white/10 hover:bg-white/20 px-4 py-2 rounded-full transition flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Retour au site
            </a>
        </div>
    </div>

    <div class="bg-white border-b border-gray-200 py-12">
        <div class="container mx-auto px-4 text-center max-w-2xl">
            <h1 class="text-3xl md:text-4xl font-extrabold mb-4 text-gray-900">Besoin d'un coup de patte ? 🐾</h1>
            <p class="text-gray-500 mb-8 text-lg">Découvrez comment utiliser Hunimalis pour le bien-être de vos animaux.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-10 grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="hidden lg:block lg:col-span-3">
            <nav class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 sticky top-28">
                <h3 class="font-bold text-gray-900 mb-6 uppercase text-xs tracking-wider border-b pb-2">Au sommaire</h3>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="#compte" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-solid fa-user-plus w-8 text-center"></i> Créer son compte</a></li>
                    <li><a href="#rdv" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-regular fa-calendar-check w-8 text-center"></i> Prendre Rendez-vous</a></li>
                    <li><a href="#animaux" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-solid fa-cat w-8 text-center"></i> Vos Animaux</a></li>
                    <li><a href="#boutique" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-solid fa-basket-shopping w-8 text-center"></i> Achetez des produits</a></li>
                    <li><a href="#securite" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-solid fa-shield-halved w-8 text-center"></i> Utilisation du ChatBot</a></li>
                    <li><a href="#faq" class="flex items-center p-2 rounded-lg text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 transition"><i class="fa-solid fa-shield-halved w-8 text-center"></i> Autres questions</a></li>

                </ul>
                
                <div @click="$dispatch('open-chat')" class="mt-8 bg-gradient-to-br from-[#1e293b] to-black rounded-xl p-5 text-white text-center shadow-lg cursor-pointer hover:scale-105 transition-transform duration-300 group">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-3 animate-bounce group-hover:bg-white/30">
                        <i class="fa-solid fa-robot text-xl"></i>
                    </div>
                    <h4 class="font-bold mb-1">Une question ?</h4>
                    <p class="text-xs text-gray-300 mb-3">Notre robot assistant est là pour vous aider 24h/24.</p>
                </div>
            </nav>
        </div>

        <div class="col-span-1 lg:col-span-9 space-y-16">

            <section id="compte" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg">
                        <i class="fa-solid fa-user-plus"></i>
                    </span>
                    Bien démarrer : Créer votre compte
                </h2>
                
                <div class="bg-white rounded-xl p-6 md:p-8 border border-gray-200 shadow-sm">
                    <p class="text-gray-600 mb-10 text-lg">
                        Pour utiliser Hunimalis à 100% (rendez-vous, boutique...), nous devons sécuriser votre accès. Suivez ces 3 étapes :
                    </p>

                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Lancer l'inscription</h3>
                        <p class="text-gray-600 mb-4">
                            Cliquez sur le bouton <strong>"S'inscrire"</strong> situé sur la page d'accueil ou dans le menu.
                        </p>
                        <img src="{{ asset('img/aide/inscription/boutonInscription.png') }}" alt="Bouton inscription" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Vos identifiants</h3>
                        <p class="text-gray-600 mb-4">
                            Renseignez votre <strong>adresse email</strong> et choisissez un mot de passe sécurisé. Cochez la case pour accepter les conditions générales, puis cliquez sur "Créer mon compte".
                        </p>
                        <img src="{{ asset('img/aide/inscription/premieresinfos.png') }}" alt="Formulaire inscription" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Validation par code</h3>
                        <p class="text-gray-600 mb-4">
                            Regardez vos emails : vous avez reçu un <strong>code secret</strong>. Copiez-le dans la case prévue, puis complétez votre Nom, Prénom et Téléphone.
                        </p>
                        <img src="{{ asset('img/aide/inscription/VerifMail.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number bg-green-500 !border-green-500"><i class="fa-solid fa-check"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">C'est terminé !</h3>
                        <p class="text-gray-600 mb-4">
                            Vous êtes connecté ! Vous pouvez le vérifier grâce à votre <strong>avatar</strong> (image de profil) qui apparaît en haut à droite.
                        </p>
                        <img src="{{ asset('img/aide/inscription/connectionfaite.png') }}" alt="Connexion réussie" class="guide-image">
                    </div>
                </div>
            </section>

            <section id="rdv" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg"><i class="fa-regular fa-calendar-check"></i></span>
                    Prendre un Rendez-vous
                </h2>
                <div class="bg-white rounded-xl p-6 md:p-8 border border-gray-200 shadow-sm">
                    <div class="relative pl-4 space-y-8">
                        <div class="step-line"></div>
                        
                        <div class="flex gap-6 relative">
                            <div class="step-circle">1</div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Rechercher un professionnel</h3>
                                <p class="text-gray-600 mb-3">Sur la page d'accueil, indiquez le service recherché (ex: "Toiletteur") et votre ville, puis lancez la recherche.</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_1.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Barre de recherche Hunimalis">
                            </div>
                        </div>

                        <div class="flex gap-6 relative">
                            <div class="step-circle">2</div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Choisir l'établissement</h3>
                                <p class="text-gray-600 mb-3">Dans la liste des résultats, cliquez sur le bouton <strong>"Voir sa fiche"</strong> pour accéder au profil du professionnel.</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_2.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Résultats de recherche">
                            </div>
                        </div>

                        <div class="flex gap-6 relative">
                            <div class="step-circle">3</div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Lancer la réservation</h3>
                                <p class="text-gray-600 mb-3">Sur la fiche du prestataire, cliquez sur le bouton bleu <strong>"Prendre rendez-vous"</strong> situé à droite.</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_3.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Bouton prendre rendez-vous">
                            </div>
                        </div>

                        <div class="flex gap-6 relative">
                            <div class="step-circle">4</div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Sélectionner un créneau</h3>
                                <p class="text-gray-600 mb-3">Choisissez le jour qui vous convient, puis cliquez sur l'un des horaires disponibles (affichés en bleu).</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_4.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Calendrier de réservation">
                            </div>
                        </div>

                        <div class="flex gap-6 relative">
                            <div class="step-circle">5</div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Finaliser la demande</h3>
                                <p class="text-gray-600 mb-3">Vérifiez le service (ex: "coupe chat"), sélectionnez votre animal (ex: "Smoothie") et cliquez sur <strong>"Confirmer ce Rendez-vous"</strong>.</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_5.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Formulaire de confirmation">
                            </div>
                        </div>

                        <div class="flex gap-6 relative">
                            <div class="step-circle bg-green-500 text-white !border-green-500"><i class="fa-solid fa-check"></i></div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">C'est confirmé !</h3>
                                <p class="text-gray-600 mb-3">Votre rendez-vous est validé. Vous pouvez le retrouver immédiatement dans l'onglet <strong>"Mes réservations"</strong> de votre tableau de bord.</p>
                                <img src="{{ asset('img/aide/rendezvous/rendezvous_6.png') }}" class="w-full rounded-lg shadow-md border border-gray-200" alt="Tableau de bord mes réservations">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="animaux" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg">
                        <i class="fa-solid fa-paw"></i>
                    </span>
                    Gérer vos Animaux
                </h2>

                <div class="bg-white rounded-xl p-6 md:p-8 border border-gray-200 shadow-sm">
                    <p class="text-gray-600 mb-10 text-lg">
                        C'est le carnet de santé numérique de votre compagnon. Voici comment ajouter et gérer vos animaux :
                    </p>

                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Accéder au menu</h3>
                        <p class="text-gray-600 mb-4">
                            Depuis la page d'accueil, cliquez sur votre <strong>photo de profil</strong> (avatar) située en haut à droite de l'écran.
                        </p>
                        <img src="{{ asset('img/aide/inscription/connectionfaite.png') }}" alt="Menu profil" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">L'espace "Mes Animaux"</h3>
                        <p class="text-gray-600 mb-4">
                            Cliquez sur l'onglet <strong>"Mes Animaux"</strong> dans le menu déroulant, puis sur le bouton <strong>"+ Nouvel animal"</strong>.
                        </p>
                        <img src="{{ asset('img/aide/gererAnimal/ongletanimaux.png') }}" alt="Onglet animaux" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Remplir la fiche</h3>
                        <p class="text-gray-600 mb-4">
                            Remplissez les informations (Nom, Race, Date de naissance...) puis validez le formulaire en cliquant sur le bouton <strong>"Valider"</strong>.
                        </p>
                        <img src="{{ asset('img/aide/gererAnimal/creerAnimal.png') }}" alt="Formulaire animal" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number bg-green-500 !border-green-500"><i class="fa-solid fa-check"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">C'est enregistré !</h3>
                        <p class="text-gray-600 mb-4">
                            Votre animal apparaît maintenant dans votre liste.
                            <br>
                            <span class="text-sm text-gray-500 mt-2 block">
                                <i class="fa-solid fa-pen mr-1"></i> Cliquez sur la fiche pour <strong>modifier</strong>.<br>
                                <i class="fa-solid fa-trash mr-1"></i> Cliquez sur la poubelle pour <strong>supprimer</strong>.
                            </span>
                        </p>
                        <img src="{{ asset('img/aide/gererAnimal/suppAnimal.png') }}" alt="Liste des animaux" class="guide-image">
                    </div>
                </div>
            </section>

            <section id="boutique" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg">
                        <i class="fa-solid fa-user-plus"></i>
                    </span>
                    La Boutique
                </h2>
                
                <div class="bg-white rounded-xl p-6 md:p-8 border border-gray-200 shadow-sm">
                    <p class="text-gray-600 mb-10 text-lg">
                        Pour pouvoir commander des produits, vous devez être <strong>connecté</strong>.
                    </p>

                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Aller dans la boutique</h3>
                        <p class="text-gray-600 mb-4">
                            Cliquez sur le bouton <strong>"Je recherche un produit plutôt qu'un service"</strong> situé sur la page d'accueil.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/acceuil.png') }}" alt="Bouton inscription" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Chercher un produit</h3>
                        <p class="text-gray-600 mb-4">
                            Renseignez un <strong>Article</strong> et/ou une <strong>Catégorie</strong>  et/ou une <strong>Espèce</strong> et/ou un <strong>Vendeur</strong>.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/categ.png') }}" alt="Formulaire inscription" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Visualiser un produit</h3>
                        <p class="text-gray-600 mb-4">
                            <strong>Cliquez</strong> sur le produit de votre choix.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/prod.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">4</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Ajouter au panier</h3>
                        <p class="text-gray-600 mb-4">
                            <strong>1</strong> : Choisissez votre quantité <br>
                            <strong>2</strong> : <strong>Cliquez</strong> pour ajouter au panier
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/Modifi quantité.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">5</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Accédez au panier</h3>
                        <p class="text-gray-600 mb-4">
                            Vous pouvez vérifier que votre produit a bien été ajouté. <br>
                            <strong>Cliquez</strong> sur l'icône en bas à droite pour accéder au panier.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/Ajout panier.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">6</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Modifier le panier</h3>
                        <p class="text-gray-600 mb-4">
                            <strong>1</strong> : Vous pouvez modifier la quantité du produit.<br>
                            <strong>2</strong> : Vous pouvez supprimer le produit du panier.<br>
                            <strong>3</strong> : <strong>Cliquez</strong> sur <strong>"Acheter"</strong>.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/panier.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">7</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Payer</h3>
                        <p class="text-gray-600 mb-4">
                            <strong>Si</strong> vous avez déjà enregistré votre carte bancaire :<br>
                            <strong>Cliquez</strong> sur <strong>"Payer"</strong>.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/CARTE.png') }}" alt="Validation code email" class="guide-image">
                        <p class="text-gray-600 mb-4">
                            <strong>Sinon</strong> :<br>
                            <strong>1</strong> : <strong>Cliquez</strong> sur l'onglet <strong>"Nouvelle carte"</strong> <br>
                            <strong>2</strong> : Entrez le nom du titulaire de la carte bancaire <br>
                            <strong>3</strong> : Entrez les informations de votre carte bancaire <br>
                            <strong>4</strong> : Si vous voulez enregistrer votre carte bancaire pour un futur paiement, <strong>cochez</strong> la case.<br>
                            <strong>5</strong> : <strong>Cliquez</strong> sur <strong>"Payer"</strong>.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/NV CARTE.png') }}" alt="Validation code email" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number bg-green-500 !border-green-500"><i class="fa-solid fa-check"></i></div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">C'est terminé !</h3>
                        <p class="text-gray-600 mb-4">
                            Si vous voulez voir votre commande, <strong>cliquez</strong> sur votre avatar (image de profil) en haut à droite.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/Profil.png') }}" alt="Validation code email" class="guide-image">
                        <p class="text-gray-600 mb-4">
                            Cliquez sur l'onglet <strong>"Paiement"</strong> dans le menu déroulant.<br>
                            Vous pouvez désormais visualiser vos commandes.
                        </p>
                        <img src="{{ asset('img/aide/ChercherProduits/VisCommande.png') }}" alt="Connexion réussie" class="guide-image">
                    </div>
                </div>
            </section>


            <section id="securite" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg">
                        <i class="fa-solid fa-robot"></i>
                    </span>
                    Utilisation de l'Assistant (Chatbot)
                </h2>
                
                <div class="bg-white rounded-xl p-6 md:p-8 border border-gray-200 shadow-sm">

                    <div class="guide-step">
                        <div class="guide-number">1</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Ouvrir l'assistant</h3>
                        <p class="text-gray-600 mb-4">
                            Notre assistant virtuel vous accompagne sur <strong>l'ensemble du site</strong> (Accueil, Boutique, Recherche...).
                            <br>
                            Peu importe la page où vous naviguez, cliquez simplement sur la bulle de discussion située <strong>en bas à droite</strong> de votre écran pour l'ouvrir.
                        </p>
                        <img src="{{ asset('img/aide/chatbot/chatbot.png') }}" alt="Ouverture du chatbot" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">2</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Posez votre question</h3>
                        <p class="text-gray-600 mb-4">
                            Écrivez votre demande naturellement dans la barre de saisie. <br>
                            <em>Exemple : "Je recherche des croquettes pour mon chien".</em><br>
                            L'intelligence artificielle analyse votre besoin et vous répond instantanément avec un <strong>lien personnalisé</strong>.
                        </p>
                        <img src="{{ asset('img/aide/chatbot/ExempleQuestion.png') }}" alt="Exemple de question dans le chat" class="guide-image">
                    </div>

                    <div class="guide-step">
                        <div class="guide-number">3</div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Accédez au résultat</h3>
                        <p class="text-gray-600 mb-4">
                            Cliquez sur le lien fourni par le robot. Vous serez automatiquement redirigé vers la bonne page (ici la Boutique), avec les <strong>filtres déjà appliqués</strong> (Article : Croquettes / Animal : Chien) pour vous faire gagner du temps.
                        </p>
                        <img src="{{ asset('img/aide/chatbot/ResultatQuestion.png') }}" alt="Résultat de la recherche" class="guide-image">
                    </div>
                </div>
            </section>



            <section id="faq" class="scroll-mt-28">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="bg-indigo-100 text-indigo-600 w-10 h-10 rounded-lg flex items-center justify-center mr-3 text-lg"><i class="fa-solid fa-circle-question"></i></span>
                    Questions fréquemment posées
                </h2>

                <div class="space-y-4">
                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-lock text-indigo-500"></i>
                                🔐 Mes mots de passe sont-ils en sécurité ?
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            <p class="mb-4 text-gray-700">
                                <strong>Absolument.</strong> La sécurité n'est pas une option, c'est le cœur de notre architecture. Nous utilisons les standards les plus élevés de l'industrie pour garantir que vos données restent inviolables.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <h4 class="font-bold text-gray-800 mb-1"><i class="fa-solid fa-shield-halved text-indigo-500 mr-2"></i>Chiffrement AES-256</h4>
                                    <p class="text-xs">Vos données sont transformées en code indéchiffrable (niveau militaire) avant même de quitter votre appareil.</p>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <h4 class="font-bold text-gray-800 mb-1"><i class="fa-solid fa-user-secret text-indigo-500 mr-2"></i>Zero-Knowledge</h4>
                                    <p class="text-xs">Nous ne connaissons pas votre mot de passe maître. Même nos ingénieurs ne peuvent pas voir vos données.</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <strong class="block text-gray-800 mb-1">Que se passe-t-il si vos serveurs sont piratés ?</strong>
                                    <p>Puisque vos données sont chiffrées avec votre propre clé (que nous n'avons pas), les pirates ne récupéreraient qu'une suite de caractères illisibles et inutilisables.</p>
                                </div>

                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <strong class="block text-gray-800 mb-1">Comment protégez-vous mes numéros de carte bancaire ?</strong>
                                    <p>Ils bénéficient du même niveau de chiffrement que vos mots de passe. De plus, nous utilisons des protocoles de masquage lors de l'affichage pour éviter les regards indiscrets.</p>
                                </div>

                                <div class="pl-4 border-l-2 border-indigo-200">
                                    <strong class="block text-gray-800 mb-1">Utilisez-vous la double authentification (2FA) ?</strong>
                                    <p>Oui, nous recommandons fortement d'activer la 2FA. Cela ajoute un verrou supplémentaire : même si quelqu'un trouvait votre mot de passe maître, il ne pourrait pas accéder à votre compte sans votre téléphone.</p>
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-mobile-screen-button text-indigo-500"></i>
                                <span>Je peux télécharger l'application Hunimalis ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            <p class="mb-4">
                                <strong>Oui et non !</strong> Hunimalis est une <em>Web App</em> de dernière génération. Elle ne prend pas de place sur votre téléphone et ne nécessite aucune mise à jour, tout en fonctionnant comme une vraie application.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                                        <i class="fa-brands fa-apple text-xl"></i> Sur iPhone
                                    </div>
                                    <ol class="list-decimal list-inside space-y-1 text-xs">
                                        <li>Ouvrez <strong>Safari</strong></li>
                                        <li>Appuyez sur "Partager" <i class="fa-solid fa-arrow-up-from-bracket mx-1"></i></li>
                                        <li>Sélectionnez <strong>"Sur l'écran d'accueil"</strong></li>
                                    </ol>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                                    <div class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                                        <i class="fa-brands fa-android text-xl text-green-600"></i> Sur Android
                                    </div>
                                    <ol class="list-decimal list-inside space-y-1 text-xs">
                                        <li>Ouvrez <strong>Chrome</strong></li>
                                        <li>Appuyez sur le menu (3 points) <i class="fa-solid fa-ellipsis-vertical mx-1"></i></li>
                                        <li>Sélectionnez <strong>"Ajouter à l'écran d'accueil"</strong></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-user-plus text-indigo-500"></i>
                                <span>Dois-je créer un compte pour prendre rendez-vous ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            <p class="mb-3">
                                <strong>Oui, la création d'un compte est nécessaire.</strong> Ce n'est pas juste une formalité, c'est ce qui nous permet de sécuriser et centraliser votre expérience.
                            </p>
                            
                            <p class="text-xs uppercase font-bold text-gray-400 mb-2 tracking-wider">Pourquoi créer un compte ?</p>
                            
                            <ul class="space-y-2">
                                <li class="flex items-start gap-3">
                                    <div class="mt-1 bg-indigo-50 text-indigo-600 rounded-full p-1 w-6 h-6 flex items-center justify-center shrink-0">
                                        <i class="fa-regular fa-calendar-check text-xs"></i>
                                    </div>
                                    <span><strong>Suivi des RDV :</strong> Retrouvez l'historique et les détails de vos prochaines séances.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1 bg-indigo-50 text-indigo-600 rounded-full p-1 w-6 h-6 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-paw text-xs"></i>
                                    </div>
                                    <span><strong>Carnet de santé :</strong> Centralisez les informations importantes de tous vos animaux.</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <div class="mt-1 bg-indigo-50 text-indigo-600 rounded-full p-1 w-6 h-6 flex items-center justify-center shrink-0">
                                        <i class="fa-regular fa-credit-card text-xs"></i>
                                    </div>
                                    <span><strong>Paiements sécurisés :</strong> Gérez vos factures et preuves de paiement en un clic.</span>
                                </li>
                            </ul>
                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-euro-sign text-indigo-500"></i>
                                <span>Est-ce que Hunimalis est gratuit ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            
                            <div class="mb-4">
                                <h4 class="font-bold text-indigo-600 mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i> Pour les particuliers
                                </h4>
                                <p class="mb-2">
                                    L'utilisation est <strong>100% gratuite</strong>. Pas d'abonnement, pas de frais d'inscription. Vous pouvez ajouter autant d'animaux que vous le souhaitez.
                                </p>
                            </div>

                            <div class="pt-3 border-t border-gray-100">
                                <h4 class="font-bold text-gray-800 mb-1 flex items-center gap-2">
                                    <i class="fa-solid fa-briefcase"></i> Pour les professionnels
                                </h4>
                                <p class="text-xs">
                                    Nous proposons des abonnements premium adaptés, incluant des outils de gestion avancés (agenda, facturation, rappels clients) pour booster votre activité.
                                </p>
                            </div>

                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-regular fa-calendar-xmark text-indigo-500"></i>
                                <span>Puis-je annuler ou modifier un rendez-vous ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4 space-y-4">
                            <p>
                                <strong>Oui, il est possible d'annuler votre rendez-vous.</strong> Cependant, des conditions s'appliquent pour garantir le bon fonctionnement du service pour les professionnels.
                            </p>
                            
                            <div class="bg-red-50 border-l-4 border-red-400 p-3 rounded-r-lg">
                                <h5 class="font-bold text-red-800 text-xs mb-2 flex items-center">
                                    <i class="fa-solid fa-user-xmark mr-2"></i> Annulation de votre part
                                </h5>
                                <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                                    <li>Si vous annulez <strong>moins de 24 heures</strong> avant le rendez-vous, un montant forfaitaire de <strong>30% de la prestation</strong> vous sera facturé.</li>
                                    <li>Le statut du rendez-vous deviendra alors "Annulation client".</li>
                                </ul>
                            </div>

                            <div class="bg-orange-50 border-l-4 border-orange-400 p-3 rounded-r-lg">
                                <h5 class="font-bold text-orange-800 text-xs mb-2 flex items-center">
                                    <i class="fa-solid fa-clock text-orange-600 mr-2"></i> Retard et Absence
                                </h5>
                                <p class="text-xs text-orange-700">
                                    Si vous ne vous présentez pas et qu'un retard de <strong>15 minutes</strong> est constaté, le rendez-vous sera marqué comme "Absent" et la <strong>totalité de la prestation</strong> vous sera facturée.
                                </p>
                            </div>

                            <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                                <h5 class="font-bold text-blue-800 text-xs mb-2 flex items-center">
                                    <i class="fa-solid fa-store-slash mr-2"></i> Annulation par le professionnel
                                </h5>
                                <p class="text-xs text-blue-700">
                                    Si le professionnel est dans l'impossibilité d'assurer la prestation (ex: absence d'un intervenant), le rendez-vous sera "Annulé" et vous serez immédiatement prévenu par <strong>mail et SMS</strong>.
                                </p>
                            </div>
                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-user-doctor text-indigo-500"></i>
                                <span>Les professionnels sont-ils vérifiés ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            <p class="mb-4">
                                <strong>La qualité avant tout.</strong> Nous ne laissons pas n'importe qui s'inscrire sur Hunimalis. Nous effectuons des vérifications rigoureuses pour garantir la sécurité et le bien-être de vos animaux.
                            </p>

                            <ul class="grid grid-cols-1 gap-2">
                                <li class="flex items-center gap-3 bg-indigo-50 p-2 rounded border border-indigo-100 font-semibold text-indigo-800">
                                    <i class="fa-solid fa-building-circle-check text-xl"></i>
                                    <span>Visite physique des locaux avant validation du compte</span>
                                </li>
                                <li class="flex items-center gap-3 bg-gray-50 p-2 rounded border border-gray-100">
                                    <i class="fa-solid fa-id-card text-green-500 text-lg"></i>
                                    <span>Vérification de l'identité et du SIRET</span>
                                </li>
                                <li class="flex items-center gap-3 bg-gray-50 p-2 rounded border border-gray-100">
                                    <i class="fa-solid fa-graduation-cap text-indigo-500 text-lg"></i>
                                    <span>Contrôle des diplômes et certifications</span>
                                </li>
                                <li class="flex items-center gap-3 bg-gray-50 p-2 rounded border border-gray-100">
                                    <i class="fa-solid fa-star text-yellow-400 text-lg"></i>
                                    <span>Avis clients certifiés (seuls les clients réels peuvent noter)</span>
                                </li>
                            </ul>
                        </div>
                    </details>

                    <details class="group bg-white p-5 rounded-xl border border-gray-200 cursor-pointer transition open:ring-2 open:ring-indigo-100">
                        <summary class="flex justify-between items-center font-bold text-gray-800 list-none">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-paw text-indigo-500"></i>
                                <span>J'ai plusieurs animaux, comment faire ?</span>
                            </span>
                            <span class="transition group-open:rotate-180">
                                <i class="fa-solid fa-chevron-down text-gray-400"></i>
                            </span>
                        </summary>
                        
                        <div class="mt-4 text-gray-600 text-sm leading-relaxed border-t border-gray-100 pt-4">
                            <p>
                                <strong>C'est prévu !</strong> Hunimalis est conçu pour les familles nombreuses comme pour les compagnons uniques.
                            </p>
                            <div class="mt-3 flex gap-4">
                                <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center shrink-0 text-indigo-600 text-xl font-bold">
                                    ∞
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">Ajout illimité</p>
                                    <p class="text-xs mt-1">Créez une fiche profil pour chacun de vos animaux (Chien, Chat, NAC...). Lors de la prise de rendez-vous, il vous suffit de sélectionner l'animal concerné pour que le professionnel ait le bon dossier.</p>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>
            </section>

            <div class="mt-16 pt-10 border-t border-gray-200">
                <div class="bg-white rounded-2xl p-8 md:p-10 text-center border border-gray-200 shadow-xl shadow-indigo-100 relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-50"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-green-50 rounded-full blur-3xl opacity-50"></div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Vous ne trouvez pas la réponse ?</h3>
                        <p class="text-gray-500 mb-8 max-w-lg mx-auto">Pas de panique. Notre assistant virtuel est disponible 24/7, ou vous pouvez contacter notre équipe support directement.</p>
                        
                        <div class="flex flex-col sm:flex-row justify-center gap-4">
                            <button @click="$dispatch('open-chat')" class="bg-[#1e293b] text-white px-8 py-3.5 rounded-xl font-bold shadow-lg hover:bg-black hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-center group">
                                <i class="fa-solid fa-robot mr-2 group-hover:animate-bounce"></i> Demander au Robot
                            </button>

                            <a href="mailto:hugo.allix@etu.univ-smb.fr" class="bg-white text-gray-700 border border-gray-200 px-8 py-3.5 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center justify-center">
                                <i class="fa-solid fa-envelope mr-2 text-indigo-500"></i> Envoyer un email
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <x-chatbot />

</body>
</html>