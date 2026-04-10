<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification base de données : création d'un registre par un CEC (agents du tribunal concernés).
 */
class CreationRegistreParCecNotification extends Notification
{
    use Queueable;

    public $registre;

    public string $cecLib;

    public function __construct($registre, string $cecLib)
    {
        $this->registre = $registre;
        $this->cecLib = $cecLib;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $typeRegistre = $this->registre->typeRegistre->lib_type_registre ?? 'Registre';
        $numero = $this->registre->numeroOrdreRegistre();

        $message = "Le centre d'état civil « {$this->cecLib} » a créé un registre de {$typeRegistre} (réf. {$numero}). <br/> Consultez la liste des registres du tribunal.";

        return [
            'message' => $message,
            'code_registre' => $this->registre->code_registre,
            'type_registre' => $typeRegistre,
            'numero_ordre_registre' => $numero,
            'cec' => $this->cecLib,
            'url' => route('registre.tribunal'),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
