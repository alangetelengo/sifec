<style>
    page{
       margin-left: 20px;
    }
    td{
        font-size: 80%;
    }
    b{
        font-size: 120%;
    }
</style>
<page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="14mm" style="font-size: 12pt">
{{-- <page orientation="portrait" backimg="{{ asset("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt"> --}}

    <page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'acte_deces'])
    </page_footer>

    @php
    setlocale(LC_TIME, "fr_FR", "French");
        $departement = "";
        $communeDistrict = "";
        $institution = $acte->declaration->institutionUser->institution;
        $libInstitution = $institution->lib_institution;

        $infos = "";
        // if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
        //     $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acte->declaration->numero_req.' /'.date("Y", strtotime($acte->declaration->date_heure_declaration));
        // }

        // if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
        //     $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.' /'.date("Y", strtotime($acte->declaration->date_heure_declaration));
        // }


        // if($acte->declaration->type_declaration == "DECLARATION TARDIVE"){
        //     $infos = 'ACTE TRANSCRIT SUIVANT LA DECLARATION TARDIVE';
        // }

        if($acte->declaration->requisition != ""){
            $titre = $acte->declaration->requisition->typeRequisition->lib_type_requisition;
            $num = $acte->declaration->requisition->num_requisition;
            $date = $acte->declaration->requisition->date_requisition;
            $infos = 'ACTE ETABLIT SUIVANT LA '.$titre.' N° '.$num.' DU '.(date("d-m-Y", strtotime($date)))." AU ".$acte->declaration->institution->institutionParent->lib_institution;

        }


        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institutionActe = $acte->declaration->institution;
        $localisationDataActe = \App\Sifec\Sifec::getLocalisationInstitution($institutionActe);
        $commune = $localisationDataActe['localite'];
        $localisationActe = $localisationDataActe['localisation'];
        
        $localisationDataInstitution = \App\Sifec\Sifec::getLocalisationInstitution($institution->institutionParent);
        $dept = $localisationDataInstitution['localiteParent'];
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width:35%; text-align: center;">
                <p>
                    <span><strong>{{$dept}}</strong></span> <br>
                    <span>{{$commune}}</span> <br>
                    <span><strong>{{$acte->institutionUser->institution->lib_institution}}</strong></span>
                </p>
            </td>
            <td style="width:30%; text-align: center;">
                <p style="color: red;text-align:center;font-style:italic"><small style="text-transform: uppercase">{{ $infos }}</small></p>
                @if ($acte->approbation_tribunal == 1)
                    <img src='{{ public_path('app/'.$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                @endif

            </td>
            <td style="width:35%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unité - Travail - Progrès
            </td>
        </tr>
  </table><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                <p><strong style="font-size: 150%;"> ACTE DE DECES</strong><br> Année: <strong>{{date("Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> Acte n°:<strong>{{ $acte->code_acte_deces }}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
            </td>
        </tr><br>
    </table>
    <div style="margin-top: 60px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; left: 5px; top: 250px; width: 700px; height: 550px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:18px;">
            <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 15px;">Centre d'état civil secondaire: <strong>
                        {{ $acte->institutionUser->institution->lib_institution }}
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;"><strong> Le
                        {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_heure_declaration))). " ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) . " ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".\App\Sifec\Sifec::asLetters((int)date( "H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minute(s)
                    </strong></td>
                </tr>
                {{-- @php
                    setlocale(LC_TIME, "fr_FR");
                @endphp --}}

                {{-- <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;"><strong> Le {{strftime("%d %B %G", strtotime(date("Y-m-d", strtotime($acte->declaration->date_heure_declaration))))}} </strong></td>
                </tr> --}}
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 15px;">S'est présenté(e) <strong>{{ $acte->declaration->declarant->nom.' '.$acte->declaration->declarant->prenom }}</strong>, Filiation: <strong>{{ $acte->declaration->filiation->lib_filiation }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 15px;">Domicilié(e): <strong>{{ $acte->declaration->declarant->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;padding-bottom: 4px;">
                    <td>qui a déclaré le décès de: <b style="color: red">{{ $acte->declaration->defunt->nom." ".$acte->declaration->defunt->prenom }}</b></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Date de décès: <strong> {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_heure_deces))). " " . \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_deces))) . " " . \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_deces))) ." à ".\App\Sifec\Sifec::asLetters(( (int)date("H", strtotime( $acte->declaration->date_heure_deces))))}} heure(s) {{ \App\Sifec\Sifec::asLetters( (int) date("i", strtotime( $acte->declaration->date_heure_deces))) }} minute(s)</strong>
                </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Lieu de décès: <strong>
                        {{ $acte->declaration->lieu_deces }}
                    </strong></td>
                </tr>
                {{-- <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Cause du décès:
                        @php
                            $causesd = $acte->declaration->DDecesCauses;
                            $v = "";
                        @endphp
                        <strong>
                                @if ($causesd != NULL)
                                    @foreach ($causesd as $item)
                                        {{$v.$item->causeDeces->lib_cause_deces}}
                                        @php
                                            $v = ", ";
                                        @endphp
                                    @endforeach
                                @endif
                        </strong>
                    </td>
                </tr> --}}

                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Sexe: <strong>{{ $acte->declaration->defunt->sexe== "M" ? "Masculin" : "Féminin" }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Nationalité: <strong>{{ $acte->declaration->defunt->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Profession: <strong>{{ $acte->declaration->defunt->profession->lib_profession }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Niveau d'instruction: <strong>{{ $acte->declaration->defunt->niveau_instruction }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Domicile: <strong>{{ $acte->declaration->defunt->adresse }} </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Lieu de survenance: <strong>{{ $acte->declaration->lieuSurvenance->lib_lieu_survenance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    {{-- <td style="height: 15px;">Réligion: <strong>{{ $acte->declaration->religion->lib_religion }}</strong></td> --}}
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">N° acte de naissance: <strong>{{ $acte->declaration->num_acte_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 13px;">Date de naissance: <strong>
                        {{ date('d-m-Y', strtotime($acte->declaration->defunt->date_naissance)) }}
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Lieu de naissance: <strong>{{ $acte->declaration->defunt->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Centre d'état civil de naissance: <strong>{{ $acte->declaration->cec_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Situation matrimoniale: <strong>{{ $acte->declaration->situationMat->lib_situation_matrimoniale }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Fils de: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nom.' '.$acte->declaration->pere->prenom : "" }}</strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Et de: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nom.' '.$acte->declaration->mere->prenom : "" }}</strong>
                    </td>
                </tr>
                @if ($acte->declaration->code_situation_matrimoniale == "SMAT_0001")
                    <tr style="width:100%; text-align: left;">
                        <td style="height: 15px;">Option de mariage: <strong>{{ $acte->declaration->code_regime != NULL ? $acte->declaration->regime->lib_regime :"" }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td style="height: 15px;">N° acte de mariage: <strong>{{ $acte->declaration->num_acte_mariage }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td style="height: 15px;">Date de mariage: <strong>
                            {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_mariage))) ." à ".date("H", strtotime( $acte->declaration->date_mariage)). " heure(s) ".date("i", strtotime( $acte->declaration->date_mariage)) }} minute(s)
                        </strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                    </tr>
                @endif
                {{-- <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Fils de : <strong>{{ $acte->declaration->pere->nom }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 15px;">Et de : <strong>{{ $acte->declaration->mere->nom }}</strong></td>
                </tr> --}}
            </table>
        </div><br>
    </div>

    <div style="position:absolute; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 15px;">
            <col style="width: 40%">
            <col style="width: 25%">
            <col style="width: 35%">
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
                         @if($acte->approbation_pompe_funebre != "")
                         <div style="margin-bottom:0;">
                             @isset($qrCode)
                                <div style="width: 30mm;">
                                    <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                                </div>
                                <span style="font-size: 6.5px; color: #555;">Scanner pour authentifier</span>
                             @endisset
                         </div>
                         @endif
                    </td>
                    <td style="text-align: left;">

                       <p>Fait à {{ ucfirst(strtolower($localisationActe)) }}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $acte->date_emission)))}}</p>

                       @if ($acte->approbation_pompe_funebre != "")
                        <p>L'officier de l'état civil</p>
                            @if (filled($acte->signature_pompe_funebre))
                                @php
                                    $pathSigDeces = public_path('app/'.ltrim(trim((string) $acte->signature_pompe_funebre), '/'));
                                    $srcSigDecesPdf = (is_file($pathSigDeces) && is_readable($pathSigDeces))
                                        ? str_replace('\\', '/', $pathSigDeces)
                                        : '';
                                @endphp
                                @if ($srcSigDecesPdf !== '')
                                    <img src="{{ $srcSigDecesPdf }}" width="100" height="100" alt=""><br>
                                @endif
                            @endif
                            @if (optional(optional($acte->signataire)->user)->personne)
                                <span style="color:black; font-weight:bold">{{ $acte->signataire->user->personne->nomcomplet() }}</span>
                            @endif
                            @php
                                $__signeLeActeDeces = $acte->signed_at ?? $acte->doc_sig_signed_at ?? $acte->date_heure_approbation_pompe_funebre ?? null;
                            @endphp
                            @if ($__signeLeActeDeces)
                                <br><span style="font-size: 8px; color:#006B31;">Signé électroniquement le {{ \Carbon\Carbon::parse($__signeLeActeDeces)->format('d/m/Y à H:i') }}</span>
                            @endif
                        @endif <br>

                    </td>
                  </tr>
            </tbody>
        </table>
    </div>

</page>
