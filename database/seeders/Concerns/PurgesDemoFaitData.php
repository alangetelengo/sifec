<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Referentiel\Entities\Personne;

/**
 * Suppression des données de démo (faits) et des lignes annexes personne
 * (contacts, résidences, feuillets registre), notifications et compteurs registre.
 */
trait PurgesDemoFaitData
{
    /**
     * @return list<string>
     */
    protected function systemPersonCodes(): array
    {
        static $codes = null;

        if ($codes === null) {
            $path = database_path('seeders/Data/sifec_comptes_institutions.php');
            $comptes = is_file($path) ? require $path : [];
            $codes = array_column($comptes, 'code_personne');
        }

        return $codes;
    }

    protected function filterDemoPersonCodes(Collection $personCodes): Collection
    {
        return $personCodes
            ->filter()
            ->unique()
            ->reject(fn ($code) => in_array((string) $code, $this->systemPersonCodes(), true))
            ->values();
    }

    protected function disableForeignKeyChecks(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }
    }

    protected function enableForeignKeyChecks(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    protected function purgePersonneAnnexes(Collection $personCodes): void
    {
        $codes = $this->filterDemoPersonCodes($personCodes);

        if ($codes->isEmpty()) {
            return;
        }

        // Suppression physique (tables avec SoftDeletes côté Eloquent).
        DB::table('t_contact_personne')->whereIn('code_personne', $codes)->delete();
        DB::table('t_residence_personne')->whereIn('code_personne', $codes)->delete();
        Personne::whereIn('code_personne', $codes)->delete();
    }

    protected function purgeFeuilletsForActeCodes(Collection $acteCodes): void
    {
        $codes = $acteCodes->filter()->unique()->values();

        if ($codes->isEmpty()) {
            return;
        }

        DB::table('t_feuillet_registre')->whereIn('code_acte', $codes)->delete();
    }

    /**
     * Vide les notifications in-app et remet à zéro les compteurs de transcription des registres.
     */
    protected function resetNotificationsAndRegistres(): void
    {
        $this->disableForeignKeyChecks();

        DB::table('notifications')->truncate();
        DB::table('tr_registre')->update(['nombre_acte_transcrit' => 0]);

        $this->enableForeignKeyChecks();
    }

    protected function purgeAllDemoFaitData(): void
    {
        $this->purgeNaissanceDemoDataWithoutRegistreReset();
        $this->purgeDecesDemoData();
        $this->purgeMariageDemoData();
        $this->resetNotificationsAndRegistres();
    }

    protected function purgeNaissanceDemoData(): void
    {
        $this->purgeNaissanceDemoDataWithoutRegistreReset();
        $this->resetNotificationsAndRegistres();
    }

    private function purgeNaissanceDemoDataWithoutRegistreReset(): void
    {
        $personCodes = DB::table('t_declaration_naissance')
            ->select('code_enfant', 'code_pere', 'code_mere', 'code_declarant', 'code_adoptant')
            ->get()
            ->flatMap(fn ($row) => [
                $row->code_enfant,
                $row->code_pere,
                $row->code_mere,
                $row->code_declarant,
                $row->code_adoptant,
            ]);

        $acteCodes = DB::table('t_acte_naissance')->pluck('niupp');

        $this->disableForeignKeyChecks();

        DB::table('t_acte_naissance')->truncate();
        DB::table('t_mouvement_naissance')->truncate();
        DB::table('t_declaration_naissance')->truncate();

        $this->purgeFeuilletsForActeCodes(collect($acteCodes));
        $this->purgePersonneAnnexes(collect($personCodes));

        $this->enableForeignKeyChecks();
    }

    protected function purgeDecesDemoData(): void
    {
        $personCodes = DB::table('t_declaration_deces')
            ->select('code_defunt', 'code_pere', 'code_mere', 'code_declarant', 'code_conjoint')
            ->get()
            ->flatMap(fn ($row) => [
                $row->code_defunt,
                $row->code_pere,
                $row->code_mere,
                $row->code_declarant,
                $row->code_conjoint,
            ]);

        $acteCodes = DB::table('t_acte_deces')->pluck('code_acte_deces');

        $this->disableForeignKeyChecks();

        DB::table('t_acte_deces')->truncate();
        DB::table('t_mouvement_deces')->truncate();
        DB::table('t_ddecescause')->truncate();
        DB::table('t_declaration_deces')->truncate();

        $this->purgeFeuilletsForActeCodes(collect($acteCodes));
        $this->purgePersonneAnnexes(collect($personCodes));

        $this->enableForeignKeyChecks();
    }

    protected function purgeMariageDemoData(): void
    {
        $personCodes = DB::table('t_declaration_mariage')
            ->select(
                'code_epoux',
                'code_epouse',
                'code_temoin_homme_epoux',
                'code_temoin_femme_epoux',
                'code_temoin_homme_epouse',
                'code_temoin_femme_epouse'
            )
            ->get()
            ->flatMap(fn ($row) => [
                $row->code_epoux,
                $row->code_epouse,
                $row->code_temoin_homme_epoux,
                $row->code_temoin_femme_epoux,
                $row->code_temoin_homme_epouse,
                $row->code_temoin_femme_epouse,
            ]);

        $acteCodes = DB::table('t_acte_mariage')->pluck('code_acte_mariage');

        $this->disableForeignKeyChecks();

        DB::table('t_mouvement_mariage')->delete();
        DB::table('t_acte_mariage')->delete();
        DB::table('t_declaration_mariage')->delete();

        $this->purgeFeuilletsForActeCodes(collect($acteCodes));

        $filtered = $this->filterDemoPersonCodes(collect($personCodes));
        if ($filtered->isNotEmpty()) {
            DB::table('t_document')->whereIn('code_personne', $filtered)->delete();
        }

        $this->purgePersonneAnnexes(collect($personCodes));

        $this->enableForeignKeyChecks();
    }
}
