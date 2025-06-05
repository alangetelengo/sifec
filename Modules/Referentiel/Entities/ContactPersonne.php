<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactPersonne extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $table = "t_contact_personne";

 
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_personne', 'code_personne');
    }
}
