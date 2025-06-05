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
 {{-- <page orientation="portrait" backimg="{{ asset("tpl/back-border.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt"> --}}
 <page orientation="portrait" backimg="{{ asset("tpl/armoirie_congo.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
   @php
   /*$infos = "";
   $tribunal = $acte->declaration->institutionUser->institution->institutionParent->lib_institution;
   setlocale(LC_TIME, "fr_FR", "French");


   $num = "";
   $titre = "";
   $top = "";

   if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
       $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
   } else {
       $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
   }

   if ($acte->declaration->top_requisition == 1) {
        $top = "REQUISITION";
        $titre = $acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration));
    }elseif ($acte->declaration->top_jugement == 1){
        $top = "JUGEMENT";
        $titre = $acte->declaration->numero_jug.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration));
    }else{
        $top = "";
        $titre = "";
    }

   if($acte->declaration->personne_declaree == "Enfant abandonné"){
       $infos = 'E.A';
   }
   if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
       $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' N° '.$titre." ".$num;
   }
   if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
       $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' N° '.$titre." ".$num;
   }

   if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
       $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' DE DECLARATION TARDIVE N° '.$titre." ".$num;
   }

   if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
       $infos = 'ACTE TRANSCRIT SUIVANT '.$top.'  N° '.$titre." ".$num;
   }

   if($acte->declaration->type_declaration == "DECLARATION DE NAISSANCE" && $acte->declaration->numero_req != 0){
        $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' DE DECLARATION TARDIVE N° '.$titre." ".$num;
    }
    */

   @endphp


     <table align="center" style="border-radius: 1mm; border: none;">

        <tr style="">
            <td style="width:100%; text-align: center;">
                <img src="{{asset("tpl/logo-sifec.gif")}}" style="width:100px; margin-top: 10px;">
                <br>
                <p><strong style="text-align:center; text-transform:uppercase; font-size:25px">Reçu de caisse N°:  </strong><br>
                    <strong style=" font-size:12px; margin-top:10px;text-transform:uppercase">date d'édition: {{date("d/m/Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> <br>
                </p>

            </td><br>

        </tr>
        <hr style="margin-top:-30px; font-size:1px; color:#EEEEEE">

     </table>





</page>
