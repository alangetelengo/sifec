<?php

namespace App\Models;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Naissance\Entities\Declarationnaissance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function declarationNaissance(): HasOne
    {
        return $this->hasOne(Declarationnaissance::class, 'code_jugement', 'code_jugement');
    }

}
