<?php

namespace Modules\Naissance\Services;

use Exception;
use Illuminate\Support\Facades\URL;
use Modules\Naissance\Entities\Declarationnaissance;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'un certificat / d'une déclaration de naissance (Html2Pdf dest=S),
 * pour le hachage SHA-256 signé électroniquement.
 *
 * Le contexte détermine le rendu et le QR :
 *  - 'formation_sanitaire' : certificat de naissance (signature FS) ;
 *  - 'centre_etat_civil'   : déclaration de naissance (signature CEC).
 */
class DeclarationNaissancePdfRenderer
{
    public function renderBinary(Declarationnaissance $dn, string $contexte): string
    {
        $dn->loadMissing([
            'enfant',
            'pere.nationalite',
            'pere.profession',
            'mere.nationalite',
            'mere.profession',
            'declarant',
            'adoptant',
            'institution.institutionParent.lieu.localiteParent',
            'institution.lieu.localiteParent',
            'lieuSurvenance',
            'filiation',
            'requisition.institution',
            'requisitionParCode.institution',
            'jugement.institution',
            'jugementParCode.institution',
        ]);

        $contexteForcage = in_array($contexte, ['formation_sanitaire', 'centre_etat_civil'], true)
            ? $contexte
            : null;

        $dummy = 'XXXXXXXXXXXXXXXX';
        $typeDeclaration = $dn->libelleAffichageType();

        $qrCode = $contexte === 'formation_sanitaire'
            ? URL::signedRoute('verification.certificat.naissance', ['code' => $dn->code_declaration_naissance])
            : URL::signedRoute('verification.declaration', ['code' => $dn->code_declaration_naissance]);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $htmlContent = view(
            'naissance::etats.declaration',
            compact('dummy', 'qrCode', 'typeDeclaration', 'contexteForcage'),
            ['dn' => $dn]
        )->render();

        if ($htmlContent === '') {
            throw new Exception('Le contenu HTML du document est vide.');
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($dn->code_declaration_naissance.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF (binaire vide).');
        }

        return $binary;
    }
}
