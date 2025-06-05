<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeExtrait extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'tr_type_extrait';
    protected $primaryKey = "code_type_extrait";
    public $incrementing = false;



}
