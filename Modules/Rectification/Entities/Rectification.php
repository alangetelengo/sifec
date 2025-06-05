<?php

namespace Modules\Rectification\Entities;

use App\Models\Requisition;
use App\Models\InstitutionUser;
use Modules\Mobile\Entities\TypeActe;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Filiation;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Naissance\Entities\ActeNaissance;
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

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }
}
