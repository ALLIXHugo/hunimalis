<?php

namespace App\Http\Controllers;

use App\Models\TaillePelage;
use Illuminate\Http\Request;

class TaillePelageController extends Controller
{
    public function index(){
        return view("taillePelage-list", ['taillePelage' => TaillePelage::all()]);
    }
}
