<style>
    page{
       position: relative;
   }
   td{
       font-size: 80%;
       height: 15px;
   }
   b{
       font-size: 120%;
   }

</style>
<page orientation="portrait" backimg="{{ public_path("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        $infos = "";
        $courAppel  = $requisition->institutionUser->institution->institutionParent->institutionParent->lib_institution;
        $tribunal   = $requisition->institutionUser->institution->institutionParent->lib_institution;
        setlocale(LC_TIME, "fr_FR", "French");


        $num = "";
        $titre = "";
        $top = "";

        if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
            $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
        } else {
            $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }

        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = $requisition->institutionUser->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
        
        $localite = $localisationData['localite'];
        $localiteParent = $localisationData['localiteParent'];
        $inst = $localisationData['inst'];
        $localisation = $localisationData['localisation'];
    @endphp

    <table cellspacing="0" style="width: 100%; font-size: 12pt; margin-left: 20px;">
        <tr>
            <td style="width:33%; text-align: center;">
                <p>
                 {{-- <strong>{{ $courAppel }}</strong><br> --}}
                 <strong>{{ $tribunal }}</strong><br>
                 <strong>PARQUET DU PROCUREUR <br> DE LA REPUBLIQUE</strong><br>
                 <strong>N° {{ $requisition->numero_dispense }}</strong>
                </p>
            </td>
            <td style="width:33%; text-align: center;">
             <qrcode value="{{ env('QRCODE_URL') }}/qrcode?niupp=Je suis Vincent" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
                {{-- <img src='{{ asset("app/sceau/zV1hQ11E1DZxoS684MtrLeuhoIV8AVhx6OeUztJo.png") }}' alt="" width="100" height="100"> --}}

            </td>
            <td style="width:33%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
    </table><br><br><br>
s
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:80%; text-align: center;">
                <p><strong style="font-size: 140%;">{{ $requisition->titre_requisition }}</strong>
                 <br> Le procureur de la République près le {{ $tribunal }}
             </p>
            </td>
            <td style="width:20%; text-align: center;">

            </td>
        </tr><br>
    </table>
    <div style="margin-left: 4px;border-radius: 2mm;">
       <div style="position: absolute; left: 8px; top: 310px; width: 720px; height: 1000px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:16px;">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr style="margin-right: 10px;">
                <td>
                    Vu les articles 143, 144, 150 et 151 du code de la famille ; <br>
                    Vu la requête aux fins de dispense de publication, de tout délai en vue de célébration d’un mariage <br>
                    formulée en date du {{strftime("%d %B %Y", strtotime(date($requisition->date_declaration_mariage)))}} par <strong>Monsieur {{ $requisition->epoux->nomcomplet() }}</strong> ; <br>
                    Vu les pièces de l’entier dossier. <br><br>

                    Attendu qu’il est envisagé un mariage entre <strong>Monsieur {{ $requisition->epoux->nomcomplet() }}</strong>, de nationalité <br>
                    {{ $requisition->epoux->nationalite->lib_nationalite }}, domicilié au {{ $requisition->epoux->adresse }} et <strong>Madame {{ $requisition->epouse->nomcomplet() }}</strong>, de nationalité {{ $requisition->epouse->nationalite->lib_nationalite }}, domiciliée au {{ $requisition->epouse->adresse }}. <br><br>

                    Qu’à cet effet, un dossier a été déposé à la {{ $inst }},{{ $localisation }}, où leur union serait <br>
                    célébrée par l’Officier d’état civil <strong>le {{strftime("%d %B %Y", strtotime(date($requisition->date_prevue_mariage)))}}</strong>. <br><br>

                    Que le requérant sollicite une dispense de délai de publication de bans afin que leur mariage soit <br>
                    célébré à une date plus proche que prévue. <br><br>

                    Attendu qu’il résulte que la célébration de ce mariage par l'officier d'état-civil, est fixée à une <br>
                    date plus proche, au point où il en serait nécessaire de déroger aux prescriptions légales imposant <br>
                    la publication de bans et de délais conformément aux dispositions des articles 143, 144, 150 et 151 <br>
                    du code de la famille. <br>
                    Qu’il y a lieu d’accorder une dispense à cet effet. <br><br>

                    <div style="text-align: center;"><strong>EN CONSEQUENCE</strong></div> <br>
                    Vu les dispositions des articles 143, 144, 150 et 151 du code de la famille précitées. <br>
                    Dispense les futurs époux sus-designés du lieu de célébration de mariage, de délais et de <br> publication de bans./-

                </td>
                <td>

                </td>
            </tr>
           </table>
           <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;">
                <col style="width: 35%">
                <col style="width: 30%">
                <col style="width: 35%">
                <thead>
                    <tr style="text-align: center">
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                            </td>
                            <td>
                                Fait au Parquet, le {{ date("d-m-Y", strtotime($requisition->updated_at)) }}
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                            </td>
                            <td>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                            </td>
                            <td style="text-align: left;padding-top: 30px;">
                                Le Procureur de la République
                            </td>
                        </tr>
                </tbody>
            </table>
       </div>
   </div>


</page>
