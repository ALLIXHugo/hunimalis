<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 

use App\Http\Controllers\ClientController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\ProfessionnelController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PlanningEmployeController;
use App\Http\Controllers\PlanningGlobalController;
use App\Http\Controllers\PosteController;
use App\Http\Controllers\ComportementController;
use App\Http\Controllers\PriseMesureController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\AdminValidationController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\CategoriePrestationController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MfaController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\CodePromoController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\AdminProController;

use App\Models\Professionnel;
use App\Models\Personne;
use App\Models\Espece;
use App\Models\Animal;
use App\Models\Client;

/*
|--------------------------------------------------------------------------
| 1. PAGES GÉNÉRALES & STATIQUES (ACCES PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $personne = null;
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->idpersonne) {
            $personne = \App\Models\Personne::find($user->idpersonne);
        }
    }
    return view('welcome', compact('personne')); 
})->name('home');

Route::get('/dev', function () { return view('dev'); })->name('dev');
Route::view('/cgu', 'utilisateur.cgu')->name('cgu');
Route::view('/mentionsLegales', 'utilisateur.mentionsLegales')->name('mentionsLegales');
Route::view('/aide', 'clients.aide')->name('client.aide');

Route::get('/test-gate', function () {
    return Gate::allows('viewPulse') ? 'allowed' : 'denied';
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTIFICATION & SÉCURITÉ
|--------------------------------------------------------------------------
*/

