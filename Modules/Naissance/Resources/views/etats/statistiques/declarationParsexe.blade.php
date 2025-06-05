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
        // $mois = strftime("%B", strtotime(date('Y-m-d')));
        $total = 0;
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>
            <td style="width:50%; text-align: center;">
                <strong>MINISTERE DE LA SANTE ET DE LA POPULATION</strong><br>
                <strong>{{Auth::user()->institution()->lib_institution}}</strong>
            </td>
            <td style="width:25%; text-align: center;">

            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table>

    <div style="margin-top: 50px; bottom:0;margin-left:10px;">
        <h4 style="text-align: center;">DECLARATION DE NAISSANCE PAR SEXE DU MOIS DE DECEMBRE</h4 style="text-align: center;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
            <col style="width: 50%">
            <col style="width: 50%">
            <thead>
              <tr style="text-align: center;">
                <td style="text-align: center;border: 1px solid black;">SEXE</td>
                <td style="text-align: center;border: 1px solid black;">NOMBRE</td>
              </tr>
            </thead>
            <tbody>
                @foreach ($datas as $data)
                    <tr>
                        <td style="text-align: left;border: 1px solid black;">{{$data->sexe == "M" ? "Masculin":"Féminin"}}</td>
                        <td style="text-align: left;border: 1px solid black;">{{ $data->TOTAL }}</td>
                    </tr>
                    @php
                        $total += (int)$data->TOTAL;
                    @endphp
                @endforeach
                <tr>
                    <td style="text-align: center;border: 1px solid black;">TOTAL</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $total }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</page>
