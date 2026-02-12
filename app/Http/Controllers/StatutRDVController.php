<?php

namespace App\Http\Controllers;

use App\Models\StatutRDV;
use Illuminate\Http\Request;

class StatutRDVController extends Controller
{
    public function index(){
        return view("statutRDV-list", ['statutRDV' => StatutRDV::all()]);
    }
}
