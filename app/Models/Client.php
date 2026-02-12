<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    
    protected $table = 'client';
    protected $primaryKey = 'idclient';
    public $timestamps = false;

    protected $fillable = [
        'idpersonne',
        'code_postal', 
        'ville'
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }

    public function animaux()
    {
        return $this->hasMany(Animal::class, 'idclient', 'idclient');
    }
}