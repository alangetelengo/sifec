<?php

namespace Modules\Mariage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LivretFamille extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 't_livret_famille';
    protected $primaryKey = 'code_livret_famille';
    public $incrementing = false;

    
    public function detailLivrets(): HasMany
    {
        return $this->hasMany(DetailLivretFamille::class, 'code_livret_famille', 'code_livret_famille');
    }


    public function declarationMariage(): BelongsTo
    {
        return $this->belongsTo(DeclarationMariage::class, 'code_declaration_mariage', 'code_declaration_mariage');
    }
}
