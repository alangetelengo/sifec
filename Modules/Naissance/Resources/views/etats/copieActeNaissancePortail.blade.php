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
</style>
  <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0" backbottom="30mm" style="font-size: 14px">

    @php
    $infos = "";
    $tribunal = $acte->declaration->institutionUser->institution->tribunal;
    if ($tribunal != NULL) {
        $tribunal = $acte->declaration->institutionUser->institution->tribunal->lib_tribunal;
    }

    $num = "";
    if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
        $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
    } else {
        $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
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
        @php
            $localite = "";
            $localiteParent = "";
            $inst = "";
            $institution = $institutionPortail;
            $localisation = "";
            setlocale(LC_TIME, "fr_FR", "French");

            if ($institution->code_arrondissement != NULL) {
                $inst = $institution->lib_institution;
                $localite = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
                $localiteParent  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
                $localisation = $institution->arrondissement->commune->lib_commune;
            }

            if ($institution->code_commune != NULL) {
                $inst = "COMMUNE DE ".$institution->commune->lib_commune;
                $localite  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
                $localisation = $institution->commune->lib_commune;
            }

            if ($institution->code_communaute_urbaine != NULL) {
                $inst = $institution->lib_institution;
                $localite = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
                $localiteParent  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
                $localisation = $institution->communauteUrbaine->district->lib_district;
            }

            if ($institution->code_district != NULL) {
                $inst = $institution->lib_institution;
                $localite = "DISTRICT DE ".$institution->district->lib_district;
                $localiteParent  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
                $localisation = $institution->communauteUrbaine->district->lib_district;
            }
        @endphp
        <tr>
            <td style="width:40%; text-align: center;">
                <p>
                    <span>
                        <strong>{{ $localite }}</strong>
                    </span> <br>
                    {{-- <span>{{ $localite}}</span> <br> --}}
                    <span>{{ $inst }}</span>
                </p>
            </td>
            <td style="width:34%; text-align: center;">
                <p style="color: red">{{ $infos != "" ? $infos : "" }}</p>
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
                <p><strong style="font-size: 18px;">COPIE INTEGRALE D'ACTE DE NAISSANCE</strong> <br> Acte n°:<strong>{{ $acte->numeroActe->numero_acte }}</strong> <br>NIUPP: <strong>{{ $acte->niupp }}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>

        </tr><br>
    </table>
    <div style="margin-top: 60px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
        <div style="width: 150px;text-align: center;">
            <p>Marge réservée aux mentions <br> d'officier(1) <br><br>
            </p>

            @if ($mariage != null)
                {{-- <small>Marié(e) le: <br> {{strftime("%d %B %Y", strtotime(date($mariage->date_declaration_mariage)))}}</small><br> --}}
                <small>Marié(e) le: <br> {{ date("d-m-Y", strtotime($mariage->date_prevue_mariage))}}</small><br>
                {{-- <small>Avec : {{$declarationDeces->lieu_deces}}</small><br> --}}
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
                    <small>{{$acte->declaration->enfant->sexe=="M" ? "Epouse: ".$mariage->acte->declaration->epouse->nomcomplet() : "Epoux: ".$mariage->acte->declaration->epoux->nomcomplet() }}</small>
                @endif
            @endif

            @if ($declarationDeces != NULL)
                <small>Décédé le: <br> {{strftime("%d %B %Y", strtotime(date($declarationDeces->date_heure_deces)))}}</small><br>
                <small>A : {{$declarationDeces->lieu_deces}}</small><br>
                @if ($declarationDeces->acte != NULL)
                <small>N° acte de décès : {{$declarationDeces->acte->code_acte_deces}}</small>
                @endif

                {{-- ************** --}}
            @endif
        </div>
        <div style="position: absolute; left: 150px; top: 240px; width: 700px; height: 500px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;border-left: 1px solid black;">
            <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td>L'Officier du centre d'état civil de: <strong>{{ $acte->institutionUser->institution->lib_institution}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".Sifec::asLetters((int)date("s", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong></td>
                </tr>
                {{-- <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".Sifec::asLetters((int)date("s", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong></td>
                </tr> --}}
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
                    <td><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }} {{ $acte->declaration->enfant->nom." ".$acte->declaration->enfant->prenom }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Déclaré par: <strong>{{ $acte->declaration->declarant->nom. " ".$acte->declaration->declarant->prenom }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Filiation: <strong>{{ $acte->declaration->filiation ?  $acte->declaration->filiation->lib_filiation : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale des parents: <strong>{{ $acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong> {{ $acte->declaration->pere ? $acte->declaration->pere->nom." ".$acte->declaration->pere->prenom : $dummy}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Né le : <strong>
                        @if ($acte->declaration->pere != NULL)
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                        @endif
                        {{-- {{ $acte->declaration->pere ? date("d",strtotime($acte->declaration->pere->date_naissance)) ." ".Sifec::mois(date("m",strtotime($acte->declaration->pere->date_naissance))) ." ". date("Y", strtotime($acte->declaration->pere->date_naissance)) : $dummy }} --}}
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
                    <td>Domicilié à : <strong>{{ $acte->declaration->pere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong> {{ $acte->declaration->mere ? $acte->declaration->mere->nom." ".$acte->declaration->mere->prenom : $dummy}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Née le : <strong>
                        @if ($acte->declaration->mere != NULL)
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                        @endif
                        {{-- {{ $acte->declaration->mere ? date("d",strtotime($acte->declaration->mere->date_naissance)) ." ".Sifec::mois(date("m",strtotime($acte->declaration->mere->date_naissance))) ." ". date("Y", strtotime($acte->declaration->mere->date_naissance)) : $dummy }} &nbsp;&nbsp;&nbsp; à  <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy }}</strong> --}}
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->mere->lieu_naissance }}</strong></td>
                </tr>
                {{-- <tr style="width:100%; text-align: left;">
                    <td></td>
                </tr> --}}
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié à : <strong>{{ $acte->declaration->mere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div style="position:absolute; bottom:0;margin-left:10px;">
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
                        @isset($qrCode)
                        <div style="margin-bottom:0; width: 30mm;">
                            <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                        </div>
                        @endisset
                    </td>
                    <td style="text-align: left;">
                     <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower(trans($localisation)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date('Y-m-d'))))}}<br>L'Officier de l'état civil</p>
                         {{-- @if ($acte->approbation_mairie != "") --}}
                             <img src='{{ public_path('app/'.$signatairePortail->signature) }}'><br>
                             {{ $signatairePortail->nom.' '.$signatairePortail->prenom }}
                         {{-- @endif --}}
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
