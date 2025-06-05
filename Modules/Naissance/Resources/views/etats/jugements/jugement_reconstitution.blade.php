<style>
    td{
        font-size: 80%;
        height: 16px;
    }
    b{
        font-size: 80%;
    }
    tr{
        width:100%; text-align: left; padding-bottom: 2px;
    }
    .para{
        margin-right: 15%;text-align: justify;font-size: 80%; margin-bottom: -2px;
    }
</style>
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        $tribunal = $jugement->institutionUser->institution->institutionParent->lib_institution;
        $ptrib = $president->user->personne->prenom." ".$president->user->personne->nom;
        $procutrib = $procureur->user->personne->prenom." ".$procureur->user->personne->nom;
        $gref = $greffier->user->personne->prenom." ".$greffier->user->personne->nom;
        // $codetribunal = $jugement->institutionUser->institution->institutionParent->code_institution;
        $num = "";
        if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
            $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
        } else {
            $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }

        setlocale(LC_TIME, "fr_FR", "French");
        // $mois = strftime("%B", strtotime(date('Y-m-d')));
        // $total = 0;
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width:40%; text-align: center; font-size: 200!important%">
                @php
                    $courAppel = $jugement->institutionUser->institution->institutionParent->institutionParent->lib_institution;
                @endphp
                <br><br>
                <p>
                    <span>{{$courAppel}}</span><br>
                    <span>{{$tribunal}}</span><br>
                    <span>PARQUET</span><br>
                    <span>N° {{ $jugement->numero_jug}}/{{date("Y", strtotime($jugement->created_at))}}</span>
                </p>
            </td>
            <td style="width:35%; text-align: center;">
            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br>

    {{-- <table align="center" style="border-radius: 1mm; border: none;">
        <tr>
            <td style="width:100%; text-align: center; margin:10%;">
                <p><strong style="font-size: 130%;">AUDIENCE CIVILE, COMMERCIALE ADMINISTRATIVE ET FINANCIERE DU DIX AOUT DEUX MIL VINGT DEUX A ONZE HEURE TREINZE MINUTES
                </strong><br>
                </p>
            </td>
            <td style="width:15%; text-align: center;">
            </td>
        </tr>
    </table> --}}
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; right:11px; left: 15px; top: 160px; width: 800px; height: 860px; padding: 0px; overflow: hidden; font-weight: normal; font-size:20px;">
            <p class="para" style="text-align: center"><strong>AUDIENCE CIVILE, COMMERCIALE ADMINISTRATIVE ET FINANCIERE <br> DU {{ str_replace("é","É",strtoupper(utf8_encode(strftime("%d %B %Y", strtotime(date($jugement->date_heure_declaration))))))}}
            </strong><br>
            </p>
            <p class="para" style="text-align: center">AFFAIRE
            </p>
            <p class="para" style="text-align: center">{{ $jugement->enfant->sexe=="M" ? "Monsieur" : "Madame"  }} {{$jugement->enfant->nom}} {{$jugement->enfant->prenom}};
            </p>
            <p class="para" style="text-align: center"><strong>REQUETE AUX FINS DE RECONSTITUTION D'ACTE DE NAISSANCE</strong>
            </p>
            <p class="para">
                A l'audience publique du {{ $tribunal }}, siégiant en matière civile, tenue le {{utf8_encode(strftime("%d %B %Y", strtotime(date($jugement->date_heure_declaration))))}}
            </p>
            <p class="para">Par Monsieur ;
            </p>
            <p class="para">- {{ $ptrib }}, président du tribunal:
            </p>
            <p class="para">- {{ $gref }}, greffier en Chef
            </p>
            <p class="para">{{ $procutrib }}, Procureur de la République, tenant le siège du ministère public;
            </p>
            <p class="para" style="text-align: center">A ETE RENDU LE JUGEMENT GRACIEUX SUIVANT
            </p>
            <p class="para">POUR:
            </p>
            <p class="para">{{ $jugement->enfant->sexe=="M" ? "Monsieur" : "Madame"  }} {{$jugement->enfant->nom}} {{$jugement->enfant->prenom}}, {{ $jugement->enfant->nationalite ? $jugement->enfant->nationalite->lib_nationalite : "" }}, {{ $jugement->enfant->profession ? $jugement->enfant->profession->lib_profession :""}}, domicilié au {{ Sifec::adressepersonne($jugement->enfant->code_personne) }}
            </p>
            <p class="para" style="text-align: center">DEMANDEUR
            </p>
            <p class="para">Sans que les présentes qualités puissent nuire ou préjudicier aux droits et intérêts respectifs du requérant, mais au contraire sous les plus expresses réserves de fait et de droit. <br><br>
                Le {{strftime("%d %B %Y", strtotime(date($jugement->created_at)))}}, {{ $jugement->enfant->sexe=="M" ? "Monsieur" : "Madame"  }} {{$jugement->enfant->nom}} {{$jugement->enfant->prenom}} saisissait le {{ $tribunal }} d'une requête aux fins d'obtenir un jugement de reconstitution d'acte de naissance;<br><br>
                En suite de cette requête, monsieur le Président du Tribunal rendait une ordonnance fixant au {{strftime("%d %B %Y", strtotime(date($jugement->created_at)))}}, la date à laquelle l'affaire serait appelée à l'audience publique;<br><br>
            </p>
            <p class="para">EN CONSEQUENCE <br><br>
                Ordonne à l'officier d'état civil du/de la {{ $jugement->institutionUser->institution->lib_institution }} de transcrire le dispositif du présent jugement dans les registres de l'état civil du/de la {{ $jugement->institutionUser->institution->lib_institution }} pour le requérant. <br><br>
            </p>

            <table align="left" style="margin-left: 0%;border-radius: 1mm; border: none;">
                <tr>
                    <td>Nom: <strong>{{$jugement->enfant->nom}}</strong></td>
                </tr>
                <tr>
                    <td>Prénom: <strong>{{$jugement->enfant->prenom}}</strong></td>
                </tr>
                <tr>
                    <td>{{ $jugement->enfant->sexe=="M" ? "né" : "née"  }} le: <strong>{{strftime("%d %B %Y", strtotime(date($jugement->enfant->date_naissance)))}}</strong> à <strong>{{$jugement->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>Sexe: <strong>{{ $jugement->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>

                <tr>
                    <td>{{ $jugement->enfant->sexe=="M" ? "Fils" : "Fille"  }} de: <strong>{{$jugement->pere->nom}} {{$jugement->pere->prenom}} </strong></td>
                </tr>
                {{-- <tr>
                    <td>Né le : <strong>{{strftime("%d %B %Y", strtotime(date($jugement->pere->date_naissance)))}}</strong> à <strong>{{$jugement->pere->lieu_naissance}}</strong></td>
                </tr> --}}
                <tr>
                    <td>Profession: <strong>{{ $jugement->pere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $jugement->pere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr>
                    <td>Domicilié: <strong>{{ Sifec::adressepersonne($jugement->pere->code_personne) }} </strong></td>
                </tr>
                <tr>
                    <td>Et de: <strong>{{$jugement->mere->nom}} {{$jugement->mere->prenom}}</strong></td>
                </tr>
                {{-- <tr>
                    <td>Née le : <strong>{{strftime("%d %B %Y", strtotime(date($jugement->mere->date_naissance)))}}</strong> à <strong>{{$jugement->mere->lieu_naissance}}</strong></td>
                </tr> --}}
                <tr>
                    <td>Profession: <strong>{{ $jugement->mere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $jugement->mere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr>
                    <td>Domiciliée: <strong>{{ Sifec::adressepersonne($jugement->mere->code_personne) }} </strong></td>
                </tr>
            </table>
            <p style="text-align: justify;margin-right: 15%;font-size: 80%;">Dit qu'il sera porté en marge la mention  <span style="color: red;">&lsaquo;&lsaquo; ACTE RECONSTITUE&rsaquo;&rsaquo;</span>. Met les dépens à la charge de la requérante.
                {{-- la présente décision a été signée par {{ $ptrib }}, président du tribunal et NOM DU GREFFIER, Greffier en chef présent lors du prononcé --}}
            </p>
        </div>
    </div>
    <div style="margin-top: 120px; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;">
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
                        {{-- <div style="margin-bottom:0;"><qrcode value="{{env('QRCODE_URL')}}/qrcode/naissance/requisition?niupp={{ $requisition->code_declaration_naissance }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div> --}}
                    </td>
                    <td style="text-align: left;">
                        <p>Fait à Brazzaville, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($jugement->date_heure_declaration))))}}<br>Président du {{ $tribunal }},</p>

                    </td>
                  </tr>
            </tbody>
        </table>
    </div>

</page>
