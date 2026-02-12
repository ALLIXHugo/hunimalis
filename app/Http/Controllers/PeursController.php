<?php

namespace App\Http\Controllers;

use App\Models\Peurs;
use Illuminate\Http\Request;

class PeursController extends Controller
{
    public function index(){
        return view("peurs-list", ['peurs' => Peurs::all()]);
    }
}
