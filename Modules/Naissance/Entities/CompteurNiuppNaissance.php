<?php

namespace Modules\Naissance\Entities;

use Illuminate\Database\Eloquent\Model;

class CompteurNiuppNaissance extends Model
{
    protected $table = 'tr_compteur_niupp_naissance';

    protected $guarded = [];

    protected $casts = [
        'annee' => 'integer',
        'mois' => 'integer',
        'dernier_ordre' => 'integer',
    ];
}
