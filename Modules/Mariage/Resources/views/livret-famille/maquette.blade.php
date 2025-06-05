@extends("layout.app")
@section("titre")
    Livret de famille
@endsection

@section("styles")

    <link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

    <!--- Carte du Congo --->
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/bookblock.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/demo1.css') }}" />
     <!-- css de la carte -->
     {{-- <link rel="stylesheet" type="text/css" href="{{ URL::to('carte/css/map.css') }}" /> --}}
     <style>
        .bb-custom-wrapper {
        width:90%;
        /* position:relative; */
        /* margin:0 auto 40%; */
        }

     </style>
     <script src="{{ URL::to('carte/js/modernizr.custom.js') }}"></script>
@endsection

@section("corps")
@php
    $f = $am->institutionUser->where("code_fonction","FONC_0002")->first();
    $nomcomplet = "";
    if ($f != null) {
        $nomcomplet = $f->user->personne->nomcomplet();
    }
@endphp
@php
setlocale(LC_TIME, "fr_FR", "French");

$localite = "";
$localiteParent = "";
$inst = "";
$institution = $am->institutionUser->institution;
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

<div class="main clearfix">
    <div class="bb-custom-wrapper">
        <div class="row" style="margin-right: 10%;"></div>
        <div id="bb-bookblock" class="bb-bookblock">
            <div class="bb-item">
                <div class="row">
                    <div class="col-6"></div>
                    <div class="col-6" style="text-align:center;">
                        <p>REPUBLIQUE DU CONGO <br> <small>Unité - Travail - Progrès</small></p> <br><br>
                        <div><img src="{{ asset('tpl/armoirie_congo.jpg') }}" width="60%"></div>
                        <h2>LIVRET DE FAMILLE <br> {{ $am->declaration->epoux->nomcomplet() }}</h2>
                    </div>
                </div>
            </div>
            <div class="bb-item">
                <div class="row">
                    <div class="col-6">
                        <span style="padding-top: 50%;">(1) Le détenteur du livret ne devra pas manquer de faire reporter les mentions de l'acte de mariage par l'Officier de l'Etat Civil compétent.</span> </div>
                    <div class="col-6"> <p>LIVRET DE FAMILLE <br> <small>LOI N° 073/84 du 17 Octobre 1984 Portant code de famille </small></p> <h4>Délivrance et tenue du Livret de Famille </h4><br> <small style="padding-right: 4%; text-align: justify!important;"> Au moment de l'établissement de l'acte de mariage, il est remis gratuite-ment à l'époux un livret de famille portant l'indication de l'identité des époux. La date et lieu de célébration du mariage et le cas échéant, des options souscrites par chacun des époux. Cette première page est signée par l'Officier de l'Etat Civil et les conjoints sils savent ou mention est faite de cause qui a empêché ces derniers ou l'un d'eux de signer. Sur les pages suivantes sont inscrites : les naissances et décès des enfants, les adoptions, les décès, divorces ou séparations de corps, des époux. <br> <br> Au cas ou l'acte de l'Etat Civil est rectifié, il doit être fait mention sur le livret. I Chacune des mentions doit être approuvée par l'Officier de l'Etat Civil et revêtue de son sceau. Délivrance d'un second livret de fa m ille.11 peut être délivré un second livret de famille : <br><br> - En cas de perte, le nouveau livret portera la mention "DUPLICATA';<br> - En cas de divorce ou séparation de corps sur présentation de l'ancien livret par la femme, on remet une copie conforme.<br> </small> </div>
                </div>
            </div>
            <div class="bb-item">
                <div class="row">
                    <div class="col-6">
                         <h4>Du lien Matrimonial</h4> <small style="padding-left: 4%; text-align: justify!important;"> Le mariage est l'acte public par lequel un homme et une femme établissent entre eux une union légale et durable dont les conditions de formation, les effets et la dissolution sont déterminés par la loi n` 073/84 du 17 Octobre 1984 portant Code de la Famille. nomme avant 21 ans révolus et la femme avant 18 ans révolus ne peuvent contracter un mariage. Néanmoins des dispenses d'âge pour des motifs graves sont accordées par le Procureur de la République près du Tribunal Populaire de District ou d'arrondissement. Pour le mariage, le consentement des futurs époux est nécessaire. <br><br>En cas de monogamie, on ne peut contracter un second mariage avant la dissolution du premier. S'il y accord des deux époux, le mari peut contracter une nouvelle union. La déclaration de l'option de polygamie est souscrite devant l'Officier de l'Etat Civil, à l'étranger devant l'agent diplomatique ou consulaire. <br><br>En cas de polygamie, les époux se doivent affection et respect dans le traitement, il y a égalité par rapport a l'autre. <br><br>Le mari est d'office chef de famille. Le Père et la Mère ont à l'égard de renfant droit et devoir de garde, de surveillance et d'éducation. <br>En cas de non respect mutuel, le divorce peut être prononcé à la demande de l'un des époux. </small>
                    </div>
                    <div class="col-6">
                        <h4>Régime matrimonial</h4> <small style="padding-right: 4%; text-align: justify!important;"> Le régime matrimonial règle les effets Patrimoniaux du mariage dans les apports des époux entre eux et à l'égard des tiers. <br><br> <h4 class="text-center">Régime des droits Communs</h4> La Loi organise trois régimes matrimoniaux : <br> 1- La communauté réduite aux acquêts <br> 2 - La séparation des biens <br> 3- La communauté conventionnelle Le régime de droit commun est celui de la communauté réduite aux acquêts. <br><br> <h4 class="text-center">Communauté conventionnelle</h4> Les époux peuvent notamment convenir <br> - Que la communauté comprendra les meubles et les acquêts. <br> - Qu'il sera dérogé aux règles concernant l'administration. <br> - Que l'un des époux sera autorisé à prélevér avant tout partage, une certaine somme ou une certaine quantité d'effets mobiliers en nature. <br> - Que les époux n'auront des parts inégales. <br> - Qu'il Y aura entre eux communauté universelle. </small>
                    </div>
                </div>
            </div>
            <div class="bb-item">
                <div class="row">
                    <div class="col-6">
                        <b>Extrait d'acte de mariage</b> <br> <b>N° {{ $am->code_acte_mariage }} du {{ date("d/m/Y", strtotime($am->created_at)) }}</b><br> <b>Centre d'Etat Civil</b><br> <b>De la {{ $inst }}</b><br>
                        <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> <th></th> </tr> </thead>
                            <tbody> <tr> <td>Le <strong>{{ date("d/m/Y", strtotime($am->declaration->date_prevue_mariage)) }}</strong></td> </tr> <tr>
                                <td>A été célébré le mariage entre</td> </tr> <tr>
                                <td>Mr <strong>{{ $am->declaration->epoux->nomcomplet() }}</strong></td> </tr> <tr colspan='2'>
                                <td>Né le <strong> {{ date("d", strtotime($am->declaration->epoux->date_naissance)) ." ".Sifec::mois(date("m", strtotime($am->declaration->epoux->date_naissance))) ." ". date("Y", strtotime($am->declaration->epoux->date_naissance)) }}</strong> A <strong>{{ $am->declaration->epoux->lieu_naissance }}</strong></td> </tr> <tr> </tr> <tr>
                                <td>Acte de naissance n° <strong>{{ $am->declaration->numero_acte_naissance_epoux }}</strong> Etabli par <strong>LA {{ $am->declaration->cec_naissance_epoux }}</strong></td> </tr> <tr colspan='2'>
                                <td>Nationalité <strong>{{ $am->declaration->epoux->nationalite->lib_nationalite }}</strong> Profession <strong>{{ $am->declaration->professionEpoux->lib_profession }}</strong></td> </tr> <tr>
                                <td>Domicile <strong>{{ $am->declaration->epoux->adresse }}</strong></td> </tr> <tr>
                                <td>Fils de<strong>{{ $am->declaration->pere_epoux }}</strong> </td> </tr> <tr>
                                <td>Et de <strong>{{ $am->declaration->mere_epoux }}</strong></td> </tr> <tr> <td> </td> </tr> <tr>
                                <td>Mlle <strong>{{ $am->declaration->epouse->nomcomplet() }}</strong></td> </tr> <tr colspan='2'>
                                <td>Né le <strong> {{ date("d", strtotime($am->declaration->epouse->date_naissance)) ." ".Sifec::mois(date("m", strtotime($am->declaration->epouse->date_naissance))) ." ". date("Y", strtotime($am->declaration->epouse->date_naissance)) }}</strong> A <strong>{{ $am->declaration->epouse->lieu_naissance }}</strong></td> </tr> <tr> </tr> <tr>
                                <td>Acte de naissance n° <strong>{{ $am->declaration->numero_acte_naissance_epouse }} Etabli par <strong>LA {{ $am->declaration->cec_naissance_epouse }}</strong></td> </tr> <tr colspan='2'>
                                <td>Nationalité <strong>{{ $am->declaration->epouse->nationalite->lib_nationalite }}</strong> Profession <strong>{{ $am->declaration->professionEpouse->lib_profession }}</strong></td> </tr> <tr>
                                <td>Domicile <strong>{{ $am->declaration->epouse->adresse }}</strong></td> </tr> <tr>
                                <td>Fille de <strong>{{ $am->declaration->pere_epoux }}</strong></td> </tr> <tr>
                                <td>Et de <strong>{{ $am->declaration->mere_epoux }}</strong></td> </tr> <tr colspan='2'>
                                <td>Les époux ont déclaré s'unir sous le régime <strong>{{ $am->declaration->regime->lib_regime }}</strong> et l'option <strong>{{ $am->declaration->optionMariage->lib_option_mariage }}</strong></td> </tr> <tr colspan='2'>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <br><br><br><br>
                        <p>L'officier de l'Etat Civil</p>
                        <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> <th></th> </tr> </thead>
                        <tbody>
                            <tr>
                                <td>Pour extrait conforme <br><strong>  {{ $localisation }}  </strong> le  <strong>{{ date('d/m/Y', strtotime($am->created_at)) }}</strong></td>
                            </tr>
                            <tr>
                                <td>L'époux<br>
                                    @if($am->declaration->signatureActe !=null)
                                    <img src="data:image/png;base64,{{$am->declaration->signatureActe->signature_epoux}}" alt="Base64 Image" width="100" height="80">
                                    @endif

                                </td>
                                <td>L'épouse
                                    @if($am->declaration->signatureActe !=null)
                                    <img src="data:image/png;base64,{{$am->declaration->signatureActe->signature_epouse}}" alt="Base64 Image" width="100" height="80">
                                    @endif
                                </td>
                            </tr>
                            <tr style="text-align: center;">
                                <td> L'Officier de l'Etat-Civil<br>
                                    @if ($am->approbation_mairie != null)
                                        <img src='{{ asset("app/".$am->signature_maire) }}' width="100" height="100">
                                        {{ $nomcomplet }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                        </table>
                        {{-- <p>Mentions Marginales (1)</p> --}}
                    </div>
                </div>
            </div>
            <div class="bb-item">
                <div class="row">
                    <div class="col-6">
                        <div>
                            <b>Epoux</b> <br> <b>Extrait d'acte de décès</b><br> <b>N° AD0001 Du 15 juillet 2022</b><br> <b>Centre d'Etat Civil</b><br> <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> <th></th> </tr> </thead> <tbody> <tr> <td>De</td> </tr> <tr> <td>Décédé le</td> </tr> <tr colspan='2'> <td>A <strong>Brazzaville</strong> heures <strong> 12h45</strong>minutes</td> </tr> <tr> <td>Lieu de survenance <strong>domicile</strong></td> </tr> <tr colspan='2'> <td>Pour extrait conforme, fait à...... le .......</td> </tr> </tbody> </table> <p>L'officier de l'Etat Civil</p>
                        </div>
                        <div>
                            <b>Epouse</b> <br> <b>Extrait d'acte de décès</b><br> <b>N° AD0002 Du 15 juillet 2022</b><br> <b>Centre d'Etat Civil</b><br> <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> <th></th> </tr> </thead> <tbody> <tr> <td>De</td> </tr> <tr> <td>Décédée le</td> </tr> <tr colspan='2'> <td>A <strong>Brazzaville</strong> heures <strong> 12h45</strong>minutes</td> </tr> <tr> <td>Lieu de survenance <strong>domicile</strong></td> </tr> <tr colspan='2'> <td>Pour extrait conforme, fait à...... le .......</td> </tr> </tbody> </table> <p>L'officier de l'Etat Civil</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4>Divorce</h4> <p>Mariage dissout par jugement</p> <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> <th></th> </tr> </thead> <tbody> <tr colspan='2'> <td>N° <strong>1452558</strong> Du <strong> 25 mars 2023</strong></td> </tr> <tr> <td>Rendu par <strong></strong></td> </tr> <tr> <td>Pour extrait conforme, </td> </tr> <tr> <td>Fait à...... le .......</td> </tr> </tbody> </table> <p>L'officier de l'Etat Civil</p>
                    </div>
                </div>
            </div>
            @if ($am->declaration->livretFamille != null)

                @php
                    $i = 1;
                @endphp


                @foreach ($am->declaration->livretFamille->detailLivrets as $item)
                <div class="bb-item">
                    <div class="row">
                        @if($item->typeExtrait->code_type_extrait == 'TEX_0001')
                        <div class="col-6">
                            <div>
                                <h4> {{ $i++ == 1 ? '1er' : 'ème' }} ENFANT</h4> <b>Extrait d'acte de naissance</b> <br> <b>N° {{ $item->numero_extrait }} Du {{ date("d/m/Y", strtotime($item->created_at)) }}</b><br> <b>Centre d'Etat Civil</b><br> <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> </tr> </thead> <tbody> <tr>
                                    {{-- <td>De</td> </tr> <tr> --}}
                                    <td>Le {{ date("d/m/Y", strtotime($item->enfant->date_naissance)) }}</td> </tr> <tr colspan='2'>
                                    <td>à <strong>Brazzaville</strong> heures <strong> 12h45</strong>minutes</td> </tr> <tr>
                                    <td>{{ $item->enfant->sexe == "M" ? "est né à" : "est née à" }} : <strong>{{ $item->enfant->lieu_naissance }}</strong></td> </tr> <tr>
                                    <td>{{ $item->enfant->sexe == "M" ? "le nommé" : "la nommée" }} : <strong>{{ $item->enfant->nomcomplet() }}</strong></td> </tr> <tr>
                                    <td>de sexe : <strong>{{ $item->enfant->sexe == "M" ? "Masculin" : "Feminin" }}</strong></td> </tr> <tr>
                                    {{-- <td>naissance à l'étranger : N° ................... le ................ </td> </tr> <tr colspan='2'> --}}
                                    <td style="text-align: right;">Pour extrait conforme, fait à Brazzaville le {{ date("d/m/Y", strtotime($item->created_at)) }}</td> </tr> </tbody> </table>
                                    <p>L'officier de l'Etat Civil</p> <thead> <tr> <th></th> <th></th> </tr> </thead> <tbody>   </tbody> </table>
                            </div>
                        </div>
                        @endif
                        @if($item->typeExtrait->code_type_extrait == 'TEX_0003')
                        <div class="col-6">
                            <div>
                                <h4><br><br> Mentions marginales</h4> <b>Extrait d'acte de décès</b> <br> <b>N°............... Du...................</b><br> <b>Centre d'Etat Civil</b><br> <table style="margin-left: 2%; text-align: left;"> <thead> <tr> <th></th> <th></th> </tr> </thead> <tbody> <tr> <td>De</td> </tr> <tr> <td>Décédé le</td> </tr> <tr colspan='2'> <td>à <strong>Brazzaville</strong> heures <strong> 12h45</strong>minutes</td> </tr> <tr> <td>Lieu de survenance <strong>domicile</strong> </td> </tr> <tr colspan='2'> <td style="text-align: right;">Pour extrait conforme, fait à...... le .......</td> </tr> </tbody> </table> <p>L'officier de l'Etat Civil</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
        <nav> <a id="bb-nav-first" href="#" class="bb-custom-icon bb-custom-icon-first">First page</a> <a id="bb-nav-prev" href="#" class="bb-custom-icon bb-custom-icon-arrow-left">Previous</a> <a id="bb-nav-next" href="#" class="bb-custom-icon bb-custom-icon-arrow-right">Next</a> <a id="bb-nav-last" href="#" class="bb-custom-icon bb-custom-icon-last">Last page</a> </nav>
    </div>
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
