<style>
    /* td{
        font-size: 100%;
    }
    b{
        font-size: 100%;
    } */
</style>
<page orientation="portrait" backimg="{{ str_replace('\\', '/', public_path('tpl/armoirie_congo.png')) }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 12pt">
    @php
        setlocale(LC_TIME, "fr_FR", "French");

        $localite = "";
        $localiteParent = "";
        $inst = "";
        $institution = $certificat->institutionUser->institution;
        $localisation = "";

        $inst = $institution->lib_institution;
        // $localite = " COMMUNE DE ".$institution->lieu->localiteParent->lib_localite;
        $localite = " DISTRICT D'".$institution->lieu->lib_localite;

        $localiteParent  = "DEPARTEMENT DE LA CUVETTE";
        $localisation = $institution->lieu->localiteParent->lib_localite;

        // Utiliser le service Sifec pour obtenir les informations de localisation
        $institutionCertif = $certificat->institution;
        $localisationDataCertif = \App\Sifec\Sifec::getLocalisationInstitution($institutionCertif);
        
        $departement = $localisationDataCertif['localiteParent'];
        $comDistrict = $localisationDataCertif['localite'];
        $cec = $localisationDataCertif['inst'];
        
        // Pour typeLocalite, on peut utiliser l'objet departement du service
        $typeLocalite = $localisationDataCertif['departement'] ? $localisationDataCertif['departement']->typelocalite->lib_type_localite : "";
    @endphp

    <table cellspacing="0" style="width: 100%; font-size: 14px;">
        <tr>
            <td style="width:40%; text-align: center;">
                <p>
                    <span>
                        <strong>{{ $typeLocalite." DE ".$departement }}</strong>
                    </span> <br>
                    <span>{{ "COMMUNE DE ".$comDistrict}}</span> <br>

                    <span>
                        {{-- @if ($certificat->type_declaration == "DECLARATION NAISSANCE" || $certificat->type_declaration == "DECLARATION TARDIVE DE NAISSANCE" || $certificat->type_declaration == "DECLARATION DE PATERNITE")
                            {{ $certificat->institution->lib_institution}}
                        @else
                            {{ $inst }}
                        @endif --}}
                        {{ $cec}}
                    </span>
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
            <td style="width:100%; text-align: center;border:black;">
                {{-- <p><strong style="font-size: 18px;"> CERTIFICAT DE NON INSCRIPTION DE NAISSANCE</strong><br> Année: <strong>{{date("Y", strtotime( $certificat->date_heure_declaration))}}</strong>  N°: <strong>{{$certificat->numero_certificat}}</strong></p> --}}
                <p><strong style="font-size: 18px;">  {{ $certificat->type_declaration }}</strong><br> Année: <strong>{{date("Y", strtotime( $certificat->date_heure_declaration))}}</strong>  N°: <strong>{{$certificat->numero_certificat}}</strong></p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>
        </tr><br>
    </table>
    <div style="margin-top: 3%;margin-left: 2%;margin-right: 6%;border-radius: 2mm;">
        <div style="position: absolute; left: 20px; top: 220px; width: 700px; height: 700px; padding: 0px; overflow: hidden; text-align: left; font-weight: normal; font-size:14px;">
            <P style="text-align: justify;">Je soussigné, Officier d'état civil de la {{ $inst }} , informons que le  {{ strtolower($certificat->type_declaration) }} de naissance :
            </P>
            <table align="left" style="border-radius: 1mm; border: none;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 14px;">Par le centre d'état civil de: <strong>{{Auth::user()->affectationActive()->institution->lib_institution}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td style="height: 14px;">Au profit de : <strong>{{$certificat->enfant->nom}} <span style="text-transform: capitalize"> {{$certificat->enfant->prenom}}</span></strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    {{-- <td style="height: 14px;">Né(e), le: <strong>{{strftime("%d %B %Y", strtotime( $certificat->enfant->date_naissance))}}</strong>, à <strong>{{$certificat->enfant->lieu_naissance}}</strong></td> --}}
                    <td style="height: 14px;">Né(e), le: <strong>{{ utf8_encode(strftime('%d %B %Y', strtotime( $certificat->enfant->date_naissance))) }}</strong>,

                        à <strong>{{$certificat->enfant->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Sexe : <strong>{{ $certificat->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">{{ $certificat->enfant->sexe=="M" ? "Fils" : "Fille" }} de:<strong>{{$certificat->pere->nom}} <span style="text-transform: capitalize">{{$certificat->pere->prenom}}</span></strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Né le : <strong>{{ utf8_encode(strftime('%d %B %Y', strtotime( $certificat->pere->date_naissance))) }}</strong>, à <strong>{{$certificat->pere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Profession: <strong>{{$certificat->pere->profession->lib_profession}}</strong>, Nationalité: <strong>{{ $certificat->pere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Domicilié au :<strong>{{ $certificat->pere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Et de :<strong>{{$certificat->mere->nom}} <span style="text-transform: capitalize">{{$certificat->mere->prenom}}</span></strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Né le : <strong>{{ utf8_encode(strftime('%d %B %Y', strtotime( $certificat->mere->date_naissance))) }}</strong>, à <strong>{{$certificat->mere->lieu_naissance}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Profession: <strong>{{$certificat->mere->code_profession != NULL ? $certificat->mere->profession->lib_profession :""}}</strong>, Nationalité: <strong>{{ $certificat->mere->nationalite->lib_nationalite }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    {{-- <td style="height: 14px;">Domicilié au: <strong>{{ Sifec::adressepersonne($certificat->mere->code_personne) }}</strong></td> --}}
                    <td style="height: 14px;">Domicilié au: <strong>{{ $certificat->mere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Ne nous a été présenté que ce jour : <strong>{{strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration))}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style="height: 14px;">Et pour ce faire , certifions que l'acte de naissance dudit enfant n'a pas encore été dréssé.</td>
                </tr>
            </table>
            <p>En foi de quoi, le présent certificat lui est établi, pour servir et valoir ce que de droit. /-</p>
        </div>
    </div><br><br>

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
                        <p>Fait à <span style="text-transform: capitalize">{{ $localisation }}</span> , le {{utf8_encode(strftime("%d %B %Y", strtotime( $certificat->date_heure_declaration)))}}<br>L'officier de l'état civil</p>

                    </td>
                  </tr>
            </tbody>
        </table>
    </div>
    <br><br><br>
    <p style="text-align: left; font-style:italic; font-size:11px"><span style="color:red">(*)</span> Ce document requiert une réquisition ou un jugement</p>

</page>
