@extends("layout.app")
@section("titre")
    Déclaration naissance
@endsection
@section("sous-titre")
    Déclaration naissance
@endsection
@section("styles")
<!-- Form step -->
<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css') }}" rel="stylesheet">
<!-- Daterange picker -->
<link href="{{ asset('tpl/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
<!-- Clockpicker -->
<link href="{{ asset('tpl/vendor/clockpicker/css/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
<!-- asColorpicker -->
<link href="{{ asset('tpl/vendor/jquery-asColorPicker/css/asColorPicker.min.css') }}" rel="stylesheet">
<!-- Material color picker -->
<link href="{{ asset('tpl/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<!-- Pick date -->
<link href="{{ asset('tpl/wizard/assets/node_modules/wizard/steps.css') }}" rel="stylesheet">
    <!--alerts CSS -->
    <link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section("corps")

        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Enregistrer une naissance</h4>
                    </div>
                    <div class="card wizard-content">
                        <div class="card-body">
                            <form action="#" class="validation-wizard wizard-circle">
                                <div>
                                    <h4 id="texte"></h4>
                                    <h4 id="noninscript"><a href="{{route('certificatNonInscription.create')}}"><span style="color: red">Le délais de déclaration est supérieur à 30 jours, créer un certificat de non inscription</span> </a></h4>
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Date de naissance de l'enfant <span class="text-danger">*</span></label>
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" onchange="age()"  class="form-control" id="date_naissance_enfant">
                                </div>
                            </form>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Déclarant</th>
                                                    <th>Enfant: Nom</th>
                                                    <th>Enfant: Prénom</th>
                                                    <th>Enfant: Date naissance</th>
                                                    <th>Enfant: Sexe</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($certificats as $certificat)
                                                @if ($certificat->type_declaration == "CERTIFICAT DE NON INSCRIPTION" || $certificat->type_declaration == "DECLARATION DE NAISSANCE")
                                                    <tr width="100%">
                                                        <td>{{ $i++ }}</td>
                                                        <td>{{ $certificat->declarant->nom.' '.$certificat->Declarant->prenom }}</td>
                                                        <td>{{ $certificat->enfant->nom }}</td>
                                                        <td>{{ $certificat->enfant->prenom }}</td>
                                                        <td>{{ date("d-m-Y", strtotime($certificat->enfant->date_naissance)) }}</td>
                                                        <td>{{ $certificat->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                                        <td>
                                                            <div class="dropdown">
                                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item" href="{{ route('certificatNonInscription.etat',$certificat->code_declaration_naissance) }}" target="_blank">Afficher le certificat</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Déclarant</th>
                                                    <th>Enfant: Nom</th>
                                                    <th>Enfant: Prénom</th>
                                                    <th>Enfant: Date naissance</th>
                                                    <th>Enfant: Sexe</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




@endsection
@section("scripts")

<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Form validate init -->
    <script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>

     <!-- Daterangepicker -->
     <script src="{{ asset("tpl/js/plugins-init/bs-daterange-picker-init.js") }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset("tpl/js/plugins-init/clock-picker-init.js") }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset("tpl/js/plugins-init/jquery-asColorPicker.init.js") }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset("tpl/js/plugins-init/material-date-picker-init.js") }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset("tpl/js/plugins-init/pickadate-init.js") }}"></script>



    <!-- This Page JS -->
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js") }}"></script>

    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

 <script>

    $("#noninscript").hide();
    $("#inscript").hide();

    function age() {
        var datenaiss = $("#date_naissance_enfant").val();
        var today = new Date();

        var date2 = new Date(datenaiss);
        // To calculate the time difference of two dates
        var Difference_In_Time = today.getTime() - date2.getTime();
        var n_jours = Difference_In_Time / (1000 * 3600 * 24);

        // alert();
        if (n_jours>30) {
            $("#noninscript").slideDown(400);
            $("#inscript").hide();
            $('#texte').html('Nombre de jours sans déclarer: '+parseInt(n_jours)+' jours');
            return false;
        }else{
            $("#inscript").slideDown(400);
            $("#noninscript").hide();
            return false;
        }

        // $("#noninscript").hide();
        return false;
    }

</script>



@endsection
