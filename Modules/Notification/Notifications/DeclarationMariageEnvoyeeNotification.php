<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeclarationMariageEnvoyeeNotification extends Notification
{
    use Queueable;

    public $declaration;
    public $institution;
    public $action; // 'envoyée' ou 'renvoyée'
    public $message;

    /**
     * @param $declaration : instance de DeclarationMariage
     * @param $institution : instance d'Institution destinataire
     * @param string $action : 'envoyée' ou 'renvoyée'
     * @param string|null $message : message personnalisé (optionnel)
     */
    public function __construct($declaration, $institution, $action = 'envoyée', $message = null)
    {
        $this->declaration = $declaration;
        $this->institution = $institution;
        $this->action = $action;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $categorie = $this->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution ?? 'institution';
        $codeCategorie = $this->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins ?? '';

        // Récupérer les noms des époux
        $epoux = $this->declaration->epoux ? $this->declaration->epoux->nomcomplet() : 'Époux inconnu';
        $epouse = $this->declaration->epouse ? $this->declaration->epouse->nomcomplet() : 'Épouse inconnue';
        $couple = $epoux . ' et ' . $epouse;

        // URL vers la déclaration de mariage
        $url = route('declarationMariage.show', $this->declaration->code_declaration_mariage);

        // Construire le message selon le type d'institution
        if ($this->message) {
            $message = $this->message;
        } else {
            // Message différent selon le type d'institution
            if ($codeCategorie === 'TCINS_0002') { // Tribunal
                $message = "Dossier reçu pour {$couple}.";
            } else { // Centre d'état civil
                $message = "Demande de dispense envoyée au tribunal pour {$couple}.";
            }
        }

        return [
            'message' => $message,
            'code_declaration' => $this->declaration->code_declaration_mariage,
            'personne' => $couple,
            'url' => $url,
            'action' => $this->action,
            'institution_type' => $categorie,
            'document_type' => 'mariage',
            'document_details' => 'Formulaire type de mariage',
        ];
    }

    public function toArray($notifiable)
    {
        $categorie = $this->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution ?? 'institution';
        $codeCategorie = $this->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins ?? '';

        // Récupérer les noms des époux
        $epoux = $this->declaration->epoux ? $this->declaration->epoux->nomcomplet() : 'Époux inconnu';
        $epouse = $this->declaration->epouse ? $this->declaration->epouse->nomcomplet() : 'Épouse inconnue';
        $couple = $epoux . ' et ' . $epouse;

        $url = route('declarationMariage.show', $this->declaration->code_declaration_mariage);

        // Message selon le type d'institution
        $message = '';
        if ($codeCategorie === 'TCINS_0002') { // Tribunal
            $message = "Dossier reçu pour {$couple}.";
        } else { // Centre d'état civil
            $message = "Demande de dispense envoyée au tribunal pour {$couple}.";
        }

        return [
            'message' => $message,
            'url' => $url,
            'action' => $this->action,
            'institution_type' => $categorie,
            'document_type' => 'mariage',
            'document_details' => 'Formulaire type de mariage',
            'personne' => $couple,
        ];
    }
}
