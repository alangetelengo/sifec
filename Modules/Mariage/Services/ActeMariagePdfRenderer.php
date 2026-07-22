<?php

namespace Modules\Mariage\Services;

use Exception;
use Modules\Mariage\Entities\ActeMariage;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'un acte de mariage (Html2Pdf dest=S), à partir de la même vue que
 * l'affichage écran (mariage::etats.ActeMariageEtat), afin que le document signé soit identique
 * à celui présenté à l'officier.
 */
class ActeMariagePdfRenderer
{
    public function renderBinary(ActeMariage $acte): string
    {
        $acte->loadMissing([
            'declaration.epoux.nationalite',
            'declaration.epouse.nationalite',
            'declaration.professionEpoux',
            'declaration.professionEpouse',
            'declaration.situationMatEpoux',
            'declaration.situationMatEpouse',
            'declaration.temoinHommeEpoux',
            'declaration.temoinFemmeEpoux',
            'declaration.temoinHommeEpouse',
            'declaration.temoinFemmeEpouse',
            'declaration.optionMariage',
            'declaration.regime',
            'declaration.filiation',
            'declaration.requisition.typeRequisition',
            'declaration.institution.institutionParent',
            'declaration.signatureActe',
            'institutionUser.institution.institutionParent',
            'signataire.user.personne',
            'registre',
        ]);

        if (! $acte->declaration) {
            throw new Exception("Données incomplètes pour générer l'acte de mariage. Déclaration manquante.");
        }

        $html2pdf = new Html2Pdf('P', 'A3', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');

        $htmlContent = view('mariage::etats.ActeMariageEtat', compact('acte'))->render();

        if ($htmlContent === '') {
            throw new Exception("Le contenu HTML de l'acte de mariage est vide.");
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($acte->code_acte_mariage.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF acte de mariage (binaire vide).');
        }

        return $binary;
    }
}
