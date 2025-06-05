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
 <page orientation="portrait" backimg="{{ asset("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="100%" backtop="0"  backbottom="30mm" footer="date;time;page" style="font-size: 12pt">
   @php

   @endphp

   <table cellspacing="0" style="width: 100%; font-size: 12pt; margin-left: 20px;">
       <tr>
           <td style="width:33%; text-align: center;">
               <p>
                <strong>DEPARTEMENT DE BRAZZAVILLE</strong><br>
                <strong>COMMUNE DE BRAZZAVILLE</strong><br>
                <strong>MAIRIE DE MFILOU</strong><br>
                <strong>QUARTIER 710 KAHOUNGA</strong>
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
   </table><br><br><br><br><br><br><br>
   <table align="center" style="border-radius: 1mm; border: none;">
       <tr style="">
           <td style="width:80%; text-align: center;">
               <p><strong style="font-size: 140%;">ATTESTATION DE DOT</strong>
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
       <div style="position: absolute; left: 20px; top: 350px; width: 700px; height: 1000px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:20px;">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr style="margin-right: 10px;">
                <td>
                    Je soussigné <strong>KOUKOU KALA Julien</strong> <br>
                    Filliation: <strong>Père</strong><br>
                    Adressse: <strong>147 rue Grémoire, Moungali, Brazzaville</strong><br><br>
                    Reconnais avoir reçu la somme de cinquante mille (50 000) FCFA en guise de la dote de <br>
                    Mlle <strong>KOUKOU KALA Ruth</strong><br><br>

                    En foi de quoi, la présente attestation a été établie pour servir et valoir ce que de droit.
                </td>
                <td>

                </td>
            </tr>
           </table><br><br>
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
                            <td>Représentant de la famille</td>
                            <td>
                            </td>
                            <td>
                                Fait à Brazzaville, le
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
                                Chef du quartier
                            </td>
                        </tr>
                        <tr>
                            <td>
                                {{-- VU pour légalisation de la signature <br>
                                De Mme, M <br>
                                Kintélé, le --}}
                            </td>
                        </tr>
                </tbody>
            </table>
       </div>
   </div>


</page>
