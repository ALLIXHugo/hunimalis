<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaillePelage extends Model
{
    use HasFactory;
    protected $table = "taillepelage";
    protected $primaryKey = "idtaillepelage";
    public $timestamps = false;
}
