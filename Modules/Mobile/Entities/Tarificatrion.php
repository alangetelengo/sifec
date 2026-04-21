<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\Institution;

class Tarificatrion extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "t_tarification";
    protected $primaryKey = "code_tarification";
    public $incrementing = false;


    public function typeActe(): BelongsTo
    {
        return $this->belongsTo(TypeActe::class, 'code_type_acte', 'code_type_acte');
    }

    public function typeDocumentDemande(): BelongsTo
    {
        return $this->belongsTo(TypeDocumentDemande::class, 'code_type_document_demande', 'code_type_document_demande');
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }
}
