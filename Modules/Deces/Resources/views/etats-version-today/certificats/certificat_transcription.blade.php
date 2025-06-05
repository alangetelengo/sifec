<style type="text/css">

    #sifec{
    margin-top: -7%;
    text-align:right;
    padding: 11px 2px;
    padding-bottom: 11px;
    padding-right: 10px;

    }
    #entete_rprt_suite{

   /*  text-align:right; */
    padding: 11px 2px;
    padding-bottom: 11px;
    padding-left: 10px;

    }

    legend {
    background-color:#FFFFFF;
    color: #000;
    padding: 3px 6px;
    border: none;
    }
    fieldset {
        font-size:110%;
        font-family: Arial;
        padding-left: 20px;
        float: left;
    }
</style>


<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimg="{{ asset("tpl/armoirie_congo.png") }}"  backimgw="100%"
	  backtop="10mm"
	  backbottom="15mm"
	  backleft="10mm"
	  backright="20mm">

	<bookmark title="Lettre" level="0" ></bookmark>

    <page_header>
        @php
        $localite = "";
        $localiteParent = "";
        $inst = "";
        $mairie = "";
        $institution = $certificat->institutionUser->institution;
        $localisation = "";
        setlocale(LC_TIME, "fr_FR", "French");

        if ($institution->code_arrondissement != NULL) {
            $mairie = " MAIRIE DE ".$institution->arrondissement->commune->lib_commune;
            $inst = $institution->lib_institution;
            $localite = " COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
            $localiteParent  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
            $localisation = $institution->arrondissement->commune->lib_commune;
        }

        if ($institution->code_commune != NULL) {
            $mairie = " MAIRIE DE ".$institution->commune->lib_commune;
            $inst = " COMMUNE DE ".$institution->commune->lib_commune;
            $localite  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
            $localisation = $institution->commune->lib_commune;
        }

        if ($institution->code_communaute_urbaine != NULL) {
            $mairie = " MAIRIE DE ".$institution->communauteUrbaine->district->lib_district;
            $inst = $institution->lib_institution;
            $localite = " DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
            $localiteParent  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }

        if ($institution->code_district != NULL) {
            $mairie = " MAIRIE DE ".$institution->communauteUrbaine->district->lib_district;
            $inst = $institution->lib_institution;
            $localite = "DISTRICT DE ".$institution->district->lib_district;
            $localiteParent  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }
    @endphp

        <div id="entete_rprt_suite">
            <p style="text-align: left;">
                <span style="text-align: center;">
                    <strong>{{ $localiteParent }}</strong>
                </span> <br>
                <span style="text-align: center;">{{ $localite}}</span> <br>
                <span style="text-align: center;">{{" ".$inst }}</span>
            </p>

        </div>

        <div id="sifec">
            <strong>REPUBLIQUE DU CONGO</strong><br/>
            Unité * Travail * Progr&egrave;s
        </div>

    </page_header>

	{{--  <table style="text-align: center;">
		<tr style="text-align: center;">
			<td>
                <p style="padding:5px 0px;text-align: center;font-size: 24px;font-weight:bold;"></p>
            </td>
		</tr>
    </table>  --}}
    <br><br><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;border:solid;">
                <p><strong style="font-size: 150%;">{{ $certificat->type_declaration }} DE DECES</strong><br> Année: <strong>{{date("Y")}}</strong>  N°: <strong>{{$certificat->numero_certificat}}</strong></p>
            </td>
        </tr><br>
    </table><br>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements du défunt</strong></legend>
        <table cellspacing="0" style="border-collapse: collapse; ">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 25%">

            <tr>
                <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil de : {{ $inst }}</td>
                <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé que le : {{ strftime("%d %B %Y", strtotime($certificat->date_heure_deces)). " A " .date("H", strtotime($certificat->date_heure_deces))." heures ".date("i", strtotime($certificat->date_heure_deces))." miniutes" }}</td>
                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Du décès, de : </td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->defunt->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->defunt->prenom}} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->defunt->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->defunt->lieu_naissance }} </span></td>
                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->situationMat->lib_situation_matrimoniale}} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->lieuSurvenance ? $certificat->lieuSurvenance->lib_lieu_survenance : "" }} </span> </td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Adresse: <span style="font-size: 13px;font-weight:bold;">{{ Sifec::adressepersonne($certificat->defunt->code_personne) }} </span></td>
            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
        </tr>
        </table>
        </fieldset>
    </div>

    <div style="width: 100%">
        @if( $certificat->conjoint != null)
        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements du conjoint(e)</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->conjoint->nom}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->conjoint->prenom }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Option de mariage : <span style="font-size: 13px;font-weight:bold;"> {{$certificat->code_regime ? $certificat->regime->lib_regime : "" }} </span>

                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">N° acte de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->num_acte_mariage }}</span>
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ date("d/M/Y", strtotime($certificat->date_mariage)) }} </span>
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->cec_mariage }} </span>
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
        </fieldset>
        @endif


        {{-- <fieldset style="margin-top:5px">
            <legend><strong>Renseignements mère</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->mere ? $certificat->mere->nom : ""}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->prenom : ""}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$certificat->mere ? strftime("%d %B %Y", strtotime($certificat->mere->date_naissance)) : "" }} </span>

                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->lieu_naissance : "" }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->adresse : "" }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->nationalite->lib_nationalite : "" }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->niveau_instruction : "" }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->profession->lib_profession : "" }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->telephone : "" }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
        </fieldset> --}}

            <fieldset style="margin-top:5px">
                <legend><strong>Renseignements déclarant</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->declarant->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->declarant->prenom}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->declarant->sexe == "M" ? "Masculin" : "Féminin" }} </span>&nbsp;&nbsp;&nbsp;&nbsp; Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ strftime("%d %B %Y", strtotime($certificat->declarant->date_naissance)) }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->declarant->lieu_naissance }} </span>&nbsp;&nbsp;&nbsp;Domicile : <span style="font-size: 13px;font-weight:bold;">{{ Sifec::adressepersonne($certificat->declarant->code_personne) }} </span>  </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Téléphone :<span style="font-size: 13px;font-weight:bold;">{{ $certificat->declarant->telephone }} &nbsp;&nbsp;&nbsp;</span>Nationalité : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->declarant->nationalite->lib_nationalite }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Filiation : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->filiation ? $certificat->filiation->lib_filiation : "" }}</span>
                        &nbsp;&nbsp;&nbsp; Profession : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->declarant->profession->lib_profession  }}</span> </td>
                </tr>

            </table>
            </fieldset>
            <p style="font-size: 110%;">En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit. /-</p>

                <div style="margin-top: 0px; bottom:0;margin-left:10px;">
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
                                    <p>Fait à {{$localisation}}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration)))}}<br>L'Officier de l'Etat Civil, pour le Maire par délégation <br>
                                        Le Directeur des Pompes Funèbres Municipales</p>

                                </td>
                              </tr>
                        </tbody>
                    </table>
                </div>

    </div>



</page>
