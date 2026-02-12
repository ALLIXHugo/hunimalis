<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comportement extends Model
{
    use HasFactory;
    protected $table = "comportement";
    protected $primaryKey = "idcomportement";
    public $timestamps = false;

    protected $fillable = [
        'griffeurmordeur', 
        'marcheautorise', 
        'photosautorise', 
        'protectionparasiteinterne', 
        'protectionparasiteexterne', 
        'hygiene', 
        'traitcaractere', 
        'idsociabilite',        
        'soc_idsociabilite',    
        'soc_idsociabilite2',   
    ];

    // Les champs booléens doivent être castés pour une manipulation facile
    protected $casts = [
        'griffeurmordeur' => 'boolean',
        'marcheautorise' => 'boolean',
        'photosautorise' => 'boolean',
        'protectionparasiteinterne' => 'boolean',
        'protectionparasiteexterne' => 'boolean',
    ];

    // Relation avec la table Sociabilite (clé principale)
    public function sociabilitePrimaire()
    {
        return $this->belongsTo(Sociabilite::class, 'idsociabilite', 'idsociabilite');
    }

    // Relation Many-to-Many avec Peurs
    public function peurs()
    {
        return $this->belongsToMany(Peurs::class, 'apeurs', 'idcomportement', 'idpeur');
    }
}
