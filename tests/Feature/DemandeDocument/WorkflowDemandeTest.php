<?php

namespace Tests\Feature\DemandeDocument;

use App\Services\DemandeDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Mobile\Entities\DemandeDocument;
use Tests\TestCase;

class WorkflowDemandeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test transition de statut "En attente de paiement" vers "En traitement"
     */
    public function test_passage_en_traitement_apres_paiement()
    {
        $service = app(DemandeDocumentService::class);

        // Créer une demande portail
        $demande = DemandeDocument::factory()->create([
            'statut' => 'En attente de paiement',
            'origine_demande' => 'portail',
        ]);

        $result = $service->passerEnTraitement($demande);

        $this->assertTrue($result);
        $this->assertEquals('En traitement', $demande->fresh()->statut);
        $this->assertNotNull($demande->fresh()->date_traitement);
    }

    /**
     * Test passage en attente de signature
     */
    public function test_passage_en_attente_signature()
    {
        $service = app(DemandeDocumentService::class);

        // Créer une demande en traitement
        $demande = DemandeDocument::factory()->create([
            'statut' => 'En traitement',
        ]);

        $result = $service->passerEnAttenteSignature($demande);

        $this->assertTrue($result);
        $this->assertEquals('En attente de signature', $demande->fresh()->statut);
    }

    /**
     * Test rejet d'une demande
     */
    public function test_rejet_demande()
    {
        $service = app(DemandeDocumentService::class);

        $demande = DemandeDocument::factory()->create([
            'statut' => 'En traitement',
        ]);

        $motif = 'Document illisible';
        $result = $service->rejeterDemande($demande, $motif);

        $this->assertTrue($result);
        $this->assertEquals('Rejetée', $demande->fresh()->statut);
        $this->assertStringContainsString($motif, $demande->fresh()->observations);
    }

    /**
     * Test marquage comme livrée
     */
    public function test_marquage_livree()
    {
        $service = app(DemandeDocumentService::class);

        // Créer une demande traitée
        $demande = DemandeDocument::factory()->create([
            'statut' => 'Traitée',
        ]);

        $result = $service->marquerLivree($demande);

        $this->assertTrue($result);
        $this->assertEquals('Livrée', $demande->fresh()->statut);
        $this->assertNotNull($demande->fresh()->date_livraison);
    }

    /**
     * Test méthodes utilitaires de statut
     */
    public function test_methodes_statut()
    {
        $demandeEnTraitement = DemandeDocument::factory()->create(['statut' => 'En traitement']);
        $this->assertTrue($demandeEnTraitement->estEnTraitement());
        $this->assertFalse($demandeEnTraitement->estTraitee());

        $demandeTraitee = DemandeDocument::factory()->create(['statut' => 'Traitée']);
        $this->assertTrue($demandeTraitee->estTraitee());
        $this->assertFalse($demandeTraitee->estEnTraitement());

        $demandePortail = DemandeDocument::factory()->create(['origine_demande' => 'portail']);
        $this->assertTrue($demandePortail->estPortail());
        $this->assertFalse($demandePortail->estSurSite());
    }
}
