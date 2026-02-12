<?php

namespace App\Http\Controllers;

use App\Models\Oreilles;
use Illuminate\Http\Request;

class OreillesController extends Controller
{
    public function index(){
        return view("oreilles-list", ['oreilles' => Oreilles::all()]);
    }
}
