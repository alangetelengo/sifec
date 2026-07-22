{{--
    Vue extrait acte de mariage pour demande de document
    Utilise la signature de la demande
--}}
<style>
    page{
       position: relative;
       margin-top: 5px;
       margin-left: 30px;
       margin-right: 30px;
   }
   td{
       font-size: 85%;
       height: 14px;
       padding-bottom: 1px!important;
       line-height: 1.3;
   }
   b{
       font-size: 110%;
   }
   .compact {
       margin: 0;
       padding: 0;
       line-height: 1.1;
   }
</style>

<page orientation="landscape" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="70%" backimgw="70%" backtop="0" backbottom="12mm" style="font-size: 12pt">

    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'extrait_mariage'])
    </page_footer>
    @php
        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = $acte->institutionUser->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);

        $dept = $localisationData['localiteParent'];
        $commune = $localisationData['localite'];

        // N'utiliser QUE la signature de la demande
        $signatureOfficier = $demande->signature_officier ?? null;
        $nomSignataire = $demande->signataire
            ? optional(optional($demande->signataire->user)->personne)->nomcomplet()
            : '';
    @endphp

<table cellspacing="0" style="width: 100%; font-size: 12px;">
    <tr>
        <td style="width:40%; text-align: center;">
            <p>
                <span><strong>{{ $dept }}</strong></span> <br>
                <span><strong>{{ $commune}}</strong></span> <br>
                <span><strong>{{ $institution->lib_institution }}</strong></span>
            </p>
        </td>
        <td style="width:34%; text-align: center;">
        </td>
        <td style="width:25%; text-align: center;margin-top:-10px">
            <strong>REPUBLIQUE DU CONGO</strong><br>
            Unit&eacute; - Travail - Progr&egrave;s
        </td>
    </tr>
</table>

<table align="center" style="border-radius: 1mm; border: none;">
    <tr style="">
        <td style="width:100%; text-align: center;">
            <p><strong style="font-size: 140%;">EXTRAIT D'ACTE DE MARIAGE</strong><br>
                N°:<strong style="color: red">{{ $acte->code_acte_mariage }}</strong> du <strong>{{date("d/m/Y", strtotime($acte->created_at))}}</strong>
            </p>
        </td>
    </tr>
</table>

@include('demande-document._mention_validite_pdf')

<div style="margin-top: 20px;margin-left: 6%;margin-right: 6%;">
    <table align="left" style="border-radius: 1mm; border: none; width: 100%; font-size: 14px;">
        <tr class="compact">
            <td>
                Le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong>
            </td>
        </tr>
        <tr class="compact">
            <td>
                Devant l'officier de l'état civil de <strong>{{ $institution->lib_institution }}</strong>
            </td>
        </tr>
        <tr class="compact">
            <td>
                Ont été unis par le mariage:
            </td>
        </tr>
        <tr class="compact">
            <td>
                <br><strong>L'ÉPOUX:</strong> {{ $acte->declaration->epoux->nomcomplet() }}
            </td>
        </tr>
        <tr class="compact">
            <td>
                Né le <strong>{{ date("d", strtotime($acte->declaration->epoux->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) ." ".date("Y", strtotime($acte->declaration->epoux->date_naissance)) }}</strong>
                à <strong>{{ $acte->declaration->epoux->lieu_naissance }}</strong>
            </td>
        </tr>
        <tr class="compact">
            <td>
                Fils de: {{ $acte->declaration->pere_epoux }} et de {{ $acte->declaration->mere_epoux }}
            </td>
        </tr>
        <tr class="compact">
            <td>
                <br><strong>L'ÉPOUSE:</strong> {{ $acte->declaration->epouse->nomcomplet() }}
            </td>
        </tr>
        <tr class="compact">
            <td>
                Née le <strong>{{ date("d", strtotime($acte->declaration->epouse->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) ." ".date("Y", strtotime($acte->declaration->epouse->date_naissance)) }}</strong>
                à <strong>{{ $acte->declaration->epouse->lieu_naissance }}</strong>
            </td>
        </tr>
        <tr class="compact">
            <td>
                Fille de: {{ $acte->declaration->pere_epouse }} et de {{ $acte->declaration->mere_epouse }}
            </td>
        </tr>
        <tr class="compact">
            <td><br>
                Régime matrimonial: <strong>{{ optional($acte->declaration->regime)->lib_regime }}</strong>
            </td>
        </tr>
    </table>
</div>

{{-- Pied avec signature de la demande --}}
<div style="position:absolute; bottom:5mm; width: 100%; text-align: right; padding-right: 10%;">
    <p style="font-size: 12px;">
        {{-- Fait à {{ ucfirst(strtolower($commune)) }}, le {{ $demande->date_signature ? $demande->date_signature->format('d/m/Y') : now()->format('d/m/Y') }}<br> --}}
        Fait à {{ "Brazzaville" }}, le {{ $demande->date_signature ? $demande->date_signature->format('d/m/Y') : now()->format('d/m/Y') }}<br>
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
