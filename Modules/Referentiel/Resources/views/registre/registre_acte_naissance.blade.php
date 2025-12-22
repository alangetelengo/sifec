
@extends("layout.app")
@section("titre")
    Registre de naissance
@endsection

@section("styles")

    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!--- Carte du Congo --->
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/bookblock.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/demo1.css') }}" />
     <!-- css de la carte -->
     <script src="{{ URL::to('carte/js/modernizr.custom.js') }}"></script>

    <!-- Styles personnalisés pour l'amélioration UX -->
    <style>
        .modern-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-header-custom th {
            border: none;
            padding: 15px 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .table-row-hover:hover {
            background-color: #f8f9fa;
        }

        .modern-table tbody tr {
            border-bottom: 1px solid #e9ecef;
        }

        .modern-table tbody td {
            padding: 12px 10px;
            vertical-align: middle;
            border: none;
        }

        .modern-btn {
            border-radius: 15px;
            padding: 8px 16px;
            font-weight: 500;
            background-color: #6c757d;
            color: white;
            border: none;
            text-decoration: none;
            display: inline-block;
        }

        .modern-btn:hover {
            background-color: #5a6268;
            color: white;
            text-decoration: none;
        }

        .badge.bg-pink {
            background-color: #e91e63 !important;
            color: white;
        }

        .badge {
            border-radius: 15px;
            font-weight: 500;
        }

        .fw-bold.text-primary {
            font-size: 1.1rem;
        }

        .fw-semibold {
            font-weight: 600;
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .table-header-custom th {
                padding: 10px 5px;
                font-size: 0.8rem;
            }

            .modern-table tbody td {
                padding: 8px 5px;
            }

            .modern-btn {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }
        }

        /* Styles pour la reliure spirale noire */
        .spiral-binding {
            position: absolute;
            left: 50%;
            top: 0;
            height: 670px;
            width: 25px;
            background: repeating-linear-gradient(
                0deg,
                #333 0px,
                #333 8px,
                #666 8px,
                #666 12px,
                #333 12px,
                #333 20px
            );
            z-index: 10;
            transform: translateX(-50%);
            box-shadow:
                0 0 10px rgba(0, 0, 0, 0.3),
                inset 0 0 5px rgba(255, 255, 255, 0.1);
            border-radius: 2px;
        }

        .spiral-binding::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            width: 3px;
            height: 100%;
            background: repeating-linear-gradient(
                0deg,
                #555 0px,
                #555 4px,
                #999 4px,
                #999 8px
            );
            transform: translateX(-50%);
            border-radius: 1px;
        }

        .position-relative {
            position: relative;
        }

        /* Espacement pour éviter le chevauchement */
        .col-sm-6:first-child {
            padding-right: 10px;
        }

        .col-sm-6:last-child {
            padding-left: 10px;
        }

        /* Styles pour le tableau scrollable dans le registre */
        .registre-table-container {
            max-height: 480px;
            overflow-y: auto;
            overflow-x: hidden;
            position: relative;
        }

        .registre-table-container::-webkit-scrollbar {
            width: 8px;
        }

        .registre-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .registre-table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        .registre-table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Fixer le header du tableau lors du scroll */
        .registre-table-wrapper {
            position: relative;
        }

        .registre-table-wrapper table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .registre-table-wrapper table thead {
            position: sticky;
            top: 0;
            z-index: 10;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Ajuster le card-body pour le scroll */
        .card-body-scrollable {
            padding: 15px;
            height: calc(100% - 120px);
            display: flex;
            flex-direction: column;
        }

        .card-header-registre {
            flex-shrink: 0;
            padding: 15px !important;
        }

        .table-responsive {
            flex: 1;
            overflow: hidden;
        }

        /* Styles pour la barre de recherche */
        #search-acte-input {
            border-radius: 0 5px 5px 0;
        }

        #search-acte-input:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 0.2rem rgba(118, 75, 162, 0.25);
        }

        #clear-search-btn {
            border-radius: 0 5px 5px 0;
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        #clear-search-btn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        #search-result-count {
            display: block;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
@endsection

@section("corps")

