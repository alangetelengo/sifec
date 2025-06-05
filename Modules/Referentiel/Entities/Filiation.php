<?php

namespace Modules\Referentiel\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Mobile\Entities\DemandeDocument;

class Filiation extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "tr_filiation";
    protected $primaryKey = "code_filiation";
    public $incrementing = false;

    
    public function demandeDocuments(): HasMany
    {
        return $this->hasMany(DemandeDocument::class, 'code_filiation', 'code_filiation');
    }
}
