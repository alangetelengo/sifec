<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class AuthentificationActe extends Model
{
    use HasFactory;

    protected $table = "tr_authentification_acte";
    protected $primaryKey = "code_authentification";
    protected $guarded = [];
    public $incrementing = false;



}
