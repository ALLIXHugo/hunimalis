<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningEmploye extends Model
{
    use HasFactory;
    protected $table = "planningemploye";
    protected $primaryKey = "idplanningemp";
    public $timestamps = false;

    protected $fillable = [
        'idpersonne', 
        'idemploye',
        'idplanningemp',
        'idjour',
        'idhoraire',
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'idemploye', 'idemploye');
    }
    
    public function horaire()
    {
        return $this->belongsTo(Horaires::class, 'idhoraire', 'idhoraire');
    }

    public function jour()
    {
        return $this->belongsTo(Jour::class, 'idjour', 'idjour');
    }
}