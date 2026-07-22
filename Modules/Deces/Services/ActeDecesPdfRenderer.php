<?php

namespace Modules\Deces\Services;

use Exception;
use Illuminate\Support\Facades\URL;
use Modules\Deces\Entities\ActeDeces;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'un acte de décès (Html2Pdf dest=S), identique à l'affichage écran.
 */
class ActeDecesPdfRenderer
{
    public function renderBinary(ActeDeces $acte): string
    {
        $acte->loadMissing([
            'institutionUser.fonction',
            'signataire.user.personne',
            'signataire.user.affectationActive.fonction',
            'declaration.institutionUser.institution',
            'declaration.institution.institutionParent.lieu.localiteParent',
            'declaration.institution.lieu.localiteParent',
            'declaration.requisition.typeRequisition',
            'declaration.defunt',
            'declaration.pere',
            'declaration.mere',
            'declaration.declarant',
            'declaration.DDecesCauses.causeDeces',
        ]);

        if (! $acte->institutionUser || ! $acte->institutionUser->fonction) {
            throw new Exception("Données incomplètes pour générer l'acte de décès.");
        }

        $codefonction = $acte->institutionUser->fonction->code_fonction;
        $nomcomplet = '';
        $libfonction = '';

        if (in_array($codefonction, ['FONC_0004', 'FONC_0017', 'FONC_0018'], true)) {
            $f = $acte->institutionUser->where('code_fonction', [], 'FONC_0002')->first();
            if ($f && $f->user && $f->user->personne) {
                $nomcomplet = $f->user->personne->nomcomplet();
                $libfonction = $f->fonction->lib_fonction ?? '';
            }
        }
        if ($codefonction === 'FONC_0005') {
            $f = $acte->institutionUser->where('code_fonction', 'FONC_0012')->first();
            if ($f && $f->user && $f->user->personne) {
                $nomcomplet = $f->user->personne->nomcomplet();
                $libfonction = $f->fonction->lib_fonction ?? '';
            }
        }

        $qrCode = URL::signedRoute('verification.acte.deces', ['code' => $acte->code_acte_deces]);

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8');
        $html2pdf->setDefaultFont('Arial');

        $htmlContent = view('deces::etats.acte', compact('acte', 'nomcomplet', 'libfonction', 'qrCode'))->render();

        if ($htmlContent === '') {
            throw new Exception("Le contenu HTML de l'acte de décès est vide.");
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($acte->code_acte_deces.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF acte de décès (binaire vide).');
        }

        return $binary;
    }
}
