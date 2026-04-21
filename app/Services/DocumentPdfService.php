<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Modules\Deces\Entities\ActeDeces;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\ActeMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Mobile\Entities\DemandeDocument;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Spipu\Html2Pdf\Html2Pdf;

class DocumentPdfService
{
    /**
     * Générer une copie d'acte pour une demande
     */
    public function genererCopie(DemandeDocument $demande): string
    {
        $acte = $demande->getActeConcerne();

        if (! $acte) {
            throw new Exception("Acte introuvable pour la demande {$demande->code_demande_document}");
        }

        $dummy = 'XXXXXXXXXXXXXXXX';

        return match ($demande->code_type_acte) {
            'TAC_0001' => $this->genererCopieNaissance($acte, $demande, $dummy),
            'TAC_0002' => $this->genererCopieMariage($acte, $demande, $dummy),
            'TAC_0004' => $this->genererCopieDeces($acte, $demande, $dummy),
            default => throw new Exception("Type d'acte non supporté: {$demande->code_type_acte}"),
        };
    }

    /**
     * Générer un extrait d'acte pour une demande
     */
    public function genererExtrait(DemandeDocument $demande): string
    {
        $acte = $demande->getActeConcerne();

        if (! $acte) {
            throw new Exception("Acte introuvable pour la demande {$demande->code_demande_document}");
        }

        $dummy = 'XXXXXXXXXXXXXXXX';

        return match ($demande->code_type_acte) {
            'TAC_0001' => $this->genererExtraitNaissance($acte, $demande, $dummy),
            'TAC_0002' => $this->genererExtraitMariage($acte, $demande, $dummy),
            'TAC_0004' => $this->genererExtraitDeces($acte, $demande, $dummy),
            default => throw new Exception("Type d'acte non supporté: {$demande->code_type_acte}"),
        };
    }

    /**
     * Générer copie acte de naissance
     */
    private function genererCopieNaissance(ActeNaissance $acte, DemandeDocument $demande, string $dummy): string
    {
        $acte->load(Declarationnaissance::eagerLoadDeclarationTribunalMentionDepuisActeNaissance());

        // Mentions de décès et mariage
        $declarationDeces = DeclarationDeces::where('num_acte_naissance', $acte->niupp)->first();
        $mariage = DeclarationMariage::where('numero_acte_naissance_epoux', $acte->niupp)
            ->orWhere('numero_acte_naissance_epouse', $acte->niupp)
            ->first();

        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('naissance::etats.copieActeNaissanceDemande',
                compact('acte', 'dummy', 'declarationDeces', 'mariage', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "copie_naissance_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Générer extrait acte de naissance
     */
    private function genererExtraitNaissance(ActeNaissance $acte, DemandeDocument $demande, string $dummy): string
    {
        $acte->load(array_merge(
            Declarationnaissance::eagerLoadDeclarationTribunalMentionDepuisActeNaissance(),
            [
                'declaration.enfant',
                'declaration.institutionUser.institution.institutionParent',
                'institutionUser.institution.lieu.localiteParent',
            ]
        ));

        $numExtrait = substr(time(), 2);

        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('L', 'A5', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('naissance::etats.extraitDemande',
                compact('acte', 'numExtrait', 'dummy', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "extrait_naissance_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Générer copie acte de mariage
     */
    private function genererCopieMariage(ActeMariage $acte, DemandeDocument $demande, string $dummy): string
    {
        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('mariage::etats.copieActeMariageDemande',
                compact('acte', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "copie_mariage_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Générer extrait acte de mariage
     */
    private function genererExtraitMariage(ActeMariage $acte, DemandeDocument $demande, string $dummy): string
    {
        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('L', 'A5', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('mariage::etats.extraitActeMariageDemande',
                compact('acte', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "extrait_mariage_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Générer copie acte de décès
     */
    private function genererCopieDeces(ActeDeces $acte, DemandeDocument $demande, string $dummy): string
    {
        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('deces::etats.copieActeDecesDemande',
                compact('acte', 'dummy', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "copie_deces_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Générer extrait acte de décès
     */
    private function genererExtraitDeces(ActeDeces $acte, DemandeDocument $demande, string $dummy): string
    {
        view()->share('tester', [], 'Alange');
        $html2pdf = new Html2Pdf('L', 'A5', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML(
            view('deces::etats.extraitActeDecesDemande',
                compact('acte', 'dummy', 'demande')
            )->render()
        );

        return $this->sauvegarderPdf(
            $html2pdf->output('', 'S'),
            "extrait_deces_{$demande->code_demande_document}.pdf"
        );
    }

    /**
     * Sauvegarder le PDF dans le storage
     */
    private function sauvegarderPdf(string $content, string $nom): string
    {
        $dossier = 'demandes_documents/'.date('Y/m');
        $chemin = $dossier.'/'.$nom;

        Storage::disk('local')->makeDirectory($dossier);
        Storage::disk('local')->put($chemin, $content);

        return storage_path('app/'.$chemin);
    }
}
