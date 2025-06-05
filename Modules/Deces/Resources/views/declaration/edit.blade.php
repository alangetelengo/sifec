@extends("layout.app")
@section("titre")
    Modifier {{ $dd->type_declaration }}
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
@endsection
@section("corps")

        <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card wizard-content">
                <div class="card-body">

                    <h4>Modification de {{ $dd->type_declaration }}</h4>
                    <form  name="contactUsForm" id="contactUsForm" class="validation-wizard wizard-circle" method="post" action="javascript:void(0)">
                        <!-- Step 1 -->
                        <h6>Défunt</h6>

                        @include("deces::declaration.formedit")
                        <!-- Step 2 -->
                       @include("deces::declaration.conjointedit")

                        @include("deces::declaration.parentedit")
                        <!-- Step 3 -->
                        @include("deces::declaration.declarantedit")
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('deces::declaration.modal-search')

@endsection
@section("scripts")
@include("deces::declaration.js.edit")

@endsection










