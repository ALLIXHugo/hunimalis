<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    protected $table = "produit";
    protected $primaryKey = "idproduit";
    public $timestamps = false;

    protected $fillable = [
        'idproduit',
        'idpro',
        'libellecategoriepro',
        'descriptionProd',
        'nomproduit',
        'prixachat',
        'prixvente',
        'numserie',
        'numlot',
        'stocks',
        'seuilalerte',
        'photo'
    ];
    
    public function categorieproduit()
    {
        return $this->belongsTo(CategorieProduit::class, 'libellecategoriepro', 'libellecategoriepro');
    }
    public function professionnel()
    {
        // Un produit appartient à un professionnel via la colonne 'idpro'
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }

}
