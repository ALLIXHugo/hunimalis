<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Personne;
use App\Models\Poste;
use App\Models\Professionnel;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    public function create()
    {
        $postes = Poste::all()->unique('libelleposte');
        
        return view('employes.create', compact('postes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'tel' => [
                'nullable',
                'string',
                'max:10',
                'regex:/^(06|07|03|04|01|02|05|09)[0-9]{8}$/'
            ], 
            'mail' => 'nullable|email|max:100',
            'civiliteemploye' => 'required|in:homme,femme',
            'idposte' => 'required|exists:poste,idposte',
            'accesutilisateur' => 'required|in:true,false', 
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'tel.regex' => 'Le format du téléphone est invalide (10 chiffres attendus).',
            'mail.email' => 'L\'adresse email n\'est pas valide.',
            'civiliteemploye.required' => 'La civilité est requise.',
            'idposte.required' => 'Veuillez sélectionner un poste.',
            'idposte.exists' => 'Le poste sélectionné est invalide.',
            'accesutilisateur.required' => 'Veuillez définir l\'accès utilisateur.',
        ]);

        if (empty($request->tel) && empty($request->mail)) {
             return back()->withInput()->withErrors(['contact' => 'Une personne doit avoir au moins un numéro de téléphone ou un email !']);
        }
        
        DB::beginTransaction();
        try {
            $personne = Personne::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'tel' => $request->tel,
                'mail' => $request->mail,
                'civilite' => $request->civiliteemploye,
            ]);

            Employe::create([
                'idpersonne' => $personne->idpersonne,
                'idposte' => $request->idposte,
                'idpro' => 6,
                'civiliteemploye' => $request->civiliteemploye,
                'accesutilisateur' => $request->accesutilisateur,
            ]);

            DB::commit();

            return redirect()->route('employes.create')->with('success', 'L\'employé ' . $request->prenom . ' ' . $request->nom . ' a été créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur technique : ' . $e->getMessage());
        }
    }
}