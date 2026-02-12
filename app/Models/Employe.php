<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;
    protected $table = "employe";
    protected $primaryKey = "idemploye";
    public $timestamps = false;

    // CORRECTION ICI : On retire nom, prenom, tel, mail
    protected $fillable = [
        'idpersonne', 
        'idposte', 
        'idpro', 
        'civiliteemploye', 
        'accesutilisateur'
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class, 'idposte', 'idposte');
    }

    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }
    
    // ... vos autres relations ...
    public function horairesTheoriques()
    {
        return $this->hasMany(A_HoraireEmp::class, 'idemploye', 'idemploye');
    }
    
    public function planningsReels()
    {
        return $this->hasMany(PlanningEmploye::class, 'idemploye', 'idemploye');
    }
}