<style>
    page{
       position: relative;
   }
   td{
       font-size: 14px;
       height: 15px;
   }
   b{
       font-size: 14px%;
   }

</style>
 <page orientation="portrait" {{ isset($armoirie_path) && $armoirie_path !== '' ? 'backimg="'.$armoirie_path.'"' : '' }} backcolor="#FEFEFE" backimgx="center" backimgy="70%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
   @php
   $infos = "";
   $tribunal = $acte->declaration->institutionUser->institution->institutionParent->lib_institution;
   setlocale(LC_TIME, "fr_FR", "French");


   $num = "";
   $titre = "";
   $top = "";


   @endphp

   <table cellspacing="0" style="width: 100%; font-size: 14px;">
       <tr>
           <td style="width:37%; text-align: center;padding-left: 14px;">
               @php
                   $localite = "";
                   $localiteParent = "";
                   $inst = "";
                   $institution = $acte->institutionUser->institution;
                   $localisation = "";

                    $inst = $institution->lib_institution;
                    $localite = " COMMUNE DE ".$institution->lieu->localiteParent->lib_localite;
                    $localiteParent  = "DEPARTEMENT DE ". $institution->lieu->localiteParent->localiteParent->lib_localite;
                    $localisation = $institution->lieu->localiteParent->lib_localite;

                    $dateJour = date("Y-m-d");
               @endphp
               <p>
                   <span>
                       <strong>{{ $localiteParent }}</strong>
                   </span> <br>
                   <span>{{ $localite}}</span> <br>
                   <span>{{ $inst }}</span>
               </p>
           </td>
           <td style="width:30%; text-align: center;">
           </td>
           <td style="width:33%; text-align: center;">
               <strong>REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; * Travail * Progr&egrave;s
           </td>
       </tr>
   </table><br><br>
   <table align="center" style="border-radius: 1mm; border: none;margin-top:20px">
       <tr style="">
           <td style="width:100%; text-align: center;">
               <h3><strong style="">FICHE DE RECTIFICATION D'ACTE DE NAISSANCE</strong></h3>
                <p style="margin-top:-5px">
                    <span style="font-style: italic">(Conformément aux dipositions du code de la famille, article 83/84)</span> <br>
                    <strong style="margin-top:15px">N°:{{ $rectification->numero_rectification }}   DU {{date("d-m-Y", strtotime($dateJour))}}</strong>


                </p>
           </td>



       </tr>
   </table>

   <table align="center" style="border-radius: 1mm; border: none;margin-top:-5px">
       <tr style="">
           <td><p style="color:red; font-size:20px"> <strong> Acte de naissance n°: {{$acte->niupp}} </strong> </p></td>

       </tr>
   </table>



            <table align="justify" style="margin-left:30px;margin-top:10px">
                <tr style="width:100%; text-align: justify; padding-bottom: 4px;">
                    <td style="">
                        <p style="line-height: 25px; text-align: justify;padding-right:30px">Ci-dessous, la liste des rectifications à apporter à l'acte de naissance ci-dessus référencé, suite à la reclamation faite par M/Mme {{ $rectification->nom_prenom_requerant }},
                        filiation: {{ $rectification->filiation->lib_filiation }} de l'enfant, en date du {{ date("d-m-Y", strtotime($rectification->created_at)) }}, adresse: {{ $rectification->adresse_requerant  }}, Téléphone:
                        {{ $rectification->telephone_requerant }}.</p>

                    </td>
                </tr>

            </table>

            <table align="justify" style="margin-left:30px;margin-top:25px">
            @php
                //récupération de la liste des détails des réctifications
               // $details = $rectification->details;
                $i=1;
            @endphp
            @foreach ($detailsRectification as $item)
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td><strong style="color: red">{{ $i }}) {{ $item->rubrique->lib_rubrique }}</strong> <br><br>
                        Lire <span style="color:red"> {{ $item->nouvelle_valeur }}</span> au lieu de  {{ $item->ancienne_valeur }}
                        <br><br>
                    </td>
            </tr>
            @php
                $i++;
            @endphp
            @endforeach
            </table>

            <p style="text-align: right; margin-right:50px"> Fait à Brazzaville, le <strong>{{ date('d-m-Y') }}</strong></p>
               <p style="text-align: right; margin-top:0px;margin-right:125px">L’officier de l’état civil</p>

               <p style="text-align: left; position:absolute; bottom:19px; font-style:italic; font-size:12px"><span style="color:red">(*)</span> Ce document requiert une réquisition aux fins de rectification d'acte</p>


</page>
