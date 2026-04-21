<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Mobile\Entities\DemandeDocument;

class DemandeDocumentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demande;

    public $ancienStatut;

    public $nouveauStatut;

    /**
     * Create a new event instance.
     */
    public function __construct(DemandeDocument $demande, string $ancienStatut, string $nouveauStatut)
    {
        $this->demande = $demande;
        $this->ancienStatut = $ancienStatut;
        $this->nouveauStatut = $nouveauStatut;
    }
}
