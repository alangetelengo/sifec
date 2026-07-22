<style>
    page{
       position: relative;
       margin-top: 5px;
       margin-left: 30px;
       margin-right: 30px;
   }
   td{
       font-size: 90%;
       line-height: 1.4;
   }
   .small-text {
       font-size: 10px;
   }
</style>

<page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    {{-- Mention légale en bas de page, conforme au module Naissance. --}}
    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'extrait_mariage'])
    </page_footer>
    @php
        $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
        $nomcomplet = "";
        if ($f != null) {
            $nomcomplet = $f->user->personne->nomcomplet();
        }

        $institution = $acte->institutionUser->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
        $dept = $localisationData['localiteParent'];
        $commune = $localisationData['localite'];

        // Signature/QR : UNIQUEMENT les valeurs de délivrance (flux « Demande de document »),
        // exactement comme le module Naissance. Hors demande, aucune signature/QR/date n'est apposé.
        $delivranceSignature = $signatureOfficier ?? null;
        $delivranceNomSignataire = isset($nomSignataireDelivrance) ? (string) $nomSignataireDelivrance : '';
        $dateSignatureSource = (isset($dateSignatureDelivrance) && $dateSignatureDelivrance !== null && $dateSignatureDelivrance !== '')
            ? $dateSignatureDelivrance
            : null;
        $estSigne = $delivranceSignature || $delivranceNomSignataire !== '';
        $dateActe = $dateSignatureSource ?? $acte->signed_at ?? $acte->doc_sig_signed_at ?? $acte->date_heure_approbation_mairie ?? $acte->date_emission;
    @endphp

   <table cellspacing="0" style="width: 100%; font-size: 13pt;margin-top: 3px;">
       <tr>
           <td style="width:35%; text-align: center;">
            <p>
                <span><strong>{{ $dept }}</strong></span> <br>
                <span>{{ $commune}}</span> <br>
                <span>{{ $institution->lib_institution }}</span>
            </p>
           </td>
           <td style="width:30%; text-align: center;">
                @if ($acte->approbation_tribunal == 1 && $acte->sceau_tribunal && file_exists(public_path("app/".$acte->sceau_tribunal)))
                    <img src='{{ public_path('app/'.$acte->sceau_tribunal) }}' alt="" width="90" height="90">
                @endif
           </td>
           <td style="width:35%; text-align: center;">
               <strong>REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; - Travail - Progr&egrave;s <br><br>
               @if ($estSigne)
                   @php
                       $acteVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.acte.mariage', ['code' => $acte->code_acte_mariage]);
                   @endphp
                   <qrcode value="{{ $acteVerificationUrl }}" ec="H" style="width: 28mm; background-color: white; color: black;"></qrcode>
                   <br><span style="font-size: 6.5px; color: #555;">Scanner pour authentifier</span>
               @endif
            </td>
       </tr>
   </table><br>

   <table align="center" style="border: none; width: 100%;">
       <tr>
           <td style="text-align: center; padding-top: 5px;">
               <p><strong style="font-size: 140%;">EXTRAIT D'ACTE DE MARIAGE</strong>
                <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}} </strong> Acte n°:<strong style="color: red">{{ $acte->code_acte_mariage }}</strong></p>
           </td>
       </tr>
   </table>

   <div style="margin-top: 15px; font-size: 14px; line-height: 1.8;">
       <p>Le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong>,
       a été célébré le mariage de :</p>
       <p style="margin-left: 30px;">
           <strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong><br>
           né le {{ date("d/m/Y", strtotime($acte->declaration->epoux->date_naissance)) }} à {{ $acte->declaration->epoux->lieu_naissance }}
       </p>
       <p style="margin-left: 30px;">et de</p>
       <p style="margin-left: 30px;">
           <strong>Mme. {{ $acte->declaration->epouse->nomcomplet() }}</strong><br>
           née le {{ date("d/m/Y", strtotime($acte->declaration->epouse->date_naissance)) }} à {{ $acte->declaration->epouse->lieu_naissance }}
       </p>
       <p>Régime matrimonial : <strong>{{ optional($acte->declaration->regime)->lib_regime ?? '' }}</strong> —
          Option : <strong>{{ optional($acte->declaration->optionMariage)->lib_option_mariage ?? '' }}</strong></p>
   </div>

   <table class="historique" cellspacing="0" style="width: 100%; margin-top: 40px;">
        <tbody>
            <tr>
                <td></td>
                <td style="text-align: center;">
                    {{-- La date n'apparaît qu'une fois l'extrait signé sur demande. --}}
                    {{-- <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower((string) $commune)) }}@if($estSigne), le {{ date("d", strtotime((string) $dateActe)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime((string) $dateActe))) ." ".date("Y", strtotime((string) $dateActe)) }}@endif<br> --}}
                    <p style="font-size: 14px;">Fait à {{ "Brazzaville" }}@if($estSigne), le {{ date("d", strtotime((string) $dateActe)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime((string) $dateActe))) ." ".date("Y", strtotime((string) $dateActe)) }}@endif<br>
                    L'officier d'état civil</p>
                    @if ($estSigne)
                        @if ($delivranceSignature)
                            @php
                                $pdfSignatureExtrait = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($delivranceSignature);
                            @endphp
                            @if ($pdfSignatureExtrait)
                                <img src="{{ $pdfSignatureExtrait }}" width="100" height="100" alt=""><br>
                            @endif
                        @endif
                        @if ($delivranceNomSignataire !== '')
                            <span style="color:black; font-weight:bold">{{ $delivranceNomSignataire }}</span>
                        @endif
                        @if ($dateSignatureSource)
                            <br><span style="font-size: 8px; color:#006B31;">Signé électroniquement le {{ \Illuminate\Support\Carbon::parse($dateSignatureSource)->format('d/m/Y à H:i') }}</span>
                        @endif
                    @else
                        <div style="height: 100px; padding-top: 30px;">
                            <span style="color: #999; font-style: italic;">[En attente de signature de délivrance]</span>
                        </div>
                    @endif
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</page>
