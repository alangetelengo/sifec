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
        <title>SIFEC::CERTIFICAT</title>
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
            .corps{
                /* background-image: url('tpl/armoirie_congo.png'); */
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
            $institution = $certificat->institutionUser->institution;
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

                        <div class="card-body" id="corps">

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
                                               @php
                                                   if ($certificat->estDeclarationSimpleSansOrigine()) {
                                                        $numcert = $certificat->code_declaration_naissance;
                                                        $localiteParent = "";
                                                        $localite = "MINISTERE DE LA SANTE ET DE LA POPULATION";
                                                   } else {
                                                        $numcert = $certificat->numero_certificat;
                                                   }
                                               @endphp
                                                <strong>{{ $localiteParent }} <br>
                                                    {{ $localite }} <br>
                                                    {{ $inst }}</strong>
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
                                <span class="text-center">{{$certificat->libelleAffichageType()}} <br> Année: <strong>{{date("Y", strtotime($certificat->date_heure_declaration))}}</strong>; n° <strong>{{$numcert}}</strong></span>
                            </div><br>
                            <div class="row">
                                <div class="col-md-4 col-sm-12">

                                    <strong>Renseignements enfant</strong>
                                    <table class="corps" style="border-top: 1px solid black;">

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: left" colspan="3">L'Officier du centre d'état civil de : {{ $inst }}</td>
                                            <td style="border: none; padding:5px 0px;text-align: center">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé que le : {{ utf8_encode(strftime("%d %B %Y", strtotime($certificat->date_heure_naissance))) . " A " .date("H", strtotime($certificat->date_heure_naissance))." heure(s) ".date("i", strtotime($certificat->date_heure_naissance))." miniute(s)" }}</td>
                                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est né, un enfant de sexe : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->enfant->nom}} </span>&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->enfant->prenom}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> BRAZZAVILLE </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->sitMatParent ? $certificat->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->lieuSurvenance ? $certificat->lieuSurvenance->lib_lieu_survenance : $dummy }} </span> </td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                    </table><br>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <strong>Renseignements père</strong>
                                        <table class="corps" style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->pere ? $certificat->pere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->prenom : $dummy}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$certificat->pere ? date("d-m-Y", strtotime($certificat->pere->date_naissance)) : $dummy }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->lieu_naissance : $dummy }}</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->adresse : $dummy }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->nationalite->lib_nationalite : $dummy }} </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->niveau_instruction : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->profession->lib_profession : $dummy }} </span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->pere ? $certificat->pere->telephone : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                        </table><br>
                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <strong>Renseignements mère</strong>
                                        <table class="corps" style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $certificat->mere ? $certificat->mere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->prenom : $dummy}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{$certificat->mere ? date("d-m-Y", strtotime($certificat->mere->date_naissance)) : $dummy }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->lieu_naissance : $dummy }}</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->adresse : $dummy }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->nationalite->lib_nationalite : $dummy }} </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->niveau_instruction : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->profession->lib_profession : $dummy }} </span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->mere ? $certificat->mere->telephone : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nombre d'enfant(s) vivant(s) y compris celui-ci : <span style="font-size: 13px;font-weight:bold;"> {{ $certificat->nombre_enfant ? $certificat->nombre_enfant : $dummy }} </span></td>
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
