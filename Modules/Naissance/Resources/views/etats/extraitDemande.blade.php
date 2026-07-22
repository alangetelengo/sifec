{{--
    Vue extrait acte de naissance pour demande de document.
    A5 paysage — flux normal (pas de position:absolute) pour 1 page sans débordement.
--}}
<style>
    td {
        font-size: 12px;
    }
    button#print {
        display: none;
    }
</style>
<page
    orientation="landscape"
    format="A5"
    backimg="{{ public_path('tpl/back-border.png') }}"
    backcolor="#FEFEFE"
    backimgx="center"
    backimgy="50%"
    backimgw="70%"
    backtop="4mm"
    backbottom="10mm"
    backleft="12mm"
    backright="12mm"
    style="font-size: 12px"
>
    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'extrait_naissance'])
    </page_footer>
    @php
    setlocale(LC_TIME, 'fr_FR', 'French');
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

    $demandeSignee = $demande->estSignee();
    $signatureOfficier = $demande->signature_officier ?? null;
    $nomSignataire = $demande->actor_nom
        ?: ($demande->signataire
            ? optional(optional($demande->signataire->user)->personne)->nomcomplet()
            : '');
    $roleDelivrance = \App\Support\GuotSignatureAffichage::roleSignataire($demande, '', "L'officier de l'état civil");
    $blocDelivrance = \App\Support\GuotSignatureAffichage::blocPki(
        $demande,
        '',
        'PKI — DÉLIVRANCE',
        "L'officier de l'état civil",
        '#006B31',
        '#f4faf6',
    );
    $qrCode = \Illuminate\Support\Facades\URL::signedRoute(
        'verification.demande.document',
        ['code' => $demande->code_demande_document]
    );
    @endphp

    {{-- Entête : institution à gauche, République à droite --}}
    <table cellspacing="0" cellpadding="0" style="width: 100%; font-size: 10px;">
        <tr>
            <td style="width: 42%; text-align: center; vertical-align: top;">
                {{ 'DEPARTEMENT DE '.$departement->lib_localite }}<br>
                {{ 'COMMUNE DE '.$communeDistrict->lib_localite }}<br>
                <strong>{{ $institution->lib_institution }}</strong>
            </td>
            <td style="width: 16%;"></td>
            <td style="width: 42%; text-align: center; vertical-align: top;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
    </table>

    <table cellspacing="0" cellpadding="0" style="width: 100%; margin-top: 4mm;">
        <tr>
            <td style="width: 100%; text-align: center;">
                <strong style="font-size: 14px;">EXTRAIT D'ACTE DE NAISSANCE</strong><br>
                @if(filled($acte->approbation_mairie))
                    N°: <strong style="color: red; font-size: 12px;">{{ $acte->niupp }}</strong>
                @endif
            </td>
        </tr>
    </table>

    @include('demande-document._mention_validite_pdf')

    <div style="margin-top: 4mm; font-size: 12px; line-height: 1.4;">
        <table cellspacing="0" cellpadding="1" align="center" style="width: 70%; margin: 0 auto;">
            <tr><td style="text-align: center;">Nom : <strong>{{ strtoupper($nomEnfant) }}</strong></td></tr>
            <tr><td style="text-align: center;">Prénom(s) : <strong>{{ $prenomEnfant }}</strong></td></tr>
            <tr><td style="text-align: center;">Sexe : <strong>{{ $acte->declaration->enfant->sexe == 'M' ? 'Masculin' : 'Féminin' }}</strong></td></tr>
            <tr><td style="text-align: center;">Né(e) le : <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date('d', strtotime($acte->declaration->date_heure_naissance))).' '.\App\Sifec\Sifec::mois(date('m', strtotime($acte->declaration->date_heure_naissance))).' '.\App\Sifec\Sifec::asLetters(date('Y', strtotime($acte->declaration->date_heure_naissance))) }}</strong></td></tr>
            <tr><td style="text-align: center;">A : <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td></tr>
        </table>
    </div>

    {{-- Pied : QR au centre, signature à droite --}}
    <div style="margin-top: 5mm;">
        <table cellspacing="0" cellpadding="0" style="width: 100%; table-layout: fixed;">
            <col style="width: 33%">
            <col style="width: 34%">
            <col style="width: 33%">
            <tr>
                <td style="vertical-align: top;"></td>
                <td style="text-align: center; vertical-align: top;">
                    @if($demandeSignee)
                        <qrcode value="{{ $qrCode }}" ec="H" style="width: 16mm; border: none;"></qrcode>
                        <br>
                        <span style="font-size: 5.5px; color: #555;">Scanner pour authentifier</span>
                    @endif
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <p style="font-size: 10px; margin: 0 0 0.5mm 0;">
                        Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite))) }}, le {{ $demande->date_signature ? $demande->date_signature->format('d/m/Y') : now()->format('d/m/Y') }}<br>
                        {{ $roleDelivrance }}
                    </p>
                    @if($demandeSignee)
                        @php
                            $pdfSignature = filled($signatureOfficier)
                                ? \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($signatureOfficier)
                                : null;
                        @endphp
                        @if ($pdfSignature)
                            <img src="{{ $pdfSignature }}" style="width: 16mm;"><br>
                        @endif
                        <span style="color: black; font-weight: bold; font-size: 10px;">{{ $nomSignataire }}</span>
                    @else
                        <span style="color: #999; font-style: italic; font-size: 9px;">[En attente de signature de délivrance]</span>
                    @endif
                </td>
            </tr>
        </table>
        @include('partials.guot.signature-pki-blocs', [
            'blocs' => array_values(array_filter([$blocDelivrance])),
            'compact' => true,
        ])
    </div>
</page>
