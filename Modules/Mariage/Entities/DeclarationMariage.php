<?php

namespace Modules\Mariage\Entities;

use App\Models\Jugement;
use App\Models\Requisition;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Mariage\Entities\Signature;
use Modules\Referentiel\Entities\Regime;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Profession;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Referentiel\Entities\OptionMariage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class DeclarationMariage extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = "t_declaration_mariage";
    protected $primaryKey = "code_declaration_mariage";
    public $incrementing = false;

    protected $casts = [
        'sig_cec_signed_at' => 'datetime',
        'sig_cec_doc_sig_signed_at' => 'datetime',
        'sig_cec_doc_seal_sealed_at' => 'datetime',
    ];




    public function epoux(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_epoux', 'code_personne');
    }

    public function temoinHommeEpoux(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_temoin_homme_epoux', 'code_personne');
    }
    public function temoinFemmeEpoux(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_temoin_femme_epoux', 'code_personne');
    }


    public function temoinHommeEpouse(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_temoin_homme_epouse', 'code_personne');
    }
    public function temoinFemmeEpouse(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_temoin_femme_epouse', 'code_personne');
    }

    public function epouse(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_epouse', 'code_personne');
    }

    public function filiation(): BelongsTo
    {
        return $this->belongsTo(Filiation::class, 'code_filiation_chef_famille', 'code_filiation');
    }

    public function regime(): BelongsTo
    {
        return $this->belongsTo(Regime::class, 'code_regime', 'code_regime');
    }

    public function optionMariage(): BelongsTo
    {
        return $this->belongsTo(OptionMariage::class, 'code_option_mariage', 'code_option_mariage');
    }

    public function situationMatEpoux(): BelongsTo
    {
        return $this->belongsTo(SituationMatrimoniale::class, 'code_situation_mat_epoux', 'code_situation_matrimoniale');
    }

    public function situationMatEpouse(): BelongsTo
    {
        return $this->belongsTo(SituationMatrimoniale::class, 'code_situation_mat_epouse', 'code_situation_matrimoniale');
    }

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(\Modules\Referentiel\Entities\Institution::class, 'code_institution', 'code_institution');
    }


    public function mouvements(): HasMany
    {
        return $this->hasMany(\Modules\Mariage\Entities\MouvementMariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }

    public function acte(): HasOne
    {
        return $this->hasOne(ActeMariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }

    public function signatureActe(): HasOne
    {
        return $this->hasOne(Signature::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }


    public function livretFamille(): HasOne
    {
        return $this->hasOne(LivretFamille::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }

    public function professionEpoux(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'code_profession_epoux', 'code_profession');
    }

    public function professionEpouse(): BelongsTo
    {
        return $this->belongsTo(Profession::class, 'code_profession_epouse', 'code_profession');
    }

    //pour retracer le jugement venant du tribunal
    public function jugement(): HasOne
    {
        return $this->hasOne(Jugement::class, 'code_declaration', 'code_declaration_mariage');
    }

    //pour retracer la réquisition venant du tribunal
    public function requisition(): HasOne
    {
        return $this->hasOne(Requisition::class, 'code_declaration', 'code_declaration_mariage');
    }

}
