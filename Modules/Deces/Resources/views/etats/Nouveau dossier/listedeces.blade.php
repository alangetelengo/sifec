<style>
    td{
        font-size: 80%;
    }
    b{
        font-size: 100%;
    }
</style>
<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" footer="date;time;page" style="font-size: 12pt">
    @php
        setlocale(LC_TIME, "fr_FR", "French");
        $mois = utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
        $total = 0;

        $departement = "";
        $communeDistrict = "";
        $institution = Auth::user()->institution();
        $libInstitution = Auth::user()->institution()->lib_institution;

        $periode = "";
        if ($dated == "" && $datef == "") {
            $periode = "";
        } elseif ($dated != "" && $datef == "") {
            $periode = "Du: ".date('d-m-Y', strtotime($dated));
        }elseif ($dated == "" && $datef != "") {
            $periode = "Du: ".date('d-m-Y', strtotime($datef));
        }elseif ($dated != "" && $datef != "") {
            $periode = "Période: du ".date('d-m-Y', strtotime($dated))." au ".date('d-m-Y', strtotime($datef));
        }

    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>
            <td style="width:50%; text-align: center;">
                {{-- <span>POMBE FUNEBRE MUNICIPALE</span><br> --}}
                <h4>{{$libInstitution}}</h4>
            </td>
            <td style="width:25%; text-align: center;">

            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; * Travail * Progr&egrave;s
            </td>
        </tr>
  </table><br><br>
    <div style="text-align: center;">
        <p><strong style="font-size: 20px;">REPERTOIRE ALPHABETIQUE DE DECES </strong><br> <strong>{{ $periode }}</strong></p>
    </div>
    <div style="margin-top: 10px; bottom:0;margin-left:10px;">

        <table class="historique" cellspacing="0" style="width: 100%; font-size: 20px;border: 2px solid black;">
            <col style="width: 5%">
            <col style="width: 30%">
            <col style="width: 10%">
            <col style="width: 18%">
            <col style="width: 15%">
            <col style="width: 22%">
            <thead>
              <tr style="text-align: center;">
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">N°</td>
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">Nom et prénom</td>
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">Sexe</td>
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">Acte de décès</td>
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">Date de décès</td>
                <td style="font-weight: bold; text-align: center;border: 1px solid black;">Lieu de survenance</td>
              </tr>
            </thead>
            <tbody>
                @php
                    $tab = [];
                    $i = 0;
                @endphp
                @foreach ($listes as $liste)
                    @php
                        $i += 1;
                    @endphp
                    <tr style="text-align: center;">
                        <td style="text-align: center;border: 1px solid black;">{{ $i }}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ $liste->nom }} {{ $liste->prenom }}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ $liste->sexe }}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ $liste->code_acte_deces }}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ date('d-m-Y',strtotime($liste->date_heure_deces)) }}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ $liste->lib_lieu_survenance }}</td>
                    </tr>
                    {{-- @php
                        $total += (int)$data->TOTAL;
                    @endphp --}}

                @endforeach
                {{-- @php
                    dd($array);
                @endphp --}}
                {{-- <tr>
                    <td style="text-align: center;border: 1px solid black;">TOTAL</td>
                    <td style="text-align: center;border: 1px solid black;" colspan="2">TOTAL</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $total }}</td>
                </tr> --}}
            </tbody>
        </table>
    </div>

</page>
