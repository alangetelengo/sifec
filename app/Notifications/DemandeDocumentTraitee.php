<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Mobile\Entities\DemandeDocument;

class DemandeDocumentTraitee extends Notification
{
    use Queueable;

    protected $demande;

    /**
     * Create a new notification instance.
     */
    public function __construct(DemandeDocument $demande)
    {
        $this->demande = $demande;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre demande de document est prête - SIFEC')
            ->greeting('Bonjour '.$this->demande->getNomCompletDemandeur())
            ->line('Nous avons le plaisir de vous informer que votre demande de '.$this->demande->getLibelleTypeDocument().' est maintenant traitée et prête.')
            ->line('**Référence de la demande:** '.$this->demande->code_demande_document)
            ->line('**Type d\'acte:** '.$this->demande->getLibelleTypeActe())
            ->line('**Numéro d\'acte:** '.$this->demande->numero_acte)
            ->line('Vous pouvez vous présenter au '.optional($this->demande->institution)->lib_institution.' pour retirer votre document.')
            ->line('Merci de votre confiance.')
            ->salutation('L\'équipe SIFEC');
    }
}
