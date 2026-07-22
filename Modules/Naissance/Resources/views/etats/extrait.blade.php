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
 <page orientation="landscape" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="70%" backimgw="70%" backtop="0" backbottom="12mm" style="font-size: 12pt">
    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'extrait_naissance'])
    </page_footer>

   @php
   $infos = "";
   $tribunal = $acte->declaration->libInstitutionTribunalPourMentionActe()
       ?? optional($acte->declaration->institutionUser->institution->institutionParent)->lib_institution;
   setlocale(LC_TIME, "fr_FR", "French");


   $num = "";
   $titre = "";
   $top = "";

   $prenomEnfantExtrait = \App\Sifec\Sifec::formatPrenomPourActe($acte->declaration->enfant->prenom ?? '');

   // Signature/QR : UNIQUEMENT les valeurs de délivrance (flux « Demande de document »).
   // Règle métier : l'extrait n'est pas pré-signé par l'officier de l'acte d'origine ; il est signé
   // sur demande de l'intéressé par l'officier EN COURS DE FONCTION. Hors demande, pas de signature.
   $delivranceSignature = $signatureOfficier ?? null;
   $delivranceNomSignataire = isset($nomSignataireDelivrance)
       ? (string) $nomSignataireDelivrance
       : '';

   $dateSignatureExtrait = (isset($dateSignatureDelivrance) && $dateSignatureDelivrance !== null && $dateSignatureDelivrance !== '')
       ? $dateSignatureDelivrance
       : null;

   if (empty($qrCode ?? null) && filled($acte->niupp ?? null) && \Illuminate\Support\Facades\Route::has('verification.acte')) {
       $qrCode = \Illuminate\Support\Facades\URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
   }
   $acteEstSigne = filled($acte->approbation_mairie ?? null) || filled($acte->niupp ?? null);

   $roleDelivrance = "L'officier de l'état civil";
   $blocsPkiDelivrance = [];
   if (isset($demande) && $demande) {
       $roleDelivrance = \App\Support\GuotSignatureAffichage::roleSignataire($demande, '', "L'officier de l'état civil");
       $bloc = \App\Support\GuotSignatureAffichage::blocPki(
           $demande,
           '',
           'PKI — DÉLIVRANCE',
           "L'officier de l'état civil",
           '#006B31',
           '#f4faf6',
       );
       if ($bloc) {
           $blocsPkiDelivrance[] = $bloc;
       }
       if ($delivranceNomSignataire === '' && filled($demande->actor_nom)) {
           $delivranceNomSignataire = (string) $demande->actor_nom;
       }
   }

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
                    @if(filled($acte->approbation_mairie))
                    N°:<strong>{{ $acte->niupp }}</strong> du <strong>{{date("d-m-Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> <br><br>
                    @endif
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
                <td>Le: <strong> {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_heure_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong> à <br>
                    <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong>
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
                {{-- La date n'apparaît qu'une fois l'extrait signé sur demande. --}}
                <td>
                    <br> Pour extrait conforme
                    @if($delivranceSignature || $delivranceNomSignataire !== '')
                        , le <strong>{{ date('d-m-Y') }}</strong>
                    @endif
                </td>
            </tr>

           </table>

                <div style="position:absolute; left: 18mm; top: 228px; width: 32mm;">
                    {{-- Le QR atteste de la signature : il n'apparaît que si l'extrait est signé sur demande. --}}
                    @if (! empty($qrCode ?? null) && ($delivranceSignature || $delivranceNomSignataire !== ''))
                        <qrcode value="{{ $qrCode }}" ec="H" style="width: 20mm; border: none;"></qrcode>
                        <br>
                        <span style="font-size: 6px; color: #555;">Scanner pour authentifier</span>
                    @endif
                </div>

                <div style="text-align:right">
                    <p style="margin-right:150px;margin-top:70px">{{ $roleDelivrance }}</p><br>
                </div>
                <div style="position:absolute; right:60px; top:250px; text-align: right;">
                            @if ($delivranceSignature || $delivranceNomSignataire !== '')
                               @php
                                   $pdfSignature = $delivranceSignature
                                       ? \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($delivranceSignature)
                                       : null;
                               @endphp
                               @if ($pdfSignature)
                               <img src="{{ $pdfSignature }}" style="width: 28mm;">
                               @endif
                               @if ($delivranceNomSignataire !== '')
                                <p style="font-weight:bold; margin-bottom:0;">{{ $delivranceNomSignataire }}</p>
                               @endif
                               @if ($dateSignatureExtrait)
                                <p style="font-size: 8px; color:#006B31; margin-top:2px;">Signé électroniquement le {{ \Illuminate\Support\Carbon::parse($dateSignatureExtrait)->format('d/m/Y à H:i') }}</p>
                               @endif
                            @else
                                <p style="color: #999; font-style: italic; font-size: 11px;">[En attente de signature de délivrance]</p>
                            @endif

                </div>

                @if(! empty($blocsPkiDelivrance))
                <div style="position:absolute; left: 18mm; right: 18mm; top: 310px; width: auto;">
                    @include('partials.guot.signature-pki-blocs', ['blocs' => $blocsPkiDelivrance, 'compact' => true])
                </div>
                @endif


        </div>
   </div>
</page>
