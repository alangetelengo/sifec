<?php

namespace Modules\Mariage\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Registre;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\RetraitActe;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\Institution;

class ActeMariage extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = "t_acte_mariage";
    protected $primaryKey = "code_acte_mariage";
    public $incrementing = false;



    public function declaration(): BelongsTo
    {
        return $this->belongsTo(Declarationmariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
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
        return $this->hasOne(FeuilletRegistre::class, 'code_acte', 'code_acte_mariage');
    }

    //L'officier d'état-civil signataire de cet acte
    public function signataire(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'approbation_mairie', 'cui');
    }

    /**
     * Get the institution that owns the ActeMariage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }


       /**
     * Recupère le déclarant ayant rétiré l'ActeMariage
     *
     */
    public function retrait(): HasOne
    {
        return $this->hasOne(RetraitActe::class, 'code_acte', 'code_acte_mariage');
    }
}
