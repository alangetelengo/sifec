<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Village;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Quartier;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdressePersonne extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_residence_personne";


    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }


    public function quertierVillage(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }

    // public function arrondissement(): BelongsTo
    // {
    //     return $this->belongsTo(Arrondissement::class, 'code_arrondissement', 'code_arrondissement');
    // }


    // public function quartier(): BelongsTo
    // {
    //     return $this->belongsTo(Quartier::class, 'code_quartier', 'code_quartier');
    // }

    // public function village(): BelongsTo
    // {
    //     return $this->belongsTo(Village::class, 'code_village', 'code_village');
    // }

}
