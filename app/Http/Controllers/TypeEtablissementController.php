<?php

namespace App\Http\Controllers;

use App\Models\TypeEtablissement;
use Illuminate\Http\Request;

class TypeEtablissementController extends Controller
{
    public function index(){
        return view("typeEtablissement-list", ['typeEtablissement' => TypeEtablissement::all()]);
    }
}
