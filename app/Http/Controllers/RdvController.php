<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use App\Models\RDV;
use App\Models\Facture;
use Illuminate\Support\Facades\DB;
use App\Models\Animal;
use App\Models\Practicien;
use App\Models\Prestation;
use App\Models\Personne;
use App\Models\Client;
use App\Models\Professionnel;
use App\Models\A_HoraireEmp;

class RdvController extends Controller
{
    /*  */
    public function index(Request $request)
    {
        $user = Auth::user();
        $client = Client::where('idpersonne', $user->idpersonne)->firstOrFail();

        $query = Rdv::query()
            ->select('rdv.*') 
            ->with(['animal', 'prestations.professionnel', 'statut'])
            ->where('rdv.idclient', $client->idclient);

        $query->leftJoin('animal', 'rdv.numtatouage', '=', 'animal.numtatouage')
              ->leftJoin('statutrdv', 'rdv.idstatut', '=', 'statutrdv.idstatut')
              ->leftJoin('liepresta', 'rdv.idrdv', '=', 'liepresta.idrdv')
              ->leftJoin('prestation', 'liepresta.idprestation', '=', 'prestation.idprestation')
              ->leftJoin('professionnel', 'prestation.idpro', '=', 'professionnel.idpro')
              ->distinct(); 


        $tab = $request->input('tab', 'avenir');
        $now = Carbon::now();
        $today = Carbon::today();
        
        $currentTime = $now->format('H:i:s');          
        $timeMinus1h = $now->copy()->subHour()->format('H:i:s'); 

        if ($tab === 'encours') {
            
            $query->whereDate('rdv.daterdv', $today)
                  ->whereTime('rdv.heurerdv', '<=', $currentTime) 
                  ->whereTime('rdv.heurerdv', '>', $timeMinus1h)  
                  ->whereNotIn('rdv.idstatut', [3, 6]);
        
        } elseif ($tab === 'passe') {
            $query->where(function($q) use ($today, $timeMinus1h) {
                $q->whereDate('rdv.daterdv', '<', $today)
                  ->orWhere(function($subQ) use ($today, $timeMinus1h) {
                      $subQ->whereDate('rdv.daterdv', '=', $today)
                           ->whereTime('rdv.heurerdv', '<=', $timeMinus1h); 
                  })
                  ->orWhereIn('rdv.idstatut', [3, 6]);
            });

        } else { 
            $query->where(function($q) use ($today, $currentTime) {
                $q->whereDate('rdv.daterdv', '>', $today) 
                  ->orWhere(function($subQ) use ($today, $currentTime) {
                      $subQ->whereDate('rdv.daterdv', '=', $today)
                           ->whereTime('rdv.heurerdv', '>', $currentTime); 
                  });
            })->whereNotIn('rdv.idstatut', [3, 6]);
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('animal.nom1animal', 'ILIKE', "%{$search}%")
                  ->orWhere('professionnel.libelleetablissement', 'ILIKE', "%{$search}%")
                  ->orWhere('statutrdv.libellestatut', 'ILIKE', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'daterdv');
        $direction = $request->input('direction', 'asc');

        switch ($sort) {
            case 'etablissement':
                $query->addSelect('professionnel.libelleetablissement')
                      ->orderBy('professionnel.libelleetablissement', $direction);
                break;

            case 'animal':
                $query->addSelect('animal.nom1animal')
                      ->orderBy('animal.nom1animal', $direction);
                break;

            case 'statut':
                $query->addSelect('statutrdv.libellestatut')
                      ->orderBy('statutrdv.libellestatut', $direction);
                break;

            case 'date':
                $query->orderBy('rdv.daterdv', $direction)
                      ->orderBy('rdv.heurerdv', $direction);
                break;

            default:
                $query->orderBy('rdv.daterdv', 'desc')->orderBy('rdv.heurerdv', 'asc');
                break;
        }

        $rdvs = $query->paginate(10)->appends($request->all());

        return view('clients.rdv', compact('rdvs', 'tab', 'sort', 'direction', 'search'));
    }

    public function show($id)
    {
        $rdv = Rdv::with(['animal.proprietaire', 'statut', 'prestations', 'practiciens'])
                  ->where('idrdv', $id)
                  ->firstOrFail();

        return view('rdv.client.show', compact('rdv'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $employe = Employe::where('idpersonne', $user->idpersonne)->first();
        $pro = null;

        if ($employe) {
            $pro = Professionnel::find($employe->idpro);
        } else {
            $pro = Professionnel::where('idpersonne', $user->idpersonne)->first();
        }

        if (!$pro) {
            $pro = Professionnel::first();

            if (!$pro) {
                dd("CRITICAL ERROR: No professional found in 'professionnel' table. Please create one.");
            }
        }

        $clients = Personne::has('animaux')->with('animaux.espece')->get();
        $prestations = Prestation::where('idpro', $pro->idpro)->get();
        $practiciens = Practicien::all(); 
        
        $pre_date = $request->query('pre_date', Carbon::today()->format('Y-m-d'));
        $pre_praticien_id = $request->query('pre_praticien');
        $pre_heure = $request->query('pre_heure');

        $ordreJours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        
        $planningRaw = A_HoraireEmp::with('horaire')
            ->whereIn('idemploye', $practiciens->pluck('idemploye'))
            ->get();
            
        $horairesData = [];
        $mapPracticien = [];

        foreach($practiciens as $p) {
            $key = $p->idemploye; 
            $mapPracticien[$key] = $p->idpracticien;
        }

        foreach($planningRaw as $ligne) {
            $key = $ligne->idemploye;

            if (isset($mapPracticien[$key]) && $ligne->horaire) {
                $praticienId = $mapPracticien[$key];
                $h = $ligne->horaire;
                $jour = trim($ligne->idjour);

                $m_debut = substr($h->heuredebutmatine, 0, 5);
                $m_fin   = substr($h->heurefinmatine, 0, 5);
                $a_debut = $h->heuredebutaprem ? substr($h->heuredebutaprem, 0, 5) : null;
                $a_fin   = $h->heurefinaprem ? substr($h->heurefinaprem, 0, 5) : null;

                $texte = "Matin : $m_debut - $m_fin";
                if($a_debut) $texte .= " / Après-midi : $a_debut - $a_fin";

                $horairesData[$praticienId][$jour] = [
                    'texte' => $texte,
                    'creneaux' => [
                        ['debut' => $m_debut, 'fin' => $m_fin],
                        ...($a_debut ? [['debut' => $a_debut, 'fin' => $a_fin]] : [])
                    ]
                ];
            }
        }

        return view('rdv.employe.create', compact(
            'pro', 
            'clients', 
            'prestations', 
            'practiciens', 
            'horairesData', 
            'ordreJours',
            'pre_date',       
            'pre_praticien_id',
            'pre_heure'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numtatouage'   => 'required|exists:animal,numtatouage',
            'prestations'   => 'required|array', 
            'prestations.*' => 'exists:prestation,idprestation',
            'idpracticien'  => 'required|exists:practicien,idpracticien',
            'date_rdv'      => 'required|date',
            'heure_rdv'     => 'required',
        ], [
            'numtatouage.required'  => "Vous devez sélectionner un animal.",
            'prestations.required'  => "Vous devez sélectionner au moins une prestation.",
            'idpracticien.required' => "Le choix du praticien est obligatoire.",
            'date_rdv.required'     => "La date est obligatoire.",
            'heure_rdv.required'    => "L'heure est obligatoire.",
        ]);

        $practicien = Practicien::where('idpracticien', $request->idpracticien)->firstOrFail();
        
        Carbon::setLocale('fr');
        $jourNom = ucfirst(Carbon::parse($request->date_rdv)->translatedFormat('l')); 

        $horaireDuJour = A_HoraireEmp::with('horaire')
                                        ->where('idpersonne', $practicien->idpersonne)
                                        ->where('idemploye', $practicien->idemploye)
                                        ->where('idjour', $jourNom)
                                        ->first();

        if (!$horaireDuJour) {
            return back()->withErrors(['date_rdv' => "Le praticien ne travaille pas le $jourNom."])->withInput();
        }

        $h = $horaireDuJour->horaire;
        $heure = $request->heure_rdv;
        
        $okMatin = ($heure >= $h->heuredebutmatine && $heure <= $h->heurefinmatine);
        $okAprem = false;
        if ($h->heuredebutaprem) {
            $okAprem = ($heure >= $h->heuredebutaprem && $heure <= $h->heurefinaprem);
        }

        if (!$okMatin && !$okAprem) {
            return back()->withErrors(['heure_rdv' => "Le praticien n'est pas disponible à cette heure (Pause ou Fermeture)."])->withInput();
        }

        DB::beginTransaction();
        try {
            $animal = Animal::where('numtatouage', $request->numtatouage)->firstOrFail();

            $rdv = new Rdv();
            $rdv->numtatouage = $animal->numtatouage;
            $rdv->idstatut    = 1; 
            $rdv->daterdv     = $request->date_rdv;
            $rdv->heurerdv    = $request->heure_rdv;
            $rdv->idpersonne  = $animal->idpersonne;
            $rdv->idclient    = $animal->idclient;
            
            $rdv->save();

            $rdv->prestations()->attach($request->prestations);
            
            DB::table('realiserdv')->insert([
                'idrdv' => $rdv->idrdv,
                'idpersonne' => $practicien->idpersonne,
                'idemploye' => $practicien->idemploye,
                'idpracticien' => $practicien->idpracticien
            ]);

            DB::commit();

            return redirect()->route('rdv.employe.planning', [
                'idpracticien' => $request->idpracticien, 
                'date'         => $request->date_rdv     
            ])->with('success', 'Rendez-vous confirmé avec succès.');

        } catch (QueryException $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur technique : ' . $e->getMessage()])->withInput();
        }
    }

    public function cancel($id)
    {
        try {
            $rdv = Rdv::with(['personne', 'animal'])->findOrFail($id);

            if ($rdv->idstatut == 3) {
                return back()->with('error', 'Ce rendez-vous est déjà annulé.');
            }

            $rdv->idstatut = 3;
            $rdv->save();



            return back()->with('success', "Rendez-vous annulé.");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function planning(Request $request)
    {
        Carbon::setLocale('fr');
        $dateReference = $request->date ? Carbon::parse($request->date) : Carbon::now();
        
        $startOfWeek = $dateReference->copy()->startOfWeek(); 
        $endOfWeek = $dateReference->copy()->endOfWeek();     

        $practiciens = Practicien::all();
        
        $currentPracticienId = $request->idpracticien ?? ($practiciens->first() ? $practiciens->first()->idpracticien : null);
        
        if (!$currentPracticienId) {
            return redirect()->back()->with('error', 'Aucun praticien trouvé.');
        }

        $currentPracticien = Practicien::findOrFail($currentPracticienId);

        $horairesRaw = A_HoraireEmp::with('horaire')
            ->where('idpersonne', $currentPracticien->idpersonne)
            ->where('idemploye', $currentPracticien->idemploye)
            ->get();
        
        $horairesSemaine = [];
        foreach($horairesRaw as $h) {
            $horairesSemaine[trim($h->idjour)] = $h->horaire;
        }

        $rdvs = Rdv::whereBetween('daterdv', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
            ->whereHas('practiciens', function($q) use ($currentPracticienId) {
                $q->where('practicien.idpracticien', $currentPracticienId);
            })
            ->with(['animal.proprietaire', 'statut', 'prestations'])
            ->orderBy('heurerdv')
            ->get()
            ->groupBy('daterdv');

        $jours = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $nomJour = ucfirst($day->translatedFormat('l'));
            
            $jours[] = [
                'date' => $day->format('Y-m-d'),
                'nom' => $nomJour,
                'readable' => $day->translatedFormat('l d F'),
                'horaire' => $horairesSemaine[$nomJour] ?? null,
                'rdvs' => $rdvs[$day->format('Y-m-d')] ?? collect([])
            ];
        }
        $statuts = StatutRDV::all(); 

        return view('rdv.employe.planning_visuel', compact('practiciens', 'currentPracticien', 'jours', 'dateReference', 'statuts'));
    }

    public function createFacture($id)
    {
        $rdv = Rdv::with(['personne', 'prestations'])->findOrFail($id);
        return redirect()->back()->with('success', 'La facture a été générée pour le client ' . $rdv->personne->nom);
    }


    public function createClient(Request $request)
    {
        $idPro = $request->query('pro');
        $date = $request->query('date');
        $heure = $request->query('heure');

        if (!$idPro || !$date || !$heure) {
            if(!$request->query('pre_praticien') && !$request->query('pre_date')) {
                return redirect()->back()->with('error', 'Informations manquantes.');
            }
        }

        $pro = Professionnel::findOrFail($idPro ?? 1); 
        $user = Auth::user();

        $clientConnecte = Client::where('idpersonne', $user->idpersonne)
                                ->with('animaux.espece')
                                ->firstOrFail();
        $clients = collect([$clientConnecte]);

        $practiciens = Practicien::all();
        $prestations = Prestation::where('idpro', $idPro ?? 6)->get();

        $pre_praticien_id = $request->query('pre_praticien');
        $pre_date = $request->query('pre_date', $date); 
        $pre_heure = $request->query('pre_heure', $heure);
        $ordreJours = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];

        $planningRaw = A_HoraireEmp::with('horaire')->get();
        $horairesData = [];

        $mapPracticien = [];
        foreach($practiciens as $p) {
            $key = $p->idpersonne . '-' . $p->idemploye;
            $mapPracticien[$key] = $p->idpracticien;
        }

        foreach($planningRaw as $ligne) {
            $key = $ligne->idpersonne . '-' . $ligne->idemploye;

            if (isset($mapPracticien[$key]) && $ligne->horaire) {
                $praticienId = $mapPracticien[$key];
                $h = $ligne->horaire;
                $jour = trim($ligne->idjour); 

                $m_debut = substr($h->heuredebutmatine, 0, 5);
                $m_fin   = substr($h->heurefinmatine, 0, 5);
                $a_debut = $h->heuredebutaprem ? substr($h->heuredebutaprem, 0, 5) : null;
                $a_fin   = $h->heurefinaprem ? substr($h->heurefinaprem, 0, 5) : null;

                $horairesData[$praticienId][$jour] = [
                    'creneaux' => [
                        ['debut' => $m_debut, 'fin' => $m_fin],
                        ...($a_debut ? [['debut' => $a_debut, 'fin' => $a_fin]] : [])
                    ]
                ];
            }
        }

        return view('rdv.client.create', compact(
            'pro',
            'date',
            'heure',
            'clients',
            'prestations',
            'practiciens',
            'pre_praticien_id',
            'pre_date',
            'pre_heure',
            'horairesData',
            'ordreJours'
        ));
    }

    public function storeClient(Request $request)
    {
        if (strlen($request->heurerdv) === 5) {
            $request->merge(['heurerdv' => $request->heurerdv . ':00']);
        }

        $request->validate([
            'idpro' => 'required',
            'daterdv' => 'required|date',
            'heurerdv' => 'required',
            'numtatouage' => 'required|exists:animal,numtatouage',
            'idprestation' => 'required|exists:prestation,idprestation',
        ]);

        try {
            DB::beginTransaction();
            
            Carbon::setLocale('fr');
            $jourSemaine = ucfirst(Carbon::parse($request->daterdv)->translatedFormat('l'));
            $heureRdv = $request->heurerdv; 

            $employesDisponibles = DB::table('employe')
                ->join('a_horaireemp', 'employe.idemploye', '=', 'a_horaireemp.idemploye')
                ->join('horaires', 'a_horaireemp.idhoraire', '=', 'horaires.idhoraire')
                ->where('employe.idpro', $request->idpro)
                ->where('a_horaireemp.idjour', $jourSemaine)
                ->where(function ($query) use ($heureRdv) {
                    $query->where(function ($q) use ($heureRdv) {
                        $q->where('horaires.heuredebutmatine', '<=', $heureRdv)
                          ->where('horaires.heurefinmatine', '>', $heureRdv);
                    })->orWhere(function ($q) use ($heureRdv) {
                        $q->where('horaires.heuredebutaprem', '<=', $heureRdv)
                          ->where('horaires.heurefinaprem', '>', $heureRdv);
                    });
                })
                ->select('employe.idemploye')
                ->get();

            $employeFinal = null;
            foreach ($employesDisponibles as $emp) {
                $estOccupe = DB::table('rdv')
                    ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
                    ->where('realiserdv.idemploye', $emp->idemploye)
                    ->where('rdv.daterdv', $request->daterdv)
                    ->where('rdv.heurerdv', $heureRdv)
                    ->whereNotIn('rdv.idstatut', [3, 6])
                    ->exists();

                if (!$estOccupe) {
                    $employeFinal = $emp->idemploye;
                    break;
                }
            }

            if (!$employeFinal) {
                return back()->with('error', "Ce créneau n'est plus disponible.");
            }

            $animal = Animal::findOrFail($request->numtatouage);

            $idRdv = DB::table('rdv')->insertGetId([
                'daterdv' => $request->daterdv,
                'heurerdv' => $heureRdv,
                'idstatut' => 1,
                'numtatouage' => $request->numtatouage,
                'idpersonne' => $animal->idpersonne, 
                'idclient' => $animal->idclient      
            ], 'idrdv');

            DB::table('liepresta')->insert([
                'idrdv' => $idRdv,
                'idprestation' => $request->idprestation
            ]);

            $infosPraticien = DB::table('practicien')
                ->join('employe', 'practicien.idemploye', '=', 'employe.idemploye')
                ->where('practicien.idemploye', $employeFinal)
                ->select('practicien.idpracticien', 'employe.idpersonne')
                ->first();

            if ($infosPraticien) {
                DB::table('realiserdv')->insert([
                    'idrdv' => $idRdv,
                    'idemploye' => $employeFinal,
                    'idpersonne' => $infosPraticien->idpersonne,
                    'idpracticien' => $infosPraticien->idpracticien
                ]);
            }

            DB::commit();

            return redirect()->route('rdv.client.index')
                             ->with('success', 'Votre rendez-vous a été confirmé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Erreur technique : " . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'idstatut' => 'required|exists:statutrdv,idstatut',
        ]);

        DB::beginTransaction();

        try {
            $rdv = Rdv::findOrFail($id);
            $ancienStatut = $rdv->idstatut;
            $nouveauStatut = (int) $request->idstatut;

            if ($nouveauStatut === 4) {
                
                $factureExiste = DB::table('appartient')->where('idrdv', $rdv->idrdv)->exists();

                if (!$factureExiste) {
                    $nouvelleFactureId = DB::table('facture')->insertGetId([
                        'idpersonne' => $rdv->idpersonne,
                        'idclient'   => $rdv->idclient,
                        'idcommande' => null
                    ], 'idfacture');

                    DB::table('appartient')->insert([
                        'idfacture' => $nouvelleFactureId,
                        'idrdv'     => $rdv->idrdv
                    ]);
                }
            }

            $rdv->idstatut = $nouveauStatut;
            $rdv->save();
            DB::commit();
            $msg = "Le statut a été mis à jour.";
            if ($nouveauStatut === 4 && !$factureExiste) {
                $msg .= " Une facture a été générée automatiquement.";
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', "Erreur technique : " . $e->getMessage());
        }
    }

    public function getDetails($id)
    {
        $rdv = RDV::with(['client.personne', 'prestations'])->findOrFail($id);
        
        $factureLink = DB::table('appartient')->where('idrdv', $id)->first();
        $totalBrut = $rdv->prestations->sum('tarifht');
        
        $dateRdv = Carbon::parse($rdv->daterdv . ' ' . $rdv->heurerdv);
        $isPast = $dateRdv->isPast();

        return response()->json([
            'id' => $rdv->idrdv,
            'client' => $rdv->client->personne->prenom . ' ' . $rdv->client->personne->nom,
            'mail' => $rdv->client->personne->mail,
            'animal' => $rdv->animal->nom1animal ?? 'Non spécifié',
            'prestations' => $rdv->prestations->pluck('nomprestation')->join(', '),
            'date' => $dateRdv->format('d/m/Y'),
            'heure' => $dateRdv->format('H:i'),
            'statut_id' => $rdv->idstatut,
            'is_past' => $isPast,
            'total' => $totalBrut,
            'has_facture' => (bool) $factureLink,
            'facture_id' => $factureLink ? $factureLink->idfacture : null
        ]);
    }

    public function traiterRdv(Request $request, $id)
    {
        $rdv = RDV::with(['client.personne'])->findOrFail($id);
        $action = $request->input('action'); 
        
        DB::beginTransaction();
        try {
            if ($action === 'validate') {
                $rdv->idstatut = 4; //fini
                $rdv->save();

                $this->creerFacturePourRdv($rdv);
            
                $msg = "RDV validé. Facture générée.";
            }

            elseif ($action === 'cancel_company') {
                $rdv->idstatut = 3; // Annulé
                $rdv->save();                
                $msg = "RDV annulé (Société). Le client a été notifié par email.";
            }

            elseif ($action === 'cancel_client') {
                $rdv->idstatut = 3;
                $rdv->save();
                
                $isLate = $request->boolean('is_late_cancellation'); 
                
                if ($isLate) {
                    $this->creerFacturePourRdv($rdv);
                    $msg = "RDV annulé tardivement. Facture de pénalité (30%) générée.";
                } else {
                    $msg = "RDV annulé sans frais.";
                }
            }

            DB::commit();
            return back()->with('success', $msg);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    private function creerFacturePourRdv($rdv) {
        $existe = DB::table('appartient')->where('idrdv', $rdv->idrdv)->exists();
        if ($existe) return;

        $factureId = DB::table('facture')->insertGetId([
            'date_facture' => now(),
            'idpersonne'   => $rdv->idpersonne,
            'idclient'     => $rdv->idclient,
            'idcommande'   => null
        ]);

        DB::table('appartient')->insert([
            'idfacture' => $factureId,
            'idrdv'     => $rdv->idrdv
        ]);
    }
}   