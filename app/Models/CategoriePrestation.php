<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriePrestation extends Model
{
    // Nom de la table
    protected $table = 'categorieprestation';

    // Clé primaire personnalisée (selon ton SQL)
    protected $primaryKey = 'libellecategorie';

    // Important : Indiquer à Laravel que la clé n'est pas un auto-increment
    public $incrementing = false;
    protected $keyType = 'string';

    // Champs remplissables
    protected $fillable = [
        'libellecategorie',
        'cat_libellecategorie'
    ];

    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(CategoriePrestation::class, 'cat_libellecategorie', 'libellecategorie');
    }

    public function enfants()
    {
        return $this->hasMany(CategoriePrestation::class, 'cat_libellecategorie', 'libellecategorie');
    }

    public function prestations()
    {
        return $this->hasMany(Prestation::class, 'libellecategorie', 'libellecategorie');
    }
}