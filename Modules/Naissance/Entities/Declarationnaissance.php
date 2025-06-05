<?php

namespace Modules\Naissance\Entities;

use App\Models\InstitutionUser;
use App\Models\Jugement;
use App\Models\Requisition;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\LieuSurvenance;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class Declarationnaissance extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_declaration_naissance";
    public $incrementing = false;
    protected $primaryKey = 'code_declaration_naissance';


    public function declarant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_declarant', 'code_personne');
    }

    public function pere(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_pere', 'code_personne');
    }

    public function mere(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_mere', 'code_personne');
    }

    public function enfant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_enfant', 'code_personne');
    }

    public function filiation(): BelongsTo
    {
        return $this->belongsTo(Filiation::class, 'code_filiation', 'code_filiation');
    }

    public function sitMatParent(): BelongsTo
    {
        return $this->belongsTo(SituationMatrimoniale::class, 'code_situation_mat', 'code_situation_matrimoniale');
    }

    public function lieuSurvenance(): BelongsTo
    {
        return $this->belongsTo(LieuSurvenance::class, 'code_lieu_survenance', 'code_lieu_survenance');
    }

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'code_user_institution', 'cui');
    }

    public function institutionUserDeclaration(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }


    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementNaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }

    /**
     * Get the acte associated with the Declarationnaissance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function acte(): HasOne
    {
        return $this->hasOne(ActeNaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }


    //institution du jugement

    public function institutionJugement(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_tribunal_jugement', 'code_institution');
    }

    public function adoptant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_adoptant', 'code_personne');
    }

    public function tribunaljugement(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_tribunal_jugement', 'code_institution');
    }


    //pour retracer les jugements venant du tribunal
    public function jugement(): HasOne
    {
        return $this->hasOne(Jugement::class, 'code_jugement', 'code_jugement');
    }

    //institution appartement le document
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution','code_institution');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'code_requisition','code_requisition');
    }


}
