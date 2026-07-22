<?php

namespace Modules\Naissance\Services;

use App\Models\User;
use App\Sifec\Sifec;
use App\Sifec\SifecFacade;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Referentiel\Entities\FeuilletRegistre;
use Modules\Referentiel\Entities\Registre;
use RuntimeException;

class ActeNaissanceSignatureFinalizer
{
    /**
     * Calcule le NIUPP prévisionnel d'un acte SANS effet de bord (pas d'incrément de registre,
     * pas de création de feuillet, pas de persistance). Utilisé pour le rendu du PDF à signer :
     * l'allocation réelle n'a lieu qu'à la finalisation via assignNiuppFeuilletRegistre().
     */
    public function previewNiupp(ActeNaissance $acte): string
    {
        if ($acte->niupp) {
            return (string) $acte->niupp;
        }

        $declaration = $acte->declaration()->with('enfant')->first();
        if (!$declaration || !$declaration->enfant) {
            throw new RuntimeException('Déclaration ou enfant manquant pour finaliser l’acte.');
        }

        $registre = Registre::query()->whereKey($acte->code_registre)->first();
        if (!$registre) {
            throw new RuntimeException('Registre introuvable pour finaliser l’acte.');
        }

        $position = $registre->nombre_acte_transcrit + 1;
        if ($position > $registre->nombre_acte_prevu) {
            throw new RuntimeException('Registre plein : impossible d’inscrire l’acte signé.');
        }

        return Sifec::genererNiupp($declaration->code_declaration_naissance, $position);
    }

    /**
     * Attribue le NIUPP, met à jour le registre et crée le feuillet (après signature électronique).
     */
    public function assignNiuppFeuilletRegistre(ActeNaissance $acte, User $user): void
    {
        if ($acte->niupp) {
            return;
        }

        $acte = ActeNaissance::query()->whereKey($acte->getKey())->lockForUpdate()->firstOrFail();
        if ($acte->niupp) {
            return;
        }

        $declaration = $acte->declaration()->with('enfant')->first();
        if (!$declaration || !$declaration->enfant) {
            throw new RuntimeException('Déclaration ou enfant manquant pour finaliser l’acte.');
        }

        $registre = Registre::query()->whereKey($acte->code_registre)->lockForUpdate()->first();
        if (!$registre) {
            throw new RuntimeException('Registre introuvable pour finaliser l’acte.');
        }

        $position = $registre->nombre_acte_transcrit + 1;
        if ($position > $registre->nombre_acte_prevu) {
            throw new RuntimeException('Registre plein : impossible d’inscrire l’acte signé.');
        }

        // Ordre NIUPP = rang dans ce volume (référentiel : un registre annuel par type d’acte et par CEC ; même suite que le feuillet).
        $ordre = $position;
        $niupp = Sifec::genererNiupp($declaration->code_declaration_naissance, $ordre);

        if (ActeNaissance::query()->where('niupp', $niupp)->exists()) {
            throw new RuntimeException('Collision NIUPP, veuillez réessayer.');
        }

        $registre->nombre_acte_transcrit = $position;
        if ($position == $registre->nombre_acte_prevu) {
            $registre->statut = 0;
        }
        $registre->save();

        $feuillet = new FeuilletRegistre();
        $feuillet->code_feuillet_registre = Sifec::genererCodeUniqueReferentiel($feuillet, 'code_feuillet_registre', 4, 'FRE_');
        $feuillet->code_acte = $niupp;
        $feuillet->numero_acte = SifecFacade::generate_acte_number($registre, $position);
        $feuillet->save();

        $acte->niupp = $niupp;
        $acte->save();
    }
}
