<?php

namespace App\Models;


use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class DemandePortailParticulier extends Model
{
    use HasFactory;

    protected $table = "tr_demande_portail_particulier";
    protected $primaryKey = "code_demande";
    protected $guarded = [];
    public $incrementing = true;


}
