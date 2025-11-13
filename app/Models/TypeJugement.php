<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeJugement extends Model
{
    use HasFactory;
    protected $table = "tr_type_jugement";
    // protected $fillable = ["code_type_jugement","lib_type_jugement"];
    protected $guarded = [];
    protected $primaryKey = "code_type_jugement";
    public $incrementing = false;

}
