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
    color: #000;2
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



<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimg="{{ str_replace('\\', '/', public_path('tpl/armoirie_congo.png')) }}"  backimgw="100%"
	  backtop="10mm"
	  backbottom="15mm"
	  backleft="10mm"
	  backright="20mm"
	  footer="date;heure;page" >

	<bookmark title="Lettre" level="0" ></bookmark>

	<page_header>


        <div id="entete_rprt_suite">
            <?php
            echo "<strong>".htmlentities("MINISTERE DE LA SANTE ET DE LA POPULATION  ")."</strong>";
            ?>
            <BR>
            <strong style='margin-left:100px'>************************ </strong><br>
            <strong style="margin-left:40px">{{ $ddc->institutionUser->institution->lib_institution  }} </strong>
            <BR>

        </div>

        <div id="sifec">
            <?php
            setlocale(LC_TIME, "fr_FR", "French");
            echo "<strong style='margin-right:100px;'>REPUBLIQUE DU CONGO</strong><br/>";
            echo "<strong style='font-size:11px;margin-left:-140px;font-weight:normal'>Unité - Travail - Progr&egrave;s"."</strong><br/>";

            ?>
        </div>

    </page_header>

	<page_footer>
        @include('partials.guot.mention-legale-pied', ['typeDocument' => 'certificat_constatation_deces'])
    </page_footer>


<br><br><br>
	<table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">


		<tr>
			<td style="border-right: solid; padding:5px 0px;text-align: center">&nbsp;</td>
			<td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 15px;font-weight:bold;">{{ $ddc->type_declaration }} </span></td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>

		<tr>
			<td style="border: none; padding:5px 0px;text-align:center ">&nbsp;</td>
			<td style="border: none; padding:5px 0px;text-align:center " colspan="2"><span style="font-size: 15px;font-weight:bold;">N&deg;  {{ $ddc->numero_certificat }}</span> du <span style="font-size: 15px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($ddc->date_heure_declaration))) }}</span></td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>

		<tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>

		</tr>
    </table>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Information</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Je soussigné(e) : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->nom_medecin }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: left" colspan="3">Chef de centre d'Hygiène Générale de : {{ $ddc->institutionUser->institution->lib_institution }} </td>
                    <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: left" colspan="3">Certifie avoir constaté ce jour le décès de : </td>
                    <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                </tr>

            </table>
        </fieldset>
        {{-- <img style="margin-left: 70%" src="{{asset("signature.png") }}" width="10%" height="20%" alt=""> --}}

    </div>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements du défunt</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                {{-- <col style="width: 25%"> --}}

                {{-- <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="4">Est informé que le : <span style="font-size: 15px;font-weight:bold;">{{ strftime("%d %B %Y", strtotime($ddc->date_heure_deces)) }}  à {{ $ddc->lieu_deces; }}  </span> </td>

                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est décédé(e), une personne de sexe : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->defunt->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr> --}}
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">{{ $ddc->defunt->sexe == "M" ? "Nommé" : "Nommée" }} : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->defunt->nom ." ". $ddc->defunt->prenom}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->defunt->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalité :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->nationalite->lib_nationalite}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->profession->lib_profession}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->dernierAdresse() ?? " "}} </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Religion :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->religion->lib_religion}}</span> </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">N&deg; acte de naissance : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->num_acte_naissance }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 15px;font-weight:bold;"> {{ strftime("%d %B %Y", strtotime($ddc->defunt->date_naissance)) }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de naissance : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->cec_naissance }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Fils de : : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->pere->nom }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Et de : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->mere->nom }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->situationMat->lib_situation_matrimoniale }}</span> </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                @if ($ddc->code_situation_matrimoniale == "SMAT_0001")
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">Option de mariage : <span style="font-size: 15px;font-weight:bold;"> {{$ddc->regime != NULL ? $ddc->regime->lib_regime :"" }}</span> </td>
                        <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">N&deg; acte de mariage : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->num_acte_mariage }} </span></td>
                        <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de mariage : <span style="font-size: 15px;font-weight:bold;"> {{ strftime("%d %B %Y", strtotime($ddc->date_mariage)) }} </span></td>
                        <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) et prénom (s) conjoint (e) : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->conjoint->nom."  ".$ddc->conjoint->prenom}} </span></td>
                        <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de mariage :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->cec_mariage }} </span></td>
                        <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                    </tr>
                @endif

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de déc&egrave;s :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->lieuDeces?->lib_localite ?? $ddc->lieu_deces }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: "  colspan="3">Lieu de survenance : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->lieusurvenance->lib_lieu_survenance }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

            </table>
        </fieldset>
    </div>
    @php
        $localisationConst = \App\Sifec\Sifec::getLocalisationInstitution($ddc->institution)['localisation'] ?? 'Brazzaville';
    @endphp
    @include('deces::etats.partials.signature-pied', [
        'ddc' => $ddc,
        'localisation' => $localisationConst,
        'contexteForcage' => $contexteForcage ?? 'centre_hygiene',
    ])

</page>
