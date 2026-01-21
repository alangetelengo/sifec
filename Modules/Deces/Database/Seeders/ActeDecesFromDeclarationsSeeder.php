<?php

namespace Modules\Deces\Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Deces\Services\ActeDecesService;
use Modules\Deces\Services\MouvementService;
use Illuminate\Support\Facades\Log;

class ActeDecesFromDeclarationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command?->info('Démarrage de la génération automatique des actes de décès…');

        $formationSanitaireUser = User::where('email', 'sandrine@gmail.com')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (!$formationSanitaireUser || !$formationSanitaireUser->affectationActive()) {
            $this->command?->warn("Utilisateur formation sanitaire introuvable ou sans affectation active (sandrine@gmail.com).");
            return;
        }

        $centreEtatCivilUser = User::where('email', 'agentpfbz@gmail.com')
            ->with(['affectations' => fn ($q) => $q->where('active', 1)])
            ->first();

        if (!$centreEtatCivilUser || !$centreEtatCivilUser->affectationActive()) {
            $this->command?->warn("Utilisateur centre d'état civil introuvable ou sans affectation active (agentpfbz@gmail.com).");
            return;
        }

        $affectationCentre = $centreEtatCivilUser->affectationActive();
        $registre = $affectationCentre->registres()
            ->where('code_type_registre', 'TPRG_0004')
            ->where('statut', 1)
            ->first();

        if (!$registre) {
            $this->command?->warn("Aucun registre actif (TPRG_0004) disponible pour le centre d'état civil de agentpfbz@gmail.com.");
            return;
        }

        /** @var MouvementService $mouvementService */
        $mouvementService = app(MouvementService::class);
        /** @var ActeDecesService $acteService */
        $acteService = app(ActeDecesService::class);

        $declarations = DeclarationDeces::with(['acte', 'mouvements'])
            ->where('type_declaration', 'DECLARATION DE DECES')
            ->orderBy('date_heure_declaration')
            ->get();

        if ($declarations->isEmpty()) {
            $this->command?->warn("Aucune déclaration de décès trouvée.");
            return;
        }

        $compteurActes = 0;

        foreach ($declarations as $declaration) {
            if ($declaration->acte) {
                continue;
            }

            if (!$declaration->mouvements()->where('code_mouvement', 'MOUV_0002')->exists()) {
                [$ok, $message] = $mouvementService->envoyerDeclaration(
                    $formationSanitaireUser,
                    $declaration,
                    'declaration_deces',
                    'Envoyée',
                    'Envoi automatique via seeder'
                );

                if (!$ok) {
                    $this->command?->warn("Envoi impossible pour {$declaration->code_declaration_deces} : {$message}");
                    continue;
                }
                $declaration->refresh();
            }

            $declaration->cec_approuver = 'OUI';
            $declaration->cec_approuve_par = 'CUI_00000004';
            if ($affectationCentre->cui) {
                $declaration->cec_approuve_par = $affectationCentre->cui;
            }
            $declaration->cec_approuve_le = now();
            $declaration->declarant_approuver = 'OUI';
            $declaration->code_institution_destinataire = 'INS_0047';
            $declaration->save();

            if (!$declaration->mouvements()->where('code_mouvement', 'MOUV_0019')->exists()) {
                [$ok, $message] = $mouvementService->confirmerDeclarationDeces(
                    $affectationCentre,
                    $declaration,
                    'Confirmée',
                    null,
                    'Confirmation automatique via seeder'
                );

                if (!$ok) {
                    $this->command?->warn("Confirmation impossible pour {$declaration->code_declaration_deces} : {$message}");
                    continue;
                }
            }

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
                $this->command?->info("Acte généré pour {$declaration->code_declaration_deces}.");
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel("sifec")->error("Échec génération acte {$declaration->code_declaration_deces} : {$e->getMessage()}");
                $this->command?->warn("Échec génération acte {$declaration->code_declaration_deces} : {$e->getMessage()}");
            }

            $registre->refresh();
        }

        $this->command?->info("Génération terminée : {$compteurActes} acte(s) créé(s).");
    }
}

