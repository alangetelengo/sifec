
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
                    <div class="col-sm-5">
                        <div class="card" style="height: 670px; border: 2px solid">
                            <div class="card-body">
                                <p class="card-text" style="margin-top: 100px">
                                    <h2><strong> C.E.C :  {{  $registre->institutionUser->institution->lib_institution}} </strong></h2>
                                    <h2><strong> {{$registre->lib_registre}} </strong></h2>
                                    <h2><strong> Numero du registre : {{$registre->getcode() }} </strong></h2>
                                    <h2><strong> Nombre d'actes : {{$registre->nombre_acte_transcrit."/".$registre->nombre_acte_prevu }} </strong></h2>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card" style="height: 670px; border: 2px solid">
                            @if (count($actes) > 0 )

                            <div class="card-header border-0 pb-0">
                                <h3><strong> Liste des actes de mariage du registre </strong></h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display table table-bordered table-hover table-striped">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Page</th>
                                                <th>Epoux</th>
                                                <th>Epouse</th>
                                                <th>En Date</th>
                                                <th>Voir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $i=1;
                                            @endphp
                                            @foreach ($actes as $act)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{$act->declaration->epoux->nomcomplet()}}</td>
                                                <td>{{$act->declaration->epouse->nomcomplet()}}</td>
                                                <td>{{ date("d/m/Y", strtotime($act->updated_at))}}</td>
                                                <td class="text-center">
                                                    <a href="{{route('registreMariage.show', $act->code_acte_mariage )}}" title="Consulter" target="_blank"><i class="fas fa-eye"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div>
                            <img src='{{ asset("app/".$registre->sceau) }}' alt="" width="100" height="100">
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
                                        <p><strong style="font-size: 100%;">ACTE DE MARIAGE</strong><br> Année : <strong>{{date("Y", strtotime($acte->created_at))}}</strong> Registre : <strong>  {{ $acte->registre->lib_registre }}   </strong><br> Acte n°: <strong>{{ $acte->code_acte_mariage }}</strong> Du : <strong>{{date("d/m/Y", strtotime($acte->created_at))}}</strong></p>
                                        <br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:12px">
                                        Centre d’état-civil : {{ $inst }} <br>
                                        <strong>{{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong> <br>
                                        Par devant nous {{ $acte->institutionUser->user->personne->nomcomplet() }} Officier de l’Etat Civil ont comparu publiquement : <br>
                                        <span style="margin-left: 50px;"><strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong></span> <br>
                                        Né le <strong>{{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->epoux->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epoux->date_naissance)))}}</strong>, à <strong>{{ $acte->declaration->epoux->lieu_naissance }}</strong> <br>
                                        Acte de naissance n° <strong>{{ $acte->declaration->numero_acte_naissance_epoux }}</strong> du <strong>{{ date("d-m-Y", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) }}</strong> <br>
                                        Nationalité : <strong>{{ $acte->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpoux->lib_profession }}</strong> <br>
                                        Domicilié : <strong>{{ $acte->declaration->epoux->adresse }}</strong> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpoux->lib_situation_matrimoniale }}</strong> <br>
                                        Fils de : <strong>{{ $acte->declaration->pere_epoux }}</strong> <br>
                                        Et de : <strong>{{ $acte->declaration->mere_epoux }}</strong> <br>
                                        <span style="margin-left: 50px;">Et <strong>Mlle {{ $acte->declaration->epouse->nomcomplet() }}</strong></span> <br>
                                        Née le <strong>{{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->epouse->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epouse->date_naissance)))}}</strong> , à <strong>{{ $acte->declaration->epouse->lieu_naissance  }}</strong> <br>
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
                                            {{-- @php
                                                $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
                                                $nomcomplet = "";
                                                if ($f != null) {
                                                    $nomcomplet = $f->user->personne->nomcomplet();
                                                }
                                            @endphp
                                            @if ($acte->approbation_mairie == 1)
                                                <img src='{{ asset("app/".$acte->signature_maire) }}' width="100" height="100">
                                                {{ $nomcomplet }}
                                            @endif --}}

                                            @if ($acte->approbation_mairie != "")
                                                <img src='{{ asset("app/".$acte->signature_maire) }}'><br>
                                                {{ $acte->signataire->user->personne->nomcomplet() }}
                                            @endif

                                            <div style="font-size:10px">
                                                <i><strong>CONDITIONS DE MARIAGE</strong></i> <br> Les futurs époux déclarent expressément opter pour la <strong>{{ $acte->declaration->optionMariage->lib_option_mariage }}</strong> et se marie sous le régime matrimonial de <strong>{{ $acte->declaration->regime->lib_regime }}</strong>.
                                                La dot: Cinquante Mille Francs (50.000 Frs) CFA versés à M. <strong>{{ $acte->declaration->chef_famille }}</strong> , {{ $acte->declaration->filiation->lib_filiation }} de la mariée *
                                                Coutume présidant à l'union: Congolaise*
                                                Stipulation particulières en date du <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> l'époux déclare expressément le <strong> {{ date("d", strtotime($acte->declaration->date_prevue_mariage)) ." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) ." ".date("Y", strtotime($acte->declaration->date_prevue_mariage)) }} </strong> renonce à prendre une
                                                seconde épouse tant que le présent mariage n'aura pas été dissout par un jugement de divorce ou le décès de sa conjointe
                                                (Article 179 du code de la famille)
                                            </div>
                                       </div>
                                    </div>

                                    <div class="col-xl-12">
                                      {{-- <strong> {{$i++}} </strong> --}}
                                      <strong> {{substr($acte->numeroActe->numero_acte,11)."/".$registre->nombre_acte_prevu}} </strong>
                                    </div>
                                </div>
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
               <div class="row">
                   <div class="col-sm-11">
                       <div class="card" style="height: 670px; border: 2px solid; text-align: left; padding: 20px; font-size: 18px">
                           <br><br><br><br>
                           <h2>
                               Nous <strong>{{ $nomcompletcec }}</strong>, officier de l'Etat-civil de <strong>{{ $registre->institutionUser->institution->lib_institution }}</strong><br>
                               arrêtons et clôturons le présent registre de <strong>{{ $registre->typeRegistre->lib_type_registre }}</strong><br>
                               comprenant <strong>{{ \App\Sifec\Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong> actes  inscrits du numero <strong>01</strong> au numero <strong>{{ \App\Sifec\Sifec::format_nombre($registre->nombre_acte_transcrit,2) }}</strong><br>
                               inclus et <strong>{{ \App\Sifec\Sifec::format_nombre(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit),2) }}</strong> feuilles perdus ou annulés.<br><br>
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
