<style>
    page{
       position: relative;
   }
   td{
       font-size: 14px;
       height: 15px;
   }
   b{
       font-size: 14px%;
   }

</style>
 <page orientation="landscape" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="70%" backimgw="70%" backtop="0" backbottom="30mm" style="font-size: 12pt">

   @php
   $infos = "";
   $tribunal = $acte->declaration->libInstitutionTribunalPourMentionActe()
       ?? optional($acte->declaration->institutionUser->institution->institutionParent)->lib_institution;
   setlocale(LC_TIME, "fr_FR", "French");


   $num = "";
   $titre = "";
   $top = "";

   $prenomEnfantExtrait = \App\Sifec\Sifec::formatPrenomPourActe($acte->declaration->enfant->prenom ?? '');

   @endphp

<table cellspacing="0" style="width: 100%; font-size: 12px;">
    <tr>
        <td style="width:40%; text-align: center;">
            @php
                $institution = $acte->institutionUser->institution;
                $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
                $departement = $localisationData['localiteParent'];
                $communeDistrict = $localisationData['localite'];
            @endphp
            @if(Auth::user() != null && Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution != "TPINS_0005")
            <p>
                <span>
                {{ $departement }}
                    <br>
                    {{ $communeDistrict }}
                </span> <br>
                <span><strong>{{ $localisationData['inst'] }}</strong></span>
            </p>
            @else
            <p>
                <span>
                    <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
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
               <p><strong>EXTRAIT D’ACTE DE NAISSANCE</strong><br>
                    N°:<strong>{{ $acte->niupp }}</strong> du <strong>{{date("d-m-Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> <br><br>
                    CENTRE D’ETAT CIVIL : <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
                </p>
           </td>
           <td style="width:15%; text-align: center;">
           </td>
       </tr>
   </table>

   <div style="margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
       <div style="width: 150px;text-align: center;">

       </div>
       <div style="position: absolute; left: 20px; top: 140px; width: 700px; height: 600px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
        <br><br>
        <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;margin-bottom:-50px">
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong> à <br>
                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong>
                </td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Est {{ $acte->declaration->enfant->sexe=="M" ? "né " : "née "  }} à: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>{{ $acte->declaration->enfant->sexe=="M" ? "Le nommé " : "La nommée "  }} : <strong>{{ $acte->declaration->enfant->nom }} </strong><strong>{{ $prenomEnfantExtrait }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Du sexe : <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin " : "Féminin "  }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille " }} de : <strong>{{ $acte->declaration->pere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->pere->nom, $acte->declaration->pere->prenom) : $dummy }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Et de : <strong>{{ $acte->declaration->mere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->mere->nom, $acte->declaration->mere->prenom) : $dummy }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: center; padding-bottom: 4px;">
                <td><br> Pour extrait conforme, le <strong>{{ date('d-m-Y') }}</strong> </td>
            </tr>

           </table>

                <div style="text-align:right">
                    <p style="margin-right:150px;margin-top:70px">L’officier de l’état civil</p><br>
                </div>
                <div style="position:absolute; right:60px; top:250px">
                            @if ($acte->approbation_mairie != "")

                               <img src='{{ public_path('app/'.$acte->signature_mairie)}}' style="">
                                <p style="font-weight:bold;"> {{ \App\Sifec\Sifec::formatNomPrenomPourActe($acte->signataire->user->personne->nom, $acte->signataire->user->personne->prenom) }}</p>
                            @endif

                </div>


        </div>
   </div>
</page>
