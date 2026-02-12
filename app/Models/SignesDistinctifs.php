<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignesDistinctifs extends Model
{
    use HasFactory;
    protected $table = "signesdistinctifs";
    protected $primaryKey = "idsigne";
    public $timestamps = false;
    protected $fillable = ['libellesigne'];
}
