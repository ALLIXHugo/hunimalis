<?php

namespace App\Http\Controllers;

use App\Models\Professionnel;
use App\Models\Module;
use Carbon\Carbon;
use App\Models\Personne;
use App\Models\Animal;
use App\Models\Prestation;
use App\Models\TypeEtablissement;
use App\Models\Utilisateur;
use App\Models\Employe;
use App\Models\Facture;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\CodePromo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

use Vonage\Client\Credentials\Basic; 
use Vonage\Client;
use Vonage\SMS\Message\SMS;


class ProfessionnelController extends Controller
{
    public function create() 
    {
        return view('professionnel.creerpro'); 
    }

    public function index()
    {
        return $this->create();
    }

    public function store(Request $request)
    {
        $request->validate([
            'typeEtablissement' => 'required|exists:typeetablissement,libelletypeetablissement', 
            'nomEtablissement'  => 'required|string|max:50',
            'nom'               => 'required|string|max:50',
            'prenom'            => 'required|string|max:50',
            'civilite'          => 'required|in:homme,femme',
            'pays'              => 'required|string|max:50',
            'telephone'         => ['required', 'unique:professionnel,telpro', 'regex:/^(06|07|03|04)[0-9]{8}$/'],
            'email'             => ['required', 'unique:professionnel,melpro', 'email', 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'],            
            'siret'             => 'required|string|size:14|unique:professionnel,siret',
            'ville'             => 'required|string|max:50',
            'cp'                => 'required|string|max:10',
            'tva'               => 'required|in:0.2,0',
        ], [
            'typeEtablissement.required' => 'Veuillez sélectionner le type d\'activité.',
            'typeEtablissement.exists'   => 'Le type d\'activité sélectionné est invalide.', 
            'nomEtablissement.required'  => 'Veuillez entrer le nom de l\'établissement.',
            'nom.required'      => 'Veuillez entrer le nom.',
            'prenom.required'   => 'Veuillez entrer le prénom.',
            'telephone.unique'  => 'Ce numéro de téléphone est déjà enregistré.',
            'telephone.regex'   => 'Le numéro de téléphone doit commencer par 0X et contenir 10 chiffres.',
            'email.required'    => 'Veuillez entrer une adresse email.',
            'email.unique'      => 'Cet email est déjà enregistré.',
            'email.email'       => 'Veuillez entrer une adresse email valide.', 
            'email.regex'       => 'Le format de l\'adresse email est invalide.',
            'siret.required'    => 'Veuillez entrer le numéro de Siret.',
            'siret.unique'      => 'Le numéro de Siret est déjà utilisé.',
            'siret.size'        => 'Le numéro de Siret doit contenir 14 chiffres.',
            'ville.required'    => 'Veuillez entrer la ville.',
            'cp.required'       => 'Veuillez entrer le code postal.',
            'tva.required'      => 'Veuillez sélectionner le taux de TVA.',
        ]);

        DB::beginTransaction();
        $code = random_int(100000, 999999);

        try {
            $pro = Professionnel::create([
                'libelletypeetablissement' => $request->typeEtablissement, 
                'libelleetablissement'     => $request->nomEtablissement,
                'nompro'                   => $request->nom,
                'prenompro'                => $request->prenom,
                'civilitepro'              => $request->civilite,
                'telpro'                   => $request->telephone,
                'melpro'                   => $request->email,
                'pays'                     => $request->pays,
                'ville'                    => $request->ville,
                'cp'                       => $request->cp,
                'siret'                    => $request->siret,
                'tva'                      => $request->tva,
                'casierjudiciaireok'       => true,
                'nationalite'              => $request->nationalite,
                'statut'                   => 2,
                'code_verification'        => $code
            ]);
            
            $to = $request->email;
            $subject = 'Hunimalis Vérification';
            
            Mail::raw("Voici le code de vérification : {$code}", function ($message) use ($to, $subject) {
                $message->to($to)
                        ->subject($subject)
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            DB::commit();

            return redirect()->route('professionnel.verifmail', ['id' => $pro->idpro])
                             ->with('success', 'Inscription réussie. Veuillez vérifier votre e-mail.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de l\'inscription : ' . $e->getMessage());
        }
    }
    
    public function verifmail($id)
    {
        $professionnel = Professionnel::where('idpro', $id)->firstOrFail();
        return view('professionnel.verifmail', compact('professionnel')); 
    }

    public function verifverifmail(Request $request) 
    {
        $request->validate([
            'idpro' => 'required',
            'code'  => 'required'
        ]);

        $pro = Professionnel::where('idpro', $request->idpro)
                            ->where('code_verification', $request->code)
                            ->first();

        if ($pro) {
            $codeSMS = strval(rand(100000, 999999));
            
            $pro->code_verification = $codeSMS;
            $pro->save();

            try {
                $basic = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
                $client = new Client($basic);
                $numero = "33" . substr($pro->telpro, 1); 

                $client->sms()->send(
                    new SMS($numero, env('VONAGE_FROM'), "Code SMS Hunimalis : " . $codeSMS . "    ")
                );

            } catch (\Exception $e) {
                \Log::error("Erreur Vonage lors de l'envoi du SMS : " . $e->getMessage());
                return back()->with('warning', 'Email validé, mais l\'envoi du code SMS a échoué. Veuillez réessayer.');
            }

            return redirect()->route('professionnel.verifnumtel', ['id' => $pro->idpro])
                            ->with('success', 'Email validé ! Code SMS envoyé.');
        }

        return back()->withInput()->withErrors(['code' => 'Code Email incorrect.']);
    }

    public function verifnumtel($id)
    {
        $professionnel = Professionnel::where('idpro', $id)->firstOrFail();
        return view('professionnel.verifnumtel', compact('professionnel')); 
    }

    public function verifCodeSMS(Request $request)
    {
        $request->validate([
            'idpro' => 'required|exists:professionnel,idpro',
            'code'  => 'required'
        ]);

        $pro = Professionnel::where('idpro', $request->idpro)
                            ->where('code_verification', $request->code)
                            ->first();

        if ($pro) {
            return redirect()->route('professionnel.creationMDP', [
                'id' => $pro->idpro, 
                'code' => $request->code
            ])->with('success', 'Code SMS valide ! Choisissez maintenant votre mot de passe.');
        }

        return back()->withInput()->withErrors(['code' => 'Code SMS incorrect.']);
    }

    public function creationMDP($id, $code)
    {
        $pro = Professionnel::where('idpro', $id)->where('code_verification', $code)->firstOrFail();
        return view('professionnel.creermdp', compact('pro', 'code'));
    }

    public function enregistre(Request $request)
    {
        $request->validate([
            'idpro' => 'required',
            'code'  => 'required', 
            'motdepasse' => 'required|string|min:8|confirmed',
        ]);

        $pro = Professionnel::where('idpro', $request->idpro)
                            ->where('code_verification', $request->code)
                            ->first();

        if ($pro) {
            DB::beginTransaction();
            try {
                $personne = Personne::create([
                    'nom'       => $pro->nompro,
                    'prenom'    => $pro->prenompro,
                    'civilite'  => $pro->civilitepro,
                    'tel'       => $pro->telpro,
                    'mail'      => $pro->melpro,
                ]);

                $user = Utilisateur::create([
                    'idpersonne'       => $personne->idpersonne,
                    'loginutilisateur' => $pro->melpro,
                    'mdputilisateur'   => Hash::make($request->motdepasse), 
                ]);

                $pro->statut = 1;
                $pro->code_verification = null; 
                $pro->idpersonne = $personne->idpersonne;
                $pro->save();

                DB::commit();

                Auth::login($user);

                return redirect()->route('dashboard')->with('success', 'Compte créé ! Bienvenue.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Erreur technique : ' . $e->getMessage());
            }
        }

        return redirect()->route('home')->with('error', 'Session expirée ou invalide.');
    }

    private $smsPacks = [
        100  => ['price' => 7.50,  'label' => 'Découverte'], 
        500  => ['price' => 37.50, 'label' => 'Standard'],   
        1000 => ['price' => 75.00, 'label' => 'Pro'],        
    ];

    public function abonnement()
    {
        $user = Auth::user();
        $pro = Professionnel::with(['typeEtablissement.modulesDisponibles', 'modules'])
                ->where('idpersonne', $user->idpersonne)
                ->first();

        if (!$pro) {
            return redirect()->route('home')->with('error', 'Profil professionnel introuvable.');
        }

        $dateSouscription = $pro->date_souscription ? Carbon::parse($pro->date_souscription) : now();
        $isEssai = $dateSouscription->diffInDays(now()) < 30;
        $data = [
            'pro' => $pro,
            'type_abo' => strtoupper($pro->typeEtablissement->libelletypeetablissement),
            'statut' => $isEssai ? "Période d'essai" : "Abonnement Actif",
            'is_essai' => $isEssai,
            'renouvellement' => 'Automatique',
            'date_souscription' => Carbon::parse($pro->date_souscription)->format('d/m/Y'),
            'prochaine_echeance' => Carbon::parse($pro->date_renouvellement)->format('d/m/Y'),
            
            'prix_base_mensuel' => $pro->typeEtablissement->prix_base_mensuel,
            'prix_base_annuel' => $pro->typeEtablissement->prix_base_annuel,
            
            'modules_disponibles' => $pro->typeEtablissement->modulesDisponibles,
            'subscribed_ids' => $pro->modules->pluck('idmodule')->toArray(),
            'is_annuel' => $pro->mode_paiement_annuel,
            
            'sms_packs' => $this->smsPacks, 
        ];

        return view('professionnel.abonnement', compact('data'));
    }

    public function updateAbonnement(Request $request)
    {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail(); 

        $request->validate([
            'cb_numero'     => 'nullable|regex:/^[0-9]{16}$/', 
            'cb_expiration' => ['nullable', 'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/'], 
            'cb_cvv'        => 'nullable|regex:/^[0-9]{3,4}$/', 
        ], [
            'cb_numero.regex' => 'Le numéro de carte doit comporter 16 chiffres.',
            'cb_expiration.regex' => 'La date doit être au format MM/AA.',
        ]);

        $pro->mode_paiement_annuel = $request->boolean('mode_paiement_annuel');

        $modulesInclusDOffice = $pro->typeEtablissement->modulesDisponibles()
                                    ->wherePivot('est_inclus', true)
                                    ->pluck('module.idmodule')
                                    ->toArray();

        $modulesPayantsCoches = $request->input('modules', []);
        $finalModuleIds = array_unique(array_merge($modulesInclusDOffice, $modulesPayantsCoches));

        $pro->modules()->sync($finalModuleIds);

        if ($request->filled('cb_numero')) {
            $pro->cb_titulaire  = $request->cb_titulaire;
            $pro->cb_numero     = $request->cb_numero;
            $pro->cb_expiration = $request->cb_expiration;
            $pro->cb_cvv        = $request->cb_cvv;
        }

        $pro->save();

        return back()->with('success', 'Votre abonnement a été mis à jour avec succès.');
    }

    public function buySmsPack(Request $request)
    {
        $request->validate([
        'pack_quantity' => 'required|integer|in:100,500,1000',
        ]);

        $pro = Professionnel::where('idpersonne', Auth::user()->idpersonne)->firstOrFail();

        if (empty($pro->cb_numero)) {
            return back()->withErrors(['paiement' => 'Veuillez enregistrer une carte bancaire.']);
        }

        $quantity = $request->input('pack_quantity');
        $packInfo = $this->smsPacks[$quantity];
        $amountHT = $packInfo['price'];
        
        $pro->increment('credit_sms', $quantity);

        return back()->with('success', "Achat confirmé ! {$quantity} crédits SMS ajoutés (Montant : " . number_format($amountHT * 1.20, 2) . "€ TTC).");
    }

    public function dashboard()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $pro = Professionnel::with(['typeEtablissement.modulesDisponibles', 'modules'])
            ->where('idpersonne', $user->idpersonne)
            ->firstOrFail();

        $clients = DB::table('client')
            ->join('personne', 'client.idpersonne', '=', 'personne.idpersonne')
            ->whereIn('client.idclient', function($query) use ($pro) {
                $query->select('rdv.idclient')
                        ->from('rdv')
                        ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
                        ->join('employe', 'realiserdv.idemploye', '=', 'employe.idemploye')
                        ->where('employe.idpro', $pro->idpro);
            })
            ->select('personne.nom', 'personne.prenom', 'personne.tel', 'personne.mail', 'client.ville', 'client.idclient')
            ->distinct()
            ->get();
        
        $employes = Employe::with('personne')->where('idpro', $pro->idpro)->get();

        $animaux = Animal::with(['espece', 'race']) 
            ->whereIn('idclient', $clients->pluck('idclient'))
            ->get();
        
        $produits = Produit::where('idpro', $pro->idpro)
            ->orderBy('nomproduit', 'asc')
            ->get();

        $statsEspeces = $animaux->groupBy(function($animal) {
            return $animal->espece->libelleespece ?? 'Non spécifié';
        })->map(function($group) {
            return $group->count();
        });

        $statsRaces = $animaux->groupBy(function($animal) {
            return $animal->race->libellerace ?? 'Race inconnue';
        })->map(function($group) {
            return $group->count();
        })->sortDesc(); 

        $prestations = DB::table('prestation')
            ->join('espece', 'prestation.idespece', '=', 'espece.idespece')
            ->where('prestation.idpro', $pro->idpro)
            ->select('prestation.*', 'espece.libelleespece')
            ->orderBy('prestation.nomprestation')
            ->get();

        $categories = DB::table('categorieprestation')->orderBy('libellecategorie')->get();
        $especes    = DB::table('espece')->orderBy('libelleespece')->get();
        $races      = DB::table('race')->orderBy('libellerace')->get();
        
        $couleurs   = DB::table('couleur')->orderBy('libellecouleur')->get();
        $aspects    = DB::table('aspectpelage')->orderBy('libelleaspectpelage')->get();
        $tailles    = DB::table('taillepelage')->orderBy('libelletaillepelage')->get();
        $caracs     = DB::table('caracpelage')->orderBy('libellecaracpelage')->get();
        $oreilles   = DB::table('oreilles')->orderBy('libelleoreilles')->get();
        $queues     = DB::table('queue')->orderBy('libellequeue')->get();
        $yeux       = DB::table('couleuryeux')->orderBy('libellecouleuryeux')->get();
        $corpulences = DB::table('corpulence')->orderBy('libellecorpulence')->get();
        $sociabilites = DB::table('sociabilite')->orderBy('libellesociabilite')->get();
        
        $praticiens = DB::table('practicien')->orderBy('libellepracticien')->get();

        $traitsCaracteres = ['Calme', 'Joueur', 'Peureux', 'Agressif', 'Affectueux', 'Dominant', 'Solitaire'];
        $hygienes         = ['Propre', 'Sale', 'Incontinent', 'En apprentissage'];

        $prestations_jour = DB::table('rdv')
            ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
            ->join('employe', 'realiserdv.idemploye', '=', 'employe.idemploye')
            ->where('employe.idpro', $pro->idpro)
            ->whereDate('rdv.daterdv', $today)
            ->where('rdv.idstatut', '!=', 6)
            ->count();

        $categoriesProduits = DB::table('categorieproduit')->orderBy('libellecategoriepro')->get();
        
        $recettes_jour = DB::table('rdv')
            ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
            ->join('employe', 'realiserdv.idemploye', '=', 'employe.idemploye')
            ->join('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
            ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
            ->where('employe.idpro', $pro->idpro)
            ->whereDate('rdv.daterdv', $today)
            ->sum('prestation.tarifht');

        $depenses_jour = DB::table('commande')
            ->join('quantite', 'commande.idcommande', '=', 'quantite.idcommande')
            ->join('produit', 'quantite.idproduit', '=', 'produit.idproduit')
            ->where('produit.idpro', $pro->idpro)
            ->whereDate('commande.datecommande', $today)
            ->sum(DB::raw('produit.prixachat * quantite.quantite'));

        $stats = [
            'prestations_jour' => $prestations_jour,
            'recettes_jour'    => $recettes_jour,
            'depenses_jour'    => $depenses_jour,
            'repartition_especes' => $statsEspeces, 
            'repartition_races'   => $statsRaces,  
        ];

        $financialData = ['labels' => [], 'recettes' => [], 'depenses' => [], 'max' => 10];
        $daysInMonth = $startOfMonth->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::createFromDate(null, null, $i)->format('Y-m-d');
            
            $valRecette = DB::table('rdv')
                ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
                ->join('employe', 'realiserdv.idemploye', '=', 'employe.idemploye')
                ->join('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
                ->join('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
                ->where('employe.idpro', $pro->idpro)
                ->whereDate('rdv.daterdv', $date)
                ->sum('prestation.tarifht');

            $valDepense = DB::table('commande')
                ->join('quantite', 'commande.idcommande', '=', 'quantite.idcommande')
                ->join('produit', 'quantite.idproduit', '=', 'produit.idproduit')
                ->where('produit.idpro', $pro->idpro)
                ->whereDate('commande.datecommande', $date)
                ->sum(DB::raw('produit.prixachat * quantite.quantite'));

            $financialData['labels'][] = $i;
            $financialData['recettes'][] = (float)$valRecette;
            $financialData['depenses'][] = (float)$valDepense;
            
            if ($valRecette > $financialData['max']) $financialData['max'] = $valRecette;
            if ($valDepense > $financialData['max']) $financialData['max'] = $valDepense;
        }

        $rdvsMois = DB::table('rdv')
            ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
            ->join('employe', 'realiserdv.idemploye', '=', 'employe.idemploye')
            ->join('client', 'rdv.idclient', '=', 'client.idclient')
            ->join('personne', 'client.idpersonne', '=', 'personne.idpersonne')
            ->leftJoin('animal', 'rdv.numtatouage', '=', 'animal.numtatouage')
            ->leftJoin('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
            ->leftJoin('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
            ->where('employe.idpro', $pro->idpro)
            ->whereBetween('rdv.daterdv', [$startOfMonth, $endOfMonth])
            ->select('rdv.idrdv', 'rdv.idstatut', 'rdv.daterdv', 'rdv.heurerdv', 'personne.nom', 'personne.prenom', 'personne.tel', 'animal.nom1animal', 'prestation.nomprestation', 'prestation.duree', 'prestation.tarifht')
            ->orderBy('rdv.heurerdv')
            ->get();

        $planning = [];
        foreach($rdvsMois as $rdv) {
            $day = Carbon::parse($rdv->daterdv)->day;
            $heure = Carbon::parse($rdv->heurerdv)->format('H:i');
            
            $planning[$day][] = [
                'id'         => $rdv->idrdv,
                'heure'      => $heure,
                'client'     => $rdv->prenom . ' ' . $rdv->nom,
                'animal'     => $rdv->nom1animal ?? 'Non spécifié',
                'prestation' => $rdv->nomprestation ?? 'Service inconnu',
                'duree'      => $rdv->duree ?? 0,
                'tel'        => $rdv->tel,
                'tarif'      => $rdv->tarifht,
                'statut'     => $rdv->idstatut 
            ];
        }

        $calendarData = [
            'monthName' => ucfirst($startOfMonth->locale('fr')->monthName),
            'year' => $startOfMonth->year,
            'daysInMonth' => $daysInMonth,
            'emptyCellsStart' => $startOfMonth->isoWeekday() - 1,
            'planning' => $planning
        ];

        $dateSouscription = $pro->date_souscription ? Carbon::parse($pro->date_souscription) : now();
        $isEssai = $dateSouscription->diffInDays(now()) < 30;
        
        $clientIds = $clients->pluck('idclient')->toArray();
        $factures = Facture::whereIn('idclient', $clientIds)
            ->with(['client.personne', 'commande.quantites.produit'])
            ->orderBy('idfacture', 'desc')
            ->get()
            ->map(function($facture) {
                $totalCalcul = 0;
                
                if ($facture->commande && $facture->commande->quantites) {
                    foreach($facture->commande->quantites as $qte) {
                        if($qte->produit) {
                            $totalCalcul += $qte->produit->prixvente * $qte->quantite;
                        }
                    }
                }
                $facture->total_calcule = $totalCalcul;
                
                $facture->date_display = $facture->commande ? $facture->commande->datecommande : null;
                
                return $facture;
        });

        $data = [
            'type_abo'            => strtoupper($pro->typeEtablissement->libelletypeetablissement ?? 'Standard'),
            'statut'              => $isEssai ? "Période d'essai" : "Abonnement Actif",
            'is_essai'            => $isEssai,
            'renouvellement'      => 'Automatique',
            'date_souscription'   => $dateSouscription->format('d/m/Y'),
            'prochaine_echeance'  => $pro->date_renouvellement ? Carbon::parse($pro->date_renouvellement)->format('d/m/Y') : '-',
            'prix_base_mensuel'   => $pro->typeEtablissement->prix_base_mensuel ?? 0,
            'prix_base_annuel'    => $pro->typeEtablissement->prix_base_annuel ?? 0,
            'modules_disponibles' => $pro->typeEtablissement->modulesDisponibles ?? collect([]),
            'subscribed_ids'      => $pro->modules->pluck('idmodule')->toArray(),
            'is_annuel'           => $pro->mode_paiement_annuel,
            'sms_packs'           => [100 => ['price' => 7.50], 500 => ['price' => 37.50], 1000 => ['price' => 75.00]],
            'credit_sms'          => $pro->credit_sms ?? 0,
            'factures'            => $factures
        ];
        $codepromos = CodePromo::where('idpro', $pro->idpro)->orderByDesc('idcodepromo')->get();

        return view('professionnel.dashboard', compact(
            'pro', 'data', 'clients', 'animaux', 'prestations', 'produits',
            'stats', 'financialData', 'calendarData', 'categoriesProduits',
            'categories', 'especes', 'races', 
            'couleurs', 'aspects', 'tailles', 'caracs', 'oreilles', 'queues', 'yeux', 'corpulences', 'sociabilites',
            'praticiens', 'traitsCaracteres', 'hygienes', 'factures', 'employes','codepromos'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $request->validate([
            'libelleetablissement' => 'required|string|max:50',
            'libelletypeetablissement' => 'required|exists:typeetablissement,libelletypeetablissement',
            'cp' => 'required|string|max:5',
            'ville' => 'required|string|max:50',
            'pays' => 'required|string|max:50',
            'telpro' => ['required', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'],
            'melpro' => 'required|email|max:50',
            'instagram' => 'nullable|string|max:50',
            'tiktok' => 'nullable|string|max:50',
            'siret' => 'nullable|string|size:14',
            'tva' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            $pro->update([
                'libelleetablissement' => $request->libelleetablissement,
                'libelletypeetablissement' => $request->libelletypeetablissement,
                'cp' => $request->cp,
                'ville' => $request->ville,
                'pays' => $request->pays,
                'telpro' => $request->telpro,
                'melpro' => $request->melpro,
                'instagram' => $request->instagram,
                'tiktok' => $request->tiktok,
                'siret' => $request->siret,
                'tva' => $request->tva,
            ]);

            DB::commit();

            return back()->with('success', 'Profil mis à jour.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function updateEtablissement(Request $request)
    {
        $user = Auth::user(); 
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $request->validate([
            'nom'    => 'required|string|max:50', 
            'cp'     => 'nullable|string|max:5',
            'ville'  => 'nullable|string|max:50',
            'pays'   => 'nullable|string|max:50',
            'telpro' => ['nullable', 'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'],
            'melpro' => 'required|email|max:50',
            'siret'  => 'nullable|string|size:14',
            'tva'    => 'nullable|numeric',
        ]);

        try {
            $pro->update([
                'libelleetablissement' => $request->nom, 
                'cp'                   => $request->cp,
                'ville'                => $request->ville,
                'pays'                 => $request->pays,
                'telpro'               => $request->telpro,
                'melpro'               => $request->melpro,
                'siret'                => $request->siret,
                'tva'                  => $request->tva,
                'instagram'            => $request->input('instagram'), 
                'tiktok'               => $request->input('tiktok'),
            ]);

            return back()->with('success', 'Informations mises à jour avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la sauvegarde : ' . $e->getMessage());
        }
    }

    public function detailProduit($id)
    {
        $produit = Produit::with(['categorieproduit', 'professionnel'])->findOrFail($id);
        return view('produit.produitDetailClient', compact('produit'));
    }

    public function addToOrder(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        $quantity = (int) $request->input('quantity', 1);

        if ($quantity > $produit->stocks) {
            return back()->with('error', "Stock insuffisant. Seulement {$produit->stocks} disponibles.");
        }

        $panier = session()->get('brouillon_commande', []);

        if (isset($panier[$id])) {
            $newQty = $panier[$id]['quantite'] + $quantity;
            if ($newQty > $produit->stocks) {
                return back()->with('error', "Vous ne pouvez pas commander plus que le stock disponible.");
            }
            $panier[$id]['quantite'] = $newQty;
        } else {
            $panier[$id] = [
                'id' => $produit->idproduit,
                'nom' => $produit->nomproduit,
                'prix' => $produit->prixvente,
                'quantite' => $quantity,
                'image' => null 
            ];
        }

        session()->put('brouillon_commande', $panier);

        return redirect()->route('client.boutique')->with('success', "Produit ajouté au panier ($quantity x).");
    }
    public function toggleMfa(Request $request)
    {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $pro->mfa_active = !$pro->mfa_active;
        $pro->save();

        $etat = $pro->mfa_active ? 'activée' : 'désactivée';
        return back()->with('success', "La double authentification a été $etat.");
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
        ]);

        $user = Auth::user();
        
        $user->update([
            'mdputilisateur' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Votre mot de passe a été modifié avec succès.');
    }
}