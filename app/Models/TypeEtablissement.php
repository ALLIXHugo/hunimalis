<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEtablissement extends Model
{
    use HasFactory;
    protected $table = "typeetablissement";
    protected $primaryKey = "libelletypeetablissement";
    public $timestamps = false;
    public $incrementing = false;

    // 3. IMPORTANT : Préciser que la clé est une chaîne de caractères (string)
    protected $keyType = 'string';
    protected $fillable = ['libelletypeetablissement', 'cat_libellecategorie', 'prix_base_mensuel', 'prix_base_annuel'];

    // Relation vers les modules DISPONIBLES (Catalogue par métier)
    public function modulesDisponibles()
    {
        return $this->belongsToMany(Module::class, 'a_module_dispo', 'libelletypeetablissement', 'idmodule')
                    ->withPivot('est_inclus'); // Important pour accéder à cette info
    }
}


