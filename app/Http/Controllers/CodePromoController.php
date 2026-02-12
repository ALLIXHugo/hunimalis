<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CodePromo;
use App\Models\Professionnel;
use Illuminate\Support\Facades\Auth;

class CodePromoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'type' => 'required|in:pourcentage,euro',
            'valeur' => 'required|numeric|min:0',
            'date_fin' => 'nullable|date', 
        ]);

        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $codeClean = strtoupper($request->code);

        $exists = CodePromo::where('idpro', $pro->idpro)->where('code', $codeClean)->exists();
        if ($exists) {
            return back()->withInput()->with('error', "Le code promo $codeClean existe déjà.");
        }

        CodePromo::create([
            'idpro' => $pro->idpro,
            'code' => $codeClean,
            'type' => $request->type,
            'valeur' => $request->valeur,
            'date_fin' => $request->date_fin,
        ]);

        return back()->with('success', 'Code promo créé avec succès !') ->with('tab', 'marketing');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $pro = Professionnel::where('idpersonne', $user->idpersonne)->firstOrFail();

        $promo = CodePromo::where('idcodepromo', $id)->where('idpro', $pro->idpro)->firstOrFail();
        $promo->delete();

        return back()->with('success', 'Code promo supprimé.')->with('tab', 'marketing');    
    }
}