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
        padding-left: 3mm;
        font-size: 14px;
        text-align: left;
    }
    /* Corps un peu plus compact : limite les sauts à 2 pages sur actes longs (pied en absolute) */
    table.acte-bi-colonne td.acte-texte table td {
        font-size: 13px;
        line-height: 1.2;
    }
    table.acte-bi-colonne td.acte-texte b {
        font-size: 13px;
    }
    td.acte-ligne-institution {
        overflow-wrap: anywhere;
        word-wrap: break-word;
        word-break: normal;
    }
</style>
  <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="22mm" style="font-size: 14px">
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

    if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
        $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }


    @endphp
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
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                @if ((int) $acte->approbation_tribunal === 1 && filled($acte->sceau_tribunal))
                    <img src="{{ public_path('app/'.$acte->sceau_tribunal) }}" alt="" width="100" height="100" style="display: block; margin: 0 auto 3mm auto;">
                @endif
                <p><strong style="font-size: 18px;">ACTE DE NAISSANCE</strong>
                    {{-- <br> Acte n°:<strong>{{ $acte->numeroActe->numero_acte }}</strong> --}}
                    <br>N°: <strong style="color: red">{{ $acte->niupp }} R.A.N {{ $acte->registre->created_at->format('Y') }}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>

        </tr><br>
    </table>
    {{-- Bi-colonne en flux (pas en position:absolute) : évite le chevauchement avec le sceau / titre sous Html2Pdf --}}
    <div style="margin-top: 5mm; margin-left: 5%; margin-right: 5%;">
        <table class="acte-bi-colonne" cellspacing="0" cellpadding="0">
            <tr>
                <td class="acte-marge" style="text-align: center; border-right: 0.5mm solid #000;">
            <p style="margin: 0 0 4px 0; line-height: 1.2;"><strong>Marge réservée aux mentions <br> d'officier ({{ $nombreMentions ?? 0 }})</strong></p>

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
                <small>A : {{$declarationDeces->lieu_deces}}</small><br>
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
                    <td class="acte-ligne-institution">L'Officier du centre d'état civil principal de: <strong>{!! Sifec::wrapLibInstitutionPourActePdf($acte->institutionUser->institution->lib_institution ?? null, 30) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong>

                        {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong>

                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong> à </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
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
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
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
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
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

    {{-- Pied hors flux : bottom:0 uniquement (valeur négative → débordement Html2Pdf et retour à 2 pages) --}}
    <div style="position:absolute; bottom:0; margin-left:10px;">
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
                    <td style="text-align: left;">
                         @if($acte->approbation_mairie != "")
                         <div style="margin-bottom:0;">
                             @isset($qrCode)
                                <div style="width: 30mm;">
                                    <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                                </div>
                             @endisset
                         </div>
                         @endif

                        {{-- <div style="margin-bottom:0;"><qrcode value="http://172.16.41.11/sifec-20-12-2023/public/qrcode?niupp={{ $acte->niupp }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div> --}}
                    </td>
                    <td style="text-align: left;">
                     <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>L'officier de l'état civil</p>
                         @if ($acte->approbation_mairie != "")
                             <img src='{{ public_path('app/'.$acte->signature_mairie) }}'><br>
                             <span style="color:black; font-weight:bold"> {{ \App\Sifec\Sifec::formatNomPrenomPourActe($acte->signataire->user->personne->nom, $acte->signataire->user->personne->prenom) }}</span>
                         @endif
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
