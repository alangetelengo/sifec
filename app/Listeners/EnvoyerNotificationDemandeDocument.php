<?php

namespace App\Listeners;

use App\Events\DemandeDocumentEvent;
use App\Notifications\DemandeDocumentTraitee;
use Illuminate\Support\Facades\Notification;

class EnvoyerNotificationDemandeDocument
{
    /**
     * Handle the event.
     */
    public function handle(DemandeDocumentEvent $event): void
    {
        $demande = $event->demande;

        // La notification avec PDF est gérée par OtpDemandeDocumentService::notifierDemandeurApresSignature().
        if ($event->nouveauStatut === 'Traitée' && filled($demande->chemin_document)) {
            return;
        }

        // Envoyer notification selon le nouveau statut
        if ($event->nouveauStatut === 'Traitée' && ! empty($demande->email_demandeur)) {
            Notification::route('mail', $demande->email_demandeur)
                ->notify(new DemandeDocumentTraitee($demande));
        }

        // TODO: Envoyer SMS si numéro de téléphone disponible
    }
}
