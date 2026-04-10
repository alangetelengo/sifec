<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Le tribunal a validé (paraphé) le registre : information aux agents du CEC concerné.
 */
class RegistreValideParTribunalNotification extends Notification
{
    use Queueable;

    public $registre;

    public string $tribunalLib;

    public function __construct($registre, string $tribunalLib)
    {
        $this->registre = $registre;
        $this->tribunalLib = $tribunalLib;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $typeRegistre = $this->registre->typeRegistre->lib_type_registre ?? 'Registre';
        $numero = $this->registre->numeroOrdreRegistre();

        $message = "Le tribunal « {$this->tribunalLib} » a validé (paraphé) le registre de {$typeRegistre} (réf. {$numero}). <br/> Vous pouvez poursuivre les transcriptions d'actes.";

        return [
            'message' => $message,
            'code_registre' => $this->registre->code_registre,
            'type_registre' => $typeRegistre,
            'numero_ordre_registre' => $numero,
            'tribunal' => $this->tribunalLib,
            'url' => route('registre.index'),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
