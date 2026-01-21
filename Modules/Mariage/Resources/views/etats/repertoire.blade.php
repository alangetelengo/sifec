<style>
    td{
        font-size: 14px;
    }
    b{
        font-size: 14px;
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
    <table cellspacing="0" style="width: 100%; font-size: 14px;">
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
        <h4 style="text-align: center;">REPERTOIRE ALPHABETIQUE DES ACTES DE MARIAGE <br>
            <small>
                @if ($dated != null && $datef != null)
                    Du {{ date('d-m-Y', strtotime($dated)) }} au {{ date('d-m-Y', strtotime($datef)) }}
                @endif

                @if ($dated != null && $datef == null)
                    Du {{ date('d-m-Y', strtotime($dated)) }}
                @endif
                @if ($dated == null && $datef != null)
                    Du {{ date('d-m-Y', strtotime($datef)) }}
                @endif
            </small>
            </h4 style="text-align: center;">

        <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;border: 2px solid black;">
            <col style="width: 10%">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 20%">
            <col style="width: 20%">
            <thead>
              <tr style="text-align: center;">
                <td style="text-align: center;border: 1px solid black;">N°</td>
                <td style="text-align: center;border: 1px solid black;">Epoux</td>
                <td style="text-align: center;border: 1px solid black;">Epouse</td>
                <td style="text-align: center;border: 1px solid black;">Date célébration</td>
                <td style="text-align: center;border: 1px solid black;">N° acte</td>
              </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($actes as $acte)

                    <tr>
                        <td style="text-align: left;border: 1px solid black;">{{$i++}}</td>
                        <td style="text-align: left;border: 1px solid black;">{{ $acte->declaration->epoux->nom }} {{ $acte->declaration->epoux->prenom }}</td>
                        <td style="text-align: left;border: 1px solid black;"> {{ $acte->declaration->epouse->nom }} {{ $acte->declaration->epouse->prenom }}</td>
                        <td style="text-align: left;border: 1px solid black;">{{date('d-m-Y', strtotime($acte->date_emission))}}</td>
                        <td style="text-align: left;border: 1px solid black;">{{ $acte->code_acte_mariage }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

</page>
