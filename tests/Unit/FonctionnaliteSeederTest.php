<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Constants\FonctionnaliteCodes;

class FonctionnaliteSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function les_fonctionnalites_naissance_sont_bien_seedees()
    {
        $this->seed(\Database\Seeders\Naissance\FonctionnaliteNaissanceSeeder::class);

        $this->assertDatabaseHas('tr_fonctionnalite', [
            'code_fonctionnalite' => FonctionnaliteCodes::GESTION_ACTE_NAISSANCE,
            'lib_fonctionnalite' => 'Gestion des actes de naissance',
        ]);
        $this->assertDatabaseHas('tr_fonctionnalite', [
            'code_fonctionnalite' => FonctionnaliteCodes::DECLARATION_NAISSANCE_CREATE,
        ]);
    }
}
