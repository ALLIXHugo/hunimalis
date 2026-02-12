<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peurs extends Model
{
    use HasFactory;
    protected $table = "peurs";
    protected $primaryKey = "idpeur";
    public $timestamps = false;
    protected $fillable = ['libellepeur'];

    // Relation Many-to-Many inverse
    public function comportements()
    {
        return $this->belongsToMany(Comportement::class, 'apeurs', 'idpeur', 'idcomportement');
    }
}
