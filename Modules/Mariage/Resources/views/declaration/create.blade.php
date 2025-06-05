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
@endsection
@section("corps")
        <!-- row -->
        <div class="row" id="validation">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Créer un formulaire type</h4>
                        <div class="d-flex justify-content-end">
                            <!--label class="form-label col-md-3">Type de mariage</label-->
                            <select id="type_mariage"  class="form-control" style="width:200px">
                                 <option value="" selected>Type de mariage</option>
                                <option value="NORMAL">Mariage normal</option>
                                <option value="PROCURATION">Mariage par procuration</option>
                                <!-- <option value="posthume">Mariage à titre posthume</option> -->
                            </select>
                            <select id="type_mandant"  class="form-control" style="width:200px">
                                <option value="" seleccted> Choix du mandant </option>
                                <option value="mandant_epoux">Epoux</option>
                                <option value="mandant_epouse">Epouse</option>
                                <!-- <option value="posthume">Mariage à titre posthume</option> -->
                            </select>
                            <br>

                        </div>

                    </div>
                    <p id="notificationMsgProcuration"  style="background:red; color:white; padding:10px; font-size:15px;font-weight:bold"> <i class="fa fa-warning"></i> Ce type de mariage requiert la présence du mandant  conformément à l'article 152 du code de la famille.</p>
                    <div class="card wizard-content">
                        <div class="card-body">
                           @include('mariage::declaration.form')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('mariage::declaration.madal-search')



@endsection
@section("scripts")

@include("mariage::declaration.js.create")


<script>
     $(document).ready(function(){
        $("#notificationMsgProcuration").hide();
        $("#type_mandant").hide();
        $("#bloc_mandant_epoux").hide();
        $("#bloc_mandant_epouse").hide();
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
        var option = "<option>Selectionnez</option>";

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
                console.log(json); cec_epoux
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


        $("#rechercherEpoux").on("click", function(event){
            event.preventDefault();
            numero_acte_naissance = $("#numero_acte_naissance_epoux").val();
            var data = {
                numero_acte_naissance:numero_acte_naissance
            };
            $.post("{{ route('declarationMariage.recherchePersonne') }}", data,function (response) {

            if(response.code == "200"){

                console.log(response.lieu_naissance);

                $(".epoux-search-modal-lg").modal("hide");
                $("#nom_epoux").val(response.nom);
                $("#prenom_epoux").val(response.prenom);
                $("#date_naissance_epoux").val(response.date_naissance);
                $("#lieu_naissance_epoux").val(response.lieu_naissance);
                $("#num_acte_naissance_epoux").val(numero_acte_naissance);
                $("#code_nationalite_epoux").val(response.lib_nationalite);
                $("#date_emission_acte_naissance_epoux").val(response.dateEmisAN);
                // $("#cec_epoux").val(response.cec_naissance);
                $("#code_profession_epoux").val(response.code_profession);
                $("#cec_epoux").val(response.cec_naissance);
                $("#nom_pere_epoux").val(response.pere);
                $("#nom_mere_epoux").val(response.mere);

            }else{
                flashAlert("Opération échouée","error",response.message);
            }
            });
            return false;
        });

        $("#rechercherEpouse").on("click", function(event){
            event.preventDefault();
            numero_acte_naissance = $("#numero_acte_naissance_epouse").val();
            var data = {
                numero_acte_naissance:numero_acte_naissance
            };
            $.post("{{ route('declarationMariage.recherchePersonne') }}", data,function (response) {

            if(response.code == "200"){

                console.log(response.dateEmisAN);

                $(".epouse-search-modal-lg").modal("hide");
                $("#nom_epouse").val(response.nom);
                $("#prenom_epouse").val(response.prenom);
                $("#date_naissance_epouse").val(response.date_naissance);
                $("#code_localite_epouse").val(response.lieu_naissance);
                $("#num_acte_naissance_epouse").val(numero_acte_naissance);
                $("#code_nationalite_epouse").val(response.lib_nationalite);
                $("#date_emission_acte_naissance_epouse").val(response.dateEmisAN);
                // $("#cec_epouse").val(response.cec_naissance);
                $("#code_profession_epouse").val(response.code_profession);
                $("#cec_epouse").val(response.cec_naissance);
                $("#nom_pere_epouse").val(response.pere);
                $("#nom_mere_epouse").val(response.mere);

            }else{
                flashAlert("Opération échouée","error",response.message);
            }
            });
            return false;
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




</script>
@endsection
