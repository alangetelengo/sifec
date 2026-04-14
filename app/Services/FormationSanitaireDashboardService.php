<?php

namespace App\Services;

use App\Helpers\CecBrazzavilleHelper;
use App\Models\InstitutionUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\Deces\Entities\ActeDeces;
use Modules\Referentiel\Entities\Institution;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

/**
 * Tableau de bord formation sanitaire : certificats / déclarations, flux CEC, actes civils.
 */
class FormationSanitaireDashboardService
{
    private const TYPE_CERTIFICAT_NAISSANCE = 'CERTIFICAT DE NAISSANCE';

    private const TYPE_DECLARATION_NAISSANCE = 'DECLARATION DE NAISSANCE';

    /** Pièces tribunal / transcription (acte générable sans « déclaration de naissance » classique). */
    private const TYPES_TRIBUNAL_NAISSANCE = [
        'CERTIFICAT DE NON INSCRIPTION',
        "CERTIFICAT DE DESTRUCTION DE L'ACTE",
        'FICHE DE TRANSCRIPTION',
        'CERTIFICAT DE TRANSCRIPTION',
    ];

    /** Dossiers naissance pris en compte au CEC : formation + flux tribunal. */
    private const TYPES_NAISSANCE_POUR_CEC = [
        self::TYPE_CERTIFICAT_NAISSANCE,
        self::TYPE_DECLARATION_NAISSANCE,
        'CERTIFICAT DE NON INSCRIPTION',
        "CERTIFICAT DE DESTRUCTION DE L'ACTE",
        'FICHE DE TRANSCRIPTION',
        'CERTIFICAT DE TRANSCRIPTION',
    ];

    private const TYPE_CERTIFICAT_DECES = 'CERTIFICAT DE CONSTATATION DE DECES';

    private const TYPE_DECLARATION_DECES = 'DECLARATION DE DECES';

    private const TYPES_DECES = [self::TYPE_CERTIFICAT_DECES, self::TYPE_DECLARATION_DECES];

    private const TYPE_FORMULAIRE_DECLARATION_MARIAGE = 'DECLARATION DE MARIAGE';

    private const TYPE_FORMULAIRE_DISPENSE = 'DISPENSE';

    /** Formulaires mariage (pas de logique « certificat / déclaration » comme pour la naissance). */
    private const TYPES_MARIAGE_FORMULAIRES = [self::TYPE_FORMULAIRE_DECLARATION_MARIAGE, self::TYPE_FORMULAIRE_DISPENSE];

    /**
     * @return array{
     *   institution: \Modules\Referentiel\Entities\Institution,
     *   cec: \Modules\Referentiel\Entities\Institution|null,
     *   affectation: InstitutionUser,
     *   kpi_naissance: array{enregistres: int, certificats_enregistres: int, declarations_produites: int, dossiers_tribunal: int, envoyes: int, valides: int, en_attente: int, actes_produits: int},
     *   kpi_deces: array,
     *   recent_naissances: Collection,
     *   recent_deces: Collection
     * }
     */
    public function buildForAffectation(InstitutionUser $affectation): array
    {
        $institution = $affectation->institution;
        if ($institution === null) {
            throw new \InvalidArgumentException('Affectation sans institution.');
        }

        return $this->buildForInstitutionCodes(
            [$institution->code_institution],
            $affectation,
            $institution->institutionParent
        );
    }

    /**
     * Tableau de bord agent formation sanitaire : certificats de naissance médicaux + déclarations
     * de décès issues du certificat saisi à l’établissement (type « DECLARATION DE DECES »).
     *
     * Le certificat de constatation de décès relève exclusivement d’un centre d’hygiène
     * (voir {@see buildPourCentreHygiene}).
     */
    public function buildPourFormationSanitaire(InstitutionUser $affectation): array
    {
        $institution = $affectation->institution;
        if ($institution === null) {
            throw new \InvalidArgumentException('Affectation sans institution.');
        }

        $codes = [$institution->code_institution];

        return [
            'institution' => $institution,
            'cec' => $institution->institutionParent,
            'affectation' => $affectation,
            'kpi_naissance' => $this->kpiNaissanceCertificatsFormation($codes),
            'recent_naissances' => $this->recentNaissanceCertificatsFormation($codes),
            'kpi_deces' => $this->kpiDecesDeclarationFormation($codes),
            'recent_deces' => $this->recentDecesDeclarationFormation($codes),
        ];
    }

