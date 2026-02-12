<?php

namespace App\Http\Controllers;

use App\Models\Couleur;
use Illuminate\Http\Request;

class CouleurController extends Controller
{
    public function index(){
        return view("couleur-list", ['couleur' => Couleur::all()]);
    }
}
