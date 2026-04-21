<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Mobile\Entities\DemandeDocument;

class NouvelleDemandeCentre extends Notification
{
    use Queueable;

    protected $demande;

    public function __construct(DemandeDocument $demande)
    {
        $this->demande = $demande;
    }

    public function via($notifiable): array
    {
        $channels = ['database'];
        $email = trim((string) ($notifiable->email ?? ''));
        if ($email !== '') {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $this->demande->loadMissing('institution');
        $centre = $this->demande->institution?->lib_institution ?? "Centre d'état civil";
        $origine = $this->demande->estPortail() ? 'Portail public' : 'Guichet (sur site)';

        $salut = trim((string) optional($notifiable->personne)->nomcomplet());
        $greeting = $salut !== '' ? 'Bonjour '.$salut : 'Bonjour,';

        return (new MailMessage)
            ->subject('Nouvelle demande de document — SIFEC')
            ->greeting($greeting)
            ->line('Une nouvelle demande de document a été enregistrée pour votre centre.')
            ->line('**Centre :** '.$centre)
            ->line('**Origine :** '.$origine)
            ->line('**Type de document :** '.$this->demande->getLibelleTypeDocument())
            ->line('**Type d\'acte :** '.$this->demande->getLibelleTypeActe())
            ->line('**Numéro d\'acte :** '.$this->demande->numero_acte)
            ->line('**Demandeur :** '.$this->demande->getNomCompletDemandeur())
            ->action('Ouvrir la demande', route('demandeDocument.show', $this->demande->code_demande_document))
            ->line('Merci de traiter cette demande dans les délais prévus.')
            ->salutation('L\'équipe SIFEC');
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => 'Nouvelle demande de '.$this->demande->getLibelleTypeDocument().
                         ' ('.($this->demande->estPortail() ? 'Portail' : 'Sur site').')',
            'type' => 'nouvelle_demande_document',
            'code_demande' => $this->demande->code_demande_document,
            'demandeur' => $this->demande->getNomCompletDemandeur(),
            'numero_acte' => $this->demande->numero_acte,
            'origine' => $this->demande->origine_demande,
            'url' => route('demandeDocument.show', $this->demande->code_demande_document),
        ];
    }
}
