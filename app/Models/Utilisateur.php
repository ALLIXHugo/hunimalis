<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use Notifiable;

    protected $table = 'utilisateur';
    protected $primaryKey = 'idutilisateur';
    public $timestamps = false;

    protected $fillable = [
        'idpersonne',
        'loginutilisateur', 
        'mdputilisateur',
        'verification_code', 
        'google_id',        
    ];

    protected $hidden = [
        'mdputilisateur',
        'verification_code',
    ];

    protected $casts = [
        'mdputilisateur' => 'hashed',
    ];

    public function getAuthPassword()
    {
        return $this->mdputilisateur;
    }

    public function getAuthIdentifierName()
    {
        return 'loginutilisateur';
    }

    public function personne()
    {
        return $this->belongsTo(Personne::class, 'idpersonne', 'idpersonne');
    }

    public function employe()
    {
        return $this->hasOne(Employe::class, 'idpersonne', 'idpersonne');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'idpersonne', 'idpersonne');
    }

    public function getRoleAttribute()
    {
        if ($this->employe()->exists()) {
            return 'employe';
        }
        
        return 'client';
    }
    
    public function getNomCompletAttribute()
    {
        return $this->personne ? $this->personne->prenom . ' ' . $this->personne->nom : 'Inconnu';
    }
}