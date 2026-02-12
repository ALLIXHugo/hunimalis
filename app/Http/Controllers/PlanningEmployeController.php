<?php

namespace App\Http\Controllers;

use App\Models\PlanningEmploye;
use App\Models\Employe;
use App\Models\Jour;
use App\Models\Personne;
use App\Models\Horaires;
use App\Models\A_HoraireEmp; 
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class PlanningEmployeController extends Controller
{
    public function index()
    {
        $planningDetails = A_HoraireEmp::with(['employe','personne', 'horaire', 'jour'])->get(); 
        return view('planning.indexPlanningEmploye', compact('planningDetails'));
    }

    public function create()
    {
        $employes = Employe::all();
        $personnes = Personne::all();
        $jours = Jour::all();
        
        $existingPlannings = A_HoraireEmp::with('horaire')->get();

        return view('planning.createPlanningEmploye', compact('employes','personnes', 'jours', 'existingPlannings'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'idpersonne'      => 'required', 
                'idemploye'       => 'required|exists:employe,idemploye',
                'idjour'          => 'required|exists:jour,idjour',
                
                'ouverture_matin' => 'nullable|required_without:ouverture_aprem|date_format:H:i',
                'fermeture_matin' => 'nullable|required_with:ouverture_matin|date_format:H:i|after:ouverture_matin',
                
                'ouverture_aprem' => 'nullable|required_without:ouverture_matin|date_format:H:i',
                'fermeture_aprem' => 'nullable|required_with:ouverture_aprem|date_format:H:i|after:ouverture_aprem',
            ], [
                'idemploye.exists'                 => "L'employé sélectionné n'est pas valide.",
                'ouverture_matin.required_without' => "Veuillez saisir au moins un horaire (Matin ou Après-midi).",
                'fermeture_matin.after'            => "L'heure de fin (Matin) doit être après l'heure de début.",
                'fermeture_aprem.after'            => "L'heure de fin (Après-midi) doit être après l'heure de début.",
            ]);

            $idemploye = $validatedData['idemploye'];
            $idjourCible = $validatedData['idjour'];

            $hDebutMatin = $request->filled('ouverture_matin') ? $validatedData['ouverture_matin'] : null;
            $hFinMatin   = $request->filled('fermeture_matin') ? $validatedData['fermeture_matin'] : null;
            $hDebutAprem = $request->filled('ouverture_aprem') ? $validatedData['ouverture_aprem'] : null;
            $hFinAprem   = $request->filled('fermeture_aprem') ? $validatedData['fermeture_aprem'] : null;

            $nouvelHoraire = Horaires::create([
                'heuredebutmatine' => $hDebutMatin,
                'heurefinmatine'   => $hFinMatin,
                'heuredebutaprem'  => $hDebutAprem,
                'heurefinaprem'    => $hFinAprem,
            ]);
            $idHoraireSaisi = $nouvelHoraire->idhoraire;

            $employe = Employe::where('idemploye', $idemploye)->firstOrFail();
            $idpersonne = $employe->idpersonne;
            
            $planningEmploye = PlanningEmploye::firstOrCreate([
                'idpersonne' => $idpersonne,
                'idemploye'  => $idemploye,
            ]);

            DB::table('a_horaireemp')->updateOrInsert(
                [
                    'idemploye' => $idemploye,
                    'idjour'    => $idjourCible
                ],
                [
                    'idplanningemp' => $planningEmploye->idplanningemp,
                    'idhoraire'     => $idHoraireSaisi,
                    'idpersonne'    => $idpersonne
                ]
            );

            $message = "Le planning a été mis à jour pour {$employe->nom} le {$idjourCible}.";
            if(is_null($hDebutMatin) && !is_null($hDebutAprem)) {
                $message .= " (Matinée libre, Après-midi planifié).";
            }

            return redirect()->route('planning.index')->with('success', $message);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput(); 
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Erreur technique : " . $e->getMessage())->withInput();
        }
    }
}