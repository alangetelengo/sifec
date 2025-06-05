<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Quartier;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_district";
    protected $primaryKey = "code_district";
    public $incrementing = false;

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'code_departement', 'code_departement');
    }

    public function communauteUrbaines(): HasMany
    {
        return $this->hasMany(CommunauteUrbaine::class, 'code_district', 'code_district');
    }

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'code_district', 'code_district');
    }

    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'code_district', 'code_district');
    }

    public function villages(): HasMany
    {
        return $this->hasMany(Village::class, 'code_district', 'code_district');
    }

    //????
    public function quartiersVillages(){
        $quartiers =  $this->quartiers();
        $villages = $this->villages();

        return $quartiers->merge($villages);
    }

}
