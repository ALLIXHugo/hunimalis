<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ValidationRequest;
use Illuminate\Support\Facades\Auth;

class ValidationController extends Controller
{
    public function create()
    {
        return view('rdv.validation.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_proposee' => 'required|date|after:today',
            'heure_proposee' => 'required',
            'message_pro' => 'nullable|string|max:500',
        ], [
            'date_proposee.after' => 'La date proposée doit être une date strictement supérieure à la date d\'aujourd\'hui.',
        ]);

        $idPro = 6;
        
        ValidationRequest::create([
            'idpro' => $idPro, 
            'date_proposee' => $request->date_proposee,
            'heure_proposee' => $request->heure_proposee,
            'statut' => 'en_attente',
            'message_pro' => $request->message_pro
        ]);

        return redirect()->back()->with('success', 'Votre proposition de date a été envoyée à Hunimalis.');
    }
}