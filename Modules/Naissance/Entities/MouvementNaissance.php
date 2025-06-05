<?php

namespace Modules\Naissance\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MouvementNaissance extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_mouvement_naissance";
    protected $primaryKey = "code_mouvement_naissance";
    public $incrementing = false;


    public function declarationNaissance(): BelongsTo
    {
        return $this->belongsTo(Declarationnaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }
    public function userInstitution(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }
}
