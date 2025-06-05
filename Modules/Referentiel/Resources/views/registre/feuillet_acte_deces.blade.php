
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
@endsection

@section("corps")

<div class="main clearfix">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">

            <!-- page 2 -->
            <div class="bb-item">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">

                                <div class="row" style="font-size: 12px">
                                    <div class="col-sm-5">

                                       @php
                                            $departement = "";
                                        $communeDistrict = "";
                                        $institution = $acteReg->declaration->institutionUser->institution;
                                        $libInstitution = $institution->lib_institution;

                                        $infos = "";
                                        if($acteReg->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                                            $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acteReg->declaration->numero_req.' /2022';
                                        }

                                        if($acteReg->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
                                            $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acteReg->declaration->numero_req.' /2022';
                                        }

                                        if($acteReg->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
                                            $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$acteReg->declaration->numero_req.' /2022';
                                        }

                                        if($acteReg->declaration->type_declaration == "CERTIFICAT DE CONSTATATION DE DECES"){
                                            $infos = 'ACTE EMIS SUIVANT LA CONSTATATION  N° '.$acteReg->declaration->numero_certificat.' /2022 DU MEDECIN '.$acteReg->declaration->nom_medecin;
                                        }

                                        if ($institution->code_arrondissement != NULL) {
                                            $communeDistrict = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
                                            $departement  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
                                            $localisation = $institution->arrondissement->commune->lib_commune;
                                        }

                                        if ($institution->code_commune != NULL) {
                                            $communeDistrict = "COMMUNE DE ".$institution->commune->lib_commune;
                                            $departement  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
                                            $localisation = $institution->commune->lib_commune;
                                        }

                                        if ($institution->code_communaute_urbaine != NULL) {
                                            $communeDistrict = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
                                            $departement  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
                                            $localisation = $institution->communauteUrbaine->district->lib_district;
                                        }

                                        if ($institution->code_district != NULL) {
                                            $communeDistrict = "DISTRICT DE ".$institution->district->lib_district;
                                            $departement  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
                                            $localisation = $institution->communauteUrbaine->district->lib_district;
                                        }
                                        @endphp

                                        <p>
                                            <span>
                                                {{-- <strong>{{ $departement }}</strong><br> --}}
                                                <strong>{{ $communeDistrict }}</strong><br>
                                            </span>
                                            <span><strong>{{$acteReg->institutionUser->institution->lib_institution}}</strong></span>
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
                                        <p><strong style="font-size: 150%;">ACTE DE DECES</strong><br> Année : <strong>{{date("Y", strtotime($acteReg->declaration->date_heure_declaration))}}</strong> Acte n°:<strong>{{ $acteReg->code_acte_deces }}</strong> Du: <strong>{{date("d/m/Y", strtotime($acteReg->declaration->date_heure_declaration))}}</strong></p>
                                        <br>
                                    </div>

                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                    Centre d'état civil communal :  <strong> {{ $acteReg->institutionUser->institution->lib_institution }}</strong><br>
                                    le: <strong> {{ Sifec::asLetters((int)date("d", strtotime( $acteReg->declaration->date_heure_declaration)))." ".Sifec::mois(date("m", strtotime($acteReg->declaration->date_heure_declaration))) ." ". Sifec::asLetters(date("Y", strtotime($acteReg->declaration->date_heure_declaration)))." à ".date("H", strtotime($acteReg->declaration->date_heure_declaration)). " heure(s) ".date("s", strtotime($acteReg->declaration->date_heure_declaration)) }} minutes</strong><br>
                                    S'est présenté(e) <strong> {{ $acteReg->declaration->declarant->nom.' '.$acteReg->declaration->declarant->prenom }}</strong>, &ensp; Filiation: <strong>{{ $acteReg->declaration->filiation->lib_filiation }} </strong><br>
                                    Domicilié(e) : <strong>{{ $acteReg->declaration->declarant->adresse }}</strong><br>
                                    qui a déclaré le décès de : <b>{{ $acteReg->declaration->defunt->nom." ".$acteReg->declaration->defunt->prenom }} </b><br>
                                    Lieu de décès : <strong> {{ $acteReg->declaration->lieu_deces }} </strong><br>
                                    Cause du décès :


                                        @php
                                            $causesd = $acteReg->declaration->DDecesCauses;
                                            $v = "";
                                        @endphp
                                        <strong>
                                            @if ($causesd != NULL)
                                                @foreach ($causesd as $item)
                                                    {{$v.$item->causeDeces->lib_cause_deces}}
                                                    @php
                                                        $v = ", ";
                                                    @endphp
                                                @endforeach
                                            @endif
                                        </strong><br>

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
                                        <p>Fait à {{$localisation}}, le {{date("d-m-Y", strtotime( $acteReg->date_emission))}}<br>L'Officier de l'état civil</p>
                                        @if ($acteReg->approbation_pompe_funebre != "")
                                            <img src='{{ asset("app/".$acteReg->signature_pompe_funebre) }}'><br>
                                            {{ $acteReg->signataire->user->personne->nomcomplet() }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                <br><br><br><br><br>
                            <strong> {{substr($acteReg->numeroActe->numero_acte,10)."/".$acteReg->registre->nombre_acte_transcrit}} </strong>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- <img class="background" src="{{asset("carte/images/bg/siprale2.gif") }}" alt="siprale"  width="30"/> --}}
            </div>
            <!-- / page 2 -->

        </div>

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
