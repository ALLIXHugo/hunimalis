<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaracteristiquesAnimal extends Model
{
    use HasFactory;
    protected $table = "caracteristiquesanimal";
    protected $primaryKey = "idca";
    public $timestamps = false;
    protected $guarded = [];

    protected $fillable = [
        'numtatouage', 
        'sterilise', 
        'degriffe'
    ];
}
