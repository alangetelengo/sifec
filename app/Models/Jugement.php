<?php

namespace App\Models;

use App\Models\TypeJugement;
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

    /**
     * Relation correcte : un Jugement appartient à une déclaration de naissance
     */
    public function declarationNaissance(): BelongsTo
    {
        return $this->belongsTo(Declarationnaissance::class, 'code_jugement', 'code_jugement');
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
