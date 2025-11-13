<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FormulaireTypeValideNotification extends Notification
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
        $typeLabel = $this->typeDeclaration ? " (" . $this->typeDeclaration . ")" : "";

        return [
            'message' => "Un formulaire type de mariage a été validé et est prêt pour la génération de l'acte.",
            'observation' => $this->observation,
            'url' => route('declarationMariage.show', $this->codeDeclaration),
            'type' => 'formulaire_valide',
            'code_declaration' => $this->codeDeclaration,
        ];
    }
}
