<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Mobile\Entities\DemandeDocument;

class NouvelleDemandeCentreMail extends Mailable
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

        return $this->subject('Nouvelle demande de document — SIFEC')
            ->from(env('MAIL_USERNAME'), $fromName)
            ->markdown('mail.nouvelle_demande_centre')
            ->with([
                'demande' => $this->demande,
                'destinataire' => $this->destinataire,
                'centre' => $this->demande->institution?->lib_institution ?? "Centre d'état civil",
                'origine' => $this->demande->estPortail() ? 'Portail public' : 'Guichet (sur site)',
                'salut' => trim((string) optional($this->destinataire->personne)->nomcomplet()),
                'urlDemande' => route('demandeDocument.show', $this->demande->code_demande_document),
            ]);
    }
}
