<?php

namespace App\Http\Controllers;

use App\Models\Couloreureux;
use Illuminate\Http\Request;

class CouloreurYeuxController extends Controller
{
    public function index(){
        return view("couloreureux-list", ['couloreureux' => CouloreurYeux::all()]);
    }
}
