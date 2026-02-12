<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; 
use App\Models\Animal;
use App\Models\Client;
use App\Models\Personne;
use App\Models\Espece;
use App\Models\Race;
use App\Models\Couleur;
use App\Models\CouleurYeux;
use App\Models\Comportement;
use App\Models\Oreilles; 
use App\Models\SignesDistinctifs; 
use App\Models\Queue;
use App\Models\AspectPelage;
use App\Models\TaillePelage;
use App\Models\CaracPelage;
use App\Models\Corpulence;
use App\Models\Sociabilite;
use App\Models\Professionnel; 
use App\Models\RDV;
use App\Models\PriseMesures;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class AnimalController extends Controller
{
    public function animals(Request $request )
    {
        $user = Auth::user();
        $personne = Personne::find($user->idpersonne);
        $client = Client::where('idpersonne', $user->idpersonne)->first();

        $animals = [];
        if ($client) {
            $animals = Animal::with(['race', 'caracteristique', 'espece', 'croisement', 'priseMesures'])->where('idclient', $client->idclient)->get();
        }

        $data = [
            'personne'    => $personne,
            'animals'     => $animals,
            'especes'     => Espece::all(),
            'races'       => Race::all(),
            'corpulences' => Corpulence::all(),
            'tailles'     => TaillePelage::all(),
            'pros'        => Professionnel::all() 
        ];

        return view('clients.animaux', $data);
    }
    
    public function index(Request $request)
    {
        $query = Animal::with(['espece', 'race', 'personne', 'client']);

        if ($request->filled('nom')) {
            $searchName = $request->input('nom');
            $query->where(function ($q) use ($searchName) {
                $q->where('nom1animal', 'ILIKE', '%' . $searchName . '%')
                  ->orWhere('nom2animal', 'ILIKE', '%' . $searchName . '%');
            });
        }

        if ($request->filled('espece')) {
            $query->where('idespece', $request->input('espece'));
        }

        if ($request->filled('proprietaire')) {
            $searchProprio = $request->input('proprietaire');
            $query->whereHas('personne', function ($q) use ($searchProprio) {
                $q->where('nom', 'ILIKE', '%' . $searchProprio . '%')
                  ->orWhere('prenom', 'ILIKE', '%' . $searchProprio . '%');
            });
        }

        $animaux = $query->get();
        $especes = Espece::all();

        return view("animaux.afficheanimal", [
            'animaux' => $animaux,
            'especes' => $especes
        ]);
    }

    public function create()
    {
        if (url()->previous() !== url()->current() && !str_contains(url()->previous(), 'animal/create')) {
            session(['animal_creation_origin' => url()->previous()]);
        }

        $clients = Client::join('personne', 'client.idpersonne', '=', 'personne.idpersonne')
            ->select('client.idclient', 'personne.idpersonne', 'personne.nom', 'personne.prenom', 'personne.tel')
            ->orderBy('personne.nom') 
            ->get();

        $data = [
            'clients'       => $clients,
            'especes'       => Espece::all(),
            'races'         => Race::all(),
            'couleurs'      => Couleur::all(),
            'yeux'          => CouleurYeux::all(),
            'oreilles'      => Oreilles::all(),
            'queues'        => Queue::all(),
            'aspects'       => AspectPelage::all(),
            'tailles'       => TaillePelage::all(),
            'libellesigne'  => SignesDistinctifs::all(),
            'pelages'       => CaracPelage::all(),
            'corpulences'   => Corpulence::all(),
            'sociabilites'  => Sociabilite::all(),
            'traitsCaracteres' => Comportement::whereNotNull('traitcaractere')->distinct()->pluck('traitcaractere'),
            'hygienes'         => Comportement::whereNotNull('hygiene')->distinct()->pluck('hygiene'),
        ];

        return view("animaux.creeranimal", $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idpersonne'    => 'required|integer|exists:personne,idpersonne',
            'nom1animal'    => 'required|string|max:50',
            'idespece'      => 'required|integer',
            'idrace'        => 'required|integer',
            'datenaissance' => 'required|date|before_or_equal:today|after_or_equal:-30 years',
            'numtatouage'   => 'required|string|max:15|unique:animal,numtatouage',
            'rac_idrace'    => 'nullable|integer',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ], [
            'numtatouage.unique' => 'Ce numéro de tatouage est déjà enregistré dans la base de données.',
            'idpersonne.required' => 'Veuillez sélectionner un propriétaire pour cet animal.',
            'datenaissance.after_or_equal'  => 'Âge invalide : l\'animal doit avoir moins de 30 ans.',
            'datenaissance.before_or_equal' => 'La date de naissance ne peut pas être dans le futur.',
        ]);

        $client = Client::where('idpersonne', $request->idpersonne)->first();
        if (!$client) {
            return back()->withInput()->withErrors(['idpersonne' => 'Le propriétaire sélectionné est introuvable.']);
        }

        $animal = new Animal();
        
        $animal->numtatouage    = strtoupper($request->numtatouage);
        $animal->idclient       = $client->idclient;
        $animal->idpersonne     = $client->idpersonne;
        $animal->nom1animal     = $request->nom1animal;
        $animal->nom2animal     = $request->nom2animal;
        $animal->sexe           = $request->sexe == '1' ? true : false;
        $animal->datenaissance  = $request->datenaissance;
        $animal->lieunaissance  = $request->lieuNaissance;
        $animal->idespece       = $request->idespece;
        $animal->idrace         = $request->idrace;

        $animal->idcouleur      = $request->filled('idcouleur') ? $request->input('idcouleur') : null;
        $animal->idcouleuryeux  = $request->filled('idcouleuryeux') ? $request->input('idcouleuryeux') : null;
        $animal->idqueue        = $request->filled('idqueue') ? $request->input('idqueue') : null;
        $animal->idcorpulence   = $request->filled('idcorpulence') ? $request->input('idcorpulence') : null;
        $animal->idaspectpelage = $request->filled('idaspectpelage') ? $request->input('idaspectpelage') : null;
        $animal->idtaillepelage = $request->filled('idtaillepelage') ? $request->input('idtaillepelage') : null;
        $animal->idpelage       = $request->filled('idpelage') ? $request->input('idpelage') : null;
        
        $animal->idpro_toiletteur  = $request->input('idpro_toiletteur');
        $animal->idpro_educateur   = $request->input('idpro_educateur');
        $animal->idpro_veterinaire = $request->input('idpro_veterinaire');
        $animal->idpro_pension     = $request->input('idpro_pension');
        
        $animal->idcomportement = 1; 

        if ($request->filled('rac_idrace')) {
            $animal->rac_idrace = $request->input('rac_idrace');
            $animal->rac_idespece = $request->idespece; 
        } else {
            $animal->rac_idrace = null;
            $animal->rac_idespece = null;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('animaux', 'public');
            $animal->photo = $path;
        }

        try {
            $animal->save();


            if ($request->filled('idoreille')) {
                DB::table('a_typeoreille')->insert([
                    'numtatouage' => $animal->numtatouage,
                    'idoreille'   => $request->input('idoreille')
                ]);
            }
            
            $boolSterilise = null;
            if ($request->has('sterilise')) {
                $boolSterilise = true;
            } else {
                if ($request->input('castre') === 'Oui') $boolSterilise = true;
                if ($request->input('castre') === 'Non') $boolSterilise = false;
            }

            if ($boolSterilise !== null || $request->has('degriffe')) {
                $animal->caracteristique()->create([
                    'numtatouage' => $animal->numtatouage,
                    'sterilise'   => $boolSterilise,
                    'degriffe'    => $request->has('degriffe') ? true : false,
                ]);
            }

            if ($request->has('signes_text') && is_array($request->input('signes_text'))) {
                foreach ($request->input('signes_text') as $libelleSigne) {
                    $libellePropre = trim($libelleSigne);
                    if (!empty($libellePropre)) {
                        $signeRef = SignesDistinctifs::firstOrCreate(['libellesigne' => $libellePropre]);
                        DB::table('a_signes')->insert([
                            'numtatouage' => $animal->numtatouage,
                            'idsigne'     => $signeRef->idsigne,
                            'libellesigne'=> $signeRef->libellesigne 
                        ]);
                    }
                }
            }

            if ($request->hasAny(['traitcaractere', 'hygiene', 'idsociabilite'])) {
                $behaviorData = $request->only([
                    'traitcaractere', 'hygiene', 'idsociabilite', 
                    'soc_idsociabilite', 'soc_idsociabilite2', 
                    'marcheautorise', 'photosautorise', 
                    'protectionparasiteinterne', 'protectionparasiteexterne'
                ]);
                
                $behaviorData = array_map(function($value) {
                    return $value === "" ? null : $value;
                }, $behaviorData);

                if(!empty(array_filter($behaviorData))) {
                    $newBehavior = Comportement::create($behaviorData);
                    $animal->idcomportement = $newBehavior->idcomportement;
                    $animal->save(); 

                    if ($request->has('peur_text') && is_array($request->input('peur_text'))) {
                        foreach ($request->input('peur_text') as $libellePeur) {
                            $libellePeur = trim($libellePeur);
                            if(!empty($libellePeur)) {
                                $peurExistante = DB::table('peurs')->where('libellepeur', 'ILIKE', $libellePeur)->first();
                                $peurId = $peurExistante ? $peurExistante->idpeur : DB::table('peurs')->insertGetId(['libellepeur' => $libellePeur], 'idpeur');
                                
                                DB::table('apeurs')->insert([
                                    'idcomportement' => $newBehavior->idcomportement,
                                    'idpeur'         => $peurId
                                ]);
                            }
                        }
                    }
                }
            }

            $user = Auth::user();

            if ($request->filled('redirect_url')) {
                return redirect($request->input('redirect_url'))->with('success', 'Animal ajouté ! Vous pouvez continuer votre réservation.');
            }

            if (session()->has('animal_creation_origin')) {
                $targetUrl = session()->pull('animal_creation_origin');
                return redirect($targetUrl)->with('success', 'Animal ajouté ! Vous pouvez continuer.');
            }

            
            if (Professionnel::where('idpersonne', $user->idpersonne)->exists()) {
                return redirect()->route('animal.index')->with('success', 'Animal créé avec succès !');
            }

            if (Client::where('idpersonne', $user->idpersonne)->exists()) {
                return redirect()->route('clients.animals')->with('success', 'Votre animal a été ajouté avec succès !');
            }

            return redirect()->route('animal.index')->with('success', 'Animal créé avec succès !');          

        } catch (QueryException $e) {
            $errorCode = $e->getCode();
            $rawMessage = $e->getMessage();
            if ($errorCode === 'P0001') {
                if (preg_match('/ERROR:\s+(.*?)(?=\s+CONTEXT:)/s', $rawMessage, $matches)) {
                    $cleanMessage = $matches[1];
                } else {
                    $cleanMessage = "Une règle de gestion base de données a bloqué l'enregistrement.";
                }
                return back()->withInput()->withErrors(['trigger_error' => $cleanMessage]);
            }
            throw $e;
        }
    }

    public function show($numtatouage)
    {
        $animal = Animal::with([
            'espece', 'race', 'caracteristique', 'personne', 'couleur', 
            'couleurYeux', 'oreilles', 'aspectPelage', 'taillePelage', 
            'caracPelage', 'signes', 'corpulence', 'comportement.peurs', 
            'priseMesures' 
        ])->where('numtatouage', $numtatouage)->firstOrFail();

        $traitsCaracteres = Comportement::whereNotNull('traitcaractere')->distinct()->pluck('traitcaractere');
        $hygienes         = Comportement::whereNotNull('hygiene')->distinct()->pluck('hygiene');

        $data = [
            'animal'        => $animal,
            'especes'       => Espece::all(),
            'races'         => Race::all(),
            'couleurs'      => Couleur::all(),
            'yeux'          => CouleurYeux::all(),
            'oreilles'      => Oreilles::all(),
            'queues'        => Queue::all(),
            'aspects'       => AspectPelage::all(),
            'tailles'       => TaillePelage::all(),
            'libellesigne'  => SignesDistinctifs::all(),
            'caracs'        => CaracPelage::all(),
            'corpulences'   => Corpulence::all(),
            'sociabilites'  => Sociabilite::all(),
            'traitsCaracteres' => $traitsCaracteres,
            'hygienes'         => $hygienes,
        ];
        return view('animaux.detailsanimal', $data);
    }

    public function update(Request $request, $numtatouage)
    {
        $request->validate([
            'idpersonne'    => 'required|integer|exists:personne,idpersonne',
            'nom1animal'    => 'required|string|max:50',
            'idespece'      => 'required|integer',
            'idrace'        => 'nullable|integer', 
            'datenaissance' => 'required|date|before_or_equal:today', 
            
            'idqueue'       => 'nullable|integer',
            'idcouleuryeux' => 'nullable|integer',
            'idcorpulence'  => 'nullable|integer',
            'idaspectpelage'=> 'nullable|integer',
            'idtaillepelage'=> 'nullable|integer',
            'idpelage'      => 'nullable|integer',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $animal = Animal::with(['comportement', 'caracteristique'])->where('numtatouage', $numtatouage)->firstOrFail();

        $idClient = Client::where('idpersonne', $request->idpersonne)->value('idclient');
        if($idClient) {
            $animal->idclient = $idClient;
        }
        
        $animal->idpersonne     = $request->idpersonne;
        $animal->nom1animal     = $request->nom1animal;
        if ($request->has('nom2animal')) {
            $animal->nom2animal = $request->nom2animal;
        }

        $animal->sexe = filter_var($request->sexe, FILTER_VALIDATE_BOOLEAN);

        $animal->datenaissance  = $request->datenaissance; 
        
        $animal->idespece       = $request->idespece;
        $animal->idrace         = $request->filled('idrace') ? $request->idrace : null;
        
        $animal->rac_idrace     = $request->filled('rac_idrace') ? $request->input('rac_idrace') : null;
        $animal->rac_idespece   = $request->filled('rac_idrace') ? $request->idespece : null;

        if ($request->has('idpro_toiletteur')) $animal->idpro_toiletteur = $request->input('idpro_toiletteur');
        if ($request->has('idpro_educateur'))  $animal->idpro_educateur  = $request->input('idpro_educateur');
        if ($request->has('idpro_veterinaire')) $animal->idpro_veterinaire = $request->input('idpro_veterinaire');
        if ($request->has('idpro_pension'))    $animal->idpro_pension    = $request->input('idpro_pension');

        $animal->idqueue        = $request->filled('idqueue') ? $request->input('idqueue') : null;
        $animal->idcouleuryeux  = $request->filled('idcouleuryeux') ? $request->input('idcouleuryeux') : null;
        $animal->idcorpulence   = $request->filled('idcorpulence') ? $request->input('idcorpulence') : null;
        $animal->idaspectpelage = $request->filled('idaspectpelage') ? $request->input('idaspectpelage') : null;
        $animal->idtaillepelage = $request->filled('idtaillepelage') ? $request->input('idtaillepelage') : null;
        $animal->idpelage       = $request->filled('idpelage') ? $request->input('idpelage') : null;

        if ($request->hasFile('photo')) {
            if ($animal->photo && Storage::disk('public')->exists($animal->photo)) {
                Storage::disk('public')->delete($animal->photo);
            }
            $path = $request->file('photo')->store('animaux', 'public');
            $animal->photo = $path;
        }
        

        $animal->save(); 

        if ($request->has('idoreille')) {
            DB::table('a_typeoreille')->where('numtatouage', $animal->numtatouage)->delete();
            if ($request->filled('idoreille')) {
                DB::table('a_typeoreille')->insert([
                    'numtatouage' => $animal->numtatouage,
                    'idoreille'   => $request->input('idoreille')
                ]);
            }
        }

        $boolSterilise = null;
        if ($request->has('castre')) {
             if ($request->input('castre') === 'Oui') $boolSterilise = true;
             if ($request->input('castre') === 'Non') $boolSterilise = false;
        } elseif ($request->has('sterilise')) {
             $boolSterilise = $request->input('sterilise') ? true : false;
        }
        
        $animal->caracteristique()->updateOrCreate(
            ['numtatouage' => $animal->numtatouage],
            [
                'sterilise' => $boolSterilise,
                'degriffe'  => $request->filled('degriffe') ? ($request->input('degriffe') == '1') : false
            ]
        );

        if ($request->has('signes_text')) {
            DB::table('a_signes')->where('numtatouage', $animal->numtatouage)->delete();
            if (is_array($request->input('signes_text'))) {
                foreach ($request->input('signes_text') as $libelleSigne) {
                    $libellePropre = trim($libelleSigne);
                    if (!empty($libellePropre)) {
                        $signeRef = SignesDistinctifs::firstOrCreate(['libellesigne' => $libellePropre]);
                        DB::table('a_signes')->insert([
                            'numtatouage' => $animal->numtatouage,
                            'idsigne'     => $signeRef->idsigne,
                            'libellesigne'=> $signeRef->libellesigne 
                        ]);
                    }
                }
            }
        }

        if ($request->hasAny(['traitcaractere', 'hygiene', 'idsociabilite'])) {
            $behaviorData = $request->only([
                'traitcaractere', 'hygiene', 'idsociabilite', 
                'soc_idsociabilite', 'soc_idsociabilite2', 
                'marcheautorise', 'photosautorise', 
                'protectionparasiteinterne', 'protectionparasiteexterne'
            ]);

            $behaviorData = array_map(function($value) {
                return $value === "" ? null : $value;
            }, $behaviorData);

            if ($animal->comportement) {
                $animal->comportement->update($behaviorData);
            } else {
                if(!empty(array_filter($behaviorData))) {
                    $newBehavior = Comportement::create($behaviorData);
                    $animal->idcomportement = $newBehavior->idcomportement;
                    $animal->save();
                }
            }

            if ($animal->idcomportement && $request->has('peur_text')) {
                DB::table('apeurs')->where('idcomportement', $animal->idcomportement)->delete();
                if (is_array($request->input('peur_text'))) {
                    foreach ($request->input('peur_text') as $libellePeur) {
                        $libellePeur = trim($libellePeur);
                        if(!empty($libellePeur)) {
                            $peurExistante = DB::table('peurs')->where('libellepeur', 'ILIKE', $libellePeur)->first();
                            $peurId = $peurExistante ? $peurExistante->idpeur : DB::table('peurs')->insertGetId(['libellepeur' => $libellePeur], 'idpeur');
                            
                            DB::table('apeurs')->insert([
                                'idcomportement' => $animal->idcomportement,
                                'idpeur'         => $peurId
                            ]);
                        }
                    }
                }
            }
        }

        $user = Auth::user();

        if (Client::where('idpersonne', $user->idpersonne)->exists()) {
            return redirect()->route('clients.animals')
                ->with('success', 'Les modifications ont été enregistrées avec succès.');
        }

        return redirect()->route('animal.show', $numtatouage)
            ->with('success', 'Fiche mise à jour avec succès !');
    }

    
    public function destroy($numtatouage)
    {
        $hasFutureRdv = Rdv::where('numtatouage', $numtatouage)
                            ->where('idstatut', 1) 
                            ->exists();
    
        if ($hasFutureRdv) {
            return back()->with('error', 'Impossible de supprimer : cet animal a des rendez-vous à venir.');
        }
    
        try {
            DB::beginTransaction(); 
    
            DB::table('a_typeoreille')->where('numtatouage', $numtatouage)->delete();
            DB::table('a_signes')->where('numtatouage', $numtatouage)->delete();
            DB::table('caracteristiquesanimal')->where('numtatouage', $numtatouage)->delete();
            DB::table('prise_mesure')->where('numtatouage', $numtatouage)->delete();
    
            $animal = Animal::where('numtatouage', $numtatouage)->firstOrFail();
            
            if ($animal->photo && Storage::disk('public')->exists($animal->photo)) {
                Storage::disk('public')->delete($animal->photo);
            }

            $animal->delete();
    
            DB::commit(); 
    
            return back()->with('success', 'Animal et ses données associées supprimés avec succès.');
    
        } catch (QueryException $e) {
            DB::rollBack(); 
            if ($e->getCode() === '23503') {
                return back()->with('error', 'Impossible de supprimer : cet animal est lié à un historique comptable.');
            }
            return back()->with('error', 'Erreur technique : ' . $e->getMessage());
        }
    }
    public function showSante($id)
    {
        $animal = Animal::where('numtatouage', $id)->firstOrFail();
        
        $especes = Espece::all();
        $races = Race::all();
        
        return view('detailsanimal', compact('animal', 'especes', 'races'));
    }
}