<style type="text/css">

    #sifec{
    margin-top: 0%;
    text-align:right;
    padding: 5px 2px;
    padding-bottom: 5px;
    padding-right: 8px;
    position: absolute;
    top: 0;
    right: 0;
    width: 45%;

    }
    #entete_rprt_suite{

   /*  text-align:right; */
    padding: 5px 2px;
    padding-bottom: 5px;
    padding-left: 8px;
    position: absolute;
    top: 0;
    left: 0;
    width: 45%;

    }

    legend {
    background-color:#FFFFFF;
    color: #000;
    padding: 2px 4px;
    border: none;
    font-size: 12px;
    }
    fieldset {
        font-size:100%;
        font-family: Arial;
        padding-left: 15px;
        float: left;
        margin: 2px 0;
    }
</style>


<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimg="{{ public_path('tpl/back-border.png') }}"  backimgw="100%"
	  backtop="5mm"
	  backbottom="8mm"
	  backleft="8mm"
	  backright="15mm">

	<bookmark title="Lettre" level="0" ></bookmark>

    <page_header >
        <div style="width: 100%; position: relative; height: 80px;">
        <div id="entete_rprt_suite">

            @php
                setlocale(LC_TIME, "fr_FR", "French");
                $afficherFormationSanitaire = false;
                $institutionAffichage = $dn->institution;
                $contexteEffectif = $contexteForcage ?? $dn->contexte_affichage ?? null;
                if (in_array($dn->type_declaration ?? '', ['CERTIFICAT DE NAISSANCE', 'DECLARATION DE NAISSANCE'])) {
                    if ($contexteEffectif) {
                        $afficherFormationSanitaire = ($contexteEffectif === 'formation_sanitaire');
                        if ($contexteEffectif === 'centre_etat_civil' && $dn->institutionDestinataire) {
                            $institutionAffichage = $dn->institutionDestinataire;
                        }
                    }
                }
                if (!$contexteEffectif) {
                    $codeCategorie = optional(optional($dn->institution)->typeInstitution)->typeCategorieInstitution;
                    $codeCategorie = $codeCategorie ? $codeCategorie->code_type_categorie_ins : null;
                    $afficherFormationSanitaire = ($codeCategorie == 'TCINS_0003');
                }
            @endphp
            @if($afficherFormationSanitaire)
                {{-- Formation sanitaire --}}
                <strong>{{ htmlentities("MINISTERE DE LA SANTE ET DE LA POPULATION  ") }}</strong><br>
                <strong style="margin-left:100px">************************</strong><br>
            @else
                {{-- Centre d'état civil (TCINS_0001) ou autre --}}
                @if($institutionAffichage)
                    @php
                        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institutionAffichage);
                        $localiteParent = $localisationData['localiteParent'] ?? '';
                        $localite = $localisationData['localite'] ?? '';
                    @endphp
                    <span>{{ $localiteParent }}<br>{{ $localite }}</span><br>
                @endif
            @endif
            <strong style="margin-left:10px">{{ optional($institutionAffichage)->lib_institution ?? optional($dn->institution)->lib_institution ?? '—' }}</strong>

        </div>

            <div id="sifec">
                <strong>REPUBLIQUE DU CONGO</strong><br/>
                <strong>Unité - Travail - Progr&egrave;s</strong><br/>

            </div>

            @if($dn->declarant_approuver == "OUI")
            <div style="margin-top: 55px; text-align: right;">
                @isset($qrCode)
                <div style="width: 25mm;">
                    <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                </div>
                @endisset
            </div>
            @endif
        </div>

    </page_header>

    <br><br><br>
	<table cellspacing="0" style="border-collapse: collapse;">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">

		<tr>
			<td style="border-right: none; padding:2px 0px;text-align: center"  >&nbsp;</td>
			<td style="border-bottom: solid; padding:2px 0px;text-align: center" colspan="2" >&nbsp;</td>
			<td style="border-right: none; padding:2px 0px;text-align: center" >&nbsp;</td>

		</tr>

		<tr>
			<td style="border-right: solid; padding:2px 0px;text-align: center">&nbsp;</td>
			<td style="border: solid; padding:2px 0px;text-align: center" colspan="2"><span style="font-size: 18px;font-weight:bold;">
                @php
                    $titreAffiche = $typeDeclaration;
                    if (in_array($dn->type_declaration ?? '', ['CERTIFICAT DE NAISSANCE', 'DECLARATION DE NAISSANCE'])) {
                        $ctx = $contexteForcage ?? $dn->contexte_affichage ?? null;
                        if ($ctx) {
                            $titreAffiche = ($ctx === 'formation_sanitaire') ? 'CERTIFICAT DE NAISSANCE' : 'DECLARATION DE NAISSANCE';
                        }
                    }
                @endphp
                {{ $titreAffiche }}
            </span>
            </td>
			<td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
		</tr>
        <tr>
			<td style="border-right: none; padding:2px 0px;text-align: center" colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td style="border: none; padding:2px 0px;text-align:center ">&nbsp;</td>
            <td style="border: none; padding:2px 0px;text-align:center " colspan="2">
            @if ($typeDeclaration == "JUGEMENT SUPPLETIF" || $typeDeclaration == "JUGEMENT D'HOMOLOGATION")
                <span style="font-size: 9px;font-weight:bold;">
                    @php
                        echo strtolower('issue du '.$typeDeclaration.' n° '.$dn->jugement->num_jugement.'  du '.(date("d-m-Y", strtotime($dn->jugement->date_jugement)))." au ").$dn->jugement->institutionUser->institution->lib_institution;
                    @endphp
                </span>
            @else

                <span style="font-size: 11px;font-weight:bold;"> N° {{ $dn->code_declaration_naissance }}</span>
                du <span style="font-size: 11px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_declaration)))  }}</span>
            @endif
        </td>
			<td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
		</tr>
        <tr>
			<td style="border-right: none; padding:2px 0px;text-align: center" colspan="4">&nbsp;</td>
		</tr>
    </table>
    <div style="width: 100%; margin-top: -3px;">
        <fieldset>
            <legend><strong>Renseignements enfant</strong></legend>


            <table cellspacing="0" style="border-collapse: collapse; ">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: left" colspan="3">

                        @php
                            $ctxSignature = $contexteForcage ?? $dn->contexte_affichage ?? null;
                            $afficherFormationSanitaireSignature = false;
                            $institutionSignature = $dn->institution;
                            if (in_array($dn->type_declaration ?? '', ['CERTIFICAT DE NAISSANCE', 'DECLARATION DE NAISSANCE'])) {
                                if ($ctxSignature) {
                                    $afficherFormationSanitaireSignature = ($ctxSignature === 'formation_sanitaire');
                                    if ($ctxSignature === 'centre_etat_civil' && $dn->institutionDestinataire) {
                                        $institutionSignature = $dn->institutionDestinataire;
                                    }
                                }
                            }
                            if (!$ctxSignature) {
                                $codeCat = optional(optional($dn->institution)->typeInstitution)->typeCategorieInstitution;
                                $codeCat = $codeCat ? $codeCat->code_type_categorie_ins : null;
                                $afficherFormationSanitaireSignature = ($codeCat == 'TCINS_0003');
                            }
                        @endphp
                        @if($afficherFormationSanitaireSignature)
                        Le chef de l’établissement sanitaire
                        {{ optional($institutionSignature)->lib_institution }}
                            @php
                                $infosque = "atteste par la présente que le ";
                            @endphp
                        @else
                        L'Officier du centre d'état civil principal de :
                        {{ optional($institutionSignature)->lib_institution }}
                            @php
                                $infosque = "Est informé que le";
                            @endphp
                        @endif

                    </td>
                    <td style="border: none; padding:2px 0px;text-align: center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">{{ $infosque ? $infosque : "Est informé que le" }} {{ utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) . " A " .date("H", strtotime($dn->date_heure_naissance))." heure(s) ".date("i", strtotime($dn->date_heure_naissance))." miniute(s)" }}</td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">a eu lieu la naissance  d'un enfant de sexe: <span style="font-size: 11px;font-weight:bold;">{{ $dn->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">

                        {{ $dn->enfant->sexe=="M" ? "Nommé" : "Nommée"  }} :
                        @if($dn->type_declarant == "Personne morale")
                            <span style="font-size: 11px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @else
                            {{ $dn->enfant->nom }} <span style="font-size: 11px;font-weight:bold;text-transform: capitalize;">{{ $dn->enfant->prenom }}</span>
                        @endif
                    </td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                @if($dn->type_declaration == "JUGEMENT D'HOMOLOGATION")
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">numero ancien acte de naissance :<span style="font-size: 11px;font-weight:bold;color:red"> {{  $dn->numero_ancien_acte }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
                @endif
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{utf8_encode(strftime("%d %B %Y", strtotime($dn->date_heure_naissance))) }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; Lieu de naissance :<span style="font-size: 11px;font-weight:bold;"> {{ $dn->enfant->lieu_naissance }}</span></td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 11px;font-weight:bold;"> {{ $dn->sitMatParent ? $dn->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 11px;font-weight:bold;">
                        {{ $dn->lieuSurvenance->lib_lieu_survenance ?? $dummy  }}
                    </span> </td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div style="width: 100%; margin-top: -5px;">
        <fieldset style="margin-top:2px">
            <legend><strong>Renseignements père</strong></legend>

            @if($dn->type_declaration == "FICHE DE MATERNITE" && $dn->pere->nom == "XXXXXXXXXXXXXXXX")  {{-- CAS DE FICHE DE MATERNITE ET LE PERE N'EST PAS RENSEIGNE --}}

            <table cellspacing="0" style="border-collapse: collapse; font-size: 9pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 11px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 11px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Profession : <span style="font-size: 11px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>

            @elseif($dn->personne_declaree == "Enfant trouvé" || $dn->personne_declaree == "Enfant abandonné")
            <table cellspacing="0" style="border-collapse: collapse; font-size: 9pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 11px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 11px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Profession : <span style="font-size: 11px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @else
            <table cellspacing="0" style="border-collapse: collapse; font-size: 9pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dn->pere ? $dn->pere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{ $dn->pere ? $dn->pere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{$dn->pere != "XXXXXXXXXXXXXXXX" ? utf8_encode(strftime("%d %B %Y", strtotime($dn->pere->date_naissance))) : $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 11px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->lieu_naissance : $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 11px;font-weight:bold;">
                        {{ $dn->pere ? $dn->pere->adresse : $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->nationalite->lib_nationalite : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Profession : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->pere ? $dn->pere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @endif
        </fieldset>

        <fieldset style="margin-top:2px">
            <legend><strong>Renseignements mère</strong></legend>
            @if($dn->personne_declaree == "Enfant trouvé")
            <table cellspacing="0" style="border-collapse: collapse; font-size: 9pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{  $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 11px;font-weight:bold;"> {{ $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 11px;font-weight:bold;">
                        {{  $dummy }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Profession : <span style="font-size: 11px;font-weight:bold;"> {{$dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 11px;font-weight:bold;"> {{ $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
            </table>
            @else

            <table cellspacing="0" style="border-collapse: collapse; font-size: 9pt">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dn->mere ? $dn->mere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{ $dn->mere ? $dn->mere->prenom : $dummy}} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{$dn->mere ? utf8_encode(strftime("%d %B %Y", strtotime($dn->mere->date_naissance))) : $dummy }} </span>
                    &nbsp; &nbsp;&nbsp;&nbsp;&nbsp; A :<span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->lieu_naissance : $dummy }}</span></td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->adresse : $dummy }} </span>
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->nationalite->lib_nationalite : $dummy }} </span>
                        &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->niveau_instruction : $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Profession : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->profession->lib_profession : $dummy }} </span>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->mere ? $dn->mere->telephone : $dummy }} </span></td>
                    <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                </tr>
                @if($dn->type_declarant == "Personne physique")
                <tr>
                    <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nombre d'enfant nés vivant y compris celui-ci : <span style="font-size: 11px;font-weight:bold;"> {{ (int)$dn->nombre_enfant }} </span>
                    </td>
                </tr>
                @endif
            </table>
            @endif
        </fieldset>

        @if($dn->type_declaration != "FICHE DE MATERNITE")
            <fieldset style="margin-top:2px">
                <legend><strong>Renseignements déclarant</strong></legend>
                <table cellspacing="0" style="border-collapse: collapse; ">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <col style="width: 25%">
                    <tr>
                        <td style="border: none; padding:2px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 11px;font-weight:bold;">{{ $dn->declarant->nom}} </span>&nbsp;&nbsp;&nbsp;
                            @if($dn->type_declarant == "Personne physique")
                            Prénom (s) :<span style="font-size: 11px;font-weight:bold;text-transform: capitalize"> {{ $dn->declarant->prenom }} </span>
                            @endif
                        </td>
                        <td style="border: none; padding:2px 0px;text-align: " >&nbsp;</td>
                    </tr>
                    @if($dn->type_declarant == "Personne physique")
                        <tr>
                            <td style="border: none; padding:2px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->declarant->sexe == "M" ? "Masculin" : "Féminin" }} </span>&nbsp;&nbsp;&nbsp;&nbsp; Date de naissance : <span style="font-size: 11px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y",strtotime($dn->declarant->date_naissance))) }} </span></td>
                            <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:2px 0px;text-align: " colspan="3">A : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->declarant->lieu_naissance }} </span>&nbsp;&nbsp;&nbsp;Domicile : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->declarant->adresse }} </span>  </td>
                            <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:2px 0px;text-align: " colspan="3">Téléphone :<span style="font-size: 11px;font-weight:bold;">{{ $dn->declarant->telephone }} &nbsp;&nbsp;&nbsp;</span>Nationalité : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->declarant->nationalite->lib_nationalite }} </span></td>
                            <td style="border: none; padding:2px 0px;text-align: ">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border: none; padding:2px 0px;text-align: " colspan="3">Filiation : <span style="font-size: 11px;font-weight:bold;"> {{ $dn->filiation ? $dn->filiation->lib_filiation : $dummy }}</span>
                                &nbsp;&nbsp;&nbsp; Profession : <span style="font-size: 11px;font-weight:bold;">{{ $dn->declarant->profession->lib_profession  }}</span> </td>
                        </tr>
                    @endif

                </table>
            </fieldset>
        @endif
    </div>

    {{-- QR code déplacé dans l'en-tête --}}

    @if($dn->type_declaration != "FICHE DE MATERNITE")
    <div style="bottom:0;margin-left:8px;margin-top:5px">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 12px;">
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
                    <td style="text-align: left;"> Lu et approuvé <br><strong>(<span style="color: red;">{{ $dn->declarant_approuver }}</span>)</strong>

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
