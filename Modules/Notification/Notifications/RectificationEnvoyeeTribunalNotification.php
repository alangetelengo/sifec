<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RectificationEnvoyeeTribunalNotification extends Notification
{
    use Queueable;

    public $rectification;

    public function __construct($rectification)
    {
        $this->rectification = $rectification;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Une fiche de rectification N° ' . $this->rectification->numero_rectification . ' a été envoyée au tribunal (requérant : ' . $this->rectification->nom_prenom_requerant . ').',
            'numero_rectification' => $this->rectification->numero_rectification,
            'requérant' => $this->rectification->nom_prenom_requerant,
            'url' => route('rectification.etat', $this->rectification->numero_acte),
        ];
    }
}
