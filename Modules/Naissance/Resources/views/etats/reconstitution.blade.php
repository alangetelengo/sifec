<style>
    td{
        font-size: 12px;
        height: 16px;
    }
    b{
        font-size: 12px;
    }
    tr{
        width:100%; text-align: left; padding-bottom: 2px;
    }
    .para{
        margin-right: 15%;text-align: justify; font-size: 12px; margin-bottom: -2px;
    }
</style>
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        $tribunal = $requisition->institutionUser->institution->institutionParent->lib_institution;
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

    <table cellspacing="0" style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width:40%; text-align: center; font-size: 12px!important%">
                @php
                    $courAppel = $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution;
                @endphp
                <br><br>
                <p>
                    <span>COURS D'APPEL DE {{ $requisition->institutionUser->institution->lieu->localiteParent->lib_localite }}</span><br>
                    <span>{{$tribunal}}</span><br>
                    <span>PARQUET</span><br>
                    <span>N° {{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}}</span>
                </p>
            </td>
            <td style="width:35%; text-align: center;">
            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>

    {{-- <table align="center" style="border-radius: 1mm; border: none;">
        <tr>
            <td style="width:100%; text-align: center; border:solid;margin:10%;">
                <p><strong style="font-size: 18px;margin-right: 0%;">REQUISITION AUX FINS DE RECONSTITUTION D’UN ACTE DE NAISSANCE </strong><br> Procureur de la République près le {{$tribunal}}</p>
            </td>
            <td style="width:15%; text-align: center;">
            </td>
        </tr>
    </table><br><br> --}}

    <table align="center" style="border-radius: 1mm; border: none;">
        <tr>
            <td style="width:100%; text-align: center;border: solid;">
                <p><strong style="font-size: 18px;margin-right: 0%;">REQUISITION AUX FINS DE RECONSTITUTION D'UN ACTE DE NAISSANCE </strong><br> Procureur de la République près le {{$tribunal}}</p>
            </td>
            <td style="width:15%; text-align: center;">
            </td>
        </tr>
    </table>
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        {{-- <div style="position: absolute; right:11px; left: 15px; top: 230px; width: 800px; height: 800px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:13px;"> --}}
        <div style="position: absolute; right:11px; left: 15px; top: 200px; width: 800px; height: 800px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:13px;">

            <p class="para">Vu la demande de la réquisition aux fins de reconstitution d’un acte d’état civil, introduite par {{$requisition->declarant->nom}} {{$requisition->declarant->prenom}}, {{ $requisition->declarant->nationalite->lib_nationalite }}, {{ $requisition->enfant->sexe=="M" ? "domicilié" : "domiciliée"  }} {{ $requisition->declarant->adresse }}, agissant en qualité de {{$requisition->filiation->lib_filiation}} ;
            </p>
            <p class="para">Vu le dossier, notamment le Certificat de destruction n° <strong>{{ $requisition->numero_certificat }}</strong> du {{strftime("%d %B %Y", strtotime(date($requisition->created_at)))}} et la copie de la pièce d'identité n° {{$requisition->declarant->document->numero_document}};
            </p>
            <p class="para">Attendu que le requérant soulève la perte – lors des récents évènements sociopolitiques qui ont endeuillé le pays – de la copie originale de l’acte de naissance, {{ $requisition->enfant->sexe=="M" ? "le nommé" : "la nommée"  }} « {{ $requisition->enfant->nom}} {{ $requisition->enfant->prenom}} de sexe {{ $requisition->enfant->sexe=="M" ? "masculin" : "féminin"  }}, {{ $requisition->enfant->sexe=="M" ? "né" : "née"  }} le {{strftime("%d %B %Y", strtotime(date($requisition->enfant->date_naissance)))}} à {{$requisition->enfant->lieu_naissance}} », laquelle copie original lui été délivrée à sa naissance ;
            </p>
            <p class="para">Qu’étant à {{$requisition->enfant->lieu_naissance}} pour la délivrance d’une copie dudit acte perdu, le réquerant  n’a pu se voir délivré un DUPLICATA de l’acte de naissance par l’Officier d’état civil, du fait de la destruction des souches des actes d’état civil, pendant les mêmes événements sociopolitiques qui ont eu lieu dans le pays ;
            </p>
            <p class="para">Qu’il allège que le défaut d’un document aussi indispensable que l’acte de naissance, ne permet pas à son neveu d’accomplir certaines formalités nécessaires courantes ;
            </p>
            <p class="para">Qu’en l’occurrence, il sied de faire constater que {{$requisition->enfant->nom}} {{$requisition->enfant->prenom}}  est dépourvu de tout document d’état civil ;
            </p>
            <p class="para">Attendu qu’en vertu de l’acte de la loi 82 n° du 17  octobre portant code de la famille congolaise cas de destruction d’un acte de registre d’Etat civil, le ou la les cartes détruis seront reconstitue à la diligence du Procureur de la République
            </p>
            <p class="para">Qu’en l’espère, cette destruction devra être réparée  par l’Officier d’état civil du/de: {{ $requisition->institutionUser->institution->lib_institution }} ou à tout autre Officier compétent :
            </p>
            <p style="text-align: center;margin-right: 15%;font-size: 12px;"><strong style="font-size: 12px;">EN CONSEQUENCE</strong><br>
                Vu les dispositions de l’article 82 de la loi d°073/84 du 17 octobre 1984 portant Code de la famille Congolaise <br>
                Vu l’entier : <br>
                Requiert qu’il plaise à l’OFFICIER d’état civil du/de: {{ $requisition->institutionUser->institution->lib_institution }} ou à tout autre Officier d’état  civil procéder à la reconstitution de l’acte de naissance <br>

            </p>
            <table align="left" style="margin-left: 0%;border-radius: 1mm; border: none;">
                <tr>
                    <td>l'enfant : </td>
                </tr>
                <tr>
                    <td>Nom: <strong>{{$requisition->enfant->nom}}</strong>, prénom: <strong>{{$requisition->enfant->prenom}}</strong></td>
                </tr>
                <tr>
                    <td>Sexe: <strong>{{ $requisition->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr>
                    <td>Date de naissance: <strong>{{strftime("%d %B %Y", strtotime(date($requisition->enfant->date_naissance)))}}</strong> à <strong>{{$requisition->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>{{ $requisition->enfant->sexe=="M" ? "Fils" : "Fille"  }} de: <strong>{{$requisition->pere->nom}} {{$requisition->pere->prenom}} </strong></td>
                </tr>
                <tr>
                    <td>Né le : <strong>{{strftime("%d %B %Y", strtotime(date($requisition->pere->date_naissance)))}}</strong> à <strong>{{$requisition->pere->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>Profession: <strong>{{ $requisition->pere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $requisition->pere->nationalite->lib_nationalite }}</strong>,Domicile: <strong>{{ $requisition->pere->adresse }}</strong></td>
                </tr>
                <tr>
                    <td>Et de: <strong>{{$requisition->mere->nom}} {{$requisition->mere->prenom}}</strong></td>
                </tr>
                <tr>
                    <td>Née le : <strong>{{strftime("%d %B %Y", strtotime(date($requisition->mere->date_naissance)))}}</strong> à <strong>{{$requisition->mere->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>Profession: <strong>{{ $requisition->mere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $requisition->mere->nationalite->lib_nationalite }}</strong>,Domicile: <strong>{{ $requisition->mere->adresse }}</strong></td>
                </tr>

            </table>
            <p style="text-align: justify;margin-right: 15%;font-size: 12px;">Requiert q'en tête de l'acte ainsi reconstitué devra être portée la mention <span style="color: red;">&lsaquo;&lsaquo; ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° {{ $requisition->numero_req}}/{{{$num}}}{{--date("Y", strtotime($requisition->created_at))--}} ET TRANSCRIT DANS LES REGISTRES D'ETAT CIVIL EN COURS ./&rsaquo;&rsaquo;</span></p>
        </div>
    </div>
    <div style="bottom:0;margin-left:10px;margin-top: 613px">

        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">

            <col style="width: 10%">
            <col style="width: 65%">
            <col style="width: 25%">
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
                        {{-- <div style="margin-bottom:0;"> --}}
                            @isset($qrCode)
                            <div style="width: 30mm;">
                                <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                            </div>
                            @endisset
                        {{-- </div> --}}
                    </td>
                    <td style="text-align: left;margin-bottom:0;">

                        <p>Fait au Parquet, le {{utf8_encode(strftime("%d %B %Y", strtotime( $requisition->date_heure_declaration)))}}<br>Le Procureur de la République,</p>

                    </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
