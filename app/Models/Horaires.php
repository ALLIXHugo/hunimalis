<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaires extends Model
{
    use HasFactory;
    protected $table = "horaires";
    protected $primaryKey = "idhoraire";
    public $timestamps = false;

    protected $fillable = [
        'heuredebutmatine', 
        'heurefinmatine', 
        'heuredebutaprem', 
        'heurefinaprem'
    ]; 

    public function jours()
    {
        return $this->belongsToMany(Jour::class, 'a_horaireemp', 'idhoraire', 'idjour');
    }

    public function employes()
    {
        return $this->belongsToMany(Employe::class, 'a_horaireemp', 'idhoraire', 'idemploye');
    }
}