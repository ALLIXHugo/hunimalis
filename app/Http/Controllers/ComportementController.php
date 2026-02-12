<?php

namespace App\Http\Controllers;

use App\Models\Comportement;
use Illuminate\Http\Request;
use App\Models\Sociabilite;
use App\Models\Peurs;
use Illuminate\Support\Facades\DB;


class ComportementController extends Controller
{
    public function create()
    {
        $sociabilites = Sociabilite::all();
        $peurs = Peur::all();
        $hygieneOptions = ['bon', 'moyen', 'mauvais'];

        return view('comportements.create', compact('sociabilites', 'peurs', 'hygieneOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'griffeurmordeur' => 'required|boolean',
            'marcheautorise' => 'required|boolean',
            'photosautorise' => 'required|boolean',
            'protectionparasiteinterne' => 'required|boolean',
            'protectionparasiteexterne' => 'required|boolean',
            
            'hygiene' => 'required|in:bon,moyen,mauvais',
            'traitcaractere' => 'nullable|string|max:100', 
            
            'idsociabilite' => 'required|exists:sociabilite,idsociabilite',
            'soc_idsociabilite' => 'required|exists:sociabilite,idsociabilite',
            'soc_idsociabilite2' => 'required|exists:sociabilite,idsociabilite',
            
            'peurs_selectionnees' => 'nullable|array',
            'peurs_selectionnees.*' => 'exists:peurs,idpeur', 
        ]);

        DB::beginTransaction();
        try {
            $comportement = Comportement::create([
                'griffeurmordeur' => $request->griffeurmordeur,
                'marcheautorise' => $request->marcheautorise,
                'photosautorise' => $request->photosautorise,
                'protectionparasiteinterne' => $request->protectionparasiteinterne,
                'protectionparasiteexterne' => $request->protectionparasiteexterne,
                'hygiene' => $request->hygiene,
                'traitcaractere' => $request->traitcaractere,
                'idsociabilite' => $request->idsociabilite,
                'soc_idsociabilite' => $request->soc_idsociabilite,
                'soc_idsociabilite2' => $request->soc_idsociabilite2,
            ]);

            if ($request->has('peurs_selectionnees')) {
                $comportement->peurs()->sync($request->peurs_selectionnees);
            }

            DB::commit();

            return redirect()->route('comportements.create')->with('success', 'Le nouveau comportement (ID: ' . $comportement->idcomportement . ') a été créé et lié avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur lors de la création du comportement. Détail: ' . $e->getMessage());
        }
    }
}