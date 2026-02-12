<?php

namespace App\Http\Controllers;

use App\Models\Categorieproduit;
use Illuminate\Http\Request;

class CategorieproduitController extends Controller
{
    public function index(){
    	return view ("categorieproduit-list", ['categorieproduit'=>Categorieproduit::all() ]);
    }
}