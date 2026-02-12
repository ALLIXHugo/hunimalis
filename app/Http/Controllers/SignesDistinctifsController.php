<?php

namespace App\Http\Controllers;

use App\Models\SigneDistinctifs;
use Illuminate\Http\Request;

class SigneDistinctifsController extends Controller
{
    public function index(){
        return view("signeDistinctifs-list", ['signeDistinctifs' => SigneDistinctifs::all()]);
    }
}
