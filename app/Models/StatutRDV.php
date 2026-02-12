<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutRDV extends Model
{
    use HasFactory;
    protected $table = "statutrdv";
    protected $primaryKey = "idstatut";
    public $timestamps = false;
}
