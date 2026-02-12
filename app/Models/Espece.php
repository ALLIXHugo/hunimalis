<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espece extends Model
{
    use HasFactory;
    protected $table = "espece";
    protected $primaryKey = "idespece";
    public $timestamps = false;
}
