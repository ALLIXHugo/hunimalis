<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jour extends Model
{
    use HasFactory;
    protected $table = "jour";
    protected $primaryKey = "idjour";
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    public function horaires()
    {
        return $this->belongsToMany(Horaires::class, 'a_horaireemp', 'idjour', 'idhoraire');
    }
}