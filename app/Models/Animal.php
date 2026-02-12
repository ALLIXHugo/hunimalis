<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animal';
    protected $primaryKey = 'numtatouage';
    public $timestamps = false; 
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];

    protected $fillable = [
        'numtatouage', 'nom1animal', 'nom2animal', 'sexe', 'datenaissance', 'lieunaissance', 'photo',
        
        'idpro_toiletteur',
        'idpro_educateur',
        'idpro_veterinaire',
        'idpro_pension',

        'idpersonne', 'idclient', 'idespece', 'idrace', 'rac_idrace', 'rac_idespece',
        'idcouleur', 'idcouleuryeux', 'idqueue', 'idaspectpelage',
        'idtaillepelage', 'idpelage', 'idcorpulence', 'idcomportement'
    ];

    protected static function booted() // pour nettoyer des espaces
    {
        static::retrieved(function ($animal) {
            $animal->numtatouage = trim($animal->numtatouage);
        });
    }

    public function couleur()
    {
        return $this->belongsTo(Couleur::class, 'idcouleur');
    }

    public function couleurYeux()
    {
        return $this->belongsTo(CouleurYeux::class, 'idcouleuryeux');
    }

    public function oreilles()
    {
        return $this->belongsToMany(Oreilles::class, 'a_typeoreille', 'numtatouage', 'idoreille');
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class, 'idqueue');
    }

    public function corpulence()
    {
        return $this->belongsTo(Corpulence::class, 'idcorpulence');
    }

    public function aspectPelage()
    {
        return $this->belongsTo(AspectPelage::class, 'idaspectpelage');
    }

    public function taillePelage()
    {
        return $this->belongsTo(TaillePelage::class, 'idtaillepelage');
    }

    public function caracPelage()
    {
        return $this->belongsTo(CaracPelage::class, 'idpelage');
    }

    public function comportement()
    {
        return $this->belongsTo(Comportement::class, 'idcomportement');
    }
    
    public function espece()
    {
        return $this->belongsTo(Espece::class, 'idespece');
    }

    public function race()
    {
        return $this->belongsTo(Race::class, 'idrace');
    }

    public function croisement()
    {
        return $this->belongsTo(Race::class, 'rac_idrace');
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne'); 
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'idclient');
    }

    public function caracteristique()
    {
        return $this->hasOne(CaracteristiquesAnimal::class, 'numtatouage', 'numtatouage');
    }

    public function signes()
    {
        return $this->belongsToMany(SignesDistinctifs::class, 'a_signes', 'numtatouage', 'idsigne');
    }

    public function priseMesures()
    {
        return $this->hasMany(PriseMesure::class, 'numtatouage', 'numtatouage');
    }

    public function sociabilite()
    {
        return $this->hasMany(Sociabilite::class, 'idsociabilite');
    }

    public function peurs()
    {
        return $this->hasMany(Peurs::class, 'idpeur');
    }

    public function proprietaire()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }

    public function toiletteur()
    {
        return $this->belongsTo(Professionnel::class, 'idpro_toiletteur');
    }

    public function educateur()
    {
        return $this->belongsTo(Professionnel::class, 'idpro_educateur');
    }

    public function veterinaire()
    {
        return $this->belongsTo(Professionnel::class, 'idpro_veterinaire');
    }

    public function pension()
    {
        return $this->belongsTo(Professionnel::class, 'idpro_pension');
    }

    public function assurance()
    {
        return $this->belongsTo(Professionnel::class, 'idpro_assurance');
    }
}