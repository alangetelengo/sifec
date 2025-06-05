<style>
    td{
        font-size: 80%;
        height: 16px;
    }
    b{
        font-size: 80%;
    }
    tr{
        width:100%; text-align: left; padding-bottom: 4px;
    }
</style>
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">

    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width:40%; text-align: center; font-size: 200!important%">
                @php
                    setlocale(LC_TIME, "fr_FR", "French");
                    // $courAppel = $requisition->institutionUser->institution->tribunal->courAppel->lib_cour_appel;
                    $tribunal = $requisition->institutionUser->institution->lib_institution;
                @endphp
                <br><br>
                <p>
                    @if($requisition->type_declaration == "DECLARATION TARDIVE")
                    <span>{{ $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution }}</span><br>
                    <span>{{$requisition->institutionUser->institution->institutionParent->lib_institution}}</span><br>
                    @else
                    <span>{{ $requisition->institutionUser->institution->pompeFunebre->institutionParent->institutionParent->lib_institution }}</span><br>
                    <span>{{$requisition->institutionUser->institution->pompeFunebre->institutionParent->lib_institution}}</span><br>
                    @endif
                    <span>PARQUET</span><br>
                    <span>N° {{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}}</span>
                </p>
            </td>
            <td style="width:35%; text-align: center;">
                {{-- <img src="{{asset("tpl/sceau.png")}}" style="width: 150px; height:150px !important"> --}}
            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; * Travail * Progr&egrave;s
            </td>
        </tr>
  </table><br>

    <table align="center" style="border-radius: 1mm; border: none;">
        <tr>
            <td style="width:100%; text-align: center; border:solid;margin:10%;">
                <p><strong style="font-size: 130%;margin-right: 0%;">REQUISITION AUX FINS DE DECLARATION TARDIVE DE DECES </strong><br> Procureur de la République près le {{$requisition->institutionUser->institution->institutionParent->lib_institution}}</p>
            </td>
        </tr>
    </table>
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        <div style="right:11px; left: 15px; top: 100px; width: 800px; height: 760px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:20px;">
            <p style="margin-right: 15%;text-align: justify;font-size: 80%;">
                Vu la requête introduite en date du {{strftime("%d %B %Y", strtotime(date($requisition->created_at)))}}, par {{$requisition->declarant->nom ." ".$requisition->declarant->prenom}}, domicilié au {{ $requisition->declarant->adresse }} <br><br>

                Attendu que le requérant expose qu’en date du {{ strftime("%d %B %Y", strtotime($requisition->date_heure_deces)) }} à {{ $requisition->lieu_deces }} est {{ $requisition->defunt->sexe=="M" ? "décédé le nommé" : "décédée la nommée"  }} {{$requisition->defunt->nom}} {{$requisition->defunt->prenom}}  <br><br>

                Que ce décès n’a pas été déclaré dans le délai légal conformément aux dispositions de l’article 60 du code de la famille ; <br><br>

                Attendu qu’aux termes de l’article 60 alinéa 5 du code de la famille « Le Procureur de la République peut, à toute époque et en dehors des délais prévus, faire la déclaration d’un décès dont il aurait eu connaissance et qui n’aurait pas été constaté à l’Etat-Civil » <br><br>

                Que cette omission doit être réparée par l’Officier de l’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} <br>

            </p>
            <p style="margin-right: 15%;text-align: center;font-size: 80%;"><strong>EN CONSEQUENCE</strong><br>
                Vu les dispositions de la loi n°73/84 du 17 octobre 1984 portant code de la famille en son article 60 alinéa 5 ; <br>

                Requiert qu’il plaise à l’Officier d’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} <br>

                Constater le décès de <strong>{{$requisition->defunt->nomcomplet()}}</strong> {{ $requisition->defunt->sexe=="M" ? "né" : "née"  }} le <strong>{{strftime("%d %B %Y", strtotime( $requisition->defunt->date_naissance))}}</strong> à <strong>{{$requisition->defunt->lieu_naissance}}</strong> {{ $requisition->defunt->sexe=="M" ? "fils" : "fille"  }} de {{$requisition->pere ? $requisition->pere->nom." ".$requisition->pere->prenom : "XXXXXXXXXX"}} et de {{$requisition->mere ? $requisition->mere->nom." ".$requisition->mere->prenom : "XXXXXXXXXX"}} {{ $requisition->defunt->sexe=="M" ? "décédé" : "décédée"  }} le {{ strftime("%d %B %Y", strtotime($requisition->date_heure_deces)) }} à {{ $requisition->lieu_deces }} <br>

            </p>

            <p style="text-align: justify;margin-right: 15%;font-size: 80%;">Requiert qu’en marge de l’acte ainsi dressé devra porter la mention  <span style="color: red;">&lsaquo;&lsaquo; INSCRIPTION TARDIVE DE DECES &rsaquo;&rsaquo;</span> suivant réquisition {{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}} et transcrit dans les registres d’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} </p>
            <div style="margin-top: 0px; bottom:0;margin-left:10px;">
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
                                <div style="margin-bottom:0;"><qrcode value="{{env('QRCODE_URL')}}/qrcode/deces/requisition?niupp={{ $requisition->code_declaration_deces }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div>
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
