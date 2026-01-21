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
//id 	lib_pays 	lib_ville 	type_voie 	nom_voie 	numero_rue 	code_localite 	code_personne
    protected $guarded = [];
    protected $table = "t_residence_personne";
    protected $primaryKey="id";
    public $incrementing = true;


    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }

    public function quertierVillage(): BelongsTo
    {
        // Utiliser code_localite car code_quartier_localite n'existe plus
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }

    public function communeDistrict(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }

}
