<?php

namespace Modules\Mariage\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Mariage\Entities\Declarationmariage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MouvementMariage extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = "t_mouvement_mariage";
    protected $primaryKey = "code_mouvement_mariage";
    public $incrementing = false;

    public function declarationMariage(): BelongsTo
    {
        return $this->belongsTo(Declarationmariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }

    public function userInstitution(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }


}
