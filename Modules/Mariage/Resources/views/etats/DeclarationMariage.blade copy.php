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
    #mention{
        text-align: center;
        color: #FF0000;
        font-size: 15px;
        padding-top: -10px;
    }
    legend{
        font-weight: bold;
        text-transform: uppercase;
    }
</style>
@php
// Utiliser le service Sifec pour obtenir les informations de localisation
$institution = $dm->institutionUser->institution;
$localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);

$localite = $localisationData['localite'];
$localiteParent = $localisationData['localiteParent'];
$inst = $localisationData['inst'];
$localisation = $localisationData['localisation'];
@endphp

<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimg="{{ public_path("tpl/armoirie_congo.png") }}"  backimgw="100%"
	  backtop="10mm"
	  backbottom="15mm"
	  backleft="10mm"
	  backright="20mm"
	  footer="page" >

	<bookmark title="Lettre" level="0" ></bookmark>

    <page_header >



        <div id="mention">
           {{-- {{ $mention }} --}}
        </div>
        <div id="sifec">
            <br>
            <?php
            setlocale(LC_TIME, "fr_FR", "French");
            ?>
        </div>

    </page_header>
    <div style="text-align:left">
            <strong>{{ $localiteParent }}</strong><br>
            <strong>{{ $localite }}</strong><br>
            <strong>{!! nl2br(e(wordwrap($dm->institution->lib_institution ?? '', 55, "\n", true))) !!}</strong>
        </div>
        <div style="text-align:right;margin-top:-50px">
            <strong>REPUBLIQUE DU CONGO</strong><br>
            <!-- <strong>unité - Travail - Progrès</strong><br> -->

        </div>
        <div style="text-align:right;margin-right:10px">
            <!-- <strong>REPUBLIQUE DU CONGO</strong><br> -->
            <span >Unité - Travail - Progrès</span><br>

        </div>


	<table cellspacing="0" style="border-collapse: collapse; margin-top:30px">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">

		<tr>
			<td style="border-right: none; padding:5px 0px;text-align: center"  >&nbsp;</td>
			<td style="border-bottom: solid; padding:5px 0px;text-align: center" colspan="2" >&nbsp;</td>
			<td style="border-right: none; padding:5px 0px;text-align: center" >&nbsp;</td>

		</tr>

		<tr>
			<td style="border-right: solid; padding:5px 0px;text-align: center">&nbsp;</td>
			{{-- <td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 20px;;"><strong>FORMULAIRE TYPE CAS DU MARIAGE </strong> </span></td> --}}
			<td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 20px;;"><strong> FORMULAIRE TYPE</strong></span></td>
			<td style="border: none; padding:5px 0px;">&nbsp;</td>
		</tr>

		<tr>
			<td style="border: none; padding:5px 0px;text-align:center ">&nbsp;</td>
			<td style="border: none; padding:5px 0px;text-align:center " colspan="2"><strong><span style="font-size:13px;;">N&deg;  {{ $dm->code_declaration_mariage }}</span> du <span style="font-size:13px;;">{{ date("d-m-Y", strtotime($dm->date_declaration_mariage)) }}</span></strong></td>
			<td style="border: none; padding:5px 0px;">&nbsp;</td>
		</tr>

        <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">
                @if ($dm->type_mariage == "PROCURATION")
                   <strong style="color:red;margin-top:5px">MARIAGE PAR PROCURATION</strong>
                @endif
            </td>

		</tr>
    </table>
    <div style="width: 100%;">
        <p style="font-size: 13px;"> <strong>Centre d’état civil principal de : </strong>{!! nl2br(e(wordwrap($inst ?? '', 55, "\n", true))) !!}<br>
             Le <strong>{{ Sifec::asLetters((int)date("d", strtotime($dm->date_declaration_mariage)))." ".Sifec::mois(date("m", strtotime($dm->date_declaration_mariage)))." ".Sifec::asLetters(date("Y", strtotime($dm->date_declaration_mariage))) }}</strong> <br>
             Par devant nous, </p>
            <br>
            <legend>Les futurs époux</legend>
            <hr>
            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 50%">
                <col style="width: 50%">
                <tr>
                    <td style="border: none; padding:5px 0px;padding-left: 20px;" colspan="3">  <strong><span style="font-size:13px;;"> {{ $dm->type_mariage == 'POSTHUME' ? ' (FEU) ' : " M. " }} {{ $dm->epoux->nom ." ".ucfirst($dm->epoux->prenom) }}</span></strong></td>
                    <td style="border: none; padding:5px 0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date de naissance :</strong><span style="font-size:13px;;"> {{ Sifec::asLetters((int)date("d", strtotime($dm->epoux->date_naissance)))." ".Sifec::mois(date("m", strtotime($dm->epoux->date_naissance)))." ".Sifec::asLetters(date("Y", strtotime($dm->epoux->date_naissance))) }} </span>  </td>
                    <td style="border: none; padding:5px 0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Lieu de naissance :</strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->epoux->lieu_naissance ?? optional($dm->epoux->commune)->lib_commune ?? optional($dm->epoux->district)->lib_district ?? $dm->epoux->lieu_naissance ?? '', 55, "\n", true))) !!} </span>, N° d'acte de naissance : <span style="font-size:13px;;">  {{ $dm->numero_acte_naissance_epoux }}</span></td>
                    <td style="border: none; padding:5px 0px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"> <strong>CEC de naissance :</strong><span style="font-size:13px;;"> {{ $dm->cec_naissance_epoux }} </span>, Nationalité: <span style="font-size:13px;;"> {{ $dm->epoux->nationalite->lib_nationalite }} </span></td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                @if ($dm->epoux->nationalite->code_nationalite != "NAT_0001")
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"> <strong>Certificat résidence :</strong><span style="font-size:13px;;"> {{ $dm->certificat_residence_epoux }} </span> du <span style="font-size:13px;;"> {{ date("m-d-Y", strtotime($dm->date_emission_certificat_residence_epoux)) }} </span>

                        , Autorisation ambassade: <span style="font-size:13px;;"> {{ $dm->autorisation_ambassade_epoux }} </span> du <span style="font-size:13px;;"> {{ date("d-m-Y", strtotime($dm->date_autorisation_ambassade_epoux)) }} </span>

                    </td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                @endif
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Profession : </strong><span style="font-size:13px;;"> {{ $dm->professionEpoux->lib_profession }} </span>,  <strong>Situation matrimoniale:</strong> <span style="font-size:13px;;"> {{ $dm->situationMatEpoux->lib_situation_matrimoniale }} </span>
                        @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0005")
                        <strong>,N&deg; du jugement du divorce :</strong> <span style="font-size:13px;;">{{ $dm->numero_jugement_divorce_epoux }}</span>
                        @endif
                        @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0006")
                        <strong>,N&deg; d'acte de décès de l'épouse : </strong><span style="font-size:13px;;">{{ $dm->numero_acte_deces_epouse }}</span>
                        @endif
                        @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0001")
                        <strong>N&deg; d'acte de mariage : </strong><span style="font-size:13px;;">{{ $dm->numero_acte_mariage_epoux }}</span><br><br>
                        <strong>Option précédent mariage: </strong><span style="font-size:13px;;">{{ $dm->optionMariage->lib_option_mariage }}</span>
                        @endif
                         </td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Domicilié : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->epoux->adresse ?? '', 55, "\n", true))) !!} </span></td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom du père : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->pere_epoux ?? '', 55, "\n", true))) !!} </span></td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom de la mère : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->mere_epoux ?? '', 55, "\n", true))) !!} </span></td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nombre d'enfant(s) : </strong><span style="font-size:13px;;"> {{ $dm->nbre_enfant ?? "0" }}</span></td>
                    <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                </tr>
            </table>
            <br>

                @if ($dm->nom_prenom_mandant_epoux != "")

                    <legend>Le mandant</legend>

                    <table>
                    <tr>
                        <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom(s) et prénom(s) du mandant : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->nom_prenom_mandant_epoux ?? '', 55, "\n", true))) !!}</span></td>
                        <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                    </tr>
                    </table>
                @endif



        <table cellspacing="0" style="border-collapse: collapse; margin-top: 20px;">
            <col style="width: 50%">
            <col style="width: 50%">

            <tr>
                <td style="border: none; padding:5px 0px;padding-left: 20px;" colspan="3"> <strong><span style="font-size:13px;;">Et Mme. {{ $dm->epouse->nom ." ". ucfirst($dm->epouse->prenom) }}</span></strong></td>
                <td style="border: none; padding:5px 0px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date de naissance :</strong><span style="font-size:13px;"> {{ Sifec::asLetters((int)date("d", strtotime($dm->epouse->date_naissance)))." ".Sifec::mois(date("m", strtotime($dm->epouse->date_naissance)))." ".Sifec::asLetters(date("Y", strtotime($dm->epouse->date_naissance))) }} </span>  </td>
                <td style="border: none; padding:5px 0px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Lieu de naissance :</strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->epouse->lieu_naissance ?? optional($dm->epouse->commune)->lib_commune ?? optional($dm->epouse->district)->lib_district ?? $dm->epouse->lieu_naissance ?? '', 55, "\n", true))) !!} </span>, N° acte de naissance : <span style="font-size:13px;;">  {{ $dm->numero_acte_naissance_epouse }}</span></td>
                <td style="border: none; padding:5px 0px;">&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"> <strong>CEC de naissance: </strong><span style="font-size:13px;;"> {{ $dm->cec_naissance_epouse }} </span>, Nationalité: <span style="font-size:13px;;"> {{ $dm->epouse->nationalite->lib_nationalite }} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            @if ($dm->epouse->nationalite->code_nationalite != "NAT_0001")
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"> <strong>Certificat résidence: </strong><span style="font-size:13px;;"> {{ $dm->certificat_residence_epouse }} </span> du <span style="font-size:13px;;"> {{ date("m-d-Y", strtotime($dm->date_emission_certificat_residence_epouse)) }} </span>

                    , Autorisation ambassade: <span style="font-size:13px;;"> {{ $dm->autorisation_ambassade_epouse }} </span> du <span style="font-size:13px;;"> {{ date("d-m-Y", strtotime($dm->date_autorisation_ambassade_epouse)) }} </span>

                </td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            @endif

            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Profession : </strong><span style="font-size:13px;;"> {{ $dm->professionEpouse->lib_profession }} </span>,  Situation matrimoniale: <span style="font-size:13px;;"> {{ $dm->situationMatEpouse->lib_situation_matrimoniale }} </span>
                    @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0005")
                    <br> <strong>N&deg; du jugement du divorce : </strong><span style="font-size:13px;;">{{ $dm->numero_jugement_divorce_epouse }}</span>
                    @endif
                    @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0006")
                    <br> <strong>N&deg; d'acte de décès de l'épouse : </strong><span style="font-size:13px;;">{{ $dm->numero_acte_deces_epoux }}</span>
                    @endif
                    @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0001")
                    <strong> N&deg; d'acte de mariage : </strong><span style="font-size:13px;;">{{ $dm->numero_acte_mariage_epouse }}</span>
                    @endif
                        </td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Domiciliée : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->epouse->adresse ?? '', 55, "\n", true))) !!} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom du père : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->pere_epouse ?? '', 55, "\n", true))) !!} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom de la mère : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->mere_epouse ?? '', 55, "\n", true))) !!} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Chef de famille : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->chef_famille ?? '', 55, "\n", true))) !!} </span> - Filiation : <span style="font-size:13px;;"> {!! nl2br(e(wordwrap(optional($dm->filiation)->lib_filiation ?? '', 55, "\n", true))) !!} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0005")
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date du précédent mariage : </strong><span style="font-size:13px;;"> {{ $dm->chef_famille }} </span> filiation : <span style="font-size:13px;;"> {{ $dm->filiation->lib_filiation }} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"> <strong>Références de l’avis du jugement de divorce : </strong><span style="font-size:13px;;"> {{ $dm->chef_famille }} </span> filiation : <span style="font-size:13px;;"> {{ $dm->filiation->lib_filiation }} </span></td>
                <td style="border: none; padding:5px 0px;" >{{ $dm->numero_jugement_divorce_epouse }}</td>
            </tr>
            @endif

            @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0006")
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date du précèdent mariage : </strong><span style="font-size:13px;;"> {{ $dm->chef_famille }} </span> - Filiation : <span style="font-size:13px;;"> {{ $dm->filiation->lib_filiation }} </span></td>
                <td style="border: none; padding:5px 0px;" >&nbsp;</td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"> <strong>Références de l’avis du jugement de divorce : </strong><span style="font-size:13px;;"> {{ $dm->chef_famille }} </span> filiation : <span style="font-size:13px;;"> {{ $dm->filiation->lib_filiation }} </span></td>
                <td style="border: none; padding:5px 0px;" >{{ $dm->numero_jugement_divorce_epouse }}</td>
            </tr>
            @endif
        </table>


        <table cellspacing="0" style="border-collapse: collapse; ">

            @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0002")
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date du pré-mariage : </strong>{{ date("d-m-Y", strtotime($dm->date_pre_mariage_epouse)) }}</td>
                <td></td>
            </tr>
            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong> Liste des parents ayant présidé au pré-mariage :</strong> <br>
                <span style="text-align:left">
                    <strong>Parents paternels : </strong><span style=";"> {!! nl2br(e(wordwrap($dm->parent_paternel_epouse ?? '', 55, "\n", true))) !!}</span><br>
                    <strong>Parents maternels : </strong> <span style=";"> {!! nl2br(e(wordwrap($dm->parent_maternel_epouse ?? '', 55, "\n", true))) !!}</span><br>
                    </span>
                </td>
                <td>

                </td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Montant de la dot :  </strong><span style=";">&nbsp;&nbsp;{{ $dm->montant_dot }} FCFA</span></td>
                <td>

                </td>
            </tr>

            @endif
        </table>
