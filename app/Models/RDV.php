<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RDV extends Model
{
    use HasFactory;
    protected $table = "rdv";
    protected $primaryKey = "idrdv";
    public $timestamps = false;
    
    // On remplace le tableau par la clé unique 'idclient'
    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient', 'idclient');
    }

    public function statut()
    {
        return $this->belongsTo(StatutRDV::class, 'idstatut', 'idstatut');
    }
    public function animal() {
        return $this->belongsTo(Animal::class, 'numtatouage', 'numtatouage');
    }
    
    public function prestation() {
        return $this->belongsToMany(Prestation::class, 'liepresta', 'idrdv', 'idprestation');
    }

    public function prestations() {
        return $this->belongsToMany(Prestation::class, 'liepresta', 'idrdv', 'idprestation');
    }
    // Relation corrigée également pour les praticiens (si nécessaire)
    public function practiciens()
    {
        return $this->belongsToMany(Practicien::class, 'realiserdv', 'idrdv', 'idpracticien');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }
}
