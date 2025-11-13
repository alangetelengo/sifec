<?php

namespace Modules\Rectification\Entities;

use App\Models\Requisition;
use App\Models\InstitutionUser;
use Modules\Mobile\Entities\TypeActe;
use Illuminate\Database\Eloquent\Model;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Referentiel\Entities\Filiation;
use Modules\Deces\Entities\ActeDeces;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Mariage\Entities\DeclarationMariage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Rectification\Entities\DetailRectification;

class Rectification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = "t_rectification";
    protected $primaryKey = "code_rectification";
    public $incrementing = false;


    public function filiation(): BelongsTo
    {
        return $this->belongsTo(Filiation::class, 'code_filiation', 'code_filiation');
    }


    public function detailsRectification(): HasMany
    {
        return $this->hasMany(DetailRectification::class, 'code_rectification', 'code_rectification');
    }


    public function typeActe(): BelongsTo
    {
        return $this->belongsTo(TypeActe::class, 'code_type_acte', 'code_type_acte');
    }

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'code_requisition', 'code_requisition');
    }


    public function acteNaissance(): BelongsTo
    {
        return $this->belongsTo(ActeNaissance::class, 'numero_acte', 'niupp');
    }

    public function acteMariage(): BelongsTo
    {
        return $this->belongsTo(ActeMariage::class, 'numero_acte', 'code_acte_mariage');
    }

    public function acteDeces(): BelongsTo
    {
        return $this->belongsTo(ActeDeces::class, 'numero_acte', 'code_acte_deces');
    }

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    /**
     * Get the institution that owns the Rectification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }

    public function mouvementRectification(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\Modules\Rectification\Entities\MouvementRectification::class, 'code_rectification', 'code_rectification');
    }
}
