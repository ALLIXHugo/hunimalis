<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracPelage extends Model
{
    use HasFactory;
    protected $table = "caracpelage";
    protected $primaryKey = "idpelage";
    public $timestamps = false;
}
