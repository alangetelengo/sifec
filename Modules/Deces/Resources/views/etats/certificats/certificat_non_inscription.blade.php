<style>
    td{
        font-size: 90%;
        padding: 3px;
    }
    b,p{
        font-size: 90%;
    }
</style>
<page orientation="portrait" backimg="{{ str_replace('\\', '/', public_path('tpl/armoirie_congo.png')) }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" footer="date;time;page" style="font-size: 12pt">
    @php
        $departement = "";
        $communeDistrict = "";
        $mairie = "";
        $institution = $certificat->institutionUser->institution;
        $libInstitution = $institution->lib_institution;
        setlocale(LC_TIME, "fr_FR", "French");

        if ($institution->code_arrondissement != NULL) {
            $mairie = "MAIRIE DE ".$institution->arrondissement->commune->lib_commune;
            $communeDistrict = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
            $departement  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
            $localisation = $institution->arrondissement->commune->lib_commune;
        }

        if ($institution->code_commune != NULL) {
            $mairie = "MAIRIE DE ".$institution->commune->lib_commune;
            $communeDistrict = "COMMUNE DE ".$institution->commune->lib_commune;
            $departement  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
            $localisation = $institution->commune->lib_commune;
        }

        if ($institution->code_communaute_urbaine != NULL) {
            $mairie = "MAIRIE DE ".$institution->communauteUrbaine->district->lib_district;
            $communeDistrict = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
            $departement  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }

        if ($institution->code_district != NULL) {
            $mairie = "MAIRIE DE ".$institution->communauteUrbaine->district->lib_district;
            $communeDistrict = "DISTRICT DE ".$institution->district->lib_district;
            $departement  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width:40%; text-align: center;">
                <p style="font-size: 110%;"><span>{{$departement}}</span> <br>
                    <span>{{$communeDistrict}}</span> <br>
                    <span><strong>{{$certificat->institutionUser->institution->lib_institution}}</strong></span>
                </p>
            </td>
            <td style="width:25%; text-align: center;">

            </td>
            <td style="width:35%; text-align: center;font-size: 110%;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;border:solid;">
                <p><strong style="font-size: 150%;"> CERTIFICAT DE NON INSCRIPTION DE L'ACTE DE DECES</strong><br> Année: <strong>{{date("Y")}}</strong>  N°: <strong>{{$certificat->numero_certificat}}</strong></p>
            </td>
        </tr><br>
    </table>

    <div style="margin-top: 3%;margin-left: 2%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; left: 20px; top: 185px; width: 700px; height: 500px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:16px;">
            {{-- <P style="text-align: justify;">Je soussigné, {{$certificat->institutionUser->user->personne->nomcomplet()." (".$certificat->institutionUser->fonction->lib_fonction.")"}} --}}
            <P style="text-align: justify;">Je soussigné, MALONGA Alfonse

                , Directeur des Pompes Funèbres Municipales de BRAZZAVILLE, la déclaration de décès:
            </P>
            <table align="left" style="border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 2px;">
                                         <td>Par les <strong>{{ Str::ucfirst($certificat->institutionUser->institution->lib_institution) }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 2px;">
                    <td>Défunt : <strong>{{$certificat->defunt->nom}} {{$certificat->defunt->prenom}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né(e), le: <strong>{{strftime("%d %B %Y", strtotime( $certificat->defunt->date_naissance))}}</strong>, à <strong>{{$certificat->defunt->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Sexe : <strong>{{ $certificat->defunt->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $certificat->defunt->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Profession: <strong>{{ $certificat->defunt->profession->lib_profession }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $certificat->defunt->niveau_instruction }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicile: <strong>{{$certificat->defunt->adresse ?? "NON DÉCLARÉ" }} </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Cause du décès:
                        @php
                            $causesd = $certificat->DDecesCauses;
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
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Lieu de survenance: <strong>{{ $certificat->lieuSurvenance->lib_lieu_survenance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Lieu de décès: <strong>{{ $certificat->lieu_deces }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale: <strong>{{ $certificat->situationMat->lib_situation_matrimoniale }}</strong></td>
                </tr>
                @if ($certificat->code_situation_matrimoniale == "SMAT_0001")
                    <tr style="width:100%; text-align: left;">
                        <td>Option de mariage: <strong>{{ $certificat->code_regime != NULL ? $certificat->regime->lib_regime :"" }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>N° acte de mariage: <strong>{{ $certificat->num_acte_mariage }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Date de mariage: <strong>{{ strftime("%d %B %Y", strtotime($certificat->date_mariage)) }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Nom(s) et prénom(s) du conjoint(e): <strong>{{ $certificat->conjoint->nom.' '.$certificat->conjoint->prenom }}</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Centre d'état civil de mariage: <strong>{{ $certificat->cec_mariage }}</strong></td>
                    </tr>

                    {{-- @else
                    <tr style="width:100%; text-align: left;">
                        <td>Option de mariage: <strong>AUCUN</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>N° acte de mariage: <strong>AUCUN</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Date de mariage: <strong>AUCUN</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Nom(s) et prénom(s) du conjoint(e): <strong>AUCUN</strong></td>
                    </tr>
                    <tr style="width:100%; text-align: left;">
                        <td>Centre d'état civil de mariage: <strong>AUCUN</strong></td>
                    </tr> --}}
                @endif

            </table>
            <p style="text-align: justify;font-size: 85%;margin-right: 6%;margin-top: 10px;">Certifions que l'acte de décès dudit défunt n'a pas été dressé. <br>
                En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit. /-
            </p>
        </div>
    </div>
    <div style="margin-top: 480px; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 100%;">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 50%">
            <thead>
              <tr style="text-align: center">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;">
                        <div style="margin-bottom:0;"><qrcode value="{{env('QRCODE_URL')}}/qrcode/deces/certificat?niupp={{ $certificat->code_declaration_deces }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div>
                    </td>
                    <td style="text-align: left;">
                        <p style="font-size: 120%;">Fait à {{$localisation}}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration)))}}<br>L'Officier de l'Etat Civil, pour le Maire par délégation <br>
                            Le Directeur des Pompes Funèbres Municipales</p>

                    </td>
                  </tr>
            </tbody>
        </table>

    </div>
    <p style="text-align: left; font-style:italic; font-size:11px"><span style="color:red">(*)</span> Ce document requiert une réquisition ou un jugement</p>


</page>
