<html lang="fr">
@extends("layout.app")
@section("titre")
    Formulaire type
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
<link rel="stylesheet" href="{{ asset('app/script-sifec/form.js') }}">

<style>
    /* Style pour les champs en mode lecture seule */
    .readonly-field {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
    }

    .readonly-field:focus {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        box-shadow: none !important;
    }

    /* Style spécial pour les select en mode disabled */
    select.readonly-field:disabled {
        background-color: #f8f9fa !important;
        border-color: #dee2e6 !important;
        color: #6c757d !important;
        opacity: 1 !important;
    }
</style>
@endsection
@section("corps")
<div class="page-sifec-form">
        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Modifier la déclaration de mariage</h4>

                        <div class="d-flex justify-content-end">
                            <!--label class="form-label col-md-3">Type de mariage</label-->
                            <select id="type_mariage"  class="form-control" style="width:200px">
                                 {{-- <option value="" selected>Type de mariage</option> --}}
                                <option value="NORMAL" {{ $declaration->type_mariage == 'NORMAL' ? 'selected' : '' }}>Mariage normal</option>
                                <option value="PROCURATION" {{ $declaration->type_mariage == 'PROCURATION' ? 'selected' : '' }}>Mariage par procuration</option>
                                <!-- <option value="posthume">Mariage à titre posthume</option> -->
                            </select>
                            <select id="type_mandant"  class="form-control" style="width:200px">
                                <option value="" {{ !$declaration->nom_prenom_mandant_epoux && !$declaration->nom_prenom_mandant_epouse ? 'selected' : '' }}> Choix du mandant </option>
                                <option value="mandant_epoux" {{ $declaration->nom_prenom_mandant_epoux ? 'selected' : '' }}>Epoux</option>
                                <option value="mandant_epouse" {{ $declaration->nom_prenom_mandant_epouse ? 'selected' : '' }}>Epouse</option>
                                <!-- <option value="posthume">Mariage à titre posthume</option> -->
                            </select>
                            <br>
                            <a href="{{ route('declarationMariage.index') }}" class="btn btn-primary me-2">Liste des déclarations</a>

                        </div>
                    </div>
                    <p id="notificationMsgProcuration"  style="background:red; color:white; padding:10px; font-size:15px;font-weight:bold"> <i class="fa fa-warning"></i> Ce type de mariage requiert la présence du mandant  conformément à l'article 152 du code de la famille.
                    </p>
                    <div class="card wizard-content">
                        <div class="card-body">
                           <!-- Champ caché pour l'ID de la déclaration -->
                           <input type="hidden" id="declaration_id" name="declaration_id" value="{{ $declaration->code_declaration_mariage }}">

                           @include('mariage::declaration.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('mariage::declaration.madal-search')
</div>
@endsection
@section("scripts")

@include("mariage::declaration.js.create")


<script>
     $(document).ready(function(){
        $("#notificationMsgProcuration").hide();
        $("#type_mandant").hide();
        $("#bloc_mandant_epoux").hide();
        $("#bloc_mandant_epouse").hide();

        // Pré-remplir le formulaire avec les données existantes
        preRemplirFormulaire();
     });

     //Gestion de l'affichage des blocs mandats
     $("#type_mandant").change(function(){
        var type=$(this).val();

        if(type=="mandant_epoux"){
            $("#bloc_mandant_epoux").show(300);
            $("#bloc_mandant_epouse").hide();
        }else{
            $("#bloc_mandant_epouse").show(300);
            $("#bloc_mandant_epoux").hide();
        }
        // $("#bloc_mandant_epouse").hide();

     });

    function getRegime(optionmariage){
        var out = "";

        $.get("{{ route('declarationMariage.regime') }}", { optionmariage:optionmariage }, function(data){
            if(data.length > 0){
                $(".showregime").removeClass("d-none");
                for(var i=0; i < data.length; i++){
                    out += "<option value="+data[i].code_regime+" >"+data[i].lib_regime+"</option>";
                }
            }
            $("#regime_mariage").html(out);

        });
    }
    function getQuartierVillage(codeparent,cle){
        var route = "{{ route('declarationNaissance.search.quartier') }}";
        var option = "<option> Selectionnez </option>";

        $.ajax({
            url: route,
            data:'id='+codeparent,
            dataType:
                'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    console.log(value.lib_localite);
                    option += '<option value="'+value.code_localite+'">'+value.lib_localite+'</option>';
                });
                $("#"+cle).html(option);
            }
        });
    }

    function getArrComUrbaine(codeparent,cle){
        var route = "{{ route('declarationNaissance.search.arrond',':id') }}";
        route = route.replace(":id", codeparent);
        var option = "<option> Selectionnez </option>";
        $.ajax({
            url: route,
            data:'id='+codeparent,
            dataType:
                'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    console.log(value.lib_localite);
                    option += '<option value="'+value.code_localite+'">'+value.lib_localite+'</option>';
                });
                $("#"+cle).html(option);
            }
        });
    }
    function getCentreEtatCivil(codelocalite,key){
        var route = "{{ route('declarationNaissance.search.institution') }}";
        var option = "";
        // var option = "<option>Selectionnez</option>";

        $.ajax({
            url: route,
            data: 'id=' +codelocalite,
            dataType:
                'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    console.log(value.lib_institution);
                    option += '<option value="'+value.code_institution+'">'+value.lib_institution+'</option>';
                });
                $("#"+key).html(option);
                console.log(json);
            }
        });

    }

    function undisabledOtherAdress()
    {
        $('#domicile_pays_epouse').prop('disabled',false);
        $('#domicile_ville_epouse').prop('disabled',false);
        $('#domicile_arrondissement_epouse').prop('disabled',false);
        $('#domicile_quartier_epouse').prop('disabled',false);
        $('#domicile_typevoie_epouse').prop('disabled',false);
        $('#domicile_numero_epouse').removeAttr('disabled');
        $('#domicile_nomvoie_epouse').removeAttr('disabled');
    }

    $(function(){
        $("#type_mariage").on("change", function(){

            var typemariage = $(this).val();

            if(typemariage == "NORMAL"){
                $("div.optionmariage").removeClass('d-none');
                $("div.showregime").removeClass('d-none');
                $("#notificationMsgProcuration").hide(300);
                $("#type_mandant").hide(300);
            }

            if(typemariage == "PROCURATION"){

                $("div.optionmariage").removeClass('d-none');
                $("div.showregime").removeClass('d-none');
                $("#notificationMsgProcuration").show(300);
                $("#type_mandant").show(300);

            }
            // }else{
            //     $("#notificationMsgProcuration").hide(300);
            // }

            if(typemariage == "posthume"){
                $("div.optionmariage").addClass('d-none');
                $("div.showregime").addClass('d-none');
                $("#notificationMsgProcuration").hide(300);
            }
        });

        $("#option_mariage").on("change", function(){
            var optionmariage = $(this).val();
            getRegime(optionmariage);
        });

        $("#code_localite_epoux").change(function() {
            var localiteepoux = $(this).val();

            var libLocaliteEpoux = $("#code_localite_epoux option:selected").text();
            if(localiteepoux != '' || localiteepoux != null){
                getCentreEtatCivil(localiteepoux,'code_cec_epoux');
                $("#lieu_naissance_epoux").val(libLocaliteEpoux);
                $("div.autrelieunaissanceepoux").addClass("d-none");
                $("div.autrececnaissanceepoux").addClass("d-none");
                $("div.codececepoux").removeClass("d-none");
            }
            if(localiteepoux == 'LOC_4247'){
               $("div.autrelieunaissanceepoux").removeClass("d-none");
               $("div.autrececnaissanceepoux").removeClass("d-none");
               $("div.codececepoux").addClass("d-none");
               $("#lieu_naissance_epoux").val("");
            }

        });
        $("#code_cec_epoux").on("change", function() {
            var libCecNaisEpoux = $("#code_cec_epoux option:selected").text();
            $("#cec_naissance_epoux").val(libCecNaisEpoux);
        });
        $("#etranger_lieu_naissance_epoux").on("change", function() {
            var libLieuNaisEpoux = $("#etranger_lieu_naissance_epoux option:selected").text();
            $("#lieu_naissance_epoux").val(libLieuNaisEpoux);
        });

        $("#code_localite_epouse").change(function() {
            var localiteepouse = $(this).val();

            var libLocaliteEpoux = $("#code_localite_epouse option:selected").text();
            if(localiteepouse != '' || localiteepouse != null){
                // alert(localiteepouse);
                getCentreEtatCivil(localiteepouse,'code_cec_epouse');
                $("#lieu_naissance_epouse").val(libLocaliteEpoux);
                $("div.autrelieunaissanceepouse").addClass("d-none");
                $("div.autrececnaissanceepouse").addClass("d-none");
                $("div.codececepouse").removeClass("d-none");
            }
            if(localiteepouse == 'LOC_4247'){
               $("div.autrelieunaissanceepouse").removeClass("d-none");
               $("div.autrececnaissanceepouse").removeClass("d-none");
               $("div.codececepouse").addClass("d-none");
               $("#lieu_naissance_epouse").val("");
            }

        });

        $("#etranger_lieu_naissance_epouse").on("change", function() {
            var libLieuNaisEpouse = $("#etranger_lieu_naissance_epouse option:selected").text();
            $("#lieu_naissance_epouse").val(libLieuNaisEpouse);
        });

        $("#code_cec_epouse").on("change", function() {
            var libCecNaisEpoux = $("#code_cec_epouse option:selected").text();
            $("#cec_naissance_epouse").val(libCecNaisEpoux);
        });


        $('#departementcongo_epoux').hide();
        $('#domicile_pays_epoux').on('change', function () {
            var pays = $('#domicile_pays_epoux').val();

            if (pays == 'Congo') {
                $('#departementcongo_epoux').show();
                $('#autredepartement_epoux').hide();
                $("div.adresselocale_epoux").removeClass('d-none');
            } else {
                $('#departementcongo_epoux').hide();
                $('#autredepartement_epoux').show();
                $("div.adresselocale_epoux").addClass('d-none');
            }
        });

        $('#departementcongo_epouse').hide();
        $('#domicile_pays_epouse').on('change', function () {
            var pays = $('#domicile_pays_epouse').val();
            if (pays == 'Congo') {
                $('#departementcongo_epouse').show();
                $('#autredepartement_epouse').hide();
                $("div.adresselocale_epouse").removeClass('d-none');
            } else {
                $('#departementcongo_epouse').hide();
                $('#autredepartement_epouse').show();
                $("div.adresselocale_epouse").addClass('d-none');
            }
        });

        $('#departementcongo_temoins_epoux').hide();
        $('#domicile_pays_temoins_epoux').on('change', function () {
            var pays = $('#domicile_pays_temoins_epoux').val();
            if (pays == 'Congo') {
                $('#departementcongo_temoins_epoux').show();
                $('#autredepartement_temoins_epoux').hide();
                $("div.adresselocale_temoins_epoux").removeClass('d-none');
            } else {
                $('#departementcongo_temoins_epoux').hide();
                $('#autredepartement_temoins_epoux').show();
                $("div.adresselocale_temoins_epoux").addClass('d-none');
            }
        });


        $('#departementcongo_temoins_epouse').hide();
        $('#domicile_pays_temoins_epouse').on('change', function () {
            var pays = $('#domicile_pays_temoins_epouse').val();
            if (pays == 'Congo') {
                $('#departementcongo_temoins_epouse').show();
                $('#autredepartement_temoins_epouse').hide();
                $("div.adresselocale_temoins_epouse").removeClass('d-none');
            } else {
                $('#departementcongo_temoins_epouse').hide();
                $('#autredepartement_temoins_epouse').show();
                $("div.adresselocale_temoins_epouse").addClass('d-none');
            }
        });

        $('#departementcongo_declarant').hide();
        $('#domicile_pays_declarant').on('change', function () {
            var pays = $('#domicile_pays_declarant').val();
            if (pays == 'Congo') {
                $('#departementcongo_declarant').show();
                $('#autredepartement_declarant').hide();
                $("div.adresselocale_declarant").removeClass('d-none');
            } else {
                $('#departementcongo_declarant').hide();
                $('#autredepartement_declarant').show();
                $("div.adresselocale_declarant").addClass('d-none');
            }
        });

        $("#domicile_ville_epoux").on("change", function(){
            var localiteParent = $(this).val();

            if(localiteParent != "" || localiteParent !=null){
                var domicilevilleepoux = $("#domicile_ville_epoux option:selected").text();
                getArrComUrbaine(localiteParent,'domicile_arrondissement_epoux');
            }

        });

        $("#domicile_quartier_epoux").on('change', function(){
            var q = $(this).val();
            if(q != "" || q !=null){
                var quartier = '<option>'+$("#domicile_quartier_epoux option:selected").text()+'</option>';
            }
        });

        $("#domicile_typevoie_epoux").on('change', function(){
            var typevoie = $(this).val();
            if(typevoie != "" || typevoie !=null){
                var tvoie = '<option>'+typevoie+'</option>';
            }
        });

        $("#domicile_arrondissement_epoux").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                var domicilearrondepoux = $("#domicile_arrondissement_epoux option:selected").text();
                getQuartierVillage(localiteParent,'domicile_quartier_epoux');
            }
        });


        //Debut traitement adresse epouse,soit même que l'époux
        $("#sameadress").on('click', function(){

            undisabledOtherAdress();

            $('#domicile_pays_epouse').val($('#domicile_pays_epoux option:selected').text());
            $('#domicile_pays_epouse').attr('readOnly','readOnly');

            $('#domicile_ville_epouse').attr('readOnly','readOnly');

            var domicile_ville_epouse = $("#domicile_ville_epouse");
            var domicile_ville_epoux = $("#domicile_ville_epoux");
            domicile_ville_epouse.val(domicile_ville_epoux.val());

            $('#domicile_arrondissement_epouse').attr('readOnly','readOnly');
            var domicile_arrondissement_epouse = $("#domicile_arrondissement_epouse");
            var domicile_arrondissement_epoux = $("#domicile_arrondissement_epoux");
            domicile_arrondissement_epouse.val(domicile_arrondissement_epoux.val());

            $('#domicile_quartier_epouse').attr('readOnly','readOnly');
            var domicile_quartier_epouse = $("#domicile_quartier_epouse");
            var domicile_quartier_epoux = $("#domicile_quartier_epoux");
            domicile_quartier_epouse.val(domicile_quartier_epoux.val());


            $('#domicile_numero_epouse').val($('#domicile_numero_epoux').val());
            $('#domicile_numero_epouse').attr('readOnly','readOnly');

            $('#domicile_nomvoie_epouse').val($('#domicile_nomvoie_epoux').val());
            $('#domicile_nomvoie_epouse').attr('readOnly','readOnly');

            $('#domicile_typevoie_epouse').val($('#domicile_typevoie_epoux').val());
            $('#domicile_typevoie_epouse').attr('readOnly','readOnly');

        });

        $("#otheradress").on('click', function(){

            undisabledOtherAdress();

            $('#domicile_pays_epouse').val("");
            $('#domicile_pays_epouse').attr('readOnly',false);

            $("#domicile_ville_epouse").val("");
            $('#domicile_ville_epouse').attr('readOnly',false);

            $("#domicile_arrondissement_epouse").val("");
            $('#domicile_arrondissement_epouse').attr('readOnly',false);

            $("#domicile_quartier_epouse").val("");
            $('#domicile_quartier_epouse').attr('readOnly',false);

            $('#domicile_numero_epouse').val("");
            $('#domicile_numero_epouse').attr('readOnly',false);
            $('#domicile_nomvoie_epouse').val("");
            $('#domicile_nomvoie_epouse').attr('readOnly',false);
            $('#domicile_typevoie_epouse').val("");
            $('#domicile_typevoie_epouse').attr('readOnly',false);

            });
        //Fin traitement adresse epouse,soit même que l'époux

        // epouse
        $("#domicile_ville_epouse").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getArrComUrbaine(localiteParent,'domicile_arrondissement_epouse');
            }
            return false;
        });

        $("#domicile_arrondissement_epouse").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getQuartierVillage(localiteParent,'domicile_quartier_epouse');
            }
            return false;
        });

        // Témoins époux
        $("#domicile_ville_temoins_epoux").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getArrComUrbaine(localiteParent,'domicile_arrondissement_temoins_epoux');
            }
            return false;
        });

        $("#domicile_arrondissement_temoins_epoux").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getQuartierVillage(localiteParent,'domicile_quartier_temoins_epoux');
            }
            return false;
        });

        // Témoins épouse
        $("#domicile_ville_temoins_epouse").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getArrComUrbaine(localiteParent,'domicile_arrondissement_temoins_epouse');
            }
            return false;
        });

        $("#domicile_arrondissement_temoins_epouse").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getQuartierVillage(localiteParent,'domicile_quartier_temoins_epouse');
            }
            return false;
        });

        // Déclarant
        $("#domicile_ville_declarant").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getArrComUrbaine(localiteParent,'domicile_arrondissement_declarant');
            }
            return false;
        });

        $("#domicile_arrondissement_declarant").on("change", function(){
            var localiteParent = $(this).val();
            if(localiteParent != "" || localiteParent !=null){
                getQuartierVillage(localiteParent,'domicile_quartier_declarant');
            }
            return false;
        });

        //temoin epoux
        $("#code_localite_t_epoux_1").change(function() {
            var localiteepouxt1 = $(this).val();
            var libLocalitetEpoux1 = $("#code_localite_t_epoux_1 option:selected").text();

            if(localiteepouxt1 != '' || localiteepouxt1 != null){
                // alert(localiteepouxt1);
                $("#lieu_naissance_t_epoux_1").val(libLocalitetEpoux1);
                $("div.autrelieunaissancetemoinepoux1").addClass("d-none");

            }
            if(localiteepouxt1 == 'LOC_4247'){
               $("div.autrelieunaissancetemoinepoux1").removeClass("d-none");
               $("#lieu_naissance_t_epoux_1").val("");
            }
        });

        $("#code_localite_t_epoux_2").change(function() {
            var localiteepouxt2 = $(this).val();

            var libLocalitetEpoux2 = $("#code_localite_t_epoux_2 option:selected").text();

            if(localiteepouxt2 != '' || localiteepouxt2 != null){
                // alert(localiteepouxt2);
                $("#lieu_naissance_t_epoux_2").val(libLocalitetEpoux2);
                $("div.autrelieunaissancetemoinepoux2").addClass("d-none");
            }
            if(localiteepouxt2 == 'LOC_4247'){
                $("div.autrelieunaissancetemoinepoux2").removeClass("d-none");
                $("#lieu_naissance_t_epoux_2").val("");
            }

            // if(localiteepouxt2 == '' || localiteepouxt2 == null){
            //     $("div.autrelieunaissanceepoux").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepoux2").attr("disabled","disabled");
            // }
            // if(localiteepouxt2 == "autres_localite_t_epoux_2"){
            //    $("div.autrelieunaissancetemoinepoux2").removeClass("d-none");
            //     $("input.autrelieunaissancetemoinepoux2").removeAttr("disabled");
            //     $("select.code_localite_t_epoux_2").attr("disabled","disabled");

            // }else{
            //     $("div.autrelieunaissancetemoinepoux2").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepoux2").attr("disabled","disabled");
            // }
        });


        $("#cec_epoux").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_cec_epoux"){
                // alert(cq)
                $("div.newcecepoux").removeClass("d-none");
                $("input.newcecepoux").removeAttr("disabled");
            }else{
                $("div.newcecepoux").addClass("d-none");
                $("input.newcecepoux").attr("disabled","disabled");
            }
        });


        // $("#code_localite_epouse").change(function() {
        //     var localiteepouse = $(this).val();

        //     var libLocalitetEpouse = $("#code_localite_epouse option:selected").text();

        //     if(localiteepouxt2 != '' || localiteepouxt2 != null){
        //         // alert(localiteepouxt2);
        //         $("#lieu_naissance_t_epoux_2").val(libLocalitetEpouse);
        //         $("div.autrelieunaissancetemoinepouse").addClass("d-none");
        //     }
        //     if(localiteepouxt2 == 'LOC_4247'){
        //         $("div.autrelieunaissancetemoinepouse").removeClass("d-none");
        //         $("#lieu_naissance_t_epoux_2").val(libLocalitetEpouse);
        //     }

            // if(localiteepouse == '' || localiteepouse == null){
            //     $("div.autrelieunaissanceepouse").addClass("d-none");
            //     $("input.autrelieunaissanceepouse").attr("disabled","disabled");
            // }
            // if(localiteepouse == "autres_localite_epouse"){
            //    $("div.autrelieunaissanceepouse").removeClass("d-none");
            //     $("input.autrelieunaissanceepouse").removeAttr("disabled");
            //     $("select.code_localite_epouse").attr("disabled","disabled");

            // }else{
            //     $("div.autrelieunaissanceepouse").addClass("d-none");
            //     $("input.autrelieunaissanceepouse").attr("disabled","disabled");
            // }
        //});
        //temoin epouse
        $("#code_localite_t_epouse_1").change(function() {
            var localiteepouset1 = $(this).val();

            var libLocalitetEpouse1 = $("#code_localite_t_epouse_1 option:selected").text();

            if(localiteepouset1 != '' || localiteepouset1 != null){
                // alert(localiteepouset1);
                $("#lieu_naissance_t_epouse_1").val(libLocalitetEpouse1);
                $("div.autrelieunaissancetemoinepouse1").addClass("d-none");
            }
            if(localiteepouset1 == 'LOC_4247'){
                $("div.autrelieunaissancetemoinepouse1").removeClass("d-none");
                $("#lieu_naissance_t_epouse_1").val("");
            }


            // if(localiteepouset1 == '' || localiteepouset1 == null){
            //     $("div.autrelieunaissanceepouse").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse1").attr("disabled","disabled");
            // }
            // if(localiteepouset1 == "autres_localite_t_epouse_1"){
            //    $("div.autrelieunaissancetemoinepouse1").removeClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse1").removeAttr("disabled");
            //     $("select.code_localite_t_epouse_1").attr("disabled","disabled");

            // }else{
            //     $("div.autrelieunaissancetemoinepouse1").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse1").attr("disabled","disabled");
            // }
        });
        $("#code_localite_t_epouse_2").change(function() {
            var localiteepouset2 = $(this).val();

            var libLocalitetEpouse2 = $("#code_localite_t_epouse_2 option:selected").text();

            if(localiteepouset2 != '' || localiteepouset2 != null){
                // alert(localiteepouset2);
                $("#lieu_naissance_t_epouse_2").val(libLocalitetEpouse2);
                $("div.autrelieunaissancetemoinepouse2").addClass("d-none");
            }
            if(localiteepouset2 == 'LOC_4247'){
                $("div.autrelieunaissancetemoinepouse2").removeClass("d-none");
                $("#lieu_naissance_t_epouse_2").val("");
            }

            // if(localiteepouset2 == '' || localiteepouset2 == null){
            //     $("div.autrelieunaissanceepouse").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse2").attr("disabled","disabled");
            // }
            // if(localiteepouset2 == "autres_localite_t_epouse_2"){
            //    $("div.autrelieunaissancetemoinepouse2").removeClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse2").removeAttr("disabled");
            //     $("select.code_localite_t_epouse_2").attr("disabled","disabled");

            // }else{
            //     $("div.autrelieunaissancetemoinepouse2").addClass("d-none");
            //     $("input.autrelieunaissancetemoinepouse2").attr("disabled","disabled");
            // }
        });

        $("#cec_epouse").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_cec_epouse"){
                // alert(cq)
                $("div.newcecepouse").removeClass("d-none");
                $("input.newcecepouse").removeAttr("disabled");
            }else{
                $("div.newcecepouse").addClass("d-none");
                $("input.newcecepouse").attr("disabled","disabled");
            }
        });


        // $("#lieu_naissance_t_epoux_1").change(function (e) {
        //     e.preventDefault();

        //     var lNaisEpouxT1 = $(this).val();
        //     if(lNaisEpouxT1 == "communes_t_epoux_1"){
        //         // alert(cq)
        //         $("div.communestemoinepoux1").removeClass("d-none");
        //         $("select.communestemoinepoux1").removeAttr("disabled");
        //         $("div.districtstemoinepoux1").addClass("d-none");
        //         $("select.districtstemoinepoux1").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepoux1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux1").attr("disabled","disabled");

        //     }else if(lNaisEpouxT1 == "districts_t_epoux_1"){
        //         $("div.districtstemoinepoux1").removeClass("d-none");
        //         $("select.districtstemoinepoux1").removeAttr("disabled");
        //         $("div.communestemoinepoux1").addClass("d-none");
        //         $("select.communestemoinepoux1").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepoux1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux1").attr("disabled","disabled");


        //     }else if(lNaisEpouxT1 == "autres_localite_t_epoux_1"){
        //        $("div.autrelieunaissancetemoinepoux1").removeClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux1").removeAttr("disabled","disabled");
        //         $("div.communestemoinepoux1").addClass("d-none");
        //         $("select.communestemoinepoux1").attr("disabled","disabled");
        //         $("div.districtstemoinepoux1").addClass("d-none");
        //         $("select.districtstemoinepoux1").attr("disabled","disabled");

        //     }
        //     else{
        //         $("div.autrelieunaissancetemoinepoux1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux1").attr("disabled","disabled");
        //         $("div.communestemoinepoux1").addClass("d-none");
        //         $("select.communestemoinepoux1").attr("disabled","disabled");
        //         $("div.districtstemoinepoux1").addClass("d-none");
        //         $("select.districtstemoinepoux1").attr("disabled","disabled");

        //     }
        // });

        // $("#lieu_naissance_t_epoux_2").change(function (e) {
        //     e.preventDefault();

        //     var lNaisEpouxT2 = $(this).val();
        //     if(lNaisEpouxT2 == "communes_t_epoux_2"){
        //         // alert(cq)
        //         $("div.communestemoinepoux2").removeClass("d-none");
        //         $("select.communestemoinepoux2").removeAttr("disabled");
        //         $("div.districtstemoinepoux2").addClass("d-none");
        //         $("select.districtstemoinepoux2").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepoux2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux2").attr("disabled","disabled");

        //     }else if(lNaisEpouxT2 == "districts_t_epoux_2"){
        //         $("div.districtstemoinepoux2").removeClass("d-none");
        //         $("select.districtstemoinepoux2").removeAttr("disabled");
        //         $("div.communestemoinepoux2").addClass("d-none");
        //         $("select.communestemoinepoux2").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepoux2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux2").attr("disabled","disabled");


        //     }else if(lNaisEpouxT2 == "autres_localite_t_epoux_2"){
        //        $("div.autrelieunaissancetemoinepoux2").removeClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux2").removeAttr("disabled","disabled");
        //         $("div.communestemoinepoux2").addClass("d-none");
        //         $("select.communestemoinepoux2").attr("disabled","disabled");
        //         $("div.districtstemoinepoux2").addClass("d-none");
        //         $("select.districtstemoinepoux2").attr("disabled","disabled");

        //     }
        //     else{
        //         $("div.autrelieunaissancetemoinepoux2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepoux2").attr("disabled","disabled");
        //         $("div.communestemoinepoux2").addClass("d-none");
        //         $("select.communestemoinepoux2").attr("disabled","disabled");
        //         $("div.districtstemoinepoux2").addClass("d-none");
        //         $("select.districtstemoinepoux2").attr("disabled","disabled");

        //     }
        // });

        // //EPOUSE TEMOIN
        // $("#lieu_naissance_t_epouse_1").change(function (e) {
        //     e.preventDefault();

        //     var lNaisEpouseT1 = $(this).val();
        //     if(lNaisEpouseT1 == "communes_t_epouse_1"){
        //         // alert(cq)
        //         $("div.communestemoinepouse1").removeClass("d-none");
        //         $("select.communestemoinepouse1").removeAttr("disabled");
        //         $("div.districtstemoinepouse1").addClass("d-none");
        //         $("select.districtstemoinepouse1").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepouse1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse1").attr("disabled","disabled");

        //     }else if(lNaisEpouseT1 == "districts_t_epouse_1"){
        //         $("div.districtstemoinepouse1").removeClass("d-none");
        //         $("select.districtstemoinepouse1").removeAttr("disabled");
        //         $("div.communestemoinepouse1").addClass("d-none");
        //         $("select.communestemoinepouse1").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepouse1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse1").attr("disabled","disabled");


        //     }else if(lNaisEpouseT1 == "autres_localite_t_epouse_1"){
        //        $("div.autrelieunaissancetemoinepouse1").removeClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse1").removeAttr("disabled","disabled");
        //         $("div.communestemoinepouse1").addClass("d-none");
        //         $("select.communestemoinepouse1").attr("disabled","disabled");
        //         $("div.districtstemoinepouse1").addClass("d-none");
        //         $("select.districtstemoinepouse1").attr("disabled","disabled");

        //     }
        //     else{
        //         $("div.autrelieunaissancetemoinepouse1").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse1").attr("disabled","disabled");
        //         $("div.communestemoinepouse1").addClass("d-none");
        //         $("select.communestemoinepouse1").attr("disabled","disabled");
        //         $("div.districtstemoinepouse1").addClass("d-none");
        //         $("select.districtstemoinepouse1").attr("disabled","disabled");

        //     }
        // });

        // $("#lieu_naissance_t_epouse_2").change(function (e) {
        //     e.preventDefault();

        //     var lNaisEpouseT2 = $(this).val();
        //     if(lNaisEpouseT2 == "communes_t_epouse_2"){
        //         // alert(cq)
        //         $("div.communestemoinepouse2").removeClass("d-none");
        //         $("select.communestemoinepouse2").removeAttr("disabled");
        //         $("div.districtstemoinepouse2").addClass("d-none");
        //         $("select.districtstemoinepouse2").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepouse2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse2").attr("disabled","disabled");

        //     }else if(lNaisEpouseT2 == "districts_t_epouse_2"){
        //         $("div.districtstemoinepouse2").removeClass("d-none");
        //         $("select.districtstemoinepouse2").removeAttr("disabled");
        //         $("div.communestemoinepouse2").addClass("d-none");
        //         $("select.communestemoinepouse2").attr("disabled","disabled");
        //         $("div.autrelieunaissancetemoinepouse2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse2").attr("disabled","disabled");


        //     }else if(lNaisEpouseT2 == "autres_localite_t_epouse_2"){
        //        $("div.autrelieunaissancetemoinepouse2").removeClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse2").removeAttr("disabled","disabled");
        //         $("div.communestemoinepouse2").addClass("d-none");
        //         $("select.communestemoinepouse2").attr("disabled","disabled");
        //         $("div.districtstemoinepouse2").addClass("d-none");
        //         $("select.districtstemoinepouse2").attr("disabled","disabled");

        //     }
        //     else{
        //         $("div.autrelieunaissancetemoinepouse2").addClass("d-none");
        //         $("input.autrelieunaissancetemoinepouse2").attr("disabled","disabled");
        //         $("div.communestemoinepouse2").addClass("d-none");
        //         $("select.communestemoinepouse2").attr("disabled","disabled");
        //         $("div.districtstemoinepouse2").addClass("d-none");
        //         $("select.districtstemoinepouse2").attr("disabled","disabled");

        //     }
        // });

        $("#numero_acte_mariage_epoux").blur(function (e) {
            e.preventDefault();
            var num_acte_epoux = $(this).val();
            var url = "{{ route('acteMariage.search', ':id') }}";
            url = url.replace(":id", num_acte_epoux);

            if(num_acte_epoux != "" || num_acte_epoux != null){

                $.get(url, function(reponse){
                    if(reponse.code == "99"){

                        // $(".over-loader-page").fadeIn(600);
                        // flashAlert("dfvd","warning",reponse.message);
                        $(".numajugementdivorceepoux").fadeIn(3000);
                        $("#numero_jugement_divorce_epoux").prop("disabled",false);
                        $(".numactedecesepouse").fadeIn(3000);
                        $("#numero_acte_deces_epouse").prop("disabled",false);

                    }else{
                        // alert("Aucun acte trouvé");
                        // flashAlert("Réponse","error","Aucun acte trouvé");
                        $(".numajugementdivorceepoux").fadeOut();
                        $("#numero_jugement_divorce_epoux").prop("disabled",true);
                        $(".numactedecesepouse").fadeOut();
                        $(".numactedecesepoux").fadeOut();
                        $("#numero_acte_deces_epouse").prop("disabled",true);

                    }
                });
            }

        });


        // Variable globale pour stocker les données de recherche
        var donneesEpouxTrouvees = null;

        $("#rechercherEpoux").on("click", function(event){
            event.preventDefault();

            var numero_acte_naissance = $("#numero_acte_naissance_epoux").val().trim();
            if(!numero_acte_naissance) {
                alert("Veuillez saisir un numéro d'acte de naissance");
                return;
            }

            // Afficher un loader
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Recherche...');
            $(this).prop('disabled', true);

            var data = {
                numero_acte_naissance: numero_acte_naissance
            };

            $.post("{{ route('declarationMariage.recherchePersonne') }}", data, function (response) {
                // Restaurer le bouton
                $("#rechercherEpoux").html('<i class="fa fa-search"></i> Rechercher');
                $("#rechercherEpoux").prop('disabled', false);

                if(response.code == "200"){
                    // Stocker les données pour utilisation ultérieure
                    donneesEpouxTrouvees = response;

                    // Afficher les résultats dans la modal
                    $("#identite_epoux").text((response.nom || '') + ' ' + (response.prenom || ''));

                    // Formater la date de naissance
                    var dateNaissanceFormatee = 'Non spécifiée';
                    if(response.date_naissance) {
                        var date = new Date(response.date_naissance);
                        var options = { year: 'numeric', month: 'long', day: 'numeric' };
                        dateNaissanceFormatee = date.toLocaleDateString('fr-FR', options);
                    }
                    $("#date_naissance_result_epoux").text(dateNaissanceFormatee);

                    $("#sexe_result_epoux").text(response.sexe == 'M' ? 'Masculin' : (response.sexe == 'F' ? 'Féminin' : 'Non spécifié'));
                    $("#lieu_naissance_result_epoux").text(response.lib_lieu_naissance || 'Non spécifié');
                    $("#parents_result_epoux").text((response.pere || 'Père non spécifié') + ' / ' + (response.mere || 'Mère non spécifiée'));

                    // Afficher la zone de résultats et le bouton de confirmation
                    $("#resultats_epoux").show();
                    $("#confirmer_epoux").show();

                    // Masquer le champ de recherche
                    $("#numero_acte_naissance_epoux").prop('readonly', true);
                    $("#rechercherEpoux").hide();

                } else {
                    // Masquer les résultats en cas d'erreur
                    $("#resultats_epoux").hide();
                    $("#confirmer_epoux").hide();
                    flashAlert("Recherche échouée", "error", response.message);
                }
            }).fail(function() {
                $("#rechercherEpoux").html('<i class="fa fa-search"></i> Rechercher');
                $("#rechercherEpoux").prop('disabled', false);
                flashAlert("Erreur", "error", "Erreur de connexion lors de la recherche");
            });

            return false;
        });

        // Bouton de confirmation pour l'époux
        $("#confirmer_epoux").on("click", function(){
            if(donneesEpouxTrouvees) {
                // Remplir le formulaire avec les données trouvées
                $("#nom_epoux").val(donneesEpouxTrouvees.nom || '');
                $("#prenom_epoux").val(donneesEpouxTrouvees.prenom || '');
                $("#date_naissance_epoux").val(donneesEpouxTrouvees.date_naissance || '');
                $("#num_acte_naissance_epoux").val(donneesEpouxTrouvees.numero_ancien_acte || '');
                $("#date_emission_acte_naissance_epoux").val(donneesEpouxTrouvees.dateEmisAN || '');
                $("#nom_pere_epoux").val(donneesEpouxTrouvees.pere || '');
                $("#nom_mere_epoux").val(donneesEpouxTrouvees.mere || '');

                // Gérer la nationalité et la profession
                if(donneesEpouxTrouvees.code_nationalite) {
                    $("#code_nationalite_epoux").val(donneesEpouxTrouvees.code_nationalite).trigger('change');
                }
                if(donneesEpouxTrouvees.code_profession) {
                    $("#code_profession_epoux").val(donneesEpouxTrouvees.code_profession);
                }

                // Remplir le lieu de naissance et le centre d'état civil selon la nationalité
                // Pour les Congolais (nationalité locale), utiliser les champs normaux
                if(donneesEpouxTrouvees.lieu_naissance && donneesEpouxTrouvees.lieu_naissance !== 'LOC_4247') {
                    // Localité congolaise - utiliser le dropdown de localité
                    $("#code_localite_epoux").val(donneesEpouxTrouvees.lieu_naissance).trigger('change');

                    // Remplir le centre d'état civil une fois que les CEC sont chargés
                    if(donneesEpouxTrouvees.code_cec_naissance) {
                        // Attendre que les CEC se chargent puis sélectionner le bon
                        setTimeout(function() {
                            $("#code_cec_epoux").val(donneesEpouxTrouvees.code_cec_naissance).trigger('change');

                            // Si la sélection n'a pas fonctionné, essayer avec le libellé
                            if($("#code_cec_epoux").val() === null || $("#code_cec_epoux").val() === '') {
                                // Chercher l'option par son texte
                                $("#code_cec_epoux option").each(function() {
                                    if($(this).text().toLowerCase().includes(donneesEpouxTrouvees.cec_naissance.toLowerCase())) {
                                        $(this).prop('selected', true);
                                        $("#code_cec_epoux").trigger('change');
                                        return false; // Break the loop
                                    }
                                });
                            }
                        }, 1000); // Augmenter le délai à 1 seconde
                    }
                } else if(donneesEpouxTrouvees.lieu_naissance === 'LOC_4247') {
                    // Étranger - utiliser les champs texte spéciaux
                    $("#lieu_naissance_epoux").val(donneesEpouxTrouvees.lib_lieu_naissance || '');
                    $("#cec_naissance_epoux").val(donneesEpouxTrouvees.cec_naissance || '');
                }

                // Mettre tous les champs identifiés en mode lecture seule
                $("#nom_epoux").prop('readonly', true).addClass('readonly-field');
                $("#prenom_epoux").prop('readonly', true).addClass('readonly-field');
                $("#date_naissance_epoux").prop('readonly', true).addClass('readonly-field');
                $("#num_acte_naissance_epoux").prop('readonly', true).addClass('readonly-field');
                $("#date_emission_acte_naissance_epoux").prop('readonly', true).addClass('readonly-field');
                $("#nom_pere_epoux").prop('readonly', true).addClass('readonly-field');
                $("#nom_mere_epoux").prop('readonly', true).addClass('readonly-field');
                $("#code_nationalite_epoux").prop('disabled', true).addClass('readonly-field');
                $("#code_profession_epoux").prop('disabled', true).addClass('readonly-field');
                $("#code_localite_epoux").prop('disabled', true).addClass('readonly-field');
                $("#code_cec_epoux").prop('disabled', true).addClass('readonly-field');

                // Afficher l'identité dans le formulaire principal
                $("#nom_prenom_epoux_trouve").text((donneesEpouxTrouvees.nom || '') + ' ' + (donneesEpouxTrouvees.prenom || ''));

                // Formater la date de naissance pour l'affichage principal
                var dateNaissanceEpouxFormatee = 'Non spécifiée';
                if(donneesEpouxTrouvees.date_naissance) {
                    var date = new Date(donneesEpouxTrouvees.date_naissance);
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };
                    dateNaissanceEpouxFormatee = date.toLocaleDateString('fr-FR', options);
                }
                $("#date_naissance_epoux_trouve").text(dateNaissanceEpouxFormatee);

                $("#numero_acte_epoux_trouve").text(donneesEpouxTrouvees.numero_ancien_acte || '');
                $("#identite_trouvee_epoux").show();

                // Fermer la modal
                $(".epoux-search-modal-lg").modal("hide");

                // Afficher un message de succès
                flashAlert("Succès", "success", "Informations de l'époux remplies automatiquement");

                // Réinitialiser la modal pour une prochaine recherche
                resetModalEpoux();
            }
        });

        // Fonction pour réinitialiser la modal époux
        function resetModalEpoux() {
            $("#numero_acte_naissance_epoux").val('').prop('readonly', false);
            $("#rechercherEpoux").show().html('<i class="fa fa-search"></i> Rechercher');
            $("#resultats_epoux").hide();
            $("#confirmer_epoux").hide();
            donneesEpouxTrouvees = null;
        }

        // Réinitialiser la modal quand elle se ferme
        $('.epoux-search-modal-lg').on('hidden.bs.modal', function () {
            resetModalEpoux();
        });

        // Variable globale pour stocker les données de recherche épouse
        var donneesEpouseTrouvees = null;

        $("#rechercherEpouse").on("click", function(event){
            event.preventDefault();

            var numero_acte_naissance = $("#numero_acte_naissance_epouse").val().trim();
            if(!numero_acte_naissance) {
                alert("Veuillez saisir un numéro d'acte de naissance");
                return;
            }

            // Afficher un loader
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Recherche...');
            $(this).prop('disabled', true);

            var data = {
                numero_acte_naissance: numero_acte_naissance
            };

            $.post("{{ route('declarationMariage.recherchePersonne') }}", data, function (response) {
                // Restaurer le bouton
                $("#rechercherEpouse").html('<i class="fa fa-search"></i> Rechercher');
                $("#rechercherEpouse").prop('disabled', false);

                if(response.code == "200"){
                    // Stocker les données pour utilisation ultérieure
                    donneesEpouseTrouvees = response;

                    // Afficher les résultats dans la modal
                    $("#identite_epouse").text((response.nom || '') + ' ' + (response.prenom || ''));

                    // Formater la date de naissance
                    var dateNaissanceFormatee = 'Non spécifiée';
                    if(response.date_naissance) {
                        var date = new Date(response.date_naissance);
                        var options = { year: 'numeric', month: 'long', day: 'numeric' };
                        dateNaissanceFormatee = date.toLocaleDateString('fr-FR', options);
                    }
                    $("#date_naissance_result_epouse").text(dateNaissanceFormatee);

                    $("#sexe_result_epouse").text(response.sexe == 'M' ? 'Masculin' : (response.sexe == 'F' ? 'Féminin' : 'Non spécifié'));
                    $("#lieu_naissance_result_epouse").text(response.lib_lieu_naissance || 'Non spécifié');
                    $("#parents_result_epouse").text((response.pere || 'Père non spécifié') + ' / ' + (response.mere || 'Mère non spécifiée'));

                    // Afficher la zone de résultats et le bouton de confirmation
                    $("#resultats_epouse").show();
                    $("#confirmer_epouse").show();

                    // Masquer le champ de recherche
                    $("#numero_acte_naissance_epouse").prop('readonly', true);
                    $("#rechercherEpouse").hide();

                } else {
                    // Masquer les résultats en cas d'erreur
                    $("#resultats_epouse").hide();
                    $("#confirmer_epouse").hide();
                    flashAlert("Recherche échouée", "error", response.message);
                }
            }).fail(function() {
                $("#rechercherEpouse").html('<i class="fa fa-search"></i> Rechercher');
                $("#rechercherEpouse").prop('disabled', false);
                flashAlert("Erreur", "error", "Erreur de connexion lors de la recherche");
            });

            return false;
        });

        // Bouton de confirmation pour l'épouse
        $("#confirmer_epouse").on("click", function(){
            if(donneesEpouseTrouvees) {
                // Remplir le formulaire avec les données trouvées
                $("#nom_epouse").val(donneesEpouseTrouvees.nom || '');
                $("#prenom_epouse").val(donneesEpouseTrouvees.prenom || '');
                $("#date_naissance_epouse").val(donneesEpouseTrouvees.date_naissance || '');
                $("#num_acte_naissance_epouse").val(donneesEpouseTrouvees.numero_ancien_acte || '');
                $("#date_emission_acte_naissance_epouse").val(donneesEpouseTrouvees.dateEmisAN || '');
                $("#nom_pere_epouse").val(donneesEpouseTrouvees.pere || '');
                $("#nom_mere_epouse").val(donneesEpouseTrouvees.mere || '');

                // Gérer la nationalité et la profession
                if(donneesEpouseTrouvees.code_nationalite) {
                    $("#code_nationalite_epouse").val(donneesEpouseTrouvees.code_nationalite).trigger('change');
                }
                if(donneesEpouseTrouvees.code_profession) {
                    $("#code_profession_epouse").val(donneesEpouseTrouvees.code_profession);
                }

                // Remplir le lieu de naissance et le centre d'état civil selon la nationalité
                // Pour les Congolais (nationalité locale), utiliser les champs normaux
                if(donneesEpouseTrouvees.lieu_naissance && donneesEpouseTrouvees.lieu_naissance !== 'LOC_4247') {
                    // Localité congolaise - utiliser le dropdown de localité
                    $("#code_localite_epouse").val(donneesEpouseTrouvees.lieu_naissance).trigger('change');

                    // Remplir le centre d'état civil une fois que les CEC sont chargés
                    if(donneesEpouseTrouvees.code_cec_naissance) {
                        // Attendre que les CEC se chargent puis sélectionner le bon
                        setTimeout(function() {
                            $("#code_cec_epouse").val(donneesEpouseTrouvees.code_cec_naissance).trigger('change');

                            // Si la sélection n'a pas fonctionné, essayer avec le libellé
                            if($("#code_cec_epouse").val() === null || $("#code_cec_epouse").val() === '') {
                                // Chercher l'option par son texte
                                $("#code_cec_epouse option").each(function() {
                                    if($(this).text().toLowerCase().includes(donneesEpouseTrouvees.cec_naissance.toLowerCase())) {
                                        $(this).prop('selected', true);
                                        $("#code_cec_epouse").trigger('change');
                                        return false; // Break the loop
                                    }
                                });
                            }
                        }, 1000); // Augmenter le délai à 1 seconde
                    }
                } else if(donneesEpouseTrouvees.lieu_naissance === 'LOC_4247') {
                    // Étranger - utiliser les champs texte spéciaux
                    $("#lieu_naissance_epouse").val(donneesEpouseTrouvees.lib_lieu_naissance || '');
                    $("#cec_naissance_epouse").val(donneesEpouseTrouvees.cec_naissance || '');
                }

                // Mettre tous les champs identifiés en mode lecture seule
                $("#nom_epouse").prop('readonly', true).addClass('readonly-field');
                $("#prenom_epouse").prop('readonly', true).addClass('readonly-field');
                $("#date_naissance_epouse").prop('readonly', true).addClass('readonly-field');
                $("#num_acte_naissance_epouse").prop('readonly', true).addClass('readonly-field');
                $("#date_emission_acte_naissance_epouse").prop('readonly', true).addClass('readonly-field');
                $("#nom_pere_epouse").prop('readonly', true).addClass('readonly-field');
                $("#nom_mere_epouse").prop('readonly', true).addClass('readonly-field');
                $("#code_nationalite_epouse").prop('disabled', true).addClass('readonly-field');
                $("#code_profession_epouse").prop('disabled', true).addClass('readonly-field');
                $("#code_localite_epouse").prop('disabled', true).addClass('readonly-field');
                $("#code_cec_epouse").prop('disabled', true).addClass('readonly-field');

                // Afficher l'identité dans le formulaire principal
                $("#nom_prenom_epouse_trouve").text((donneesEpouseTrouvees.nom || '') + ' ' + (donneesEpouseTrouvees.prenom || ''));

                // Formater la date de naissance pour l'affichage principal
                var dateNaissanceEpouseFormatee = 'Non spécifiée';
                if(donneesEpouseTrouvees.date_naissance) {
                    var date = new Date(donneesEpouseTrouvees.date_naissance);
                    var options = { year: 'numeric', month: 'long', day: 'numeric' };
                    dateNaissanceEpouseFormatee = date.toLocaleDateString('fr-FR', options);
                }
                $("#date_naissance_epouse_trouve").text(dateNaissanceEpouseFormatee);

                $("#numero_acte_epouse_trouve").text(donneesEpouseTrouvees.numero_ancien_acte || '');
                $("#identite_trouvee_epouse").show();

                // Fermer la modal
                $(".epouse-search-modal-lg").modal("hide");

                // Afficher un message de succès
                flashAlert("Succès", "success", "Informations de l'épouse remplies automatiquement");

                // Réinitialiser la modal pour une prochaine recherche
                resetModalEpouse();
            }
        });

        // Fonction pour réinitialiser la modal épouse
        function resetModalEpouse() {
            $("#numero_acte_naissance_epouse").val('').prop('readonly', false);
            $("#rechercherEpouse").show().html('<i class="fa fa-search"></i> Rechercher');
            $("#resultats_epouse").hide();
            $("#confirmer_epouse").hide();
            donneesEpouseTrouvees = null;
        }

        // Réinitialiser la modal quand elle se ferme
        $('.epouse-search-modal-lg').on('hidden.bs.modal', function () {
            resetModalEpouse();
        });

        // ========== BOUTONS VIDER ==========

        // Bouton vider pour l'époux
        $("#clear_epoux").on("click", function(){
            if(confirm("Êtes-vous sûr de vouloir vider toutes les informations de l'époux ?")) {
                // Vider tous les champs de l'époux
                $("#nom_epoux").val('');
                $("#prenom_epoux").val('');
                $("#date_naissance_epoux").val('');
                $("#num_acte_naissance_epoux").val('');
                $("#date_emission_acte_naissance_epoux").val('');
                $("#nom_pere_epoux").val('');
                $("#nom_mere_epoux").val('');
                $("#code_nationalite_epoux").val('').trigger('change');
                $("#code_profession_epoux").val('').trigger('change');

                // Retirer le mode lecture seule de tous les champs
                $("#nom_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#prenom_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#date_naissance_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#num_acte_naissance_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#date_emission_acte_naissance_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#nom_pere_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#nom_mere_epoux").prop('readonly', false).removeClass('readonly-field');
                $("#code_nationalite_epoux").prop('disabled', false).removeClass('readonly-field');
                $("#code_profession_epoux").prop('disabled', false).removeClass('readonly-field');
                $("#code_localite_epoux").prop('disabled', false).removeClass('readonly-field');
                $("#code_cec_epoux").prop('disabled', false).removeClass('readonly-field');

                // Masquer la zone d'identité trouvée
                $("#identite_trouvee_epoux").hide();

                // Réinitialiser les données stockées
                donneesEpouxTrouvees = null;

                flashAlert("Succès", "success", "Informations de l'époux vidées");
            }
        });

        // Bouton vider pour l'épouse
        $("#clear_epouse").on("click", function(){
            if(confirm("Êtes-vous sûr de vouloir vider toutes les informations de l'épouse ?")) {
                // Vider tous les champs de l'épouse
                $("#nom_epouse").val('');
                $("#prenom_epouse").val('');
                $("#date_naissance_epouse").val('');
                $("#num_acte_naissance_epouse").val('');
                $("#date_emission_acte_naissance_epouse").val('');
                $("#nom_pere_epouse").val('');
                $("#nom_mere_epouse").val('');
                $("#code_nationalite_epouse").val('').trigger('change');
                $("#code_profession_epouse").val('').trigger('change');

                // Retirer le mode lecture seule de tous les champs
                $("#nom_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#prenom_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#date_naissance_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#num_acte_naissance_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#date_emission_acte_naissance_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#nom_pere_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#nom_mere_epouse").prop('readonly', false).removeClass('readonly-field');
                $("#code_nationalite_epouse").prop('disabled', false).removeClass('readonly-field');
                $("#code_profession_epouse").prop('disabled', false).removeClass('readonly-field');
                $("#code_localite_epouse").prop('disabled', false).removeClass('readonly-field');
                $("#code_cec_epouse").prop('disabled', false).removeClass('readonly-field');

                // Masquer la zone d'identité trouvée
                $("#identite_trouvee_epouse").hide();

                // Réinitialiser les données stockées
                donneesEpouseTrouvees = null;

                flashAlert("Succès", "success", "Informations de l'épouse vidées");
            }
        });

        $("#lieu_ceremonie_mariage").on("change", function(){
            var lieu_Mariage = $(this).val();

            if(lieu_Mariage != null || lieu_Mariage != ""){

                if(lieu_Mariage == 'Hors centre d\'état civil'){
                    // alert(lieu_Mariage)
                    $('.adresseCeremonie').removeClass("d-none");
                    $('#adresse_celebration').prop("disabled",false);
                }else{
                    // alert("rien")
                    $('.adresseCeremonie').addClass("d-none");
                    $('#adresse_celebration').prop("disabled",true);
                }

            }
            return false;
        });

        $("#date_ceremonie_mariage").blur(function() {
            var durree = $(this).val();
            var today = new Date();

            if(durree != null || durree != ''){
                var date2 = new Date(durree);

                // To calculate the time difference of two dates
                var Difference_In_Time = date2.getTime() - today.getTime();
                var n_jours = parseInt(Difference_In_Time / (1000 * 3600 * 24));

                if(n_jours < 60){
                    $(".notification").slideDown(300);
                }else{
                    $(".notification").fadeOut();
                }
                return false;
                // alert(n_jours);
            }
        });

        $("#lieu_ceremonie_mariage").change(function (e) {
            e.preventDefault();

            var lieuC = $(this).val();
            if(lieuC != "" || lieuC != null){
                if(lieuC === 'Hors centre d\'état civil'){
                    $(".notification2").slideDown(300);
                }else{
                    $(".notification2").fadeOut();
                }

            }
        });

        // GESTION ADRESSE EPOUX/EPOUSE/TEMOINS EPOUX/TEMOINS EPOUSE/INFO GENERALE
            //EPOUX
        $(".quartier_epoux").removeClass("d-none");
        // $(".village_epoux").removeClass("d-none");
        $("#residence_externe_epoux").change(function(e) {
            e.preventDefault();

            var pays = $(this).val();
            if(pays == 'Congo'){

                $(".quartier_epoux").removeClass("d-none");
                $("#code_quartier_epoux").removeAttr("disabled");
                $("div.ville_epoux").addClass("d-none");
                $("input.ville_epoux").attr("disabled", "disabled");

            }else{
                $("#code_quartier_epoux").attr("disabled","disabled");
                $(".quartier_epoux").addClass("d-none");
                $("div.ville_epoux").removeClass("d-none");
                $("input.ville_epoux").removeAttr("disabled");

                $("div.new_quartier_epoux").addClass("d-none");
                $("input.new_quartier_epoux").attr("disabled","disabled");
            }
        });

        $("#code_quartier_epoux").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_q_epoux"){
                // alert(cq)
                $("div.new_quartier_epoux").removeClass("d-none");
                $("input.new_quartier_epoux").removeAttr("disabled");
            }else{
                $("div.new_quartier_epoux").addClass("d-none");
                $("input.new_quartier_epoux").attr("disabled","disabled");
            }
        });
            //EPOUSE
        $(".quartier_epouse").removeClass("d-none");
        // $(".village_epouse").removeClass("d-none");
        $("#residence_externe_epouse").change(function(e) {
            e.preventDefault();

            var pays = $(this).val();
            if(pays == 'Congo'){

                $(".quartier_epouse").removeClass("d-none");
                $("#code_quartier_epouse").removeAttr("disabled");
                $("div.ville_epouse").addClass("d-none");
                $("input.ville_epouse").attr("disabled", "disabled");

            }else{
                $("#code_quartier_epouse").attr("disabled","disabled");
                $(".quartier_epouse").addClass("d-none");
                $("div.ville_epouse").removeClass("d-none");
                $("input.ville_epouse").removeAttr("disabled");

                $("div.new_quartier_epouse").addClass("d-none");
                $("input.new_quartier_epouse").attr("disabled","disabled");
            }
        });

        $("#code_quartier_epouse").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_q_epouse"){
                // alert(cq)
                $("div.new_quartier_epouse").removeClass("d-none");
                $("input.new_quartier_epouse").removeAttr("disabled");
            }else{
                $("div.new_quartier_epouse").addClass("d-none");
                $("input.new_quartier_epouse").attr("disabled","disabled");
            }
        });
            //TEMOINS EPOUX
        $(".quartier_temoins_epoux").removeClass("d-none");
        // $(".village_temoins_epoux").removeClass("d-none");
        $("#residence_externe_temoins_epoux").change(function(e) {
            e.preventDefault();

            var pays = $(this).val();
            if(pays == 'Congo'){

                $(".quartier_temoins_epoux").removeClass("d-none");
                $("#code_quartier_temoins_epoux").removeAttr("disabled");
                $("div.ville_temoins_epoux").addClass("d-none");
                $("input.ville_temoins_epoux").attr("disabled", "disabled");

            }else{
                $("#code_quartier_temoins_epoux").attr("disabled","disabled");
                $(".quartier_temoins_epoux").addClass("d-none");
                $("div.ville_temoins_epoux").removeClass("d-none");
                $("input.ville_temoins_epoux").removeAttr("disabled");

                $("div.new_quartier_temoins_epoux").addClass("d-none");
                $("input.new_quartier_temoins_epoux").attr("disabled","disabled");
            }
        });

        $("#code_quartier_temoins_epoux").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_q_temoins_epoux"){
                // alert(cq)
                $("div.new_quartier_temoins_epoux").removeClass("d-none");
                $("input.new_quartier_temoins_epoux").removeAttr("disabled");
            }else{
                $("div.new_quartier_temoins_epoux").addClass("d-none");
                $("input.new_quartier_temoins_epoux").attr("disabled","disabled");
            }
        });
            //TEMOINS EPOUSE
        $(".quartier_temoins_epouse").removeClass("d-none");
        // $(".village_temoins_epouse").removeClass("d-none");
        $("#residence_externe_temoins_epouse").change(function(e) {
            e.preventDefault();

            var pays = $(this).val();
            if(pays == 'Congo'){

                $(".quartier_temoins_epouse").removeClass("d-none");
                $("#code_quartier_temoins_epouse").removeAttr("disabled");
                $("div.ville_temoins_epouse").addClass("d-none");
                $("input.ville_temoins_epouse").attr("disabled", "disabled");

            }else{
                $("#code_quartier_temoins_epouse").attr("disabled","disabled");
                $(".quartier_temoins_epouse").addClass("d-none");
                $("div.ville_temoins_epouse").removeClass("d-none");
                $("input.ville_temoins_epouse").removeAttr("disabled");

                $("div.new_quartier_temoins_epouse").addClass("d-none");
                $("input.new_quartier_temoins_epouse").attr("disabled","disabled");
            }
        });

        $("#code_quartier_temoins_epouse").change(function (e) {
            e.preventDefault();

            var cq = $(this).val();
            if(cq == "new_q_temoins_epouse"){
                // alert(cq)
                $("div.new_quartier_temoins_epouse").removeClass("d-none");
                $("input.new_quartier_temoins_epouse").removeAttr("disabled");
            }else{
                $("div.new_quartier_temoins_epouse").addClass("d-none");
                $("input.new_quartier_temoins_epouse").attr("disabled","disabled");
            }
        });

        //ADRESSE CEREMONIE
        $("#lib_quartier_ceremonie").change(function (e) {
            e.preventDefault();
            $("div.domicile_ceremonie").removeClass("d-none");
            $("div.domicile_numero_ceremonie").removeClass("d-none");
            $("div.domicile_nomvoie_ceremonie").removeClass("d-none");

            var cq = $(this).val();
            if(cq == "new_q_ceremonie"){
                // alert(cq)
                $("div.new_quartier_ceremonie").removeClass("d-none");
                $("div.domicile_ceremonie").removeClass("d-none");
                $("div.domicile_numero_ceremonie").removeClass("d-none");
                $("div.domicile_nomvoie_ceremonie").removeClass("d-none");

                $("input.new_quartier_ceremonie").removeAttr("disabled");
                $("#domicile_ceremonie").removeAttr("disabled");
                $("#domicile_numero_ceremonie").removeAttr("disabled");
                $("#domicile_nomvoie_ceremonie").removeAttr("disabled");
            }else{

                $("div.new_quartier_ceremonie").addClass("d-none");
                $("input.new_quartier_ceremonie").attr("disabled","disabled");
            }
        });



    });

    // Fonction pour pré-remplir le formulaire avec les données existantes
    function preRemplirFormulaire() {
        // Données de la déclaration
        var declaration = @json($declaration);

        // Informations générales
        $("#type_declaration").val(declaration.type_declaration || '');
        $("#date_declaration_mariage").val(declaration.date_declaration_mariage || '');
        $("#date_ceremonie_mariage").val(declaration.date_ceremonie_mariage || '');
        $("#lieu_ceremonie_mariage").val(declaration.lieu_ceremonie_mariage || '');
        $("#option_mariage").val(declaration.code_option_mariage || '');
        $("#regime_mariage").val(declaration.code_regime || '');
        $("#chef_famille").val(declaration.chef_famille || '');
        $("#filiation").val(declaration.code_filiation || '');

        // Informations époux
        if (declaration.epoux) {
            $("#nom_epoux").val(declaration.epoux.nom || '');
            $("#prenom_epoux").val(declaration.epoux.prenom || '');
            $("#date_naissance_epoux").val(declaration.epoux.date_naissance || '');
            $("#lieu_naissance_epoux").val(declaration.epoux.lieu_naissance || '');
            $("#code_localite_epoux").val(declaration.epoux.code_localite || '');
            $("#code_nationalite_epoux").val(declaration.epoux.code_nationalite || '');
            $("#code_profession_epoux").val(declaration.epoux.code_profession || '');
            $("#nom_pere_epoux").val(declaration.epoux.nom_pere || '');
            $("#nom_mere_epoux").val(declaration.epoux.nom_mere || '');
            $("#sit_matrimoniale_epoux").val(declaration.code_situation_mat_epoux || '');
            $("#num_acte_naissance_epoux").val(declaration.numero_acte_naissance_epoux || '');
            $("#date_emission_acte_naissance_epoux").val(declaration.date_emission_acte_naissance_epoux || '');
            $("#numero_acte_mariage_epoux").val(declaration.numero_acte_mariage_epoux || '');
            $("#date_pre_mariage_epoux").val(declaration.date_pre_mariage_epoux || '');
            $("#parent_paternel_epoux").val(declaration.parent_paternel_epoux || '');
            $("#parent_maternel_epoux").val(declaration.parent_maternel_epoux || '');
        }

        // Informations épouse
        if (declaration.epouse) {
            $("#nom_epouse").val(declaration.epouse.nom || '');
            $("#prenom_epouse").val(declaration.epouse.prenom || '');
            $("#date_naissance_epouse").val(declaration.epouse.date_naissance || '');
            $("#lieu_naissance_epouse").val(declaration.epouse.lieu_naissance || '');
            $("#code_localite_epouse").val(declaration.epouse.code_localite || '');
            $("#code_nationalite_epouse").val(declaration.epouse.code_nationalite || '');
            $("#code_profession_epouse").val(declaration.epouse.code_profession || '');
            $("#nom_pere_epouse").val(declaration.epouse.nom_pere || '');
            $("#nom_mere_epouse").val(declaration.epouse.nom_mere || '');
            $("#sit_matrimoniale_epouse").val(declaration.code_situation_mat_epouse || '');
            $("#num_acte_naissance_epouse").val(declaration.numero_acte_naissance_epouse || '');
            $("#date_emission_acte_naissance_epouse").val(declaration.date_emission_acte_naissance_epouse || '');
            $("#numero_acte_mariage_epouse").val(declaration.numero_acte_mariage_epouse || '');
            $("#date_pre_mariage_epouse").val(declaration.date_pre_mariage_epouse || '');
            $("#parent_paternel_epouse").val(declaration.parent_paternel_epouse || '');
            $("#parent_maternel_epouse").val(declaration.parent_maternel_epouse || '');
        }

        // Témoins époux
        if (declaration.temoinHommeEpoux) {
            $("#nom_t_epoux_1").val(declaration.temoinHommeEpoux.nom || '');
            $("#prenom_t_epoux_1").val(declaration.temoinHommeEpoux.prenom || '');
            $("#date_naissance_t_epoux_1").val(declaration.temoinHommeEpoux.date_naissance || '');
            $("#code_localite_t_epoux_1").val(declaration.temoinHommeEpoux.code_localite || '');
            $("#code_nationalite_t_epoux_1").val(declaration.temoinHommeEpoux.code_nationalite || '');
            $("#code_profession_t_epoux_1").val(declaration.temoinHommeEpoux.code_profession || '');
        }

        if (declaration.temoinFemmeEpoux) {
            $("#nom_t_epoux_2").val(declaration.temoinFemmeEpoux.nom || '');
            $("#prenom_t_epoux_2").val(declaration.temoinFemmeEpoux.prenom || '');
            $("#date_naissance_t_epoux_2").val(declaration.temoinFemmeEpoux.date_naissance || '');
            $("#code_localite_t_epoux_2").val(declaration.temoinFemmeEpoux.code_localite || '');
            $("#code_nationalite_t_epoux_2").val(declaration.temoinFemmeEpoux.code_nationalite || '');
            $("#code_profession_t_epoux_2").val(declaration.temoinFemmeEpoux.code_profession || '');
        }

        // Témoins épouse
        if (declaration.temoinHommeEpouse) {
            $("#nom_t_epouse_1").val(declaration.temoinHommeEpouse.nom || '');
            $("#prenom_t_epouse_1").val(declaration.temoinHommeEpouse.prenom || '');
            $("#date_naissance_t_epouse_1").val(declaration.temoinHommeEpouse.date_naissance || '');
            $("#code_localite_t_epouse_1").val(declaration.temoinHommeEpouse.code_localite || '');
            $("#code_nationalite_t_epouse_1").val(declaration.temoinHommeEpouse.code_nationalite || '');
            $("#code_profession_t_epouse_1").val(declaration.temoinHommeEpouse.code_profession || '');
        }

        if (declaration.temoinFemmeEpouse) {
            $("#nom_t_epouse_2").val(declaration.temoinFemmeEpouse.nom || '');
            $("#prenom_t_epouse_2").val(declaration.temoinFemmeEpouse.prenom || '');
            $("#date_naissance_t_epouse_2").val(declaration.temoinFemmeEpouse.date_naissance || '');
            $("#code_localite_t_epouse_2").val(declaration.temoinFemmeEpouse.code_localite || '');
            $("#code_nationalite_t_epouse_2").val(declaration.temoinFemmeEpouse.code_nationalite || '');
            $("#code_profession_t_epouse_2").val(declaration.temoinFemmeEpouse.code_profession || '');
        }

        // Informations complémentaires
        $("#examens_prenuptiaux").val(declaration.examens_prenuptiaux || '1');
        $("#montant_dot").val(declaration.montant_dot || '50000');
        $("#nbre_enfant").val(declaration.nbre_enfant || '0');

        // Mandants
        if (declaration.nom_prenom_mandant_epoux) {
            $("#nom_prenom_mandant_epoux").val(declaration.nom_prenom_mandant_epoux);
            $("#type_mandant").val('mandant_epoux');
        }
        if (declaration.nom_prenom_mandant_epouse) {
            $("#nom_prenom_mandant_epouse").val(declaration.nom_prenom_mandant_epouse);
            $("#type_mandant").val('mandant_epouse');
        }

        // Gérer l'affichage des champs conditionnels selon les données existantes
        gererChampsConditionnels();

        // Déclencher les événements de changement pour mettre à jour les champs dépendants
        $("#option_mariage").trigger('change');
        $("#type_mariage").trigger('change');
        $("#type_mandant").trigger('change');

        // Modifier l'action du formulaire pour la modification
        $("#contactUsForm").attr('action', '{{ route("declarationMariage.update", $declaration->code_declaration_mariage) }}');

        // Ajouter la méthode PUT pour Laravel
        $("#contactUsForm").append('<input type="hidden" name="_method" value="PUT">');
    }

    // Fonction pour gérer l'affichage des champs conditionnels
    function gererChampsConditionnels() {
        var declaration = @json($declaration);

        // Gérer les champs de situation matrimoniale
        if (declaration.code_situation_mat_epoux === 'SMAT_0001') {
            // Célibataire - afficher les champs de pré-mariage
            $(".premariageepoux").removeClass('d-none');
        } else if (declaration.code_situation_mat_epoux === 'SMAT_0002') {
            // Divorcé - afficher les champs d'acte de mariage
            $(".numactemariageepoux").show();
        }

        if (declaration.code_situation_mat_epouse === 'SMAT_0001') {
            // Célibataire - afficher les champs de pré-mariage
            $(".premariageepouse").removeClass('d-none');
        } else if (declaration.code_situation_mat_epouse === 'SMAT_0002') {
            // Divorcée - afficher les champs d'acte de mariage
            $(".numactemariageepouse").show();
        }

        // Gérer l'affichage des blocs de mandant
        if (declaration.nom_prenom_mandant_epoux) {
            $("#bloc_mandant_epoux").show();
            $("#type_mandant").show();
        } else if (declaration.nom_prenom_mandant_epouse) {
            $("#bloc_mandant_epouse").show();
            $("#type_mandant").show();
        }

        // Gérer l'affichage des notifications de procuration
        if (declaration.type_mariage === 'PROCURATION') {
            $("#notificationMsgProcuration").show();
        }
    }




</script>
@endsection
