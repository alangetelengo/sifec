<?php

namespace Tests\Unit\DemandeDocument;

use Carbon\Carbon;
use Modules\Mobile\Entities\DemandeDocument;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * Logique métier validité / expiration sans persistance (pas de RefreshDatabase).
 */
class ValiditeModeleTest extends TestCase
{
    #[Test]
    public function est_expiree_lorsque_statut_expiree(): void
    {
        $d = new DemandeDocument(['statut' => 'Expirée']);
        $this->assertTrue($d->estExpiree());
        $this->assertFalse($d->estTraitee());
    }

    #[Test]
    public function document_encore_valide_dans_la_plage(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-06-15 12:00:00'));

        $d = new DemandeDocument([
            'document_valide_de' => Carbon::parse('2026-06-01 00:00:00'),
            'document_valide_jusquau' => Carbon::parse('2026-06-30 23:59:59'),
        ]);

        $this->assertTrue($d->documentEstEncoreValide());

        Carbon::setTestNow();
    }

    #[Test]
    public function document_non_valide_hors_plage(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-07-01 12:00:00'));

        $d = new DemandeDocument([
            'statut' => 'Traitée',
            'document_valide_de' => Carbon::parse('2026-06-01'),
            'document_valide_jusquau' => Carbon::parse('2026-06-30 23:59:59'),
        ]);

        $this->assertFalse($d->documentEstEncoreValide());
        $this->assertTrue($d->documentPerimeSansChangementStatut());

        Carbon::setTestNow();
    }

    #[Test]
    public function document_perime_sans_changement_statut_false_si_deja_expiree(): void
    {
        $d = new DemandeDocument([
            'statut' => 'Expirée',
            'document_valide_jusquau' => now()->subDay(),
        ]);

        $this->assertFalse($d->documentPerimeSansChangementStatut());
    }
}
