<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeActe extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_type_acte";
    protected $primaryKey = "code_type_acte";
    public $incrementing = false;


    public function demandes(): HasMany
    {
        return $this->hasMany(DemandeDocument::class, 'code_type_acte', 'code_type_acte');
    }

    public function tarifications(): HasMany
    {
        return $this->hasMany(Tarificatrion::class, 'code_type_acte', 'code_type_acte');
    }

   
}
