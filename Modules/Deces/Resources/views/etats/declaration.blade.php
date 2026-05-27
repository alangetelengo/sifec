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



<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimg="{{ public_path('tpl/back-border.png') }}" backimgw="100%"

	  backtop="10mm"
	  backbottom="15mm"
	  backleft="10mm"
	  backright="20mm">

	<bookmark title="Lettre" level="0" ></bookmark>
    @php
        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = $ddc->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);

        $commune = $localisationData['localite'];
        $dept = $localisationData['localiteParent'];
        $localisation = $localisationData['localisation'];

    @endphp

	<page_header>


        <div id="entete_rprt_suite">
            @php
                setlocale(LC_TIME, 'fr_FR', 'French');
                $afficherFormationSanitaire = false;
                $institutionAffichage = $ddc->institution;
                $contexteEffectif = $contexteForcage ?? $ddc->contexte_affichage ?? null;
                if (in_array($ddc->type_declaration ?? '', ['DECLARATION DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'], true)
                    || in_array($ddc->type_declaration_origine ?? '', ['CERTIFICAT DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'], true)) {
                    if ($contexteEffectif) {
                        $afficherFormationSanitaire = ($contexteEffectif === 'formation_sanitaire');
                        if ($contexteEffectif === 'pompe_funebre' && $ddc->institutionDestinataire) {
                            $institutionAffichage = $ddc->institutionDestinataire;
                        }
                    }
                }
                if (! $contexteEffectif) {
                    $codeCategorie = optional(optional($ddc->institution)->typeInstitution)->typeCategorieInstitution->code_type_categorie_ins ?? null;
                    $afficherFormationSanitaire = ($codeCategorie === 'TCINS_0003');
                }
            @endphp
            @if($ddc->type_declaration == "DECLARATION TARDIVE")
                <strong>{{$dept}}</strong> <BR>
                <strong>{{$commune}}</strong>
            @elseif($afficherFormationSanitaire)
                <strong>{{ htmlentities('MINISTERE DE LA SANTE ET DE LA POPULATION  ') }}</strong><br>
                <strong>************************************************************** </strong>
            @else
                @if($institutionAffichage)
                    @php
                        $localisationDataPf = \App\Sifec\Sifec::getLocalisationInstitution($institutionAffichage);
                    @endphp
                    <span>{{ $localisationDataPf['localiteParent'] ?? '' }}<br>{{ $localisationDataPf['localite'] ?? '' }}</span><br>
                @endif
            @endif

             <BR>
            <strong>{{ optional(optional($ddc->institutionUser)->institution)->lib_institution ?? optional($institutionAffichage)->lib_institution ?? '—' }}</strong>

        </div>

        <div id="sifec">
            <?php
             setlocale(LC_TIME, "fr_FR", "French");
             echo "<strong style='margin-right:100px;margin-top:2px'>REPUBLIQUE DU CONGO</strong><br/>";
             echo "<strong style='font-size:11px;margin-left:-140px;font-weight:normal;margin-bottom:15px'>Unité - Travail - Progr&egrave;s"."</strong><br/>";
            ?>
        </div>

    </page_header>

	<page_footer>
        <div id="pied_de_page">
            Plate-forme système des faits d'état civil
        </div>
    </page_footer>


