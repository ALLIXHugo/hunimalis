<?php

namespace App\Http\Controllers;

use App\Models\Race;
use Illuminate\Http\Request;

class RaceController extends Controller
{
    public function index(){
        return view("race-list", ['race' => Race::all()]);
    }
}
