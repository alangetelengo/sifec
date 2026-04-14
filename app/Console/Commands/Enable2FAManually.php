<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserAuditTrail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;

class Enable2FAManually extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:2fa-enable 
                            {email : Email de l\'utilisateur}
                            {--show-secret : Afficher le secret en clair}
                            {--force : Forcer l\'activation sans vérification}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activer manuellement la 2FA pour un utilisateur (avec QR code)';

    protected $google2fa;

    public function __construct()
    {
        parent::__construct();
        $this->google2fa = new Google2FA();
    }

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

        $this->info("👤 Utilisateur trouvé : {$user->email}");
        $this->info("📋 Code utilisateur : {$user->code_user}");
        $this->line("");

        // Vérifier l'état actuel
        if ($user->google2fa_enabled) {
            $this->warn("⚠️  La 2FA est déjà activée pour cet utilisateur.");
            
            if (!$this->confirm('Voulez-vous la reconfigurer ?')) {
                return 0;
            }
        }

        // Générer un nouveau secret
        $secret = $this->google2fa->generateSecretKey();
        $user->google2fa_secret = encrypt($secret);
        $user->save();

        $this->info("✅ Nouveau secret généré");
        $this->line("");

        // Afficher le secret
        if ($this->option('show-secret')) {
            $this->line("🔑 Secret : <fg=yellow>{$secret}</>");
            $this->line("");
        }

        // Générer l'URL du QR Code
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->twoFactorAccountLabel(),
            $secret
        );

        $this->info("📱 QR Code URL à scanner :");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line($qrCodeUrl);
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("");

        // Afficher les instructions
        $this->info("📝 Instructions :");
        $this->line("1. Ouvrez votre application d'authentification (Google Authenticator, etc.)");
        $this->line("2. Scannez le QR code ci-dessus");
        $this->line("3. Entrez le code à 6 chiffres généré");
        $this->line("");

        // Option de forçage
        if ($this->option('force')) {
            return $this->forceEnable($user);
        }

        // Demander le code de vérification
        $code = $this->ask('Entrez le code à 6 chiffres (ou "skip" pour forcer l\'activation)');

        if (strtolower($code) === 'skip') {
            return $this->forceEnable($user);
        }

        // Vérifier le code
        $valid = $this->google2fa->verifyKey($secret, $code);

        if (!$valid) {
            $this->error("❌ Code invalide !");
            $this->warn("💡 Astuce : Le code change toutes les 30 secondes.");
            
            if ($this->confirm('Voulez-vous forcer l\'activation quand même ?')) {
                return $this->forceEnable($user);
            }
            
            return 1;
        }

        // Activer la 2FA
        $user->google2fa_enabled = true;
        $user->two_factor_verified_at = now();
        $user->save();

        // Générer les codes de récupération
        $recoveryCodes = $user->generateRecoveryCodes();

        $this->info("✅ 2FA activée avec succès !");
        $this->line("");

        // Afficher les codes de récupération
        $this->displayRecoveryCodes($recoveryCodes);

        // Audit trail
        Log::channel('sifec')->info("2FA activée manuellement via commande Artisan pour {$user->email}");
        UserAuditTrail::log($user->code_user, '2fa_enabled', "Double authentification activée via commande Artisan (manuelle)");

        // Générer le fichier HTML
        $this->generateHtmlFile($user, $secret, $recoveryCodes);

        return 0;
    }

    /**
     * Forcer l'activation sans vérification
     */
    protected function forceEnable(User $user)
    {
        $this->warn("⚠️  Activation forcée sans vérification du code");
        
        if (!$this->confirm('Êtes-vous sûr ?')) {
            $this->info('❌ Opération annulée.');
            return 0;
        }

        $user->google2fa_enabled = true;
        $user->two_factor_verified_at = now();
        $user->save();

        // Générer les codes de récupération
        $recoveryCodes = $user->generateRecoveryCodes();

        $this->info("✅ 2FA activée avec succès (mode forcé) !");
        $this->line("");

        // Afficher les codes de récupération
        $this->displayRecoveryCodes($recoveryCodes);

        // Audit trail
        Log::channel('sifec')->info("2FA activée FORCÉE via commande Artisan pour {$user->email}");
        UserAuditTrail::log($user->code_user, '2fa_enabled', "Double authentification activée via commande Artisan (forcée)");

        // Générer le fichier HTML
        $secret = decrypt($user->google2fa_secret);
        $this->generateHtmlFile($user, $secret, $recoveryCodes);

        return 0;
    }

    /**
     * Afficher les codes de récupération
     */
    protected function displayRecoveryCodes(array $codes)
    {
        $this->info("🔐 Codes de récupération générés ({count} codes) :");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        foreach ($codes as $index => $code) {
            $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            $this->line("<fg=green>{$num}. {$code}</>");
        }
        
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->warn("⚠️  IMPORTANT : Conservez ces codes dans un endroit sûr !");
        $this->line("   Chaque code ne peut être utilisé qu'UNE SEULE FOIS.");
    }

    /**
     * Générer le fichier HTML
     */
    protected function generateHtmlFile(User $user, string $secret, array $recoveryCodes)
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->twoFactorAccountLabel(),
            $secret
        );

        $fileName = '2fa-activation-' . str_replace(['@', '.'], ['_', '_'], $user->twoFactorAccountLabel()) . '.html';
        $filePath = public_path($fileName);

        $data = [
            'email' => $user->emailForTwoFactorMail() ?? $user->email,
            'secret' => $secret,
            'qrCodeUrl' => $qrCodeUrl,
            'recoveryCodes' => $recoveryCodes
        ];

        $html = view('auth.two-factor.activation-manual', $data)->render();
        
        file_put_contents($filePath, $html);

        $this->line("");
        $this->info("📄 Fichier HTML généré avec succès !");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->line("📂 Emplacement : <fg=cyan>{$filePath}</>");
        $this->line("🌐 URL : <fg=cyan>" . url($fileName) . "</>");
        $this->line("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->warn("⚠️  Ce fichier contient des informations sensibles.");
        $this->line("   Envoyez-le de manière sécurisée et supprimez-le après utilisation.");
    }
}

