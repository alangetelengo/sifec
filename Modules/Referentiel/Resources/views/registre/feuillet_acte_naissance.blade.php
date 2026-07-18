
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

        .registre-acte-qrcode svg {
            display: block;
            max-width: 100%;
            height: auto;
        }
    </style>

@endsection

@section("corps")

<div class="main clearfix">
    <div class="bb-custom-wrapper">

        <div id="bb-bookblock" class="bb-bookblock">

            <!-- page 2 -->
            {{-- @php
            $acte = \App\Sifec\Sifec::acteNaissance($acte->declaration->code_declaration_naissance);
            @endphp --}}
            <div class="bb-item">
                <div class="row position-relative">
                    <!-- Reliure spirale noire -->
                    <div class="spiral-binding"></div>

                    <div class="col-sm-6">
                        <div class="card" style="height: 670px; border: 2px solid">
                            {{-- <div class="card-header">
                              <strong> Acte de naissance de :  {{$acte->declaration->enfant->nom}} {{$acte->declaration->enfant->prenom}} </strong>
                            </div> --}}
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

                                               if($acte->declaration->type_declaration == "DECLARATION DE NAISSANCE" && ($acte->declaration->numero_req != 0 || ($acte->declaration->type_declaration_origine ?? '') === 'DECLARATION TARDIVE DE NAISSANCE')){
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
                                        {{-- <br> Année: <strong>{{date("Y", strtotime($acte->created_at))}}</strong> Registre: <strong> {{ $acte->registre->getcode() }} </strong> Acte n°: <strong>{{ $acte->numeroActe->numero_acte }}</strong>
                                        <br><br> --}}
                                        <br> <strong>ACTE DE NAISSANCE  <br> N°: <span style="color: red">{{ $acte->niupp }}  R.A.N {{ $acte->registre->created_at->format('Y') }} </span> </strong>
                                        <br><br>
                                    </div>
                                    <div class="col-xl-12" style="text-align:left; font-size:14px">
                                        @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                                        L'Officier du centre d'état civil de: <strong> {{ $acte->declaration->institutionUser->institution->lib_institution }}</strong>

                                        @else
                                        Consulat du Congo en République Démocratique du Congo
                                        @endif
                                          <br>

                                        Est informé que le : <strong> {{ \App\Sifec\Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))." ". \App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".\App\Sifec\Sifec::asLetters((int)date("H", strtotime( $acte->declaration->date_heure_naissance))). " heure(s) ".\App\Sifec\Sifec::asLetters((int)date("i", strtotime( $acte->declaration->date_heure_naissance))) }} minute(s)</strong><br>
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
                                        Né le : <strong> {{ $acte->declaration->pere ?  \App\Sifec\Sifec::asLetters((int)date("d",strtotime($acte->declaration->pere->date_naissance)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) : $dummy }}</strong><br>
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
                                        Né le : <strong> {{ $acte->declaration->mere ?  \App\Sifec\Sifec::asLetters((int)date("d",strtotime($acte->declaration->mere->date_naissance)))." ".\App\Sifec\Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) ." ". \App\Sifec\Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) : $dummy }}</strong><br>
                                        A : <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy }}</strong><br>
                                        Nationalité: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }}</strong><br>
                                        Niveau d'instruction: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }}</strong><br>
                                        Domicilié au : <strong>{{ $acte->declaration->mere ?  $acte->declaration->mere->adresse." / ".$acte->declaration->mere->lieu_naissance : $dummy }}</strong><br>
                                        Proféssion: <strong>{{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }}</strong><br>
                                        @if($acte->declaration->type_declarant == "Personne physique")
                                        Nombre d'enfant nés vivant y compris celui-ci : <strong>{{ (int)$acte->declaration->nombre_enfant + 1 }}</strong><br>
                                        @endif
                                    </div><br><br>
                                    <div class="col-xl-12" style="margin-top: 28px;">
                                        <div class="d-flex align-items-start justify-content-between" style="gap: 12px; width: 100%;">
                                            <div style="min-width: 90px; padding-top: 8px;">Le déclarant,</div>
                                            <div class="text-center">
                                                @include('referentiel::registre.partials.acte-naissance-qrcode', ['acte' => $acte])
                                            </div>
                                            <div class="text-end" style="min-width: 200px; flex: 1;">
                                                <p class="mb-1">
                                                    Fait à {{ ucfirst(strtolower(trans($communeDistrict->lib_localite)))}}, le {{utf8_encode(strftime("%d %B %Y", strtotime(date($acte->date_emission))))}}<br>
                                                    @if( Auth::user()->affectationActive()->institution->code_institution != "INS_0170")
                                                        L'Officier de l'Etat Civil
                                                    @else
                                                        Consule
                                                    @endif
                                                </p>
                                                @if ($acte->approbation_mairie != "")
                                                    @php
                                                        $sigRel = ltrim(str_replace('\\', '/', (string) ($acte->signature_mairie ?? '')), '/');
                                                        if (str_starts_with($sigRel, 'app/')) {
                                                            $sigRel = substr($sigRel, 4);
                                                        }
                                                        $sigUrl = ($sigRel !== '' && is_file(public_path('app/'.$sigRel)))
                                                            ? asset('app/'.$sigRel)
                                                            : null;
                                                    @endphp
                                                    @if ($sigUrl)
                                                        <img src="{{ $sigUrl }}" alt="" style="max-height: 56px; max-width: 120px;"><br>
                                                    @endif
                                                    <strong>{{ $acte->signataire?->user?->personne?->nomcomplet() }}</strong>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-center mb-0 mt-3" style="font-size: 10px; color: #5a3d1e; line-height: 1.25;">
                                            Cet acte de naissance est un document officiel de l'état civil de la République du Congo. Toute falsification ou usage frauduleux est puni par la loi.
                                        </p>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-12" style="padding-left: 500px">
                                <br>
                                @php
                                // Récupérer la position depuis les 4 derniers caractères du niupp (code_acte)
                                // Les 4 derniers caractères du code_acte représentent la position dans le registre
                                $positionActe = (int) substr($acte->niupp, -4);
                                @endphp
                                <strong> {{$positionActe.'/'.$acte->registre->nombre_acte_transcrit}} </strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- / page 2 -->

        </div>
        @if($acte->registre)
        <a href="{{ route('registre.naissance', $acte->registre->code_registre) }}" class="btn btn-primary mb-2" style="float: right; margin-top: 20px;">
            <i class="fas fa-arrow-left me-1"></i>
            Retour au registre
        </a>
        @endif
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