    /**
     * Tableau de bord centre d’hygiène : uniquement les constatations (CERTIFICAT DE CONSTATATION DE DECES).
     *
     * @return array{
     *   institution: \Modules\Referentiel\Entities\Institution,
     *   cec: \Modules\Referentiel\Entities\Institution|null,
     *   affectation: InstitutionUser,
     *   kpi_constatation: array,
     *   recent_constatations: Collection,
     *   routingDecesParcoursBrazzaville: bool,
     *   routingDecesMessage: string
     * }
     */
    public function buildPourCentreHygiene(InstitutionUser $affectation): array
    {
        $institution = $affectation->institution;
        if ($institution === null) {
            throw new \InvalidArgumentException('Affectation sans institution.');
        }

        $codes = [$institution->code_institution];
        $institution->loadMissing(['lieu.localiteParent', 'institutionParent']);

        $estBrazzaville = CecBrazzavilleHelper::localiteRattacheeCommuneBrazzaville($institution->lieu);

        return [
            'institution' => $institution,
            'cec' => $institution->institutionParent,
            'affectation' => $affectation,
            'kpi_constatation' => $this->kpiDecesConstatationCentreHygiene($codes),
            'recent_constatations' => $this->recentDecesConstatationCentreHygiene($codes),
            'routingDecesParcoursBrazzaville' => $estBrazzaville,
            'routingDecesMessage' => $estBrazzaville
                ? 'Selon la localité de votre centre, les constatations sont adressées au réseau des pompes funèbres (parcours Brazzaville).'
                : 'Selon la localité de votre centre, les constatations sont transmises au centre d’état civil de rattachement (parcours hors Brazzaville).',
        ];
    }

    /**
     * Même périmètre KPI / dossiers récents que la formation sanitaire, pour une ou plusieurs institutions (ex. mairie centrale + arrondissements).
     *
     * @param  array<int, string>  $codes
     * @return array{
     *   institution: \Modules\Referentiel\Entities\Institution,
     *   cec: \Modules\Referentiel\Entities\Institution|null,
     *   affectation: InstitutionUser,
     *   kpi_naissance: array{enregistres: int, certificats_enregistres: int, declarations_produites: int, dossiers_tribunal: int, envoyes: int, valides: int, en_attente: int, actes_produits: int},
     *   kpi_deces: array,
     *   recent_naissances: Collection,
     *   recent_deces: Collection
     * }
     */
    public function buildForInstitutionCodes(array $codes, InstitutionUser $affectation, ?Institution $cec = null): array
    {
        $institution = $affectation->institution;
        if ($institution === null) {
            throw new \InvalidArgumentException('Affectation sans institution.');
        }

        $codes = array_values(array_unique(array_filter($codes)));
        if ($codes === []) {
            throw new \InvalidArgumentException('Aucun code institution.');
        }

        return [
            'institution' => $institution,
            'cec' => $cec,
            'affectation' => $affectation,
            'kpi_naissance' => $this->kpiNaissance($codes),
            'kpi_deces' => $this->kpiDeces($codes),
            'kpi_mariage' => $this->kpiMariage($codes),
            'recent_naissances' => $this->recentNaissance($codes),
            'recent_deces' => $this->recentDeces($codes),
            'recent_mariages' => $this->recentMariage($codes),
        ];
    }

    /**
     * @return array{
     *   enregistres: int,
     *   certificats_enregistres: int,
     *   declarations_produites: int,
     *   envoyes: int,
     *   valides: int,
     *   en_attente: int,
     *   actes_produits: int,
     *   dossiers_tribunal: int
     * }
     */
    /**
     * @param  array<int, string>  $codes
     */
    private function kpiNaissance(array $codes): array
    {
        $tous = Declarationnaissance::query();
        $this->perimetreDeclarationVersCodes($tous, $codes);
        $tous->whereIn('type_declaration', self::TYPES_NAISSANCE_POUR_CEC);

        $certificats = (clone $tous)->where('type_declaration', self::TYPE_CERTIFICAT_NAISSANCE)->count();
        $declarations = (clone $tous)->where('type_declaration', self::TYPE_DECLARATION_NAISSANCE)->count();
        $dossiersTribunal = (clone $tous)->whereIn('type_declaration', self::TYPES_TRIBUNAL_NAISSANCE)->count();

        $base = Declarationnaissance::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->whereIn('type_declaration', self::TYPES_NAISSANCE_POUR_CEC);

        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeNaissance::query()
            ->whereNotNull('approbation_mairie')
            ->where('approbation_mairie', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->whereIn('type_declaration', self::TYPES_NAISSANCE_POUR_CEC);
            })
            ->count();