Route::get('/login', [UtilisateurController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UtilisateurController::class, 'login']);
Route::post('/logout', [UtilisateurController::class, 'logout'])->name('logout');
Route::get('/register', [UtilisateurController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [UtilisateurController::class, 'register']);

Route::get('/utilisateur/verification/{id}', [UtilisateurController::class, 'showVerifMail'])->name('utilisateur.verifmail');
Route::post('/utilisateur/verification', [UtilisateurController::class, 'verifyMailCode'])->name('utilisateur.validercode');

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/mot-de-passe-oublie', [UtilisateurController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/mot-de-passe-oublie/send', [UtilisateurController::class, 'sendResetCode'])->name('password.send');
Route::get('/mot-de-passe/verification', [UtilisateurController::class, 'showVerifyCodeForm'])->name('password.verify');
Route::post('/mot-de-passe/verification', [UtilisateurController::class, 'checkResetCode'])->name('password.check');
Route::get('/mot-de-passe/reset', [UtilisateurController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/mot-de-passe/reset', [UtilisateurController::class, 'resetPassword'])->name('password.update.new');

Route::get('/mfa/verify', [MfaController::class, 'index'])->name('mfa.index');
Route::post('/mfa/verify', [MfaController::class, 'verify'])->name('mfa.verify');

/*
|--------------------------------------------------------------------------
| 3. ONBOARDING PROFESSIONNEL (INSCRIPTION & VERIFICATION)
|--------------------------------------------------------------------------
*/

Route::get('/professionnel/creer', [ProfessionnelController::class, 'create'])->name('professionnel.create');
Route::post('/professionnel/store', [ProfessionnelController::class, 'store'])->name('professionnel.store');
Route::get('/professionnel/verifmail/{id}', [ProfessionnelController::class, 'verifmail'])->name('professionnel.verifmail');
Route::post('/professionnel/verifverifmail', [ProfessionnelController::class, 'verifverifmail'])->name('professionnel.verifverifmail');
Route::get('/professionnel/verifnumtel/{id}', [ProfessionnelController::class, 'verifnumtel'])->name('professionnel.verifnumtel');
Route::post('/professionnel/verifCodeSMS', [ProfessionnelController::class, 'verifCodeSMS'])->name('professionnel.verifCodeSMS');
Route::get('/professionnel/mot-de-passe/{id}/{code}', [ProfessionnelController::class, 'creationMDP'])->name('professionnel.creationMDP');
Route::post('/professionnel/enregistre', [ProfessionnelController::class, 'enregistre'])->name('professionnel.enregistre');

/*
|--------------------------------------------------------------------------
| 4. RECHERCHE & API PUBLIQUE (AJAX)
|--------------------------------------------------------------------------
*/

Route::get('/trouver-un-pro', [PrestationController::class, 'rechercher'])->name('client.recherche');
Route::get('/etablissement/{id}', [PrestationController::class, 'show'])->name('client.pro.details');
Route::post('/api/search-cp', [ProfessionnelController::class, 'searchCpByVille'])->name('api.searchcp');

Route::get('/api/villes/search', function (Illuminate\Http\Request $request) {
    $search = $request->input('q');
    if (strlen($search) < 2) return response()->json([]);

    $villes = DB::table('professionnel')
                ->select('ville', 'cp')
                ->distinct()
                ->where('ville', 'ILIKE', "%{$search}%")
                ->orWhere('cp', 'LIKE', "{$search}%")
                ->limit(10)
                ->get();
    return response()->json($villes);
})->name('api.villes.search');

Route::get('/api/search/global', function (Illuminate\Http\Request $request) {
    $search = $request->input('q');
    if (strlen($search) < 2) return response()->json([]);

    $etablissements = DB::table('professionnel')
        ->select('libelleetablissement as label')
        ->where('libelleetablissement', 'ILIKE', "%{$search}%")
        ->limit(5)->get()
        ->map(function ($item) { $item->type = 'etablissement'; return $item; });

    $prestations = DB::table('prestation')
        ->select('nomprestation as label')
        ->distinct()
        ->where('nomprestation', 'ILIKE', "%{$search}%")
        ->limit(5)->get()
        ->map(function ($item) { $item->type = 'prestation'; return $item; });

    return response()->json($etablissements->merge($prestations));
})->name('api.search.global');

/*
|--------------------------------------------------------------------------
| 5. E-COMMERCE CLIENT (BOUTIQUE, PANIER)
|--------------------------------------------------------------------------
*/

Route::get('/produits', [ProduitController::class, 'index'])->name('produit.index'); 
Route::get('/boutique', [ProduitController::class, 'boutique'])->name('client.boutique');
Route::get('/boutique/produit/{id}', [ProduitController::class, 'detailProduit'])->name('produit.details');

Route::get('/panier', [ProduitController::class, 'panierClient'])->name('client.panier');
Route::post('/boutique/panier/ajouter/{id}', [ProduitController::class, 'addToOrder'])->name('produit.addToOrder');
Route::patch('/panier/update/{id}', [ProduitController::class, 'updatePanierClient'])->name('client.panier.update');
Route::delete('/panier/delete/{id}', [ProduitController::class, 'supprimerDuPanierClient'])->name('client.panier.delete');
Route::get('/panier/vider', [ProduitController::class, 'viderPanierClient'])->name('client.panier.vider');

Route::middleware(['auth'])->group(function () {
    Route::get('/client/paiement', [ProduitController::class, 'checkout'])->name('client.paiement');
    Route::post('/client/paiement/valider', [ProduitController::class, 'processPaiement'])->name('client.paiement.process');
    
    Route::post('/panier/code-promo', [ProduitController::class, 'appliquerCodePromo'])->name('client.panier.promo');
    Route::get('/panier/code-promo/remove', [ProduitController::class, 'retirerCodePromo'])->name('client.panier.promo.remove');
});

/*
|--------------------------------------------------------------------------
| 6. GESTION RESSOURCES TRANSVERSES (ANIMAUX, CHAT, MESURES)
|--------------------------------------------------------------------------
*/

Route::get("/animaux", [AnimalController::class, "index"])->name('animal.index'); 
Route::get("/animaux/creer", [AnimalController::class, "create"])->name('animal.create'); 
Route::post("/animaux", [AnimalController::class, "store"])->name('animal.store'); 
Route::get("/animaux/voir/{numtatouage}", [AnimalController::class, "show"])->name('animal.show');
Route::put("/animaux/{numtatouage}", [AnimalController::class, "update"])->name('animal.update'); 
Route::delete('/animaux/{numtatouage}', [AnimalController::class, 'destroy'])->name('animal.destroy');
Route::get('/animal/{numtatouage}', [AnimalController::class, 'showSante'])->name('animal.sante'); 

Route::get('/comportements/creer', [ComportementController::class, 'create'])->name('comportements.create');
Route::post('/comportements', [ComportementController::class, 'store'])->name('comportements.store');
Route::get('/mesures/creer', [PriseMesureController::class, 'create'])->name('mesures.create'); 
Route::post('/mesures', [PriseMesureController::class, 'store'])->name('mesures.store');
Route::post('/mesure/store', [PriseMesureController::class, 'storeSingle'])->name('mesure.store');

Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');


/*
|--------------------------------------------------------------------------
| 7. ESPACE CLIENT (MON COMPTE, RDV)
|--------------------------------------------------------------------------
*/

Route::get("/clients/creer",[ClientController::class, "create" ])->name('client.create'); 
Route::post("/clients",[ClientController::class, "store" ])->name('client.store'); 

Route::get('/rdv/client', [RdvController::class, 'create'])->name('rdv.client.create');
Route::post('/rdv', [RdvController::class, 'store'])->name('rdv.client.store');
Route::get('/rdv', [RdvController::class, 'index'])->name('rdv.client.index');
Route::get('/rdv/{id}', [RdvController::class, 'show'])->name('rdv.client.show')->whereNumber('id');
Route::patch('/rdv/cancel/{id}', [RdvController::class, 'cancel'])->name('rdv.cancel');
Route::patch('/rdv/{id}/update-status', [RdvController::class, 'updateStatus'])->name('rdv.updateStatus');
Route::get('/rdv/{id}/facture/create', [RdvController::class, 'createFacture'])->name('rdv.facture.create');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/reservation/confirmation', [RdvController::class, 'createClient'])->name('rdv.client.confirm');
    Route::post('/reservation/validation', [RdvController::class, 'storeClient'])->name('rdv.client.store_confirm');
    
    Route::get('/rdv/details/{id}', [RdvController::class, 'getDetails'])->name('rdv.details');
    Route::post('/rdv/traiter/{id}', [RdvController::class, 'traiterRdv'])->name('rdv.traiter');

    Route::post('/clients/animaux', [AnimalController::class, 'store'])->name('animal.store.client');
    Route::get('/parametres', [ClientController::class, 'settings'])->name('client.settings');
    Route::put('/parametres/update-password', [ClientController::class, 'updatePassword'])->name('client.password.update');
    Route::delete('/client/carte/{id}', [ClientController::class, 'deleteCard'])->name('client.carte.delete');

    Route::prefix('mon-compte')->group(function () {
        Route::get('/', function () {
            $user = Auth::user();
            $personne = Personne::find($user->idpersonne);
            $especes = Espece::all(); 
            $nbAnimaux = 0; $nbRdv = 0; 
            $client = Client::where('idpersonne', $user->idpersonne)->first();
            if ($client) {
                $nbAnimaux = Animal::where('idclient', $client->idclient)->count();
            }
            return view('clients.dashboard', compact('user', 'personne', 'especes', 'nbAnimaux', 'nbRdv'));
        })->name('clients.dashboard');

        Route::get('/supprimer', [ClientController::class, 'showDeletePage'])->name('client.profile.delete');
        Route::delete('/supprimer', [ClientController::class, 'destroy'])->name('client.profile.destroy');
        Route::patch('/client/data/delete/{field}', [ClientController::class, 'deleteSpecificData'])->name('client.data.delete');
        Route::put('/profil', [ClientController::class, 'updateProfile'])->name('client.profile.update');
        Route::get('/animaux', [AnimalController::class, 'animals'])->name('clients.animals');
        Route::get('/paiements', [ClientController::class, 'mesPaiements'])->name('client.paiements.index');
        
        Route::get('/animal/nouveau', [AnimalController::class, 'create'])->name('animal.create.client');
        Route::post('/animal/nouveau', [AnimalController::class, 'store'])->name('animal.store.client');
    });
});


