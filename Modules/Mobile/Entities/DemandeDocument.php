<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\LieuSurvenance;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DemandeDocument extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_demande_document";
    protected $primaryKey = "code_demande_document";
    public $incrementing = false;


    public function typeActe(): BelongsTo
    {
        return $this->belongsTo(TypeActe::class, 'code_type_acte', 'code_type_acte');
    }

    public function typeDocumentDemande(): BelongsTo
    {
        return $this->belongsTo(TypeDocumentDemande::class, 'code_type_document_demande', 'code_type_document_demande');
    }

    public function lieuSurvenance(): BelongsTo
    {
        return $this->belongsTo(LieuSurvenance::class, 'code_lieu_survenance', 'code_lieu_survenance');
    }

    public function filiation(): BelongsTo
    {
        return $this->belongsTo(Filiation::class, 'code_filiation', 'code_filiation');
    }


    public function paiementDocument(): HasOne
    {
        return $this->hasOne(PaiementDocument::class, 'code_demande_document', 'code_demande_document');
    }


    public function paiements(): HasMany
    {
        return $this->hasMany(PaiementDetail::class, 'code_demande_document', 'code_demande_document');
    }




}
