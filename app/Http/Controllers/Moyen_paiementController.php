<?php

namespace App\Http\Controllers;

use App\Models\MoyenPaiement;
use Illuminate\Http\Request;

class Moyen_paiementController extends Controller
{
    public function index(){
        return view("moyen_paiement-list", ['moyen_paiement' => MoyenPaiement::all()]);
    }
}
