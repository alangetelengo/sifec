<?php
namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Illuminate\Database\Eloquent\SoftDeletes;

class Localite extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;
    use SoftDeletes;



    protected $guarded = [];
    protected $table = 'tr_localite';
    protected $primaryKey = "code_localite";
    public $incrementing = false;

    public function typelocalite(): BelongsTo
    {
        return $this->belongsTo(TypeLocalite::class, 'code_type_localite', 'code_type_localite');
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_localite', 'code_localite');
    }


    public function localiteParent(): BelongsTo
    {
        return $this->belongsTo(Localite::class, 'code_localite_parent', 'code_localite');
    }

    public function localitesEnfants(): HasMany
    {
        return $this->hasMany(Localite::class, 'code_localite_parent', 'code_localite');
    }

    public function personnes(): HasMany
    {
        return $this->hasMany(Personne::class, 'code_localite', 'code_localite');
    }

    public function getParentKeyName()
    {
        return 'code_localite_parent';
    }

    public function descendants(){
        return $this->descendantsAndSelf()->depthFirst()->get();
    }

    //recuperer les declarations de deces de chaque localite
    public function declarationDeces()
    {
        return $this->institutions->map->institutionsUsers->flatten()->map->declarationDeces->flatten();
    }



}