<div class="main clearfix">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">
            @php
                $pdt = "";
                $sexep = "";
                if ($registre->signataire != null) {
                    $pdt = $registre->signataire->user->personne->nomcomplet();
                    $sexep = $registre->signataire->user->personne->sexe;
                }
            @endphp
              <!-- page 1 -->
            @if($registre->sceau != null)
            <div class="bb-item">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="height: 680px; border: 2px solid; text-align: left; padding: 20px; font-size: 12px">
                            <h2> Ce présent registre contenant <strong>{{ $registre->nombre_acte_prevu }}</strong> feuillets devant servir de  <strong> REGISTRE DE {{ $registre->typeRegistre->lib_type_registre }}</strong>
                                en <strong>{{ date("Y", strtotime($registre->updated_at)) }}</strong> pour le compte du {{ strtolower($registre->institutionUser->institution->typeInstitution->typeCategorieInstitution->lib_type_categorie_institution) }} de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong>, a été côté et paraphé par nous, <strong>{{ $pdt }}</strong>, {{ $sexep == "M" ? "Président" : "Présidente" }}  du <strong> {{ $registre->institutionUser->institution->institutionparent->lib_institution }} </strong>
                                ce <strong>{{ date("d-m-Y", strtotime($registre->updated_at)) }}</strong>. <br> <br>
                                Le registre sera clôturé et arrêté le 31 Décembre par l'officier de l'état-civil.<br><br><br><br><br><br>
                            </h2>

                            <h2><span style="margin-left: 730px;"> Fait à <strong style="text-transform: capitalize">{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->updated_at)) }}</strong> </span></h2>
                            <b style="margin-left: 730px;">
                                <img src='{{ asset("app/".$registre->sceau) }}' alt="">
                                <img src='{{ asset("app/".$registre->signature_tribunal) }}' alt="" width="200" height="200">
                            </b>

                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- /page 1 -->

            <!-- page 2 -->
            <div class="bb-item">
                <div class="row position-relative">
                    <!-- Reliure spirale noire -->
                    <div class="spiral-binding"></div>

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="">
                                @if ($registre->sceau != null)
                                    <img src='{{ asset("app/".$registre->sceau) }}' alt="">
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="card-text" style="margin-top: 100px;">
                                    <h2><strong> C.E.C :  {{  $registre->institutionUser->institution->lib_institution}} </strong></h2>
                                    <h2><strong> {{$registre->lib_registre}} </strong></h2>
                                    <h2><strong>Année : {{date("Y", strtotime($registre->updated_at))}} </strong></h2>
                                    <h2><strong> Nombre d'actes transcrits: {{$registre->nombre_acte_transcrit."/".$registre->nombre_acte_prevu }} </strong></h2>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid; display: flex; flex-direction: column;">
                            @if (count($actesRegistre) > 0 )
                                <div class="card-header border-0 pb-0 card-header-registre" style="flex-shrink: 0;">
                                    <h3><strong> Liste des actes de naissance du registre </strong></h3>
                                    <div class="input-group mt-2" style="max-width: 100%;">
                                        <span class="input-group-text" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" id="search-acte-input" class="form-control" placeholder="Rechercher par nom, prénom, feuillet..." style="border: 1px solid #667eea;">
                                        <button class="btn btn-secondary" type="button" id="clear-search-btn" style="display: none;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted" id="search-result-count" style="display: none;"></small>
                                </div>
                                <div class="card-body card-body-scrollable" style="flex: 1; overflow: hidden; padding: 15px;">
                                    <div class="registre-table-container">
                                        <div class="registre-table-wrapper">
                                            <table id="example" class="table table-hover table-striped modern-table" style="margin-bottom: 0;">
                                                <thead class="table-header-custom">
                                                    <tr class="text-center">
                                                        <th><i class="fas fa-file-alt me-2"></i>Feuillet</th>
                                                        <th><i class="fas fa-user me-2"></i>Nom</th>
                                                        <th><i class="fas fa-user-tag me-2"></i>Prénom</th>
                                                        <th><i class="fas fa-venus-mars me-2"></i>Sexe</th>
                                                        <th><i class="fas fa-eye me-2"></i>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="actes-tbody">
                                                    @foreach ($actesRegistre as $act)
                                                    @php
                                                    // Récupérer la position depuis les 4 derniers caractères du niupp (code_acte)
                                                    $position = (int) substr($act->niupp, -4);
                                                    @endphp
                                                    <tr class="table-row-hover acte-row" data-nom="{{ strtolower($act->declaration->enfant->nom) }}" data-prenom="{{ strtolower($act->declaration->enfant->prenom) }}" data-feuillet="{{ $position }}">
                                                        <td class="fw-bold text-primary">{{ $position }}</td>
                                                        <td class="fw-semibold">{{$act->declaration->enfant->nom}}</td>
                                                        <td class="fw-semibold">{{$act->declaration->enfant->prenom}}</td>
                                                        <td class="text-center">
                                                            @if($act->declaration->enfant->sexe == "M")
                                                                <span class="badge bg-primary fs-6 px-3 py-2">
                                                                    <i class="fas fa-mars me-1"></i>Masculin
                                                                </span>
                                                            @else
                                                                <span class="badge bg-pink fs-6 px-3 py-2">
                                                                    <i class="fas fa-venus me-1"></i>Féminin
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{route('registre.feuillet.registre.naissance', $act->niupp )}}"
                                                               class="modern-btn"
                                                               title="Consulter l'acte"
                                                               target="_blank">
                                                                <i class="fas fa-eye me-1"></i>Consulter
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- / page 2 -->
            @foreach ($actesRegistre as $acte)

            <div class="bb-item">
                <div class="row position-relative">
                    <!-- Reliure spirale noire -->
                    <div class="spiral-binding"></div>

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">
                                <div class="row" style="font-size: 12px">
                                    <div class="col-sm-5">

                                        @php
                                            $institution = $acte->institutionUser->institution;
                                            $departement = $institution->lieu->localiteParent->localiteParent;
                                            $communeDistrict = $institution->lieu->localiteParent;
                                            $tribunal = $acte->institutionUser->institution->institutionParent->lib_institution;
                                            setlocale(LC_TIME, "fr_FR", "French");

                                            $num = "";
                                            $titre = "";
                                            $top = "";
                                            $infos = "";

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

                                            if($acte->declaration->jugement != null){

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

                                        @if(Auth::user() != null && Auth::user()->affectationactive()->institution->typeInstitution->code_type_institution != "TPINS_0005")
                                        <p>
                                            <span>
                                            {{ "DEPARTEMENT DE ".$departement->lib_localite }}
                                            <br>
                                                {{ "COMMUNE DE ".$communeDistrict->lib_localite }}
                                            </span> <br>
                                            <span><strong>{{ $institution->lib_institution }}</strong></span>
                                        </p>
                                        @else
                                        <p>
                                            <span>
                                                <strong>{{ $acte->institutionUser->institution->lib_institution }}</strong>
                                            </span> <br>
                                            <span>Service Consulaire</span> <br>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="col-sm-3">
                                        <small style="color: red;text-align:center;font-style:italic">{{ $infos != "" ? $infos : "" }}</small><br>

                                        @if ($acte->approbation_tribunal == 1)
                                            <img src='{{ asset("app/".$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                                        @endif
                                    </div>

                                    <div class="col-sm-4"><strong>
                                        REPUBLIQUE DU CONGO<br>
                                        Unit&eacute; - Travail - Progr&egrave;s
                                    </strong>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <br> <strong>ACTE DE NAISSANCE  <br> N°: <span style="color: red">{{ $acte->niupp }}  R.A.N {{ $acte->registre->created_at->format('Y') }} </span> </strong>
                                        <br><br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                        @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                                        L'officier du centre d'état civil de: <strong> {{ $acte->declaration->institutionUser->institution->lib_institution }}</strong>

                                        @else
                                        Consulat du Congo en République Démocratique du Congo
                                        @endif
                                          <br>
                                          Est informé que le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong><br>
                                        Est né(e), un enfant de sexe: <strong>{{$acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin" }}</strong><br>
                                        @if($acte->declaration->type_declarant == "Personne physique")
                                        Nom(s): <strong style="color: red">{{ $acte->declaration->enfant->nom }}</strong><br>
                                        @endif
                                        Prénom(s): <strong style="color: red">{{ $acte->declaration->enfant->prenom }}</strong><br>
                                        Lieu de naissance: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong><br>
                                        Déclaré par: <strong>{{$acte->declaration->declarant->nom. " ".$acte->declaration->declarant->prenom }}</strong><br>
                                        Filiation: <strong>{{$acte->declaration->type_declarant == "Personne physique" ? $acte->declaration->filiation->lib_filiation : $dummy }}</strong><br>
                                        Situation matrimoniale des parents: <strong>{{$acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong><br>
                                        Fils de:<strong> {{$acte->declaration->pere ? $acte->declaration->pere->nom." ".$acte->declaration->pere->prenom : $dummy }}</strong><br>
                                        Né le : <strong> {{ $acte->declaration->pere ?  Sifec::asLetters((int)date("d",strtotime($acte->declaration->pere->date_naissance)))." ".Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) : $dummy }}</strong><br>
                                        A : <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->lieu_naissance : $dummy }}</strong><br>
                                        Nationalité: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy}}</strong><br>
                                        Niveau d'instruction: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy}}</strong><br>
                                        Domicilié au : <strong>{{$acte->declaration->pere ? $acte->declaration->pere->adresse." / ".$acte->declaration->pere->lieu_naissance : $dummy }}</strong><br>
                                        Proféssion: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy}}</strong><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12" style="text-align:left; font-size:14px"><br><br>
                                        Et de :<strong> {{ $acte->declaration->mere ? $acte->declaration->mere->nom." ".$acte->declaration->mere->prenom : $dummy }}</strong><br>
                                        Né le : <strong> {{ $acte->declaration->mere ?  Sifec::asLetters((int)date("d",strtotime($acte->declaration->mere->date_naissance)))." ".Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) : $dummy }}</strong><br>
                                        A : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy }}</strong><br>
                                        Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong><br>
                                        Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong><br>
                                        Domicilié au : <strong>{{ $acte->declaration->mere ?  $acte->declaration->mere->adresse." / ".$acte->declaration->mere->lieu_naissance : $dummy }}</strong><br>
                                        Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong><br>
                                        @if($acte->declaration->type_declarant == "Personne physique")
                                        Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant + 1 }}</strong><br>
                                        @endif
                                    </div><br><br>
                                    Le déclarant,
                                    <div class="col-xl-12" style="margin-left: 100px">
                                        <p>Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>
                                            @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                                                L'Officier de l'Etat Civil
                                            @else
                                                Consule
                                            @endif
                                        </p>
                                        @if ($acte->approbation_mairie != "")
                                            <img src='{{ asset("app/".$acte->signature_mairie) }}'><br>
                                            {{ $acte->signataire->user->personne->nomcomplet() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                <br><br><br><br><br>
                                @php
                                // Récupérer la position depuis les 4 derniers caractères du niupp (code_acte)
                                $positionActe = (int) substr($acte->niupp, -4);
                                @endphp
                                <strong> {{$positionActe.'/'.count($actesRegistre)}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if ($registre->signataireClose != null)
            <div class="bb-item">
               @php
                   $nomcompletcec = "";
                    $nomcompletcec = $registre->signataireClose->user->personne->nomcomplet();
               @endphp
               <div class="row position-relative">
                   <!-- Reliure spirale noire -->
                   <div class="spiral-binding"></div>

                   <div class="col-sm-11">
                       <div class="card" style="height: 670px; border: 2px solid; text-align: left; padding: 20px; font-size: 18px">

                           <h2>
                               Nous <strong>{{ $nomcompletcec }}</strong>,
                               @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                               officier de l'Etat-civil
                               @else
                               consule
                               @endif

                                de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                               arrêtons et clôturons le présent registre de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                               comprenant <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,1) }}</strong> actes  inscrits du numero <strong>1</strong> au numero <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,1) }}</strong>
                               inclus et <strong>{{ Sifec::format_nombre(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit),1) }}</strong> feuillets restants.<br><br>
                           <br><br><br><br><br><br>
                            </h2>

                           <h2><span style="margin-left: 730px;"> Fait à <strong style="text-transform: capitalize">{{ trans($communeDistrict->lib_localite)}}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->updated_at)) }}</strong> </span></h2>
                            <b style="margin-left: 730px;">
                                <img src='{{ asset("app/".$registre->signature_cloture_cec) }}' alt="" width="200" height="200">
                            </b>
                       </div>
                   </div>
               </div>
           </div>
           @endif
        </div>

        <nav id="btn_footer">
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
        </nav>
        <a href="{{ route('registre.index') }}" class="btn btn-primary mb-2" style="float: right;">
            <i class="fas fa-list"></i>
            Liste des registres
        </a>
        <!-- lien vers la page de la liste de registres -->
    </div>
