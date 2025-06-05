<?php

namespace Modules\Mariage\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Mariage\Entities\DeclarationMariage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = 't_signature_mariage';
    protected $primaryKey = 'code_signature_mariage';
    public $incrementing = false;


    public function mariage(): BelongsTo
    {
        return $this->belongsTo(DeclarationMariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }
}
