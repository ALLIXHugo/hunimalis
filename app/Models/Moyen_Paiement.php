<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moyen_Paiement extends Model
{
    use HasFactory;
    protected $table = "moyen_paiement";
    protected $primaryKey = "idmoyenpaiement";
    public $timestamps = false;
}
