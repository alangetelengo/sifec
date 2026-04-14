<form name="contactUsForm" id="contactUsForm" method="post" action="javascript:void(0)" class="validation-wizard wizard-circle">
    <link rel="stylesheet" href="{{ asset('css/mariage/declaration-form.css') }}">

    @include('mariage::declaration.form.steps._step_epoux')
    @include('mariage::declaration.form.steps._step_epouse')
    @include('mariage::declaration.form.steps._step_temoins')
    @include('mariage::declaration.form.steps._step_informations_generales')
</form>

<script src="{{ asset('js/mariage/declaration-form-logic.js') }}"></script>
