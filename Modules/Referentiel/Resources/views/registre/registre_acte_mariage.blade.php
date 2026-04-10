
@extends("layout.app")
@section("titre")
    Registre de mariages
@endsection

@section("styles")

    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!--- Carte du Congo --->
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/bookblock.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/demo1.css') }}" />
     <!-- css de la carte -->
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
    </style>
@endsection

@section("corps")

<div class="page-sifec-form page-sifec-registre-livret registre-livret-toolbar-unified">
<div class="row mb-0 g-0 registre-livret-toolbar">
    <div class="col-12">
        <div class="registre-livret-toolbar-surface">
            <div class="registre-livret-toolbar-row">
                <div class="flex-grow-1 min-w-0">
                    <h4 class="mb-0"><i class="fas fa-book-open me-2"></i>Registre de mariage</h4>
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
                        <div class="card registre-livret-doc-inner" style="height: 680px; border: 2px solid; text-align: left; font-size: 12px">
                            <h2>
                                {!! $registre->getTexteParapheRegistre('mariage') !!}<br><br>
                            </h2>
                            <h2><span style="margin-left: 730px;"> Fait à <strong>{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->created_at)) }}</strong> </span></h2>
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
                        <div class="card" style="height: 670px; border: 2px solid">
                            @if (count($actes) > 0 )

                            <div class="card-header border-0 pb-0">
                                <h3><strong> Liste des actes de mariage du registre </strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display table table-hover table-striped modern-table">
                                        <thead class="table-header-custom">
                                            <tr class="text-center">
                                                <th><i class="fas fa-file-alt me-2"></i>Page</th>
                                                <th><i class="fas fa-male me-2"></i>Époux</th>
                                                <th><i class="fas fa-female me-2"></i>Épouse</th>
                                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                                <th><i class="fas fa-eye me-2"></i>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $i=1;
                                            @endphp
                                            @foreach ($actes as $act)
                                            <tr class="table-row-hover">
                                                <td class="fw-bold text-primary">{{ $i++ }}</td>
                                                <td class="fw-semibold">{{$act->declaration->epoux->nomcomplet()}}</td>
                                                <td class="fw-semibold">{{$act->declaration->epouse->nomcomplet()}}</td>
                                                <td class="fw-semibold">{{ date("d/m/Y", strtotime($act->updated_at))}}</td>
                                                <td class="text-center">
                                                    <a href="{{route('registre.feuillet.registre.mariage', $act->code_acte_mariage )}}"
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
                            @else
                            <div>
                            <img src='{{ asset("app/".$registre->sceau) }}' alt="">
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <img class="background" src="{{asset("carte/images/bg/siprale2.gif") }}" alt="siprale"  width="30"/> --}}
            </div>

            <!-- / page 2 -->
            @php $i=1; @endphp
            @foreach ($actes as $acte)
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
                                            $localite = "";
                                            $localiteParent = "";
                                            $inst = "";
                                            $institution = $acte->institutionUser->institution;
                                            $localisation = "";

                                            if ($institution->code_arrondissement != NULL) {
                                                $inst = $institution->lib_institution;
                                                $localite = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
                                                $localiteParent  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
                                                $localisation = $institution->arrondissement->commune->lib_commune;
                                            }

                                            if ($institution->code_commune != NULL) {
                                                $inst = "COMMUNE DE ".$institution->commune->lib_commune;
                                                $localite  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
                                                $localisation = $institution->commune->lib_commune;
                                            }

                                            if ($institution->code_communaute_urbaine != NULL) {
                                                $inst = $institution->lib_institution;
                                                $localite = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
                                                $localiteParent  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
                                                $localisation = $institution->communauteUrbaine->district->lib_district;
                                            }

                                            if ($institution->code_district != NULL) {
                                                $inst = $institution->lib_institution;
                                                $localite = "DISTRICT DE ".$institution->district->lib_district;
                                                $localiteParent  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
                                                $localisation = $institution->communauteUrbaine->district->lib_district;
                                            }
                                        @endphp
                                        <p>
                                            <span>
                                                <strong>{{ $localiteParent }}</strong>
                                            </span> <br>
                                             <span>{{ $localite}}</span> <br>
                                            <span>{{ $inst }}</span>
                                        </p>
                                    </div>
                                    <div class="col-sm-2">
                                        @if ($acte->approbation_tribunal == 1)
                                            <img src='{{ asset("app/".$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                                        @endif
                                    </div>

                                    <div class="col-sm-5">
                                        <strong>REPUBLIQUE DU CONGO</strong><br>
                                        Unit&eacute; - Travail - Progr&egrave;s
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <p><strong style="font-size: 100%;">ACTE DE MARIAGE</strong><br> N°: <strong style="color: red">{{ $acte->code_acte_mariage }}</strong> Du : <strong>{{date("d/m/Y", strtotime($acte->created_at))}}</strong></p>
                                        <br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:12px">
                                        Centre d’état civil : {{ $inst }} <br>
                                        <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong> <br>
                                        Par devant nous {{ $acte->institutionUser->user->personne->nomcomplet() }} Officier de l’Etat Civil ont comparu publiquement : <br>
                                        <span style="margin-left: 50px;"><strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong></span> <br>
                                        Né le <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->epoux->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->epoux->date_naissance)))}}</strong>, à <strong>{{ $acte->declaration->epoux->lieu_naissance }}</strong> <br>
                                        Acte de naissance n° <strong>{{ $acte->declaration->numero_acte_naissance_epoux }}</strong> du <strong>{{ date("d-m-Y", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) }}</strong> <br>
                                        Nationalité : <strong>{{ $acte->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpoux->lib_profession }}</strong> <br>
                                        Domicilié : <strong>{{ $acte->declaration->epoux->adresse }}</strong> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpoux->lib_situation_matrimoniale }}</strong> <br>
                                        Fils de : <strong>{{ $acte->declaration->pere_epoux }}</strong> <br>
                                        Et de : <strong>{{ $acte->declaration->mere_epoux }}</strong> <br>
                                        <span style="margin-left: 50px;">Et <strong>Mlle {{ $acte->declaration->epouse->nomcomplet() }}</strong></span> <br>
                                        Née le <strong>{{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->epouse->date_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->epouse->date_naissance)))}}</strong> , à <strong>{{ $acte->declaration->epouse->lieu_naissance  }}</strong> <br>
                                        Acte de naissance n° <strong>{{  $acte->declaration->numero_acte_naissance_epouse  }}</strong> du <strong>{{ date("d-m-Y", strtotime($acte->declaration->date_emission_acte_naissance_epouse)) }}</strong> <br>
                                        Nationalité : <strong>{{ $acte->declaration->epouse->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpouse->lib_profession }}</strong> <br>
                                        Domicilié : <strong>{{ $acte->declaration->epouse->adresse }}</strong> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpouse->lib_situation_matrimoniale }}</strong> <br>
                                        Fille de : <strong>{{ $acte->declaration->pere_epouse }}</strong> <br>
                                        Et de : <strong>{{ $acte->declaration->mere_epouse }}</strong>
                                        Sur notre interpellation, les futurs époux ont déclaré l’un après l’autre vouloir se prendre pour mari et femme et nous avons prononcé au nom <br> de la loi
                                        qu’ils sont unis par le mariage légal en présence de : M. {{ $acte->declaration->chef_famille }} représentant de l’épouse *
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-12" style="text-align:left; font-size:13px">
                                        <i>Témoins de l'époux</i> <br>
                                        Et ce : 1° {{ $acte->declaration->temoinHommeEpoux->nomcomplet() }}, Domicilié: {{ $acte->declaration->temoinHommeEpoux->adresse }}* <br>
                                        2° Mme {{ $acte->declaration->temoinFemmeEpoux->nomcomplet() }} , domicilié: {{ $acte->declaration->temoinHommeEpoux->adresse }}* <br><br>
                                        <i>Témoins de l'épouse</i> <br>
                                        1° {{ $acte->declaration->temoinHommeEpouse->nomcomplet() }}, Domicilié: {{ $acte->declaration->temoinHommeEpouse->adresse }}* <br>
                                        2° Mme {{ $acte->declaration->temoinFemmeEpouse->nomcomplet() }} , domicilié: {{ $acte->declaration->temoinHommeEpouse->adresse }}*<br>
                                        qui, lecture faite nous avons signé le présent acte avec les époux et  les témoins<br>
                                    </div>

                                    <div class="col-xl-12">
                                        L'époux
                                        <span>
                                            @if($acte->declaration->signatureActe !=null)
                                            <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_epoux}}" alt="Base64 Image" width="80" height="60">
                                            @endif
                                        </span>

                                        L'épouse
                                        <span>
                                            @if($acte->declaration->signatureActe !=null)
                                            <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_epouse}}" alt="Base64 Image" width="80" height="60">
                                            @endif
                                        </span><br>

                                         Témoins de l'époux 1
                                        <span>
                                            @if($acte->declaration->signatureActe != null)
                                            <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_premier_epoux}}" alt="Base64 Image" width="80" height="60">
                                             @endif
                                        </span>
                                        Témoins de l'époux 2
                                       <span>
                                        @if($acte->declaration->signatureActe !=null)
                                       <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_deuxieme_epoux}}" alt="Base64 Image" width="80" height="60">
                                       @endif
                                       </span><br>
                                       Témoins de l'épouse 1
                                       <span>
                                        @if($acte->declaration->signatureActe !=null)
                                       <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_premier_epouse}}" alt="Base64 Image" width="80" height="60">

                                       @endif
                                       </span>
                                       Témoins de l'épouse 2
                                      <span>
                                        @if($acte->declaration->signatureActe !=null)
                                        <img src="data:image/png;base64,{{$acte->declaration->signatureActe->signature_temoin_deuxieme_epouse}}" alt="Base64 Image" width="80" height="60">
                                        @endif
                                      </span>

                                       <div class="col-xl-12">
                                            @if ($acte->approbation_mairie != null)
                                                <img src='{{ asset("app/".$acte->signature_maire) }}'>
                                                {{ $acte->signataire->user->personne->nomcomplet() }}
                                            @endif

                                            <div style="font-size:10px">
                                                <i><strong>CONDITIONS DE MARIAGE</strong></i> <br> Les futurs époux déclarent expressément opter pour la <strong>{{ $acte->declaration->optionMariage->lib_option_mariage }}</strong> et se marie sous le régime matrimonial de <strong>{{ $acte->declaration->regime->lib_regime }}</strong>.
                                                La dot: Cinquante Mille Francs (50.000 Frs) CFA versés à M. <strong>{{ $acte->declaration->chef_famille }}</strong> , {{ $acte->declaration->filiation->lib_filiation }} de la mariée *
                                                Coutume présidant à l'union: Congolaise*
                                                Stipulation particulières en date du <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> l'époux déclare expressément le <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> renonce à prendre une
                                                seconde épouse tant que le présent mariage n'aura pas été dissout par un jugement de divorce ou le décès de sa conjointe
                                                (Article 179 du code de la famille)
                                            </div>
                                       </div>
                                    </div>


                                </div>

                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                {{-- <strong> {{$i++}} </strong> --}}
                                {{-- <strong> {{substr($acte->numeroActe->numero_acte,10)."/".count($actes)}} </strong> --}}
                                <strong> {{$i++."/".count($actes)}} </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
             <!-- page last -->
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
                               Nous <strong>{{ $nomcompletcec }}</strong>, officier de l'Etat-civil de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                               arrêtons et clôturons le présent registre de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                               comprenant <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,1) }}</strong> actes  inscrits du numero <strong>1</strong> au numero <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,1) }}</strong>
                               inclus et <strong>{{ Sifec::format_nombre(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit),1) }}</strong> feuillets restants.<br><br>
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
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

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

</script>

@endsection
