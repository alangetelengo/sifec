<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FeuilletRegistreAjouteNotification extends Notification
{
    use Queueable;

    public $registre;
    public $nombreFeuilletsAjoutes;

    /**
     * @param $registre : instance de Registre
     * @param int $nombreFeuilletsAjoutes : nombre de feuillets ajoutés au registre
     */
    public function __construct($registre, $nombreFeuilletsAjoutes)
    {
        $this->registre = $registre;
        $this->nombreFeuilletsAjoutes = $nombreFeuilletsAjoutes;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $typeRegistre = $this->registre->typeRegistre->lib_type_registre ?? 'Registre';
        $cec = $this->registre->institutionUser->institution->lib_institution ?? 'Centre d\'état civil';

        // Récupérer l'année du registre depuis la date d'ouverture
        $anneeRegistre = $this->registre->date_ouverture
            ? date('Y', strtotime($this->registre->date_ouverture))
            : date('Y');

        $message = "Le centre d'état civil {$cec} a ajouté {$this->nombreFeuilletsAjoutes} feuillet(s) au registre de {$typeRegistre} de l'année {$anneeRegistre}.";

        return [
            'message' => $message,
            'code_registre' => $this->registre->code_registre,
            'type_registre' => $typeRegistre,
            'annee_registre' => $anneeRegistre,
            'nombre_feuillets_ajoutes' => $this->nombreFeuilletsAjoutes,
            'nombre_acte_prevu' => $this->registre->nombre_acte_prevu,
            'cec' => $cec,
            'url' => route('registre.tribunal'),
        ];
    }

    public function toArray($notifiable)
    {
        $typeRegistre = $this->registre->typeRegistre->lib_type_registre ?? 'Registre';
        $cec = $this->registre->institutionUser->institution->lib_institution ?? 'Centre d\'état civil';

        // Récupérer l'année du registre depuis la date d'ouverture
        $anneeRegistre = $this->registre->date_ouverture
            ? date('Y', strtotime($this->registre->date_ouverture))
            : date('Y');

        return [
            'code_registre' => $this->registre->code_registre,
            'type_registre' => $typeRegistre,
            'annee_registre' => $anneeRegistre,
            'nombre_feuillets_ajoutes' => $this->nombreFeuilletsAjoutes,
            'nombre_acte_prevu' => $this->registre->nombre_acte_prevu,
            'cec' => $cec,
            'url' => route('registre.index'),
        ];
    }
}

