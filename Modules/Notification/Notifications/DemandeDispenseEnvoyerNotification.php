<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DemandeDispenseEnvoyerNotification extends Notification
{
    use Queueable;

    protected $codeDeclaration;
    protected $observation;
    protected $typeDeclaration;

    public function __construct($codeDeclaration, $observation = null, $typeDeclaration = null)
    {
        $this->codeDeclaration = $codeDeclaration;
        $this->observation = $observation;
        $this->typeDeclaration = $typeDeclaration;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Une demande de dispense a été envoyée au tribunal.",
            'observation' => $this->observation,
            'url' => route('declarationMariage.show', $this->codeDeclaration),
            'type' => 'demande_dispense_envoyee',
            'code_declaration' => $this->codeDeclaration,
        ];
    }
}
