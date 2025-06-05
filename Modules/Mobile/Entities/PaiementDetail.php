<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaiementDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "paiement_details";
    protected $primaryKey = "id";


    public function demandeDocument(): BelongsTo
    {
        return $this->belongsTo(DemandeDocument::class, 'code_demande_document', 'code_demande_document');
    }



}
