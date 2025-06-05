<?php

namespace Modules\Deces\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Deces\Entities\DeclarationDeces;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MouvementDeces extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table="t_mouvement_deces";
    protected $primarykey="code_mouvement_deces";
    public $incrementing = false;


    public function declarationDeces(): BelongsTo
    {
        return $this->belongsTo(DeclarationDeces::class, 'code_declaration_deces', 'code_declaration_deces');
    }
    public function userInstitution(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }
}
