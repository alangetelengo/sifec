<?php

namespace Modules\Mobile\Entities;

use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Deces\Entities\ActeDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\LieuSurvenance;

class DemandeDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 't_demande_document';

    protected $primaryKey = 'code_demande_document';

    public $incrementing = false;

    protected $casts = [
        'date_demande' => 'datetime',
        'date_traitement' => 'datetime',
        'date_livraison' => 'datetime',
        'date_signature' => 'datetime',
        'document_valide_de' => 'datetime',
        'document_valide_jusquau' => 'datetime',
        'otp_expire_at' => 'datetime',
        'prix' => 'decimal:2',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

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

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'cui', 'cui');
    }

    public function signataire(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'code_signataire', 'cui');
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

    // ==========================================
    // MÉTHODES UTILITAIRES - ORIGINE
    // ==========================================

    public function estPortail(): bool
    {
        return $this->origine_demande === 'portail';
    }

    public function estSurSite(): bool
    {
        return $this->origine_demande === 'sur_site';
    }

    // ==========================================
    // MÉTHODES UTILITAIRES - STATUT
    // ==========================================

    public function estEnAttentePaiement(): bool
    {
        return $this->statut === 'En attente de paiement';
    }

    public function estEnTraitement(): bool
    {
        return $this->statut === 'En traitement';
    }

    public function estEnAttenteSignature(): bool
    {
        return $this->statut === 'En attente de signature';
    }

    public function estTraitee(): bool
    {
        return $this->statut === 'Traitée';
    }

    public function estLivree(): bool
    {
        return $this->statut === 'Livrée';
    }

    public function estRejetee(): bool
    {
        return $this->statut === 'Rejetée';
    }

    public function estExpiree(): bool
    {
        return $this->statut === 'Expirée';
    }

    /**
     * Document encore dans sa période de validité (dates renseignées et aujourd'hui dans l'intervalle).
     */
    public function documentEstEncoreValide(): bool
    {
        if (! $this->document_valide_de || ! $this->document_valide_jusquau) {
            return false;
        }

        $now = now();

        return $now->greaterThanOrEqualTo($this->document_valide_de)
            && $now->lessThanOrEqualTo($this->document_valide_jusquau);
    }

    /**
     * Traitée ou livrée mais la date de fin de validité est dépassée (en attente du job / tâche planifiée pour passer en Expirée).
     */
    public function documentPerimeSansChangementStatut(): bool
    {
        if (! $this->document_valide_jusquau || $this->estExpiree()) {
            return false;
        }

        if (! $this->estTraitee() && ! $this->estLivree()) {
            return false;
        }

        return now()->greaterThan($this->document_valide_jusquau);
    }

    // ==========================================
    // MÉTHODES UTILITAIRES - WORKFLOW
    // ==========================================

    public function peutEtreSigne(): bool
    {
        return $this->estEnAttenteSignature()
            && ! empty($this->otp_code)
            && $this->otp_expire_at
            && $this->otp_expire_at > now();
    }

    public function otpEstValide(): bool
    {
        return ! empty($this->otp_code)
            && $this->otp_expire_at
            && $this->otp_expire_at > now();
    }

    public function otpEstExpire(): bool
    {
        return ! empty($this->otp_code)
            && $this->otp_expire_at
            && $this->otp_expire_at <= now();
    }

    public function estSignee(): bool
    {
        return ! empty($this->code_signataire) && ! empty($this->date_signature);
    }

    public function peutEtreGeneree(): bool
    {
        // Paiement temporairement désactivé : accepter aussi « En attente de paiement »
        return ($this->estEnTraitement() || $this->estEnAttentePaiement())
            && ! empty($this->numero_acte);
    }

    // ==========================================
    // MÉTHODES - RÉCUPÉRATION DE L'ACTE
    // ==========================================

    /**
     * Récupère l'acte concerné par cette demande
     * Retourne ActeNaissance, ActeMariage ou ActeDeces selon le type
     */
    public function getActeConcerne()
    {
        if (empty($this->numero_acte) || empty($this->code_type_acte)) {
            return null;
        }

        return match ($this->code_type_acte) {
            'TAC_0001' => ActeNaissance::findByIdentifier($this->numero_acte),
            'TAC_0002' => ActeMariage::where('code_acte_mariage', $this->numero_acte)->first(),
            'TAC_0004' => ActeDeces::where('code_acte_deces', $this->numero_acte)->first(),
            default => null,
        };
    }

    /**
     * Vérifie si l'acte existe et est valide
     */
    public function acteExiste(): bool
    {
        return $this->getActeConcerne() !== null;
    }

    // ==========================================
    // MÉTHODES - HELPERS
    // ==========================================

    public function estCopie(): bool
    {
        return $this->code_type_document_demande === 'TDD_0001';
    }

    public function estExtrait(): bool
    {
        return $this->code_type_document_demande === 'TDD_0002';
    }

    public function getLibelleTypeDocument(): string
    {
        return $this->typeDocumentDemande ? $this->typeDocumentDemande->lib_type_document_demande : '';
    }

    public function getLibelleTypeActe(): string
    {
        return $this->typeActe ? $this->typeActe->lib_type_acte : '';
    }

    public function getNomCompletDemandeur(): string
    {
        $nom = $this->nom_demandeur ?? '';
        $prenom = $this->prenom_demander ?? '';

        return trim($nom.' '.$prenom);
    }

    /**
     * Retourne le lib_technique de la permission de signature selon le type d'acte et de document
     */
    public function getPermissionSignature(): string
    {
        // Déterminer le type de document (copie ou extrait)
        $typeDoc = $this->estCopie() ? 'copie' : 'extrait';

        // Retourner la permission selon le type d'acte
        return match ($this->code_type_acte) {
            'TAC_0001' => "module.acteNaissance.signature.{$typeDoc}", // Naissance
            'TAC_0002' => "module.acteMariage.signature.{$typeDoc}",   // Mariage
            'TAC_0003' => "module.acteDivorce.signature.{$typeDoc}",   // Divorce
            'TAC_0004' => "module.acteDeces.signature.{$typeDoc}",     // Décès
            default => 'module.demande_document.signature', // Fallback (ne devrait pas arriver)
        };
    }

    /**
     * Retourne le code de fonctionnalité FNC correspondant à la permission de signature
     */
    public function getCodeFonctionnaliteSignature(): string
    {
        // Déterminer si c'est une copie ou un extrait
        $estCopie = $this->estCopie();

        // Retourner le code FNC selon le type d'acte et de document
        return match ($this->code_type_acte) {
            'TAC_0001' => $estCopie ? 'FNC_0061' : 'FNC_0058', // Naissance
            'TAC_0002' => $estCopie ? 'FNC_0063' : 'FNC_0062', // Mariage
            'TAC_0003' => $estCopie ? 'FNC_0067' : 'FNC_0066', // Divorce
            'TAC_0004' => $estCopie ? 'FNC_0065' : 'FNC_0064', // Décès
            default => 'FNC_0058', // Fallback
        };
    }
}
