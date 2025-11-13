<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ActeDecesAValiderNotification extends Notification
{
    use Queueable;

    protected $numeroActe;
    protected $observation;

    public function __construct($numeroActe, $observation = null)
    {
        $this->numeroActe = $numeroActe;
        $this->observation = $observation;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Un acte de décès (".$this->numeroActe.") est disponible pour la validation.",
            'observation' => $this->observation,
            'url' => route('acteDeces.print.acte', $this->numeroActe),
        ];
    }
}
