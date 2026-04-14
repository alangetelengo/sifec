<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorBulkMailable extends Mailable
{
    use Queueable, SerializesModels;

    public User   $user;
    public array  $recoveryCodes;
    public string $action;       // 'enabled' | 'disabled'
    public ?string $rawSecret;   // Clé secrète TOTP en clair (pour affichage manuel)
    public ?string $qrCodeUrl;   // URL de l'image QR code (otpauth://)

    public function __construct(
        User    $user,
        array   $recoveryCodes,
        string  $action     = 'enabled',
        ?string $rawSecret  = null,
        ?string $qrCodeUrl  = null
    ) {
        $this->user          = $user;
        $this->recoveryCodes = $recoveryCodes;
        $this->action        = $action;
        $this->rawSecret     = $rawSecret;
        $this->qrCodeUrl     = $qrCodeUrl;
    }

    public function build(): static
    {
        $subject = $this->action === 'enabled'
            ? '🔐 Votre double authentification (2FA) a été activée — SIFEC'
            : '🔓 Votre double authentification (2FA) a été désactivée — SIFEC';

        $mail = $this
            ->subject($subject)
            ->from(env('MAIL_USERNAME'), env('SUBJECT', 'SIFEC — État Civil'))
            ->view('emails.two-factor-bulk');

        // Joindre le fichier de codes de récupération uniquement à l'activation
        if ($this->action === 'enabled' && count($this->recoveryCodes) > 0) {
            $nom      = strtoupper($this->user->personne->nom ?? 'UTILISATEUR');
            $fileName = 'SIFEC_codes_recuperation_' . $nom . '_' . now()->format('Ymd') . '.txt';
            $mail->attachData($this->buildRecoveryCodesFile(), $fileName, ['mime' => 'text/plain']);
        }

        return $mail;
    }

    /**
     * Génère le contenu textuel du fichier de codes de récupération.
     */
    private function buildRecoveryCodesFile(): string
    {
        $nom    = strtoupper($this->user->personne->nom ?? 'N/A');
        $prenom = $this->user->personne->prenom ?? '';
        $email  = $this->user->emailForTwoFactorMail() ?? $this->user->email;
        $date   = now()->format('d/m/Y à H:i');

        $lines   = [];
        $lines[] = '╔══════════════════════════════════════════════════════════╗';
        $lines[] = '║       SIFEC — Système d\'Information de l\'État Civil      ║';
        $lines[] = '║         CODES DE RÉCUPÉRATION — DOUBLE AUTHENTIFICATION  ║';
        $lines[] = '╚══════════════════════════════════════════════════════════╝';
        $lines[] = '';
        $lines[] = 'Utilisateur : ' . $nom . ' ' . $prenom;
        $lines[] = 'Email       : ' . $email;
        $lines[] = 'Généré le   : ' . $date;
        $lines[] = '';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = 'CLEF SECRÈTE (saisie manuelle dans Google Authenticator)';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = '';
        $lines[] = '  ' . ($this->rawSecret ?? 'N/A');
        $lines[] = '';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = 'VOS 8 CODES DE RÉCUPÉRATION (usage unique chacun)';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = '';

        foreach ($this->recoveryCodes as $index => $code) {
            $lines[] = sprintf('  %d.  %s', $index + 1, $code);
        }

        $lines[] = '';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = 'IMPORTANT :';
        $lines[] = '  • Conservez ce fichier dans un endroit sûr et confidentiel.';
        $lines[] = '  • Chaque code de récupération ne peut être utilisé qu\'UNE SEULE FOIS.';
        $lines[] = '  • Utilisez-les uniquement si vous n\'avez plus accès à';
        $lines[] = '    votre application d\'authentification (Google Authenticator).';
        $lines[] = '  • Ne partagez jamais ces codes avec quiconque.';
        $lines[] = '──────────────────────────────────────────────────────────';
        $lines[] = '';
        $lines[] = 'République du Congo — Ministère de l\'Intérieur';
        $lines[] = 'Direction Générale de l\'Administration du Territoire';

        return implode("\n", $lines);
    }
}
