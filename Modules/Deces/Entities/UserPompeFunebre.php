<?php

namespace Modules\Deces\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Localite;
use Modules\Deces\Entities\DeclarationDeces;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\Institution;

class UserPompeFunebre extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = "t_ins_user_localite";


    public function pompeFunebre(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    public function localite(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite', 'code_localite');
    }




}
