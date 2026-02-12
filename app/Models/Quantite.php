<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quantite extends Model
{
    use HasFactory;

    protected $table = "quantite";
    // Clé primaire composite, Laravel gère mal par défaut, mais pour la lecture ça ira
    public $timestamps = false;

    protected $fillable = ['idproduit', 'idcommande', 'quantite'];

    public function produit()
    {
        return $this->belongsTo(Produit::class, 'idproduit', 'idproduit');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'idcommande', 'idcommande');
    }
}