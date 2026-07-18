<style>
    td{
        font-size: 12px;
    }
    b{
        font-size: 12px;
    }
    small{
        color: red;
    }
    button#print{
        display: none;
    }
    .acte-corps-principal table td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }
    table.acte-bi-colonne {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }
    /* Bordure à droite de la marge : mieux rendue par Html2Pdf/TCPDF que border-left sur la 2e colonne */
    table.acte-bi-colonne td.acte-marge {
        width: 18%;
        vertical-align: top;
        font-size: 10px;
        word-wrap: break-word;
        overflow-wrap: break-word;
        padding-right: 2mm;
        border-right: 0.5mm solid #000000;
    }
    table.acte-bi-colonne td.acte-texte {
        width: 82%;
        vertical-align: top;
        padding-left: 2mm;
        font-size: 12px;
        text-align: left;
    }
    /* Corps compact : tient sur une page A4 avec pied absolu (Html2Pdf) */
    table.acte-bi-colonne td.acte-texte table td {
        font-size: 11.5px;
        line-height: 1.1;
    }
    table.acte-bi-colonne td.acte-texte b {
        font-size: 11.5px;
    }
    td.acte-ligne-institution {
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: normal;
    }
</style>
  <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="52%" backimgw="55%" backtop="0" backbottom="14mm" style="font-size: 12px">
    <page_footer>
        <div style="width: 100%; text-align: center; font-size: 9px; color: #5a3d1e; line-height: 1.25; padding: 1mm 4mm 2mm 4mm;">
            Cet acte de naissance est un document officiel de l'état civil de la République du Congo. Toute falsification ou usage frauduleux est puni par la loi.
        </div>
    </page_footer>
    @php
    $infos = "";
    // Tribunal : t_declaration_naissance → réquisition/jugement (code_* ou code_declaration) → tr_institution
    $tribunal = null;
    try {
        $tribunal = $acte->declaration->libInstitutionTribunalPourMentionActe();
    } catch (\Throwable $e) {
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

    // Numéro : declaration.numero_req souvent vide ; t_requisition.num_requisition via code_requisition ou code_declaration
    $numeroRequisitionAffiche = trim((string) ($acte->declaration->numero_req ?? ''));
    if ($numeroRequisitionAffiche === '') {
        $numeroRequisitionAffiche = trim((string) (optional($acte->declaration->requisitionParCode)->num_requisition ?? ''));
    }
    if ($numeroRequisitionAffiche === '') {
        $numeroRequisitionAffiche = trim((string) (optional($acte->declaration->requisition)->num_requisition ?? ''));
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$numeroRequisitionAffiche." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$numeroRequisitionAffiche." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
        $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$numeroRequisitionAffiche." ".$num;
    }


    @endphp
    {{-- Html2Pdf : regroupe en-tête + corps + pied sur une page si la hauteur le permet (évite 2e page quasi vide avec seul le filigrane). --}}
    <nobreak>
    <table cellspacing="0" style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width:40%; text-align: center;">
                @php
                   setlocale(LC_TIME, "fr_FR", "French");

                    $institution = $acte->institutionUser->institution;
                    $departement = $institution->lieu->localiteParent->localiteParent;
                    $communeDistrict = $institution->lieu->localiteParent;
                @endphp
                @if(Auth::user() != null && Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution != "TPINS_0005")
                <p>
                    <span>
                    {{ "DEPARTEMENT DE ".$departement->lib_localite }}
                    <br>
                        {{ "COMMUNE DE ".$communeDistrict->lib_localite }}
                    </span> <br>
                    <span><strong>{{ $institution->lib_institution }}</strong></span>
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
                <p style="color: red">{{ $infos != "" ? $infos : "" }}</p>
            </td>
            <td style="width:33%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                @if ((int) $acte->approbation_tribunal === 1 && filled($acte->sceau_tribunal))
                    @php
                        $__pdfSceauSrc = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($acte->sceau_tribunal);
                    @endphp
                    @if ($__pdfSceauSrc)
                    <img src="{{ $__pdfSceauSrc }}" alt="" width="80" height="80" style="display: block; margin: 0 auto 2mm auto;">
                    @endif
                @endif
                <p style="margin: 0;"><strong style="font-size: 16px;">ACTE DE NAISSANCE</strong>
                    @if ($acte->approbation_mairie != "")
                    <br>N°: <strong style="color: red">{{ $acte->niupp }} R.A.N {{ $acte->registre->created_at->format('Y') }}</strong>
                    @endif
                </p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>

        </tr><br>
    </table>
    {{-- Bi-colonne en flux (pas en position:absolute) : évite le chevauchement avec le sceau / titre sous Html2Pdf --}}
    <div style="margin-top: 2mm; margin-left: 4%; margin-right: 4%; padding-bottom: 1mm;">
        <table class="acte-bi-colonne" cellspacing="0" cellpadding="0">
            <tr>
                <td class="acte-marge" style="text-align: center; border-right: 0.5mm solid #000;">
            <p style="margin: 0 0 4px 0; line-height: 1.2;"><strong>Marge réservée aux mentions <br> d&#39;officier
                @if(($nombreMentions ?? 0) > 0)
                    ({{ $nombreMentions }})
                @endif
            </strong></p>

            @if ($mariage != null)
                <small>Marié(e) le: <br> {{ date("d-m-Y", strtotime($mariage->date_prevue_mariage))}}</small><br>
                @if ($mariage->acte != NULL)
                    @php
                        $inst = "";
                        $institution = $acte->institutionUser->institution;

                        if ($institution->code_arrondissement != NULL) {
                            $inst = $institution->lib_institution;
                        }

                        if ($institution->code_commune != NULL) {
                            $inst = "COMMUNE DE ".$institution->commune->lib_commune;
                        }

                        if ($institution->code_communaute_urbaine != NULL) {
                            $inst = $institution->lib_institution;
                        }

                        if ($institution->code_district != NULL) {
                            $inst = $institution->lib_institution;
                        }
                    @endphp
                    <small>A : LA {{$inst}}</small><br>
                    <small>N° acte de mariage : {{$mariage->acte->code_acte_mariage}}</small>
                    <small>{{ $acte->declaration->enfant->sexe=="M" ? "Epouse: ".\App\Sifec\Sifec::formatNomPrenomPourActe(optional($mariage->acte->declaration->epouse)->nom, optional($mariage->acte->declaration->epouse)->prenom) : "Epoux: ".\App\Sifec\Sifec::formatNomPrenomPourActe(optional($mariage->acte->declaration->epoux)->nom, optional($mariage->acte->declaration->epoux)->prenom) }}</small>
                @endif
            @endif

            @if ($declarationDeces != NULL)
                <small>Décédé le: <br> {{utf8_encode(strftime("%d %B %Y", strtotime($declarationDeces->date_heure_deces)))." à ".date("H:i", strtotime($declarationDeces->date_heure_deces))}} minute(s)</small><br>
                <small>A : {{ optional($declarationDeces->lieuDeces)->lib_localite }}</small><br>
                @if ($declarationDeces->acte != NULL)
                <small>N° acte de décès : {{$declarationDeces->acte->code_acte_deces}}</small>
                @endif
            @endif
           @if($acte->declaration->jugement != null)
                @if($acte->declaration->adoptant != "")
                <small>{{ $acte->declaration->enfant->sexe == "M" ? "Adopté" : "Adoptée" }} par <strong>{{ \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->adoptant->nom, $acte->declaration->adoptant->prenom) }}</strong></small><br>
                <small>Jugement N&deg; : <strong> {{ $acte->declaration->jugement->num_jugement }}</strong> </small>
                <small>du : <strong>{{ date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)) }}</strong> </small>
                <small>au : <strong>{{ $acte->declaration->jugement->institutionUser->institution->institutionParent->lib_institution }}</strong></small> <br>
                @if($acte->declaration->type_adoption == "adoption pleniere")
                    <small>N&deg; ancien acte de naissance  <strong>{{ $acte->declaration->jugement->numero_ancien_acte }}</strong></small>
                @endif
                @endif
                @if($acte->declaration->jugement->type_jugement == "JUGEMENT SUPPLETIF" || $acte->declaration->jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                    {{-- <small>{{ $acte->declaration->enfant->sexe == "M" ? "Adopté" : "Adoptée" }} par <strong>{{ $acte->declaration->adoptant->nomcomplet() }}</strong></small><br> --}}
                    <small>{{ $acte->declaration->jugement->type_jugement }} N&deg;: <strong> {{ $acte->declaration->jugement->num_jugement }}</strong> </small>
                    <small><br>du : <strong>{{ date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)) }}</strong> </small>
                    <small><br>au : <strong>{{ $acte->declaration->jugement->institutionUser->institution->institutionParent->lib_institution }}</strong></small> <br>
                    @if($acte->declaration->jugement->type_jugement == "JUGEMENT D'HOMOLOGATION")
                    <small>N&deg; ancien acte de naissance  <strong>{{ $acte->declaration->jugement->numero_ancien_acte }}</strong></small>
                    @endif
                @endif
                
                {{-- Mention pour acte d'annulation --}}
                @if($acte->declaration->jugement->type_jugement == "JUGEMENT D'ANNULATION D'ACTE" || ($acte->est_acte_annulation ?? false))
                    <small><strong style="color: red; font-size: 110%;">ACTE ANNULÉ</strong></small><br>
                    <small>Suivant JUGEMENT D'ANNULATION N&deg;: <strong>{{ $acte->declaration->jugement->num_jugement }}</strong></small><br>
                    <small>du : <strong>{{ date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)) }}</strong></small><br>
                    <small>au : <strong>{{ $acte->declaration->jugement->institutionUser->institution->institutionParent->lib_institution }}</strong></small><br>
                    @if($acte->niupp_acte_annule ?? $acte->declaration->jugement->numero_ancien_acte)
                    <small>N&deg; acte annulé : <strong>{{ $acte->niupp_acte_annule ?? $acte->declaration->jugement->numero_ancien_acte }}</strong></small>
                    @endif
                @endif
           @endif
          {{-- recuperer les rectification de l'acte avec ses détails--}}
            @if (isset($acte->rectifications) && $acte->rectifications->count() > 0)
                @if ($acte->rectifications->count() > 0)

                    @foreach ($acte->rectifications as $rectification)
                        <small>Suivant le jugement émanant du <strong>{{  $rectification->institutionUser->institution->institutionParent->lib_institution  }}</strong>
                            en date du <strong>{{ date("d-m-Y", strtotime($rectification->updated_at)) }}</strong>, sous le
                            N&deg;: <strong>{{ $rectification->numero_rectification }}</strong>, l"acte ci-contre est réctifié en ce sens
                            que,
                        </small>
                        @if ($rectification->detailsRectification->count() > 0)

                            @foreach ($rectification->detailsRectification as $detail)
                                <small>le <strong>{{ $detail->rubrique->lib_rubrique }}</strong> du titulaire est</small>
                                <small><strong>{{ $detail->nouvelle_valeur }}</strong> au lieu de <strong>{{ $detail->ancienne_valeur }}</strong></small><br>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endif
                </td>
                <td class="acte-texte acte-corps-principal">
            <table align="left" style="width: 100%; border-radius: 1mm; border: none; table-layout: fixed;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td class="acte-ligne-institution">L'Officier du centre d'état civil principal de: <strong>{!! \App\Sifec\Sifec::wrapLibInstitutionPourActePdf($acte->institutionUser->institution->lib_institution ?? null, 30) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong>

                        {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong>

                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong> à </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }}
                       <span style="color: red;">
                         @php
                         $nomEnfant = "";
                         $prenomEnfant = "";
                         @endphp
                        {{-- Si l'acte a une dernière rectification, on prend les valeurs de la dernière rectification --}}
                        {{-- Sinon, on prend les valeurs de la déclaration --}}
                        @if($acte->lastRectification)

                            @if($acte->lastRectification->detailsRectification->last()->code_rubrique == "RUB_0001")
                            @php
                                $nomEnfant = $acte->lastRectification->detailsRectification->last()->nouvelle_valeur;
                            @endphp
                            @endif
                            @if($acte->lastRectification->detailsRectification->last()->code_rubrique == "RUB_0002")
                            @php
                                $prenomEnfant = $acte->lastRectification->detailsRectification->last()->nouvelle_valeur;
                            @endphp
                            @endif
                        @else
                            @php
                                $nomEnfant = $acte->declaration->enfant->nom;
                                $prenomEnfant = $acte->declaration->enfant->prenom;
                            @endphp
                        @endif
                        {{ \App\Sifec\Sifec::formatNomPrenomPourActe($nomEnfant, $prenomEnfant) }}
                        </span></strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Déclaré par: <strong>{{ $acte->declaration->adoptant != "" ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->adoptant->nom, $acte->declaration->adoptant->prenom) : \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->declarant->nom, $acte->declaration->declarant->prenom) }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale des parents: <strong>{{ $acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong> {{ $acte->declaration->pere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->pere->nom, $acte->declaration->pere->prenom) : $dummy}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né le : <strong>
                        @if ($acte->declaration->pere != NULL)
                            {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->pere->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{{ $acte->declaration->pere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong> {{ $acte->declaration->mere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->mere->nom, $acte->declaration->mere->prenom) : $dummy}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Née le : <strong>
                        @if ($acte->declaration->mere != NULL)
                            {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->mere->lieu_naissance }}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{{ $acte->declaration->mere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                @if($acte->declaration->type_declarant == "Personne physique")
                <tr style="width:100%; text-align: left;">
                    <td>Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant }}</strong></td>
                </tr>
                @endif
            </table>
                </td>
            </tr>
        </table>
    </div>

    {{-- Pied en flux : l’absolute bottom:0 faisait souvent une 2e page fantôme (filigrane seul) avec Html2Pdf/TCPDF. --}}
    <div style="margin-top: 14mm; margin-left: 6px; margin-right: 10px;">
        <table class="historique" cellspacing="0" style="width: 100%; table-layout: fixed; font-size: 12px;">
            <col style="width: 30%">
            <col style="width: 28%">
            <col style="width: 42%">
            <tbody>
                <tr>
                    <td style="text-align: center; vertical-align: top;">Le déclarant</td>
                    <td style="text-align: center; vertical-align: top;">
                         @if($acte->approbation_mairie != "")
                             @isset($qrCode)
                                <qrcode value="{{ $qrCode }}" ec="H" style="width: 24mm; border: none;"></qrcode>
                                <br>
                                <span style="font-size: 6.5px; color: #555;">Scanner pour authentifier</span>
                             @endisset
                         @endif
                    </td>
                    <td style="text-align: right; vertical-align: top; padding-right: 3mm;">
                     <p style="font-size: 12px; margin: 0 0 1mm 0;">
                         Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>
                         L'officier de l'état civil
                     </p>
                         @if ($acte->approbation_mairie != "")
                             @php
                                 $__pdfSignatureSrc = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($acte->signature_mairie);
                             @endphp
                             @if ($__pdfSignatureSrc)
                             <img src="{{ $__pdfSignatureSrc }}" style="width: 28mm;"><br>
                             @endif
                             <span style="color:black; font-weight:bold">{{ \App\Sifec\Sifec::formatNomPrenomPourActe($acte->signataire->user->personne->nom, $acte->signataire->user->personne->prenom) }}</span>
                         @endif
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
    </nobreak>
</page>
