<style>
    page{
       position: relative;
       margin-top: 5px;
       margin-left: 30px;
       margin-right: 30px;
   }
   td{
       font-size: 85%;
       height: 14px;
       padding-bottom: 1px!important;
       line-height: 1.3;
   }
   b{
       font-size: 110%;
   }
   .compact {
       margin: 0;
       padding: 0;
       line-height: 1.1;
   }
   .small-text {
       font-size: 10px;
   }
</style>
{{-- <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt"> --}}

   <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt">

    {{-- <page orientation="portrait" backimg="" backcolor="#FEFEFE" backimgx="center" backimgy="100%" backimgw="100%" backtop="0"  backbottom="30mm" style="font-size: 12pt"> --}}
    @php
        $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
        $nomcomplet = "";
        if ($f != null) {
            $nomcomplet = $f->user->personne->nomcomplet();
        }
        $num = "";
        $req = "";
        $jugement = "";
        $infos = "";

        if($acte->declaration->requisition != ""){
            $titre = $acte->declaration->requisition->typeRequisition->lib_type_requisition;
            $num = $acte->declaration->requisition->num_requisition;
            $date = $acte->declaration->requisition->date_requisition;
            $infos = 'ACTE ETABLIT SUIVANT LA '.$titre.' N° '.$num.' DU '.(date("d-m-Y", strtotime($date)))." AU ".$acte->declaration->institution->institutionParent->lib_institution;

        }
    @endphp
    @php
        $localite = "";
        $localiteParent = "";
        $inst = "";
        $institution = $acte->institutionUser->institution;
        $localisation = "";

        // localite concernant les mairies
        $dept = "DEPARTEMENT DE ".$acte->institution->lieu->localiteParent->localiteParent->lib_localite;
        $commune = "COMMUNE DE ".$acte->institution->lieu->localiteParent->lib_localite;
    @endphp

<p style="color: red;text-align:center;font-style:italic"><small style="text-transform: uppercase">{{ $infos }}</small></p>

   <table cellspacing="0" style="width: 100%; font-size: 13pt;margin-top: 3px;">
       <tr>
           <td style="width:35%; text-align: center;padding-top: -120px;">
            <p>
                <span>
                    <strong>{{ $dept }}</strong>
                </span> <br>
                <span>{{ $commune}}</span> <br>
                <span>{{ $institution->lib_institution }}</span>
            </p>
           </td>
           <td style="width:30%; text-align: center;">
            {{-- Logo du milieu --}}
                @if ($acte->approbation_tribunal == 1)
                    <img src='{{ public_path('app/'.$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                @endif
           </td>

           <td style="width:35%; text-align: center;">
               <strong style="margin-top:10px">REPUBLIQUE DU CONGO</strong><br>
               Unit&eacute; - Travail - Progr&egrave;s <br><br>
               @if ($acte->approbation_mairie != null)
               <qrcode value="{{ env('QRCODE_URL') }}/qrcode?niupp=Je suis Vincent" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode>
                @else
                <br><br><br><br><br><br>
               @endif
            </td>
       </tr>
   </table><br>
   <table align="center" style="border-radius: 1mm; border: none; width: 100%;">
       <tr style="">
           <td style="width:70%; text-align: center; padding-top: 5px;">
               <p><strong style="font-size: 140%;">ACTE DE MARIAGE</strong>
                <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}} </strong> Acte n°:<strong>{{ $acte->code_acte_mariage }}</strong>
            </p>
           </td>
       </tr>
   </table>
   <div style="margin-top: 10px;margin-left: 4px;border-radius: 2mm;">
       <div style="width: 150px;text-align: center;">
           {{-- <p>Marge réservée aux mention <br> d'officier(1)</p> --}}
       </div>
       <div style="position: relative; left: 0px; top: 0px; width: 100%; padding: 0px; text-align: left; font-weight: normal; font-size:20px;" class="main-content">
           <table align="left" style="border-radius: 1mm; border: none;">
            <tr style="margin-right: 8px;">
                <td class="compact">
                    Centre d'état civil principal : {{ $institution->lib_institution }} <br>
                    <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong> <br>
                    Par devant nous {{ $nomcomplet }} Officier de l'Etat Civil ont comparu publiquement : <br>
                    <span style="margin-left: 30px;"><strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong></span> <br>
                    Né le <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->epoux->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->epoux->date_naissance)))}}</strong>, à <strong>{{ $acte->declaration->epoux->lieu_naissance }}</strong> <br>
                    Acte de naissance n° <strong>{{ $acte->declaration->numero_acte_naissance_epoux }}</strong> du <strong>{{ date("d", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_emission_acte_naissance_epoux))) ." ".date("Y", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) }}</strong> dressé à la <strong> {{ $acte->declaration->cec_naissance_epoux }}</strong> <br>
                    Nationalité : <strong>{{ $acte->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpoux->lib_profession }}</strong> <br>
                    Domicilié : <strong>{{ $acte->declaration->epoux->adresse }}</strong><br> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpoux->lib_situation_matrimoniale }}</strong> <br>
                    Fils de : {{ $acte->declaration->pere_epoux }} <br>
                    Et de : {{ $acte->declaration->mere_epoux }} <br>
                    <span style="margin-left: 30px;">Et <strong>Mme. {{ $acte->declaration->epouse->nomcomplet() }}</strong></span> <br>
                    Née le <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->epouse->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->epouse->date_naissance)))}}</strong> , à <strong>{{ $acte->declaration->epouse->lieu_naissance }}</strong> <br>
                    Acte de naissance n° <strong>{{  $acte->declaration->numero_acte_naissance_epouse  }}</strong> du <strong>{{ date("d", strtotime($acte->declaration->date_emission_acte_naissance_epouse)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_emission_acte_naissance_epouse))) ." ".date("Y", strtotime($acte->declaration->date_emission_acte_naissance_epouse)) }}</strong> dressé à la <strong> {{ $acte->declaration->cec_naissance_epouse }}</strong> <br>
                    Nationalité : <strong>{{ $acte->declaration->epouse->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpouse->lib_profession }}</strong> <br>
                    Domiciliée : <strong>{{ $acte->declaration->epouse->adresse }}</strong><br> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpouse->lib_situation_matrimoniale }}</strong> <br>
                    Fille de : {{ $acte->declaration->pere_epouse }} <br>
                    Et de : {{ $acte->declaration->mere_epouse }} <br>
                    Sur notre interpellation, les futurs époux ont déclaré l'un après l'autre vouloir se prendre pour mari et femme et nous avons prononcé au nom de la loi
                    qu'ils sont unis par le mariage légal en présence de : <br>
                    @if ($acte->declaration->nom_prenom_mandant_epouse != "")
                        <strong style="color:red;margin-top:2px">{{$acte->declaration->nom_prenom_mandant_epouse}}, représentante de l'épouse</strong>
                    @endif
                    @if ($acte->declaration->nom_prenom_mandant_epoux != "")
                        <strong style="color:red;margin-top:2px">{{$acte->declaration->nom_prenom_mandant_epoux}}, représentant de l'époux</strong>
                    @endif
                    <br>
                    <strong><i>Témoins de l'époux</i> </strong><br>
                    Et ce :<br>
                    1° {{ $acte->declaration->temoinHommeEpoux->nomcomplet() }}  Née le {{ date("d", strtotime($acte->declaration->temoinHommeEpoux->date_naissance)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->temoinHommeEpoux->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinHommeEpoux->date_naissance)) ." à ".$acte->declaration->temoinHommeEpoux->lieu_naissance}}, Domicilié au {{ $acte->declaration->temoinHommeEpoux->adresse }}* <br>
                    2° Mme {{ $acte->declaration->temoinFemmeEpoux->nomcomplet() }}  Née le {{ date("d", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinFemmeEpoux->date_naissance)) ." à ".$acte->declaration->temoinFemmeEpoux->lieu_naissance}}* <br>
                    <strong><i>Témoins de l'épouse</i></strong> <br>
                    1° {{ $acte->declaration->temoinHommeEpouse->nomcomplet() }}  Née le {{ date("d", strtotime($acte->declaration->temoinHommeEpouse->date_naissance)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->temoinHommeEpouse->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinHommeEpouse->date_naissance)) ." à ".$acte->declaration->temoinHommeEpouse->lieu_naissance}}, Domicilié au {{ $acte->declaration->temoinHommeEpouse->adresse }}* <br>
                    2° Mme {{ $acte->declaration->temoinFemmeEpouse->nomcomplet() }}  Née le {{ date("d", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance))) ." ".date("Y", strtotime($acte->declaration->temoinFemmeEpouse->date_naissance)) ." à ".$acte->declaration->temoinFemmeEpouse->lieu_naissance}}* <br>

                    qui, lecture faite nous avons signé le présent acte avec les époux et  les témoins
                </td>
            </tr>
           </table>
           <table class="historique" cellspacing="0" style="width: 100%; font-size: 18px;">
                <col style="width: 33%">
                <col style="width: 33%">
                <col style="width: 33%">
                <thead>
                    <tr style="text-align: center;">
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;"></td>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td style="text-align: center;">L'époux <br>
                                @if($acte->declaration->signatureActe !=null)
                                <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_epoux}}" alt="Base64 Image" width="100" height="80">
                                @endif

                                @if ($acte->declaration->nom_prenom_mandant_epoux != "")
                                    <span style="color:black;margin-top:-25px;font-size:20px;color:black">P.O</span>
                                @endif
                            </td>
                            <td></td>
                            <td style="text-align: left;">
                                L'épouse <br>
                                @if($acte->declaration->signatureActe !=null)
                                <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_epouse}}" alt="Base64 Image" width="100" height="80">
                                @endif
                                @if ($acte->declaration->nom_prenom_mandant_epouse != "")
                                    <span style="color:black;margin-top:-25px;font-size:20px">P.O</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"></td>
                            <td style="text-align: left;">
                            </td>
                            <td style="text-align: left;">
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">
                                Témoins de l'époux <br>

                                @if($acte->declaration->signatureActe != null)
                                    <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_premier_epoux}}" alt="Base64 Image" width="100" height="100">
                               @endif
                               @if($acte->declaration->signatureActe !=null)
                               <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_deuxieme_epoux}}" alt="Base64 Image" width="100" height="100">

                               @endif
                            </td>

                            <td>
                            </td>
                            <td style="text-align: left;">Témoins de l'épouse <br>
                                @if($acte->declaration->signatureActe !=null)
                                <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_premier_epouse}}" alt="Base64 Image" width="100" height="80">

                                @endif
                                @if($acte->declaration->signatureActe !=null)
                                <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_deuxieme_epouse}}" alt="Base64 Image" width="100" height="80">
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                            </td>
                            <td style="text-align: center;padding-top: 5px;">L'officier d'état civil <br>

                                @if ($acte->approbation_mairie != null)
                                    <img src='{{ public_path('app/'.$acte->signature_maire) }}' width="100" height="100">
                                    {{ $acte->signataire->user->personne->nomcomplet() }}
                                @endif
                            </td>
                            <td>
                            </td>
                        </tr>
                </tbody>
            </table><br>
            <div style="font-size: 11px;text-align: center;" class="small-text">
            <i><strong>CONDITIONS DE MARIAGE</strong></i> <br> Les futurs époux déclarent expressément opter pour la <strong>{{ $acte->declaration->optionMariage->lib_option_mariage }}</strong> et se marie sous le régime matrimonial de <strong>{{ $acte->declaration->regime->lib_regime }}</strong>.
            <br>La dot: Cinquante Mille Francs (50.000 Frs) CFA versés à M. <strong>{{ $acte->declaration->chef_famille }}</strong> , {{ $acte->declaration->filiation->lib_filiation }} de la mariée *
            <br>Coutume présidant à l'union: Congolaise*
            <br>Stipulations particulières en date du <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> l'époux déclare expressément le <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> renonce à prendre une
            seconde épouse tant que le présent mariage n'aura pas été dissout par un jugement de divorce ou le décès de sa conjointe
            (Article 179 du code de la famille)
            </div>
       </div>
   </div>


</page>
