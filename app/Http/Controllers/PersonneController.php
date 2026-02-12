<?php

namespace App\Http\Controllers;

use App\Models\Personne;
use Illuminate\Http\Request;

class PersonneController extends Controller
{
    public function index(){
        return view("personne-list", ['personne' => Personne::all()]);
    }
}
