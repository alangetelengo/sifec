<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Regime extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_regime";
    protected $primaryKey = "code_regime";
    public $incrementing = false;
}
