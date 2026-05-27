<?php

namespace Modules\Naissance\Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Services\ActeNaissanceService;
use Modules\Naissance\Services\MouvementService;
use Illuminate\Support\Facades\Log;

class ActeNaissanceFromDeclarationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Démarrage de la génération automatique des actes de naissance…');

        $formationSanitaireUser = User::where('email', 'agentfs@sifec.cg')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (!$formationSanitaireUser || !$formationSanitaireUser->affectationActive()) {
            $this->command?->warn("Utilisateur formation sanitaire introuvable ou sans affectation active (agentfs@sifec.cg).");
            return;
        }

        $centreEtatCivilUser = User::where('email', 'agentcec@sifec.cg')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (!$centreEtatCivilUser || !$centreEtatCivilUser->affectationActive()) {
            $this->command?->warn("Utilisateur centre d'état civil introuvable ou sans affectation active (agentcec@sifec.cg).");
            return;
        }

        $affectationCentre = $centreEtatCivilUser->affectationActive();
        $registre = $affectationCentre->registres()
            ->where('code_type_registre', 'TPRG_0001')
            ->where('statut', 1)
            ->first();

        if (!$registre) {
            $this->command?->warn("Aucun registre actif (TPRG_0001) disponible pour le centre d'état civil de agentcec@sifec.cg.");
            return;
        }

        /** @var MouvementService $mouvementService */
        $mouvementService = app(MouvementService::class);
        /** @var ActeNaissanceService $acteService */
        $acteService = app(ActeNaissanceService::class);

        $declarations = Declarationnaissance::with(['acte', 'mouvements'])
            ->whereIn('type_declaration', ['DECLARATION DE NAISSANCE', 'CERTIFICAT DE NAISSANCE'])
            ->orderBy('date_heure_declaration')
            ->get();

        if ($declarations->isEmpty()) {
            $this->command?->warn("Aucune déclaration de naissance trouvée.");
            return;
        }

        $compteurActes = 0;

        foreach ($declarations as $declaration) {
            if ($declaration->acte) {
                continue;
            }

            $codeMouvementEnvoi = $declaration->type_declaration === 'CERTIFICAT DE NAISSANCE'
                ? 'MOUV_0035'
                : 'MOUV_0001';

            if (!$declaration->mouvements->firstWhere('code_mouvement', $codeMouvementEnvoi)) {
                [$ok, $message] = $mouvementService->envoyerDeclaration(
                    $formationSanitaireUser,
                    $declaration,
                    $codeMouvementEnvoi,
                    'Envoyée',
                    'Envoi automatique via seeder'
                );

                if (!$ok) {
                    $this->command?->warn("Envoi impossible pour {$declaration->code_declaration_naissance} : {$message}");
                    continue;
                }
                $declaration->refresh();
            }

            if (!$declaration->mouvements()->where('code_mouvement', 'MOUV_0019')->exists()) {
                [$ok, $message] = $mouvementService->confirmerDeclarationNaissance(
                    $affectationCentre,
                    $declaration,
                    'Confirmée',
                    null,
                    'Confirmation automatique via seeder'
                );

                if (!$ok) {
                    $this->command?->warn("Confirmation impossible pour {$declaration->code_declaration_naissance} : {$message}");
                    continue;
                }
            }
            $declaration->refresh();

            if ($registre->statut == 0 || ($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) <= 0) {
                $this->command?->warn("Registre saturé, arrêt de la génération des actes.");
                break;
            }

            try {
                DB::beginTransaction();

                $acteService->genererActe($declaration, $registre, $centreEtatCivilUser);
                $mouvementService->ajouterEvenementActe(
                    $affectationCentre,
                    $declaration,
                    'attente_approbation',
                    'Acte généré automatiquement via seeder'
                );

                DB::commit();
                $compteurActes++;
                $this->command?->info("Acte généré pour {$declaration->code_declaration_naissance}.");
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel("sifec")->error("Échec génération acte {$declaration->code_declaration_naissance} : {$e->getMessage()}");
                $this->command?->warn("Échec génération acte {$declaration->code_declaration_naissance} : {$e->getMessage()}");
            }

            $registre->refresh();
        }

        $this->command?->info("Génération terminée : {$compteurActes} acte(s) créé(s).");
    }
}

