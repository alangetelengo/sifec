<style>
    td,p{
        font-size: 80%;
    }
    b{
        font-size: 120%;
    }
    small{
        color: red;
    }
</style>
  <page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
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
    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        @php
            $localite = "";
            $localiteParent = "";
            $inst = "";
            $institution = $acte->institutionUser->institution;
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
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:70%; text-align: center;">
                <p>
                    REPUBLIQUE DU CONGO <br>
                    Unité*Travail*Unité <br><br>
                    <strong style="font-size: 20px;"> ACTE DE NAISSANCE <br> <small style="color:#000;font-size: 15px;">(SOUCHE)</small> </strong> <br>

                    {{-- Acte n°:<strong>{{ $acte->niupp }}</strong> --}}
                </p>
            </td>
            <td style="width:15%; text-align: center;">
                @if ($acte->approbation_tribunal == 1)
                    <img src='{{ asset("app/".$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                @endif
            </td>
        </tr><br>
    </table>
    <div style="margin-top: 10px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
        <div style="width: 150px;text-align: left;">
            <p style="text-align: center;">Marge réservée aux mentions <br> d'officier(1) <br><br>
            </p>
            @if ($declarationDeces != NULL)
                <small>Décédé le: <br> {{strftime("%d %B %Y", strtotime(date($declarationDeces->date_heure_deces)))}}</small><br>
                <small>A : {{$declarationDeces->lieu_deces}}</small><br>
                @if ($declarationDeces->acte != NULL)
                <small>N° acte de décès : {{$declarationDeces->acte->code_acte_deces}}</small>
                @endif

            @endif

            @if ($mariage != null)
            @endif
        </div>
        <div style="position: absolute; left: 150px; top: 180px; width: 800px; height: 700px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:17px;border-left: 1px solid black;">
            <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td><strong>{{ $localiteParent }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td><strong>{{ $localite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td>Arrondissement : <strong></strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 8px;">
                    <td><br></td>
                </tr>
                <tr style="width:100%; text-align: center; padding-bottom: 4px;">
                    <td>Acte n° : <strong>{{ $acte->niupp }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 8px;">
                    <td><br></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td>Centre d'état civil de: <strong>{{ $inst }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td>a été déclaré la naissance d'un enfant <strong></strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né " : "Née "  }} le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance)))}}</strong> <br>
                        heures : <strong>{{date("H", strtotime( $acte->declaration->date_heure_naissance))}}H</strong> minutes: <strong>{{date("i", strtotime( $acte->declaration->date_heure_naissance)) }}min</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nommé, nom : <strong>{{ $acte->declaration->enfant->nom }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>prénom : <strong>{{$acte->declaration->enfant->prenom }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong> {{ $acte->declaration->pere ? $acte->declaration->pere->nom." ".$acte->declaration->pere->prenom : $dummy}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né le: <strong>
                        @if ($acte->declaration->pere != NULL)
                           {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) .' '.Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                       @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->lieu_naissance : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié à : <strong>{{  $acte->declaration->pere ? $acte->declaration->pere->adresse : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong> {{ $acte->declaration->mere ? $acte->declaration->mere->nom." ".$acte->declaration->mere->prenom : $dummy}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Née le:
                        <strong>
                            @if ($acte->declaration->mere != NULL)
                            {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                        @endif
                        </strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié à : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->adresse: $dummy }}</strong></td>
                </tr>
                <tr>
                    {{-- <td style="text-align: left;">Le déclarant</td> --}}
                    <td>
                        <div>
                            <strong>Le déclarant</strong>
                        </div>
                        <div style="text-align: right;">
                            <p><strong>L'Officier de l'Etat Civil</strong> </p>
                            <p>
                                @if ($acte->approbation_mairie != "")
                                <img src='{{ asset("app/".$acte->signature_mairie) }}'><br>
                                {{ $acte->signataire->user->personne->nomcomplet() }}
                                @endif
                            </p>

                        </div>
                    </td>
                  </tr>
            </table>
        </div>
    </div>

    <div style="position:absolute; bottom:0;margin-left:40px;border-top: 1px solid #000;">
        <table class="historique" cellspacing="0" style="width: 80%; font-size: 20px;">
            <col style="width: 100%">
            <thead>
              <tr style="text-align: center">
                <td style="text-align: center;"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 70%;text-align: center">(1) le détenteur de l'acte ne devra pas manquer de faire les mentions par l'officier de l'Etat Civil compétent</td>
                </tr>
            </tbody>
        </table>
    </div>

</page>
