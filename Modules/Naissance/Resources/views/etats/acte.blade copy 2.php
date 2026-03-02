<style>
    td{
        font-size: 14px;
    }
    b{
        font-size: 14px;
    }
    small{
        color: red;
    }
    button#print{
        display: none;
    }
    /* Empêcher le débordement des adresses longues (Domicilié au) */
    .acte-contenu td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        max-width: 650px;
    }
    /* Nom d'institution long : forcer le retour à la ligne sans débordement */
    .acte-contenu td.institution-officier {
        overflow-wrap: anywhere;
        word-break: break-word;
        max-width: 100%;
    }
    /* Zone signature : hauteur fixe pour pagination cohérente (1 page qu'il y ait signature ou non) */
    .zone-signature-acte {
        min-height: 55mm;
        page-break-inside: avoid;
    }
</style>
  <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 14px">
    @php
    $infos = "";
    $tribunal = null;
    try {
        // Vérifier si la relation tribunal existe et est accessible
        $institution = $acte->declaration->institutionUser->institution ?? null;
        if ($institution && method_exists($institution, 'tribunal')) {
            $tribunal = $institution->tribunal;
            if ($tribunal != null && isset($tribunal->lib_tribunal)) {
                $tribunal = $tribunal->lib_tribunal;
            }
        }
    } catch (\Exception $e) {
        // Si la relation n'existe pas, on continue avec $tribunal = null
        $tribunal = null;
    }

    $num = "";
    if ($tribunal) {
    if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
        $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
    } else {
        $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }
    }

    // Numéro de réquisition : priorité à requisition.num_requisition, sinon declaration.numero_req
    $numeroRequisition = optional($acte->declaration->requisition)->num_requisition ?? $acte->declaration->numero_req ?? '';
    $anneeRequisition = optional($acte->declaration->requisition)->date_requisition
        ? date("Y", strtotime($acte->declaration->requisition->date_requisition))
        : date("Y", strtotime($acte->declaration->date_heure_declaration));

    if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$numeroRequisition.($numeroRequisition ? '/'.$anneeRequisition : '')." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$numeroRequisition.($numeroRequisition ? '/'.$anneeRequisition : '')." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
        $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION N° '.$numeroRequisition.($numeroRequisition ? '/'.$anneeRequisition : '')." ".$num;
    }

    if($acte->declaration->personne_declaree == "Enfant abandonné"){
        $infos = 'E.A';
    }

    if($acte->declaration->personne_declaree == "Enfant trouvé"){
        $infos = 'E.T';
    }


    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width:40%; text-align: center;">
                @php
                   setlocale(LC_TIME, "fr_FR", "French");

                    $institution = $acte->institutionUser->institution;
                    $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
                    $departement = $localisationData['localiteParent'];
                    $communeDistrict = $localisationData['localite'];
                    $localisation = $localisationData['localisation'];
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
                        {{ $departement }}
                        <br>
                            {{ $communeDistrict }}
                        </span> <br>
                    <span>
                        <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
                    </span> <br>
                    {{-- <span>Service Consulaire</span> <br> --}}
                </p>
                @endif
            </td>
            <td style="width:34%; text-align: center;">
                <p style="color: red">{{ $infos != "" ? $infos : "" }}</p>
            </td>
            <td style="width:33%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                <p><strong style="font-size: 18px;">ACTE DE NAISSANCE</strong>
                    {{-- <br> Acte n°:<strong>{{ $acte->numeroActe->numero_acte }}</strong> --}}
                    <br>N°: <strong style="color: red">{{ $acte->niupp }} R.A.N {{ $acte->registre->created_at->format('Y') }}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>

        </tr><br>
    </table>
    @php
        $hasMentionsMarginales = ($mariage != null)
            || ($declarationDeces != null)
            || ($acte->declaration->jugement != null)
            || (isset($acte->rectifications) && $acte->rectifications->count() > 0);
    @endphp
    <div style="margin-top: 60px;margin-left: 6%;margin-right: 6%;border-radius: 2mm; position: relative;">
        @if($hasMentionsMarginales)
        <div style="width: 150px;text-align: center;">
            <p>Marge réservée aux mentions <br> d'officier({{ $nombreMentions ?? 0 }}) <br><br>
            </p>

            @if ($mariage != null)
                <small>Marié(e) le: <br> {{ date("d-m-Y", strtotime($mariage->date_prevue_mariage))}}</small><br>
                @if ($mariage->acte != NULL)
                    @php
                        $institution = $acte->institutionUser->institution;
                        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
                        $inst = $localisationData['inst'];
                    @endphp
                    <small>A : LA {!! nl2br(e(wordwrap($inst ?? '', 55, "\n", true))) !!}</small><br>
                    <small>N° acte de mariage : {{$mariage->acte->code_acte_mariage}}</small>
                    <small>{!! nl2br(e(wordwrap(($acte->declaration->enfant->sexe=="M" ? "Epouse: ".optional($mariage->acte->declaration->epouse)->nomcomplet() : "Epoux: ".optional($mariage->acte->declaration->epoux)->nomcomplet()) ?? '', 55, "\n", true))) !!}</small>
                @endif
            @endif

            @if ($declarationDeces != NULL)
                <small>Décédé le: <br> {{utf8_encode(strftime("%d %B %Y", strtotime($declarationDeces->date_heure_deces)))." à ".date("H:i", strtotime($declarationDeces->date_heure_deces))}} minute(s)</small><br>
                <small>A : {!! nl2br(e(wordwrap($declarationDeces->lieu_deces ?? '', 55, "\n", true))) !!}</small><br>
                @if ($declarationDeces->acte != NULL)
                <small>N° acte de décès : {{$declarationDeces->acte->code_acte_deces}}</small>
                @endif
            @endif
           @if($acte->declaration->jugement != null)
                @if($acte->declaration->adoptant != "")
                <small>{{ $acte->declaration->enfant->sexe == "M" ? "Adopté" : "Adoptée" }} par <strong>{!! nl2br(e(wordwrap($acte->declaration->adoptant->nomcomplet() ?? '', 55, "\n", true))) !!}</strong></small><br>
                <small>Jugement N&deg; : <strong> {{ $acte->declaration->jugement->num_jugement }}</strong> </small>
                <small>du : <strong>{{ date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)) }}</strong> </small>
                <small>au : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->jugement->institutionUser->institution->institutionParent)->lib_institution ?? '', 55, "\n", true))) !!}</strong></small> <br>
                @if($acte->declaration->type_adoption == "adoption pleniere")
                    <small>N&deg; ancien acte de naissance  <strong>{{ $acte->declaration->jugement->numero_ancien_acte }}</strong></small>
                @endif
                @endif
                @if($acte->declaration->jugement->type_jugement == "JUGEMENT SUPPLETIF" || $acte->declaration->jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                    {{-- <small>{{ $acte->declaration->enfant->sexe == "M" ? "Adopté" : "Adoptée" }} par <strong>{{ $acte->declaration->adoptant->nomcomplet() }}</strong></small><br> --}}
                    <small>{{ $acte->declaration->jugement->type_jugement }} N&deg;: <strong> {{ $acte->declaration->jugement->num_jugement }}</strong> </small>
                    <small><br>du : <strong>{{ date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)) }}</strong> </small>
                    <small><br>au : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->jugement->institutionUser->institution->institutionParent)->lib_institution ?? '', 55, "\n", true))) !!}</strong></small> <br>
                    @if($acte->declaration->jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                    <small>N&deg; ancien acte de naissance  <strong>{{ $acte->declaration->jugement->numero_ancien_acte }}</strong></small>
                    @endif
                @endif
           @endif
          {{-- Récupérer les rectifications de l'acte avec leurs détails (mention marginale) --}}
            @if (isset($acte->rectifications) && $acte->rectifications->count() > 0)
                @foreach ($acte->rectifications as $rectification)
                    <small>Suivant la décision émanant du <strong>{!! nl2br(e(wordwrap(optional($rectification->institutionUser->institution->institutionParent)->lib_institution ?? '', 55, "\n", true))) !!}</strong>
                        en date du <strong>{{ date("d-m-Y", strtotime($rectification->updated_at)) }}</strong>, sous le
                        N&deg;: <strong>{{ $rectification->numero_rectification }}</strong>, l'acte ci-contre est rectifié en ce sens que :
                    </small>
                    @if ($rectification->detailsRectification->count() > 0)
                        @foreach ($rectification->detailsRectification as $index => $detail)
                            <small>
                                @if ($index > 0)<br>@endif
                                le <strong>{!! nl2br(e(wordwrap(optional($detail->rubrique)->lib_rubrique ?? '', 55, "\n", true))) !!}</strong> du titulaire est
                                <strong>{!! nl2br(e(wordwrap($detail->nouvelle_valeur ?? '', 55, "\n", true))) !!}</strong> au lieu de <strong>{!! nl2br(e(wordwrap($detail->ancienne_valeur ?? '', 55, "\n", true))) !!}</strong>.
                            </small>
                        @endforeach
                        <br>
                    @endif
                @endforeach
            @endif
        </div>
        @endif
        <div style="{{ $hasMentionsMarginales ? 'position: absolute; left: 150px; top: 240px; width: 700px; min-height: 500px; border-left: 1px solid black;' : 'position: relative; width: 100%; min-height: 500px;' }} padding: 0; overflow: visible; text-align: left; font-weight: normal; font-size:14px;">
            <table class="acte-contenu" align="left" style="margin-left: 2%; border-radius: 1mm; border: none; table-layout: fixed; width: 96%;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td class="institution-officier">L'Officier du centre d'état civil principal de: <strong>{!! nl2br(e(wordwrap(optional($acte->institutionUser->institution)->lib_institution ?? '', 35, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong>{!! nl2br(e(wordwrap(Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration))).' '.Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))).' '.Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))).' à '.Sifec::asLetters((int)date("H", strtotime($acte->declaration->date_heure_declaration))).' heure(s) '.Sifec::asLetters((int)date("i", strtotime($acte->declaration->date_heure_declaration))).' minutes', 55, "\n", true))) !!}</strong>

                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {!! nl2br(e(wordwrap(Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance))).' '.Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))).' '.Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))), 55, "\n", true))) !!}</strong> à </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A: <strong>{!! nl2br(e(wordwrap($acte->declaration->enfant->lieu_naissance ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }}
                       <span style="color: red;">

                        

                         @php
                         $nomEnfant = $acte->declaration->enfant->nom ?? '';
                         $prenomEnfant = $acte->declaration->enfant->prenom ?? '';
                         // Appliquer toutes les rectifications de la dernière fiche (nom/prénom et autres champs enfant)
                         if ($acte->lastRectification && $acte->lastRectification->detailsRectification->count() > 0) {
                             foreach ($acte->lastRectification->detailsRectification as $d) {
                                 if ($d->code_rubrique === 'RUB_0001') {
                                     $nomEnfant = $d->nouvelle_valeur ?? $nomEnfant;
                                 }
                                 if ($d->code_rubrique === 'RUB_0002') {
                                     $prenomEnfant = $d->nouvelle_valeur ?? $prenomEnfant;
                                 }
                             }
                         }
                         @endphp


                        @if($acte->declaration->personne_declaree == "Enfant trouvé")
                            {!! nl2br(e(wordwrap(trim($prenomEnfant), 55, "\n", true))) !!}
                        @else
                            {!! nl2br(e(wordwrap(trim($nomEnfant.' '.$prenomEnfant), 55, "\n", true))) !!}
                        @endif
                        </span></strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Déclaré par: <strong>{!! nl2br(e(wordwrap(($acte->declaration->adoptant != "" ? $acte->declaration->adoptant->nomcomplet() : $acte->declaration->declarant->nomcomplet()) ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale des parents: <strong>{!! nl2br(e(wordwrap(($acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy) ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong> {!! nl2br(e(wordwrap(($acte->declaration->pere ? $acte->declaration->pere->nom.' '.$acte->declaration->pere->prenom : $dummy) ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né le : <strong>
                        @if ($acte->declaration->pere != NULL)
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->pere)->lieu_naissance ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{!! nl2br(e(wordwrap(optional(optional($acte->declaration->pere)->nationalite)->lib_nationalite ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->pere)->niveau_instruction ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->pere)->adresse ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{!! nl2br(e(wordwrap(optional(optional($acte->declaration->pere)->profession)->lib_profession ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong> {!! nl2br(e(wordwrap(($acte->declaration->mere ? $acte->declaration->mere->nom.' '.$acte->declaration->mere->prenom : $dummy) ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Née le : <strong>
                        @if ($acte->declaration->mere != NULL)
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->mere)->lieu_naissance ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{!! nl2br(e(wordwrap(optional(optional($acte->declaration->mere)->nationalite)->lib_nationalite ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->mere)->niveau_instruction ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{!! nl2br(e(wordwrap(optional($acte->declaration->mere)->adresse ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{!! nl2br(e(wordwrap(optional(optional($acte->declaration->mere)->profession)->lib_profession ?? $dummy ?? '', 55, "\n", true))) !!}</strong></td>
                </tr>
                @if($acte->declaration->type_declarant == "Personne physique")
                <tr style="width:100%; text-align: left;">
                    <td>Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="zone-signature-acte" style="position:absolute; bottom:0; left:10px; right:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
            <col style="width: 35%">
            <col style="width: 25%">
            <col style="width: 40%">
            <thead>
              <tr style="text-align: center">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">Le déclarant</td>
                    <td style="text-align: left; vertical-align: top;">
                         @if($acte->approbation_mairie != "")
                         <div style="margin-bottom:0;">
                             @php
                                 $acteVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
                             @endphp
                             <div style="width: 30mm;">
                                 <qrcode value="{{ $acteVerificationUrl }}" ec="H" style="width: 100%;"></qrcode>
                             </div>
                         </div>
                         @else
                         {{-- Réserve d'espace identique pour pagination cohérente (avec ou sans signature) --}}
                         <div style="width: 30mm; height: 30mm;"></div>
                         @endif
                    </td>
                    <td style="text-align: left; vertical-align: top;">
                     <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower($localisation)) }}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>L'officier de l'état civil</p>
                         @if ($acte->approbation_mairie != "")
                             <img src='{{ public_path('app/'.$acte->signature_mairie) }}'><br>
                             <span style="color:black; font-weight:bold"> {{ $acte->signataire->user->personne->nomcomplet() }}</span>
                         @else
                         {{-- Réserve d'espace pour la signature (pagination cohérente) --}}
                         <div style="height: 25mm;"></div>
                         @endif
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
