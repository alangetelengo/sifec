
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
                <div class="row">
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
                                                        <a href="{{route('registre.feuillet.registre.naissance', $act->niupp )}}" title="Consulter" target="_blank"><i class="fas fa-eye"></i></a>
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

                                            $inst = $institution->lib_institution;
                                            // $localite = " COMMUNE DE ".$institution->lieu->localiteParent->lib_localite;
                                            $localite = " DISTRICT D'".$institution->lieu->lib_localite;

                                            // $localiteParent  = "DEPARTEMENT DE LA CUVETTE";
                                            $localisation = $institution->lieu->localiteParent->lib_localite;

                                            $localiteParent  = "DEPARTEMENT DE LA ". $institution->lieu->localiteParent->localiteParent->lib_localite;

                                          $infos = "";
                                          $tribunal = $acte->declaration->institutionUser->institution->institutionParent->lib_institution;
                                          setlocale(LC_TIME, "fr_FR", "French");


                                          $num = "";
                                          $titre = "";
                                          $top = "";

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


                                        <p style="font-weight:bolder">
                                            <span>
                                                <strong>{{ $localiteParent }}</strong>
                                            </span> <br>
                                            {{-- <span><strong>{{ $localite}}</strong></span> <br> --}}
                                            <span><strong>{{ $inst }}</strong></span>
                                        </p>
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
                                        <br> <strong>ACTE DE NAISSANCE  <br>N°: <span style="color: red">{{ $acte->niupp }}</span> </strong>
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
                                {{-- <strong> {{substr($acte->numeroActe->numero_acte,10) .'/'. count($actesRegistre)}}</strong> --}}
                                <strong> {{$i++.'/'.count($actesRegistre)}}</strong>
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

                           <h2><span style="margin-left: 730px;"> Fait à <strong style="text-transform: capitalize">{{ $registre->institutionUser->institution->lieu->localiteparent->lib_localite }}</strong>, le <strong>{{ date("d-m-Y", strtotime($registre->updated_at)) }}</strong> </span></h2>
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