/*
|--------------------------------------------------------------------------
| 8. ESPACE PROFESSIONNEL (GESTION, PLANNING, VENTE)
|--------------------------------------------------------------------------
*/

Route::post('/professionnel/update-etablissement', [ProfessionnelController::class, 'updateEtablissement'])
    ->name('professionnel.updateEtablissement');

Route::get('/validation/demande', [ValidationController::class, 'create'])->name('pro.validation.create');
Route::post('/validation/demande', [ValidationController::class, 'store'])->name('pro.validation.store');

Route::middleware(['auth'])->group(function () {
    
    Route::post('/professionnel/mfa/toggle', [ProfessionnelController::class, 'toggleMfa'])->name('pro.mfa.toggle');
    Route::put('/update-password', [ProfessionnelController::class, 'updatePassword'])->name('pro.password.update');

    Route::get('/professionnel/abonnement', [ProfessionnelController::class, 'abonnement'])->name('professionnel.abonnement');
    Route::post('/professionnel/abonnement', [ProfessionnelController::class, 'updateAbonnement'])->name('professionnel.updateAbonnement');
    Route::post('/professionnel/achat-sms', [ProfessionnelController::class, 'buySmsPack'])->name('professionnel.buySmsPack');

    Route::prefix('espace-pro')->group(function () {
        Route::get('/', [ProfessionnelController::class, 'dashboard'])->name('pro.dashboard');
        Route::put('/etablissement/update', [ProfessionnelController::class, 'updateEtablissement'])->name('etablissement.update');
        
        Route::get('/rdv/nouveau', [RdvController::class, 'create'])->name('rdv.employe.create');
        Route::post('/rdv/enregistrer', [RdvController::class, 'store'])->name('rdv.employe.store');

        Route::get('/panier', [ProduitController::class, 'panierPro'])->name('pro.panier');
        Route::post('/panier/ajouter/{id}', [ProduitController::class, 'ajouterAuPanierPro'])->name('panier.ajouter');
        Route::delete('/panier/supprimer/{id}', [ProduitController::class, 'supprimerDuPanierPro'])->name('panier.supprimer');
        Route::get('/panier/vider', [ProduitController::class, 'viderPanierPro'])->name('panier.vider');
        Route::post('/panier/valider', [ProduitController::class, 'validerCommandePro'])->name('panier.valider');
        Route::patch('/panier/update/{id}', [ProduitController::class, 'updatePanierPro'])->name('panier.modifier');
        Route::post('/produit/stock/{id}', [ProduitController::class, 'updateStock'])->name('produit.stock.update');
        Route::post('/facture/{id}/avoir', [FactureController::class, 'creerAvoir'])->name('facture.creerAvoir');

        Route::post('/codepromo', [CodePromoController::class, 'store'])->name('codepromo.store');
        Route::delete('/codepromo/{id}', [CodePromoController::class, 'destroy'])->name('codepromo.destroy');
    });

    Route::get('/pro/stocks', [ProduitController::class, 'gestionStock'])->name('produit.stock');
    Route::post('/pro/stocks/{id}/update', [ProduitController::class, 'updateStockRapide'])->name('produit.stock.update');

    Route::post('/pro/vente/store', [VenteController::class, 'store'])->name('pro.vente.store');
    Route::post('/pro/vente/check-promo', [VenteController::class, 'checkPromo'])->name('pro.vente.check-promo');

    Route::get('/employes/creer', [EmployeController::class, 'create'])->name('employes.create');
    Route::post('/employes', [EmployeController::class, 'store'])->name('employes.store'); 
    Route::resource('planning', PlanningEmployeController::class)->only(['index', 'create', 'store']);
    Route::resource('planning-pro', PlanningGlobalController::class)->only(['index', 'create', 'store']);
    Route::get('/rdv/planning-visuel', [RdvController::class, 'planning'])->name('rdv.employe.planning');
    Route::get('/postes/creer', [PosteController::class, 'create'])->name('postes.create');
    Route::post('/postes', [PosteController::class, 'store'])->name('postes.store');

    Route::get('/produit/creer', [ProduitController::class, 'create'])->name('produit.create');
    Route::post('/produit', [ProduitController::class, 'store'])->name('produit.store');
    Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produit.show');
    Route::get('/produit/{id}/edit', [ProduitController::class, 'edit'])->name('produit.edit');
    Route::put('/produit/{id}', [ProduitController::class, 'update'])->name('produit.update');

    Route::get('/categorie/create', [CategoriePrestationController::class, 'create'])->name('prestation.categories.create');
    Route::post('/categories', [CategoriePrestationController::class, 'store'])->name('prestation.categories.store');
    Route::get('/categories', [CategoriePrestationController::class, 'index'])->name('prestation.categories.index');
    Route::get('/prestation/create', [PrestationController::class, 'create'])->name('prestation.create');
    Route::post('/prestations', [PrestationController::class, 'store'])->name('prestation.store');
    Route::get('/prestations', [PrestationController::class, 'index'])->name('prestation.index');
    Route::get('/prestation/{id}/edit', [PrestationController::class, 'edit'])->name('prestation.edit');
    Route::put('/prestations/{id}', [PrestationController::class, 'update'])->name('prestation.update');

    Route::post('/facture/{id}/valider', [FactureController::class, 'valider'])->name('facture.valider');
    Route::post('/facture/{id}/annuler', [FactureController::class, 'annuler'])->name('facture.annuler');
    Route::get('/facture/{id}/mail', [FactureController::class, 'envoyerParMail'])->name('facture.mail');
    Route::get('/facture/{id}/download', [FactureController::class, 'downloadPDF'])->name('facture.download');
    Route::put('/facture/{id}/update', [FactureController::class, 'update'])->name('facture.update');
});

