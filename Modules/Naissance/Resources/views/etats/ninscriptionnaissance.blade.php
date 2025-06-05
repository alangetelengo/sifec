<style>
    td{
        font-size: 14px;
    }
    b{
        font-size: 14px;
    }
</style>
<page orientation="portrait" backimg="{{asset('tpl/armoirie_congo.png')}}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">

    <table cellspacing="0" style="width: 100%; font-size: 14px;">
        <tr>
            <td style="width:40%; text-align: center;">
                <p><span><strong>{{$certificat->institutionUser->institution->localite->lib_localite}}</strong></span> <br>
                    <span>{{ $certificat->institutionUser->institution->localite->typeLocalite->lib_type_localite.' DE '.$certificat->institutionUser->institution->localite->localiteParent->lib_localite}}</span> <br>
                    <span>{{ Auth::user()->affectationActive()->institution->lib_institution }}</span>
                </p>
            </td>
            <td style="width:35%; text-align: center;">

            </td>
            <td style="width:25%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                <p><strong style="font-size: 18px;"> CERTIFICAT DE NON INSCRIPTION DE L'ACTE DE NAISSANCE</strong><br> Année: <strong>{{date("Y")}}</strong>  N°: <strong>{{$certificat->code_declaration_naissance}}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>
        </tr><br>
    </table>
    <div style="margin-top: 3%;margin-left: 2%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; left: 20px; top: 220px; width: 700px; height: 700px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
            <P style="text-align: justify;">Je soussigné, du {{Auth::user()->affectationActive()->institution->localite->typeLocalite->lib_type_localite}} de {{Auth::user()->affectationActive()->institution->localite->lib_localite}}, officier d'Etat Civil informons que la déclaration de naissance de l'enfant:
            </P>
            <table align="left" style="border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 18px;">Par le centre d'Etat Civil de: <strong>{{Auth::user()->affectationActive()->institution->lib_institution}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 18px;">Au profit de : <strong>{{$certificat->enfant->nom}} {{$certificat->enfant->prenom}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Né(e), le: <strong>{{date("d/M/Y", strtotime( $certificat->enfant->date_naissance))}}</strong>, à <strong>{{$certificat->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Sexe : <strong>{{ $certificat->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">{{ $certificat->enfant->sexe=="M" ? "Fils" : "Fille" }} de:<strong>{{$certificat->pere->nom}} {{$certificat->pere->prenom}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Né le : <strong>{{date("d/M/Y", strtotime( $certificat->pere->date_naissance))}}</strong>, à : <strong>{{$certificat->pere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Profession: <strong>{{$certificat->pere->profession->lib_profession}}</strong>, Nationalité: <strong>{{ $certificat->pere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Domicilié à :<strong>{{ Sifec::adressepersonne($certificat->pere->code_personne) }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Et de :<strong>{{$certificat->mere->nom}} {{$certificat->mere->prenom}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Né le : <strong>{{date("d/M/Y", strtotime( $certificat->mere->date_naissance))}}</strong>, à <strong>{{$certificat->mere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Profession: <strong>{{$certificat->mere->code_profession != NULL ? $certificat->mere->profession->lib_profession :""}}</strong>, Nationalité: <strong>{{ $certificat->mere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Domicilié à : <strong>{{ Sifec::adressepersonne($certificat->mere->code_personne) }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Ne nous a été présenté que ce jour : <strong>{{date("d/M/Y")}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 18px;">Et pour ce faire <strong></strong></td>
                </tr>
            </table>
        </div>
    </div>

    <div style="margin-top: 410px;">
        <p style="text-align: justify;font-size: 14px;margin-left: 5%;margin-right: 6%;">Certifions que l'acte de naissance dudit enfant n'a pas été dressé. <br>
            En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit. /-
        </p>
    </div>
    <div style="margin-top: 60px;">
        {{-- <div style="width: 80%;margin-left: 50%;">Le déclarant,</div> --}}
        <div style="margin-left: 60%;"><p>Fait à Ignié, le {{date("d/m/Y")}}<br>
             L'Officier de l'Etat Civil</p>
             {{-- <img src="{{asset("tpl/signature.png") }}" width="10%" height="20%" alt=""> --}}
        </div>
    </div>

</page>
