<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeclarationEnvoyeeCentreNotification extends Notification
{
    use Queueable;

    public $declaration;
    public $institution;
    public $action; // 'envoyée' ou 'renvoyée'
    public $message;

    /**
     * @param $declaration : instance de Declarationnaissance, DeclarationDeces ou DeclarationMariage
     * @param $institution : instance d'Institution destinataire
     * @param string $action : 'envoyée' ou 'renvoyée'
     * @param string|null $message : message personnalisé (optionnel)
     *
     * La notification détecte automatiquement le type de déclaration (naissance, décès, mariage)
     * et si c'est une réquisition ou jugement, générant des messages explicites pour l'utilisateur notifié.
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

        // Détecter le type de déclaration (décès, naissance ou mariage)
        $isDeces = property_exists($this->declaration, 'code_declaration_deces') || isset($this->declaration->code_declaration_deces);
        $isMariage = property_exists($this->declaration, 'code_declaration_mariage') || isset($this->declaration->code_declaration_mariage);
        $typeDeclaration = $this->declaration->type_declaration ?? null;

        // Récupérer la personne concernée
        if ($isDeces) {
            $personne = $this->declaration->defunt->nomcomplet();
            if ($typeDeclaration === 'CERTIFICAT DE NON INSCRIPTION') {
                $url = route('certificatNonInscriptionDeces.displayCertificat', $this->declaration->code_declaration_deces);
            } else {
                $url = route('declarationDeces.show', $this->declaration->code_declaration_deces);
            }
        } elseif ($isMariage) {
            $personne = $this->declaration->epoux->nomcomplet() . ' et ' . $this->declaration->epouse->nomcomplet();
            $url = route('declarationMariage.show', $this->declaration->code_declaration_mariage);
        } else {
            $personne = $this->declaration->enfant->nomcomplet();
            if ($typeDeclaration === 'CERTIFICAT DE NON INSCRIPTION') {
                $url = route('certificatNonInscription.show', $this->declaration->code_declaration_naissance);
            } elseif ($typeDeclaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE") {
                $url = route('certificatDestruction.show', $this->declaration->code_declaration_naissance);
            } else {
                $url = route('declarationNaissance.show', $this->declaration->code_declaration_naissance);
            }
        }

        // Détecter si c'est une réquisition ou jugement envoyé par le tribunal
        $documentType = null;
        $documentDetails = null;
        if (!$isDeces && $this->declaration->requisition) {
            $documentType = 'réquisition';
            $documentDetails = $this->declaration->requisition->typeRequisition
                ? $this->declaration->requisition->typeRequisition->lib_type_requisition
                : 'Réquisition';
            if ($this->declaration->requisition->num_requisition) {
                $documentDetails .= " N° " . $this->declaration->requisition->num_requisition;
            }
        } elseif (!$isDeces && $this->declaration->jugement) {
            $documentType = 'jugement';
            $documentDetails = $this->declaration->jugement->typeJugement
                ? $this->declaration->jugement->typeJugement->lib_type_jugement
                : 'Jugement';
            if ($this->declaration->jugement->num_jugement) {
                $documentDetails .= " N° " . $this->declaration->jugement->num_jugement;
            }
        }

        // Construire le message selon le contexte
        if ($this->message) {
            $message = $this->message;
        } elseif ($isDeces && $typeDeclaration === 'CERTIFICAT DE NON INSCRIPTION') {
            $message = "Certificat de non inscription de décès {$this->action} au {$categorie} pour {$personne}.";
        } elseif ($isDeces) {
            $message = "Déclaration de décès {$this->action} au {$categorie} pour {$personne}.";
        } elseif ($isMariage && $typeDeclaration === 'DISPENSE') {
            $message = "Dispense de mariage {$this->action} au {$categorie} pour {$personne}.";
        } elseif ($isMariage) {
            $message = "Déclaration de mariage {$this->action} au {$categorie} pour {$personne}.";
        } elseif ($typeDeclaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE") {
            $message = "Réquisition aux fins de reconstitution de l'acte envoyée au {$categorie} pour {$personne}.";
        } elseif ($typeDeclaration === 'CERTIFICAT DE NAISSANCE') {
            if ($this->action === 'renvoyée') {
                $message = "Certificat de naissance renvoyé au {$categorie} pour {$personne}.";
            } elseif ($this->action === 'envoyée') {
                $message = "Certificat de naissance envoyé au {$categorie} pour {$personne}.";
            } else {
                $message = "Certificat de naissance ({$this->action}) au {$categorie} pour {$personne}.";
            }
        } elseif ($documentType && $this->action === 'envoyée') {
            $message = "Tribunal : {$documentType} reçue pour {$personne}. Prêt pour la transcription de l'acte.";
        } elseif ($documentType && $this->action === 'renvoyée') {
            $message = "Tribunal : {$documentType} renvoyée pour {$personne}. Vérification requise.";
        } else {
            $message = "Déclaration {$this->action} au {$categorie}.";
        }

        return [
            'message' => $message,
            'code_declaration' => $isDeces ? $this->declaration->code_declaration_deces : ($isMariage ? $this->declaration->code_declaration_mariage : $this->declaration->code_declaration_naissance),
            'personne' => $personne,
            'url' => $url,
            'action' => $this->action,
            'institution_type' => $categorie,
            'document_type' => $documentType,
            'document_details' => $documentDetails,
        ];
    }

    public function toArray($notifiable)
    {
        $categorie = $this->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution ?? 'institution';
        $isDeces = property_exists($this->declaration, 'code_declaration_deces') || isset($this->declaration->code_declaration_deces);
        $isMariage = property_exists($this->declaration, 'code_declaration_mariage') || isset($this->declaration->code_declaration_mariage);
        $typeDeclaration = $this->declaration->type_declaration ?? null;

        if ($isDeces) {
            $personne = $this->declaration->defunt->nomcomplet();
            if ($typeDeclaration === 'CERTIFICAT DE NON INSCRIPTION') {
                $url = route('certificatNonInscription.show', $this->declaration->code_declaration_deces);
            } else {
                $url = route('declarationDeces.show', $this->declaration->code_declaration_deces);
            }
        } elseif ($isMariage) {
            $personne = $this->declaration->epoux->nomcomplet() . ' et ' . $this->declaration->epouse->nomcomplet();
            $url = route('declarationMariage.show', $this->declaration->code_declaration_mariage);
        } else {
            if ($typeDeclaration === 'CERTIFICAT DE NON INSCRIPTION') {
                $url = route('certificatNonInscription.show', $this->declaration->code_declaration_naissance);
            } elseif ($typeDeclaration === "CERTIFICAT DE DESTRUCTION DE L'ACTE") {
                $url = route('certificatDestruction.show', $this->declaration->code_declaration_naissance);
            } elseif ($typeDeclaration === "FICHE DE TRANSCRIPTION") {
                $url = route('certificatTranscription.show', $this->declaration->code_declaration_naissance);
            } else {
                $url = route('declarationNaissance.show', $this->declaration->code_declaration_naissance);
            }
            $personne = $this->declaration->enfant->nomcomplet();
        }
        // Détecter le type de document (pour naissance et mariage)
        $documentType = null;
        $documentDetails = null;
        if (!$isDeces && $this->declaration->requisition) {
            $documentType = 'réquisition';
            $documentDetails = $this->declaration->requisition->typeRequisition
                ? $this->declaration->requisition->typeRequisition->lib_type_requisition
                : 'Réquisition';
        } elseif (!$isDeces && $this->declaration->jugement) {
            $documentType = 'jugement';
            $documentDetails = $this->declaration->jugement->typeJugement
                ? $this->declaration->jugement->typeJugement->lib_type_jugement
                : 'Jugement';
        }
        return [
            'url' => $url,
            'action' => $this->action,
            'institution_type' => $categorie,
            'document_type' => $documentType,
            'document_details' => $documentDetails,
            'personne' => $personne,
        ];
    }
}
