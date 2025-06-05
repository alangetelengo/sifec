
@extends("layout.app")
@section("titre")
    Registre des {{ $registre->typeRegistre->lib_type_registre }}
@endsection

@section("styles")

    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!--- Carte du Congo --->
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/bookblock.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/demo1.css') }}" />
     <!-- css de la carte -->
     <script src="{{ URL::to('carte/js/modernizr.custom.js') }}"></script>
@endsection

@section("corps")

<div class="main clearfix">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">
            @php
                $pdt = "";
                if ($registre->signataire != null) {
                    $pdt = $registre->signataire->user->personne->nomcomplet();
                }
            @endphp
            <!-- page 1 -->
            @if($registre->sceau != null)
            <div class="bb-item">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card" style="height: 670px; border: 2px solid; text-align: left; padding: 20px; font-size: 15px">
                            <div class="">
                                <img src='{{ asset("app/".$registre->sceau) }}' alt="" width="100" height="100">
                            </div>
                            <br><br>
                            <h2> Nous <strong>{{ $pdt }}</strong>, Président du <strong> {{ $registre->institutionUser->institution->institutionparent->lib_institution }} </strong>,<br>
                            ouvrons le présent registe destiné à l'inscription des actes de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                            au cours de l'année <strong>{{ date("Y", strtotime($registre->created_at)) }}</strong> Du centre d'Etat-civil de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                            Le présent registe est composé de <strong>{{ $registre->nombre_acte_prevu }}</strong> Feuillets numérotés de 1 à <strong>{{ $registre->nombre_acte_prevu }}</strong><br>
                            A <strong>{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->created_at)) }}</strong><br><br>

                            <img src='{{ asset("app/".$registre->signature_tribunal) }}'>
                            <br>
                            Les registres sont clos et arrêtés le 31 Décembre par l'officier de l'Etat-civil. <br><br>
                            La clôture est constatée par un procès-verbal qui est rédigé sur chaque registre <br>
                            immédiatement après le dernier acte. Il annonce le nombre d'actes inscrits.<br></h2>

                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- /page 1 -->

            <!-- page 2 -->
            <div class="bb-item">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="">
                                @if ($registre->sceau != null)
                                    <img src='{{ asset("app/".$registre->sceau) }}' alt="" width="100" height="100">
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="card-text" style="margin-top: 100px">
                                    <h2><strong> C.E.C :  {{  $registre->institutionUser->institution->lib_institution}} </strong></h2>
                                    <h2><strong> {{$registre->lib_registre}} </strong></h2>
                                    <h2><strong> Numero du registre : {{$registre->getcode() }} </strong></h2>
                                    <h2><strong> Nombre d'acte : {{$registre->nombre_acte_transcrit."/".$registre->nombre_acte_prevu }} </strong></h2>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            @if (count($actesRegistre) > 0 )
                                <div class="card-header border-0 pb-0">
                                    <h3><strong> Liste des actes de naissance du registre </strong></h3>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="display table table-bordered table-hover table-striped">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Feuillet</th>
                                                    <th>Nom</th>
                                                    <th>Prenom</th>
                                                    <th>Sexe</th>
                                                    <th>Voir</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                $i=1;
                                                @endphp
                                                @foreach ($actesRegistre as $act)
                                                <tr>
                                                    {{-- <td>{{ substr($act->numeroActe->numero_acte,12)}}</td> --}}
                                                    <td>{{ $i++}}</td>
                                                    <td>{{$act->declaration->enfant->nom}}</td>
                                                    <td>{{$act->declaration->enfant->prenom}}</td>
                                                    <td>{{$act->declaration->enfant->sexe}}</td>
                                                    <td class="text-center">
                                                        <a href="{{route('livretNaissance.show', $act->niupp )}}" title="Consulter" target="_blank"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- @else
                                <div>
                                <img src='{{ asset("app/".$registre->sceau) }}' alt="" width="100" height="100">
                                </div> --}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- / page 2 -->
            {{-- @php $i=1; @endphp --}}
            @php $i=1; @endphp
            @foreach ($actesRegistre as $acte)

            <div class="bb-item">
                <div class="row">

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
                                        Unit&eacute; * Travail * Progr&egrave;s
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12">
                                        <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}}</strong> Registre: <strong> {{ $acte->registre->getcode() }} </strong> Acte n°: <strong>{{ $acte->numeroActe->numero_acte }}</strong>
                                        <br><br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                        L'Officier du centre d'état civil de: <strong> {{ $acte->declaration->institutionUser->institution->lib_institution }}</strong><br>
                                        Est informé que le : <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong><br>
                                        Est né(e), un enfant de sexe: <strong>{{$acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin" }}</strong><br>
                                        Nom(s): <strong>{{ $acte->declaration->enfant->nom }}</strong><br>
                                        Prénom(s): <strong>{{ $acte->declaration->enfant->prenom }}</strong><br>
                                        Lieu de naissance: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong><br>
                                        Déclaré par: <strong>{{$acte->declaration->declarant->nom. " ".$acte->declaration->declarant->prenom }}</strong><br>
                                        Filiation: <strong>{{$acte->declaration->declarant ? $acte->declaration->filiation->lib_filiation : $dummy }}</strong><br>
                                        Situation matrimoniale des parents: <strong>{{$acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy }}</strong><br>
                                        Fils de:<strong> {{$acte->declaration->pere ? $acte->declaration->pere->nom." ".$acte->declaration->pere->prenom : $dummy }}</strong><br>
                                        Né le : <strong> {{ $acte->declaration->pere ?  Sifec::asLetters((int)date("d",strtotime($acte->declaration->pere->date_naissance)))." ".Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) : $dummy }}</strong><br>
                                        A : <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->lieu_naissance : $dummy }}</strong><br>
                                        Nationalité: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy}}</strong><br>
                                        Niveau d'instruction: <strong>{{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy}}</strong><br>
                                        Domicilié à : <strong>{{$acte->declaration->pere ? $acte->declaration->pere->adresse." / ".$acte->declaration->pere->lieu_naissance : $dummy }}</strong><br>
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
                                        Domicilié à : <strong>{{ $acte->declaration->mere ?  $acte->declaration->mere->adresse." / ".$acte->declaration->mere->lieu_naissance : $dummy }}</strong><br>
                                        Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong><br>
                                    </div><br><br>
                                    Le déclarant,
                                    <div class="col-xl-12" style="margin-left: 100px">
                                    <p>Fait à {{$localisation}}, le {{date("d-m-Y", strtotime( $acte->date_emission))}}<br>L'Officier de l'Etat Civil</p>
                                        @if ($acte->approbation_mairie != "")
                                            <img src='{{ asset("app/".$acte->signature_mairie) }}'><br>
                                            {{ $acte->signataire->user->personne->nomcomplet() }}
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                <br><br><br><br><br>
                            <strong> {{substr($acte->numeroActe->numero_acte,11)."/".$registre->nombre_acte_prevu}} </strong>
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
               <div class="row">
                   <div class="col-sm-11">
                       <div class="card" style="height: 670px; border: 2px solid; text-align: left; padding: 20px; font-size: 18px">
                           <br><br><br><br>
                           <h2>
                               Nous <strong>{{ $nomcompletcec }}</strong>, officier de l'Etat-civil de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                               arrêtons et clôturons le présent registre de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                               comprenant <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong> actes  inscrits du numero <strong>01</strong> au numero <strong>{{ Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong><br>
                               inclus et <strong>{{ Sifec::format_nombre(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit),2) }}</strong> feuilles perdus ou annulés.<br><br>
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

        <nav id="btn_footer">
            <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a>
            <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a>
            <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a>
            <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a>
        </nav>

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
