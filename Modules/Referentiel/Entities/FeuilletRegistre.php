<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeuilletRegistre extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = "t_feuillet_registre";
    protected $primaryKey = "code_feuillet_registre";
    public $incrementing = false;


}
