<?php

namespace App\Http\Controllers;

use App\Models\Aspectpelage;
use Illuminate\Http\Request;

class AspectpelageController extends Controller
{
    public function index(){
    	return view ("aspectpelage-list", ['aspectpelage'=>Aspectpelage::all() ]);
    }
}