/*
|--------------------------------------------------------------------------
| 9. ROUTAGE GLOBAL (DASHBOARD REDIRECT)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
    
        if (!$user) return redirect('/login');
        if (!$user->idpersonne) return redirect('/'); 
    
        $isPro = DB::table('professionnel')
                    ->where('idpersonne', $user->idpersonne)
                    ->exists();
    
        if ($isPro) {
            return redirect()->route('pro.dashboard');
        }
    
        $isClient = DB::table('client')
                    ->where('idpersonne', $user->idpersonne)
                    ->exists();
    
        if ($isClient) {
            return redirect()->route('clients.dashboard');
        }
    
        return redirect('/'); 
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| 10. ADMINISTRATION & VALIDATION
|--------------------------------------------------------------------------
*/

// Validation des requêtes
Route::get('/validation-requests', [AdminValidationController::class, 'index'])->name('rdv.validation.index');
Route::post('/validation-requests/{id}/accept', [AdminValidationController::class, 'accept'])->name('rdv.validation.accept');
Route::post('/validation-requests/{id}/refuse', [AdminValidationController::class, 'refuse'])->name('rdv.validation.refuse');

// Panel Admin 
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/validPro', [AdminProController::class, 'index'])->name('admin.validPro.index');
    
    Route::put('/validPro/{id}/accepter', [AdminProController::class, 'accept'])->name('admin.pro.accepter');
    Route::put('/validPro/{id}/refuser', [AdminProController::class, 'refuse'])->name('admin.pro.refuser');
});