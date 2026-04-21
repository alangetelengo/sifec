@extends("layout.app")
@section("titre")
     {{ $type_declaration }}
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

<div class="page-sifec-form">
        <!-- row -->
    <div class="row" id="validation">
        <div class="col-12">
            <div class="card wizard-content">
                <div class="card-body">

                    <h4>{{ $title }}</h4>
                      <!-- retour à la liste selon le type de declaration -->
                      @isset($decesDeclarationSuccessListUrl)
                      <a href="{{ $decesDeclarationSuccessListUrl }}" class="btn btn-warning float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                      @elseif($type_declaration == 'CERTIFICAT DE NON INSCRIPTION' || $type_declaration == 'DECLARATION TARDIVE')
                      <a href="{{ route('certificatNonInscriptionDeces.index') }}" class="btn btn-warning float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                      @elseif($type_declaration == 'CERTIFICAT DE DESTRUCTION DE L\'ACTE')
                      <a href="{{ route('certificatDestructionDeces.index') }}" class="btn btn-warning float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                      @else
                      <a href="{{ route('declarationDeces.index') }}" class="btn btn-warning float-end"> <i class="fa fa-list"></i> Retour à la liste</a>
                      @endif
                      <br>
                    <form  name="contactUsForm" id="contactUsForm" class="validation-wizard wizard-circle" method="post" action="javascript:void(0)">
                        <!-- Step 1 -->
                        <h6>Défunt</h6>
                        @include("deces::declaration.form")
                        <!-- Step 2 -->
                       @include("deces::declaration.conjoint")

                        @include("deces::declaration.parent")
                        <!-- Step 3 -->
                        @include("deces::declaration.declarant")
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('deces::declaration.modal-search')
</div>
@endsection
@section("scripts")
@php
    $sifecDecesDeclarationForm = [
        'storeUrl' => isset($decesDeclarationStoreUrl) ? $decesDeclarationStoreUrl : route('declarationDeces.store'),
        'afterSuccessListUrl' => isset($decesDeclarationSuccessListUrl) ? $decesDeclarationSuccessListUrl : null,
    ];
@endphp
<script>
    window.sifecDecesDeclarationForm = @json($sifecDecesDeclarationForm);
</script>
@include("deces::declaration.js.create")

@endsection










