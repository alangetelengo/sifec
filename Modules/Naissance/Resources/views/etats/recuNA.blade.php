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
{{-- <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt"> --}}
 <page orientation="portrait" backimg="{{ asset("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">

     <table align="center" style="border-radius: 1mm; border: none;">

        <tr style="">
            <td style="width:100%; text-align: center;margin-top:50px">
                <img src="{{asset("tpl/logo-sifec.gif")}}" style="width:100px; margin-top: 0px;">
                <br>
                <p><strong style="text-align:center; text-transform:uppercase; font-size:14px">RAPPORT D'AUTHENTIFICATION </strong><br>
                    <strong style=" font-size:10px; margin-top:10px;text-transform:uppercase">date d'édition:  <i>{{date("d/m/Y", strtotime(now()))}} </i></strong> <br>
                </p>

            </td><br>

        </tr>
        <hr style="margin-top:-30px; font-size:1px; color:#EEEEEE">

        <tr style="margin-top: 100px">
            <td style="width:100%; text-align: center;margin-top: 100px; border-bottom:1px dashed #EEEEEE;padding:10px">
                <p><strong style="text-align:center; font-size:10px">Type de document : <i>{{$typeDocument}}</i></strong></p>
            </td>

        </tr>


        <tr style="margin-top: 0px">
            <td style="width:100%; text-align: center; border-bottom:1px dashed #EEEEEE;padding-bottom:10px">
                @php

             @endphp
                 <p><strong style="text-align:center; font-size:10px">Numéro d'acte : <i>{{ $numeroActeNA }}</i></strong></p>
            </td>

        </tr>

        <tr style="margin-top: 0px">
            <td style="width:100%; text-align: center; border-bottom:1px dashed #EEEEEE;margin-top:20px">
                <p>
                    <strong style="text-align:center; font-size:10px">Statut : </strong><br/>
                    <img src="{{asset("tpl/certificat1.png")}}" style="width:70px; height:70px; margin-top: 10px;"><br/>
                     <strong style="text-align:center; font-size:12px;color:red">{{ $statut }} </strong><br/>
                 </p>
            </td>

        </tr>



     </table>






</page>