</div><!-- /container -->

@endsection

@section("scripts")
{{-- DataTables désactivé pour cette page car nous utilisons un scroll personnalisé --}}
{{-- <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script> --}}
{{-- <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script> --}}

<script src="{{ URL::to('carte/js/jquerypp.custom.js') }}"></script>
<script src="{{ URL::to('carte/js/jquery.bookblock.js') }}"></script>
<!-- js de la catre -->
{{-- <script src="{{ URL::to('carte/js/carte.js') }}"></script> --}}

<script>
    var Page = (function() {
        var config = {
                $bookBlock : $( '#bb-bookblock' ),
                $navNext : $( '#bb-nav-next' ),
                $navPrev : $( '#bb-nav-prev' ),
                $navFirst : $( '#bb-nav-first' ),
                $navLast : $( '#bb-nav-last' )
            },
            init = function() {
                config.$bookBlock.bookblock( {
                    speed : 800,
                    shadowSides : 0.8,
                    shadowFlip : 0.7
                } );
                initEvents();
            },
            initEvents = function() {
                var $slides = config.$bookBlock.children();
                // add navigation events
                config.$navNext.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'next' );
                    return false;
                } );

                config.$navPrev.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'prev' );
                    return false;
                } );

                config.$navFirst.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'first' );
                    return false;
                } );

                config.$navLast.on( 'click touchstart', function() {
                    config.$bookBlock.bookblock( 'last' );
                    return false;
                } );

                // add swipe events
                $slides.on( {
                    'swipeleft' : function( event ) {
                        config.$bookBlock.bookblock( 'next' );
                        return false;
                    },
                    'swiperight' : function( event ) {
                        config.$bookBlock.bookblock( 'prev' );
                        return false;
                    }
                } );

                // add keyboard events
                $( document ).keydown( function(e) {
                    var keyCode = e.keyCode || e.which,
                        arrow = {
                            left : 37,
                            up : 38,
                            right : 39,
                            down : 40
                        };

                    switch (keyCode) {
                        case arrow.left:
                            config.$bookBlock.bookblock( 'prev' );
                            break;
                        case arrow.right:
                            config.$bookBlock.bookblock( 'next' );
                            break;
                    }
                } );
            };

        return { init : init };
    })();

    Page.init();

    // Fonctionnalité de recherche dans le tableau des actes
    $(document).ready(function() {
        var $searchInput = $('#search-acte-input');
        var $clearBtn = $('#clear-search-btn');
        var $actesRows = $('.acte-row');
        var $searchResultCount = $('#search-result-count');
        var totalRows = $actesRows.length;

        function performSearch() {
            var searchTerm = $searchInput.val().toLowerCase().trim();
            var visibleCount = 0;

            if (searchTerm === '') {
                $actesRows.show();
                $clearBtn.hide();
                $searchResultCount.hide();
                visibleCount = totalRows;
            } else {
                $actesRows.each(function() {
                    var $row = $(this);
                    var nom = $row.data('nom') || '';
                    var prenom = $row.data('prenom') || '';
                    var feuillet = $row.data('feuillet') || '';
                    var searchableText = (nom + ' ' + prenom + ' ' + feuillet).toLowerCase();

                    if (searchableText.indexOf(searchTerm) !== -1) {
                        $row.show();
                        visibleCount++;
                    } else {
                        $row.hide();
                    }
                });

                $clearBtn.show();
                $searchResultCount.show().text(visibleCount + ' résultat(s) trouvé(s) sur ' + totalRows);
            }
        }

        // Recherche en temps réel
        $searchInput.on('keyup', function() {
            performSearch();
        });

        // Bouton pour effacer la recherche
        $clearBtn.on('click', function() {
            $searchInput.val('');
            performSearch();
            $searchInput.focus();
        });

        // Recherche au focus
        $searchInput.on('focus', function() {
            $(this).select();
        });
    });

</script>

@endsection
