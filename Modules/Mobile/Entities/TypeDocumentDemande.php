<?php

namespace Modules\Mobile\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeDocumentDemande extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = "tr_type_document_demande";
    protected $primaryKey = "code_type_document_demande";
    public $incrementing = false;


    public function demandeDocuments(): HasMany
    {
        return $this->hasMany(DemandeDocument::class, 'code_type_document_demande', 'code_type_document_demande');
    }

    public function tarifications(): HasMany
    {
        return $this->hasMany(Tarificatrion::class, 'code_type_document_demande', 'code_type_document_demande');
    }
}
