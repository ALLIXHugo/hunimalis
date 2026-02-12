<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class A_HoraireEmp extends Model
{
    protected $table = 'a_horaireemp';
    public $timestamps = false;
    protected $primaryKey = null;     
    public $incrementing = false;

    protected $fillable = [
        'idpersonne',
        'idemploye', 
        'idplanningemp',
        'idhoraire', 
        'idjour'
    ];

    public function employe()
    {
        return $this->belongsTo(Employe::class, 'idemploye', 'idemploye');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
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