<br><br><br>
	<table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">
		<col style="width: 25%">


		<tr>
			<td style="border-right: solid; padding:5px 0px;text-align: center">&nbsp;</td>
			<td style="border: solid; padding:5px 0px;text-align: center" colspan="2"><span style="font-size: 16px;font-weight:bold;">
                @php
                    $titreAffiche = $typeDeclaration ?? $ddc->type_declaration;
                    if (in_array($ddc->type_declaration ?? '', ['DECLARATION DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'], true)
                        || in_array($ddc->type_declaration_origine ?? '', ['CERTIFICAT DE DECES', 'CERTIFICAT DE CONSTATATION DE DECES'], true)) {
                        $ctx = $contexteForcage ?? $ddc->contexte_affichage ?? null;
                        if ($ctx === 'formation_sanitaire') {
                            $titreAffiche = 'CERTIFICAT DE DECES';
                        } elseif ($ctx === 'centre_hygiene') {
                            $titreAffiche = 'CERTIFICAT DE CONSTATATION DE DECES';
                        } elseif ($ctx === 'pompe_funebre') {
                            $titreAffiche = 'DECLARATION DE DECES';
                        }
                    }
                @endphp
                {{ $titreAffiche }}
            </span></td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>


		<tr>
			<td style="border: none; padding:5px 0px;text-align:center ">&nbsp;</td>
			<td style="border: none; padding:5px 0px;text-align:center " colspan="2">Année:<span style="font-size: 15px;font-weight:bold;"> {{ utf8_encode(strftime("%Y", strtotime($ddc->date_heure_deces))) }}</span> N&deg;:<span style="font-size: 15px;font-weight:bold;">{{ $ddc->code_declaration_deces }}</span> </td>
			<td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
		</tr>

		<tr>
			<td style="border-right: none; padding:5px 0px;text-align: center" colspan="4">&nbsp;</td>

		</tr>
    </table>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements du défunt</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">

                <tr>
                    {{-- <td style="border: none; padding:5px 0px;text-align: left" colspan="3">
                        @if($ddc->institutionUser->institution->institutionParent->code_institution == "INS_0193")
                        <strong>
                            {{ $ddc->institutionUser->institution->institutionParent->lib_institution }}
                        </strong>
                        @elseif($ddc->institutionUser->institution->typeInstitution->code_type_institution == "TPINS_0002" && $diffJour < 15)
                            BUREAU D'ENREGISTREMENT DE DECES :
                        @else
                            L'officier du centre d'état civil de :
                        <strong>
                            {{ $ddc->institutionUser->institution->institutionParent->lib_institution }}
                        </strong>
                        @endif
                    </td> --}}
                    <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil secondaire des :
                        @if($ddc->institution && optional(optional($ddc->institution->TypeInstitution)->typeCategorieInstitution)->code_type_categorie_ins == "TCINS_0003")
                        {{ optional($ddc->institutionPompeFunebre)->lib_institution ?? '—' }}

                        @else
                        {{ optional($ddc->institution)->lib_institution ?? '—' }} 

                         @endif

                    </td>
                    <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="4">Est informé que le : <span style="font-size: 15px;font-weight:bold;">{{ utf8_encode(strftime("%d %B %Y", strtotime($ddc->date_heure_deces))) }}  à {{ utf8_encode(strftime("%H", strtotime($ddc->date_heure_deces)))  }} heure(s) {{ date("i", strtotime($ddc->date_heure_deces))  }} minute(s) </span> </td>

                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est décédée, une personne de sexe : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->defunt->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom(s) : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->defunt->nom}} </span>Prénom(s) :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->prenom}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalité :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->nationalite->lib_nationalite}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->profession->lib_profession }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->adresse ?? "NON DÉCLARÉ" }} </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Religion :<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->religion->lib_religion}}</span> </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">N&deg; d'acte de naissance : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->num_acte_naissance }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 15px;font-weight:bold;"> {{ utf8_encode(strftime("%d %B %Y", strtotime($ddc->defunt->date_naissance))) }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->defunt->lieu_naissance }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de naissance : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->cec_naissance }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->situationMat->lib_situation_matrimoniale }}</span> </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>

                @if($ddc->conjoint != null)
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Option de mariage : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->regime != NULL ? $ddc->regime->lib_regime :""}}</span> </td>
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
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de déc&egrave;s :<span style="font-size: 15px;font-weight:bold;">
                        {{-- @if($ddc->type_declaration == "CERTIFICAT DE CONSTATATION DE DECES")
                            {{ $ddc->lieuDeces->lib_localite }} ( {{ $ddc->lieuDeces->localiteParent->lib_localite }} )
                            @else --}}
                            {{ $ddc->lieu_deces }}
                        {{-- @endif --}}
                         </span>
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: "  colspan="3">Lieu de survenance : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->lieusurvenance->lib_lieu_survenance }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: "  colspan="3">Cause(s) du décès : <span style="font-size: 15px;font-weight:bold;">
                        @php
                        $causesd = $ddc->DDecesCauses;
                        $v = "";
                    @endphp
                    <strong>
                        {{-- <div> --}}
                            @if ($causesd != NULL)
                                @foreach ($causesd as $item)
                                    {{$v.$item->causeDeces->lib_cause_deces}}
                                    @php
                                        $v = ", ";
                                    @endphp
                                @endforeach
                            @endif
                        {{-- </div>                      --}}
                    </strong> </span>
                    </td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td colspan="2" style="height: 15px;">Fils de: <strong>{{ $ddc->pere->nom }} </strong><strong style="text-transform: capitalize">{{ $ddc->pere->prenom }}</strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td colspan="2"  style="height: 15px;">Et de: <strong>{{ $ddc->mere->nom }} </strong><strong style="text-transform: capitalize">{{ $ddc->mere->prenom }}</strong>
                    </td>
                </tr>
                <!--<tr>
                    <td style="border: none; padding:5px 0px;text-align: "  colspan="3">Fils de : <span style="font-size: 15px;font-weight:bold;"> SITOU Jean</span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: "  colspan="3">Et de : <span style="font-size: 15px;font-weight:bold;"> OKO Irène </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>-->
            </table>
        </fieldset>
    </div>
    <div style="width: 100%;">
        <fieldset>
            <legend><strong>Renseignements du déclarant</strong></legend>
            <table cellspacing="0" style="border-collapse: collapse; font-size: 12pt;" >
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <col style="width: 25%">
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom(s) et Prénom(s) déclarant : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->declarant->nom."  ".$ddc->declarant->prenom}} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="4">Date et lieu de naissance déclarant  : <span style="font-size: 15px;font-weight:bold;"> {{ strftime("%d %B %Y", strtotime($ddc->declarant->date_naissance)) }} </span> à<span style="font-size: 15px;font-weight:bold;"> {{ $ddc->declarant->lieu_naissance }}</span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Filiation déclarant: <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->filiation->lib_filiation }} </span></td>
                    <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Domicile déclarant : <span style="font-size: 15px;font-weight:bold;">{{ $ddc->declarant->adresse }} </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
                <tr>
                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession déclarant : <span style="font-size: 15px;font-weight:bold;"> {{ $ddc->declarant->profession->lib_profession}} </span> </td>
                    <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                </tr>
            </table>
        </fieldset>
        @if($ddc->mouvements->last()->statut == "Envoyée")
            <div style="position:absolute; margin-left:570px;top:60px;">
                <qrcode value="{{env('QRCODE_URL')}}/qrcode/deces/certificat?niupp={{ $ddc->code_declaration_deces }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
            </div>
        @endif

        {{-- <div style="position:absolute; margin-left:570px;">
            <qrcode value="{{env('QRCODE_URL')}}/qrcode/naissance/certificat?niupp={{ $dn->code_declaration_naissance }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
        </div> --}}

        <div style="bottom:0;margin-left:10px;margin-top:10px">
            <table class="historique" cellspacing="0" style="width: 95%; font-size: 15px;margin-top:20px">
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
                        <td style="text-align: left;"> Lu et approuvé <br><strong>(<span style="color: red;">{{ $ddc->declarant_approuver }}</span>)</strong>
                            <br> Le déclarant
                         </td>

                        <td>
                           <span> Fait à {{ ucfirst(strtolower($localisation)) }}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $ddc->created_at)))}}<br></span>
                            <span style='text-align:left; margin-top:10px'>@if(optional(optional(optional($ddc->institutionUser)->institution)->institutionParent)->code_institution == "INS_0193")
                                        Chef de bureau
        s
                                    @elseif(optional(optional(optional($ddc->institutionUser)->institution)->typeInstitution)->code_type_institution == "TPINS_0002" && isset($diffJour) && $diffJour < 15)
                                        Chef de bureau:
                                    @else
                                        Chef de service
                                    @endif
                            </span>

                         </td>
                      </tr>
                </tbody>
            </table>
        </div>
    </div>


</page>
