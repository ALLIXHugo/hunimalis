<?php

namespace App\Http\Controllers;

use App\Models\PriseMesure;
use Illuminate\Http\Request;
use App\Models\Animal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator; 

class PriseMesureController extends Controller
{
    public function create()
    {
        $animaux = Animal::all(['numtatouage', 'nom1animal']);
        return view('mesures.create', compact('animaux'));
    }

    public function store(Request $request)
    {
        $messages = [
            'numtatouage.required'        => 'Le numéro de puce est obligatoire.',
            'numtatouage.exists'          => 'Cet animal est introuvable.',
            'date_mesure.required'        => 'La date est obligatoire.',
            'date_mesure.date'            => 'Format de date invalide.',
            'date_mesure.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'poids.required'              => 'Le poids est obligatoire.',
            'poids.numeric'               => 'Le poids doit être un nombre.',
            'poids.gt'                    => 'Le poids doit être strictement supérieur à 0.',
            'poids.max'                   => 'Le poids semble excessif (max 999kg).',
        ];

        $validator = Validator::make($request->all(), [
            'numtatouage' => 'required|exists:animal,numtatouage',
            'date_mesure' => 'required|date|before_or_equal:today',
            'poids'       => 'required|numeric|gt:0|max:999.99',
        ], $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false, 
                    'message' => $validator->errors()->first() 
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            PriseMesure::create([
                'numtatouage' => $request->numtatouage,
                'date_mesure' => $request->date_mesure,
                'poids'       => $request->poids,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                $mesures = PriseMesure::where('numtatouage', $request->numtatouage)
                                      ->orderBy('date_mesure')
                                      ->get();
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Poids enregistré !',
                    'mesures' => $mesures
                ]);
            }

            return back()->with('success', 'Mesure enregistrée.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => "Erreur technique : " . $e->getMessage()], 500);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function storeSingle(Request $request)
    {
        $request->validate([
            'numtatouage' => 'required|exists:animal,numtatouage',
            'date_mesure' => 'required|date',
            'valeur'      => 'required|numeric',
            'type_mesure' => 'required|in:poids,taille,temperature' 
        ]);

        $data = [
            'numtatouage' => $request->numtatouage,
            'date_mesure' => $request->date_mesure,
        ];

        $data[$request->type_mesure] = $request->valeur;

        PriseMesure::create($data);

        return back()->with('success', ucfirst($request->type_mesure) . ' ajouté avec succès.');
    }
}