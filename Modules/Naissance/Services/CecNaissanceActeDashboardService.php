<?php

namespace Modules\Naissance\Services;

use Illuminate\Support\Collection;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\InstitutionLien;
use Modules\Referentiel\Entities\TypeLienInstitution;

/**
 * Requêtes ciblées pour le tableau de bord CEC (actes de naissance) :
 * aperçu limité côté SQL au lieu de charger toute la base puis take(20) en mémoire.
 */
class CecNaissanceActeDashboardService
{
    /** @var list<string> */
    private const TYPES_TRIBUNAL_NAISSANCE = [
        'CERTIFICAT DE NON INSCRIPTION',
        "CERTIFICAT DE DESTRUCTION DE L'ACTE",
        'FICHE DE TRANSCRIPTION',
        'CERTIFICAT DE TRANSCRIPTION',
    ];

    /**
     * Codes institutions émettrices (formations sanitaires descendantes + liens CEC).
     *
     * @return list<string>
     */
    public function naissanceEmitterInstitutionCodes(Institution $cec): array
    {
        $formationsSanitairesCodes = $cec->descendants()
            ->filter(fn ($institution) => $institution->typeInstitution?->code_type_categorie_ins === 'TCINS_0003')
            ->pluck('code_institution')
            ->toArray();

        $codesFormationsLiees = InstitutionLien::query()
            ->where('code_institution_cible', $cec->code_institution)
            ->where('code_type_lien', TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE)
            ->pluck('code_institution_source')
            ->all();

        return array_values(array_unique(array_merge($formationsSanitairesCodes, $codesFormationsLiees)));
    }

    /**
     * Documents du flux formation → CEC affichés dans « Documents à contrôler » :
     * - certificats encore à valider (comme getDeclarationsFormationSanitaireAControler) ;
     * - déclarations déjà validées au CEC (même périmètre que getDeclarationsFormationSanitaireApprouvees),
     *   pour conserver l’accès aux PDF certificat / déclaration générée.
     */
    public function documentsAControlerPreview(Institution $cec, int $limit = 35): Collection
    {
        $emitters = $this->naissanceEmitterInstitutionCodes($cec);
        if ($emitters === []) {
            return collect();
        }

        return Declarationnaissance::query()
            ->with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'])
            ->whereIn('code_institution', $emitters)
            ->where('declarant_approuver', 'OUI')
            ->where('code_institution_destinataire', $cec->code_institution)
            ->where(function ($outer) {
                $outer->where(function ($q) {
                    $q->where('type_declaration', 'CERTIFICAT DE NAISSANCE')
                        ->where('cec_approuver', 'NON')
                        ->whereHas('mouvements', fn ($mq) => $mq->whereIn('code_mouvement', ['MOUV_0001', 'MOUV_0035', 'MOUV_0011']));
                })->orWhere(function ($q) {
                    $q->where('type_declaration', 'DECLARATION DE NAISSANCE')
                        ->where('cec_approuver', 'OUI');
                });
            })
            ->orderByRaw("CASE WHEN type_declaration = 'CERTIFICAT DE NAISSANCE' AND cec_approuver = 'NON' THEN 0 ELSE 1 END")
            ->orderByDesc('date_heure_declaration')
            ->limit($limit)
            ->get();
    }

    /**
     * Actes à gérer : fusion des trois sources (comme getActesGestion), avec limite SQL par source puis dédoublonnage et tri métier.
     */
    public function actesGestionPreview(Institution $cec, int $limit = 20): Collection
    {
        $perSource = max($limit, (int) ceil($limit * 1.5));

        $formation = $this->queryFormationSanitaireApprouvees($cec, $perSource);
        $tribunal = $this->queryTribunalTransfert($cec, $perSource);
        $direct = $this->queryCentreDirectApprouvees($cec, $perSource);

        $merged = $this->mergeUniqueByPrimaryKey($formation, $tribunal, $direct);

        return $merged
            ->sort(function (Declarationnaissance $a, Declarationnaissance $b) {
                $pa = $this->pipelinePriority($a);
                $pb = $this->pipelinePriority($b);
                if ($pa !== $pb) {
                    return $pa <=> $pb;
                }
                $da = strtotime((string) ($a->date_heure_declaration ?? $a->created_at ?? '')) ?: 0;
                $db = strtotime((string) ($b->date_heure_declaration ?? $b->created_at ?? '')) ?: 0;

                return $db <=> $da;
            })
            ->values()
            ->take($limit);
    }

    private function pipelinePriority(Declarationnaissance $dn): int
    {
        if (! $dn->acte) {
            return 0;
        }
        if (! $dn->acte->approbation_mairie) {
            return 1;
        }

        return 2;
    }

    private function queryFormationSanitaireApprouvees(Institution $cec, int $limit): Collection
    {
        $emitters = $this->naissanceEmitterInstitutionCodes($cec);
        if ($emitters === []) {
            return collect();
        }

        return Declarationnaissance::query()
            ->with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'])
            ->whereIn('code_institution', $emitters)
            ->whereIn('type_declaration', ['DECLARATION DE NAISSANCE'])
            ->where([
                'cec_approuver' => 'OUI',
                'code_institution_destinataire' => $cec->code_institution,
            ])
            ->orderByDesc('date_heure_declaration')
            ->limit($limit)
            ->get();
    }

    private function queryTribunalTransfert(Institution $cec, int $limit): Collection
    {
        return Declarationnaissance::query()
            ->with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'])
            ->where([
                'code_institution' => $cec->code_institution,
                'tribunal_approuver' => 'OUI',
            ])
            ->where(function ($q) {
                $q->whereHas('requisition')->orWhereHas('jugement');
            })
            ->whereHas('mouvements', fn ($q) => $q->where('code_mouvement', 'MOUV_0011'))
            ->whereIn('type_declaration', self::TYPES_TRIBUNAL_NAISSANCE)
            ->orderByDesc('date_heure_declaration')
            ->limit($limit)
            ->get();
    }

    private function queryCentreDirectApprouvees(Institution $cec, int $limit): Collection
    {
        return Declarationnaissance::query()
            ->with(['enfant', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'])
            ->where([
                'code_institution' => $cec->code_institution,
                'cec_approuver' => 'OUI',
            ])
            ->whereIn('type_declaration', ['DECLARATION DE NAISSANCE'])
            ->orderByDesc('date_heure_declaration')
            ->limit($limit)
            ->get();
    }

    /**
     * @param  Collection<int, Declarationnaissance>  ...$chunks
     * @return Collection<int, Declarationnaissance>
     */
    private function mergeUniqueByPrimaryKey(Collection ...$chunks): Collection
    {
        $seen = [];
        $out = collect();

        foreach ($chunks as $chunk) {
            foreach ($chunk as $dn) {
                $id = $dn->code_declaration_naissance;
                if (! isset($seen[$id])) {
                    $seen[$id] = true;
                    $out->push($dn);
                }
            }
        }

        return $out;
    }
}
