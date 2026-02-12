<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $table = 'prestation';
    protected $primaryKey = 'idprestation';
    public $timestamps = false;

    protected $fillable = [
        'libellecategorie',
        'idespece',
        'idpro',
        'nomprestation',
        'tarifht',
        'duree'
    ];

    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }

    public function categorie()
    {
        return $this->belongsTo(CategoriePrestation::class, 'libellecategorie', 'libellecategorie');
    }

    public function espece()
    {
        return $this->belongsTo(Espece::class, 'idespece', 'idespece');
    }
}