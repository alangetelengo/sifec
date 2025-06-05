<?php

namespace Modules\Naissance\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Referentiel\Entities\RetraitActe;

class ActeNaissance extends Model
{
    use HasFactory;
    

    protected $guarded = [];
    protected $table = "t_acte_naissance";
    protected $primaryKey = "niupp";
    public $incrementing = false;



    public function declaration(): BelongsTo
    {
        return $this->belongsTo(Declarationnaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }

    public function registre(): BelongsTo
    {
        return $this->belongsTo(Registre::class, 'code_registre', 'code_registre');
    }


    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }


    public function numeroActe(): HasOne
    {
        return $this->hasOne(FeuilletRegistre::class, 'code_acte', 'niupp');
    }

    //L'officier d'état-civil signataire de cet acte
    public function signataire(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'approbation_mairie', 'cui');
    }

    /**
     * Recupère le déclarant ayant rétiré l'ActeNaissance
     *
     */
    public function retrait(): HasOne
    {
        return $this->hasOne(RetraitActe::class, 'code_acte', 'niupp');
    }

}
