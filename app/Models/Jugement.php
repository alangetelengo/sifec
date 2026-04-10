<?php

namespace App\Models;

use App\Models\TypeJugement;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jugement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "t_jugement";
    protected $guarded = [];
    protected $primaryKey = "code_jugement";

    public $incrementing = false;


    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    /**
     * Tribunal / juridiction ayant émis le jugement (tr_institution via t_jugement.code_institution).
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }

    /**
     * Déclaration de naissance liée (t_jugement.code_declaration → t_declaration_naissance).
     */
    public function declarationNaissance(): BelongsTo
    {
        return $this->belongsTo(Declarationnaissance::class, 'code_declaration', 'code_declaration_naissance');
    }

    /**
     * Déclaration référençant ce jugement par t_declaration_naissance.code_jugement.
     */
    public function declarationParCodeJugement(): HasOne
    {
        return $this->hasOne(Declarationnaissance::class, 'code_jugement', 'code_jugement');
    }

    /**
     * Get the typeJugement that owns the Jugement
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeJugement(): BelongsTo
    {
        return $this->belongsTo(TypeJugement::class, 'code_type_jugement', 'code_type_jugement');
    }
}
