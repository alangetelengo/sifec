<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FA\Google2FA;

class Debug2FA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:2fa-debug {email : Email de l\'utilisateur}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostic complet de la 2FA pour un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        // Rechercher l'utilisateur
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Utilisateur non trouvé : {$email}");
            return 1;
        }

        $this->info("═══════════════════════════════════════════════════════");
        $this->info("   🔍 DIAGNOSTIC 2FA - {$user->email}");
        $this->info("═══════════════════════════════════════════════════════");
        $this->line("");

        // Informations utilisateur
        $this->info("👤 INFORMATIONS UTILISATEUR");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("Email          : {$user->email}");
        $this->line("Code User      : {$user->code_user}");
        $this->line("Nom            : " . ($user->personne->nom ?? 'N/A'));
        $this->line("");

        // État de la 2FA
        $this->info("🔐 ÉTAT DE LA 2FA");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $enabled = $user->google2fa_enabled ? '<fg=green>✅ OUI</>' : '<fg=red>❌ NON</>';
        $this->line("Activée        : {$enabled}");

        $hasSecret = !empty($user->google2fa_secret) ? '<fg=green>✅ OUI</>' : '<fg=red>❌ NON</>';
        $this->line("Secret présent : {$hasSecret}");

        if (!empty($user->google2fa_secret)) {
            try {
                $decrypted = decrypt($user->google2fa_secret);
                $secretLength = strlen($decrypted);
                $this->line("Longueur secret: <fg=green>{$secretLength} caractères</>");
                $this->line("Secret (clair)  : <fg=yellow>{$decrypted}</>");

                // Générer le code TOTP actuel pour comparaison
                $google2fa = new Google2FA();
                $currentCode = $google2fa->getCurrentOtp($decrypted);
                $this->line("");
                $this->info("🔢 CODE TOTP ACTUEL (pour test)");
                $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
                $this->line("Code attendu    : <fg=cyan>{$currentCode}</>");
                $this->line("Temps restant   : " . (30 - (time() % 30)) . " secondes");
                $this->line("");
                $this->line("<fg=yellow>💡 Comparez ce code avec celui affiché par Google Authenticator</>");
            } catch (\Exception $e) {
                $this->line("Longueur secret: <fg=red>❌ Erreur de décryptage</>");
                $this->line("Erreur: " . $e->getMessage());
            }
        }

        $verified = $user->two_factor_verified_at
            ? '<fg=green>✅ ' . $user->two_factor_verified_at->format('d/m/Y H:i:s') . '</>'
            : '<fg=red>❌ Jamais</>';
        $this->line("Vérifié le     : {$verified}");

        // Codes de récupération
        $recoveryCodes = $user->getRecoveryCodes();
        $codesCount = count($recoveryCodes);
        $codesStatus = $codesCount > 0 ? "<fg=green>✅ {$codesCount} code(s)</>" : '<fg=red>❌ Aucun</>';
        $this->line("Codes récup.   : {$codesStatus}");
        $this->line("");

        // Affectation et permissions
        if ($user->affectationActive()) {
            $this->info("🏢 AFFECTATION ACTIVE");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $affectation = $user->affectationActive();
            $this->line("Institution    : " . ($affectation->institution->lib_institution ?? 'N/A'));
            $this->line("Fonction       : " . ($affectation->fonction->lib_fonction ?? 'N/A'));
            $this->line("");
        }

        // Sessions actives (si la table existe)
        try {
            $this->info("💻 SESSIONS ACTIVES");
            $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $sessions = DB::table('sessions')
                ->where('user_id', $user->id)
                ->get();

            if ($sessions->isEmpty()) {
                $this->line("<fg=yellow>Aucune session active</>");
            } else {
                foreach ($sessions as $session) {
                    $lastActivity = date('d/m/Y H:i:s', $session->last_activity);
                    $this->line("Session : {$session->id}");
                    $this->line("  └─ Dernière activité : {$lastActivity}");
                }
            }
            $this->line("");
        } catch (\Exception $e) {
            // Table sessions n'existe pas, ignorer
            $this->line("");
        }

        // Historique des actions 2FA
        $this->info("📋 HISTORIQUE 2FA (dernières actions)");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $auditLogs = DB::table('tr_user_audit_trail')
            ->where('code_user', $user->code_user)
            ->whereIn('action', ['2fa_enabled', '2fa_disabled', '2fa_reset', '2fa_verified', 'recovery_codes_regenerated'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($auditLogs->isEmpty()) {
            $this->line("<fg=yellow>Aucune action 2FA enregistrée</>");
        } else {
            foreach ($auditLogs as $log) {
                $date = date('d/m/Y H:i:s', strtotime($log->created_at));
                $action = str_pad($log->action, 25);
                $this->line("{$date} | {$action} | {$log->description}");
            }
        }
        $this->line("");

        // Recommandations
        $this->info("💡 RECOMMANDATIONS");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        if (!$user->google2fa_enabled && empty($user->google2fa_secret)) {
            $this->line("✅ État normal : Prêt pour une nouvelle activation");
            $this->line("   Commande : <fg=cyan>php artisan user:2fa-enable {$email}</>");
        } elseif (!$user->google2fa_enabled && !empty($user->google2fa_secret)) {
            $this->line("⚠️  État incohérent : Secret présent mais 2FA désactivée");
            $this->line("   Solution : <fg=cyan>php artisan user:2fa {$email} reset</>");
        } elseif ($user->google2fa_enabled && empty($user->google2fa_secret)) {
            $this->line("❌ État invalide : 2FA activée mais pas de secret");
            $this->line("   Solution : <fg=cyan>php artisan user:2fa {$email} reset</>");
        } else {
            $this->line("✅ État normal : 2FA activée et fonctionnelle");
        }

        $this->line("");
        $this->info("═══════════════════════════════════════════════════════");

        return 0;
    }
}

