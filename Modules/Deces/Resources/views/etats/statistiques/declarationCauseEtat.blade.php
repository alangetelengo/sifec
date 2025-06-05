<style>
    td{
        font-size: 80%;
    }
    b{
        font-size: 100%;
    }
</style>
<page orientation="portrait" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        setlocale(LC_TIME, "fr_FR", "French");
        $mois = utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
        $total = 0;

        $departement = "";
        $communeDistrict = "";
        $institution = Auth::user()->institution();
        $libInstitution = Auth::user()->institution()->lib_institution;
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>
            <td style="width:50%; text-align: center;">
                <span>MINISTERE DE LA SANTE ET DE LA POPULATION</span><br>
                <strong>{{$libInstitution}}</strong>
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
        <h4 style="text-align: center;">DECLARATION DE DECES PAR CAUSE ET ZONE <br> MOIS DE: {{ Str::upper($mois) }}</h4 style="text-align: center;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
            <col style="width: 30%">
            <col style="width: 50%">
            <col style="width: 20%">
            <thead>
              <tr style="text-align: center;">
                <td style="text-align: center;border: 1px solid black;">ZONES</td>
                <td style="text-align: center;border: 1px solid black;">CAUSES</td>
                <td style="text-align: center;border: 1px solid black;">NOMBRE</td>
              </tr>
            </thead>
            <tbody>
                @php
                    $tab = [];
                @endphp
                @foreach ($datas as $data)
                    <tr>
                            @php
                               $array = implode("','",$tab);
                               $tab[] = $data->lib_arrondissement;
                            @endphp
                            @if (str_contains($array, $data->lib_arrondissement) == 0)
                                <td style="text-align: left;border-top: 1px solid black;border-right: 1px solid black;">{{ $data->lib_arrondissement }}</td>
                            @else
                            <td style="border-right: 1px solid black;"></td>
                            @endif
                        <td style="text-align: left;border: 1px solid black;">{{$data->lib_cause_deces}}</td>
                        <td style="text-align: center;border: 1px solid black;">{{ $data->TOTAL }}</td>
                    </tr>
                    @php
                        $total += (int)$data->TOTAL;
                    @endphp

                @endforeach
                {{-- @php
                    dd($array);
                @endphp --}}
                <tr>
                    {{-- <td style="text-align: center;border: 1px solid black;">TOTAL</td> --}}
                    <td style="text-align: center;border: 1px solid black;" colspan="2">TOTAL</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $total }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</page>
