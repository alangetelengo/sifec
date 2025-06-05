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
    @php
        $tribunal = "";
        if ($jugement->type_declaration == "CERTIFICAT DE NON INSCRIPTION") {
            $tribunal = $jugement->institutionUser->institution->institutionParent->lib_institution;

        }else {
            $tribunal = $jugement->institutionUser->institution->institutionParent->institutionParent->lib_institution;
        }

        $institution = "";
        if ($jugement->type_declaration == "CERTIFICAT DE NON INSCRIPTION") {
            $institution = $jugement->institutionUser->institution->lib_institution;
        } else {
            $institution = $jugement->institutionUser->institution->institutionParent->lib_institution;
        }

        $num = "";
        setlocale(LC_TIME, "fr_FR", "French");
        if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
            $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
        } else {
            $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 10pt;">
        <tr>
            <td style="width:40%; text-align: center; font-size: 200!important%">
                @php
                 $courAppel = "";
                    if ($jugement->type_declaration == "CERTIFICAT DE NON INSCRIPTION") {
                        $courAppel = $jugement->institutionUser->institution->institutionParent->institutionParent->lib_institution;
                    } else {
                        $courAppel = $jugement->institutionUser->institution->institutionParent->institutionParent->institutionParent->lib_institution;
                    }
                @endphp
                <br><br>
                <p>
                    <span>{{$courAppel}}</span><br>
                    <span>{{$tribunal}}</span><br>
                    <span>PARQUET</span><br>
                    <span>N° {{ $jugement->numero_req}}/{{date("Y", strtotime($jugement->created_at))}}</span>
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

    <table align="center" style="border-radius: 1mm;">
        <tr>
            <td style="text-align: center;border: solid;">
                <p style="margin-bottom: 20px;"><strong style="font-size: 130%;">JUGEMENT AUX FINS DE DECLARATION TARDIVE DE NAISSANCE</strong><br> Procureur de la République près le {{$tribunal}}</p>
            </td>
        </tr><br><br>
    </table>
</page>
