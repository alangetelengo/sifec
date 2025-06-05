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
            .corps{
                background-image: url('tpl/armoirie_congo.png');
                /* /* background-position: 50%; */
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
                                <div class="col-md-4 col-sm-12">

                                    <strong>Renseignements enfant</strong>
                                    <table class="corps" style="border-top: 1px solid black;">

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">L'Officier du centre d'état civil de: <strong>{{ $acte->institutionUser->institution->lib_institution}}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est informé que le: <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_declaration)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_declaration))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_declaration)))  }} </strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Est né(e), un enfant de sexe: <strong>{{ $acte->declaration->enfant->sexe=="M" ? "Masculin" : "Féminin"  }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">{{ $acte->declaration->enfant->sexe=="M" ? "Né :" : "Née :"  }} le <strong> {{ Sifec::asLetters((int)date("d", strtotime($acte->declaration->date_heure_naissance)))}} {{ Sifec::mois(date("m", strtotime($acte->declaration->date_heure_naissance))) }} {{ Sifec::asLetters(date("Y", strtotime($acte->declaration->date_heure_naissance))) ." à ".date("H", strtotime( $acte->declaration->date_heure_naissance)). " heure(s) ".date("i", strtotime( $acte->declaration->date_heure_naissance)) }} minutes</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">A: <strong>{{ $acte->declaration->enfant->lieu_naissance }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3"><strong>{{ $acte->declaration->enfant->sexe=="M" ? "Nommé " : "Nommée "  }} {{ $acte->declaration->enfant->nom." ".$acte->declaration->enfant->prenom }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance :<span style="font-size: 13px;font-weight:bold;"> BRAZZAVILLE </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Situation matrimoniale des parents :<span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->sitMatParent ? $acte->declaration->sitMatParent->lib_situation_matrimoniale : $dummy}} </span></td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de survenance : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->lieuSurvenance ? $acte->declaration->lieuSurvenance->lib_lieu_survenance : $dummy }} </span> </td>
                                            <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                        </tr>
                                    </table><br>

                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <strong>Renseignements père</strong>
                                        <table class="corps" style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $acte->declaration->pere ? $acte->declaration->pere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->prenom : $dummy}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? Sifec::asLetters((int)date("d", strtotime($acte->declaration->pere->date_naissance))) ." ". Sifec::mois(date("m", strtotime($acte->declaration->pere->date_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->pere->date_naissance))) : $dummy }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->lieu_naissance : $dummy }}</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->adresse : $dummy }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->nationalite->lib_nationalite : $dummy }} </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->niveau_instruction : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->profession->lib_profession : $dummy }} </span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->pere ? $acte->declaration->pere->telephone : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                        </table><br>

                                </div>

                                <div class="col-md-4 col-sm-12">
                                    <strong>Renseignements mère</strong>
                                        <table class="corps" style="border-top: 1px solid black;">

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nom (s) : <span style="font-size: 13px;font-weight:bold;">{{ $acte->declaration->mere ? $acte->declaration->mere->nom : $dummy}} </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prénom (s) :<span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->prenom : $dummy}} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: " >&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Date de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? Sifec::asLetters((int)date("d", strtotime($acte->declaration->mere->date_naissance))) ." ". Sifec::mois(date("m", strtotime($acte->declaration->mere->date_naissance))) ." ". Sifec::asLetters(date("Y", strtotime($acte->declaration->mere->date_naissance))) : $dummy }} </span>

                                                </td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Lieu de naissance : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->lieu_naissance : $dummy }}</span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Domicile : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->adresse : $dummy }} </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nationalite : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->nationalite->lib_nationalite : $dummy }} </span>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;Niveau d'instruction : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->niveau_instruction : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Profession : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->profession->lib_profession : $dummy }} </span>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Téléphone : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->mere ? $acte->declaration->mere->telephone : $dummy }} </span></td>
                                                <td style="border: none; padding:5px 0px;text-align: ">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="border: none; padding:5px 0px;text-align: " colspan="3">Nombre d'enfant(s) vivant(s) y compris celui-ci : <span style="font-size: 13px;font-weight:bold;"> {{ $acte->declaration->nombre_enfant ? $acte->declaration->nombre_enfant : $dummy }} </span></td>
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
