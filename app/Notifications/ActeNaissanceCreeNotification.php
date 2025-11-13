<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;

class ActeNaissanceCreeNotification extends Notification
{
    public $acte;

    public function __construct($acte)
    {
        $this->acte = $acte;
    }

    public function via($notifiable)
    {
        // Envoie par mail et SMS (Vonage)
        return ['mail', 'vonage'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Acte de naissance généré')
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Votre acte de naissance a été généré avec succès.')
            ->line('Numéro unique : ' . $this->acte->niupp)
            ->action('Voir mon acte', url('/actes-naissance/' . $this->acte->id))
            ->line('Merci d\'utiliser notre service.');
    }

    public function toVonage($notifiable)
    {
        return (new VonageMessage)
            ->content('Votre acte de naissance (N° ' . $this->acte->niupp . ') a été généré avec succès.');
    }
}
