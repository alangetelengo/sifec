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
   .small-text {
       font-size: 10px;
   }
</style>

<page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="16mm" style="font-size: 12pt">
    {{-- Mention légale en bas de page, conforme au module Naissance. --}}
    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'copie_mariage'])
    </page_footer>
    @php
        $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
        $nomcomplet = "";
        if ($f != null) {
            $nomcomplet = $f->user->personne->nomcomplet();
        }
        $infos = "";

        if($acte->declaration->requisition != ""){
            $titre = $acte->declaration->requisition->typeRequisition->lib_type_requisition;
            $num = $acte->declaration->requisition->num_requisition;
            $date = $acte->declaration->requisition->date_requisition;
            $infos = 'ACTE ETABLIT SUIVANT LA '.$titre.' N° '.$num.' DU '.(date("d-m-Y", strtotime($date)))." AU ".$acte->declaration->institution->institutionParent->lib_institution;
        }

        $institution = $acte->institutionUser->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
        $dept = $localisationData['localiteParent'];
        $commune = $localisationData['localite'];

        // Signature/QR : UNIQUEMENT les valeurs de délivrance (flux « Demande de document »),
        // exactement comme le module Naissance. La copie n'est pas pré-signée par l'officier de
        // l'acte d'origine ; elle est signée sur demande par l'officier en cours de fonction.
        // Hors demande, aucune signature/QR/date n'est apposé.
        $delivranceSignature = $signatureOfficier ?? null;
        $delivranceNomSignataire = isset($nomSignataireDelivrance) ? (string) $nomSignataireDelivrance : '';
        $dateSignatureSource = (isset($dateSignatureDelivrance) && $dateSignatureDelivrance !== null && $dateSignatureDelivrance !== '')
            ? $dateSignatureDelivrance
            : null;
        $estSigne = $delivranceSignature || $delivranceNomSignataire !== '';
        $dateActe = $dateSignatureSource ?? $acte->signed_at ?? $acte->doc_sig_signed_at ?? $acte->date_heure_approbation_mairie ?? $acte->date_emission;

        // Helper pour valider les images base64 (signatures époux/épouse/témoins)
        if (! function_exists('isValidBase64Image')) {
            function isValidBase64Image($data) {
                if (empty($data)) return false;
                $data = str_replace(['data:image/png;base64,', 'data:image/jpeg;base64,', 'data:image/jpg;base64,'], '', $data);
                return base64_decode($data, true) !== false;
            }
        }
    @endphp

