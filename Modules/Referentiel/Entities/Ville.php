<?php
namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ville extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tr_localite';
    protected $primaryKey = "code_localite";
    public $incrementing = false;


    public function typelocalite(): BelongsTo
    {
        return $this->belongsTo(TypeLocalite::class, 'code_type_localite', 'code_type_localite');
    }

    public function institutions(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_localite', 'code_localite');
    }

    public function localiteParent(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite_parent', 'code_localite');
    }

    public function personnes(): HasMany
    {
        return $this->hasMany(Personne::class, 'code_localite', 'code_localite');
    }
}
