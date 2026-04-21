<?php

namespace App\Jobs;

use App\Mail\OtpDemandeDocumentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ValidationDemandeDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nomSignataire;

    private $nbDemandes;

    private $codeOtp;

    private $to;

    /**
     * Create a new job instance.
     */
    public function __construct(string $nomSignataire, int $nbDemandes, string $codeOtp, string $to)
    {
        $this->nomSignataire = $nomSignataire;
        $this->nbDemandes = $nbDemandes;
        $this->codeOtp = $codeOtp;
        $this->to = $to;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $to = trim((string) $this->to);

        if ($to === '' || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::channel('sifec')->warning('ValidationDemandeDocumentJob : destinataire e-mail ignoré (vide ou invalide).', [
                'to_masque' => $to === '' ? '(vide)' : substr($to, 0, 3).'…',
            ]);

            return;
        }

        Log::channel('sifec')->info('Envoi e-mail OTP signature demande document', [
            'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
            'nb_demandes' => $this->nbDemandes,
        ]);

        try {
            Mail::to($to)->send(new OtpDemandeDocumentMail($this->nomSignataire, $this->nbDemandes, $this->codeOtp));

            Log::channel('sifec')->info('E-mail OTP signature demande document : envoi SMTP terminé sans exception.', [
                'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
            ]);
        } catch (\Throwable $e) {
            Log::channel('sifec')->error('Échec envoi e-mail OTP signature demande document', [
                'to_masque' => preg_replace('/(^.).*(@.*$)/', '$1…$2', $to),
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
