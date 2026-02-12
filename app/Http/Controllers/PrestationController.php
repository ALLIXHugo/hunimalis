<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Prestation;
use App\Models\CategoriePrestation;
use App\Models\Espece;
use App\Models\Practicien;   
use App\Models\Professionnel;   
use App\Models\Corpulence;
use App\Models\TaillePelage;


class PrestationController extends Controller
{
    public function index()
    {
        $prestations = Prestation::with(['categorie', 'espece'])
                                 ->orderBy('libellecategorie')
                                 ->get();

        return view('prestation.index', compact('prestations'));
    }

    public function create()
    {
        $categories = CategoriePrestation::orderBy('libellecategorie')->get();
        $especes = Espece::all();
        $praticiens = Practicien::all();
        $corpulences = Corpulence::all();
        $taillesPelage = TaillePelage::all();

        return view('prestation.create', compact(
            'categories', 
            'especes', 
            'praticiens', 
            'corpulences', 
            'taillesPelage'
        ));
    }

    private function getValidationMessages() {
        return [
            'nomprestation.required' => 'Le nom de la prestation est obligatoire.',
            'nomprestation.max' => 'Le nom ne doit pas dépasser 50 caractères.',
            'libellecategorie.required' => 'Veuillez sélectionner une catégorie.',
            'tarifht.required' => 'Le tarif HT est obligatoire.',
            'tarifht.numeric' => 'Le tarif doit être un nombre valide.',
            'duree.required' => 'La durée est obligatoire.',
            'duree.integer' => 'La durée doit être un nombre entier (minutes).',
            'especes.required' => 'Vous devez sélectionner au moins une espèce.',
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomprestation' => 'required|string|max:50',
            'libellecategorie' => 'required|exists:categorieprestation,libellecategorie',
            'tarifht' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'especes' => 'required|array', 
        ], $this->getValidationMessages());

        Prestation::create([
            'nomprestation' => $request->nomprestation,
            'libellecategorie' => $request->libellecategorie,
            'idespece' => $request->especes[0], 
            'tarifht' => $request->tarifht,
            'duree' => $request->duree,
            'idpro' => 6, 
        ]);

