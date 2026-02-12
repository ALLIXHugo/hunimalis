<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanningGlobal extends Model
{
    use HasFactory;
    protected $table = "planningglobal";
    
    // CORRECTION : Définir UNIQUEMENT la clé auto-incrémentée
    protected $primaryKey = "idplanningglob"; 
    
    // Ajouter ceci pour indiquer qu'elle est bien auto-incrémentée (c'est le cas pour 'serial')
    public $incrementing = true;
    
    // Par défaut, Eloquent pense que la clé est un INT, ce qui est correct
    // protected $keyType = 'int'; 

    public $timestamps = false;

    protected $fillable = [
        'idpro',
        'idplanningglob' // Bien que généré, il est bon de le garder si vous le gérez manuellement à un moment donné.
    ];

    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }
}
