
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
            {{-- @php
            $acte = App\Sifec\Sifec::acteNaissance($acte->declaration->code_declaration_naissance);
            @endphp --}}
            <div class="bb-item">
                <div class="row">

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            {{-- <div class="card-header">
                              <strong> Acte de naissance de :  {{$acte->declaration->enfant->nom}} {{$acte->declaration->enfant->prenom}} </strong>
                            </div> --}}
                            <div class="card-body">

                                <div class="row" style="font-size: 12px">
                                    <div class="col-sm-5">

                                       @php
                                            $departement = "";
                                        $communeDistrict = "";
                                        $institution = $acte->declaration->institutionUser->institution;
                                        $libInstitution = $institution->lib_institution;
                                        $localisation = Auth::user()->affectationActive()->institution->lieu->lib_localite;

                                        $infos = "";
                                        if($acte->declaration->type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                                            $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DU PROCUREUR DE LA REPUBLIQUE N° '.$acte->declaration->numero_req.' /2022';
                                        }

                                        if($acte->declaration->type_declaration == "CERTIFICAT DE NON INSCRIPTION"){
                                            $infos = 'ACTE RECONSTITUE SUIVANT REQUISITION DE DECLARATION TARDIVE N° '.$acte->declaration->numero_req.' /2022';
                                        }

                                        if($acte->declaration->type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
                                            $infos = 'ACTE TRANSCRIT SUIVANT REQUISITION  N° '.$acte->declaration->numero_req.' /2022';
                                        }

                                        if($acte->declaration->type_declaration == "CERTIFICAT DE CONSTATATION DE DECES"){
                                            $infos = 'ACTE EMIS SUIVANT LA CONSTATATION  N° '.$acte->declaration->numero_certificat.' /2022 DU MEDECIN '.$acte->declaration->nom_medecin;
                                        }

                                        // if ($institution->code_arrondissement != NULL) {
                                        //     $communeDistrict = "COMMUNE DE ".$institution->arrondissement->commune->lib_commune;
                                        //     $departement  = "DEPARTEMENT DE ". $institution->arrondissement->commune->departement->lib_departement;
                                        //     $localisation = $institution->arrondissement->commune->lib_commune;
                                        // }

                                        // if ($institution->code_commune != NULL) {
                                        //     $communeDistrict = "COMMUNE DE ".$institution->commune->lib_commune;
                                        //     $departement  = "DEPARTEMENT DE ". $institution->commune->departement->lib_departement;
                                        //     $localisation = $institution->commune->lib_commune;
                                        // }

                                        // if ($institution->code_communaute_urbaine != NULL) {
                                        //     $communeDistrict = "DISTRICT DE ".$institution->communauteUrbaine->district->lib_district;
                                        //     $departement  = "DEPARTEMENT DE ". $institution->communauteUrbaine->district->departement->lib_departement;
                                        //     $localisation = $institution->communauteUrbaine->district->lib_district;
                                        // }

                                        // if ($institution->code_district != NULL) {
                                        //     $communeDistrict = "DISTRICT DE ".$institution->district->lib_district;
                                        //     $departement  = "DEPARTEMENT DE ". $institution->district->departement->lib_departement;
                                        //     $localisation = $institution->communauteUrbaine->district->lib_district;
                                        // }
                                        @endphp

                                        <p>
                                            <span>
                                                <strong>{{ $departement }}</strong><br>
                                                {{-- <strong>{{ $communeDistrict }}</strong><br> --}}
                                            </span>
                                            <span><strong>{{$acte->institutionUser->institution->lib_institution}}</strong></span>
                                        </p>
                                    </div>

                                    <div class="col-sm-2">
                                        @if ($acte->approbation_tribunal == 1)
                                            <img src='{{ asset("app/".$acte->sceau_tribunal) }}' alt="" width="100" height="100">
                                        @endif
                                    </div>

                                    <div class="col-sm-5">
                                        <strong>REPUBLIQUE DU CONGO</strong><br>
                                        Unit&eacute; * Travail * Progr&egrave;s
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-12">
                                        <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}}</strong> Registre: <strong> {{ $acte->registre->getcode() }} </strong> Acte n°: <strong>{{ $acte->numeroActe->numero_acte }}</strong>
                                        <br><br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                        @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                                        L'Officier du centre d'état civil de: <strong> {{ $acte->declaration->institutionUser->institution->lib_institution }}</strong>

                                        @else
                                        Consulat du Congo en République Démocratique du Congo
                                        @endif
                                          <br>

                                        Est informé que le : <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong><br>
                                        Est né(e), un enfant de sexe: <strong>{{$acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin" }}</strong><br>
                                        @if($acte->declaration->type_declarant == "Personne physique")
                                        Nom(s): <strong>{{ $acte->declaration->enfant->nom }}</strong><br>
                                        @endif
                                        Prénom(s): <strong>{{ $acte->declaration->enfant->prenom }}</strong><br>
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
                                    <p>Fait à {{$localisation}}, le {{date("d-m-Y", strtotime( $acte->date_emission))}}<br>
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
                                <strong> {{substr($acte->numeroActe->numero_acte,10)."/".$acte->registre->nombre_acte_transcrit}} </strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- / page 2 -->

        </div>
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
