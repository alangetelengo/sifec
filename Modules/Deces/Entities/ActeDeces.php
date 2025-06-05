<?php

namespace Modules\Deces\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Registre;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\RetraitActe;
use Modules\Referentiel\Entities\ActeRegistre;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActeDeces extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_acte_deces";
    protected $primaryKey = "code_acte_deces";
    public $incrementing = false;


    public function declaration(): BelongsTo
    {
        return $this->belongsTo(DeclarationDeces::class, 'code_declaration_deces', 'code_declaration_deces');
    }

    /**
     * Get the registre that owns the ActeDeces
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
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
        return $this->hasOne(FeuilletRegistre::class, 'code_acte', 'code_acte_deces');
    }

    //L'officier d'état-civil signataire de cet acte
    public function signataire(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'approbation_pompe_funebre', 'cui');
    }

     /**
     * Recupère le déclarant ayant rétiré l'ActeDeces
     *
     */
    public function retrait(): HasOne
    {
        return $this->hasOne(RetraitActe::class, 'code_acte', 'code_acte_deces');
    }

}
