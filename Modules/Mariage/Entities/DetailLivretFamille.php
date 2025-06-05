<?php

namespace Modules\Mariage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\TypeExtrait;

class DetailLivretFamille extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 't_detail_livret';
    protected $primaryKey = 'code_detail_livret';
    public $incrementing = false;


    public function livretFamille(): BelongsTo
    {
        return $this->belongsTo(LivretFamille::class, 'code_livret_famille', 'code_livret_famille');
    }


    public function typeExtrait(): BelongsTo
    {
        return $this->belongsTo(TypeExtrait::class, 'code_type_extrait', 'code_type_extrait');
    }


    public function enfant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_enfant', 'code_personne');
    }


}
