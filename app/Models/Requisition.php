<?php

namespace App\Models;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Institution;
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


    public function declarationNaissance(): HasOne
    {
        return $this->hasOne(Declarationnaissance::class, 'code_requisition', 'code_requisition');
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
}
