{{-- 
    Vue extrait acte de décès pour demande de document
    Utilise la signature de la demande
--}}
<style>
    page{
       position: relative;
   }
   td{
       font-size: 14px;
       height: 15px;
   }
   b{
       font-size: 14px;
   }
</style>

<page orientation="landscape" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="70%" backimgw="70%" backtop="0" backbottom="30mm" style="font-size: 12pt">
   @php
   setlocale(LC_TIME, "fr_FR", "French");

   // Utiliser le service Sifec pour obtenir les informations de localisation
   $institution = $acte->declaration->institutionUser->institution;
   $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
   
   $departement = $localisationData['departement'];
   $communeDistrict = $localisationData['lieu'] ? $localisationData['lieu']->localiteParent : null;
   $localiteParent = $localisationData['localiteParent'];
   $localite = $localisationData['localite'];
   
   // N'utiliser QUE la signature de la demande
   $signatureOfficier = $demande->signature_officier ?? null;
   $nomSignataire = $demande->signataire 
       ? optional(optional($demande->signataire->user)->personne)->nomcomplet() 
       : '';
   @endphp

<table cellspacing="0" style="width: 100%; font-size: 12px;">
    <tr>
        <td style="width:40%; text-align: center;">
            @if($departement)
            <p>
                <span><strong>
                {{ $localiteParent }}
                    <br>
                    {{ $localite }}
                </strong></span> <br>
                <span><strong>{{ $acte->institutionUser->institution->lib_institution }}</strong></span>
            </p>
            @else
            <p>
                <span>
                    <strong>{{ $acte->declaration->institutionUser->institution->lib_institution }}</strong>
                </span> <br>
                <span>Service Consulaire</span> <br>
            </p>
            @endif
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
            <p><strong>EXTRAIT D'ACTE DE DÉCÈS</strong><br>
                N°:<strong>{{ $acte->code_acte_deces }}</strong> du <strong>{{date("d-m-Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> <br><br>
                <strong>CENTRE D'ÉTAT CIVIL SECONDAIRE: {{ $acte->institutionUser->institution->lib_institution }}</strong>
            </p>
        </td>
    </tr>
</table>

@include('demande-document._mention_validite_pdf')

<div style="margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
    <div style="position: absolute; left: 20px; top: 140px; width: 700px; height: 600px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
        <br><br>
        <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;margin-bottom:-50px">
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Le: <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_deces)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_deces))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_deces))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_deces))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_deces))) }} minute(s)</strong>
                </td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Est décédé(e) à: <strong>{{ $acte->declaration->lieu_deces ?? 'Non renseigné' }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Le nommé(e) : <strong>{{ $acte->declaration->defunt->nom }} </strong><strong style="text-transform: capitalize">{{ $acte->declaration->defunt->prenom }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Du sexe : <strong>{{ $acte->declaration->defunt->sexe=="M" ? "Masculin " : "Féminin "  }}</strong></td>
            </tr>
            @if($acte->declaration->defunt->date_naissance)
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Né(e) le : <strong>{{ date("d-m-Y", strtotime($acte->declaration->defunt->date_naissance)) }}</strong> à <strong>{{ $acte->declaration->defunt->lieu_naissance ?? 'Non renseigné' }}</strong></td>
            </tr>
            @endif
            @if($acte->declaration->pere)
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Fils/Fille de : <strong>{{ $acte->declaration->pere->nom }} </strong><strong style="text-transform: capitalize">{{ $acte->declaration->pere->prenom }}</strong></td>
            </tr>
            @endif
            @if($acte->declaration->mere)
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Et de : <strong>{{ $acte->declaration->mere->nom }} </strong><strong style="text-transform: capitalize">{{ $acte->declaration->mere->prenom }}</strong></td>
            </tr>
            @endif
            @if($acte->declaration->causeDeces)
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Cause du décès : <strong>{{ $acte->declaration->causeDeces->lib_cause_deces }}</strong></td>
            </tr>
            @endif
            <tr style="width:100%; text-align: center; padding-bottom: 4px;">
                <td><br> Pour extrait conforme, le <strong>{{ $demande->date_signature ? $demande->date_signature->format('d-m-Y') : date('d-m-Y') }}</strong> </td>
            </tr>
        </table>

        <div style="text-align:right">
            <p style="margin-right:150px;margin-top:70px">L'officier de l'état civil</p><br>
        </div>
        <div style="position:absolute; right:60px; top:250px">
            @if($signatureOfficier)
                <img src='{{ public_path('app/'.$signatureOfficier) }}' style="">
                <p style="font-weight:bold;">{{ $nomSignataire }}</p>
            @else
                <div style="height: 60px; padding-top: 10px; text-align: center;">
                    <span style="color: #999; font-style: italic;">[En attente de signature]</span>
                </div>
            @endif
        </div>
    </div>
</div>
</page>
