<?php

namespace Modules\Deces\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;

/**
 * Détermine l'institution destinataire à l'envoi d'un certificat / dossier décès
 * à partir de la localité du lieu de décès : pompe funèbre centrale (ex. Brazzaville)
 * ou CEC (même localité, avec repli par parent et table {@see tr_routage_reception_deces}).
 */
class DecesDestinataireEnvoiService
{
    private const CATEGORIE_CEC = 'TCINS_0001';

    private const TYPE_MAIRIE = 'TPINS_0002';

    public function resolveCodeInstitutionDestinataire(DeclarationDeces $declaration): ?string
    {
        $codeLieuDeces = $this->extractCodeLocaliteLieuDeces($declaration);
        if ($codeLieuDeces === null) {
            return null;
        }

        $localite = Localite::query()->find($codeLieuDeces);
        if ($localite === null) {
            return null;
        }

        foreach (config('sifec_deces.communes_pompe_funebre_centrale', []) as $rule) {
            $codeCommune = $rule['code_localite_commune'] ?? null;
            $codePompe = $rule['code_institution_pompe_funebre'] ?? null;
            if (! is_string($codeCommune) || ! is_string($codePompe)) {
                continue;
            }
            if ($this->localiteADescendantOuEst($localite, $codeCommune)) {
                return $codePompe;
            }
        }

        return $this->resolveCecAvecRemontee($localite);
    }

    /**
     * Extrait le code tr_localite du champ lieu_deces (code ou libellé).
     */
    public function extractCodeLocaliteLieuDeces(DeclarationDeces $declaration): ?string
    {
        $raw = $declaration->lieu_deces;
        if ($raw === null || $raw === '') {
            return null;
        }
        $raw = trim((string) $raw);
        if ($raw === '') {
            return null;
        }

        if (strlen($raw) <= 16 && Localite::query()->where('code_localite', $raw)->exists()) {
            return $raw;
        }

        return Localite::query()->where('lib_localite', $raw)->value('code_localite');
    }

    private function localiteADescendantOuEst(Localite $localite, string $codeAncetre): bool
    {
        $current = $localite;
        for ($i = 0; $i < 30 && $current !== null; $i++) {
            if ($current->code_localite === $codeAncetre) {
                return true;
            }
            $current = $current->localiteParent;
        }

        return false;
    }

    private function resolveCecAvecRemontee(Localite $depart): ?string
    {
        $current = $depart;
        for ($i = 0; $i < 30 && $current !== null; $i++) {
            $fromTable = $this->codeInstitutionDepuisTableRoutage($current->code_localite);
            if ($fromTable !== null) {
                return $fromTable;
            }

            $cecs = $this->institutionsCecEligiblesPourLocalite($current->code_localite);
            if ($cecs->isNotEmpty()) {
                return $cecs->first()->code_institution;
            }

            $current = $current->localiteParent;
        }

        return null;
    }

    private function codeInstitutionDepuisTableRoutage(string $codeLocalite): ?string
    {
        if (! Schema::hasTable('tr_routage_reception_deces')) {
            return null;
        }

        $row = DB::table('tr_routage_reception_deces')
            ->where('code_localite', $codeLocalite)
            ->orderBy('priorite')
            ->orderBy('code_institution')
            ->first();

        return $row->code_institution ?? null;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Institution>
     */
    private function institutionsCecEligiblesPourLocalite(string $codeLocalite)
    {
        $exclus = config('sifec_deces.types_institution_exclus_reception_cec', ['TPINS_0003', 'TPINS_0019']);

        return Institution::query()
            ->where('code_localite', $codeLocalite)
            ->whereHas('typeInstitution', function ($q) use ($exclus) {
                $q->where('code_type_categorie_ins', self::CATEGORIE_CEC)
                    ->whereNotIn('code_type_institution', $exclus);
            })
            ->orderByRaw("CASE WHEN code_type_institution = '".self::TYPE_MAIRIE."' THEN 0 ELSE 1 END")
            ->orderBy('code_institution')
            ->get();
    }
}
