<style>
    page{
       position: relative;
   }
   td{
       font-size: 14px;
       height: 15px;
   }
   b{
       font-size: 14px;
   }

</style>

 <page orientation="portrait" backimg="{{ asset("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">

     <table align="center" style="border-radius: 1mm; border: none;">

        <tr style="">
            <td style="width:100%; text-align: center;margin-top:100px">
                <img src="{{asset("tpl/logo-sifec.gif")}}" style="width:150px; margin-top: 0px;">
                <br>
                <p><strong style="text-align:center; text-transform:uppercase; font-size:23px;margin-top:10px"> RECETTES DES DEMANDES EN LIGNE  </strong><br>
                    <strong style=" font-size:10px; margin-top:10px;text-transform:uppercase">date d'édition:  <i>{{date("d/m/Y", strtotime(now()))}} </i></strong> <br>
                </p>

            </td><br>

        </tr>
        <hr style="margin-top:-30px; font-size:1px; color:#EEEEEE">

     </table>


     <table align="center" style="border-radius: 1mm; border: 1px solid silver; margin-top:20px; width:100%">
        <tr style="width:100%;">
            <td style="width:65%; text-align: left; border:1px solid silver"">
            <strong>ADMINISTRATION</strong>

            </td>

            <td style="width:35%; text-align: right;border:1px solid silver"">
                <strong> MONTANT (FCFA)</strong>
            </td>

        </tr>
        @php
            $total = 0;
        @endphp
        @foreach ($etat as $data)
        @php
            $total += $data->montantApayer;
        @endphp
        <tr style="width:100%;">
            <td style="width:65%; text-align: left;border:1px solid silver"">
               {{$data->cec_associe}}

            </td>

            <td style="width:35%; text-align: right;border:1px solid silver"">
                {{number_format($data->montantApayer, 2, ',', '.')}}
            </td>

        </tr>
        @endforeach

        <tr style="width:100%;">
            <td style="width:65%; text-align: left;border:1px solid silver"">
                <strong>TOTAL</strong>

            </td>

            <td style="width:35%; text-align: right;border:1px solid silver"">
                <strong>{{ number_format($total, 2, ',', '.')}} </strong>
            </td>

        </tr>

      </table>



</page>
