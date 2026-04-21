{{-- 
    Vue extrait acte de naissance pour demande de document
    Utilise la signature de la demande
--}}
<style>
    td {
        font-size: 13px;
    }
    button#print {
        display: none;
    }
</style>
<page orientation="landscape" format="148x210" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0" backbottom="17mm" style="font-size: 13px">
    @php
    setlocale(LC_TIME, "fr_FR", "French");
    $institution = $acte->institutionUser->institution;
    $departement = $institution->lieu->localiteParent->localiteParent;
    $communeDistrict = $institution->lieu->localiteParent;
    
    $nomEnfant = $acte->declaration->enfant->nom ?? '';
    $prenomEnfant = $acte->declaration->enfant->prenom ?? '';
    if ($acte->lastRectification && $acte->lastRectification->detailsRectification && $acte->lastRectification->detailsRectification->count() > 0) {
        foreach ($acte->lastRectification->detailsRectification as $d) {
            if ($d->code_rubrique === 'RUB_0001') {
                $nomEnfant = $d->nouvelle_valeur ?? $nomEnfant;
            }
            if ($d->code_rubrique === 'RUB_0002') {
                $prenomEnfant = $d->nouvelle_valeur ?? $prenomEnfant;
            }
        }
    }
    $prenomEnfant = \App\Sifec\Sifec::formatPrenomPourActe($prenomEnfant);
    
    $signatureOfficier = $demande->signature_officier ?? null;
    $nomSignataire = $demande->signataire 
        ? optional(optional($demande->signataire->user)->personne)->nomcomplet() 
        : '';
    @endphp

    {{-- Entête --}}
    <table cellspacing="0" style="width: 100%; font-size: 11px;">
        <tr>
            <td style="width:33%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
            <td style="width:34%; text-align: center;">
                <p>
                    <span>{{ "DEPARTEMENT DE ".$departement->lib_localite }}</span><br>
                    <span>{{ "COMMUNE DE ".$communeDistrict->lib_localite }}</span><br>
                    <span><strong>{{ $institution->lib_institution }}</strong></span>
                </p>
            </td>
            <td style="width:33%; text-align: center;"></td>
        </tr>
    </table>

    <table align="center" style="border-radius: 1mm; border: none; margin-top: 10mm;">
        <tr>
            <td style="width:100%; text-align: center;">
                <p><strong style="font-size: 16px;">EXTRAIT D'ACTE DE NAISSANCE</strong><br>
                   N°: <strong style="color: red; font-size: 14px;">{{ $acte->niupp }}</strong></p>
            </td>
        </tr>
    </table>

    @include('demande-document._mention_validite_pdf')

    <div style="margin-top: 5mm; margin-left: 8%; margin-right: 8%; font-size: 13px;">
        <table align="left" style="width: 100%;">
            <tr><td>Nom : <strong>{{ strtoupper($nomEnfant) }}</strong></td></tr>
            <tr><td>Prénom(s) : <strong>{{ $prenomEnfant }}</strong></td></tr>
            <tr><td>Sexe : <strong>{{ $acte->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</strong></td></tr>
            <tr><td>Né(e) le : <strong>{{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance)))." ".\App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong></td></tr>
            <tr><td>A : <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td></tr>
        </table>
    </div>

    {{-- Pied avec signature de la demande --}}
    <div style="position:absolute; bottom:5mm; width: 100%; text-align: right; padding-right: 10%;">
        <p style="font-size: 12px;">
            Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{ $demande->date_signature ? $demande->date_signature->format('d/m/Y') : now()->format('d/m/Y') }}<br>
            L'officier de l'état civil
        </p>
        @if($signatureOfficier)
            <img src='{{ public_path('app/'.$signatureOfficier) }}' style="max-height: 60px;"><br>
            <span style="color:black; font-weight:bold">{{ $nomSignataire }}</span>
        @else
            <div style="height: 60px; padding-top: 10px;">
                <span style="color: #999; font-style: italic;">[En attente de signature]</span>
            </div>
        @endif
    </div>
</page>
