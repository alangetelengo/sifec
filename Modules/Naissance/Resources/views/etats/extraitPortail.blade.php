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


   @endphp

   <table cellspacing="0" style="width: 100%; font-size: 14px;">
       <tr>
           <td style="width:37%; text-align: center;padding-left: 14px;">
               @php
                   $localite = "";
                   $localiteParent = "";
                   // Utiliser le service Sifec pour obtenir les informations de localisation
                   $institution = $institutionPortail;
                   $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
                   
                   $inst = $localisationData['inst'];
                   $localite = $localisationData['localite'];
                   $localiteParent = $localisationData['localiteParent'];
                   $localisation = $localisationData['localisation'];
               @endphp
               <p>
                   <span>
                       <strong>{{ $localiteParent }}</strong>
                   </span> <br>
                   <span>{{ $localite}}</span> <br>
                   <span>{{ $inst }}</span>
               </p>
           </td>
           <td style="width:30%; text-align: center;">
           </td>
           <td style="width:33%; text-align: center;">
               <strong>REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; * Travail * Progr&egrave;s
           </td>
       </tr>
   </table><br><br>
   <table align="center" style="border-radius: 1mm; border: none;">
       <tr style="">
           <td style="width:100%; text-align: center;">
               <p><strong>EXTRAIT D’ACTE DE NAISSANCE</strong><br>
                @if(filled($acte->approbation_mairie))
                N°:<strong>{{ $acte->niupp }}</strong> DU <strong>{{date("d-m-Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> <br>
                @endif
                CENTRE D’ETAT CIVIL : <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
            </p>
           </td>
           <td style="width:15%; text-align: center;">
           </td>
       </tr><br>
   </table>
   <div style="margin-top: 100px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
       <div style="width: 150px;text-align: center;">

       </div>
       <div style="position: absolute; left: 20px; top: 200px; width: 700px; height: 700px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
           <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;">
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Le: <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong> à <br>
                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong>
                </td>
            </tr>
            {{-- <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>A: <strong>{{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
            </tr> --}}
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Est {{ $acte->declaration->enfant->sexe=="M" ? "né " : "née "  }} à: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>{{ $acte->declaration->enfant->sexe=="M" ? "Le nommé " : "La nommée "  }} : <strong>{{ $acte->declaration->enfant->nom." ".$acte->declaration->enfant->prenom }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Du sexe : <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin " : "Féminin "  }}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille " }} de : <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nom." ".$acte->declaration->pere->prenom : $dummy}}</strong></td>
            </tr>
            <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                <td>Et de : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nom." ".$acte->declaration->mere->prenom : $dummy}}</strong></td>
            </tr>
            <tr style="width:100%; text-align: center; padding-bottom: 4px;">
                <td><br> Pour extrait conforme, le <strong>{{ date('d-m-Y') }}</strong> </td>
            </tr>

           </table>

            <div style="text-align:right">
                    <p style="margin-right:150px;">L’officier de l’état civil</p><br>
                </div>
                <div style="position:absolute; right:60px; top:210px">
                          @php
                              $pdfSignature = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($signatairePortail->signature);
                          @endphp
                          @if ($pdfSignature)
                          <img src="{{ $pdfSignature }}"><br>
                          @endif
                             {{ $signatairePortail->nom.' '.$signatairePortail->prenom }}

                </div>
       </div>
   </div>
</page>
