{{-- 
    Vue copie acte de naissance pour demande de document
    Utilise la signature de la demande au lieu de celle de l'acte original
--}}
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
    .acte-contenu td {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        font-size: 13px;
        line-height: 1.2;
    }
    .acte-contenu b {
        font-size: 13px;
    }
    .acte-contenu td.institution-officier {
        overflow-wrap: anywhere;
        word-break: break-word;
        max-width: 100%;
    }
</style>
<page orientation="portrait" backimg="{{ public_path('tpl/back-border.png') }}" backcolor="#FEFEFE" backimgx="center" backimgy="50%" backimgw="70%" backtop="0"  backbottom="22mm" style="font-size: 14px">
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

    $institution = $acte->institutionUser->institution;
    $departement = $institution->lieu->localiteParent->localiteParent;
    $communeDistrict = $institution->lieu->localiteParent;
    
    // N'utiliser QUE la signature de la demande (pas de fallback sur l'acte original)
    $signatureOfficier = $demande->signature_officier ?? null;
    $nomSignataire = $demande->signataire 
        ? optional(optional($demande->signataire->user)->personne)->nomcomplet() 
        : '';
    @endphp
    
    {{-- Entête --}}
    <table cellspacing="0" style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width:40%; text-align: center;">
                <p>
                    <span>
                    {{ "DEPARTEMENT DE ".$departement->lib_localite }}
                    <br>
                        {{ "COMMUNE DE ".$communeDistrict->lib_localite }}
                    </span> <br>
                    <span><strong>{{ $institution->lib_institution }}</strong></span>
                </p>
            </td>
            <td style="width:34%; text-align: center;">
                <p style="color: red">{{ $infos != "" ? $infos : "" }}</p>
            </td>
            <td style="width:33%; text-align: center;">
                <strong>REPUBLIQUE DU CONGO</strong><br>
                Unit&eacute; - Travail - Progr&egrave;s
            </td>
        </tr>
  </table><br><br>
  
    <table align="center" style="border-radius: 1mm; border: none;">
        <tr style="">
            <td style="width:100%; text-align: center;">
                @if ((int) $acte->approbation_tribunal === 1 && filled($acte->sceau_tribunal))
                    <img src="{{ public_path('app/'.$acte->sceau_tribunal) }}" alt="" width="100" height="100" style="display: block; margin: 0 auto 3mm auto;">
                @endif
                <p><strong style="font-size: 18px;">COPIE D'ACTE DE NAISSANCE</strong>
                    <br>N°: <strong style="color: red">{{ $acte->niupp }} R.A.N {{ optional(optional($acte->registre)->created_at)->format('Y') ?? date('Y') }}</strong></p>
            </td>
        </tr><br>
    </table>

    @include('demande-document._mention_validite_pdf')
    
    <div style="margin-top: 5mm; margin-left: 6%; margin-right: 6%;">
            <table class="acte-contenu" align="left" style="table-layout: fixed; width: 100%;">
                <tr style="width:100%; text-align: left; padding-bottom: 4px;">
                    <td class="institution-officier">L'Officier du centre d'état civil principal de: <strong>{!! \App\Sifec\Sifec::wrapLibInstitutionPourActePdf($acte->institutionUser->institution->lib_institution ?? null, 30) !!}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est informé le: <br> <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_declaration))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_declaration))) }} minutes</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) }}</strong> à </td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td style=""> <strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td>A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
                </tr>
                <tr style="width:100%; text-align: left;">
                    <td><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }}
                       <span style="color: red;">{{ \App\Sifec\Sifec::formatNomPrenomPourActe($nomEnfant, $prenomEnfant) }}</span></strong>
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
                            {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) }}
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
                            {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) }}
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

    {{-- Pied avec signature de la demande --}}
    <div style="position:absolute; bottom:0; margin-left:10px;">
        <table class="historique" cellspacing="0" style="width: 95%; font-size: 14px;">
            <col style="width: 35%">
            <col style="width: 25%">
            <col style="width: 40%">
            <tbody>
                <tr>
                    <td style="text-align: center;">Le déclarant</td>
                    <td style="text-align: left;"></td>
                    <td style="text-align: left;">
                     <p style="font-size: 14px;">Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{ $demande->date_signature ? $demande->date_signature->format('d/m/Y') : now()->format('d/m/Y') }}<br>L'officier de l'état civil</p>
                         @if ($signatureOfficier)
                             <img src='{{ public_path('app/'.$signatureOfficier) }}'><br>
                             <span style="color:black; font-weight:bold">{{ $nomSignataire }}</span>
                         @else
                             <div style="height: 60px; padding-top: 10px;">
                                 <span style="color: #999; font-style: italic;">[En attente de signature]</span>
                             </div>
                         @endif
                     </td>
                  </tr>
            </tbody>
        </table>
    </div>
</page>
