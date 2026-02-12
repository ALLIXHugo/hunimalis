<?php

namespace App\Http\Controllers;


use App\Models\Jour;
use App\Models\Horaires;
use App\Models\Professionnel;
use App\Models\PlanningGlobal;
use App\Models\A_HoraireGlob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningGlobalController extends Controller
{
    public function index(){
        return view("planning.createPlanningPro", ['planningGlobal' => PlanningGlobal::all()]);
    }
    public function create() 
    {
        return view('planning.createPlanningPro'); 
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'jourD' => 'required', 
            'ouverture_matin'      => 'nullable|date_format:H:i',
            'fermeture_matin'      => 'nullable|date_format:H:i',
            'ouverture_aprem'      => 'nullable|date_format:H:i', 
            'fermeture_aprem'      => 'nullable|date_format:H:i'
        ]);

        $proId = 1 ;
                $jour = $request->input('jourD'); 

        DB::beginTransaction();

        try {
            $planningGlob = PlanningGlobal::firstOrCreate(
                ['idpro' => $proId]
            );
            
            $planningGlobId = $planningGlob->idplanningglob;
            
            $nouvelHoraire = Horaires::create([
                'heuredebutmatine' => $request->input('ouverture_matin'),
                'heurefinmatine'   => $request->input('fermeture_matin'),
                'heuredebutaprem'  => $request->input('ouverture_aprem'),
                'heurefinaprem'    => $request->input('fermeture_aprem'),
            ]);
            $nouvelHoraireId = $nouvelHoraire->idhoraire;

            
            $clesDeRecherche = [
                'idpro'          => $proId,
                'idplanningglob' => $planningGlobId,
                'idjour'         => $jour,
            ];

            DB::table('a_horaireglob')
                ->where('idpro', $proId)
                ->where('idplanningglob', $planningGlobId)
                ->where('idjour', $jour)
                ->delete();
            
            DB::table('a_horaireglob')->insert([
                'idpro'          => $proId,
                'idplanningglob' => $planningGlobId,
                'idjour'         => $jour,
                'idhoraire'      => $nouvelHoraireId
            ]);


            DB::commit();

            return redirect()->back()->with('success', 'Planning du ' . $jour . ' enregistré avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de l\'enregistrement des heures : ' . $e->getMessage());
        }
    }
}
