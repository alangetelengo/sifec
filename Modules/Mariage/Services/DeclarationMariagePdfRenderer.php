<?php

namespace Modules\Mariage\Services;

use Exception;
use Illuminate\Support\Carbon;
use Modules\Mariage\Entities\DeclarationMariage;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'une déclaration de mariage (Html2Pdf dest=S), identique à l'affichage
 * écran (mariage::etats.declarationMariage) pour que le document signé au CEC soit fidèle.
 */
class DeclarationMariagePdfRenderer
{
    /**
     * @param  bool  $forceSignatureQr  Force l'affichage du QR d'authentification (rendu de l'état
     *                                   final signé, pour figer les octets signés au CEC).
     */
    public function renderBinary(DeclarationMariage $dm, bool $forceSignatureQr = false): string
    {
        if ($dm->code_declaration_mariage === null) {
            throw new Exception('Déclaration de mariage introuvable.');
        }

        // Même logique de mention que EtatsMariageController::declaration().
        $mention = '';
        $dateDeclaration = Carbon::create($dm->date_declaration_mariage);
        $dateMariage = Carbon::create($dm->date_prevue_mariage);
        $diffJours = $dateDeclaration->diffInDays($dateMariage);

        if ($diffJours < 60 || $dm->lieu_ceremonie_mariage == "Hors centre d'état civil") {
            $mention = 'Cette déclaration est soumise à une réquisition';
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');

        $htmlContent = view('mariage::etats.declarationMariage', compact('dm', 'mention', 'forceSignatureQr'))->render();

        if ($htmlContent === '') {
            throw new Exception('Le contenu HTML de la déclaration de mariage est vide.');
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($dm->code_declaration_mariage.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF déclaration de mariage (binaire vide).');
        }

        return $binary;
    }
}
