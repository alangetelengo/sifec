<style>
    td{
        font-size: 14px;
        padding: 3px;
    }
    b,p{
        font-size: 14px;
    }
</style>
<page orientation="portrait" backimg="{{ str_replace('\\', '/', public_path('tpl/armoirie_congo.png')) }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institution = $certificat->institutionUser->institution;
        $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
        setlocale(LC_TIME, "fr_FR", "French");

        $localite = $localisationData['localite'];
        $localiteParent = $localisationData['localiteParent'];
        $inst = $localisationData['inst'];
        $localisation = $localisationData['localisation'];
    @endphp
    <table cellspacing="0" style="width: 100%;">
        <tr>
            <td style="width:50%; text-align: center;">
                <p>
                    <span>
                        <strong>{{ $localiteParent }}</strong>
                    </span> <br>
                    <span>{{ $localite}}</span> <br>
                    <span>{{ $inst }}</span>
                </p>

            </td>
            <td style="width:15%; text-align: center;">

            </td>
            <td style="width:35%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;border:solid;">
                <p><strong style="font-size: 18px;"> CERTIFICAT DE DESTRUCTION DE L'ACTE DE NAISSANCE</strong><br> Année: <strong>{{date("Y")}}</strong>  N°: <strong>{{$certificat->numero_certificat}}</strong></p>
            </td>
        </tr><br>
    </table>
    <div style="margin-top: 3%;margin-left: 2%;border-radius: 2mm;">
        <div style="position: absolute;right:30px; left: 30px; top: 220px; width: 700px; height: 500px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
            <P style="text-align: justify;padding: 3px;margin-right: 40px;">Je soussigné, l'Officier d'Etat Civil de : <strong>{{ $inst}}</strong>,
                certifie sur présentation du témoignage de <strong>{{$certificat->declarant->nom}} {{$certificat->declarant->prenom}} ({{$certificat->filiation->lib_filiation}})</strong>, <br>
                Que l'acte de naissance établi par le centre d'Etat civil de : <strong>{{ $certificat->institutionUser->institution->lib_institution}}</strong>
            </P>
            <table align="left" style="border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td>Au profit de : <strong>{{$certificat->enfant->nom}} <span style="text-transform: capitalize">{{$certificat->enfant->prenom}}</span></strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né(e), le: <strong>{{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->enfant->date_naissance)))}}</strong>, à <strong>{{$certificat->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Sexe : <strong>{{ $certificat->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $certificat->enfant->sexe=="M" ? "Fils" : "Fille" }} de:<strong>{{$certificat->pere->nom}} <span style="text-transform: capitalize">{{$certificat->pere->prenom}}</span></strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Né le : <strong>{{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->pere->date_naissance)))}}</strong>, à <strong>{{$certificat->pere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Profession: <strong>{{ $certificat->pere->code_profession != NULL ? $certificat->pere->profession->lib_profession :"" }}</strong>, Nationalité: <strong>{{ $certificat->pere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au: <strong>{{ $certificat->pere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong>{{$certificat->mere->nom}} <span style="text-transform: capitalize">{{$certificat->mere->prenom}}</span></strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    {{-- <td>Né le : <strong>{{strftime("%d %B %Y", strtotime( $certificat->mere->date_naissance))}}</strong>, à <strong>{{$certificat->mere->lieu_naissance}}</strong></td> --}}
                    <td>Né le : <strong>{{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->mere->date_naissance)))}}</strong>, à <strong>{{$certificat->mere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Profession: <strong>{{$certificat->mere->code_profession != NULL ? $certificat->mere->profession->lib_profession :""}}</strong>, Nationalité: <strong>{{ $certificat->mere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au: <strong>{{ $certificat->mere->adresse }}</strong></td>
                </tr>
            </table>
            <p>A été détruit du fait des conflits sociopolitiques ou de vétusté. <br><br>
                En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit.</p>
        </div>
    </div>

    <div style="margin-top: 500px; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
            <col style="width: 25%">
            <col style="width: 25%">
            <col style="width: 50%">
            <thead>
              <tr style="text-align: center">
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
                <td style="text-align: center;"></td>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: left;">
                        @isset($qrCode)
                        <div style="margin-bottom:0; width: 30mm;">
                            <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                        </div>
                        @endisset
                    </td>
                    <td style="text-align: left;">
                        <p>Fait à {{$localisation}}, le {{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration)))}}<br>L'Officier de l'Etat Civil</p>
                    </td>
                  </tr>
            </tbody>
        </table>
        <br><br><br>
        <p style="text-align: left; font-style:italic; font-size:11px"><span style="color:red">(*)</span> Ce document requiert une réquisition aux fins de reconstitution de l'acte</p>

    </div>

</page>
