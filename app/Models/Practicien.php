<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practicien extends Model
{
    use HasFactory;
    protected $table = "practicien";
    protected $primaryKey = "idpracticien";
    public $timestamps = false;

    protected $fillable = ['idpersonne', 'idemploye', 'libellepracticien'];
    
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'idemploye', 'idemploye')
                    ->where('idpersonne', $this->idpersonne);
    }
}
