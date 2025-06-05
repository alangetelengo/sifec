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
	  backright="20mm"
	  footer="date;heure;page" >

	<bookmark title="Lettre" level="0" ></bookmark>

    <page_header >
        @php
        $localite = "";
        $localiteParent = "";
        $inst = "";
        $institution = $certificat->institutionUser->institution;
        $localisation = "";
        setlocale(LC_TIME, "fr_FR", "French");

        $inst = $institution->lib_institution;
        $localite = " COMMUNE DE ".$institution->lieu->localiteParent->lib_localite;
        $localiteParent  = "DEPARTEMENT DE ". $institution->lieu->localiteParent->localiteParent->lib_localite;
        $localisation = $institution->lieu->localiteParent->lib_localite;
    @endphp

        <div id="entete_rprt_suite">
            <p style="text-align: left;">
                <span>
                    <strong>{{ $localiteParent }}</strong>
                </span> <br>
                <span>{{ $localite}}</span> <br>
                <span>MAIRIE CENTRALE</span>
            </p>

        </div>

        <div id="sifec">
            <?php

            echo "<strong>REPUBLIQUE DU CONGO</strong><br/>";
            echo "Unité - Travail - Progr&egrave;s"."<br/>";
            ?>
        </div>

    </page_header>

	<table cellspacing="0" style="border-collapse: collapse; margin-top: 50px;">
		<col style="width: 15%">
		<col style="width: 35%">
		<col style="width: 35%">
		<col style="width: 15%">

		{{-- <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center"  >&nbsp;</td>
			<td style="border-bottom: solid; padding:5px 0px;text-align: center" colspan="2" >&nbsp;</td>
			<td style="border-right: none; padding:5px 0px;text-align: center" >&nbsp;</td>

		</tr> --}}

		<tr>
			<td style="border-right: solid; padding:5px 0px;text-align: center">&nbsp;</td>
			<td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 18px;font-weight:bold;">{{ $certificat->type_declaration }} </span></td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>
        {{-- <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>

		</tr> --}}

		<tr>
			<td style="border: none; padding:5px 0px;text-align:center ">&nbsp;</td>
			<td style="border: none; padding:5px 0px;text-align:center " colspan="2"><span style="font-size: 14px;font-weight:bold;">N&deg;  {{ $certificat->numero_certificat }}</span> du <span style="font-size: 14px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($certificat->date_heure_declaration))) }}</span></td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>

        <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>

		</tr>
    </table>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements enfant</strong></legend>
        <table cellspacing="0" style="border-collapse: collapse; ">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 25%">

            <tr>
                <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil de : MAIRIE CENTRALE</td>
                <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé que le : {{ utf8_encode(strftime("%d %B %Y", strtotime($certificat->date_heure_declaration))). " A " .date("H", strtotime($certificat->date_heure_declaration))." heures ".date("s", strtotime($certificat->date_heure_declaration))." miniutes" }}</td>
                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est né, un enfant de sexe : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->enfant->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->enfant->prenom}} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->enfant->lieu_naissance}} </span>&nbsp;&nbsp;&nbsp;CEC de naissance:<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->cec_naissance}} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Pays de naissance : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->pays_naissance_enfant}} </span>&nbsp;&nbsp;&nbsp; Lieu de survenance :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->lieuSurvenance ? $certificat->lieuSurvenance->lib_lieu_survenance : $dummy }} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->sitMatParent ? $certificat->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
            </tr>

            <tr>
            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nombre d'enfant(s) vivant(s) y compris celui-ci : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->nombre_enfant ? $certificat->nombre_enfant : $dummy }} </span></td>
            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
        </tr>
        </table>
        </fieldset>
    </div>

    <div style="width: 100%">
        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements père</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->pere ? $certificat->pere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 14px;font-weight:bold;"> {{$certificat->pere ? utf8_encode(strftime("%d %B %Y", strtotime($certificat->pere->date_naissance))) : $dummy }}</span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Lieu de naissance : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->lieu_naissance : $dummy }} </span>
                    </td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3"> Domicile : <span style="font-size: 14px;font-weight:bold;">{{ Sifec::adressepersonne($certificat->pere->code_personne) }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalité : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->nationalite->lib_Nationalité : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
        </fieldset>

        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements mère</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->mere ? $certificat->mere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 14px;font-weight:bold;"> {{$certificat->mere ? strftime("%d %B %Y", utf8_encode(strtotime($certificat->mere->date_naissance))) : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Lieu de naissance : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->lieu_naissance : $dummy }}</span>

                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3"> Domicile : <span style="font-size: 14px;font-weight:bold;"> {{ Sifec::adressepersonne($certificat->mere->code_personne) }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalité : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->nationalite->lib_Nationalité : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
        </fieldset>

        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements déclarant</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->declarant->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 14px;font-weight:bold;"> {{ $certificat->declarant->prenom}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->declarant->sexe == "M" ? "Masculin" : "Féminin" }} </span>&nbsp;&nbsp;&nbsp;&nbsp; Date de naissance : <span style="font-size: 14px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($certificat->declarant->date_naissance))) }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->declarant->lieu_naissance}} </span>&nbsp;&nbsp;&nbsp;Domicile : <span style="font-size: 14px;font-weight:bold;"> {{ Sifec::adressepersonne($certificat->declarant->code_personne) }} </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Téléphone :<span style="font-size: 14px;font-weight:bold;">{{ $certificat->declarant->telephone }} &nbsp;&nbsp;&nbsp;</span>Nationalité : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->declarant->nationalite->lib_nationalite }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Filiation : <span style="font-size: 14px;font-weight:bold;"> {{ $certificat->filiation ? $certificat->filiation->lib_filiation : $dummy }}</span>
                        &nbsp;&nbsp;&nbsp; Profession : <span style="font-size: 14px;font-weight:bold;">{{ $certificat->declarant->profession->lib_profession  }}</span> </td>
                </tr>

            </table>
        </fieldset>
            <p>En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit. /-</p>
            <p style="margin-left: 40%;">Fait à {{$localisation}}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration)))}}<br><br>
                L'Officier de l'Etat Civil</p>
            <div style="position:absolute; margin-left:570px;top:80px;">
                <qrcode value="{{env('QRCODE_URL')}}/qrcode/naissance/certificat?niupp={{ $certificat->code_declaration_naissance }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
            </div>

    </div>



</page>
