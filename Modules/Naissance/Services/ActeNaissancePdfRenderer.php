<?php

namespace Modules\Naissance\Services;

use Exception;
use Illuminate\Support\Facades\URL;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Génère le PDF binaire d'un acte de naissance (Html2Pdf dest=S).
 */
class ActeNaissancePdfRenderer
{
    public function renderBinary(ActeNaissance $acte): string
    {
        $acte->loadMissing([
            'declaration.enfant',
            'declaration.pere.nationalite',
            'declaration.pere.profession',
            'declaration.mere.nationalite',
            'declaration.mere.profession',
            'declaration.declarant',
            'declaration.adoptant',
            'declaration.jugement.institutionUser.institution.institutionParent',
            'declaration.jugement.institution',
            'declaration.institutionUser.institution.institutionParent',
            'declaration.institutionUser.institution.lieu.localiteParent',
            'declaration.requisition.typeRequisition',
            'declaration.requisition.institution',
            'declaration.requisitionParCode.institution',
            'declaration.jugementParCode.institution',
            'declaration.institution.institutionParent',
            'institutionUser.institution',
            'institutionUser.institution.institutionParent.lieu.localiteParent',
            'registre',
            'signataire.user.personne',
            'rectifications',
        ]);

        if (! $acte->declaration) {
            throw new Exception("Données incomplètes pour générer l'acte. Déclaration manquante.");
        }

        $dummy = 'XXXXXXXXXXXXXXXX';
        $acteannuler = Declarationnaissance::where('numero_ancien_acte', $acte->niupp)->first();
        $declarationDeces = DeclarationDeces::pourMentionActeNaissance(
            $acte->niupp,
            optional($acte->declaration)->date_heure_naissance
        );

        $mariage = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)->first()
            ?: DeclarationMariage::where('numero_acte_naissance_epouse', $acte->niupp)->first();

        $nombreMentions = 0;
        if ($mariage != null) {
            $nombreMentions++;
        }
        if ($declarationDeces != null) {
            $nombreMentions++;
        }
        $jugement = $acte->declaration->jugement;
        if ($jugement !== null) {
            if (filled($acte->declaration->code_adoptant) || filled(optional($acte->declaration->adoptant)->code_personne)) {
                $nombreMentions++;
            }
            $typeJg = (string) ($jugement->type_jugement ?? '');
            if (in_array($typeJg, ['JUGEMENT SUPPLETIF', "JUGEMENT D'HOMOLOGATION"], true)) {
                $nombreMentions++;
            }
        }
        if ($acte->rectifications && $acte->rectifications->count() > 0) {
            $nombreMentions += $acte->rectifications->count();
        }

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', [5, 5, 5, 5]);
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->setTestTdInOnePage(false);

        $qrCode = $acte->niupp
            ? URL::signedRoute('verification.acte', ['niupp' => $acte->niupp])
            : '';

        $htmlContent = view('naissance::etats.acte', compact(
            'acte',
            'dummy',
            'acteannuler',
            'declarationDeces',
            'mariage',
            'qrCode',
            'nombreMentions'
        ))->render();

        if ($htmlContent === '') {
            throw new Exception("Le contenu HTML de l'acte est vide.");
        }

        $html2pdf->writeHTML($htmlContent);

        $binary = $html2pdf->output($acte->code_acte_naissance.'.pdf', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF (binaire vide).');
        }

        return $binary;
    }
}
