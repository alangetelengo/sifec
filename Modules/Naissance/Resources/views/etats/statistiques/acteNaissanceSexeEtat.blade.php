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
        $mois =  utf8_encode(strftime("%B", strtotime(date('Y-m-d'))));
        $total = 0;

        $localite = "";
        $localiteParent = "";
        $inst = "";
        $institution = Auth::user()->institution();
        $lib = Auth::user()->institution()->lib_institution;

        if ($institution->code_arrondissement != NULL) {
            $inst = $institution->lib_institution;
            $localite = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
            $localiteParent  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
            $localisation = $institution->arrondissement->commune->lib_commune;
        }

        if ($institution->code_commune != NULL) {
            $inst = "COMMUNE DE ".$institution->commune->lib_commune;
            $localite  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
            $localisation = $institution->commune->lib_commune;
        }

        if ($institution->code_communaute_urbaine != NULL) {
            $inst = $institution->lib_institution;
            $localite = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
            $localiteParent  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }

        if ($institution->code_district != NULL) {
            $inst = $institution->lib_institution;
            $localite = "DISTRICT DE ".$institution->district->lib_district;
            $localiteParent  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
            $localisation = $institution->communauteUrbaine->district->lib_district;
        }
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12pt;">
        <tr>
            <td style="width:50%; text-align: center;">
                <span>{{ $localiteParent}}</span> <br>
                <span>{{ $localite}}</span> <br>
                <strong>{{$lib}}</strong>
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
        <h4 style="text-align: center;">NAISSANCE PAR SEXE DU MOIS DE DECEMBRE</h4 style="text-align: center;">
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
