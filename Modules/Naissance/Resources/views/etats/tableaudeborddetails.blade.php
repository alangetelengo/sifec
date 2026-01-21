
<style>
    td{
        font-size: 80%;
        border: 1px solid black;
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

        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = Auth::user()->institution();
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);

        $localite = $localisationData['localite'];
        $localiteParent = $localisationData['localiteParent'];
        $inst = $localisationData['inst'];
        $lib = $institution->lib_institution;
        $localisation = $localisationData['localisation'];
    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12pt;border: none;">
        <tr>
            <td style="width:50%; text-align: center; border: none;">
                <span>{{ $localiteParent}}</span> <br>
                <span>{{ $localite}}</span> <br>
                <span>{{$lib}}</span><br>
                <strong>{{ $hopital }}</strong>
            </td>
            <td style="width:25%; text-align: center; border: none;">

            </td>
            <td style="width:25%; text-align: center; border: none;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table>

    <div style="margin-top: 50px; bottom:0;margin-left:10px;">
        <h4 style="text-align: center;">DECLARATIONS DE NAISSANCE</h4 style="text-align: center;">

        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
            <col style="width: 40%">
            <col style="width: 20%">
            <col style="width: 20%">
            <col style="width: 20%">
            <thead>
              <tr style="text-align: center;">
                <td>SITUATION</td>
                <td>PRODUITS</td>
                <td>RECUES</td>
                <td>NON RECUES</td>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td> CUMULEE</td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationcumul[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $denvoyercum[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationcumul[0]->total - $denvoyercum[0]->total }}</span></strong> </td>
                </tr>
                <tr>
                    <td>L'ANNEE</td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationannee[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $denvoyeran[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationannee[0]->total - $denvoyeran[0]->total }}</span></strong> </td>
                    </tr>
                <tr>
                    <td>DU MOIS</td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationmois[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $denvoyermois[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationmois[0]->total - $denvoyermois[0]->total }}</span></strong> </td>
                </tr>
                <tr>
                    <td>DE LA SEMAINE</td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationsemaine[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $denvoyersemaine[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationsemaine[0]->total - $denvoyersemaine[0]->total }}</span></strong> </td>
                    </tr>
                <tr>
                    <td>DU JOUR</td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationjour[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $denvoyerjour[0]->total }}</span></strong> </td>
                    <td style="text-align: center;"> <strong><span>{{ $declarationjour[0]->total - $denvoyerjour[0]->total }}</span></strong> </td>
                </tr>
            </tbody>
        </table>
        <br>
        <h4 style="text-align: center;">ACTES DE NAISSANCE</h4 style="text-align: center;">
            <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
                <col style="width: 40%">
                <col style="width: 20%">
                <col style="width: 20%">
                <col style="width: 20%">
                <thead>
                  <tr style="text-align: center;">
                    <td>SITUATION</td>
                    <td>PRODUITS</td>
                    <td>VALIDES</td>
                    <td>NON VALIDES</td>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> CUMULEE</td>
                        <td style="text-align: center;"> <strong><span>{{ $acteproduits[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $acteproduitsv[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $acteproduitsn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>L'ANNEE</td>
                        <td style="text-align: center;"> <strong><span>{{ $acteannee[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $acteanneev[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $acteanneen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU MOIS</td>
                        <td style="text-align: center;"> <strong><span>{{ $actemois[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actemoisv[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actemoisn[0]->total }}</span></strong> </td>
                    </tr>
                    <tr>
                        <td>DE LA SEMAINE</td>
                        <td style="text-align: center;"> <strong><span>{{ $actesemaine[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actesemainev[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actesemainen[0]->total }}</span></strong> </td>
                        </tr>
                    <tr>
                        <td>DU JOUR</td>
                        <td style="text-align: center;"> <strong><span>{{ $actesjour[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actesjourv[0]->total }}</span></strong> </td>
                        <td style="text-align: center;"> <strong><span>{{ $actesjourn[0]->total }}</span></strong> </td>
                    </tr>
                </tbody>
            </table>
    </div>

</page>
