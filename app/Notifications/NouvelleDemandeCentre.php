<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
        return ['database'];
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
            'origine' => $this->demande->origine,
            'url' => route('demandeDocument.show', $this->demande->code_demande_document),
        ];
    }
}
