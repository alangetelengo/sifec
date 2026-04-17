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
<page orientation="portrait" backimg="{{asset("tpl/armoirie_congo.png")}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" footer="date;time;page" style="font-size: 12pt">

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
                    <span>{{$requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution}}</span><br>
                    <span>{{$requisition->institutionUser->institution->institutionParent->lib_institution}}</span><br>
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
                <p><strong style="font-size: 130%;margin-right: 0%;">REQUISITION AUX FINS DE TRANSCRIPTION DE l'ACTE DE DECES </strong><br> Procureur de la République près du {{$requisition->institutionUser->institution->institutionParent->lib_institution}}</p>
            </td>
        </tr>
    </table>
    <div style="margin-top: 0%;margin-left: 4%;margin-right: 6%;border-radius: 2mm;">
        <div style="right:11px; left: 15px; top: 10px; width: 800px; height: 720px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:20px;">
            <p style="margin-right: 15%;text-align: justify;font-size: 80%;">

                {{-- Vu la requête de {{$requisition->declarant->nom ." " .$requisition->declarant->prenom}} en date du {{strftime("%d %B %Y", strtotime(date($requisition->created_at)))}} de nationalité congolaise,  domicilié au {{ \App\Sifec\Sifec::adressepersonne($requisition->declarant->code_personne) }}  tendant à la transcription dans les registres de l’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} la copie intégrale d’acte de décès de dressé sous le n°{{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}} et transcrit dans les registres d’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} <br> --}}
                Vu la requête de {{$requisition->declarant->nom ." " .$requisition->declarant->prenom}} en date du {{strftime("%d %B %Y", strtotime(date($requisition->created_at)))}} de nationalité congolaise,  domicilié au {{ $requisition->declarant->adresse }}  tendant à la transcription dans les registres de l’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} la copie intégrale d’acte de décès de dressé sous le n°{{ $requisition->numero_req}}/{{date("Y", strtotime($requisition->created_at))}} et transcrit dans les registres d’Etat-Civil {{$requisition->institutionUser->institution->lib_institution}} <br>

                <br> Attendu que le requérant expose que le surnommé(e) {{$requisition->defunt->nom}} {{$requisition->defunt->prenom}}  est décédé le {{ strftime("%d %B %Y", strtotime($requisition->date_heure_deces)) }} à {{ $requisition->lieu_deces }} <br> <br>

                Que ce décès a été reçu selon les formes et usages dans ce pays ;
                Que cependant et, alors même que l’autorise le code de la famille, la copie intégrale de l’acte de décès du défunt n’a jamais été transcrite dans les registres des {{$requisition->institutionUser->institution->lib_institution}} <br> <br>

                Qu’il recourt au ministère public pour remédier à cette carence ; <br>

                Que la transcription sollicitée devra être effectuée par l’Officier de l’Etat Civil {{$requisition->institutionUser->institution->lib_institution}} <br>

                <br> Attendu que les documents produits par la requérante sont probants et font foi ; <br>

            </p>
            <p style="margin-right: 15%;text-align: center;font-size: 80%;"><strong style="font-size: 100%;">EN CONSEQUENCE</strong><br>
                Requiert qu’il plaise à l’Officier de l’Etat Civil {{$requisition->institutionUser->institution->lib_institution}} procéder à la transcription de la copie intégrale d’acte de décès ci-joint faisant constater le décès de <strong>{{$requisition->defunt->nom}} {{$requisition->defunt->prenom}}</strong> {{ $requisition->defunt->sexe=="M" ? "né" : "née"  }} le <strong>{{ strftime("%d %B %Y", strtotime( $requisition->defunt->date_naissance))}}</strong> à <strong>{{$requisition->defunt->lieu_naissance}}</strong> {{ $requisition->defunt->sexe=="M" ? "fils" : "fille"  }} de {{$requisition->pere ? $requisition->pere->nom." ".$requisition->pere->prenom : "XXXXXXXXXX"}} et de {{$requisition->mere ? $requisition->mere->nom." ".$requisition->mere->prenom : "XXXXXXXXXX"}}, décès survenu le {{ strftime("%d %B %Y", strtotime($requisition->date_heure_deces)) }} à {{ $requisition->lieu_deces }} <br><br>
                Requiert qu’en marge de l’acte antérieur le plus proche en date,  dressé dans les registres de l’année en cours,  devra figurer  la mention  de la présente transcription et son numéro./-
            </p>

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
