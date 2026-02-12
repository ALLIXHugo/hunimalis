<?php

namespace App\Http\Controllers;

use App\Models\Espece;
use Illuminate\Http\Request;

class EspeceController extends Controller
{
    public function index(){
        return view("espece-list", ['espece' => Espece::all()]);
    }
}
