<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oreilles extends Model
{
    use HasFactory;
    protected $table = "oreilles";
    protected $primaryKey = "idoreille";
    public $timestamps = false;
}
