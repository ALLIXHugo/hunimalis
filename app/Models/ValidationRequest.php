<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'idpro',
        'date_proposee',
        'heure_proposee',
        'statut',
        'message_pro'
    ];

    // Relation vers le professionnel (Table 'professionnel')
    public function professionnel()
    {
        return $this->belongsTo(Professionnel::class, 'idpro', 'idpro');
    }
}