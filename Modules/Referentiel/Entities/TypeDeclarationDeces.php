<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TypeDeclarationDeces extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_type_declaration_deces";
    protected $primaryKey = 'code_type_declaration_deces';
    public $incrementing = false;
}