        return redirect()->route('prestation.index')
                         ->with('success', 'La prestation a été ajoutée au catalogue avec succès !');
    }

    public function edit($id)
    {
        $prestation = Prestation::findOrFail($id);
        
        $categories = CategoriePrestation::orderBy('libellecategorie')->get();
        $especes = Espece::all();
        $praticiens = Practicien::all();
        $corpulences = Corpulence::all();
        $taillesPelage = TaillePelage::all();

        return view('prestation.edit', compact(
            'prestation', 
            'categories', 
            'especes',
            'praticiens', 
            'corpulences', 
            'taillesPelage'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nomprestation' => 'required|string|max:50',
            'libellecategorie' => 'required|exists:categorieprestation,libellecategorie',
            'tarifht' => 'required|numeric|min:0',
            'duree' => 'required|integer|min:1',
            'especes' => 'required|array', 
        ], $this->getValidationMessages());

        $prestation = Prestation::findOrFail($id);

        $prestation->update([
            'nomprestation' => $request->nomprestation,
            'libellecategorie' => $request->libellecategorie,
            'idespece' => $request->especes[0], 
            'tarifht' => $request->tarifht,
            'duree' => $request->duree,
        ]);

        return redirect()->route('prestation.index')
                         ->with('success', 'La prestation a été modifiée avec succès !');
    }

    public function rechercher(Request $request)
    {
        $query = Professionnel::query();
            
        if ($request->filled('q')) {
            $term = $request->input('q');
            
            $query->where(function($q) use ($term) {
                $q->where('libelleetablissement', 'ILIKE', '%' . $term . '%') 
                  ->orWhereRaw('libelleetablissement % ?', [$term])
                  
                  ->orWhere('libelletypeetablissement', 'ILIKE', '%' . $term . '%')
                  ->orWhereRaw('libelletypeetablissement % ?', [$term])

                  ->orWhereHas('prestations', function($subQuery) use ($term) {
                      $subQuery->where('nomprestation', 'ILIKE', '%' . $term . '%')
                               ->orWhereRaw('nomprestation % ?', [$term]);
                  });
            });
        }
    
        if ($request->filled('ville')) {
            $villeInput = preg_replace('/\s\(\d+\)$/', '', $request->input('ville'));
            
            $query->where(function($q) use ($villeInput) {
                $q->where('ville', 'ILIKE', '%' . $villeInput . '%')
                  ->orWhere('cp', 'LIKE', $villeInput . '%')
                  ->orWhereRaw('ville % ?', [$villeInput]);
            });
        }
    
        if ($request->filled('types')) {
            $query->whereIn('libelletypeetablissement', $request->types);
        }
    
        if ($request->filled('especes')) {
            $query->whereHas('prestations', function($q) use ($request) {
                $q->whereIn('idespece', $request->especes);
            });
        }

        if ($request->filled('date')) {
             $request->validate([
                'date' => 'date|after_or_equal:today',
            ]);
        }
    
        if ($request->filled('q')) {
            $query->orderByRaw("similarity(libelleetablissement, ?) DESC", [$request->input('q')]);
        } else {
            $query->orderBy('libelleetablissement'); 
        }

        $professionnels = $query->paginate(10);
        
        return view('prestation.client.rechercher', compact('professionnels'));
    }


    public function show(Request $request, $id)
    {
        $pro = Professionnel::where('idpro', $id)->firstOrFail();
    
        $horairesDb = DB::table('a_horaireglob')
            ->join('horaires', 'a_horaireglob.idhoraire', '=', 'horaires.idhoraire')
            ->where('a_horaireglob.idpro', $id)
            ->select('a_horaireglob.idjour', 'horaires.*')
            ->get();
    
        $joursSemaine = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $horairesDisponibles = $horairesDb->keyBy(function($item) { return trim($item->idjour); });

        $dateDebut = $request->filled('date') ? Carbon::parse($request->date) : Carbon::now()->locale('fr');
        
        if($dateDebut->lt(Carbon::today())) {
            $dateDebut = Carbon::now()->locale('fr');
        }
        
        $datePrev = $dateDebut->copy()->subDays(5);
        if($datePrev->lt(Carbon::today())) {
            $isPrevDisabled = true;
            $linkPrev = '#';
        } else {
            $isPrevDisabled = false;
            $linkPrev = route('client.pro.details', ['id' => $id, 'date' => $datePrev->format('Y-m-d')]);
        }

        $dateNext = $dateDebut->copy()->addDays(5);
        $linkNext = route('client.pro.details', ['id' => $id, 'date' => $dateNext->format('Y-m-d')]);

        $employesIds = DB::table('employe')->where('idpro', $id)->pluck('idemploye')->toArray();

        $horairesEmployes = DB::table('a_horaireemp')
            ->join('horaires', 'a_horaireemp.idhoraire', '=', 'horaires.idhoraire')
            ->whereIn('a_horaireemp.idemploye', $employesIds)
            ->select('a_horaireemp.idemploye', 'a_horaireemp.idjour', 'horaires.*')
            ->get()
            ->groupBy(function($item) { return trim($item->idjour); });

        $dateFinMax = $dateDebut->copy()->addDays(20);

        $rdvsExistants = DB::table('rdv')
            ->join('realiserdv', 'rdv.idrdv', '=', 'realiserdv.idrdv')
            ->whereIn('realiserdv.idemploye', $employesIds)
            ->whereBetween('rdv.daterdv', [$dateDebut->toDateString(), $dateFinMax->toDateString()])
            ->whereNotIn('rdv.idstatut', [3, 5, 6]) 
            ->select('rdv.daterdv', 'rdv.heurerdv', 'realiserdv.idemploye')
            ->get();


        $prochainsJours = [];
        $tempDate = $dateDebut->copy();
        $iterations = 0;
        

        while(count($prochainsJours) < 5 && $iterations < 14) {
            $nomJour = ucfirst($tempDate->isoFormat('dddd')); 
            $dateString = $tempDate->format('Y-m-d');
            $horaireGlobal = $horairesDisponibles->get($nomJour);
            $employesDuJour = $horairesEmployes->get($nomJour) ?? collect();
            $rdvsDuJour = $rdvsExistants->where('daterdv', $dateString);
            $estOuvert = $horaireGlobal && (
                ($horaireGlobal->heuredebutmatine && $horaireGlobal->heurefinmatine) || 
                ($horaireGlobal->heuredebutaprem && $horaireGlobal->heurefinaprem)
            );

            if ($estOuvert && $employesDuJour->isNotEmpty()) {
                $creneaux = [];
                
                $ajouterCreneaux = function($debut, $fin) use (&$creneaux, $tempDate, $employesDuJour, $rdvsDuJour) {
                    $start = Carbon::parse($tempDate->format('Y-m-d') . ' ' . $debut);
                    $end   = Carbon::parse($tempDate->format('Y-m-d') . ' ' . $fin);
                    $end->subMinutes(30); 

                    while($start->lte($end)) {
                        if($tempDate->isToday() && $start->lte(Carbon::now())) {
                            $start->addMinutes(30);
                            continue;
                        }

                        $heureFormat = $start->format('H:i');
                        $heureFormatSec = $start->format('H:i:00');
                        $unEmployeEstDispo = false;

                        foreach($employesDuJour as $empHoraire) {
                            $matinOk = ($empHoraire->heuredebutmatine && $empHoraire->heurefinmatine && 
                                        $heureFormat >= Carbon::parse($empHoraire->heuredebutmatine)->format('H:i') && 
                                        $heureFormat < Carbon::parse($empHoraire->heurefinmatine)->format('H:i'));
                            
                            $apremOk = ($empHoraire->heuredebutaprem && $empHoraire->heurefinaprem && 
                                        $heureFormat >= Carbon::parse($empHoraire->heuredebutaprem)->format('H:i') && 
                                        $heureFormat < Carbon::parse($empHoraire->heurefinaprem)->format('H:i'));

                            if ($matinOk || $apremOk) {
                                $estOccupe = $rdvsDuJour->where('idemploye', $empHoraire->idemploye)
                                                        ->where('heurerdv', $heureFormatSec)
                                                        ->isNotEmpty();
                                
                                if (!$estOccupe) {
                                    $unEmployeEstDispo = true;
                                    break; 
                                }
                            }
                        }

                        if ($unEmployeEstDispo) {
                            $creneaux[] = $heureFormat;
                        }
                        
                        $start->addMinutes(30);
                    }
                };

                if($horaireGlobal->heuredebutmatine && $horaireGlobal->heurefinmatine) {
                    $ajouterCreneaux($horaireGlobal->heuredebutmatine, $horaireGlobal->heurefinmatine);
                }
                if($horaireGlobal->heuredebutaprem && $horaireGlobal->heurefinaprem) {
                    $ajouterCreneaux($horaireGlobal->heuredebutaprem, $horaireGlobal->heurefinaprem);
                }

                $prochainsJours[] = [
                    'date_full' => $tempDate->format('Y-m-d'),
                    'nom_jour'  => $nomJour,
                    'jour_mois' => $tempDate->isoFormat('D MMM'),
                    
                    'creneaux'  => $creneaux, 
                    'plus'      => count($creneaux) > 5 
                ];
            }
            
            $tempDate->addDay();
            $iterations++;
        }

        if ($request->ajax()) {
            return view('prestation.client.partials.calendar', compact(
                'pro', 'prochainsJours', 'linkPrev', 'linkNext', 'isPrevDisabled'
            ))->render();
        }
    
        return view('prestation.client.details', compact(
            'pro', 
            'joursSemaine', 
            'horairesDisponibles', 
            'prochainsJours',
            'linkPrev',       
            'linkNext',       
            'isPrevDisabled',  
            'dateDebut'
        ));
    }
}