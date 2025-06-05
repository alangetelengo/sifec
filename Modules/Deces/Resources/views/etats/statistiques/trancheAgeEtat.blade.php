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
        $annee = utf8_encode(strftime("%Y", strtotime(date('Y-m-d'))));
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
        <h4 style="text-align: center;">DECLARATION DE DECES PAR TRANCHES D'AGE <br> MOIS DE: {{ Str::upper($mois)." ".$annee }}</h4 style="text-align: center;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
            <col style="width: 50%">
            <col style="width: 50%">
            <thead>
              <tr style="text-align: center;">
                    <td style="text-align: center;border: 1px solid black;font-weight: bold;">Tranches d'âge</td>
                    <td style="text-align: center;border: 1px solid black;font-weight: bold;">Nombre</td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;border: 1px solid black;">Moins de 18 ans</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $moinsde18 }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;border: 1px solid black;">De 18 à 29 ans</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $de18a29 }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;border: 1px solid black;">De 30 à 65 ans</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $de30a65 }}</td>
                </tr>
                <tr>
                    <td style="text-align: center;border: 1px solid black;">Plus 65 ans</td>
                    <td style="text-align: center;border: 1px solid black;">{{ $plusde65 }}</td>
                </tr>
                <tr width="100%">
                    <td colspan="2" class="text-center" style="text-align: center;border: 1px solid black;"><strong>Total {{ $tout }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

</page>

