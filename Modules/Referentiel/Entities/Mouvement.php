<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mouvement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table="tr_mouvement";
    protected $primaryKey="code_mouvement";
    public $incrementing = false;



}
