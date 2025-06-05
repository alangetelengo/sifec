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

    <page_header >
        <div id="entete_rprt_suite">

            <?php

            // CAS DE FORMATION SANITAIRE
            if($dn->institution->typeInstitution->code_type_institution != "TPINS_0002")

            {
                echo "<strong>".htmlentities("MINISTERE DE LA SANTE ET DE LA POPULATION  ")."</strong> <BR><strong style='margin-left:100px'>************************ </strong><BR>";
            ?>
            <?php
            }else {
                $localiteParent  = "DEPARTEMENT DE ". $dn->institution->lieu->localiteParent->localiteParent->lib_localite;
                $localite = " COMMUNE DE ".$dn->institution->lieu->localiteParent->lib_localite;
             echo  " <span>".
                   $localiteParent. "<br>".
                      $localite.
                   "</span> <br>";
            }

            ?>
            {{-- <strong style="margin-left:40px"> {{ $dn->institutionUserDeclaration->lib_institution }}</strong> --}}
            <strong style="margin-left:10px"> {{ $dn->institutionUserDeclaration->lib_institution }}</strong>
            <BR>

        </div>
        <div id="sifec"

        @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0003")
            style=""
            @else
             style="margin-top: -70pxmargin-bottom:10px;"
        @endif >
        <?php
            setlocale(LC_TIME, "fr_FR", "French");
            echo "<strong style='margin-right:100px;margin-top:-80px'>REPUBLIQUE DU CONGO</strong><br/>";
            echo "<strong style='font-size:11px;margin-left:-140px;font-weight:normal;margin-bottom:15px'>Unité - Travail - Progr&egrave;s"."</strong><br/>";
            // $dummy = "XXXXXXXXXXXXXXXX";

            $typeDeclaration = $dn->type_declaration;

            ?>
        </div>

    </page_header>


    <br><br>
	<table cellspacing="0" style="border-collapse: collapse;">
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
			<td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 21px;font-weight:bold;">
                {{-- @if ($typeDeclaration == "JUGEMENT SUPPLETIF" || $typeDeclaration == "JUGEMENT D'HOMOLOGATION") --}}
                @if($dn->jugement)
                DECLARATION DE NAISSANCE
               @else
                {{ $typeDeclaration }}
                @endif
            </span>
            </td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>
        <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td style="border: none; padding:5px 0px;text-align:center ">&nbsp;</td>
            <td style="border: none; padding:5px 0px;text-align:center " colspan="2">
            @if ($typeDeclaration == "JUGEMENT SUPPLETIF" || $typeDeclaration == "JUGEMENT D'HOMOLOGATION")
                <span style="font-size: 10px;font-weight:bold;">
                    @php
                        echo strtolower('issue du '.$typeDeclaration.' n° '.$dn->jugement->num_jugement.'  du '.(date("d-m-Y", strtotime($dn->jugement->date_jugement)))." au ").$dn->jugement->institutionUser->institution->lib_institution;
                    @endphp
                </span>
            @else

                <span style="font-size: 13px;font-weight:bold;"> N° {{ $dn->code_declaration_naissance }}</span>
                du <span style="font-size: 13px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_declaration)))  }}</span>
            @endif
        </td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>
        <tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>
		</tr>
    </table>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements enfant</strong></legend>

            @if($dn->type_declaration == "FICHE DE MATERNITE") {{-- CAS DE FICHE DE MATERNITE ET LE PERE N'EST PAS RENSEIGNE --}}
            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: left" colspan="3">
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Le {{ utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) . " A " .date("H", strtotime($dn->date_heure_naissance))." heure(s) ".date("i", strtotime($dn->date_heure_naissance))." miniute(s)" }}</td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Naissance d'un enfant de sexe: <span style="font-size: 13px;font-weight:bold;">{{ $dn->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">

                        {{ $dn->enfant->sexe=="M" ? "Nommé" : "Nommée"  }} :
                        @if($dn->type_declarant == "Personne morale")
                            <span style="font-size: 13px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @else
                            {{ $dn->enfant->nom }} <span style="font-size: 13px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @endif
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                @if($dn->type_declaration == "JUGEMENT D'HOMOLOGATION")
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">numero ancien acte de naissance :<span style="font-size: 13px;font-weight:bold;color:red"> {{  $dn->numero_ancien_acte }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                @endif
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->enfant->lieu_naissance }}</span></td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->sitMatParent ? $dn->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;">
                        {{ $dn->lieuSurvenance->lib_lieu_survenance ?? $dummy  }}
                    </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
            </table>
            @endif
            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil principal de :
                        @if(Auth::user()->affectationActive()->institution->TypeInstitution->code_type_institution == "TPINS_0002")
                        {{ $dn->institutionUser->institution->lib_institution }}
                        @elseif(Auth::user()->affectationActive()->institution->TypeInstitution->code_type_institution == "TPINS_0005")
                        {{ $dn->institutionUser->institution->lib_institution }}

                        @else
                        {{ $dn->institutionUser->institution->institutionParent ? $dn->institutionUser->institution->institutionParent->lib_institution : $dn->institutionUser->institution->lib_institution }}

                        @endif
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé que: {{ utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) . " A " .date("H", strtotime($dn->date_heure_naissance))." heure(s) ".date("i", strtotime($dn->date_heure_naissance))." miniute(s)" }}</td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Naissance d'un enfant de sexe: <span style="font-size: 13px;font-weight:bold;">{{ $dn->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">

                        {{ $dn->enfant->sexe=="M" ? "Nommé" : "Nommée"  }} :
                        @if($dn->type_declarant == "Personne morale")
                            <span style="font-size: 13px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @else
                            {{ $dn->enfant->nom }} <span style="font-size: 13px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @endif
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                @if($dn->type_declaration == "JUGEMENT D'HOMOLOGATION")
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">numero ancien acte de naissance :<span style="font-size: 13px;font-weight:bold;color:red"> {{  $dn->numero_ancien_acte }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                @endif
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->enfant->lieu_naissance }}</span></td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->sitMatParent ? $dn->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;">
                        {{-- @if(Auth::user()->affectationActive()->institution->TypeInstitution->code_type_institution == "TPINS_0002")
                        {{ $dn->formation_sanitaire_naissance }}
                        @else
                        {{ $dn->lieuSurvenance ? $dn->lieuSurvenance->lib_lieu_survenance : $dummy }}
                        @endif --}}
                        {{ $dn->lieuSurvenance->lib_lieu_survenance ?? $dummy  }}
                    </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div style="width: 100%">
        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements père</strong></legend>

            @if($dn->type_declaration == "FICHE DE MATERNITE" && $dn->pere->nom == "XXXXXXXXXXXXXXXX")  {{-- CAS DE FICHE DE MATERNITE ET LE PERE N'EST PAS RENSEIGNE --}}

            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 13px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 13px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>

            @elseif($dn->personne_declaree == "Enfant trouvé" || $dn->personne_declaree == "Enfant abandonné")
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 13px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 13px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @else
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dn->pere ? $dn->pere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{ $dn->pere ? $dn->pere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$dn->pere != "XXXXXXXXXXXXXXXX" ? utf8_encode(strftime("%d %B %Y", strtotime($dn->pere->date_naissance))) : $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->lieu_naissance : $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 13px;font-weight:bold;">
                        {{ $dn->pere ? $dn->pere->adresse : $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->nationalite->lib_nationalite : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @endif
        </fieldset>

        <fieldset style="margin-top:5px">
            <legend><strong>Renseignements mère</strong></legend>
            @if($dn->personne_declaree == "Enfant trouvé")
            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 13px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 13px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @else

            <table cellspacing="0" style="border-collapse: collapse; font-size: 10pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dn->mere ? $dn->mere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{ $dn->mere ? $dn->mere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$dn->mere ? utf8_encode(strftime("%d %B %Y", strtotime($dn->mere->date_naissance))) : $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->lieu_naissance : $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->adresse : $dummy }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->nationalite->lib_nationalite : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                @if($dn->type_declarant == "Personne physique")
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nombre d'enfant nés vivant y compris celui-ci : <span style="font-size: 13px;font-weight:bold;"> {{ (int)$dn->nombre_enfant }} </span>
                    </td>
                </tr>
                @endif
            </table>
            @endif
        </fieldset>

        @if($dn->type_declaration != "FICHE DE MATERNITE")
            <fieldset style="margin-top:5px">
                <legend><strong>Renseignements déclarant</strong></legend>
                <table cellspacing="0" style="border-collapse: collapse; ">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <tr>
                        <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $dn->declarant->nom}} </span>&nbsp;&nbsp;&nbsp;
                            @if($dn->type_declarant == "Personne physique")
                            Prénom (s) :<span style="font-size: 13px;font-weight:bold;text-transform: capitalize"> {{ $dn->declarant->prenom }} </span>
                            @endif
                        </td>
                        <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                    </tr>
                    @if($dn->type_declarant == "Personne physique")
                        <tr>
                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->declarant->sexe == "M" ? "Masculin" : "Féminin" }} </span>&nbsp;&nbsp;&nbsp;&nbsp; Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y",strtotime($dn->declarant->date_naissance))) }} </span></td>
                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">A : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->declarant->lieu_naissance }} </span>&nbsp;&nbsp;&nbsp;Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->declarant->adresse }} </span>  </td>
                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Téléphone :<span style="font-size: 13px;font-weight:bold;">{{ $dn->declarant->telephone }} &nbsp;&nbsp;&nbsp;</span>Nationalité : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->declarant->nationalite->lib_nationalite }} </span></td>
                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Filiation : <span style="font-size: 13px;font-weight:bold;"> {{ $dn->filiation ? $dn->filiation->lib_filiation : $dummy }}</span>
                                &nbsp;&nbsp;&nbsp; Profession : <span style="font-size: 13px;font-weight:bold;">{{ $dn->declarant->profession->lib_profession  }}</span> </td>
                        </tr>
                    @endif

                </table>
            </fieldset>
        @endif
    </div>

    {{-- <div style="position:absolute; margin-left:570px;top:30px;">
        <qrcode value="{{env('QRCODE_URL')}}/qrcode/naissance/certificat?niupp={{ $dn->code_declaration_naissance }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
    </div> --}}


    {{--  --}}
    <div style="position:absolute; margin-left:570px;top:5px">
        <qrcode value="{{env('QRCODE_URL')}}/qrcode/naissance/certificat?niupp={{ $dn->code_declaration_naissance }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
    </div>

    @if($dn->type_declaration != "FICHE DE MATERNITE")
    <div style="bottom:0;margin-left:10px;margin-top:10px">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 15px;">
            <col style="width: 50%">
            <col style="width: 50%">
            <thead>
              <tr style="text-align: center">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left;"> Lu et approuvé <br><strong>(<span style="color: red;">{{ $dn->approuver }}</span>)</strong>

                        <br> Le déclarant
                     </td>
                    <td style="text-align: center;">
                        Fait à Brazzaville, le {{utf8_encode(strftime("%d %B %Y", strtotime( $dn->created_at)))}}<br> Chef de service
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>

    @endif

</page>
