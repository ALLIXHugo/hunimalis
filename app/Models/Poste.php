<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    use HasFactory;
    protected $table = "poste";
    protected $primaryKey = "idposte";
    public $timestamps = false;

    // Champs que nous permettons d'assigner massivement
    protected $fillable = [
        'idpro', 
        'libelleposte'
    ];

    // Relation avec le Professionnel
    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }
}
