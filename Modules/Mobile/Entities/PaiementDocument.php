<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaiementDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_paiement_document";
    protected $primaryKey = "code_paiement_document";
    public $incrementing = false;

   
    public function demandeDocument(): BelongsTo
    {
        return $this->belongsTo(DemandeDocument::class, 'code_demande_document', 'code_demande_document');
    }

}
