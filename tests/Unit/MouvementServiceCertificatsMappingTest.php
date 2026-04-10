<?php

namespace Tests\Unit;

use Tests\TestCase;

/**
 * Contrat sans base : les services doivent mapper les événements métier vers les bons codes tr_mouvement.
 */
class MouvementServiceCertificatsMappingTest extends TestCase
{
    /** @test */
    public function naissance_service_lie_certificat_naissance_a_mouv_0033(): void
    {
        $src = file_get_contents(base_path('Modules/Naissance/Services/MouvementService.php'));

        $this->assertStringContainsString("'certificat_naissance'", $src);
        $this->assertStringContainsString("'code' => 'MOUV_0033'", $src);
    }

    /** @test */
    public function deces_service_lie_constatation_a_mouv_2005(): void
    {
        $src = file_get_contents(base_path('Modules/Deces/Services/MouvementService.php'));

        $this->assertStringContainsString("'certificat_constatation_deces'", $src);
        $this->assertStringContainsString("'code' => 'MOUV_2005'", $src);
    }
}
