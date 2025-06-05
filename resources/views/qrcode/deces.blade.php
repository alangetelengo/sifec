<!DOCTYPE html>
<html lang="FR">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keywords" content="admin, dashboard" />
        <meta name="author" content="DexignZone" />
        <meta name="robots" content="index, follow" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
        <meta property="og:title" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
        <meta property="og:description" content="tixia : tixia School Admission Admin  Bootstrap 5 Template" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIFEC</title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link href="{{ asset('tpl/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
        <link href="{{ asset('tpl/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
        <link href="{{ asset('tpl/css/style.css')}}" rel="stylesheet">
        <style type="text/css">
            /* strong{
                color: blue;
            } */
            fieldset {
                font-family: Arial;
                padding-left: 150px;
                float: left;
            }
            .back{
                background-image: url("{{asset('tpl/armoirie_congo.png')}}");
                background-size: 80%;
                background-repeat: no-repeat;
            }
        </style>
    </head>
<body>
    <div id="main-wrapper">
        @php
            $localite = "";
            $localiteParent = "";
            $inst = "";
            $institution = $declaration->institutionUser->institution;
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

            <div class="row">
                <div class="col-lg-12">

                    <div class="card mt-3">

                        <div class="card-body">

                            <div class="row text-center mt-20">
                                <table class="historique" cellspacing="0" style="width: 95%; font-size: 20px;">
                                    <col style="width: 60%">
                                    <col style="width: 40%">
                                    <thead>
                                      <tr style="text-align: center">
                                        <td style="text-align: center;"></td>
                                        <td style="text-align: center;"></td>
                                      </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="font-size: 60%;">
                                            <td style="text-align: center;">
                                                <br>
                                                <strong>{{ $localiteParent }} <br>
                                                    {{ $localite }} <br>
                                                    {{ $declaration->institutionUser->institution->pompeFunebre->lib_institution }}</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>REPUBLIQUE DU CONGO</strong><br>
                                                Unité * Travail * Progrès
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <div class="row">
                                <span class="text-center">Acte de décès <br> Année: <strong>{{date("Y")}}</strong>; n°<strong>{{$declaration->acte->code_acte_deces}}</strong></span>
                            </div><br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12 back">

                                    <strong>Renseignements du défunt(e)</strong>
                                    <table style="border-top: 1px solid black;">

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil des : {{ $declaration->institutionUser->institution->pompeFunebre->lib_institution }}</td>
                                            <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé le : {{ date("d-m-Y", strtotime($declaration->defunt->date_naissance)). " A " .date("H", strtotime($declaration->date_heure_declaration))." heures ".date("s", strtotime($declaration->date_heure_declaration))." miniutes" }}</td>
                                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Du décès de : <span style="font-size: 13px;font-weight:bold;"> </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $declaration->defunt->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $declaration->defunt->prenom}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Sexe : <span style="font-size: 13px;font-weight:bold;">{{ $declaration->defunt->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> {{$declaration->defunt->lieu_naissance}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale :<span style="font-size: 13px;font-weight:bold;"> {{$declaration->situationMat ? $declaration->situationMat->lib_situation_matrimoniale :""}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->lieuSurvenance ? $declaration->lieuSurvenance->lib_lieu_survenance : "" }} </span> </td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Cause du décès: <span style="font-size: 13px;font-weight:bold;">

                                                @php
                                                $causesd = $declaration->DDecesCauses;
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
                                            </strong>
                                        </span> </td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                    </table><br>

                                </div>
                                @if($declaration->conjoint != null)
                                <div class="col-md-4 col-sm-12 back">
                                    <strong>Renseignements du conjoint(e)</strong>
                                        <table style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $declaration->conjoint ? $declaration->conjoint->nom : ""}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $declaration->conjoint ? $declaration->conjoint->prenom : ""}} </span>
                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Prénom (s) : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->conjoint ? $declaration->conjoint->prenom : ""}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Option de mariage : <span style="font-size: 13px;font-weight:bold;"> {{$declaration->conjoint ? date("d-m-Y", strtotime($declaration->conjoint->date_naissance)) : "" }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">N° acte de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->conjoint ? $declaration->conjoint->lieu_naissance : "" }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->conjoint ? $declaration->conjoint->adresse : "" }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                @if ($declaration->conjoint != NULL)
                                                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de mariage : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->conjoint->code_nationalite != NULL ? $declaration->conjoint->nationalite->lib_nationalite : "" }} </span>
                                                @else
                                                    <td style="border: none; padding:5px 0px;text-align: " colspan="3">Centre d'état civil de mariage : <span style="font-size: 13px;font-weight:bold;">  </span>
                                                @endif
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                        </table><br>

                                </div>
                                @endif

                                <div class="col-md-4 col-sm-12 back">
                                    <strong>Renseignements du déclarant</strong>
                                        <table style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $declaration->declarant ? $declaration->declarant->nom : ""}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->prenom : ""}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$declaration->declarant ? date("d-m-Y", strtotime($declaration->declarant->date_naissance)) : "" }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->lieu_naissance : "" }}</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->adresse : "" }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->nationalite->lib_nationalite : "" }} </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->niveau_instruction : "" }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->profession->lib_profession : "" }} </span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->declarant ? $declaration->declarant->telephone : "" }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nombre d'defunt(s) vivant(s) y compris celui-ci : <span style="font-size: 13px;font-weight:bold;"> {{ $declaration->nombre_defunt ? $declaration->nombre_defunt : "" }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                        </table>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
            <div class="footer">
                <div class="copyright">
                    <p>Copyright © Réalisé par <a href="#" target="_blank">ASCI</a> 2022</p>
                </div>
            </div>
        </div>

    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
{{--         <script src="{{ asset('tpl/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
 --}}        <script src="{{ asset('tpl/vendor/bootstrap-datetimepicker/js/moment.js') }}"></script>
        <script src="{{ asset('tpl/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ asset('tpl/vendor/highlightjs/highlight.pack.min.js')}}"></script>


        <script src="{{ asset('tpl/js/custom.min.js') }}"></script>
        <script src="{{ asset('tpl/js/deznav-init.js') }}"></script>
</body>
</html>
