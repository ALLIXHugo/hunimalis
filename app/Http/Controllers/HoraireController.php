<?php

namespace App\Http\Controllers;

use App\Models\Horaire;
use Illuminate\Http\Request;

class HoraireController extends Controller
{
    public function index(){
        return view("horaire-list", ['horaire' => Horaire::all()]);
    }
}
