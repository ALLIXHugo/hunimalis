<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $table = "commande";
    protected $primaryKey = "idcommande";
    public $timestamps = false;

    protected $fillable = ['idfacture', 'datecommande'];

    public function facture()
    {
        return $this->belongsTo(Facture::class, 'idfacture', 'idfacture');
    }

    // --- AJOUTER CETTE MÉTHODE ---
    public function quantites()
    {
        // Une commande a plusieurs lignes de produits (table 'quantite')
        return $this->hasMany(Quantite::class, 'idcommande', 'idcommande');
    }
}
