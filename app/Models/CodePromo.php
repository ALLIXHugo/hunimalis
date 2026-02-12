<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodePromo extends Model
{
    use HasFactory;

    protected $table = 'codepromo';
    protected $primaryKey = 'idcodepromo';
    public $timestamps = false;

    // C'EST CETTE PARTIE QUI EST IMPORTANTE :
    protected $fillable = [
        'idpro',
        'code',
        'type',
        'valeur',
        'date_fin'
    ];

    protected $casts = [
        'date_fin' => 'date',
        'valeur' => 'float'
    ];
}