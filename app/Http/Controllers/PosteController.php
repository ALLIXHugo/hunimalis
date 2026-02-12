<?php

namespace App\Http\Controllers;

use App\Models\Poste;
use Illuminate\Http\Request;
use App\Models\Professionnel;

class PosteController extends Controller
{
    public function create()
    {
        $professionnels = Professionnel::all(['idpro', 'nompro', 'libelletypeetablissement']);

        return view('postes.create', compact('professionnels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelleposte' => 'required|string|max:50', 
            'idpro' => 'required|exists:professionnel,idpro', 
        ]);
        
        try {
            Poste::create([
                'libelleposte' => $request->libelleposte,
                'idpro' => $request->idpro,
            ]);

            return redirect()->route('postes.create')->with('success', 'Le poste "' . $request->libelleposte . '" a été créé avec succès.');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erreur lors de la création du poste. Détail: ' . $e->getMessage());
        }
    }
}