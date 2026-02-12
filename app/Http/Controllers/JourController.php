<?php

namespace App\Http\Controllers;

use App\Models\Jour;
use Illuminate\Http\Request;

class JourController extends Controller
{
    public function index(){
        return view("jour-list", ['jour' => Jour::all()]);
    }
}
