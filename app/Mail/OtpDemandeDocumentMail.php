<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpDemandeDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    private $nomSignataire;

    private $nbDemandes;

    private $codeOtp;

    /**
     * Create a new message instance.
     */
    public function __construct(string $nomSignataire, int $nbDemandes, string $codeOtp)
    {
        $this->nomSignataire = $nomSignataire;
        $this->nbDemandes = $nbDemandes;
        $this->codeOtp = $codeOtp;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Même en-tête From que ValidationActeNaissanceMailable
        $fromName = env('SUBJECT', config('app.name'));

        return $this->subject('Code OTP - Signature demandes de documents - SIFEC')
            ->from(env('MAIL_USERNAME'), $fromName)
            ->markdown('mail.otp_demande_document')
            ->with([
                'nomSignataire' => $this->nomSignataire,
                'nbDemandes' => $this->nbDemandes,
                'codeOtp' => $this->codeOtp,
            ]);
    }
}
