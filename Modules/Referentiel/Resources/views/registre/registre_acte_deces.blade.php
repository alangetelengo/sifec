
@extends("layout.app")
@section("titre")
    Livret deces
@endsection

@section("styles")

    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!--- Carte du Congo --->
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/bookblock.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/demo1.css') }}" />
     <!-- css de la carte -->
     {{-- <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/map.css') }}" /> --}}
     <script src="{{ URL::to('carte/js/modernizr.custom.js') }}"></script>
    <link href="{{ asset('css/sifec-registre-livret.css') }}?v=4" rel="stylesheet">

    <!-- Styles personnalisés pour l'amélioration UX -->
    <style>
        .modern-table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-header-custom {
            background: linear-gradient(90deg, #92b885 0%, #a3ce85 100%);
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
            background: linear-gradient(90deg, #92b885 0%, #a3ce85 100%);
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
            border-color: #92b885;
            box-shadow: 0 0 0 0.2rem rgba(146, 184, 133, 0.25);
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

<div class="page-sifec-form page-sifec-registre-livret registre-livret-toolbar-unified">
<div class="row mb-0 g-0 registre-livret-toolbar">
    <div class="col-12">
        <div class="registre-livret-toolbar-surface">
            <div class="registre-livret-toolbar-row">
                <div class="flex-grow-1 min-w-0">
                    <h4 class="mb-0"><i class="fas fa-book-open me-2"></i>Registre de décès</h4>
                    <small class="text-muted registre-livret-toolbar-sub">{{ $registre->lib_registre }} — {{ $registre->institutionUser->institution->lib_institution ?? '' }}</small>
                </div>
                <div class="d-flex flex-wrap gap-2 flex-shrink-0 align-items-center">
                    @if(Auth::user()->affectationActive()->institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == "TCINS_0002")
                        <a href="{{ route('registre.tribunal') }}" class="btn btn-sm pu-btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Liste des registres
                        </a>
                    @else
                        <a href="{{ route('registre.index') }}" class="btn btn-sm pu-btn-back">
                            <i class="fas fa-arrow-left me-1"></i> Liste des registres
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main clearfix">
    <div class="bb-custom-wrapper">
        <div class="registre-livret-panel">

        <div id="bb-bookblock" class="bb-bookblock">
        <!-- page 1 -->
        @if($registre->sceau != null)
            <div class="bb-item">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card registre-livret-doc-inner" style="min-height: 680px; border: 2px solid; text-align: left; font-size: 12px">
                            <h2>
                                {!! $registre->getTexteParapheRegistre('deces') !!}<br><br>
                            </h2>

                            <h2><span style="margin-left: 730px;"> Fait à <strong>{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->created_at)) }}</strong> </span></h2>
                            <b style="margin-left: 730px;">
                                <img src='{{ asset("app/".$registre->sceau) }}' alt="">
                                @include('referentiel::registre.partials.registre-paraphe-qrcode', ['registre' => $registre])
                            </b>
                            @include('referentiel::registre.partials.registre-paraphe-signature-pki', [
                                'registre' => $registre,
                                'contexte' => 'deces',
                            ])
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
                                <p class="card-text registre-livret-cec-intro">
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
                                <h3><strong> Liste des actes de décès du registre </strong></h3>
                                <div class="input-group mt-2" style="max-width: 100%;">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="search-acte-input" class="form-control" placeholder="Rechercher par nom, prénom, feuillet...">
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
                                                // Récupérer la position depuis les 8 derniers caractères du code_acte_deces
                                                // Les 8 derniers caractères du code_acte_deces représentent la position dans le registre
                                                $position = (int) substr($act->code_acte_deces, -8);
                                                @endphp
                                                <tr class="table-row-hover acte-row" data-nom="{{ strtolower($act->declaration->defunt->nom) }}" data-prenom="{{ strtolower($act->declaration->defunt->prenom) }}" data-feuillet="{{ $position }}">
                                                    <td class="fw-bold text-primary">{{ $position }}</td>
                                                    <td class="fw-semibold">{{$act->declaration->defunt->nom}}</td>
                                                    <td class="fw-semibold">{{$act->declaration->defunt->prenom}}</td>
                                                    <td class="text-center">
                                                        @if($act->declaration->defunt->sexe == "M")
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
                                                        <a href="{{ route('registre.feuillet.registre.deces',$act->code_acte_deces) }}"
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
                            @else
                            <div class="card-body">
                                <img src='{{ asset("app/".$registre->sceau) }}' alt="" width="100" height="100">
                            </div>
                            @endif

                        </div>
                    </div>

                </div>
                {{-- <img class="background" src="{{asset("carte/images/bg/siprale2.gif") }}" alt="siprale"  width="30"/> --}}
            </div>
            <!-- / page 1 -->


            <!-- page 3 -->
            @foreach ($actesRegistre as $acteReg)
            {{-- @php
            $acte = \App\Sifec\Sifec::acteNaissance($acteReg->declaration->code_declaration_naissance);
            @endphp --}}
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
                                            setlocale(LC_TIME, "fr_FR", "French");
                                                $departement = "";
                                                $communeDistrict = "";
                                                $institution = $acteReg->declaration->institutionUser->institution;
                                                $libInstitution = $institution->lib_institution;

                                                $infos = "";
                                                // if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                                                //     $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acte->declaration->numero_req.' /'.date("Y", strtotime($acte->declaration->date_heure_declaration));
                                                // }

                                                // if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
                                                //     $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.' /'.date("Y", strtotime($acte->declaration->date_heure_declaration));
                                                // }


                                                // if($acte->declaration->type_declaration == "DECLARATION TARDIVE"){
                                                //     $infos = 'ACTE TRANSCRIT SUIVANT LA DECLARATION TARDIVE';
                                                // }

                                                if($acteReg->declaration->requisition != ""){
                                                    $titre = $acteReg->declaration->requisition->typeRequisition->lib_type_requisition;
                                                    $num = $acteReg->declaration->requisition->num_requisition;
                                                    $date = $acteReg->declaration->requisition->date_requisition;
                                                    $infos = 'ACTE ETABLIT SUIVANT LA '.$titre.' N° '.$num.' DU '.(date("d-m-Y", strtotime($date)))." AU ".$acteReg->declaration->institutionUser->institution->institutionParent->lib_institution;

                                                }


                                                $commune = "COMMUNE DE ".$acteReg->declaration->institutionUser->institution->lieu->localiteParent->lib_localite;
                                                $departement = "DEPARTEMENT DE ".$institution->institutionParent->lieu->localiteParent->localiteParent->lib_localite;
                                            @endphp

                                        <p><strong>
                                            <span>
                                                {{ $departement }}<br>
                                                {{ $commune }}<br>
                                            </span>
                                            <span>{{$acteReg->institutionUser->institution->lib_institution}}</span>
                                            </strong>
                                        </p>
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($acteReg->approbation_tribunal == 1)
                                            <img src='{{ asset("app/".$acteReg->sceau_tribunal) }}' alt="" width="100" height="100">
                                        @endif
                                    </div>

                                    <div class="col-sm-5">
                                        <strong>REPUBLIQUE DU CONGO</strong><br>
                                        Unit&eacute; - Travail - Progr&egrave;s
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <p><strong style="font-size: 100%;">ACTE DE DECES</strong><br> N° : <strong style="color: red">{{ $acteReg->code_declaration_deces }}</strong> Du : <strong>{{date("d/m/Y", strtotime($acteReg->updated_at))}}</strong></p>
                                        <br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                        Centre d'état civil secondaire :  <strong> {{ $acteReg->institutionUser->institution->lib_institution }}</strong><br>
                                        le <strong> {{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime( $acteReg->declaration->date_heure_declaration)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($acteReg->declaration->date_heure_declaration))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acteReg->declaration->date_heure_declaration)))." à ".date("H", strtotime($acteReg->declaration->date_heure_declaration)). " heure(s) ".date("s", strtotime($acteReg->declaration->date_heure_declaration)) }} minutes</strong><br>
                                        S'est présenté(e) <strong> {{ $acteReg->declaration->declarant->nom.' '.$acteReg->declaration->declarant->prenom }}</strong>, &ensp; Filiation: <strong>{{ $acteReg->declaration->filiation->lib_filiation }} </strong><br>
                                        Domicilié(e) : <strong>{{ $acteReg->declaration->declarant->adresse }}</strong><br>
                                        qui a déclaré le décès de : <strong style="color: red">{{ $acteReg->declaration->defunt->nomComplet() }} </strong><br>
                                        Lieu de décès : <strong> {{ $acteReg->declaration->lieu_deces }} </strong><br>
                                        Sexe: <strong>{{ $acteReg->declaration->defunt->sexe== "M" ? "Masculin" : "Féminin" }}</strong><br>
                                        Nationalité : <strong>{{ $acteReg->declaration->defunt->nationalite->lib_nationalite }}</strong><br>
                                        Profession : <strong>{{ $acteReg->declaration->defunt->profession->lib_profession }}</strong><br>
                                        Niveau d'instruction : <strong>{{ $acteReg->declaration->defunt->niveau_instruction }}</strong><br>
                                        Domicile: <strong>{{ $acteReg->declaration->defunt->adresse }}</strong><br>
                                        Lieu de survenance : <strong>{{ $acteReg->declaration->lieuSurvenance->lib_lieu_survenance }}</strong><br>
                                        Réligion: <strong>{{ $acteReg->declaration->religion->lib_religion }}</strong><br>
                                        N° acte de naissance : <strong>{{ $acteReg->declaration->num_acte_naissance }}</strong><br>
                                        Date de naissance : <strong>{{ date('d-m-Y', strtotime($acteReg->declaration->defunt->date_naissance)) }}</strong><br>
                                        </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12" style="text-align: left"><br>

                                        Centre d'état civil de naissance : <strong>{{ $acteReg->declaration->cec_naissance }}</strong><br>
                                        Situation matrimoniale : <strong>{{ $acteReg->declaration->situationMat->lib_situation_matrimoniale }}</strong><br>

                                        @if ($acteReg->declaration->code_situation_matrimoniale == "SMAT_0001")
                                        Option de mariage : <strong>{{ $acteReg->declaration->code_regime != NULL ? $acteReg->declaration->regime->lib_regime :"" }}</strong><br>
                                        N° acte de mariage : <strong>{{ $acteReg->declaration->num_acte_mariage }}</strong>
                                        Date de mariage : <strong>{{ date("d/M/Y", strtotime($acteReg->declaration->date_mariage)) }}</strong><br>
                                        Centre d'état civil de mariage : <strong>{{ $acteReg->declaration->cec_mariage }}</strong><br>
                                        @endif
                                        {{-- Fils de : <strong>{{ $acteReg->declaration->pere->nom }}</strong><br>
                                        Et de : <strong>{{ $acteReg->declaration->mere->nom }}</strong> --}}
                                    </div><br><br><br>
                                    Le déclarant,

                                    <div class="col-xl-12" style="margin-left: 100px">
                                        <p>Fait à {{ ucfirst(strtolower(trans($acteReg->declaration->institutionUser->institution->lieu->localiteParent->lib_localite)))}}, le {{date("d-m-Y", strtotime( $acteReg->date_emission))}}<br>L'Officier de l'état civil</p>
                                        @if ($acteReg->approbation_pompe_funebre != "")
                                            <img src='{{ asset("app/".$acteReg->signature_pompe_funebre) }}'><br>
                                            {{ $acteReg->signataire->user->personne->nomcomplet() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                <br><br><br><br><br>
                                @php
                                // Récupérer la position depuis les 8 derniers caractères du code_acte_deces
                                // Les 8 derniers caractères du code_acte_deces représentent la position dans le registre
                                $positionActe = (int) substr($acteReg->code_acte_deces, -8);
                                @endphp
                                <strong> {{$positionActe.'/'.$acteReg->registre->nombre_acte_transcrit}} </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- / page 3 -->
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
                       <div class="card registre-livret-doc-inner" style="height: 670px; border: 2px solid; text-align: left; font-size: 18px">
                           <br>
                           <h2>
                               Nous <strong>{{ $nomcompletcec }}</strong>, officier de l'état-civil de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                               arrêtons et clôturons le présent registre de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                               comprenant <strong>{{ \App\Sifec\Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong> actes  inscrits du numero <strong>1</strong> au numero <strong>{{ \App\Sifec\Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong>
                               inclus et <strong>{{ \App\Sifec\Sifec::format_nombre(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit),2) }}</strong> feuillets restants.<br><br>
                               A <strong>{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->updated_at)) }}</strong><br><br>
                               {{-- (Signature de l'officier d'état civil). --}}
                               <img src='{{ asset("app/".$registre->signature_cloture_cec) }}' alt="signature_clôture">
                           <br>
                           </h2>

                       </div>
                   </div>
               </div>
           </div>
           @endif

        </div>

        <div class="registre-livret-footer d-flex flex-column align-items-center w-100">
            <nav id="btn_footer">
                <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
                <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
                <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
                <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
            </nav>
            <div class="text-muted small registre-livret-footer-hint">
                <i class="fas fa-keyboard me-1"></i> Flèches gauche / droite pour feuilleter
            </div>
        </div>
        </div>
    </div>
</div><!-- /container -->

</div>

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
