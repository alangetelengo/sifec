<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Modules\Mobile\Entities\DemandeDocument;

class DocumentPretPourSignature extends Notification
{
    use Queueable;

    protected $demande;

    public function __construct(DemandeDocument $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Base de données uniquement : l’e-mail est envoyé via {@see \App\Mail\DocumentPretPourSignatureMail}
     * et {@see \Illuminate\Support\Facades\Mail} (même schéma que l’OTP signature demande document).
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Document prêt à signer : '.$this->demande->getLibelleTypeDocument().
                         ' - Acte N° '.$this->demande->numero_acte,
            'type' => 'signature_requise',
            'code_demande' => $this->demande->code_demande_document,
            'type_document' => $this->demande->getLibelleTypeDocument(),
            'type_acte' => $this->demande->getLibelleTypeActe(),
            'numero_acte' => $this->demande->numero_acte,
            'demandeur' => $this->demande->getNomCompletDemandeur(),
            'url' => route('demandeDocument.show', $this->demande->code_demande_document),
        ];
    }
}
