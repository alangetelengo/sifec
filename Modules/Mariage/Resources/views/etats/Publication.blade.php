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
        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = $dm->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
        
        $commune = $localisationData['localite'];
        $dpt = $localisationData['localiteParent'];
        $cec = $localisationData['inst'];
        $localisation = $localisationData['localisation'];
   @endphp

   <table cellspacing="0" style="width: 100%; font-size: 12pt; margin-left: 20px;">
       <tr>
           <td style="width:33%; text-align: center;">
               <p>
                <strong>{{ $dpt }}</strong><br>
                <strong>{{ $commune}}</strong><br>
                <strong>{{ $cec }}</strong>
            </p>
           </td>
           <td style="width:33%; text-align: center;">
            <qrcode value="{{ env('QRCODE_URL') }}/qrcode?niupp=Je suis Vincent" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>

           </td>
           <td style="width:33%; text-align: center;">
               <strong>REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; - Travail - Progr&egrave;s
           </td>
       </tr>
   </table><br><br><br>
   <table align="center" style="border-radius: 1mm; border: none;">
       <tr style="">
           <td style="width:80%; text-align: center;">
            @php
                $lieu = $dm->lieu_ceremonie_mariage;
            @endphp
            @if( $lieu == "Hors centre d\'état civil")
                <p><strong style="font-size: 140%;">PUBLICATION DE MARIAGE<br> A CELEBRER AU {{ Str::upper($dm->adresse_celebration_mariage. " le ".date("d/m/Y", strtotime($dm->date_prevue_mariage))) }}</strong>
            @else
                <p><strong style="font-size: 140%;">PUBLICATION DE MARIAGE<br> A CELEBRER AU {{ Str::upper($lieu. " le ".date("d/m/Y", strtotime($dm->date_prevue_mariage))) }}</strong>
            @endif
            </p>
           </td>
           <td style="width:20%; text-align: center;">

           </td>
       </tr><br>
   </table>
   <div style="margin-left: 4px;border-radius: 2mm;">
       <div style="width: 150px;text-align: center;">
           {{-- <p>Marge réservée aux mention <br> d'officier(1)</p> --}}

       </div>
       <div style="position: absolute; left: 25px; top: 300px; width: 700px; height: 1000px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:20px;">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr>
                <td>
                    <div style="text-align: center;"><strong>ENTRE</strong></div>
                </td>
            </tr>
            <tr style="margin-right: 10px;">
                <td style="border-top: 1px solid #000;padding: 10px;">
                    Mr: <strong>{{ $dm->epoux->nomcomplet()  }}</strong><br>
                    Né le : <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($dm->epoux->date_naissance)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($dm->epoux->date_naissance)))." ".\App\Sifec\Sifec::asLetters(date("Y", strtotime($dm->epoux->date_naissance))) }}</strong> à <strong> {{ $dm->epoux->lieu_naissance }}</strong><br>
                    Nationalité: <strong>{{  $dm->epoux->nationalite->lib_nationalite  }}</strong><br>
                    Profession: <strong>{{  $dm->epoux->profession->lib_profession  }}</strong><br>
                    Domicilié: <strong>{{ $dm->epoux->adresse }}</strong><br>
                    Situation matrimoniale:

                    @if($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0005")
                    ,N&deg; du jugement du divorce : <strong>{{ $dm->numero_jugement_divorce_epoux }}</strong>
                    @elseif($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0006")
                    ,N&deg; d'acte de décès de l'épouse : <strong>{{ $dm->numero_acte_deces_epouse }}</strong>

                    @elseif($dm->situationMatEpoux->code_situation_matrimoniale == "SMAT_0001")
                    ,N&deg; d'acte de mariage : <strong>{{ $dm->numero_acte_mariage_epoux }}</strong>
                    @else
                    <strong>{{ $dm->situationMatEpoux->lib_situation_matrimoniale }}</strong><br>
                    @endif
                    Fils de: <strong>{{ $dm->pere_epoux }}</strong><br>
                    Et de: <strong>{{ $dm->mere_epoux }}</strong><br><br>
                    Option mariage: <strong>{{ $dm->optionMariage->lib_option_mariage }}</strong><br>
                    Régime : <strong>{{ $dm->regime->lib_regime }}</strong>

                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <div style="text-align: center;"><strong>ET</strong></div>
                </td>
            </tr>
            <tr style="margin-right: 10px;">
                <td style="border-top: 1px solid #000;padding: 10px;">
                    Mlle: <strong>{{ $dm->epouse->nomcomplet()  }}</strong><br>
                    Née le : <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($dm->epouse->date_naissance)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($dm->epouse->date_naissance)))." ".\App\Sifec\Sifec::asLetters(date("Y", strtotime($dm->epouse->date_naissance))) }}</strong> à <strong> {{ $dm->epouse->lieu_naissance }}</strong><br>
                    Nationalité: <strong>{{  $dm->epouse->nationalite->lib_nationalite  }}</strong><br>
                    Profession: <strong>{{  $dm->epouse->profession->lib_profession  }}</strong><br>
                    Domiciliée: <strong>{{  $dm->epouse->adresse }}</strong><br>
                    Situation matrimoniale:
                    <strong>
                        @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0005")
                        ,N&deg; du jugement du divorce : <span style="font-size: 10px;font-weight:bold;">{{ $dm->numero_jugement_divorce_epouse }}</span>
                        @endif
                        @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0006")
                        ,N&deg; d'acte de décès de l'épouse : <span style="font-size: 10px;font-weight:bold;">{{ $dm->numero_acte_deces_epouse }}</span>
                        @endif
                        @if($dm->situationMatEpouse->code_situation_matrimoniale == "SMAT_0001")
                        ,N&deg; d'acte de mariage : <span style="font-size: 10px;font-weight:bold;">{{ $dm->numero_acte_mariage_epouse }}</span><br>
                        Option mariage: : <span style="font-size: 10px;font-weight:bold;">{{ "//" }}</span>
                        @endif
                    </strong><br>
                    Fille de: <strong>{{ $dm->pere_epouse }}</strong><br>
                    Et de: <strong>{{ $dm->mere_epouse }}</strong><br>
                </td>
            </tr>
            <tr style="margin-right: 10px;">
                <td><br><br> Ladite Publication, inscrite d'après le consentement des parties et conformément à la loi, sera <br> affichée au centre de l'état civil.</td>
            </tr>
           </table>
           <table class="historique" cellspacing="0" style="width: 95%; font-size: 18px;">
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
                            <td style="font-size: 12px;">
                                {{-- Fait à {{ $dm->institutionUser->institution->arrondissement->commune->lib_commune }}, le {{ date("d/m/Y", strtotime($dm->date_declaration_mariage)) }}<br> --}}
                                Fait à <span style="text-transform:capitalize">{{ $localisation }}</span>, le  {{ date("d/m/Y", strtotime($dm->date_declaration_mariage)) }}<br>
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
                                l'Officier de l'état civil
                            </td>
                        </tr>
                </tbody>
            </table>
       </div>
   </div>


</page>
