<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(){
        return view("queue-list", ['queue' => Queue::all()]);
    }
}
