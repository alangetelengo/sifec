
@extends("layout.app")
@section("titre")
    Acte de mariage
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

            <!-- page 2 -->
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
                                        <p><strong style="font-size: 150%;">ACTE DE MARIAGE</strong><br> Année : <strong>{{date("Y", strtotime($acte->created_at))}}</strong> Registre : <strong>  {{ $acte->registre->lib_registre }}   </strong><br> Acte n°: <strong>{{ $acte->code_acte_mariage }}</strong> Du : <strong>{{ date("d/m/Y", strtotime($acte->created_at))}}</strong></p>
                                        <br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:12px">
                                        Centre d’état-civil : {{ $inst }} <br>
                                        <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->date_prevue_mariage)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_prevue_mariage))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_prevue_mariage)))}}</strong> <br>
                                        Par devant nous {{ $acte->institutionUser->user->personne->nomcomplet() }} Officier de l’Etat Civil ont comparu publiquement : <br>
                                        <span style="margin-left: 50px;"><strong>M. {{ $acte->declaration->epoux->nomcomplet() }}</strong></span> <br>
                                        Né le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->epoux->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epoux->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epoux->date_naissance)))}}</strong>, à <strong>{{ $acte->declaration->epoux->lieu_naissance }}</strong> <br>
                                        Acte de naissance n° <strong>{{ $acte->declaration->numero_acte_naissance_epoux }}</strong> du <strong>{{ date("d-m-Y", strtotime($acte->declaration->date_emission_acte_naissance_epoux)) }}</strong> <br>
                                        Nationalité : <strong>{{ $acte->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession : <strong>{{ $acte->declaration->professionEpoux->lib_profession }}</strong> <br>
                                        Domicilié : <strong>{{ $acte->declaration->epoux->adresse }}</strong> Situation matrimoniale : <strong>{{ $acte->declaration->situationMatEpoux->lib_situation_matrimoniale }}</strong> <br>
                                        Fils de : <strong>{{ $acte->declaration->pere_epoux }}</strong> <br>
                                        Et de : <strong>{{ $acte->declaration->mere_epoux }}</strong> <br>
                                        <span style="margin-left: 50px;">Et <strong>Mlle {{ $acte->declaration->epouse->nomcomplet() }}</strong></span> <br>
                                        Née le <strong>{{ \App\Sifec\Sifec::jourEnLettres((int)date("d", strtotime($acte->declaration->epouse->date_naissance)))}} {{ \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->epouse->date_naissance))) }} {{ \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->epouse->date_naissance)))}}</strong> , à <strong>{{ $acte->declaration->epouse->lieu_naissance }}</strong> <br>
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
                            {{-- <div class="card-header border-0 pb-0">
                               <a href="{{ route('acte.naissance.display',$acteReg->declaration->code_declaration_naissance) }}" target="_blank"> <button type="button" class="btn btn-sm btn-primary light"><i class="fas fa-print"></i> Imprimer l'acte de naissance de : {{$acteReg->declaration->enfant->nom}} {{$acteReg->declaration->enfant->prenom}}</button></a>
                            </div> --}}
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
                                        @php
                                            $f = $acte->institutionUser->where("code_fonction","FONC_0002")->first();
                                            $nomcomplet = "";
                                            if ($f != null) {
                                                $nomcomplet = $f->user->personne->nomcomplet();
                                            }
                                        @endphp
                                        @if ($acte->approbation_mairie == 1)
                                            <img src='{{ asset("app/".$acte->signature_maire) }}' width="100" height="100">
                                            {{ $nomcomplet }}
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


                                </div>
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
