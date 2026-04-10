<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Database\Seeders\TrMouvementCertificatsNaissanceDecesSeeder;

class TrMouvementCertificatsNaissanceDecesSeederTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function le_seeder_insere_mouv_0033_0034_et_mouv_2005(): void
    {
        $this->seed(TrMouvementCertificatsNaissanceDecesSeeder::class);

        $this->assertDatabaseHas('tr_mouvement', [
            'code_mouvement' => 'MOUV_0033',
            'lib_mouvement' => 'Certificat de naissance enregistré',
        ]);
        $this->assertDatabaseHas('tr_mouvement', [
            'code_mouvement' => 'MOUV_0034',
            'lib_mouvement' => 'Certificat transformé en déclaration de naissance',
        ]);
        $this->assertDatabaseHas('tr_mouvement', [
            'code_mouvement' => 'MOUV_2005',
            'lib_mouvement' => 'Certificat de constatation de décès enregistré',
        ]);
    }

    /** @test */
    public function le_seeder_est_idempotent(): void
    {
        $this->seed(TrMouvementCertificatsNaissanceDecesSeeder::class);
        $this->seed(TrMouvementCertificatsNaissanceDecesSeeder::class);

        $this->assertSame(1, DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0033')->count());
        $this->assertSame(1, DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_0034')->count());
        $this->assertSame(1, DB::table('tr_mouvement')->where('code_mouvement', 'MOUV_2005')->count());
    }
}
