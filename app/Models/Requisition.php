<?php

namespace App\Models;

use App\Models\InstitutionUser;
use App\Models\TypeRequisition;
use Illuminate\Database\Eloquent\Model;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\Institution;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Rectification\Entities\Rectification;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisition extends Model
{
    use HasFactory;


    protected $table = "t_requisition";
    protected $guarded = [];
    protected $primaryKey = "code_requisition";

    public $incrementing = false;


    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }


    /**
     * Relation correcte : une Réquisition appartient à une déclaration de naissance
     */
    public function declarationNaissance(): BelongsTo
    {
        return $this->belongsTo(Declarationnaissance::class, 'code_declaration', 'code_declaration_naissance');
    }
    public function declarationDeces(): BelongsTo
    {
        return $this->belongsTo(DeclarationDeces::class, 'code_declaration', 'code_declaration_deces');
    }
    public function declarationMariage(): BelongsTo
    {
        return $this->belongsTo(DeclarationMariage::class, 'code_declaration', 'code_declaration_mariage');
    }

    /**
     * centre d'état civil à envoyer le document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }

    //récuperer la rectification liée à cette requisition
    public function rectification(): HasOne
    {
        return $this->hasOne(Rectification::class, 'code_requisition', 'code_requisition');
    }

    /**
     * Get the typeRequisition that owns the Requisition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeRequisition(): BelongsTo
    {
        return $this->belongsTo(TypeRequisition::class, 'code_type_requisition', 'code_type_requisition');
    }
}
