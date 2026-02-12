<?php

namespace App\Http\Controllers;

use App\Models\Sociabilite;
use Illuminate\Http\Request;

class SociabiliteController extends Controller
{
    public function index(){
        return view("sociabilite-list", ['sociabilite' => Sociabilite::all()]);
    }
}
