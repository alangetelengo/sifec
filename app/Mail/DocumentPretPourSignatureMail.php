<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Mobile\Entities\DemandeDocument;

class DocumentPretPourSignatureMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private DemandeDocument $demande,
        private User $destinataire
    ) {}

    public function build()
    {
        $this->demande->loadMissing('institution');

        $fromName = env('SUBJECT', config('app.name'));

        return $this->subject('Document en attente de signature — SIFEC')
            ->from(env('MAIL_USERNAME'), $fromName)
            ->markdown('mail.document_pret_pour_signature')
            ->with([
                'demande' => $this->demande,
                'destinataire' => $this->destinataire,
                'salut' => trim((string) optional($this->destinataire->personne)->nomcomplet()),
                'urlDemande' => route('demandeDocument.show', $this->demande->code_demande_document),
            ]);
    }
}
