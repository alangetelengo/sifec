<style>
    td{
        font-size: 14px;
        height: 16px;
    }
    b{
        font-size: 14px;
    }
    tr{
        width:100%; text-align: left; padding-bottom: 4px;
    }
</style>
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        $typeMetaNaissance = optional($requisition->declarationNaissance)->type_declaration;
        $tribunal = "";
        if ($typeMetaNaissance == "CERTIFICAT DE NON INSCRIPTION") {
            $tribunal = $requisition->institutionUser->institution->institutionParent->lib_institution;

        }else {
            $tribunal = $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution;
        }

        $institution = "";
        if ($typeMetaNaissance == "CERTIFICAT DE NON INSCRIPTION") {
            $institution = $requisition->institutionUser->institution->lib_institution;
        } else {
            $institution = $requisition->institutionUser->institution->institutionParent->lib_institution;
        }

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
                 $courAppel = "";
                    if ($typeMetaNaissance == "CERTIFICAT DE NON INSCRIPTION") {
                        $courAppel = $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution;
                    } else {
                        $courAppel = $requisition->institutionUser->institution->institutionParent->institutionParent->institutionParent->lib_institution;
                    }
                @endphp
                <br><br>
                <p>
                    <span>{{$courAppel}}</span><br>
                    <span>{{$tribunal}}</span><br>
                    <span>PARQUET</span><br>
                    <span>N° {{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}}</span>
                </p>
            </td>
            <td style="width:35%; text-align: center;">
                {{-- <img src="{{asset("tpl/sceau.png")}}" style="width: 150px; height:150px !important"> --}}
            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>

    <table align="center" style="border-radius: 1mm;">
        <tr>
            <td style="text-align: center;border: solid;">
                <p style="margin-bottom: 20px;"><strong style="font-size: 18px;">REQUISITION AUX FINS DE DECLARATION TARDIVE DE NAISSANCE</strong><br> Procureur de la République près le {{$tribunal}}</p>
            </td>
        </tr><br><br>
    </table>
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        <div style="right:11px; left: 15px; top: 200px; width: 800px; height: 680px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
            <p style="margin-right: 15%;text-align: justify;font-size: 14px;">
                Attendu qu'en date du {{ date("d-m-Y", strtotime($requisition->created_at)) }}, nous avions eu connaissance de la naissance d'un enfant de sexe <strong>{{$requisition->enfant->sexe=="M" ? "Masculin" : "Féminin"}}</strong>, le nommé <strong>{{$requisition->enfant->nom}} {{$requisition->enfant->prenom}}</strong>, née le  {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($requisition->date_heure_naissance)))." ". \App\Sifec\Sifec::mois(date("m", strtotime($requisition->date_heure_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($requisition->date_heure_naissance))) }}  à {{$requisition->enfant->lieu_naissance}}.
                Que cette naissance n'a jamais été déclarée à l'état civil;
                Attendu qu'en application de l'article 45 du Code de la famille, que le Procureur de la
                République peut à toute époque et en dehors des délais prévus par la loi, faire la déclaration
                d'une naissance dont il aurait eu connaissance et qui n'aurait pas été constatée à l'état civil;
                Que l'omission alléguée, en l'espèce doit être réparée par l'Officier d'état civil
                de/du: <span>{{$institution}}</span> ou tout autre Officier d'état civil compétent.
            </p>
            <p style="margin-right: 15%;text-align: center;font-size: 14px;"><strong style="font-size: 14px;">EN CONSEQUENCE</strong><br>Vu l'article 45 du code de la famille:
                Requiert qu'il plaise à l'Officier d'Etat-Civil de/du : <span>{{$institution}}</span>, de constater la naissance de
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
                    <td>Date de naissance: <strong>{{ utf8_encode(strftime('%d %B %Y', strtotime( $requisition->enfant->date_naissance))) }}</strong> à <strong>{{$requisition->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr>
                    <td>{{ $requisition->enfant->sexe=="M" ? "Fils" : "Fille"  }} de: <strong>{{$requisition->pere ? $requisition->pere->nom:""}} {{$requisition->pere ? $requisition->pere->prenom:""}} </strong></td>
                </tr>
                <tr>
                    <td>Né le : <strong>{{strftime("%d %B %Y", strtotime(date($requisition->pere ? $requisition->pere->date_naissance :"")))}}</strong> à <strong>{{$requisition->pere ? $requisition->pere->lieu_naissance: ""}}</strong></td>
                </tr>
                <tr>
                    <td>Profession: <strong>{{$requisition->pere->profession->lib_profession }}</strong>, Nationalité: <strong>{{ $requisition->pere->nationalite->lib_nationalite }}</strong>,Domicile: <strong>{{ $requisition->pere->adresse }}</strong></td>
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
            <p style="text-align: justify;margin-right: 15%;font-size: 14px;">Ordonne qu'en tête de l'acte ainsi dressé devra
                être porté la mention <span style="color: red;">&lsaquo;&lsaquo; INSCRIPTION DE DECLARATION TARDIVE SUIVANT
                    REQUISITION N° {{ $requisition->numero_req}}/{{$num}}

                    &rsaquo;&rsaquo;</span>
                    et transcription dans les registres de l'état civil compétent. /-
                 </p>

                <div style="margin-top: 0px; bottom:0;margin-left:14px;">
                    <table class="historique" cellspacing="0" style="width: 95%; font-size: 12px;">
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
        </div>
    </div>



</page>
