<?php

namespace App\Services;

use App\Helpers\CecBrazzavilleHelper;
use App\Models\InstitutionUser;
use App\Models\User;
use Modules\Referentiel\Entities\Institution;

/**
 * Choix du tableau de bord « métier » après connexion.
 * Formation sanitaire (cat. TCINS_0003 ou FONC_0006 / FONC_0016 / FONC_0020) → {@see FormationSanitaireDashboardService::buildPourFormationSanitaire}.
 * Centre d’hygiène (TPINS_0019 ou FONC_0007) → {@see FormationSanitaireDashboardService::buildPourCentreHygiene}.
 */
class MetierDashboardService
{
    /** Rôles susceptibles d’agréger les arrondissements rattachés à la même mairie / collectivité. */
    private const HUB_ROLES = [
        'FONC_0001',
        'FONC_0002',
        'FONC_0004',
        'FONC_0014',
        'FONC_0015',
        'FONC_0024',
    ];

    /** Mairies Brazzaville : pas de parcours décès pour ces fonctions (CEC). */
    private const CEC_BRAZZAVILLE_SANS_DECES_FONC = [
        'FONC_0001',
        'FONC_0002',
        'FONC_0003',
        'FONC_0004',
        'FONC_0014',
        'FONC_0015',
        'FONC_0024',
    ];

    public function __construct(
        private FormationSanitaireDashboardService $deskKpi
    ) {}

    /**
     * @return array{view: string, data: array}|null null → tableau de bord statistique historique (admin / DGAT / inconnu)
     */
    public function resolve(User $user): ?array
    {
        $aff = $user->affectationActive();
        if ($aff === null || $aff->institution === null) {
            return null;
        }

        $inst = $aff->institution;
        $inst->loadMissing(['typeInstitution.typeCategorieInstitution', 'institutionParent', 'institutionsEnfants']);

        $typeIns = $inst->typeInstitution;
        $codeCat = $typeIns?->typeCategorieInstitution?->code_type_categorie_ins;
        $codeTypeIns = $typeIns?->code_type_institution;
        $codeFonc = $aff->fonction?->code_fonction;

        if ($this->estDeskFormationSanitaire($codeCat, $codeFonc)) {
            try {
                return [
                    'view' => 'admin.dashboard.formation_sanitaire',
                    'data' => array_merge(
                        $this->deskKpi->buildPourFormationSanitaire($aff),
                        [
                            'libInstitution' => $inst->lib_institution,
                            'user' => $user,
                            'roleFormationLibelle' => $aff->fonction?->lib_fonction ?? 'Agent formation sanitaire',
                        ]
                    ),
                ];
            } catch (\Throwable $e) {
                report($e);

                return null;
            }
        }

        if ($this->estDeskCentreHygiene($codeTypeIns, $codeFonc)) {
            try {
                return [
                    'view' => 'admin.dashboard.metier_centre_hygiene',
                    'data' => array_merge(
                        $this->deskKpi->buildPourCentreHygiene($aff),
                        [
                            'libInstitution' => $inst->lib_institution,
                            'user' => $user,
                            'roleHygieneLibelle' => $aff->fonction?->lib_fonction ?? 'Agent centre d’hygiène',
                        ]
                    ),
                ];
            } catch (\Throwable $e) {
                report($e);

                return null;
            }
        }

        if ($codeCat === 'TCINS_0002') {
            return [
                'view' => 'admin.dashboard.metier_tribunal',
                'data' => [
                    'libInstitution' => $inst->lib_institution,
                    'user' => $user,
                    'affectation' => $aff,
                    'roleBadge' => $this->tribunalRoleBadge($codeTypeIns),
                    'fonctionLib' => $aff->fonction?->lib_fonction ?? '—',
                ],
            ];
        }

        if ($codeCat === 'TCINS_0004') {
            return $this->etatCivilDesk($user, $aff, $inst, $codeTypeIns, $codeFonc, 'Ambassade', 'fa fa-globe-africa');
        }

        if ($codeCat === 'TCINS_0001') {
            $badge = $this->etatCivilRoleBadge($codeTypeIns, $codeFonc);
            $icon = $this->etatCivilHeaderIcon($codeTypeIns);

            return $this->etatCivilDesk($user, $aff, $inst, $codeTypeIns, $codeFonc, $badge, $icon);
        }

        return null;
    }

    /**
     * Institution catégorie « Formation sanitaire » ou fonction métier hôpital / agent sanitaire.
     */
    private function estDeskFormationSanitaire(?string $codeCat, ?string $codeFonc): bool
    {
        if ($codeCat === 'TCINS_0003') {
            return true;
        }

        return $codeFonc !== null && in_array($codeFonc, ['FONC_0006', 'FONC_0016', 'FONC_0020'], true);
    }

