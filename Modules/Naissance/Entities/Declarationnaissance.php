<?php

namespace Modules\Naissance\Entities;

use App\Models\Jugement;
use App\Models\Requisition;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Model;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Filiation;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\LieuSurvenance;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class Declarationnaissance extends Model
{
//	code_declaration_naissance 	nombre_enfant 	date_heure_declaration 	type_declarant 	personne_morale 	personne_declaree 	cec_naissance 	pays_naissance_enfant 	code_declarant 	code_adoptant 	code_enfant 	code_pere 	code_mere 	code_filiation 	code_user_institution 	code_institution 	code_lieu_survenance 	code_situation_mat 	date_heure_naissance 	top_requisition 	numero_req 	numero_certificat 	type_declaration 	formation_sanitaire_naissance 	code_jugement 	code_requisition 	num_jugement 	date_jugement 	code_tribunal_jugement 	numero_ancien_acte
    use HasFactory;

    /** Types stockés en base (enum t_declaration_naissance.type_declaration) */
    public const TYPES_AUTORISES = [
        'CERTIFICAT DE NAISSANCE',
        'DECLARATION DE NAISSANCE',
        'CERTIFICAT DE NON INSCRIPTION',
        "CERTIFICAT DE DESTRUCTION DE L'ACTE",
        'FICHE DE TRANSCRIPTION',
        'CERTIFICAT DE TRANSCRIPTION',
    ];

    protected $guarded = [];
    protected $table = "t_declaration_naissance";
    public $incrementing = false;
    protected $primaryKey = 'code_declaration_naissance';


    public function declarant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_declarant', 'code_personne');
    }

    public function pere(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_pere', 'code_personne');
    }

    public function mere(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_mere', 'code_personne');
    }

    public function enfant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_enfant', 'code_personne');
    }

    public function filiation(): BelongsTo
    {
        return $this->belongsTo(Filiation::class, 'code_filiation', 'code_filiation');
    }

    public function sitMatParent(): BelongsTo
    {
        return $this->belongsTo(SituationMatrimoniale::class, 'code_situation_mat', 'code_situation_matrimoniale');
    }

    public function lieuSurvenance(): BelongsTo
    {
        return $this->belongsTo(LieuSurvenance::class, 'code_lieu_survenance', 'code_lieu_survenance');
    }

    public function institutionUser(): BelongsTo
    {
        return $this->belongsTo(InstitutionUser::class, 'code_user_institution', 'cui');
    }

    public function institutionUserDeclaration(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution', 'code_institution');
    }


    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementNaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }

    /**
     * Get the acte associated with the Declarationnaissance
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function acte(): HasOne
    {
        return $this->hasOne(ActeNaissance::class, 'code_declaration_naissance', 'code_declaration_naissance');
    }


    public function adoptant(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'code_adoptant', 'code_personne');
    }

    /**
     * Permet de savoir l'institution dont la déclaration a été envoyée
     */
    public function institutionDestinataire(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution_destinataire', 'code_institution');
    }
    //pour retracer le jugement venant du tribunal
    public function jugement(): HasOne
    {
        return $this->hasOne(Jugement::class, 'code_declaration', 'code_declaration_naissance');
    }

    //pour retracer la réquisition venant du tribunal
    public function requisition(): HasOne
    {
        return $this->hasOne(Requisition::class, 'code_declaration', 'code_declaration_naissance');
    }

    //institution appartement le document
    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class, 'code_institution','code_institution');
    }

    /**
     * @return array{type: string, origine: ?string}
     */
    public static function normaliserTypeEtOrigine(?string $soumis, ?string $origineExplicite = null): array
    {
        $soumis = ($soumis !== null && $soumis !== '') ? trim($soumis) : 'DECLARATION DE NAISSANCE';

        $legacy = [
            'DECLARATION TARDIVE DE NAISSANCE' => ['DECLARATION DE NAISSANCE', 'DECLARATION TARDIVE DE NAISSANCE'],
            'DECLARATION DE PATERNITE' => ['CERTIFICAT DE TRANSCRIPTION', 'DECLARATION DE PATERNITE'],
            "FICHE DE TRANSCRIPTION DE L'ACTE" => ['FICHE DE TRANSCRIPTION', "FICHE DE TRANSCRIPTION DE L'ACTE"],
            'JUGEMENT SUPPLETIF' => ['DECLARATION DE NAISSANCE', 'JUGEMENT SUPPLETIF'],
            "JUGEMENT D'HOMOLOGATION" => ['DECLARATION DE NAISSANCE', "JUGEMENT D'HOMOLOGATION"],
            "JUGEMENT D'ADOPTION" => ['DECLARATION DE NAISSANCE', "JUGEMENT D'ADOPTION"],
            "JUGEMENT D'ANNULATION D'ACTE" => ['DECLARATION DE NAISSANCE', "JUGEMENT D'ANNULATION D'ACTE"],
        ];

        if (isset($legacy[$soumis])) {
            return ['type' => $legacy[$soumis][0], 'origine' => $legacy[$soumis][1]];
        }

        if (in_array($soumis, self::TYPES_AUTORISES, true)) {
            return ['type' => $soumis, 'origine' => $origineExplicite];
        }

        return ['type' => 'DECLARATION DE NAISSANCE', 'origine' => null];
    }

    public function libelleAffichageType(): string
    {
        return $this->type_declaration_origine ?: (string) $this->type_declaration;
    }

    /**
     * Déclaration « simple » au sens pièces / QR : type stocké DECLARATION sans origine métier.
     */
    public function estDeclarationSimpleSansOrigine(): bool
    {
        return ($this->type_declaration ?? '') === 'DECLARATION DE NAISSANCE'
            && (($this->type_declaration_origine ?? '') === '' || $this->type_declaration_origine === null);
    }

    public function estJugementHomologation(): bool
    {
        return ($this->type_declaration_origine ?? '') === "JUGEMENT D'HOMOLOGATION";
    }

    /**
     * Clé métier pour le mapping des mouvements (équivalent à l’ancien type_declaration formulaire).
     */
    public function typePourMappingMouvement(): string
    {
        $originesMouvement = [
            'DECLARATION TARDIVE DE NAISSANCE',
            'DECLARATION DE PATERNITE',
            'JUGEMENT SUPPLETIF',
            "JUGEMENT D'HOMOLOGATION",
            "JUGEMENT D'ADOPTION",
            "JUGEMENT D'ANNULATION D'ACTE",
        ];
        if (!empty($this->type_declaration_origine) && in_array($this->type_declaration_origine, $originesMouvement, true)) {
            return $this->type_declaration_origine;
        }

        return $this->type_declaration ?: 'DECLARATION DE NAISSANCE';
    }

}