<p style="color: red;text-align:center;font-style:italic"><small style="text-transform: uppercase">{{ $infos }}</small></p>

   <table cellspacing="0" style="width: 100%; font-size: 13pt;margin-top: 3px;">
       <tr>
           <td style="width:35%; text-align: center;padding-top: -120px;">
            <p>
                <span><strong>{{ $dept }}</strong></span> <br>
                <span>{{ $commune}}</span> <br>
                <span>{{ $institution->lib_institution }}</span>
            </p>
           </td>
           <td style="width:30%; text-align: center;">
                @if ($acte->approbation_tribunal == 1 && $acte->sceau_tribunal && file_exists(public_path("app/".$acte->sceau_tribunal)))
                    <img src='{{ public_path('app/'.$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                @endif
           </td>
           <td style="width:35%; text-align: center;">
               <strong style="margin-top:10px">REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; - Travail - Progr&egrave;s <br><br>
               @if ($estSigne)
                   @php
                       $acteVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.acte.mariage', ['code' => $acte->code_acte_mariage]);
                   @endphp
                   <qrcode value="{{ $acteVerificationUrl }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
                   <br><span style="font-size: 6.5px; color: #555;">Scanner pour authentifier</span>
               @else
                   <br><br><br><br><br><br>
               @endif
            </td>
       </tr>
   </table>
   <table align="center" style="border-radius: 1mm; border: none; width: 100%;">
       <tr>
           <td style="width:70%; text-align: center; padding-top: 5px;">
               <p><strong style="font-size: 140%;">COPIE D'ACTE DE MARIAGE</strong>
                <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}} </strong> Acte n°:<strong style="color: red">{{ $acte->code_acte_mariage }}</strong>
            </p>
           </td>
       </tr>
   </table>
   <div style="margin-top: 6px;margin-left: 4px;border-radius: 2mm;">
       <div style="position: relative; left: 0px; top: 0px; width: 100%; padding: 0px; text-align: left; font-weight: normal; font-size:14px;" class="main-content">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr style="margin-right: 8px;">
                <td class="compact">
                    Centre d'état civil principal : {!! nl2br(e(wordwrap($institution->lib_institution ?? '', 55, "\n", true))) !!} <br>
                    <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong> <br>
                    Par devant nous {{ $nomcomplet }} Officier de l'Etat Civil ont comparu publiquement : <br>
                    <span style="margin-left: 30px;"><strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong></span> <br>
                    Né le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->epoux->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epoux->date_naissance)))}}</strong>, à <strong>{!! nl2br(e(wordwrap($acte->declaration->epoux->lieu_naissance ?? '', 55, "\n", true))) !!}</strong> <br>
                    Acte de naissance n° <strong>{{ $acte->declaration->numero_acte_naissance_epoux }}</strong> du <strong>{{ date("d", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_emission_acte_naissance_epoux))) ." ".date("Y", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) }}</strong> dressé à la <strong>{!! nl2br(e(wordwrap($acte->declaration->cec_naissance_epoux ?? '', 55, "\n", true))) !!}</strong> <br>
                    Nationalité : <strong>{{ $acte->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpoux->lib_profession }}</strong> <br>
                    Domicilié : <strong>{!! nl2br(e(wordwrap($acte->declaration->epoux->adresse ?? '', 55, "\n", true))) !!}</strong><br> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpoux->lib_situation_matrimoniale }}</strong> <br>
                    Fils de : {!! nl2br(e(wordwrap($acte->declaration->pere_epoux ?? '', 55, "\n", true))) !!} <br>
                    Et de : {!! nl2br(e(wordwrap($acte->declaration->mere_epoux ?? '', 55, "\n", true))) !!} <br>
                    <span style="margin-left: 30px;">Et <strong>Mme. {{ $acte->declaration->epouse->nomcomplet() }}</strong></span> <br>
                    Née le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->epouse->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epouse->date_naissance)))}}</strong> , à <strong>{!! nl2br(e(wordwrap($acte->declaration->epouse->lieu_naissance ?? '', 55, "\n", true))) !!}</strong> <br>
                    Acte de naissance n° <strong>{{  $acte->declaration->numero_acte_naissance_epouse  }}</strong> du <strong>{{ date("d", strtotime($acte->declaration->date_emission_acte_naissance_epouse)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_emission_acte_naissance_epouse))) ." ".date("Y", strtotime($acte->declaration->date_emission_acte_naissance_epouse)) }}</strong> dressé à la <strong>{!! nl2br(e(wordwrap($acte->declaration->cec_naissance_epouse ?? '', 55, "\n", true))) !!}</strong> <br>
                    Nationalité : <strong>{{ $acte->declaration->epouse->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpouse->lib_profession }}</strong> <br>
                    Domiciliée : <strong>{!! nl2br(e(wordwrap($acte->declaration->epouse->adresse ?? '', 55, "\n", true))) !!}</strong><br> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpouse->lib_situation_matrimoniale }}</strong> <br>
                    Fille de : {!! nl2br(e(wordwrap($acte->declaration->pere_epouse ?? '', 55, "\n", true))) !!} <br>
                    Et de : {!! nl2br(e(wordwrap($acte->declaration->mere_epouse ?? '', 55, "\n", true))) !!} <br>
                    Sur notre interpellation, les futurs époux ont déclaré l'un après l'autre vouloir se prendre pour mari et femme et nous avons prononcé au nom de la loi
                    qu'ils sont unis par le mariage légal en présence de : <br>
                    @if ($acte->declaration->nom_prenom_mandant_epouse != "")
                        <strong style="color:red;margin-top:2px">{!! nl2br(e(wordwrap($acte->declaration->nom_prenom_mandant_epouse, 55, "\n", true))) !!}, représentante de l'épouse</strong>
                    @endif
                    @if ($acte->declaration->nom_prenom_mandant_epoux != "")
                        <strong style="color:red;margin-top:2px">{!! nl2br(e(wordwrap($acte->declaration->nom_prenom_mandant_epoux, 55, "\n", true))) !!}, représentant de l'époux</strong>
                    @endif
                    <br>
                    <strong><i>Témoins de l'époux</i></strong><br>
                    Et ce :<br>
                    1° {{ $acte->declaration->temoinHommeEpoux->nomcomplet() }} Née le {{ date("d", strtotime($acte->declaration->temoinHommeEpoux->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->temoinHommeEpoux->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinHommeEpoux->date_naissance)) ." à ".$acte->declaration->temoinHommeEpoux->lieu_naissance}}, Domicilié au {!! nl2br(e(wordwrap(optional($acte->declaration->temoinHommeEpoux)->adresse ?? '', 55, "\n", true))) !!}* <br>
                    2° Mme {{ $acte->declaration->temoinFemmeEpoux->nomcomplet() }} Née le {{ date("d", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance)) ." à ".$acte->declaration->temoinFemmeEpoux->lieu_naissance}}* <br>
                    <strong><i>Témoins de l'épouse</i></strong><br>
                    1° {{ $acte->declaration->temoinHommeEpouse->nomcomplet() }} Née le {{ date("d", strtotime($acte->declaration->temoinHommeEpouse->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->temoinHommeEpouse->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinHommeEpouse->date_naissance)) ." à ".$acte->declaration->temoinHommeEpouse->lieu_naissance}}, Domicilié au {!! nl2br(e(wordwrap(optional($acte->declaration->temoinHommeEpouse)->adresse ?? '', 55, "\n", true))) !!}* <br>
                    2° Mme {{ $acte->declaration->temoinFemmeEpouse->nomcomplet() }} Née le {{ date("d", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance)) ." à ".$acte->declaration->temoinFemmeEpouse->lieu_naissance}}* <br>
                    qui, lecture faite nous avons signé le présent acte avec les époux et les témoins
                </td>
            </tr>
           </table>

           {{-- Signatures des acteurs (comme l'acte de mariage) + officier en délivrance --}}
           <table class="historique" cellspacing="0" style="width: 100%; font-size: 12px; margin-top: 6px;">
                <col style="width: 33%">
                <col style="width: 33%">
                <col style="width: 33%">
                <tbody>
                        <tr>
                            <td style="text-align: center; vertical-align: top;">L'époux<br>
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_epoux))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_epoux }}" alt="Signature époux" width="70" height="55">
                                @endif
                                @if ($acte->declaration->nom_prenom_mandant_epoux != "")
                                    <span style="color:black;font-size:14px;">P.O</span>
                                @endif
                            </td>
                            <td></td>
                            <td style="text-align: center; vertical-align: top;">L'épouse<br>
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_epouse))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_epouse }}" alt="Signature épouse" width="70" height="55">
                                @endif
                                @if ($acte->declaration->nom_prenom_mandant_epouse != "")
                                    <span style="color:black;font-size:14px;">P.O</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; vertical-align: top; padding-top: 4px;">Témoins de l'époux<br>
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_temoin_premier_epoux))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_temoin_premier_epoux }}" alt="Signature témoin 1 époux" width="65" height="55">
                                @endif
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_temoin_deuxieme_epoux))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_temoin_deuxieme_epoux }}" alt="Signature témoin 2 époux" width="65" height="55">
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: top; padding-top: 4px;">
                                {{-- La date n'apparaît qu'une fois la copie signée sur demande. --}}
                                {{-- <p style="font-size: 12px; margin: 0;">Fait à {{ ucfirst(strtolower((string) $commune)) }}@if($estSigne), le {{ date("d", strtotime((string) $dateActe)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime((string) $dateActe))) ." ".date("Y", strtotime((string) $dateActe)) }}@endif<br> --}}
                                <p style="font-size: 12px; margin: 0;">Fait à {{ "Brazzaville" }}@if($estSigne), le {{ date("d", strtotime((string) $dateActe)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime((string) $dateActe))) ." ".date("Y", strtotime((string) $dateActe)) }}@endif<br>
                                L'officier d'état civil</p>
                                @if ($estSigne)
                                    @if ($delivranceSignature)
                                        @php
                                            $pdfSignatureCopie = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($delivranceSignature);
                                        @endphp
                                        @if ($pdfSignatureCopie)
                                            <img src="{{ $pdfSignatureCopie }}" width="70" height="55" alt=""><br>
                                        @endif
                                    @endif
                                    @if ($delivranceNomSignataire !== '')
                                        <span style="color:black; font-weight:bold; font-size: 11px;">{{ $delivranceNomSignataire }}</span>
                                    @endif
                                    @if ($dateSignatureSource)
                                        <br><span style="font-size: 7px; color:#006B31;">Signé électroniquement le {{ \Illuminate\Support\Carbon::parse($dateSignatureSource)->format('d/m/Y à H:i') }}</span>
                                    @endif
                                @else
                                    <span style="color: #999; font-style: italic; font-size: 10px;">[En attente de signature de délivrance]</span>
                                @endif
                            </td>
                            <td style="text-align: center; vertical-align: top; padding-top: 4px;">Témoins de l'épouse<br>
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_temoin_premier_epouse))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_temoin_premier_epouse }}" alt="Signature témoin 1 épouse" width="65" height="55">
                                @endif
                                @if($acte->declaration->signatureActe != null && isValidBase64Image($acte->declaration->signatureActe->signature_temoin_deuxieme_epouse))
                                    <img src="data:image/png;base64,{{ $acte->declaration->signatureActe->signature_temoin_deuxieme_epouse }}" alt="Signature témoin 2 épouse" width="65" height="55">
                                @endif
                            </td>
                        </tr>
                </tbody>
            </table>
            <div style="font-size: 10px;text-align: center; margin-top: 4px;" class="small-text">
            <i><strong>CONDITIONS DE MARIAGE</strong></i> <br> Les futurs époux déclarent expressément opter pour la <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->optionMariage)->lib_option_mariage ?? '', 55, "\n", true))) !!}</strong> et se marie sous le régime matrimonial de <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->regime)->lib_regime ?? '', 55, "\n", true))) !!}</strong>.
            <br>Coutume présidant à l'union: Congolaise*
            </div>
       </div>
   </div>
</page>