    /**
     * Centre d’hygiène (TPINS_0019) ou agent métier FONC_0007 — avant le desk état civil TCINS_0001.
     */
    private function estDeskCentreHygiene(?string $codeTypeIns, ?string $codeFonc): bool
    {
        if ($codeTypeIns === 'TPINS_0019') {
            return true;
        }

        return $codeFonc === 'FONC_0007';
    }

    private function etatCivilDesk(
        User $user,
        InstitutionUser $aff,
        Institution $inst,
        ?string $codeTypeIns,
        ?string $codeFonc,
        string $roleBadge,
        string $headerIcon
    ): ?array {
        $codes = $this->resolveDeskInstitutionCodes($inst, $codeFonc);
        $cec = $inst->institutionParent;
        $showNaissance = $codeTypeIns !== 'TPINS_0003' && $codeFonc !== 'FONC_0017';

        $sansDecesCecBrazzaville = $codeFonc !== null
            && $codeFonc !== 'FONC_0017'
            && in_array($codeFonc, self::CEC_BRAZZAVILLE_SANS_DECES_FONC, true)
            && CecBrazzavilleHelper::mairieCecZoneBrazzavilleSansParcoursDeces($inst);

        $showDeces = ! $sansDecesCecBrazzaville;

        try {
            return [
                'view' => 'admin.dashboard.metier_etat_civil',
                'data' => array_merge(
                    $this->deskKpi->buildForInstitutionCodes($codes, $aff, $cec),
                    [
                        'libInstitution' => $inst->lib_institution,
                        'user' => $user,
                        'roleBadge' => $roleBadge,
                        'headerIcon' => $headerIcon,
                        'showKpiNaissance' => $showNaissance,
                        'showKpiDeces' => $showDeces,
                        'noteParcoursDecesBrazzaville' => $sansDecesCecBrazzaville
                            ? 'Les centres d’état civil de Brazzaville ne gèrent pas le processus décès dans SIFEC ; le suivi des dossiers décès relève des pompes funèbres municipales.'
                            : null,
                    ]
                ),
            ];
        } catch (\Throwable $e) {
            report($e);

            return null;
        }
    }

    /**
     * @return array<int, string>
     */
    private function resolveDeskInstitutionCodes(Institution $inst, ?string $codeFonc): array
    {
        $self = $inst->code_institution;
        if ($codeFonc !== null && in_array($codeFonc, self::HUB_ROLES, true)) {
            $children = $inst->institutionsEnfants()->pluck('code_institution')->all();
            if ($children !== []) {
                return array_values(array_unique(array_merge([$self], $children)));
            }
        }

        return [$self];
    }

    private function etatCivilRoleBadge(?string $codeTypeIns, ?string $codeFonc): string
    {
        if ($codeFonc !== null) {
            $parFonction = match ($codeFonc) {
                'FONC_0001' => 'Sous-préfet — État civil',
                'FONC_0002', 'FONC_0003', 'FONC_0004' => 'Officier / agent — État civil',
                'FONC_0005', 'FONC_0012' => 'Pompes funèbres',
                'FONC_0007' => 'Centre d’hygiène',
                'FONC_0014' => 'Mairie centrale',
                'FONC_0015', 'FONC_0024' => 'Service état civil',
                'FONC_0016' => 'Chef de service gestion des malades',
                'FONC_0017' => 'Bureau d’enregistrement des décès',
                'FONC_0019', 'FONC_0021' => 'Ambassade / consulat',
                default => null,
            };
            if ($parFonction !== null) {
                return $parFonction;
            }
        }

        return match ($codeTypeIns) {
            'TPINS_0002' => 'Mairie — Centre d’état civil',
            'TPINS_0003' => 'Pompes funèbres',
            'TPINS_0005' => 'Ambassade',
            'TPINS_0019' => 'Centre d’hygiène',
            default => 'État civil',
        };
    }

    private function etatCivilHeaderIcon(?string $codeTypeIns): string
    {
        return match ($codeTypeIns) {
            'TPINS_0003' => 'fa fa-dove',
            'TPINS_0005' => 'fa fa-globe-africa',
            'TPINS_0019' => 'fa fa-hand-holding-medical',
            default => 'fa fa-landmark',
        };
    }

    private function tribunalRoleBadge(?string $codeTypeIns): string
    {
        return match ($codeTypeIns) {
            'TPINS_0006' => 'Cour d’appel',
            'TPINS_0008' => 'Tribunal d’instance',
            default => 'Tribunal',
        };
    }
}
