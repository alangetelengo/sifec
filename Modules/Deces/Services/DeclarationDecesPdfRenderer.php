<?php

namespace Modules\Deces\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Modules\Deces\Entities\DeclarationDeces;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'un certificat / déclaration / constatation de décès pour signature GUOT.
 *
 * Contextes :
 *  - formation_sanitaire : certificat de décès (FS) ;
 *  - centre_hygiene      : certificat de constatation (CH) ;
 *  - pompe_funebre       : déclaration de décès (CEC/PF, après confirmation certificat FS).
 */
class DeclarationDecesPdfRenderer
{
    public function renderBinary(DeclarationDeces $ddc, string $contexte, bool $forceSignatureQr = false): string
    {
        $ddc->loadMissing([
            'institution', 'institutionDestinataire', 'institutionUser.institution',
            'defunt', 'pere', 'mere', 'declarant', 'religion', 'situationMat', 'regime',
            'conjoint', 'filiation', 'lieuDeces', 'lieuSurvenance', 'mouvements',
        ]);

        $contexteForcage = in_array($contexte, ['formation_sanitaire', 'centre_hygiene', 'pompe_funebre'], true)
            ? $contexte
            : null;

        $dat1 = Carbon::create($ddc->created_at);
        $dateDeces = Carbon::create($ddc->date_heure_deces);
        $diffJour = $dateDeces->diffInDays($dat1);
        $typeDeclaration = $ddc->libelleAffichageType();
        $qrCode = $this->resolveQrCode($ddc, $contexte);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');

        if ($contexte === 'centre_hygiene') {
            $htmlContent = view('deces::etats.certificats.certificat_constatation_deces', compact('ddc', 'forceSignatureQr', 'qrCode', 'contexteForcage'))->render();
        } else {
            $htmlContent = view('deces::etats.declaration', compact('ddc', 'diffJour', 'typeDeclaration', 'contexteForcage', 'forceSignatureQr', 'qrCode'))->render();
        }

        if ($htmlContent === '') {
            throw new Exception('Le contenu HTML du document de décès est vide.');
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($ddc->code_declaration_deces.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF document de décès (binaire vide).');
        }

        return $binary;
    }

    private function resolveQrCode(DeclarationDeces $ddc, string $contexte): string
    {
        $code = $ddc->code_declaration_deces;

        return match ($contexte) {
            'formation_sanitaire' => URL::signedRoute('verification.certificat.deces', ['code' => $code]),
            'centre_hygiene' => URL::signedRoute('verification.constatation.deces', ['code' => $code]),
            default => URL::signedRoute('verification.declaration.deces', ['code' => $code]),
        };
    }
}
