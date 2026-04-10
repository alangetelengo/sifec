<?php

namespace Tests\Unit;

use Tests\TestCase;
use Modules\Naissance\Entities\Declarationnaissance;

/**
 * Sans base : vérifie que le libellé métier utilisé pour le mapping contrôleur → MouvementService
 * est bien exposé pour un certificat de naissance.
 */
class DeclarationNaissanceTypePourMappingMouvementTest extends TestCase
{
    /** @test */
    public function certificat_de_naissance_expose_la_cle_de_mapping(): void
    {
        $declaration = new Declarationnaissance();
        $declaration->type_declaration = 'CERTIFICAT DE NAISSANCE';
        $declaration->type_declaration_origine = null;

        $this->assertSame('CERTIFICAT DE NAISSANCE', $declaration->typePourMappingMouvement());
    }

    /** @test */
    public function declaration_de_naissance_standard_sans_origine_reste_sur_declaration(): void
    {
        $declaration = new Declarationnaissance();
        $declaration->type_declaration = 'DECLARATION DE NAISSANCE';
        $declaration->type_declaration_origine = null;

        $this->assertSame('DECLARATION DE NAISSANCE', $declaration->typePourMappingMouvement());
    }
}
