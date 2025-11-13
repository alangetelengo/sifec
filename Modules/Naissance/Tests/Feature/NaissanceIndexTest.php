<?php

namespace Modules\Naissance\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\InstitutionUser;
use Illuminate\Support\Facades\Notification;
use Modules\Referentiel\Entities\Institution;
use Modules\Naissance\Services\MouvementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Naissance\Entities\Declarationnaissance;
use App\Notifications\CertificatEnvoyeAuTribunalNotification;
use Modules\Naissance\Http\Controllers\CertificatNonInscriptionController;

class NaissanceIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function envoi_declaration_au_tribunal_met_a_jour_le_destinataire_et_notifie_les_agents()
    {
        // Préparation des données
        Notification::fake();

        // Création d'une institution tribunal et d'une institution d'origine
        $tribunal = Institution::factory()->create();
        $origine = Institution::factory()->create(['institutionParent' => $tribunal->code_institution]);

        // Création d'utilisateurs pour le tribunal
        $agents = InstitutionUser::factory()->count(2)->create(['code_institution' => $tribunal->code_institution]);
        foreach ($agents as $agent) {
            $user = User::factory()->create();
            $agent->user()->associate($user);
            $agent->save();
        }

        // Création d'une déclaration
        $declaration = Declarationnaissance::factory()->create([
            'code_institution' => $origine->code_institution,
        ]);

        // Simuler un utilisateur connecté
        $user = InstitutionUser::factory()->create(['code_institution' => $origine->code_institution]);
        $this->actingAs($user->user);

        // Appel du contrôleur
        $service = new MouvementService();
        $controller = new CertificatNonInscriptionController();

        $response = $this->call('POST', route('certificatNonInscription.envoyerAuTribunal', $declaration->getKey()), [
            // paramètres nécessaires
        ]);

        // Vérifier que le champ code_institution_dest est bien mis à jour
        $declaration->refresh();
        $this->assertEquals($tribunal->code_institution, $declaration->code_institution_dest);

        // Vérifier que tous les agents du tribunal ont reçu la notification
        Notification::assertSentTo(
            $agents->map->user,
            CertificatEnvoyeAuTribunalNotification::class
        );
    }
}
