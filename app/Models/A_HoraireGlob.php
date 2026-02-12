<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class A_HoraireGlob extends Pivot
{
    protected $table = 'a_horaireglob';
    public $timestamps = false;
    
    protected $fillable = ['idplanningglob', 'idhoraire', 'idjour','idpro'];

    public function planningGlobal()
    {
        return $this->belongsTo(PlanningGlobal::class, 'idplanningglob', 'idplanningglob');
    }
    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
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