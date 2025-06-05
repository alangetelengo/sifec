<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class OptionMariage extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = "tr_option_mariage";
    protected $primaryKey = "code_option_mariage";
    public $incrementing = false;


}