        return [
            'enregistres' => $certificats + $declarations + $dossiersTribunal,
            'certificats_enregistres' => $certificats,
            'declarations_produites' => $declarations,
            'dossiers_tribunal' => $dossiersTribunal,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @return array{
     *   enregistres: int,
     *   certificats_enregistres: int,
     *   declarations_produites: int,
     *   envoyes: int,
     *   valides: int,
     *   en_attente: int,
     *   actes_produits: int
     * }
     */
    /**
     * @param  array<int, string>  $codes
     */
    private function kpiDeces(array $codes): array
    {
        $tous = DeclarationDeces::query();
        $this->perimetreDeclarationVersCodes($tous, $codes);
        $tous->whereIn('type_declaration', self::TYPES_DECES);

        $certificats = (clone $tous)->where('type_declaration', self::TYPE_CERTIFICAT_DECES)->count();
        $declarations = (clone $tous)->where('type_declaration', self::TYPE_DECLARATION_DECES)->count();

        $base = DeclarationDeces::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->whereIn('type_declaration', self::TYPES_DECES);

        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeDeces::query()
            ->whereNotNull('approbation_pompe_funebre')
            ->where('approbation_pompe_funebre', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->whereIn('type_declaration', self::TYPES_DECES);
            })
            ->count();

        return [
            'enregistres' => $certificats + $declarations,
            'certificats_enregistres' => $certificats,
            'declarations_produites' => $declarations,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @return Collection<int, Declarationnaissance>
     */
    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, Declarationnaissance>
     */
    private function recentNaissance(array $codes): Collection
    {
        $q = Declarationnaissance::query();
        $this->perimetreDeclarationVersCodes($q, $codes);

        return $q->whereIn('type_declaration', self::TYPES_NAISSANCE_POUR_CEC)
            ->with(['enfant', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, DeclarationDeces>
     */
    private function recentDeces(array $codes): Collection
    {
        $q = DeclarationDeces::query();
        $this->perimetreDeclarationVersCodes($q, $codes);

        return $q->whereIn('type_declaration', self::TYPES_DECES)
            ->with(['defunt', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * KPI naissance — uniquement {@see TYPE_CERTIFICAT_NAISSANCE} (saisie hôpital / formation).
     *
     * @param  array<int, string>  $codes
     */
    private function kpiNaissanceCertificatsFormation(array $codes): array
    {
        $type = self::TYPE_CERTIFICAT_NAISSANCE;
        $base = Declarationnaissance::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->where('type_declaration', $type);

        $certificats = (clone $base)->count();
        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeNaissance::query()
            ->whereNotNull('approbation_mairie')
            ->where('approbation_mairie', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes, $type) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->where('type_declaration', $type);
            })
            ->count();

        return [
            'enregistres' => $certificats,
            'certificats_enregistres' => $certificats,
            'declarations_produites' => 0,
            'dossiers_tribunal' => 0,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, Declarationnaissance>
     */
    private function recentNaissanceCertificatsFormation(array $codes): Collection
    {
        return Declarationnaissance::query()
            ->whereIn('code_institution', $codes)
            ->where('type_declaration', self::TYPE_CERTIFICAT_NAISSANCE)
            ->with(['enfant', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * KPI décès — formation sanitaire : déclarations issues du certificat médical (pas la constatation hygiène).
     *
     * @param  array<int, string>  $codes
     * @return array{
     *   enregistres: int,
     *   certificats_enregistres: int,
     *   declarations_produites: int,
     *   envoyes: int,
     *   valides: int,
     *   en_attente: int,
     *   actes_produits: int
     * }
     */
    private function kpiDecesDeclarationFormation(array $codes): array
    {
        $type = self::TYPE_DECLARATION_DECES;
        $base = DeclarationDeces::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->where('type_declaration', $type);

        $declarations = (clone $base)->count();
        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeDeces::query()
            ->whereNotNull('approbation_pompe_funebre')
            ->where('approbation_pompe_funebre', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes, $type) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->where('type_declaration', $type);
            })
            ->count();

        return [
            'enregistres' => $declarations,
            'certificats_enregistres' => 0,
            'declarations_produites' => $declarations,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, DeclarationDeces>
     */
    private function recentDecesDeclarationFormation(array $codes): Collection
    {
        return DeclarationDeces::query()
            ->whereIn('code_institution', $codes)
            ->where('type_declaration', self::TYPE_DECLARATION_DECES)
            ->with(['defunt', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * @param  array<int, string>  $codes
     * @return array{
     *   constatations_saisies: int,
     *   envoyes: int,
     *   valides: int,
     *   en_attente: int,
     *   actes_produits: int
     * }
     */
    private function kpiDecesConstatationCentreHygiene(array $codes): array
    {
        $type = self::TYPE_CERTIFICAT_DECES;
        $base = DeclarationDeces::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->where('type_declaration', $type);

        $saisies = (clone $base)->count();
        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeDeces::query()
            ->whereNotNull('approbation_pompe_funebre')
            ->where('approbation_pompe_funebre', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes, $type) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->where('type_declaration', $type);
            })
            ->count();

        return [
            'constatations_saisies' => $saisies,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, DeclarationDeces>
     */
    private function recentDecesConstatationCentreHygiene(array $codes): Collection
    {
        return DeclarationDeces::query()
            ->whereIn('code_institution', $codes)
            ->where('type_declaration', self::TYPE_CERTIFICAT_DECES)
            ->with(['defunt', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * @return array{
     *   enregistres: int,
     *   formulaires_declaration_mariage: int,
     *   formulaires_dispense: int,
     *   envoyes: int,
     *   valides: int,
     *   en_attente: int,
     *   actes_produits: int
     * }
     *
     * @param  array<int, string>  $codes
     */
    private function kpiMariage(array $codes): array
    {
        $tous = DeclarationMariage::query();
        $this->perimetreDeclarationVersCodes($tous, $codes);
        $tous->whereIn('type_declaration', self::TYPES_MARIAGE_FORMULAIRES);

        $declarationMariage = (clone $tous)->where('type_declaration', self::TYPE_FORMULAIRE_DECLARATION_MARIAGE)->count();
        $dispense = (clone $tous)->where('type_declaration', self::TYPE_FORMULAIRE_DISPENSE)->count();

        $base = DeclarationMariage::query();
        $this->perimetreDeclarationVersCodes($base, $codes);
        $base->whereIn('type_declaration', self::TYPES_MARIAGE_FORMULAIRES);

        $envoyes = (clone $base)->whereHas('mouvements', fn ($q) => $q->where('statut', 'Envoyée'))->count();
        $valides = (clone $base)->where('cec_approuver', 'OUI')->count();
        $enAttente = (clone $base)->where('cec_approuver', '!=', 'OUI')->count();

        $actesProduits = ActeMariage::query()
            ->whereNotNull('approbation_mairie')
            ->where('approbation_mairie', '!=', '')
            ->whereHas('declaration', function ($q) use ($codes) {
                $this->perimetreDeclarationVersCodes($q, $codes);
                $q->whereIn('type_declaration', self::TYPES_MARIAGE_FORMULAIRES);
            })
            ->count();

        return [
            'enregistres' => $declarationMariage + $dispense,
            'formulaires_declaration_mariage' => $declarationMariage,
            'formulaires_dispense' => $dispense,
            'envoyes' => $envoyes,
            'valides' => $valides,
            'en_attente' => $enAttente,
            'actes_produits' => $actesProduits,
        ];
    }

    /**
     * @param  array<int, string>  $codes
     * @return Collection<int, DeclarationMariage>
     */
    private function recentMariage(array $codes): Collection
    {
        $q = DeclarationMariage::query();
        $this->perimetreDeclarationVersCodes($q, $codes);

        return $q->whereIn('type_declaration', self::TYPES_MARIAGE_FORMULAIRES)
            ->with(['epoux', 'epouse', 'mouvements'])
            ->orderByDesc('date_heure_declaration')
            ->limit(6)
            ->get();
    }

    /**
     * Dossiers dont l’établissement d’origine OU le destinataire du flux appartient au périmètre (CEC + rattachements).
     *
     * @param  array<int, string>  $codes
     */
    private function perimetreDeclarationVersCodes(Builder $query, array $codes): void
    {
        $query->where(function ($q) use ($codes) {
            $q->whereIn('code_institution', $codes)
                ->orWhere(function ($q2) use ($codes) {
                    $q2->whereNotNull('code_institution_destinataire')
                        ->whereIn('code_institution_destinataire', $codes);
                });
        });
    }
}
