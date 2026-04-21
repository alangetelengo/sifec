<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
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

    public function via($notifiable): array
    {
        return ['database', 'mail'];
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

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Document en attente de signature - SIFEC')
            ->greeting('Bonjour '.optional($notifiable->personne)->nomcomplet())
            ->line('Un document a été généré et nécessite votre signature électronique.')
            ->line('**Type de document :** '.$this->demande->getLibelleTypeDocument())
            ->line('**Type d\'acte :** '.$this->demande->getLibelleTypeActe())
            ->line('**Numéro d\'acte :** '.$this->demande->numero_acte)
            ->line('**Demandeur :** '.$this->demande->getNomCompletDemandeur())
            ->action('Signer le document', route('demandeDocument.show', $this->demande->code_demande_document))
            ->line('Merci de procéder à la signature dès que possible.')
            ->salutation('L\'équipe SIFEC');
    }
}
