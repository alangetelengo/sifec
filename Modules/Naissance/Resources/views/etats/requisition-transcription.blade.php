<style>
    td{
        font-size: 14px;
        height: 16px;
    }
    b{
        font-size: 14px;
    }
    tr{
        width:100%; text-align: left; padding-bottom: 1px;
    }
    .para{
        margin-right: 15%;text-align: justify;font-size: 14px; margin-bottom: -5px;
    }
</style>
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        $tribunal = $requisition->institutionUser->institution->institutionParent->lib_institution;
        $num = "";
        setlocale(LC_TIME, "fr_FR", "French");
        if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
            $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
        } else {
            $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 14px;">
        <tr>
            <td style="width:40%; text-align: center; font-size: 14px!important%">
                @php
                    $courAppel = $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution;
                @endphp
                <br><br>
                <p>
                    {{-- <span>{{$courAppel}}</span><br> --}}
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
  </table><br>

    <table align="center" style="border-radius: 1mm; border: none;">
        <tr>
            <td style="width:100%; text-align: center;border: solid;">
                <p><strong style="font-size: 18px;margin-right: 0%;">REQUISITION AUX FINS DE TRANSCRIPTION D'UN ACTE DE NAISSANCE </strong><br> Procureur de la République près le {{$tribunal}}</p>
            </td>
            <td style="width:15%; text-align: center;">
            </td>
        </tr><br><br>
    </table>
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; right:11px; left: 15px; top: 200px; width: 800px; height: 750px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
            <p class="para">Vu la requête en date du {{strftime("%d %B %Y", strtotime(date($requisition->date_heure_declaration)))}}, à {{-- $requisition->institutionUser->institution->localite->localiteParent->lib_localite --}}, introduite par {{$requisition->declarant->nom}} {{$requisition->declarant->prenom}}, {{ $requisition->declarant->nationalite->lib_nationalite }}, {{ $requisition->enfant->sexe=="M" ? "domicilié" : "domiciliée"  }} {{ \App\Sifec\Sifec::adressepersonne($requisition->declarant->code_personne) }}, agissant en qualité de {{$requisition->filiation->lib_filiation}} ;
            </p>
            <p class="para">Tendant à la transcription dans les registres de la Commune de Brazzaville, de la copie intégrale de l’acte de naissance de {{ $requisition->enfant->sexe=="M" ? "le nommé" : "la nommée"  }} {{$requisition->enfant->nom}} {{$requisition->enfant->prenom}} dressé par l’Officier d’Etat de {{ $requisition->cec_naissance }};</p>
            <p class="para">Attendu que le requérant expose au soutien de sa requête que {{$requisition->enfant->nom}} {{$requisition->enfant->prenom}} {{ $requisition->enfant->sexe=="M" ? "né" : "née"  }} le {{strftime("%d %B %Y", strtotime(date($requisition->enfant->date_naissance)))}}  à {{$requisition->enfant->lieu_naissance}};</p>
            <p class="para">Attendu que ladite naissance a été reçue suivant la procédure en vigueur en/au/à {{$requisition->pays_naissance_enfant}}, comme l’en témoigne la copie intégrale de l’acte de naissance versée au dossier ;</p>
            <p class="para">Que nonobstant les prévisions de l’article 38 du code congolais de la famille, ladite naissance n’a jamais été enregistrée à l’Etat-Civil congolais ;</p>
            <p class="para">Qu’ainsi, pour pallier cette carence, la transcription ainsi sollicitée devra être effectuée par l’Officier d’Etat-Civil de la Mairie Centrale de Brazzaville</p>

            <p class="para" style="text-align: center;font-size: 14px;"><strong style="font-size: 14px;">EN CONSEQUENCE</strong><br>Vu l’article 38 du code précité <br>Requiert qu’il plaise à l’OFFICIER d’état civil de la Mairie Centrale de Brazzaville de transcrire comme suit, l'acte de naissance ci-joint:
            </p><br>
            <table align="left" style="margin-left: 0%;border-radius: 1mm; border: none;">
                <tr>
                    <td>Nom: <strong>{{$requisition->enfant->nom}}</strong>, prénom: <strong>{{$requisition->enfant->prenom}}</strong></td>
                </tr>
                <tr>
                    <td>Sexe: <strong>{{ $requisition->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong>, Date de naissance: <strong>{{strftime("%d %B %Y", strtotime(date($requisition->enfant->date_naissance)))}}</strong> à <strong>{{$requisition->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>{{ $requisition->enfant->sexe=="M" ? "Fils" : "Fille"  }} de: <strong>{{$requisition->pere->nom}} {{$requisition->pere->prenom}} </strong></td>
                </tr>
                <tr>
                    <td>Né le : <strong>{{strftime("%d %B %Y", strtotime(date($requisition->pere->date_naissance)))}}</strong> à <strong>{{$requisition->pere->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>Profession: <strong>{{ $requisition->pere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $requisition->pere->nationalite->lib_nationalite }}</strong>,Domicile: <strong>{{ \App\Sifec\Sifec::adressepersonne($requisition->pere->code_personne) }}</strong></td>
                </tr>
                <tr>
                    <td>Et de: <strong>{{$requisition->mere->nom}} {{$requisition->mere->prenom}}</strong></td>
                </tr>
                <tr>
                    <td>Née le : <strong>{{strftime("%d %B %Y", strtotime(date($requisition->mere->date_naissance)))}}</strong> à <strong>{{$requisition->mere->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>Profession: <strong>{{ $requisition->mere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $requisition->mere->nationalite->lib_nationalite }}</strong>,Domicile: <strong>{{ \App\Sifec\Sifec::adressepersonne($requisition->mere->code_personne) }}</strong></td>
                </tr>
            </table>
            <p style="text-align: justify;margin-right: 15%;font-size: 14px;">Requiert qu'en tête de l'acte ainsi dressé sera portée la mention <span style="color: red;">&lsaquo;&lsaquo; ACTE TRANSCRIT SUIVANT REQUISITION N° {{ $requisition->numero_req}}/{{$num}}{{--date("Y", strtotime($requisition->created_at))--}} ET TRANSCRIT DANS LES REGISTRES D'ETAT CIVIL EN COURS &rsaquo;&rsaquo;</span>./</p>
        </div>
    </div>
    <div style="margin-top: 620px; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
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
                        @isset($qrCode)
                        <div style="margin-bottom:0; width: 30mm;">
                            <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                        </div>
                        @endisset
                    </td>
                    <td style="text-align: left;">
                        <p>Fait au Parquet, le {{utf8_encode(strftime("%d %B %Y", strtotime( $requisition->date_heure_declaration)))}}<br>Le Procureur de la République,</p>

                    </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
