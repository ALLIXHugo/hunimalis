<?php

namespace App\Http\Controllers;

use App\Models\Praticien;
use Illuminate\Http\Request;

class PraticienController extends Controller
{
    public function index(){
        return view("praticien-list", ['praticien' => Praticien::all()]);
    }
}
