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
                <strong>COMMUNE DE BRAZZAVILLE</strong><br>
                {{-- <strong>COMMUNE DE BRAZZAVILLE</strong><br> --}}
                {{-- <strong>TRIBUNAL D'INSTANCE DE TALANGAI/OUENZE</strong><br>
                <strong>PARQUET DU PROCUREUR <br> DE LA REPUBLIQUE</strong><br>
                <strong>N°</strong> --}}
                   {{-- <span>
                       <strong>{{ $localiteParent }}</strong>
                   </span> <br>
                   <span>{{ $localite}}</span> <br>
                   <span>{{ $inst }}</span> --}}
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
   {{-- <table cellspacing="0" style="width: 100%; font-size: 12pt; margin-left: 20px;">
    <tr>
        <td style="width:33%; text-align: center;">
        </td>
        <td style="width:33%; text-align: center;">

        </td>
        <td style="width:33%; text-align: center;">

        </td>
    </tr>
</table> --}}
   <table align="center" style="border-radius: 1mm; border: none;">
       <tr style="">
           <td style="width:80%; text-align: center;">
               <p><strong style="font-size: 140%;">CERTIFICAT DE COUTUME</strong>
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
       <div style="position: absolute; left: 12px; top: 250px; width: 700px; height: 1000px; padding: 0px; overflow: hidden; text-align: justify; font-weight: normal; font-size:20px;">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr style="margin-right: 10px;">
                <td>
                    La majorité est fixée à 18 ans accomplis en République du Congo, conformément à l’article 3 du <br>
                    Code de la Nationalité.
                    Les mineurs ne peuvent contracter le mariage sauf cas d’émancipation <br>
                    (état de grossesse chez la femme). <br><br>
                    Il est interdit à toute personne atteinte d’une maladie mentale de contracter le mariage. Une <br>
                    personne se trouvant dans les liens d’un précédent mariage peut toutefois contracter un autre <br>
                    mariage (cas prévus chez l’homme seulement).
                    A condition qu’elle obtienne l’accord exprès de la <br>
                    première épouse. <br><br>
                    Les ascendants et les descendants, les frères et les sœurs ne peuvent contracter le mariage entre <br>
                    eux.<br><br>
                    Le mariage d’un ressortissant congolais à l’étranger est aussi valable qu’un mariage célébré au <br> Congo.
                    Le mari et la femme ont les même droits et les mêmes devoirs.<br>
                    Ils ont obligation réciproque de fidélité, assistance morale et matérielle dans l’intérêt de la famille <br>
                    et de l’union. Les deux conjoints doivent contribuer aux besoins de la famille pour le bien être <br>
                    du ménage. <br><br>
                    La femme mariée perd son nom de jeune fille, et prend celui de son mari une fois que le mariage <br>
                    est consommé.<br> <br>
                    Elle conserve la nationalité congolaise, sauf en cas de renonciation. La femme congolaise qui <br>
                    épouse un étranger peut acquérir la nationalité de son mari si elle le désire, après cinq ans de vie commune.<br>
                    Le régime matrimonial de la famille peut être la communauté ou la séparation des biens. <br>
                    Les enfants issus d’un mariage sont sujets à l’autorité parentale jusqu’à la majorité ou <br>
                    l’émancipation.<br>


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
                        <td style="text-align: center;">Certificat de coutume délivré à <strong>M. KAYA Fulbert</strong></td>
                        <td style="text-align: left;">
                        </td>
                        <td style="text-align: left;">
                            Fait à Brazzaville le, 07/05/2023
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"></td>
                        <td style="text-align: left;">
                            {{-- L'Officier de l'Etat-Civil --}}
                        </td>
                        <td style="text-align: left;">
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center;padding-top: 30px;">en vu du mariage avec <br> <strong>Mlle YOULOU Charlesse</strong></td>
                        <td style="text-align: left;padding-top: 60px;">
                             {{-- L'Officier de l'Etat-Civil --}}
                        </td>
                        <td style="text-align: left;padding-top: 30px;">
                            {{-- Témoin de l'épouse --}}
                        </td>
                    </tr>
            </tbody>
        </table>
       </div>
   </div>


</page>
