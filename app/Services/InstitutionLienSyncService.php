<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\InstitutionLien;
use Modules\Referentiel\Entities\TypeLienInstitution;

class InstitutionLienSyncService
{
    /**
     * Remplace tous les liens sortants de l'institution pour chaque type concerné,
     * puis met à jour la colonne historique code_pompe_funebre si demandé.
     *
     * @param  array<string, mixed>  $input
     */
    public function syncFromRequest(Institution $institution, array $input, bool $refreshLegacyColumn = true): void
    {
        if (!Schema::hasTable('tr_institution_lien')) {
            return;
        }

        $decesCodes = $this->normalizeCodes($input['liens_cec_deces'] ?? []);
        $naissanceCodes = $this->normalizeCodes($input['liens_cec_naissance'] ?? []);
        $tribunalCodes = $this->normalizeCodes($input['liens_tribunal_ressort'] ?? []);

        $decesCodes = $this->filterExistingInstitutionCodes($decesCodes, $institution->code_institution);
        $naissanceCodes = $this->filterExistingInstitutionCodes($naissanceCodes, $institution->code_institution);
        $tribunalCodes = $this->filterExistingInstitutionCodes($tribunalCodes, $institution->code_institution);

        $source = $institution->code_institution;

        $this->replaceLiens($source, TypeLienInstitution::CODE_PARTENAIRE_DECES_POMPE, $decesCodes);
        $this->replaceLiens($source, TypeLienInstitution::CODE_FORMATION_CEC_NAISSANCE, $naissanceCodes);
        $this->replaceLiens($source, TypeLienInstitution::CODE_TRIBUNAL_RESSORT, $tribunalCodes);

        if ($refreshLegacyColumn) {
            $this->refreshCodePompeFunebreColumn($institution, $decesCodes, $naissanceCodes);
        }
    }

    /**
     * @param  list<string>  $decesCodes
     * @param  list<string>  $naissanceCodes
     */
    public function refreshCodePompeFunebreColumn(Institution $institution, array $decesCodes, array $naissanceCodes): void
    {
        $institution->load('typeInstitution');
        $cat = $institution->typeInstitution?->code_type_categorie_ins;
        $type = $institution->code_type_institution;

        if ($cat === 'TCINS_0003') {
            $primary = $naissanceCodes[0] ?? null;
        } elseif ($type === 'TPINS_0003') {
            $primary = $decesCodes[0] ?? null;
        } else {
            $primary = $naissanceCodes[0] ?? $decesCodes[0] ?? null;
        }

        $institution->code_pompe_funebre = $primary;
        $institution->saveQuietly();
    }

    /**
     * @param  list<string>  $cibles
     * @return list<string>
     */
    private function filterExistingInstitutionCodes(array $cibles, string $sourceCode): array
    {
        if ($cibles === []) {
            return [];
        }

        $valid = Institution::query()
            ->whereIn('code_institution', $cibles)
            ->where('code_institution', '!=', $sourceCode)
            ->pluck('code_institution')
            ->all();

        return array_values(array_unique($valid));
    }

    /**
     * @param  list<string>  $cibles
     */
    private function replaceLiens(string $source, string $typeCode, array $cibles): void
    {
        InstitutionLien::query()
            ->where('code_institution_source', $source)
            ->where('code_type_lien', $typeCode)
            ->delete();

        foreach ($cibles as $cible) {
            InstitutionLien::query()->create([
                'code_institution_source' => $source,
                'code_institution_cible' => $cible,
                'code_type_lien' => $typeCode,
            ]);
        }
    }

    /**
     * @return list<string>
     */
    private function normalizeCodes($raw): array
    {
        if (!is_array($raw)) {
            return [];
        }

        $out = [];
        foreach ($raw as $c) {
            $s = is_string($c) ? trim($c) : '';
            if ($s !== '') {
                $out[] = $s;
            }
        }

        return array_values(array_unique($out));
    }
}
