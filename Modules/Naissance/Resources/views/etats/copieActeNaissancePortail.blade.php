<style>
    td{
        font-size: 14px;
    }
    b{
        font-size: 14px;
    }
    small{
        color: red;
    }
    button#print{
        display: none;
    }
    /* Empêcher le débordement du nom d'institution long */
    .acte-contenu td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        max-width: 650px;
    }
    .acte-contenu td.institution-officier {
        overflow-wrap: anywhere;
        word-break: break-word;
        max-width: 100%;
    }
</style>
  <page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="30mm" style="font-size: 14px">
    @php
    $infos = "";
    $tribunal = null;
    try {
        $tribunal = $acte->declaration->libInstitutionTribunalPourMentionActe();
    } catch (\Throwable $e) {
        $tribunal = null;
    }

    $num = "";
    if ($tribunal) {
        if (str_contains($tribunal, "TRIBUNAL D'INSTANCE")) {
            $num = str_replace("TRIBUNAL D'INSTANCE","TI ",$tribunal);
        } else {
            $num = str_replace("TRIBUNAL DE GRANDE INSTANCE","TGI ",$tribunal);
        }
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
        $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
        $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    if($acte->declaration->type_declaration == "FICHE DE TRANSCRIPTION"){
        $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$acte->declaration->numero_req.'/'.date("Y", strtotime($acte->declaration->date_heure_declaration))." ".$num;
    }

    $nomEnfant = $acte->declaration->enfant->nom ?? '';
    $prenomEnfant = $acte->declaration->enfant->prenom ?? '';
    if ($acte->lastRectification && $acte->lastRectification->detailsRectification && $acte->lastRectification->detailsRectification->count() > 0) {
        foreach ($acte->lastRectification->detailsRectification as $d) {
            if ($d->code_rubrique === 'RUB_0001') {
                $nomEnfant = $d->nouvelle_valeur ?? $nomEnfant;
            }
            if ($d->code_rubrique === 'RUB_0002') {
                $prenomEnfant = $d->nouvelle_valeur ?? $prenomEnfant;
            }
        }
    }
    $prenomEnfant = \App\Sifec\Sifec::formatPrenomPourActe($prenomEnfant);
    $personneDeclCopie = filled(optional($acte->declaration)->code_adoptant) && $acte->declaration->adoptant
        ? $acte->declaration->adoptant
        : $acte->declaration->declarant;
    $ligneDeclarantCopie = $personneDeclCopie
        ? \App\Sifec\Sifec::formatNomPrenomPourActe($personneDeclCopie->nom ?? '', $personneDeclCopie->prenom ?? '')
        : '';
    $datePourCopiePdf = $acte->date_emission ?? optional($acte->declaration)->date_heure_declaration ?? now();

    @endphp
    <table cellspacing="0" style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width:35%; text-align: left; vertical-align: middle;">
                @php
                   setlocale(LC_TIME, "fr_FR", "French");

                    $institution = $acte->institutionUser->institution;
                    $localisationData = \App\Sifec\Sifec::getLocalisationInstitution($institution);
                    $departement = $localisationData['localiteParent'];
                    $communeDistrict = $localisationData['localite'];
                    $libLocalite = $localisationData['localisation'];
                @endphp
                @if(Auth::user() != null && Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution != "TPINS_0005")
                <p>
                    <span>
                    {{ $departement }}
                    <br>
                        {{ $communeDistrict }}
                    </span> <br>
                    <span><strong>{{ $localisationData['inst'] }}</strong></span>
                </p>
                @else
                <p>
                    <span>
                        {{ $departement }}
                        <br>
                            {{ $communeDistrict }}
                        </span> <br>
                    <span>
                        <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
                    </span> <br>
                    {{-- <span>Service Consulaire</span> <br> --}}
                </p>
                @endif
                @if($infos != "")
                <p style="color: red; margin-top: 4px;">{{ $infos }}</p>
                @endif
            </td>
            <td style="width:30%; text-align: center; vertical-align: middle;">
                @if ($acte->approbation_tribunal == 1 && $acte->sceau_tribunal)
                    @php
                        $pdfSceau = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($acte->sceau_tribunal);
                    @endphp
                    @if ($pdfSceau)
                    <img src="{{ $pdfSceau }}" alt="" width="100" height="100" style="display: block; margin: 0 auto;">
                    @endif
                @endif
            </td>
            <td style="width:35%; text-align: right; vertical-align: middle;">
                <p style="margin: 0;">
                    <strong>REPUBLIQUE DU CONGO</strong><br>
                    Unit&eacute; - Travail - Progr&egrave;s
                </p>
            </td>
        </tr>
  </table><br><br>
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                <p><strong style="font-size: 18px;">COPIE D'ACTE DE NAISSANCE </strong>
                    @if(filled($acte->approbation_mairie))
                    <br>N°: <strong style="color: red">{{ $acte->niupp }} R.A.N {{ optional(optional($acte->registre)->created_at)->format('Y') ?? date('Y') }}</strong>
                    @endif
                </p>
            </td>
            <td style="width:15%; text-align: center;">
                {{-- <img src="{{asset('app-assets/images/img.jpg')}}" alt=""> --}}
            </td>

        </tr><br>
    </table>
    <div style="margin-top: 60px;margin-left: 6%;margin-right: 6%;border-radius: 2mm;">

        <div style="position: absolute;top: 240px; width: 700px; height: 500px; padding: 0px; overflow: hidden; font-weight: normal; font-size:14px;">
            <table class="acte-contenu" align="left" style="margin-left: 2%; table-layout: fixed; width: 96%;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td class="institution-officier">L'Officier du centre d'état civil principal de: <strong>{!! nl2br(e(wordwrap(optional($acte->institutionUser->institution)->lib_institution ?? '', 35, "\n", true))) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong> {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong> à </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }}
                       <span style="color: red;">{{ trim($nomEnfant.' '.$prenomEnfant) }}</span></strong>
                    </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Déclaré par: <strong>{{ $ligneDeclarantCopie }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Situation matrimoniale des parents: <strong>{{ $acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Fils " : "Fille "  }} de:<strong> {{ $acte->declaration->pere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->pere->nom, $acte->declaration->pere->prenom) : $dummy}}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Né le : <strong>
                        @if ($acte->declaration->pere != NULL)
                            {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->pere->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{{ $acte->declaration->pere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Et de :<strong> {{ $acte->declaration->mere ? \App\Sifec\Sifec::formatNomPrenomPourActe($acte->declaration->mere->nom, $acte->declaration->mere->prenom) : $dummy}}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Née le : <strong>
                        @if ($acte->declaration->mere != NULL)
                            {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
                        @endif
                    </strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A : <strong>{{ $acte->declaration->mere->lieu_naissance }}</strong></td>
                </tr>

                <tr style="width:100%; text-align: left;">
                    <td>Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Domicilié au : <strong>{{ $acte->declaration->mere->adresse }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong></td>
                </tr>
                @if($acte->declaration->type_declarant == "Personne physique")
                <tr style="width:100%; text-align: left;">
                    <td>Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant }}</strong></td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div style="position:absolute; bottom:0;margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
            <col style="width: 35%">
            <col style="width: 25%">
            <col style="width: 40%">
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
                         @if($acte->approbation_mairie != "")
                         <div style="margin-bottom:0;">
                             @isset($qrCode)
                                <div style="width: 30mm;">
                                    <qrcode value="{{ $qrCode }}" ec="H" style="width: 100%;"></qrcode>
                                </div>
                             @endisset
                         </div>
                         @endif

                        {{-- <div style="margin-bottom:0;"><qrcode value="http://172.16.41.11/sifec-20-12-2023/public/qrcode?niupp={{ $acte->niupp }}" ec="H" style="width: 30mm; background-color: white; color: black;"></qrcode></div> --}}
                    </td>
                    <td style="text-align: left;">
                     <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower(trans($libLocalite)))}}, le {{ utf8_encode(strftime('%d %B %Y', strtotime($datePourCopiePdf))) }}<br>L'officier de l'état civil</p>
                         @if (! empty($signatairePortail->signature ?? null))
                             @php
                                 $pdfSignature = \App\Support\SifecPdfLocalImagePath::imgSrcForHtml2Pdf($signatairePortail->signature);
                             @endphp
                             @if ($pdfSignature)
                             <img src="{{ $pdfSignature }}"><br>
                             @endif
                             <span style="color:black; font-weight:bold">{{ \App\Sifec\Sifec::formatNomPrenomPourActe($signatairePortail->nom ?? '', $signatairePortail->prenom ?? '') }}</span>
                         @else
                             <div style="height: 60px; padding-top: 10px;">
                                 <span style="color: #999; font-style: italic;">[En attente de signature de délivrance]</span>
                             </div>
                         @endif
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
