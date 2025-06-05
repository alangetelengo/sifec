<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MobileMoneyTransactionDetail extends Model
{
    use HasFactory;

    /**
     * Get the transaction that owns the MobileMoneyTransactionDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paiementDocument(): BelongsTo
    {
        return $this->belongsTo(PaiementDocument::class, 'code_paiement_document', 'code_paiement_document');
    }
}
