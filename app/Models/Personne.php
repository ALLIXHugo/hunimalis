<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personne extends Model
{
    protected $table = 'personne';
    protected $primaryKey = 'idpersonne';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = ['nom', 'prenom', 'tel', 'mail', 'civilite', 'note', 'avatar'];

    public function employe()
    {
        return $this->hasOne(Employe::class, 'idpersonne', 'idpersonne');
    }

    public function animaux()
    {
        return $this->hasMany(Animal::class, 'idpersonne', 'idpersonne');
    }
    public function professionnel()
    {
        return $this->hasOne(Professionnel::class, 'idpersonne', 'idpersonne');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'idpersonne', 'idpersonne');
    }
}