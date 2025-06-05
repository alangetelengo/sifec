<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departement extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table="tr_departement";
    protected $primaryKey="code_departement";
    public $incrementing = false;


    public function communes(): HasMany
    {
        return $this->hasMany(Commune::class, 'code_departement', 'code_departement');
    }
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'code_departement', 'code_departement');
    }

    public function institutions(){
        $communes = $this->communes;
        $districts = $this->districts;

        $dist_inst = $districts->map->institutions->flatten();
        $com_inst = $communes->map->institutions->flatten();
        $arr_inst = $communes->map->arrondissements->flatten()->map->institutions->flatten();
        $cur_inst = $districts->map->communauteUrbaines->flatten()->map->institutions->flatten();

        $institutions = $dist_inst->merge($com_inst)->merge($arr_inst)->merge($cur_inst);
        return $institutions;
    }


}
