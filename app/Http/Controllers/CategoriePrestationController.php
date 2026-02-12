<?php

namespace App\Http\Controllers;

use App\Models\CategoriePrestation;
use Illuminate\Http\Request;

class CategoriePrestationController extends Controller
{
    public function index()
    {
        $categories = CategoriePrestation::whereNull('cat_libellecategorie')
                                         ->with('enfants')
                                         ->get();

        return view('prestation.categories.index', compact('categories'));
    }

    public function create()
    {
        $categoriesPrincipales = CategoriePrestation::whereNull('cat_libellecategorie')->get();

        return view('prestation.categories.create', compact('categoriesPrincipales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:50|unique:categorieprestation,libellecategorie',
            'parent_id' => 'nullable|exists:categorieprestation,libellecategorie'
        ]);

        $categorie = new CategoriePrestation();
        $categorie->libellecategorie = $request->libelle;
        $categorie->cat_libellecategorie = $request->parent_id; 

        $categorie->save();

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès !');
    }
}