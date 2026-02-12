@extends('layouts.app')

@section('content')

<style>
    header#header {
        position: sticky;
        top: 0;
        z-index: 9999;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        width: 100%;
        border-bottom: 1px solid #f3f4f6;
    }

    .navbar {
        display: flex !important;
        justify-content: space-between !important; 
        align-items: center !important;
        flex-wrap: nowrap !important;
        padding: 0 2rem; 
        max-width: 1400px;
        margin: 0 auto; 
        height: 80px;
    }

    .navbar-brand-item {
        height: 50px !important;
        width: auto !important;
        display: block;
    }

    #navbarCollapse {
        margin-left: auto !important; 
        display: flex !important;
        align-items: center;
    }

    #navbarMenu {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 1.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .nav-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
        font-weight: 600;
        border-radius: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
        cursor: pointer;
    }
    
    .nav-btn-pro { 
        color: #374151; 
        background-color: transparent; 
        border: 1px solid transparent;
    }
    .nav-btn-pro:hover { 
        color: #00C497; 
        background-color: #f0fdf9; 
    }
    
    .nav-btn-login { 
        color: #374151; 
        background: transparent; 
        border: 1px solid #e5e7eb; 
    }
    .nav-btn-login:hover { 
        color: #00C497; 
        border-color: #00C497;
    }
    
    .nav-btn-register { 
        background-color: #1e293b; 
        color: white; 
        border: none; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .nav-btn-register:hover { 
        background-color: #0f172a; 
        transform: translateY(-1px); 
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }

    .navbar-toggler { display: none; }
    
    @media (max-width: 991px) {
        #navbarCollapse { display: none; } 
        .navbar-toggler { display: block; background: none; border: none; cursor: pointer; }
        .burger-line { display: block; width: 25px; height: 3px; background-color: #333; margin: 4px 0; }
    }

    .cgu-title {
        text-align: center;
        color: #1e293b;
        margin-top: 3rem;
        margin-bottom: 2rem;
        font-size: 2.5rem;
        font-weight: 800;
    }
    .cgu-container {
        font-family: 'Segoe UI', sans-serif;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 40px;
        margin: 0 auto 60px auto;
        max-width: 1000px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        color: #4b5563;
    }
    .cgu-content h1 { 
        font-size: 1.5rem; 
        font-weight: 700; 
        color: #1e293b; 
        margin-top: 2.5rem; 
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #00C497;
        display: inline-block;
    }
    .cgu-content h2 { font-size: 1.25rem; font-weight: 600; color: #334155; margin-top: 1.5rem; margin-bottom: 0.5rem; }
    .cgu-content p { line-height: 1.7; margin-bottom: 1rem; text-align: justify; }
    .cgu-content ul { padding-left: 1.5rem; margin-bottom: 1rem; }
    .cgu-content li { list-style-type: disc; margin-bottom: 0.5rem; }
</style>

<header id="header">
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            <img class="navbar-brand-item" src="{{ asset('img/logo.webp') }}" alt="Hunimalis">
        </a>

        <button type="button" class="navbar-toggler" aria-label="Menu">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </button>

        <div id="navbarCollapse">
            <ul id="navbarMenu">
                @guest
                    <li>
                        <a href="{{ route('professionnel.create') }}" class="nav-btn nav-btn-pro">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"></rect><line x1="9" y1="22" x2="9" y2="22.01"></line><line x1="15" y1="22" x2="15" y2="22.01"></line><line x1="9" y1="18" x2="9" y2="18.01"></line><line x1="15" y1="18" x2="15" y2="18.01"></line><line x1="9" y1="14" x2="9" y2="14.01"></line><line x1="15" y1="14" x2="15" y2="14.01"></line><line x1="9" y1="10" x2="9" y2="10.01"></line><line x1="15" y1="10" x2="15" y2="10.01"></line><line x1="9" y1="6" x2="9" y2="6.01"></line><line x1="15" y1="6" x2="15" y2="6.01"></line></svg>
                            Je suis un professionnel
                        </a>
                    </li>
                    <li>
                        <button @click="openModal = true; mode = 'login'" class="nav-btn nav-btn-login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
                            Se connecter
                        </button>
                    </li>
                    <li>
                        <button @click="openModal = true; mode = 'register'" class="nav-btn nav-btn-register">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            S'inscrire
                        </button>
                    </li>
                @endguest

                @auth
                    <li>
                        <a href="{{ route('clients.dashboard') }}" title="Mon Espace">
                            @if(isset($personne) && !empty($personne->avatar))
                                <img src="{{ asset('storage/' . $personne->avatar) }}" alt="Profil" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #00C497;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'U' }}&background=00C497&color=fff" alt="Profil" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #00C497;">
                            @endif
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
</header>

<h1 class="cgu-title">Mentions légales</h1>

<div class="cgu-container cgu-content">

    <h1>1. Renseignements Juridiques</h1>
    <ul>
        <li><b>Editeur</b></li>
        <ul>
            <li><b>HUNIMALIS</b>,  SAS au capital de 8580 euros</li>
            <li>Siège : 9, rue de l'arc en ciel, 74940 Annecy-le-Vieux, France</li>
            <li>Identifiant SIRET du siège : 1*************</li>
        </ul>
        <li><b>Hébergement</b></li>
        <ul>
            <li><b>OVH</b></li>
            <li>2, Rue Kellermann, 59100 Roubaix, France</li>
        </ul>
        <li><b>Contact</b></li>
        <ul>
            <li>Directeur de publication : M. WAGNER ANTOINE</li>
            <li>Téléphone : +33 (0) 1 11 11 11 11</li>
            <li>Mail : antoine.wagner@etu.univ-smb.fr</li>
        </ul>
    </ul>

    <h1>2. ACCÈS AU SITE</h1>    
    <p>La poursuite de la navigation sur notre site internet www.hunimalis.com vaut acceptation sans réserve des dispositions et conditions d’utilisation qui suivent (ci-après les « Conditions d’Utilisation »). <br> <br>
       La version actuellement en ligne de ces Conditions d’Utilisation est la seule opposable pendant toute la durée d’utilisation du Site et jusqu’à ce qu’une nouvelle version la remplace, notamment afin de respecter toute nouvelle législation et/ou réglementation applicable et/ou afin d’améliorer le Site. <br> <br>
       Toute nouvelle modification sera intégrée dans les présentes Conditions d’Utilisation. <br> <br>
       Les présentes Conditions d’Utilisation ont pour objet de déterminer les conditions dans lesquelles Hunimalis propose l’accès à son Site et s’appliquent de plein droit à toute personne qui utilise le Site (ci-après l’«Utilisateur » ou « Vous »). <br> <br>
       HUNIMALIS A MIS EN LIGNE CE SITE AFIN DE PRÉSENTER A L’UTILISATEUR SES PRODUITS ET SERVICES, AINSI QUE DES INFORMATIONS LIÉES A SON ACTIVITÉ. <br> <br>
       Les produits et services qui sont présentés ne constituent pas une offre de vente mais une présentation générale de la gamme des produits et services distribués par Hunimalis. Par conséquent, les éléments et informations disponibles sur le Site ne sont pas contractuels. <br> <br>
       L’Utilisateur reconnaît disposer de la compétence et des moyens nécessaires pour accéder et utiliser le Site, et avoir vérifié que la configuration informatique utilisée ne contient aucun virus et qu’elle est en bon état de fonctionnement. <br> <br>
       Le Site ainsi que certaines informations et/ou documents accessibles sur le Site sont disponibles en version française et/ou en version anglaise et/ou dans d’autres langues. L’attention de l’Utilisateur est attirée sur le fait qu’en cas de divergence de rédaction entre les différentes versions, la version française et la version anglaise prévalent sur les autres langues.<br> <br>
       Bien que Hunimalis s’efforce de maintenir le Site accessible à tout moment, Hunimalis ne peut pas garantir cet accès en toute circonstance. En effet, et notamment pour des raisons de maintenance, de mise à jour, ou pour toute autre raison que Hunimalis ne maîtriserait pas, l’accès au Site pourra être interrompu.<br> <br>
    </p>

    <h1>3. DONNÉES PERSONNELLES</h1>
    <p>Conformément à la Loi Informatique n° 78-17 du 6 janvier 1978 relative à l’Informatique, aux fichiers et aux libertés, le traitement automatisé des données nominatives réalisé à partir du Site a fait l’objet d’une déclaration auprès de la Commission Nationale de l’Informatique et des Libertés (CNIL), sous le numéro de déclaration 2043819v2. <br><br>
       Lors de l’utilisation du Site, l’Utilisateur peut être amené à divulguer des données le concernant notamment ses noms et son adresse électronique. Conformément à la Loi Informatique et Libertés n° 78-17 du 06 janvier 1978 susvisée, l’Utilisateur dispose des droits d’opposition, de communication, d’accès et de rectification de ces données. Ainsi, l’Utilisateur peut exiger que soient rectifiées, complétées, mises à jour, verrouillées ou effacées les données à caractère personnel le concernant, qui sont inexactes, incomplètes, équivoques, périmées, ou dont la collecte, l’utilisation, la communication ou la conservation est interdite. <br><br>
       Conformément à la loi « informatique et libertés », vous pouvez exercer votre droit d'accès aux données vous concernant et les faire rectifier en contactant : Hunimalis - 9, rue de l'arc en ciel - 74940 Annecy-le-Vieux, France <br><br>
       Les informations nominatives concernant l’Utilisateur recueillies sur nos formulaires sont enregistrées dans un fichier informatisé par Hunimalis pour la gestion des achats, la gestion de notre clientèle. Elles sont conservées sur des serveurs situés en France pendant 1 an et sont destinées au service marketing et au service commercial. <br><br>
       L’Utilisateur du Site est tenu de respecter les dispositions de la loi relative à l’Informatique, aux fichiers et aux Libertés, dont la violation est passible de sanctions pénales. L’Utilisateur doit notamment s’abstenir, s’agissant des informations nominatives auxquelles il accède, de toute collecte, de toute utilisation détournée et d’une manière générale, de tout acte susceptible de porter atteinte à la vie privée et à la réputation des personnes. <br><br>
       L’Utilisateur s’engage à ne pas transmettre, par quelque procédé que ce soit :
       <ul>
           <li>Tout contenu contenant des programmes malveillants (codes, programmes, virus…) destinés à détruire ou limiter les fonctionnalités du Site;</li>
           <li>Tout contenu violant les droits de propriété intellectuelle d’un tiers;</li>
           <li>Tout contenu illicite ou nuisible, notamment des messages à caractère injurieux, insultant, diffamant, dénigrant, dégradant, pornographique ou sans rapport avec les thèmes proposés sur le Site.</li>
       </ul> <br>
       Hunimalis se réserve le droit de supprimer immédiatement, et sans mise en demeure préalable, tout contenu de quelque nature que ce soit, et notamment tout message, texte, image, graphique, son qui contreviendrait aux lois et réglementations applicables.
    </p>

    <h1>4. POLITIQUE EN MATIÈRE DE COOKIES</h1>
    <p>Lors de la consultation du Site www.hunimalis.com, des informations sont susceptibles d’être enregistrées dans des fichiers « Cookies » installés dans votre ordinateur, tablette ou téléphone mobile. Ces dispositions permettent de comprendre ce qu’est un Cookie, à quoi il sert et comment on peut le paramétrer.</p>

    <h2>Définition d'un Cookie</h2>
    <p>Il s’agit d’un fichier texte déposé dans un espace dédié du disque dur de votre terminal (ordinateur, tablette, téléphone mobile ou tout autre appareil permettant la navigation sur l'Internet), lors de la consultation d’un contenu ou d’une publicité en ligne. Ce fichier Cookie ne peut être lu que par son émetteur. Il permet d’identifier votre terminal dans lequel il est enregistré, pendant une durée de validité limitée.</p>

    <h2>Cookies déposés</h2>
    <p>Nous utilisons ces types de cookies:</p>
    <ul>
        <li><b>requis</b>- cookies, qui sont inévitables pour faire fonctionner le site web;</li>
        <li><b>analytiques</b>- cookies (y compris tiers), qui sont nécessaires pour analyser les visites et le comportement des visiteurs du site web, principalement les cookies du service Google Analytics;</li>
        <li><b>marketing</b>- cookies (y compris tiers), qui sont nécessaires pour vous offrir des fonctions de médias sociaux, de support client et des fonctionnalités de marketing, y compris la personnalisation des publicités sur le site web et la possibilité de cibler des publicités même en dehors du site;</li>
        <li><b>préférences</b>- cookies, qui sont utilisés pour personnaliser le contenu en fonction de l'utilisateur.</li>
    </ul> <br>
    <p><i>Cookies de mesure d'audience</i> <br><br>
        Ces Cookies sont émis par nous ou par nos prestataires techniques aux fins de mesurer l’audience des différents contenus et rubriques de notre Site, afin de les évaluer et de mieux les organiser. Ces Cookies permettent également, le cas échéant, de détecter des problèmes de navigation et par conséquent d’améliorer l’ergonomie de nos services. Ces Cookies ne produisent que des statistiques anonymes et des volumes de fréquentation, à l’exclusion de toute information individuelle. La durée de vie de ces Cookies de mesure d’audience n’excède pas 13 mois. <br><br>
        Cookies utilisés par Hunimalis SAS :
        <ul>
            <li>Nom du cookie : <b>Hunimalis</b></li>
            <li>Fonction : Ce Cookie permet d’analyser l’intérêt des Utilisateurs pour notre Site</li>
            <li>Société : HunimalisS331</li>
            <li>Finalité : Navigation</li>
            <br>
            <li>Nom du cookie : <b> Google Analytics</b></li>
            <li>Fonction : Ce Cookie est utilisé pour analyser l’audience de notre Site.</li>
            <li>Société : Google</li>
            <li>Finalité : Navigation</li>
        </ul>   
    </p>

    <h2>Gérer vos Cookies</h2>
    <p>En poursuivant votre navigation sur le Site, vous acceptez le dépôt de Cookies sur votre terminal. Vous avez la possibilité de paramétrer votre navigateur en tenant compte de la finalité des Cookies déposés. Étant précisé que le refus de dépôt de certains Cookies pourra altérer votre expérience sur le Site. La gestion des Cookies et de vos choix est différente en fonction du navigateur que vous utilisez et fait l’objet d’une description dans le menu aide de celui-ci. Si votre terminal est utilisé par plusieurs personnes, ce partage d’utilisation et la configuration des paramètres de votre navigateur pour les Cookies relèvent de votre responsabilité.</p>

    <h1>5. PROPRIÉTÉ INTELLECTUELLE</h1>
    <p>La structure générale, l’arborescence, ainsi que les logiciels, textes, images animées ou fixes, sons, savoir-faire, dessins, graphismes (…) et tout autre élément composant le Site sont la propriété exclusive de Hunimalis et/ou de ses concédants. <br>
        Les logiciels, données, textes, informations, images animées ou fixes, sons, savoir-faire, dessins, graphismes, noms de domaine, documents téléchargeables, et/ou tout autre élément composant le Site font l´objet d´une protection au titre du droit de la propriété intellectuelle. <br>
        Il en est de même des bases de données figurant sur le Site, qui sont protégées par les dispositions de la loi du 1er juillet 1998 portant transposition dans le Code de la propriété intellectuelle de la directive européenne du 11 mars 1996 relative à la protection juridique des bases de données et dont Hunimalis est productrice. <br>
        Hunimalis concède à l’Utilisateur le droit d’utiliser le Site pour ses besoins propres et des fins d’information, à l’exclusion de toute utilisation lucrative ou professionnelle, et conformément aux conditions non limitatives suivantes : 
        <ul>
            <li>Non dissociation des éléments graphiques, photos, schémas et des textes les accompagnants;</li>
            <li>Utilisation pour ses besoins propres, exclusivement à des fins d’information;</li>
            <li>Non modification, non altération des informations et éléments contenus dans le Site.</li>
        </ul><br>
        L’UTILISATEUR S’INTERDIT EXPRESSÉMENT DE COPIER, REPRODUIRE, ADAPTER, EXTRAIRE, REPRÉSENTER, COMMERCIALISER, ALTÉRER, DÉNATURER, MODIFIER ET/OU EXPLOITER, DE QUELQUE FAÇON QUE CE SOIT, SUR QUELQUE SUPPORT QUE CE SOIT ET À QUELQUE FIN QUE CE SOIT, TOUT OU PARTIE DE LA STRUCTURE ET DU CONTENU DU SITE. <br>
        Les produits tiers, sociétés tierces, contenus tiers et sites web mentionnés ou référencés via le Site ou par des annonceurs, ainsi que certains contenus appartenant à des tiers et témoignages, le cas échéant, sont protégés par le droit d’auteur, le droit des marques ou tout autre droit reconnu par la législation en vigueur, auxquels l’Utilisateur s’engage à se conformer. <br>
        Tout contrevenant aux présentes dispositions relatives à la propriété intellectuelle se verra exposé à des poursuites judiciaires en contrefaçon. <br>
    </p>

    <h1>6. GARANTIE ET RESPONSABILITÉ</h1>
    <p>Hunimalis est susceptible d’apporter, à tout moment et pour quelque motif que ce soit, des améliorations et/ou modifications des informations contenues ou référencées dans ce Site. <br> <br>
        Le Site, ainsi que tous les éléments, et contenus y afférents, sont fournis « en l’état ». En conséquence, Hunimalis n’apporte aucune garantie quant à l’exactitude, la mise à jour ou l’exhaustivité desdites informations et/ou quant aux éventuelles conséquences dommageables de ces améliorations et/ou modifications. Les informations figurant sur le Site sont à titre indicatif et ne peuvent en aucun cas valoir offre contractuelle de produits ou services. <br><br>
        Hunimalis ne garantit pas non plus l’aptitude de ces informations à un usage particulier quelconque et ne saurait être tenue pour responsable, à quelque titre que ce soit, de tout dommage consécutif à une mauvaise utilisation, à une erreur d’interprétation ou encore à une inexactitude relative à ces informations. <br> <br>
        Toute garantie de Hunimalis, même expresse, implicite ou autre, relative à une action, réclamation, revendication, opposition de la part de toute personne revendiquant un droit de propriété intellectuelle ou un acte de concurrence déloyale et/ou parasitaire relatifs aux informations contenues ou référencées dans le présent Site, et de manière générale toute action, réclamation, revendication, opposition de quelque nature que ce soit, est exclue. <br> <br>
        Il appartient à l’Utilisateur du Site de prendre des mesures afin de protéger ses propres données, matériel ou applications lorsqu’il circule sur Internet. En conséquence, Hunimalis ne pourra pas être tenue de quelque altération, détérioration, intrusion ou infection par un virus informatique. <br> <br>
        Le Site comprend des liens hypertextes vers des sites internet tiers ou d’autres sources internet. Hunimalis ne pouvant en contrôler le contenu, cette dernière ne pourra en aucun cas être tenue responsable des informations et contenus de ces dits sites et sources accessibles, ou des éventuelles collectes et transmission de données personnelle, installation de cookies ou tout autre procédé tendant aux mêmes fins, effectués par des sites tiers. Il est donc conseillé à l’Utilisateur de vérifier les conditions générales d’utilisation desdits sites préalablement à toute utilisation. <br> <br>
        De manière générale et en aucune circonstance, Hunimalis ne pourra être tenue responsable de tout dommage, incluant sans limitation, les dommages directs, indirects, spéciaux, accidentels, ou consécutifs liés à l’utilisation ou à l’accès au Site par l’Utilisateur. <br><br>
    </p>

    <h1>7. DROIT APPLICABLE ET ATTRIBUTION DE JURIDICTION</h1>
    <p>Les présentes Conditions d’Utilisation sont soumises au droit français. En cas de conflit, le Tribunal de Commerce de Paris sera seul compétent. <br><br>
        <b>L’Utilisateur peut contacter Hunimalis pour toute question relative aux Conditions d’Utilisation ou au Site, <a href="cgu">en cliquant ici</a>.</b> <br><br>
        IL EST CONSEILLÉ À TOUT UTILISATEUR DE CONSERVER ET/OU D’IMPRIMER LES PRÉSENTES CONDITIONS D’UTILISATION.
    </p>

</div>

@endsection