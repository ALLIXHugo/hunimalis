<?php

namespace App\Http\Controllers;

use App\Models\Caracpelage;
use Illuminate\Http\Request;

class CaracPelageController extends Controller
{
    public function index(){
    	return view ("caracpelage-list", ['caracpelage'=>Caracpelage::all() ]);
    }
}