<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(){
        return view("module-list", ['module' => Module::all()]);
    }
}
