<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Contrat sans base : le jeu de données TrMouvementSeeder doit conserver les codes
 * attendus par Naissance / Décès (évite TrMouvementSeeder en test DB : truncate + rollback migrations).
 */
class TrMouvementSeederCertificatsTest extends TestCase
{
    /** @test */
    public function tr_mouvement_seeder_definit_mouv_0033_et_mouv_2005(): void
    {
        $src = file_get_contents(database_path('seeders/TrMouvementSeeder.php'));

        $this->assertStringContainsString("'code_mouvement' => 'MOUV_0033'", $src);
        $this->assertStringContainsString('Certificat de naissance enregistré', $src);
        $this->assertStringContainsString("'code_mouvement' => 'MOUV_0034'", $src);
        $this->assertStringContainsString('Certificat transformé en déclaration de naissance', $src);
        $this->assertStringContainsString("'code_mouvement' => 'MOUV_2005'", $src);
        $this->assertStringContainsString('Certificat de constatation de décès enregistré', $src);
    }

    /** @test */
    public function tr_mouvement_seeder_definit_mouv_0032_declaration_deces(): void
    {
        $src = file_get_contents(database_path('seeders/TrMouvementSeeder.php'));

        $this->assertStringContainsString("'code_mouvement' => 'MOUV_0032'", $src);
        $this->assertStringContainsString('Déclaration de décès enregistrée', $src);
    }
}
