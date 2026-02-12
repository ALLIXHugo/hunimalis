<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professionnel extends Model
{
    use HasFactory;

    protected $table = "professionnel";    
    protected $primaryKey = "idpro";     
    public $timestamps = false;

    protected $fillable = [
        'idpro',
        'libelletypeetablissement',
        'libelleetablissement',
        'nompro',
        'prenompro',
        'civilitepro',
        'telpro',
        'nationalite',
        'melpro',
        'siret',
        'tva',
        'casierjudiciaireok',
        'cp',
        'ville',
        'pays',
        'instagram',
        'tiktok',
        'statut', 
        'code_verification',
        'idpersonne',
        'mode_paiement_annuel',
        'cb_titulaire',
        'cb_numero',
        'cb_expiration',
        'cb_cvv'
    ];

    // PROTECTION AUTOMATIQUE : Laravel crypte à l'écriture et décrypte à la lecture
    protected $casts = [
        'mode_paiement_annuel' => 'boolean',
        'cb_numero' => 'encrypted', // Chiffrement AES-256
        'cb_cvv'    => 'encrypted', // Chiffrement AES-256
    ];

    public function typeetablissement()
    {
        return $this->belongsTo(TypeEtablissement::class, 'libelletypeetablissement','libelletypeetablissement');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'idpersonne', 'idpersonne');
    }

    public function planningGlobal()
    {
        return $this->hasMany(PlanningGlobal::class, 'idpro', 'idpro');
    }

    public function validationRequests()
    {
        return $this->hasMany(ValidationRequest::class, 'idpro', 'idpro');
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'abonnement', 'idpro', 'idmodule');
    }

    public function prestations()
    {
        return $this->hasMany(Prestation::class, 'idpro', 'idpro');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne');
    }
}