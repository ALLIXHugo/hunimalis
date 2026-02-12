<?php

namespace App\Http\Controllers;

use App\Models\Corpulence;
use Illuminate\Http\Request;

class CorpulenceController extends Controller
{
    public function index(){
        return view("corpulence-list", ['corpulence' => Corpulence::all()]);
    }
}
