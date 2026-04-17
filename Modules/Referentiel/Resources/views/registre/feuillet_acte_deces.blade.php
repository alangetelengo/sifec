
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

    <!-- Styles pour la reliure spirale noire -->
    <style>
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

<div class="main clearfix">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">

            <!-- page 2 -->
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
                                        Unit&eacute; * Travail * Progr&egrave;s
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <p><strong style="font-size: 100%;">ACTE DE DECES</strong><br> N° : <strong style="color: red">{{ $acteReg->code_declaration_deces }}</strong> Du : <strong>{{date("d/m/Y", strtotime($acteReg->updated_at))}}</strong></p>
                                        <br>
                                    </div>

                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                    Centre d'état civil secondaire :  <strong> {{ $acteReg->institutionUser->institution->lib_institution }}</strong><br>
                                    le: <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime( $acteReg->declaration->date_heure_declaration)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($acteReg->declaration->date_heure_declaration))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acteReg->declaration->date_heure_declaration)))." à ".date("H", strtotime($acteReg->declaration->date_heure_declaration)). " heure(s) ".date("s", strtotime($acteReg->declaration->date_heure_declaration)) }} minutes</strong><br>
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
                                    </div><br>
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
                {{-- <img class="background" src="{{asset("carte/images/bg/siprale2.gif") }}" alt="siprale"  width="30"/> --}}
            </div>
            <!-- / page 2 -->

        </div>
        @if($acteReg->registre)
        <a href="{{ route('registre.deces', $acteReg->registre->code_registre) }}" class="btn btn-primary mb-2" style="float: right; margin-top: 20px;">
            <i class="fas fa-arrow-left me-1"></i>
            Retour au registre
        </a>
        @endif

        {{-- <nav id="btn_footer">
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
        </nav> --}}

    </div>
</div><!-- /container -->

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
