<?php

namespace Tests\Feature\DemandeDocument;

use App\Models\User;
use App\Services\DemandeDocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Mobile\Entities\DemandeDocument;
use Tests\TestCase;

class CreationDemandeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test création d'une demande sur site
     */
    public function test_creation_demande_sur_site()
    {
        // Simuler un utilisateur authentifié avec affectation
        $user = User::factory()->create();
        // TODO: Créer l'affectation avec institution

        $service = app(DemandeDocumentService::class);

        $data = [
            'nom_demandeur' => 'DUPONT',
            'prenom_demandeur' => 'Jean',
            'sexe_demandeur' => 'M',
            'telephone_demandeur' => '0600000000',
            'email_demandeur' => 'jean.dupont@example.com',
            'numero_acte' => 'TEST123',
            'code_type_acte' => 'TAC_0001',
            'code_type_document_demande' => 'TDD_0001',
        ];

        $demande = $service->creerDemandeSurSite($data, $user);

        $this->assertInstanceOf(DemandeDocument::class, $demande);
        $this->assertEquals('sur_site', $demande->origine_demande);
        $this->assertEquals('En traitement', $demande->statut);
        $this->assertEquals('DUPONT', $demande->nom_demandeur);
    }

    /**
     * Test création d'une demande portail
     */
    public function test_creation_demande_portail()
    {
        $service = app(DemandeDocumentService::class);

        $data = [
            'nom_demandeur' => 'MARTIN',
            'prenom_demandeur' => 'Marie',
            'sexe_demandeur' => 'F',
            'telephone_demandeur' => '0700000000',
            'email_demandeur' => 'marie.martin@example.com',
            'numero_acte' => 'TEST456',
            'code_type_acte' => 'TAC_0001',
            'code_type_document_demande' => 'TDD_0002',
            'code_institution' => null,
        ];

        $demande = $service->creerDemandePortail($data);

        $this->assertInstanceOf(DemandeDocument::class, $demande);
        $this->assertEquals('portail', $demande->origine_demande);
        $this->assertEquals('En attente de paiement', $demande->statut);
        $this->assertEquals('MARTIN', $demande->nom_demandeur);
    }

    /**
     * Test calcul du prix
     */
    public function test_calcul_prix()
    {
        $service = app(DemandeDocumentService::class);

        // Test prix copie (devrait fallback sur 5000 FCFA si pas de tarif)
        $prixCopie = $service->calculerPrix('TDD_0001', 'TAC_0001');
        $this->assertGreaterThan(0, $prixCopie);

        // Test prix extrait (devrait fallback sur 3000 FCFA si pas de tarif)
        $prixExtrait = $service->calculerPrix('TDD_0002', 'TAC_0001');
        $this->assertGreaterThan(0, $prixExtrait);
    }
}
