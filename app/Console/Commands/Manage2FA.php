<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserAuditTrail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class Manage2FA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:2fa 
                            {email : Email de l\'utilisateur}
                            {action : Action à effectuer (disable|reset|status)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gérer la double authentification d\'un utilisateur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $action = $this->argument('action');

        // Rechercher l'utilisateur
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Utilisateur non trouvé : {$email}");
            return 1;
        }

        $this->info("👤 Utilisateur trouvé : {$user->email}");
        $this->info("📋 Code utilisateur : {$user->code_user}");

        switch ($action) {
            case 'status':
                return $this->showStatus($user);

            case 'disable':
                return $this->disable2FA($user);

            case 'reset':
                return $this->reset2FA($user);

            default:
                $this->error("❌ Action invalide. Utilisez : disable, reset ou status");
                return 1;
        }
    }

    /**
     * Afficher le statut de la 2FA
     */
    protected function showStatus(User $user)
    {
        $this->info("\n📊 Statut de la 2FA :");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        if ($user->google2fa_enabled) {
            $this->line("✅ 2FA : <fg=green>Activée</>");
            $this->line("🔑 Secret : " . ($user->google2fa_secret ? "Présent" : "Absent"));
            $this->line("📅 Vérifié le : " . ($user->two_factor_verified_at ? $user->two_factor_verified_at->format('d/m/Y H:i:s') : "N/A"));
            
            $recoveryCodes = $user->getRecoveryCodes();
            $this->line("🔐 Codes de récupération : " . count($recoveryCodes) . " code(s)");
        } else {
            $this->line("❌ 2FA : <fg=red>Désactivée</>");
        }
        
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        return 0;
    }

    /**
     * Désactiver la 2FA
     */
    protected function disable2FA(User $user)
    {
        if (!$user->google2fa_enabled) {
            $this->warn("⚠️  La 2FA est déjà désactivée pour cet utilisateur.");
            return 0;
        }

        if (!$this->confirm('⚠️  Êtes-vous sûr de vouloir désactiver la 2FA pour cet utilisateur ?')) {
            $this->info('❌ Opération annulée.');
            return 0;
        }

        $user->disableTwoFactor();

        // Audit trail
        Log::channel('sifec')->info("2FA désactivée via commande Artisan pour {$user->email}");
        UserAuditTrail::log($user->code_user, '2fa_disabled', "Double authentification désactivée via commande Artisan");

        $this->info("✅ La 2FA a été désactivée avec succès pour {$user->email}");
        
        return 0;
    }

    /**
     * Réinitialiser la 2FA (désactiver complètement)
     */
    protected function reset2FA(User $user)
    {
        $this->warn("⚠️  Cette action va complètement réinitialiser la 2FA :");
        $this->line("   - Désactiver la 2FA");
        $this->line("   - Supprimer le secret");
        $this->line("   - Supprimer les codes de récupération");

        if (!$this->confirm('Continuer ?')) {
            $this->info('❌ Opération annulée.');
            return 0;
        }

        $wasEnabled = $user->google2fa_enabled;

        $user->google2fa_enabled = false;
        $user->google2fa_secret = null;
        $user->recovery_codes = null;
        $user->two_factor_verified_at = null;
        $user->save();

        // Audit trail
        Log::channel('sifec')->info("2FA réinitialisée via commande Artisan pour {$user->email}");
        UserAuditTrail::log(
            $user->code_user, 
            '2fa_reset', 
            "Double authentification réinitialisée via commande Artisan"
        );

        $this->info("✅ La 2FA a été complètement réinitialisée pour {$user->email}");
        $this->line("📌 L'utilisateur peut maintenant activer la 2FA normalement via l'interface web.");
        
        return 0;
    }
}

