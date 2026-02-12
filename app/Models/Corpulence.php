<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corpulence extends Model
{
    use HasFactory;
    protected $table = "corpulence";
    protected $primaryKey = "idcorpulence";
    public $timestamps = false;
}
