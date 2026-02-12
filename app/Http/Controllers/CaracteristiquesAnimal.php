<?php

namespace App\Http\Controllers;

use App\Models\Caracteristiquesanimal;
use Illuminate\Http\Request;

class CaracteristiquesAnimalController extends Controller
{
    public function index(){
    	return view ("caracteristiquesanimal-list", ['caracteristiquesanimal'=>Caracteristiquesanimal::all() ]);
    }
}