<br>
        @if ($dm->nom_prenom_mandant_epouse != "")

                    <legend>La mandante</legend>

                    <table>
                    <tr>
                        <td style="border: none; padding:5px 0px;" colspan="3"><strong>Nom(s) et prénom(s) de la mandante : </strong><span style="font-size:13px;;"> {!! nl2br(e(wordwrap($dm->nom_prenom_mandant_epouse ?? '', 55, "\n", true))) !!}</span></td>
                        <td style="border: none; padding:5px 0px;" >&nbsp;</td>
                    </tr>
                    </table>
                @endif



        <legend>Célébration du mariage </legend>
        <hr>
        <table cellspacing="0" style="border-collapse: collapse; ">

            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Date de mariage : </strong><span style="color:red;font-weight:bold">{{ date("d-m-Y", strtotime($dm->date_prevue_mariage)) }}</span></td>
                <td></td>
            </tr>

            <tr>
                <td style="border: none; padding:5px 0px;" colspan="3"><strong>Lieu de mariage : </strong><span style="color:red;font-weight:bold">{!! nl2br(e(wordwrap($dm->lieu_ceremonie_mariage ?? '', 55, "\n", true))) !!}</span></td>
                <td></td>
            </tr>


        </table>


    <br><br>
        <legend>Demander aux futurs époux s’ils ont été déjà mariés</legend>
        <hr>

        <p><strong>A- Question de l’officier de l’état civil et réponse du futur époux</strong> <br>
        &nbsp;&nbsp; Avez-vous déjà été marié ?:
        @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0001")
            &nbsp;&nbsp; Date du précèdent mariage
                <span style=";">{{ date("d-m-Y", strtotime($dm->date_celebration_mariage)) }}</span><br>
                &nbsp;&nbsp; Option du précédent mariage :
            @if($dm->optionMariage->code_option_mariage == "OMRG_0002")
            &nbsp;&nbsp; Avis de votre épouse :
                    @if($dm->avis_epouse == 1)
                        <span style=";">OUI</span>
                        &nbsp;&nbsp; Références de l’avis du jugement de divorce :
                        <span style=";">{{ $dm->numero_jugement_divorce_epoux }}</span><br>
                    @endif
            @endif
            @if($dm->optionMariage->code_option_mariage == "OMRG_0001")
            &nbsp;&nbsp; Avis de votre épouse :
                @if($dm->avis_epouse == 1)
                    &nbsp;&nbsp; Références de l’avis de votre épouse :
                    <span style=";">{{ $dm->reference_avis_epouse }}</span><br>
                @endif
            @endif
            @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0005")
            &nbsp;&nbsp; Date du précédent mariage
                <span style=";">//</span><br>
                &nbsp;&nbsp; Références de l’avis du jugement de divorce :
                <span style=";">{{ $dm->numero_jugement_divorce_epoux }}</span><br>
        @endif
        @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0006")
            &nbsp;&nbsp; Date du précèdent mariage
                <span style=";">//</span><br>
                &nbsp;&nbsp; Numéro de l'acte de décès de l'épouse :
                <span style=";">{{ $dm->numero_acte_deces_epouse }}</span><br>
        @endif
        @else
            Non
        @endif
    </p>
    <p>
        <strong>B-  Question de l’officier de l’état civil et réponse de la future épouse</strong><br>
        &nbsp;&nbsp; Avez-vous déjà été marié ?:
        @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0005")
            &nbsp;&nbsp; Date du précédent mariage
                <span style=";">//</span><br>
                &nbsp;&nbsp; Références de l’avis du jugement de divorce :
                <span style=";">{{ $dm->numero_jugement_divorce_epoux }}</span><br>
        @endif
        @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0006")
            &nbsp;&nbsp; Date du précèdent mariage
                <span style=";">//</span><br>
                &nbsp;&nbsp; Numéro de l'acte de décès de l'épouse :
                <span style=";">{{ $dm->numero_acte_deces_epouse }}</span><br>
        @endif
        @if ($dm->situationMatEpouse->code_situation_matrimoniale != "SMAT_0005" && $dm->situationMatEpouse->code_situation_matrimoniale != "SMAT_0006" )
            Non
        @endif
        <br><br>
        <legend>Demander aux futurs époux s’ils sont pré- mariés</legend>
        <hr>
        <strong>A- Question de l’officier de l’état civil et réponse du futur époux</strong><br>
        <p>1- Etes-vous pré-marié? <strong>OUI</strong></p>
        <p>2- Date du pré-mariage : <strong>{{ date("d-m-Y", strtotime($dm->date_pre_mariage_epouse)) }}</strong></p>
        <p>3- Quels sont les parents qui ont présidé au pré-mariage ?</p>
        <p>Parents paternels : <strong>{!! nl2br(e(wordwrap($dm->parent_paternel_epoux ?? '', 55, "\n", true))) !!}</strong></p>
        <p>Parents maternels : <strong>{!! nl2br(e(wordwrap($dm->parent_maternel_epoux ?? '', 55, "\n", true))) !!}</strong></p>
        <p>4- Quel est le montant de la dot versé ? (sur interpellation des parents de l’époux) <strong>50 000 FCFA</strong></p>

        <strong>A- Question de l’officier de l’état civil et réponse à la future épouse</strong>
        <p>1- Etes-vous pré-marié? <strong>OUI</strong></p>
        <p>2- Date du pré-mariage : {{ date("d-m-Y", strtotime($dm->date_pre_mariage_epouse)) }}</p>
        <p>3- Quels sont les parents qui ont présidé au pré-mariage ?</p>
        <p>Parents paternels : <strong>{!! nl2br(e(wordwrap($dm->parent_paternel_epouse ?? '', 55, "\n", true))) !!}</strong></p>
        <p>Parents maternels : <strong>{!! nl2br(e(wordwrap($dm->parent_maternel_epouse ?? '', 55, "\n", true))) !!}</strong></p>
        <p>4- Quel est le montant de la dot versé ? (sur interpellation des parents de l’époux) <strong>50 000 FCFA</strong></p>
    </p>
    <p>
        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
        <legend>Question de l’officier de l’état civil et réponse du futur époux</legend>
        <hr>
        <strong>A- Question sur les résultats des examens prénuptiaux</strong>
        <p>1- Connaissance des résultats des examens prénuptiaux : &nbsp;&nbsp;<span style=";"> {{ $dm->examens_prenuptiaux == 1 ? "OUI" : "NON" }}</span></p>
        <p>2-	Persistez-vous à vous marier ? <strong>OUI</strong></p>

        <legend>Question de l’officier de l’état civil et réponse de la future épouse</legend>
        <hr>
        <strong>A- Question sur les résultats des examens prénuptiaux</strong>
        <p>1- Connaissance des résultats des examens prénuptiaux : &nbsp;&nbsp;<span style=";"> {{ $dm->examens_prenuptiaux == 1 ? "OUI" : "NON" }}</span></p>
        <p>2-	Persistez-vous à vous marier ? <strong>OUI</strong></p>
        @if($dm->type_mariage != "POSTHUME")
            <legend>Option de polygamie ou de monogamie</legend>
            <hr>
            <strong>A-	Question de l’officier de l’état civil et réponse du futur époux</strong>
            <p>Optez-vous pour la polygamie ? <strong>{{ $dm->optionMariage->code_option_mariage == "OMRG_0001" ? 'OUI':'NON' }}</strong></p>
            <strong>B-	Question de l’officier de l’état civil et réponse du futur époux</strong>
            <p>Acceptez-vous la polygamie ? <strong>{{ $dm->optionMariage->code_option_mariage == "OMRG_0001" ? 'OUI':'NON' }}</strong></p>



            <legend>Choix du régime matrimonial</legend>
            <hr>
            @if ($dm->optionMariage->code_option_mariage == "OMRG_0001")
            <strong>Les époux ont opté pour la polygamie</strong>
            <p>L’officier de l’état civil leur indique que la polygamie entraine pour eux le régime de la séparation des biens (RSB)</p>
            @else
            <strong>Les époux ont opté pour la monogamie</strong>
            <p>Quel régime matrimonial choisissez-vous ?<strong> {{ $dm->regime->lib_regime}}</strong></p>
            @endif
        @endif
    </p>
    <br><br><br><br>  <br><br><br><br>  <br><br>
    {{-- Liste des témoins --}}
    <table cellspacing="0" style="border-collapse: collapse; ">
        <col style="width: 50%">
        <col style="width: 50%">
        <tr>
            <td style="border: none; padding:5px 0px; " colspan="3">
                <legend>Liste des témoins</legend>
                <hr>
                <strong>A-Les témoins du futur époux </strong><br>
                1-	Premier témoin <br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Noms et Prénoms : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinHommeEpoux->nom ." ". ucfirst($dm->temoinHommeEpoux->prenom) }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Né le : </strong>&nbsp;&nbsp;<span style=";">{{ date("d", strtotime($dm->temoinHommeEpoux->date_naissance)) ." ". Sifec::mois(date("m", strtotime($dm->temoinHommeEpoux->date_naissance))) ." ".date("Y", strtotime($dm->temoinHommeEpoux->date_naissance)) ." à ".$dm->temoinHommeEpoux->lieu_naissance }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Profession : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinHommeEpoux->profession->lib_profession }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Domicilié à : </strong>&nbsp;&nbsp;<span style="">{!! nl2br(e(wordwrap(optional($dm->temoinHommeEpoux)->adresse ?? '', 55, "\n", true))) !!}</span><br><br>

                2-	Deuxième témoin <br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Noms et Prénoms : </strong>&nbsp;&nbsp;<span style=";"> {{ $dm->temoinFemmeEpoux->nom.' '.ucfirst($dm->temoinFemmeEpoux->prenom) }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Né le : </strong>&nbsp;&nbsp;<span style=";">{{ date("d", strtotime($dm->temoinFemmeEpoux->date_naissance)) ." ". Sifec::mois(date("m", strtotime($dm->temoinFemmeEpoux->date_naissance))) ." ".date("Y", strtotime($dm->temoinFemmeEpoux->date_naissance)) ." à ".$dm->temoinFemmeEpoux->lieu_naissance }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Profession :</strong> &nbsp;&nbsp;<span style=";">{{ $dm->temoinFemmeEpoux->profession->lib_profession }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Domicilié à :</strong> &nbsp;&nbsp;<span style="">{!! nl2br(e(wordwrap(optional($dm->temoinFemmeEpoux)->adresse ?? '', 55, "\n", true))) !!}</span><br><br>

                <br>
                <strong>B-Les témoins du futur épouse </strong><br>
                1-	Premier témoin <br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Noms et Prénoms :</strong> &nbsp;&nbsp;<span style=";">{{ $dm->temoinHommeEpouse->nom ." ". ucfirst($dm->temoinHommeEpouse->prenom) }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Né le : </strong>&nbsp;&nbsp;<span style=";">{{ date("d", strtotime($dm->temoinHommeEpouse->date_naissance)) ." ". Sifec::mois(date("m", strtotime($dm->temoinHommeEpouse->date_naissance))) ." ".date("Y", strtotime($dm->temoinHommeEpouse->date_naissance)) ." à ".$dm->temoinHommeEpouse->lieu_naissance }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Profession : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinHommeEpouse->profession->lib_profession }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Domicilié à :</strong> &nbsp;&nbsp;<span style="">{!! nl2br(e(wordwrap(optional($dm->temoinHommeEpouse)->adresse ?? '', 55, "\n", true))) !!}</span><br><br>

                2-	Deuxième témoin <br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Noms et Prénoms : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinFemmeEpouse->nom.' '.ucfirst($dm->temoinFemmeEpouse->prenom) }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Né le : </strong>&nbsp;&nbsp;<span style=";">{{ date("d", strtotime($dm->temoinFemmeEpouse->date_naissance)) ." ". Sifec::mois(date("m", strtotime($dm->temoinFemmeEpouse->date_naissance))) ." ".date("Y", strtotime($dm->temoinFemmeEpouse->date_naissance)) ." à ".$dm->temoinFemmeEpouse->lieu_naissance }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Profession : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinFemmeEpouse->profession->lib_profession }}</span><br>
                <strong>&nbsp;&nbsp;&nbsp;&nbsp;Domicilié à : </strong>&nbsp;&nbsp;<span style=";">{{ $dm->temoinHommeEpouse->adresse }}</span><br><br><br>

            </td>
            <td style="border: none; padding:5px 0px;text-align:">&nbsp;</td>
        </tr>
    </table>

    <table class="historique" cellspacing="0" style="width: 95%; font-size: 13px;margin-top:130px">
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
                <td style="border: none; padding:5px 0px;text-align:">&nbsp;</td>
                <td style="border: none; padding:5px 0px;text-align:">&nbsp;</td>
                <td style="text-align: right;">
                     Fait à <span style="text-transform:capitalize">{{ $localisation }}</span>, le {{ date("d-m-Y", strtotime($dm->date_declaration_mariage)) }}<br>
                    <p style="text-align:left;margin-left:120px">L’officier de l'état civil</p><br><br><br><br><br><br><br><br><br><br>
                </td>
              </tr>

              <tr>
                <td style="text-align: left;">Lu et approuvé
                    <br><strong>(<span style="color: red;">{{ $dm->epoux_approuver }}</span>)</strong>
                    <br>Le futur époux</td>
                <td style="border: none; padding:5px 0px;text-align:">&nbsp;</td>
                <td style="text-align: right;">Lu et approuvé <br><strong>(<span style="color: red;">{{ $dm->epouse_approuver }}</span>)</strong>
                    <br>La future épouse</td>
              </tr>
        </tbody>
    </table>

@if($dm->type_declaration == "DISPENSE")
<br><br><br><br><br><br><br><br><br>
<span style="text-align: left; font-style:italic; font-size:11px"><span style="color:red">(*)</span> Ce document requiert une dispense</span>
@endif
</div>



@if($dm->epoux_approuver == "OUI" && $dm->epouse_approuver == "OUI")
    @php
        $declarationVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.declaration.mariage', ['code' => $dm->code_declaration_mariage]);
    @endphp
    <div style="position:absolute; margin-left:40px;margin-top: 550px;">
        <qrcode value="{{ $declarationVerificationUrl }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
    </div>
@endif


</page>
