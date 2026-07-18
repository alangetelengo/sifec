<?php

namespace Modules\Referentiel\Services;

use Exception;
use Modules\Referentiel\Entities\Registre;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * PDF d’attestation de paraphe tribunal (hashé puis scellé L2/L3 GUOT).
 */
class RegistreParaphePdfRenderer
{
    public function renderBinary(Registre $registre, string $magistratNom): string
    {
        $registre->loadMissing([
            'typeRegistre',
            'institutionUser.institution.institutionParent',
            'institutionUser.institution.lieu',
        ]);

        $cec = $registre->institutionUser?->institution;
        $tribunal = $cec?->institutionParent;
        $typeLib = $registre->typeRegistre?->lib_type_registre ?? 'Registre';
        $texte = strip_tags($registre->getTexteParapheRegistre($this->contexteFromType($registre)));

        $html = '
<html><head><meta charset="UTF-8"></head><body style="font-family: dejavusans; font-size: 11pt; color: #222;">
  <h2 style="text-align:center;color:#006B31;margin-bottom:8px;">Attestation de paraphe électronique</h2>
  <p style="text-align:center;font-size:9pt;color:#666;margin-top:0;">Signature électronique — attestation de paraphe</p>
  <hr style="border:none;border-top:1px solid #009E49;margin:16px 0;">
  <table cellpadding="6" cellspacing="0" width="100%" style="font-size:10.5pt;">
    <tr><td width="38%"><strong>Code registre</strong></td><td>'.e($registre->code_registre).'</td></tr>
    <tr><td><strong>Type</strong></td><td>'.e($typeLib).'</td></tr>
    <tr><td><strong>Libellé</strong></td><td>'.e((string) $registre->lib_registre).'</td></tr>
    <tr><td><strong>Centre d’état civil</strong></td><td>'.e((string) ($cec?->lib_institution ?? '')).'</td></tr>
    <tr><td><strong>Tribunal</strong></td><td>'.e((string) ($tribunal?->lib_institution ?? '')).'</td></tr>
    <tr><td><strong>Feuillets prévus</strong></td><td>'.e((string) $registre->nombre_acte_prevu).'</td></tr>
    <tr><td><strong>Magistrat signataire</strong></td><td>'.e($magistratNom).'</td></tr>
    <tr><td><strong>Date / heure</strong></td><td>'.e(now()->format('d/m/Y H:i:s')).'</td></tr>
  </table>
  <h3 style="margin-top:22px;font-size:11pt;color:#006B31;">Mention de paraphe</h3>
  <p style="text-align:justify;line-height:1.45;">'.e($texte).'</p>
  <p style="margin-top:28px;font-size:9pt;color:#666;">
    Document produit par SIFEC. L’intégrité est garantie par la signature cryptographique du magistrat
    et le cachet institutionnel du tribunal.
  </p>
</body></html>';

        $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', [12, 12, 12, 12]);
        $html2pdf->setDefaultFont('dejavusans');
        $html2pdf->writeHTML($html);

        $binary = $html2pdf->output('', 'S');
        if (! is_string($binary) || $binary === '') {
            throw new Exception('Échec génération PDF de paraphe (binaire vide).');
        }

        return $binary;
    }

    private function contexteFromType(Registre $registre): string
    {
        return match ((string) $registre->code_type_registre) {
            'TPRG_0002' => 'mariage',
            'TPRG_0003' => 'deces',
            default => 'naissance',
        };
    }
}
