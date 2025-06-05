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
 <page orientation="portrait" backimg="{{ asset("tpl/back-border.png") }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
   @php
   $infos = "";
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
   if($acte->declaration->personne_declaree == "Enfant trouvé"){
       $infos = 'E.T';
   }
   if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
       $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' N° '.$titre." ".$num;
   }
   if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
       $infos = 'ACTE RECONSTITUE SUIVANT '.$top.' N° '.$titre." ".$num;
   }

   if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION" && $acte->declaration->jugement == null){
       $infos = 'ACTE ETABLIT SUIVANT LA REQUISITION AUX FINS DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.' DU '.(date("d-m-Y", strtotime($acte->declaration->requisition->updated_at)))." AU ".$acte->declaration->requisition->institutionUser->institution->lib_institution;
   }

   if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
       $infos = 'ACTE TRANSCRIT SUIVANT '.$top.'  N° '.$titre." ".$num;
   }

   if($acte->declaration->jugement != null){


        if($acte->declaration->jugement->type_jugement == "JUGEMENT D'AUTORISATION"){
            $infos = 'ACTE ETABLIT SUIVANT LE '.$acte->declaration->jugement->type_jugement.' N° '.$acte->declaration->jugement->num_jugement.'  DU '.(date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)))." AU ".$acte->declaration->jugement->institutionUser->institution->lib_institution;
        }

        if($acte->declaration->jugement->type_jugement == "JUGEMENT SUPPLETIF"){
            $infos = 'ACTE ETABLIT SUIVANT LE JUGEMENT N° '.$acte->declaration->jugement->num_jugement.'  DU '.(date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)))." AU ".$acte->declaration->jugement->institutionUser->institution->lib_institution;
        }

        if($acte->declaration->jugement->type_jugement == "JUGEMENT D'HOMOLOGATION"){
            $infos = 'ACTE ETABLIT SUIVANT LE JUGEMENT N° '.$acte->declaration->jugement->num_jugement.'  DU '.(date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)))." AU ".$acte->declaration->jugement->institutionUser->institution->lib_institution;
        }

        if($acte->declaration->type_declaration == "DECLARATION DE NAISSANCE" && $acte->declaration->numero_req != 0){
            $infos = 'ACTE ETABLIT SUIVANT '.$top.' DE DECLARATION TARDIVE N° '.$titre." ".$num;
        }

        if ($acte->deleted_at != NULL && $acte->deleted_at != "") {
            $infos = 'ACTE ANNULE PAR JUGEMENT N° '.$acte->declaration->jugement->num_jugement.'  DU '.(date("d-m-Y", strtotime($acte->declaration->jugement->date_jugement)))." \n AU ".$acte->declaration->jugement->institutionUser->institution->lib_institution;
        }

    }

   @endphp

    <p style="color: red;text-align:center;font-style:italic"><small>{{ $infos != "" ? $infos : "" }}</small></p>


   <table cellspacing="0" style="width: 100%; font-size: 12px;">
       <tr>
           <td style="width:40%; text-align: center;padding-left: 12px;">
               @php
                   $localite = "";
                   $localiteParent = "";
                   $inst = "";
                   $institution = $acte->institutionUser->institution;
                   $localisation = "";

                    $inst = $institution->lib_institution;
                    // $localite = " COMMUNE DE ".$institution->lieu->localiteParent->lib_localite;
                    $localite = " DISTRICT D'".$institution->lieu->lib_localite;

                    // $localiteParent  = "DEPARTEMENT DE LA CUVETTE";
                    $localisation = $institution->lieu->localiteParent->lib_localite;
               @endphp
               @if(Auth::user() != null && Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution != "TPINS_0005")


               @php
                    $localiteParent  = "DEPARTEMENT DE LA ". $institution->lieu->localiteParent->localiteParent->lib_localite;
                @endphp
               <p>
                   <span>
                    {{ $localiteParent }}
                     <br>
                       {{ $localite }}
                   </span> <br>
                   <span><strong>{{ $inst }}</strong></span>
               </p>
               @else
               <p>
                    <span>
                        <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
                    </span> <br>
                    <span>Service Consulaire</span> <br>
                </p>
                @endif
           </td>

           {{-- <td style="width:34%; text-align: center;"> --}}
           <td style="width:34%; text-align: center;">
               @if ($acte->approbation_tribunal == 1)
                   <img src='{{ asset("app/".$acte->sceau_tribunal) }}' alt="sceau" width="100" height="100">
               @endif

           </td>
           <td style="width:25%; text-align: center;margin-top:-10px">
               <strong>REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; - Travail - Progr&egrave;s
           </td>
       </tr>
   </table><br><br>
   <table align="center" style="border-radius: 1mm; border: none;">
       <tr style="">
           <td style="width:100%; text-align: center;">
               <p><strong style="font-size: 18px;">ACTE DE NAISSANCE</strong><br>
                {{-- Année: <strong>{{date("Y", strtotime($acte->declaration->date_heure_declaration))}}</strong> Registre: <strong>  {{ $acte->registre->getcode() }}   N°: <span style="color: red">{{ $acte->niupp }}</span> </strong> --}}
                 <strong>N°: <span style="color: red">{{ $acte->niupp }}</span> </strong>
                {{-- Année: <strong>{{date("Y", strtotime($acte->declaration->date_heure_declaration))}}</strong>  &nbsp;&nbsp;&nbsp; Acte n°: <strong> {{ $acte->numeroActe->numero_acte }} </strong> --}}
                {{-- Acte n°:<strong>{{ $acte->niupp }}</strong> --}}
                </p>
           </td>
           <td style="width:15%; text-align: center;">
           </td>
       </tr><br>
   </table>
   <div style="margin-top: 100px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">
       <div style="width: 150px;text-align: center;">
           {{-- <p>Marge réservée aux mention <br> d'officier(1)</p> --}}

       </div>
       <div style="position: absolute; left: 20px; top: 270px; width: 700px; height: 530px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
           <table align="left" style="margin-left: 2%;border-radius: 1mm; border: none;">
               <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                   <td>Centre d'état civil principal de: <strong>{{ $acte->institutionUser->institution->lib_institution}}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>S'est présenté {{ $acte->declaration->declarant->sexe=="M" ? "M. :" : "Mme :"  }} <strong>{{ $acte->declaration->declarant->nom. " ".$acte->declaration->declarant->prenom }}</strong></td>
                    {{-- <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minute(s)</strong></td> --}}
               </tr>
               <tr style="width:100%; text-align: left;">
                    <td>Filiation: <strong>{{ $acte->declaration->filiation ?  $acte->declaration->filiation->lib_filiation : $dummy }}</strong></td>
                </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Pour la naissance d'un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong> à</td>
               </tr>
               <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }}<strong> :
                    @if($acte->declaration->type_declarant == "Personne morale")
                        <span style="font-size: 13px;font-weight:bold;text-transform: capitalize;color: red">{{ $acte->declaration->enfant->prenom }}</span>
                    @else
                    <span style="color: red">{{ $acte->declaration->enfant->nom }} </span><span style="font-size: 13px;font-weight:bold;text-transform: capitalize;color: red">{{ $acte->declaration->enfant->prenom }}</span>
                    @endif
                </strong></td>
               </tr>
               {{-- <tr style="width:100%; text-align: left;">
                   <td>Déclaré par: <strong>{{ $acte->declaration->declarant->nom. " ".$acte->declaration->declarant->prenom }}</strong></td>
               </tr> --}}


               <tr style="width:100%; text-align: left;">
                   <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong>

                    @if($acte->declaration->pere != null)
                       <span>{{ $acte->declaration->pere->nom }}</span> <span style="font-size: 13px;font-weight:bold;text-transform: capitalize">{{ $acte->declaration->pere->prenom }}</span>
                    @else
                    <span>{{ $dummy }} </span>
                    @endif
                </strong></td>
               </tr>

               <tr style="width:100%; text-align: left;">
                   <td>Né le : <strong>
                       @if ($acte->declaration->personne_declaree == "Enfant trouvé" || $acte->declaration->personne_declaree == "Enfant abandonné")
                        {{ $dummy }}
                        @else
                           {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) .' '.Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                       @endif
                   </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>A : <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->lieu_naissance : $dummy }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Nationalité: <strong>
                    @if($acte->declaration->personne_declaree == "Enfant trouvé" || $acte->declaration->personne_declaree == "Enfant abandonné")
                    {{ $dummy }}
                    @else
                    {{ $acte->declaration->pere->nationalite->lib_nationalite }}
                    @endif
                   </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Niveau d'instruction: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Domicilié au : <strong>
                    @if($acte->declaration->personne_declaree == "Enfant trouvé" || $acte->declaration->personne_declaree == "Enfant abandonné")
                    {{ $dummy }}
                    @else
                    {{ $acte->declaration->pere->adresse }}
                    @endif
                </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Et de :<strong>
                    @if($acte->declaration->mere != null)
                    <span>{{ $acte->declaration->mere->nom }}</span> <span style="font-size: 13px;font-weight:bold;text-transform: capitalize">{{ $acte->declaration->mere->prenom }}</span>
                    @else
                    <span>{{ $dummy }} </span>
                    @endif
                    </strong>
                   </td>
               </tr>

               <tr style="width:100%; text-align: left;">
                   <td>Née le : <strong>
                    @if($acte->declaration->personne_declaree == "Enfant trouvé")
                        {{ $dummy }}
                    @else
                        {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                    @endif
                   </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>A : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy}}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                <td>Nationalité: <strong>
                    @if($acte->declaration->personne_declaree == "Enfant trouvé")
                    {{ $dummy }}
                    @else
                    {{ $acte->declaration->mere->nationalite->lib_nationalite }}
                    @endif
                   </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                <td>Domicilié au : <strong>
                    @if($acte->declaration->personne_declaree == "Enfant trouvé")
                    {{ $dummy }}
                    @else
                    {{ $acte->declaration->mere->adresse }}
                    @endif
                </strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                   <td>Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong></td>
               </tr>
               <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale des parents: <strong>{{ $acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong></td>
                </tr>
               @if($acte->declaration->type_declarant == "Personne physique")
               <tr style="width:100%; text-align: left;">
                   <td>Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant  }}</strong></td>
               </tr>
               @endif
           </table>
       </div>
   </div>

   <div style="position:absolute; bottom:0;margin-left:10px;">
       <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
           <col style="width: 30%">
           <col style="width: 25%">
           <col style="width: 45%">
           <thead>
             <tr style="text-align: center">
               <td style="text-align: center;"></td>
               <td style="text-align: center;"></td>
               <td style="text-align: center;"></td>
             </tr>
           </thead>
           <tbody>
               <tr>
                   <td style="text-align: center;">Le déclarant</td>
                   <td style="text-align: left;">
                       <div style="margin-bottom:0;"><qrcode value="{{ env('QRCODE_URL') }}/qrcode?niupp={{ $acte->niupp }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div>
                   </td>
                   <td style="text-align: left;">
                    <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower(trans($localisation)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>
                        {{-- @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170") --}}
                            L'officier de l'état civil
                        {{-- @else
                        <br>Consule
                        @endif --}}
                    </p>
                        @if ($acte->approbation_mairie != "")
                            <img src='{{ asset("app/".$acte->signature_mairie)}}'>
                            <br>
                            <span style="color:black; font-weight:bold"> {{ $acte->signataire->user->personne->nomcomplet() }}</span>
                        @endif
                    </td>
                 </tr>
           </tbody>
       </table>
   </div>
</page>
