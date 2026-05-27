<?php

namespace Modules\Deces\Services;

use Illuminate\Support\Collection;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\Institution;

/**
 * Aperçu limité côté SQL pour la pompe funèbre (actes de décès),
 * calqué sur CecNaissanceActeDashboardService.
 */
class PfDecesActeDashboardService
{
    public function documentsAControlerPreview(Institution $pf, int $limit = 20): Collection
    {
        return DeclarationDeces::query()
            ->with(['defunt', 'declarant', 'pere', 'mere', 'mouvements', 'acte', 'requisition', 'jugement'])
            ->where('declarant_approuver', 'OUI')
            ->where('code_institution_destinataire', $pf->code_institution)
            ->where(function ($outer) {
                $outer->where(function ($q) {
                    $q->whereIn('type_declaration', ['DECLARATION DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'])
                        ->where('cec_approuver', 'NON');
                })->orWhere(function ($q) {
                    $q->where('type_declaration', 'DECLARATION DE DECES')
                        ->where('cec_approuver', 'OUI');
                });
            })
            ->orderByRaw("CASE WHEN cec_approuver = 'NON' THEN 0 ELSE 1 END")
            ->orderByDesc('date_heure_declaration')
            ->limit($limit)
            ->get();
    }

    public function actesGestionPreview(Institution $institution, int $limit = 20): Collection
    {
        return $institution->getActesGestion('deces')
            ->sort(function (DeclarationDeces $a, DeclarationDeces $b) {
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

    private function pipelinePriority(DeclarationDeces $dd): int
    {
        if (! $dd->acte) {
            return 0;
        }
        $approbation = $dd->acte->approbation_pompe_funebre ?? null;
        if (is_null($approbation) || $approbation === '' || $approbation === '0') {
            return 1;
        }

        return 2;
    }
}
