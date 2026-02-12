<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriseMesure extends Model
{
    use HasFactory;
    protected $table = "prise_mesure";
    protected $primaryKey = "null";
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'numtatouage',
        'date_mesure',
        'poids',
        'taille',
        'temperature'
    ];

    protected $casts = [
        'date_mesure' => 'date',
        'poids' => 'decimal:2',
        'taille' => 'decimal:1',
        'temperature' => 'decimal:1',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'numtatouage', 'numtatouage');
    }
}
