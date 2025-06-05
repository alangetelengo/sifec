<?php

namespace App\Models;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaiementDocument extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table="paiement_documents";
    protected $primaryKey="code_paiement_document";
    public $incrementing = false;

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }



}
