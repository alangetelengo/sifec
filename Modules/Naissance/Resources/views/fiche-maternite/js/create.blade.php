@section("scripts")

<script>
    function dateFrench(dat){
        var date = new Date(dat);
        return date.getDate()+ "/"+(date.getMonth() + 1 )+"/"+date.getFullYear();
    }

    function age() {
        var dateNaissance = $("#date_naissance_enfant").val();
        var age_annee = 0;
        var age_mois = 0;
        var datechoisie_convertie = moment(moment(dateNaissance, 'DD-MM-YYYY')).format('YYYY-MM-DD');
        var age_annee = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'years');
        var age_mois = moment().diff(moment(dateNaissance, 'YYYYMMDD'), 'month');
        return age_annee;
    }



$(function(){

    $("#code_type_document_pere").on('change', function(){
        var typeDoc = $(this).val();
        var libTypeDoc = $("#code_type_document_pere option:selected").text();
        var option = "<option>"+libTypeDoc+"</option>";
        if(typeDoc !="" || typeDoc !=null){
            $("#typeDocumentPere").html(option);
        }
    });

    $("input#numero_document_pere").blur(function(){
        var numeroDocumentPere = $(this).val();
        $("#numDocumentPere").val(numeroDocumentPere);
    });

    $("#code_type_document_mere").on('change', function(){
        var typeDoc = $(this).val();
        var libTypeDoc = $("#code_type_document_mere option:selected").text();
        var option = "<option>"+libTypeDoc+"</option>";
        if(typeDoc !="" || typeDoc !=null){
            $("#typeDocumentMere").html(option);
        }
    });
    $("input#numero_document_mere").blur(function(){
        var numeroDocumentMere = $(this).val();
        $("#numDocumentMere").val(numeroDocumentMere);
    });


    $("#code_type_document_declarant").on('change', function(){
        var typeDoc = $(this).val();
        var libTypeDoc = $("#code_type_document_declarant option:selected").text();
        var option = "<option>"+libTypeDoc+"</option>";
        if(typeDoc !="" || typeDoc !=null){
            $("#typeDocumentDeclarant").html(option);
        }
    });
    $("input#numero_document_declarant").blur(function(){
        var numeroDocumentDeclarant = $(this).val();
        $("#numDocumentDeclarant").val(numeroDocumentDeclarant);
  });


    // var ageEnfant = parseInt(age());
    $("#code_lieu_survenance").on("change", function(){
        var lieuSurvenance = $(this).val();
        if(lieuSurvenance == "LSURV_0001"){
            $("div.formationsanitaire").removeClass("d-none");
            $("#formation_sanitaire_naissance").attr("disabled",false);
        }else{
            $("div.formationsanitaire").addClass("d-none");
            $("#formation_sanitaire_naissance").attr("disabled",true);
        }
    });


    $("#cec_naissance_enfant").change(function (e) {
        e.preventDefault();

        var cq = $(this).val();
        if(cq == "new_cec_naissance_enfant"){
            // alert(cq)
            $("div.newcecenfant").removeClass("d-none");
            $("input.newcecenfant").removeAttr("disabled");
        }else{
            $("div.newcecenfant").addClass("d-none");
            $("input.newcecenfant").attr("disabled","disabled");
        }
    });

    $("#code_localite_pere").change(function (e) {
        e.preventDefault();
        var codeLocalitePere = $(this).val();
        var lieunaispere = $("#code_localite_pere option:selected").text();

        if(codeLocalitePere != 'LOC_4247'){
            $("#lieu_naissance_pere").val(lieunaispere);
        }
    });

    $("#code_localite_mere").change(function (e) {
        e.preventDefault();
        var codeLocaliteMere = $(this).val();
        var lieunaismere = $("#code_localite_mere option:selected").text();

        if(codeLocaliteMere != 'LOC_4247'){
            $("#lieu_naissance_mere").val(lieunaismere);
        }
    });

    $("#code_localite_declarant").change(function (e) {
        e.preventDefault();
        var codeLocaliteDeclarant = $(this).val();
        var lieunaisdeclarant = $("#code_localite_declarant option:selected").text();

        if(codeLocaliteDeclarant != 'LOC_4247'){
            $("#lieu_naissance_declarant").val(lieunaisdeclarant);
        }
    });


    //adresse pere
    $('#domicile_pays_pere').on('change', function () {
        var pays = $('#domicile_pays_pere').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_pere').removeClass('d-none');
            $('div.autredomicile_ville_pere').addClass('d-none');

            $('#domicile_ville_pere').prop('disabled', false);
            $('#domicile_arrondissement_pere').prop('disabled', false);
            $('#domicile_quartier_pere').prop('disabled', false);

        }else{
            $("div.domicile_ville_pere").addClass("d-none");
            $("div.domicile_arrondissement_pere").addClass("d-none");
            $("div.domicile_quartier_pere").addClass('d-none');
            $('#domicile_ville_pere').prop('disabled', true);
            $('#domicile_arrondissement_pere').prop('disabled', true);
            $('#domicile_quartier_pere').prop('disabled', true);

            $('div.autredomicile_ville_pere').removeClass('d-none');
            $('#autredomicile_ville_pere').prop('disabled',false);
        }
    });

    $("#domicile_ville_pere").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_pere").removeClass("d-none");
            $('#domicile_arrondissement_pere').prop('disabled',false);

            var domicilevillepere = $("#domicile_ville_pere option:selected").text();
            var ville = '<option>'+domicilevillepere+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_pere');
        }
    });

    $("#domicile_arrondissement_pere").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_pere").removeClass('d-none');
            $('#domicile_quartier_pere').prop('disabled',false);

            var domicilearrondepere = $("#domicile_arrondissement_pere option:selected").text();
            getQuartierVillage(localiteParent,'domicile_quartier_pere');

        }
    });

    $("#domicile_quartier_pere").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_pere option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_pere").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });
    // adresse mere
    $('#domicile_pays_mere').on('change', function () {
        var pays = $('#domicile_pays_mere').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_mere').removeClass('d-none');
            $('div.autredomicile_ville_mere').addClass('d-none');

            $('#domicile_ville_mere').prop('disabled', false);
            $('#domicile_arrondissement_mere').prop('disabled', false);
            $('#domicile_quartier_mere').prop('disabled', false);

        }else{
            $("div.domicile_ville_mere").addClass("d-none");
            $("div.domicile_arrondissement_mere").addClass("d-none");
            $("div.domicile_quartier_mere").addClass('d-none');
            $('#domicile_ville_mere').prop('disabled', true);
            $('#domicile_arrondissement_mere').prop('disabled', true);
            $('#domicile_quartier_mere').prop('disabled', true);

            $('div.autredomicile_ville_mere').removeClass('d-none');
            $('#autredomicile_ville_mere').prop('disabled',false);
        }
    });

    $("#domicile_ville_mere").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_mere").removeClass("d-none");

            var domicilevillemere = $("#domicile_ville_mere option:selected").text();
            // var ville = '<option>'+domicilevillemere+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_mere');
        }
    });

    $("#domicile_arrondissement_mere").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_mere").removeClass('d-none');

            var domicilearrondemere = $("#domicile_arrondissement_mere option:selected").text();
            // var arrondComUrb = '<option>'+domicilearrondemere+'</option>';

            getQuartierVillage(localiteParent,'domicile_quartier_mere');
        }
    });

    $("#domicile_quartier_mere").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_mere option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_mere").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });

    //adresse declarant
    $('#domicile_pays_declarant').on('change', function () {
        var pays = $('#domicile_pays_declarant').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_declarant').removeClass('d-none');
            $('div.autredomicile_ville_declarant').addClass('d-none');

            $('#domicile_ville_declarant').prop('disabled', false);
            $('#domicile_arrondissement_declarant').prop('disabled', false);
            $('#domicile_quartier_declarant').prop('disabled', false);

        }else{
            $("div.domicile_ville_declarant").addClass("d-none");
            $("div.domicile_arrondissement_declarant").addClass("d-none");
            $("div.domicile_quartier_declarant").addClass('d-none');
            $('#domicile_ville_declarant').prop('disabled', true);
            $('#domicile_arrondissement_declarant').prop('disabled', true);
            $('#domicile_quartier_declarant').prop('disabled', true);

            $('div.autredomicile_ville_declarant').removeClass('d-none');
            $('#autredomicile_ville_declarant').prop('disabled',false);
        }
    });

    $("#domicile_ville_declarant").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_declarant").removeClass("d-none");

            var domicilevilledeclarant = $("#domicile_ville_declarant option:selected").text();
            getArrComUrbaine(localiteParent,'domicile_arrondissement_declarant');
        }
    });

    $("#domicile_arrondissement_declarant").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_declarant").removeClass('d-none');
            getQuartierVillage(localiteParent,'domicile_quartier_declarant');
        }
    });

    $("#domicile_quartier_declarant").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_declarant option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_declarant").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });

    //traitement declarant soit pere ou mere ou autre
    $("#sameadress").on('click', function(){

        undisabledOtherAdress();

        $('#domicile_pays_mere').val($('#domicile_pays_pere option:selected').text());
        $('#domicile_pays_mere').attr('readOnly','readOnly');

        $('#domicile_ville_mere').attr('readOnly','readOnly');

        var domicile_ville_mere = $("#domicile_ville_mere");
        var domicile_ville_pere = $("#domicile_ville_pere");
        domicile_ville_mere.val(domicile_ville_pere.val());

        $('#domicile_arrondissement_mere').attr('readOnly','readOnly');
        var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
        var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
        domicile_arrondissement_mere.val(domicile_arrondissement_pere.val());

        $('#domicile_quartier_mere').attr('readOnly','readOnly');
        var domicile_quartier_mere = $("#domicile_quartier_mere");
        var domicile_quartier_pere = $("#domicile_quartier_pere");
        domicile_quartier_mere.val(domicile_quartier_pere.val());


        $('#domicile_numero_mere').val($('#domicile_numero_pere').val());
        $('#domicile_numero_mere').attr('readOnly','readOnly');

        $('#domicile_nomvoie_mere').val($('#domicile_nomvoie_pere').val());
        $('#domicile_nomvoie_mere').attr('readOnly','readOnly');

        $('#domicile_typevoie_mere').val($('#domicile_typevoie_pere').val());
        $('#domicile_typevoie_mere').attr('readOnly','readOnly');

    });

    $("#otheradress").on('click', function(){

        undisabledOtherAdress();

        $('#domicile_pays_mere').val("");
        $('#domicile_pays_mere').attr('readOnly',false);

        $("#domicile_ville_mere").val("");
        $('#domicile_ville_mere').attr('readOnly',false);

        $("#domicile_arrondissement_mere").val("");
        $('#domicile_arrondissement_mere').attr('readOnly',false);

        $("#domicile_quartier_mere").val("");
        $('#domicile_quartier_mere').attr('readOnly',false);

        $('#domicile_numero_mere').val("");
        $('#domicile_numero_mere').attr('readOnly',false);
        $('#domicile_nomvoie_mere').val("");
        $('#domicile_nomvoie_mere').attr('readOnly',false);
        $('#domicile_typevoie_mere').val("");
        $('#domicile_typevoie_mere').attr('readOnly',false);

    });
    //Fin traitement declarant soit pere ou mere ou autre
    //Traitement input
    $('#clear_pere').click(function()
    {
        $('#nom_pere').val("");
        document.getElementById('nom_pere').readOnly = false;

        $('#prenom_pere').val("");
        document.getElementById('prenom_pere').readOnly = false;

        $('#date_naissance_pere').val("");
        document.getElementById('date_naissance_pere').readOnly = false;

        $('#email_pere').val("");
        document.getElementById('email_pere').readOnly = false;

        $('#lieu_naissance_pere').val("");
        document.getElementById('lieu_naissance_pere').readOnly = false;

        $('#code_pays_pere').val("");
        $('#telephone_pere').val("");
        $('#domicile_numero_pere').val("");
        $('#domicile_nomvoie_pere').val("");
        $('#domicile_quartier_pere').val("");
        $('#domicile_ville_pere').val("");


        var profession_pere = $("#profession_pere");
            profession_pere.val("");
            $("#profession_pere option:selected").text();

        var code_nationalite_pere = $("#code_nationalite_pere");
            code_nationalite_pere.val("");
            $("#code_nationalite_pere option:selected").text();
            document.getElementById('code_nationalite_pere').disabled = false;

        var code_type_document_pere = $("#code_type_document_pere");
        code_type_document_pere.val("");
        $("#code_type_document_pere option:selected").text();
        document.getElementById('code_type_document_pere').disabled = false;

        var numero_document_pere = $("#numero_document_pere");
        numero_document_pere.val("");
        $("#numero_document_pere option:selected").text();
        document.getElementById('numero_document_pere').disabled = false;

        var niveau_instruction_pere = $("#niveau_instruction_pere");
        niveau_instruction_pere.val("");
        $("#niveau_instruction_pere option:selected").text();
        document.getElementById('niveau_instruction_pere').disabled = false;


        $('#statut_personne_pere').val("");
        $('#type_date_naissance_pere').val("EXACTE");
        document.getElementById('type_date_naissance_pere').checked="";
        document.getElementById('type_date_naissance_pere').disabled = false;
        document.getElementById('statut_personne_pere').disabled = false;

    });

    $('#clear_mere').click(function()
    {
        $('#nom_mere').val("");
        document.getElementById('nom_mere').readOnly = false;

        $('#prenom_mere').val("");
        document.getElementById('prenom_mere').readOnly = false;

        $('#telephone_parent').val("");
        document.getElementById('telephone_parent').readOnly = false;

        $('#date_naissance_mere').val("");
        document.getElementById('date_naissance_mere').readOnly = false;

        $('#lieu_naissance_mere').val("");
        document.getElementById('lieu_naissance_mere').readOnly = false;

        $('#code_pays_mere').val("");
        $('#telephone_mere').val("");
        $('#domicile_numero_mere').val("");
        $('#domicile_nomvoie_mere').val("");
        $('#domicile_quartier_mere').val("");
        $('#domicile_ville_mere').val("");


        var profession_mere = $("#profession_mere");
            profession_mere.val("");
            $("#profession_mere option:selected").text();

        var code_nationalite_mere = $("#code_nationalite_mere");
            code_nationalite_mere.val("");
            $("#code_nationalite_mere option:selected").text();
            document.getElementById('code_nationalite_mere').disabled = false;

        var code_type_document_mere = $("#code_type_document_mere");
        code_type_document_mere.val("");
        $("#code_type_document_mere option:selected").text();
        document.getElementById('code_type_document_mere').disabled = false;

        var numero_document_mere = $("#numero_document_mere");
        numero_document_mere.val("");
        $("#numero_document_mere option:selected").text();
        document.getElementById('numero_document_mere').disabled = false;

        var niveau_instruction_mere = $("#niveau_instruction_mere");
        niveau_instruction_mere.val("");
        $("#niveau_instruction_mere option:selected").text();
        document.getElementById('niveau_instruction_mere').disabled = false;


        $('#statut_personne_mere').val("");
        $('#type_date_naissance_mere').val("EXACTE");
        document.getElementById('type_date_naissance_mere').checked="";
        document.getElementById('type_date_naissance_mere').disabled = false;
        document.getElementById('statut_personne_mere').disabled = false;

    });


        $('.validation-wizard').click(function()
        {
            $('.FIL_0001').hide();
            $('.FIL_0002').hide();

            if((($('#peredeclarant').is(':checked')))&&($('#statut_personne_pere').val()==="VIVANT"))
            {

                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';

                $('#nom_declarant').val($('#nom_pere').val());
                document.getElementById('nom_declarant').readOnly = true;

                $('#prenom_declarant').val($('#prenom_pere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                $('#email_declarant').val($('#email_pere').val());
                document.getElementById('email_declarant').readOnly = true;

                $('#date_naissance_declarant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#lieu_naissance_declarant').val($('#lieu_naissance_pere').val());
                document.getElementById('lieu_naissance_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_pere').val());
                document.getElementById('telephone_declarant').readOnly = true;

                $('#domicile_numero_declarant').val($('#domicile_numero_pere').val());
                document.getElementById('domicile_numero_declarant').readOnly = true;

                $('#domicile_nomvoie_declarant').val($('#domicile_nomvoie_pere').val());
                document.getElementById('domicile_nomvoie_declarant').readOnly = true;

                $('#domicile_typevoie_declarant').val($('#domicile_typevoie_pere').val());
                $('#domicile_typevoie_declarant').attr('readOnly','readOnly');

                $('#domicile_pays_declarant').val($('#domicile_pays_pere option:selected').text());
                document.getElementById('domicile_pays_declarant').readOnly = true;

                $('#numero_document_declarant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_pere').val());

                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("M");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0001");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;
                var profession_declarant = $("#profession_declarant");
                var profession_pere = $("#profession_pere");
                profession_declarant.val(profession_pere.val());
                document.getElementById('profession_declarant').disabled = true;

                var code_type_document_declarant = $("#code_type_document_declarant");
                var code_type_document_pere = $("#code_type_document_pere");
                code_type_document_declarant.val(code_type_document_pere.val());
                document.getElementById('code_type_document_declarant').disabled = true;

                var code_nationalite_declarant = $("#code_nationalite_declarant");
                var code_nationalite_pere = $("#code_nationalite_pere");
                code_nationalite_declarant.val(code_nationalite_pere.val());
                document.getElementById('code_nationalite_declarant').disabled = true;

                var code_localite_declarant = $("#code_localite_declarant");
                var code_localite_pere = $("#code_localite_pere");
                code_localite_declarant.val(code_localite_pere.val());
                code_localite_declarant.prop("disabled",true);

                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_declarant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var code_pays_declarant = $("#code_pays_declarant");
                var code_pays_pere = $("#code_pays_pere");
                code_pays_declarant.val(code_pays_pere.val());
                document.getElementById('code_pays_declarant').disabled = true;

                var domicile_ville_declarant = $("#domicile_ville_declarant");
                var domicile_ville_pere = $("#domicile_ville_pere");
                domicile_ville_declarant.val(domicile_ville_pere.val());

                var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
                var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                domicile_arrondissement_declarant.val(domicile_arrondissement_pere.val());

                var domicile_quartier_declarant = $("#domicile_quartier_declarant");
                var domicile_quartier_pere = $("#domicile_quartier_pere");
                domicile_quartier_declarant.val(domicile_quartier_pere.val());

                $("#domicile_pays_declarant").prop('disabled', true);
                $("#domicile_ville_declarant").prop('disabled', true);
                $("#domicile_arrondissement_declarant").prop('disabled', true);
                $("#domicile_quartier_declarant").prop('disabled', true);

            }


            if(($('#statut_personne_mere').val()==="DECEDE"))
            {
                document.getElementById('hide_mere').style.visibility = 'hidden';
            }
            if(($('#statut_personne_mere').val()==="VIVANT"))
            {
                document.getElementById('hide_mere').style.visibility = 'visible';
            }

        });

        $('input:radio[name="autredeclarant"]').change(function()
        {
            ///si on coche père
            if (($(this).val() === 'pere')&&($('#statut_personne_pere').val()==="VIVANT"))
            {

                //Traitement input
                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';
                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';

                $('#nom_declarant').val($('#nom_pere').val());
                document.getElementById('nom_declarant').readOnly = true;
                $('#prenom_declarant').val($('#prenom_pere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("M");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = true;

                $('#date_naissance_declarant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#code_localite_declarant').val($('#code_localite_pere').val());
                document.getElementById('code_localite_declarant').readOnly = true;

                $('#code_nationalite_declarant').val($('#code_nationalite_pere').val());
                document.getElementById('code_nationalite_declarant').readOnly = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0001");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;

                $('#profession_declarant').val($('#profession_pere').val());
                document.getElementById('profession_declarant').readOnly = true;

                $('#niveau_instruction_declarant').val($('#niveau_instruction_pere').val());
                document.getElementById('niveau_instruction_declarant').readOnly = true;

                $('#code_type_document_declarant').val($('#code_type_document_pere').val());
                document.getElementById('code_type_document_declarant').readOnly = true;

                $('#numero_document_declarant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#domicile_ville_declarant').val($('#domicile_ville_pere').val());
                document.getElementById('domicile_ville_declarant').readOnly = true;

                $('#domicile_arrondissement_declarant').val($('#domicile_arrondissement_pere').val());
                document.getElementById('domicile_arrondissement_declarant').readOnly = true;

                $('#domicile_quartier_declarant').val($('#domicile_quartier_pere').val());
                document.getElementById('domicile_quartier_declarant').readOnly = true;

                $('#domicile_typevoie_declarant').val($('#domicile_typevoie_pere').val());
                document.getElementById('domicile_typevoie_declarant').readOnly = true;

                $('#domicile_numero_declarant').val($('#domicile_numero_pere').val());
                document.getElementById('domicile_numero_declarant').readOnly = true;

                $('#domicile_nomvoie_declarant').val($('#domicile_nomvoie_pere').val());
                document.getElementById('domicile_nomvoie_declarant').readOnly = true;

                $('#code_pays_declarant').val($('#code_pays_pere').val());
                document.getElementById('code_pays_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_pere').val());
                document.getElementById('telephone_declarant').readOnly = true;

            }

            if (($(this).val() === 'mere')&&($('#statut_personne_mere').val()==="VIVANT"))
            {
                document.getElementById('hide_mere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';

                $('#nom_declarant').val($('#nom_mere').val());
                document.getElementById('nom_declarant').readOnly = true;
                $('#prenom_declarant').val($('#prenom_mere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("F");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = true;

                $('#date_naissance_declarant').val($('#date_naissance_mere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#code_localite_declarant').val($('#code_localite_mere').val());
                document.getElementById('code_localite_declarant').readOnly = true;

                $('#code_nationalite_declarant').val($('#code_nationalite_mere').val());
                document.getElementById('code_nationalite_declarant').readOnly = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0002");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;

                $('#profession_declarant').val($('#profession_mere').val());
                document.getElementById('profession_declarant').readOnly = true;

                $('#niveau_instruction_declarant').val($('#niveau_instruction_mere').val());
                document.getElementById('niveau_instruction_declarant').readOnly = true;

                $('#code_type_document_declarant').val($('#code_type_document_mere').val());
                document.getElementById('code_type_document_declarant').readOnly = true;

                $('#numero_document_declarant').val($('#numero_document_mere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#domicile_ville_declarant').val($('#domicile_ville_mere').val());
                document.getElementById('domicile_ville_declarant').readOnly = true;

                $('#domicile_arrondissement_declarant').val($('#domicile_arrondissement_mere').val());
                document.getElementById('domicile_arrondissement_declarant').readOnly = true;

                $('#domicile_quartier_declarant').val($('#domicile_quartier_mere').val());
                document.getElementById('domicile_quartier_declarant').readOnly = true;

                $('#domicile_typevoie_declarant').val($('#domicile_typevoie_mere').val());
                document.getElementById('domicile_typevoie_declarant').readOnly = true;

                $('#domicile_numero_declarant').val($('#domicile_numero_mere').val());
                document.getElementById('domicile_numero_declarant').readOnly = true;

                $('#domicile_nomvoie_declarant').val($('#domicile_nomvoie_mere').val());
                document.getElementById('domicile_nomvoie_declarant').readOnly = true;

                $('#code_pays_declarant').val($('#code_pays_mere').val());
                document.getElementById('code_pays_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_mere').val());
                document.getElementById('telephone_declarant').readOnly = true;
            }

            if (($(this).val() === 'autre'))
            {
                $("#domicile_pays_declarant").prop("disabled", false);
                $("#domicile_ville_declarant").prop("disabled", false);
                $("#domicile_arrondissement_declarant").prop("disabled", false);
                $("#domicile_quartier_declarant").prop("disabled", false);
                $("#domicile_typevoie_declarant").prop("disabled", false);

                $('#nom_declarant').val("");
                document.getElementById('nom_declarant').readOnly = false;
                $('#prenom_declarant').val($('#prenom_pere').val());
                document.getElementById('prenom_declarant').readOnly = false;

                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("M");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = false;

                $('#date_naissance_declarant').val("");
                document.getElementById('date_naissance_declarant').readOnly = false;

                $('#code_localite_declarant').val("");
                document.getElementById('code_localite_declarant').readOnly = false;

                $('#code_nationalite_declarant').val("");
                document.getElementById('code_nationalite_declarant').readOnly = false;

                var filiation = $("#filiation");
                    filiation.val("FIL_0008");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = false;

                $('#profession_declarant').val("");
                document.getElementById('profession_declarant').readOnly = false;

                $('#niveau_instruction_declarant').val("");
                document.getElementById('niveau_instruction_declarant').readOnly = false;

                $('#code_type_document_declarant').val("");
                document.getElementById('code_type_document_declarant').readOnly = false;

                $('#numero_document_declarant').val("");
                document.getElementById('numero_document_declarant').readOnly = false;

                $('#domicile_ville_declarant').val("");
                document.getElementById('domicile_ville_declarant').readOnly = false;

                $('#domicile_arrondissement_declarant').val("");
                document.getElementById('domicile_arrondissement_declarant').readOnly = false;

                $('#domicile_quartier_declarant').val("");
                document.getElementById('domicile_quartier_declarant').readOnly = false;

                $('#domicile_typevoie_declarant').val("");
                document.getElementById('domicile_typevoie_declarant').readOnly = false;

                $('#domicile_numero_declarant').val("");
                document.getElementById('domicile_numero_declarant').readOnly = false;

                $('#domicile_nomvoie_declarant').val("");
                document.getElementById('domicile_nomvoie_declarant').readOnly = false;

                $('#code_pays_declarant').val("");
                document.getElementById('code_pays_declarant').readOnly = false;

                $('#telephone_declarant').val("");
                document.getElementById('telephone_declarant').readOnly = false;

            }

        });


});



    //VERIFICATION FORMULAIRE
    var form = $(".validation-wizard").show();

    function soumission()
    {
        // informations du père
         var nom_pere = $("#nom_pere");
         var prenom_pere = $("#prenom_pere");
         var date_naissance_pere = $("#date_naissance_pere");
         var lieu_naissance_pere = $("#lieu_naissance_pere");
         var code_localite_pere = $("#code_localite_pere");
         var email_pere = $("#email_pere");
         var code_pays_pere = $("#code_pays_pere");
         var telephone_pere = $("#telephone_pere");
         var telephone_pere = $("#telephone_pere");
         var profession_pere = $("#profession_pere");
         var code_nationalite_pere = $("#code_nationalite_pere");
         var niveau_instruction_pere = $("#niveau_instruction_pere");
         var code_type_document_pere = $("#code_type_document_pere");
         var numero_document_pere = $("#numero_document_pere");
         var images_pere = $("#images_pere");

         //information mere
         var nom_mere = $("#nom_mere");
         var prenom_mere = $("#prenom_mere");
         var date_naissance_mere = $("#date_naissance_mere");
         var lieu_naissance_mere = $("#lieu_naissance_mere");
         var code_localite_mere = $("#code_localite_mere");
         var code_pays_mere = $("#code_pays_mere");
         var telephone_mere = $("#telephone_mere");
         var telephone_parent = $("#telephone_parent");
         var profession_mere = $("#profession_mere");
         var code_nationalite_mere = $("#code_nationalite_mere");
         var niveau_instruction_mere = $("#niveau_instruction_mere");
         var code_type_document_mere = $("#code_type_document_mere");
         var numero_document_mere = $("#numero_document_mere");
         var images_mere = $("#images_mere");
         formation_sanitaire_naissance = $("#formation_sanitaire_naissance");

         //déclarant
         var nom_declarant = $("#nom_declarant");
         var prenom_declarant = $("#prenom_declarant");
         var date_naissance_declarant = $("#date_naissance_declarant");
         var lieu_naissance_declarant = $("#lieu_naissance_declarant");
         var code_localite_declarant = $("#code_localite_declarant");
         var code_pays_declarant = $("#code_pays_declarant");
         var telephone_declarant = $("#telephone_declarant");
         var niveau_instruction_declarant = $("#niveau_instruction_declarant");//alange
         var telephone_declarant = $("#telephone_declarant");
         var profession_declarant = $("#profession_declarant");
         var code_nationalite_declarant = $("#code_nationalite_declarant");
         var filiation = $("#filiation");
         var sexe_declarant = $("#sexe_declarant");
         var email_declarant = $("#email_declarant");
         var code_type_document_declarant = $("#code_type_document_declarant");
         var numero_document_declarant = $("#numero_document_declarant");
         var images_declarant = $("#images_declarant");

         // enfant
         var nom_enfant = $("#nom_enfant");
         var prenom_enfant = $("#prenom_enfant");
         var date_naissance_enfant = $("#date_naissance_enfant");
         var lieu_naissance_enfant = $("#lieu_naissance_enfant");
         var code_localite_enfant = $("#code_localite_enfant");
         var code_situation_matrimoniale = $("#code_situation_matrimoniale");
         var lieu_survenance = $("#code_lieu_survenance");
         var sexe_enfant = $("#sexe_enfant");
         var heure_naissance_enfant = $("#heure_naissance_enfant");
         var nombre_enfants = $("#nombre_enfants");
         var poids_enfant = $("#poids_enfant");
         var taille_enfant = $("#taille_enfant");
         var pc_enfant = $("#pc_enfant");

         var type_date_naissance_mere = $("#type_date_naissance_mere");
         var statut_personne_mere = $("#statut_personne_mere");
         var type_date_naissance_pere = $("#type_date_naissance_pere");
         var statut_personne_pere = $("#statut_personne_pere");
         var type_date_naissance_declarant = $("#type_date_naissance_declarant");
         var statut_personne_declarant = $("#statut_personne_declarant");

         var domicile_pays_pere = $("#domicile_pays_pere");
         var domicile_ville_pere = $("#domicile_ville_pere");
         var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
         var domicile_quartier_pere = $("#domicile_quartier_pere");
         var domicile_typevoie_pere = $("#domicile_typevoie_pere");
         var domicile_numero_pere = $("#domicile_numero_pere");
         var domicile_nomvoie_pere = $("#domicile_nomvoie_pere");

         var domicile_pays_mere = $("#domicile_pays_mere");
         var domicile_ville_mere = $("#domicile_ville_mere");
         var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
         var domicile_quartier_mere = $("#domicile_quartier_mere");
         var domicile_typevoie_mere = $("#domicile_typevoie_mere");
         var domicile_numero_mere = $("#domicile_numero_mere");
         var domicile_nomvoie_mere = $("#domicile_nomvoie_mere");

         var peredeclarant = $("#peredeclarant").val();
         var meredeclarant = $("#meredeclarant").val();
         var autredeclarant = $("#autredeclarant").val();


        var domicile_pays_declarant = $("#domicile_pays_declarant");
        var domicile_ville_declarant = $("#domicile_ville_declarant");
        var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
        var domicile_quartier_declarant = $("#domicile_quartier_declarant");
        var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
        var domicile_numero_declarant = $("#domicile_numero_declarant");
        var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");

        var date_heure_declaration = $("#date_heure_declaration");
        var type_declaration = $("#type_declaration");
        var formation_sanitaire_naissance = $("#formation_sanitaire_naissance");


        var data =
        {
            nom_pere:nom_pere.val(),
            prenom_pere:prenom_pere.val(),
            date_naissance_pere:date_naissance_pere.val(),
            lieu_naissance_pere:lieu_naissance_pere.val(),
            code_localite_pere:code_localite_pere.val(),
            profession_pere:profession_pere.val(),
            code_nationalite_pere:code_nationalite_pere.val(),
            niveau_instruction_pere:niveau_instruction_pere.val(),
            code_pays_pere:code_pays_pere.val(),
            telephone_pere:telephone_pere.val(),
            code_type_document_pere:code_type_document_pere.val(),
            numero_document_pere:numero_document_pere.val(),
            nom_mere:nom_mere.val(),
            prenom_mere:prenom_mere.val(),
            date_naissance_mere:date_naissance_mere.val(),
            lieu_naissance_mere:lieu_naissance_mere.val(),
            code_localite_mere:code_localite_mere.val(),
            profession_mere:profession_mere.val(),
            code_nationalite_mere:code_nationalite_mere.val(),
            niveau_instruction_mere:niveau_instruction_mere.val(),
            code_pays_pere :code_pays_pere.val(),
            telephone_pere :telephone_pere.val(),
            code_pays_mere :code_pays_mere.val(),
            telephone_mere :telephone_mere.val(),
            code_type_document_mere:code_type_document_mere.val(),
            numero_document_mere:numero_document_mere.val(),
             // données du déclarant
            nom_declarant:nom_declarant.val(),
            prenom_declarant:prenom_declarant.val(),
            date_naissance_declarant:date_naissance_declarant.val(),
            lieu_naissance_declarant:lieu_naissance_declarant.val(),
            code_localite_declarant:code_localite_declarant.val(),
            profession_declarant:profession_declarant.val(),
            code_nationalite_declarant:code_nationalite_declarant.val(),
            niveau_instruction_declarant:niveau_instruction_declarant.val(),
            filiation:filiation.val(),
            code_pays_declarant :code_pays_declarant.val(),
            telephone_declarant :telephone_declarant.val(),
            sexe_declarant:sexe_declarant.val(),
            code_type_document_declarant:code_type_document_declarant.val(),
            numero_document_declarant:numero_document_declarant.val(),
             // données de l'enfant
            nom_enfant:nom_enfant.val(),
            prenom_enfant:prenom_enfant.val(),
            date_naissance_enfant:date_naissance_enfant.val(),
            lieu_naissance_enfant:lieu_naissance_enfant.val(),
            code_localite_enfant:code_localite_enfant.val(),
            code_situation_matrimoniale:code_situation_matrimoniale.val(),
            sexe_enfant:sexe_enfant.val(),
            heure_naissance_enfant:heure_naissance_enfant.val(),
            lieu_survenance:lieu_survenance.val(),
            nombre_enfant:nombre_enfants.val(),
            poids_enfant:poids_enfant.val(),
            taille_enfant:taille_enfant.val(),
            pc_enfant:pc_enfant.val(),

            type_date_naissance_declarant:type_date_naissance_declarant.val(),
            statut_personne_declarant:statut_personne_declarant.val(),
            type_date_naissance_mere:type_date_naissance_mere.val(),
            statut_personne_mere:statut_personne_mere.val(),
            type_date_naissance_pere:type_date_naissance_pere.val(),
            statut_personne_pere:statut_personne_pere.val(),
            email_pere:email_pere.val(),
            telephone_parent:telephone_parent.val(),
            email_declarant:email_declarant.val(),
            domicile_pays_pere:domicile_pays_pere.val(),
            domicile_ville_pere:domicile_ville_pere.val(),
            domicile_arrondissement_pere:domicile_arrondissement_pere.val(),
            domicile_quartier_pere:domicile_quartier_pere.val(),
            domicile_typevoie_pere:domicile_typevoie_pere.val(),
            domicile_numero_pere:domicile_numero_pere.val(),
            domicile_nomvoie_pere:domicile_nomvoie_pere.val(),
            domicile_pays_mere:domicile_pays_mere.val(),
            domicile_ville_mere:domicile_ville_mere.val(),
            domicile_arrondissement_mere:domicile_arrondissement_mere.val(),
            domicile_quartier_mere:domicile_quartier_mere.val(),
            domicile_typevoie_mere:domicile_typevoie_mere.val(),
            domicile_numero_mere:domicile_numero_mere.val(),
            domicile_nomvoie_mere:domicile_nomvoie_mere.val(),
            domicile_pays_declarant:domicile_pays_declarant.val(),
            domicile_ville_declarant:domicile_ville_declarant.val(),
            domicile_arrondissement_declarant:domicile_arrondissement_declarant.val(),
            domicile_quartier_declarant:domicile_quartier_declarant.val(),
            domicile_typevoie_declarant:domicile_typevoie_declarant.val(),
            domicile_numero_declarant:domicile_numero_declarant.val(),
            domicile_nomvoie_declarant:domicile_nomvoie_declarant.val(),
            date_heure_declaration:date_heure_declaration.val(),
            type_declaration:type_declaration.val(),
            formation_sanitaire_naissance:formation_sanitaire_naissance.val(),
        };

        //traitement ajax
        Swal.fire({
            width:2500,
            position: 'top',
            title: "Récapitulatif des informations",
            icon: 'file',
            html:
            "<input type='button' value='Imprimer' class=\"btn btn-primary\" onClick='printDiv(\"printcontent\")'><div id='printcontent'><br><table style='border:0px solid black; width:100%; padding:10px; text-align:left'>"

//ENFANT
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>1)ENFANT<br></td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_enfant.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_enfant.val()+"</span></td><td style='padding:2px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_enfant" ).options[ document.getElementById( "sexe_enfant" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Lieu<br><span style='font-weight:bold;'>"+lieu_naissance_enfant.val()+"</span></td></tr>"
+"<tr><td></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_enfant.val())+"</span></td><td style='padding:2px'>Heure</td><td style='padding:2px'><span style='font-weight:bold;'> "+heure_naissance_enfant.val()+"</span></td></tr>"

//PERE
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>2)PERE </td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_pere.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_pere.val()+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_pere.val())+"</span></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_pere.val()+" "+domicile_nomvoie_pere.val()+" "+$("#domicile_ville_pere option:selected").text()+" " +$("#domicile_arrondissement_pere option:selected").text()+" "+$("#domicile_quartier_pere option:selected").text()+"</span></td><td style='padding:2px'>Telephone<br><span style='font-weight:bold;'>"+telephone_pere.val()+"</span></td></tr>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Document<br><span style='font-weight:bold;'>"+numero_document_pere.val()+"</span></td><td style='padding:2px'>Lieu naissance<br><span style='font-weight:bold;'>"+lieu_naissance_pere.val()+"</span></td><td style='padding:2px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_pere" ).options[ document.getElementById( "code_nationalite_pere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_pere" ).options[ document.getElementById( "profession_pere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Niveau <br><span style='font-weight:bold;'>"+document.getElementById( "niveau_instruction_pere" ).options[ document.getElementById( "niveau_instruction_pere" ).selectedIndex ].text+"</span></td></tr>"

//mere
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>2)MERE </td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_mere.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_mere.val()+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_mere.val())+"</span></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_mere.val()+" "+domicile_nomvoie_mere.val()+" "+$("#domicile_ville_pere option:selected").text()+" "+$("#domicile_arrondissement_pere option:selected").text()+" "+$("#domicile_quartier_pere option:selected").text()+"</span></td><td style='padding:2px'>Niveau <br><span style='font-weight:bold;'>"+document.getElementById( "niveau_instruction_mere" ).options[ document.getElementById( "niveau_instruction_mere" ).selectedIndex ].text +"</span></td></tr>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Document<br><span style='font-weight:bold;'>"+numero_document_mere.val()+"</span></td><td style='padding:2px'>Lieu naissance<br><span style='font-weight:bold;'>"+lieu_naissance_mere.val()+"</span></td><td style='padding:2px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_mere" ).options[ document.getElementById( "code_nationalite_mere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_mere" ).options[ document.getElementById( "profession_mere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Telephone<br><span style='font-weight:bold;'>"+telephone_mere.val()+"</span></td></tr>"

+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Nombre enfant <br><span style='font-weight:bold;'>"+nombre_enfants.val()+"</span></td></tr>"

//DECLARANT
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><td style='font-weight:bold; padding:2px'>3)DECLARANT</td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_declarant.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_declarant.val()+"</span></td><td style='padding:2px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_declarant" ).options[ document.getElementById( "sexe_declarant" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_declarant.val())+"</span></td><td style='padding:2px'>Lieu<br><span style='font-weight:bold;'>"+ lieu_naissance_declarant.val()+"</span></td>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_declarant.val()+" "+domicile_nomvoie_declarant.val()+" "+$("#domicile_ville_declarant option:selected").text()+" "+$("#domicile_arrondissement_declarant option:selected").text()+" "+$("#domicile_quartier_declarant option:selected").text()+"</span></td><td style='padding:2px'>Filiation<br><span style='font-weight:bold;'>"+document.getElementById( "filiation" ).options[ document.getElementById( "filiation" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Téléphone<br><span style='font-weight:bold;'>"+ telephone_declarant.val()+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_declarant" ).options[ document.getElementById( "profession_declarant" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Nationalite<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_declarant" ).options[ document.getElementById( "code_nationalite_declarant" ).selectedIndex ].text+"</span></tr>"


            +"<tr><td style='padding:5px;' colspan=11><hr></td></tr></table></div>",
             type: "warning",
             showCancelButton: !0,
             confirmButtonText: "Enregistrer",
             cancelButtonText: "Annuler",
             reverseButtons: !0
         }).then((result)=>
          {

            if (result.value==true)
            {
                $.post("{{route('fiche_maternite.store')}}",data,function(response)
                {
                    if(response.code == "200")
                    {
                        flashAlert("Opération réussie","success",response.message);
                        var url = "{{ route('fiche_maternite.index') }}";
                        setTimeout(() => {
                            window.open(url);
                        }, 2000);

                    }else{
                        swal.fire("Opération échouée!", response.message, "error");
                    }
                });
            }

         });

         return false;

    }


    $(".validation-wizard").steps({
         headerTag: "h6",
         bodyTag: "section",
         transitionEffect: "fade",
         titleTemplate: '<span class="step">#index#</span> #title#',
         labels: {
             finish: "Enregistrer"
         },
         onStepChanging: function (event, currentIndex, newIndex) {
             return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
         },
         onFinishing: function (event, currentIndex) {
             return form.validate().settings.ignore = ":disabled", form.valid()
         },
         onFinished: function (event, currentIndex) {
             soumission();
            }
        }),$("#contactUsForm").validate({
            ignore: "input[type=hidden]",
            errorClass: "text-danger",
            successClass: "text-success",
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element)
            },
            rules: {


                nom_mere:
                {
                    required: true,
                },

                date_naissance_mere:
                {
                    required: true,
                },

                code_localite_mere:
                {
                    required: true,
                },
                code_nationalite_mere:
                {
                    required: true,
                },

                statut_personne_mere:
                {
                    required: true,
                },
                telephone_parent:
                {
                    required: true,
                },
                sexe_enfant:{
                    required:true,
                },

                date_naissance_enfant:{
                    required:true,
                },
                code_localite_enfant:{
                    required:true,
                },
                heure_naissance_enfant:{
                    required:true,
                },
                poids_enfant:{
                    required:true,
                },
                taille_enfant:{
                    required: true,
                },
                pc_enfant:{
                    required: true,
                },
                code_pays_mere:{
                    required: true,
                }

            },
            messages: {
                numero_document_declarant:
                {
                    required: "Veuillez saisir un numero de pièce",
                },
                code_type_document_declarant:
                {
                    required: "Veuillez selectionner le document",
                },
                sexe_enfant:
                {
                required: "Veuillez selectionner le sexe",
                },
                date_naissance_enfant:
                {
                    required: "Veuillez selectionner une date",
                },
                code_localite_enfant:
                {
                    required: "Veuillez saisir le lieu de naissance",
                },
                heure_naissance_enfant:
                {
                    required: "Veuillez saisir selectionner une heure",
                },
                    heure_naissance_enfant:
                {
                required: "Veuillez saisir selectionner une heure",
                },

                poids_enfant:{
                    required: "Veuillez saisir le poids de l'enfant",
                },
                taille_enfant:{
                    required: "Veuillez saisir la taille de l'enfant",
                },
                pc_enfant:{
                    required: "Veuillez saisir le périmètre crânien de l'enfant",
                },

                nom_mere:
                {
                    required: "Veuillez saisir un nom",
                },

                date_naissance_mere:
                {
                    required: "Veuillez selectionner une date",

                },

                code_localite_mere:
                {
                    required: "Veuillez saisir un lieu",

                },
                code_nationalite_mere:
                {
                    required: "Veuillez selectionner une nationalité",
                },

                statut_personne_mere:
                {
                    required: "Veuillez selectionner un statut",
                },
                telephone_parent:
                {
                    required: "Veuillez saisir le téléphone d'un parent",
                },
                code_pays_mere:
                {
                    required: "Veuillez selectionner l'indicatif du pays",
                }
            },

    });

    //Traitement input
    $('#clear_pere').click(function()
    {
        $('#nom_pere').val("");
        document.getElementById('nom_pere').readOnly = false;

        $('#prenom_pere').val("");
        document.getElementById('prenom_pere').readOnly = false;

        $('#date_naissance_pere').val("");
        document.getElementById('date_naissance_pere').readOnly = false;

        $('#email_pere').val("");
        document.getElementById('email_pere').readOnly = false;

        $('#lieu_naissance_pere').val("");
        document.getElementById('lieu_naissance_pere').readOnly = false;

        $('#code_pays_pere').val("");
        $('#telephone_pere').val("");
        $('#domicile_numero_pere').val("");
        $('#domicile_nomvoie_pere').val("");
        $('#domicile_quartier_pere').val("");
        $('#domicile_ville_pere').val("");


        var profession_pere = $("#profession_pere");
        profession_pere.val("");
        $("#profession_pere option:selected").text();

        var code_nationalite_pere = $("#code_nationalite_pere");
        code_nationalite_pere.val("");
        $("#code_nationalite_pere option:selected").text();
        document.getElementById('code_nationalite_pere').disabled = false;

        var code_type_document_pere = $("#code_type_document_pere");
        code_type_document_pere.val("");
        $("#code_type_document_pere option:selected").text();
        document.getElementById('code_type_document_pere').disabled = false;

        var numero_document_pere = $("#numero_document_pere");
        numero_document_pere.val("");
        $("#numero_document_pere option:selected").text();
        document.getElementById('numero_document_pere').disabled = false;

        var niveau_instruction_pere = $("#niveau_instruction_pere");
        niveau_instruction_pere.val("");
        $("#niveau_instruction_pere option:selected").text();
        document.getElementById('niveau_instruction_pere').disabled = false;


        $('#statut_personne_pere').val("");
        $('#type_date_naissance_pere').val("EXACTE");
        document.getElementById('type_date_naissance_pere').checked="";
        document.getElementById('type_date_naissance_pere').disabled = false;
        document.getElementById('statut_personne_pere').disabled = false;

    });

    $('#clear_mere').click(function()
    {
        $('#nom_mere').val("");
        document.getElementById('nom_mere').readOnly = false;

        $('#prenom_mere').val("");
        document.getElementById('prenom_mere').readOnly = false;

        $('#telephone_parent').val("");
        document.getElementById('telephone_parent').readOnly = false;

        $('#date_naissance_mere').val("");
        document.getElementById('date_naissance_mere').readOnly = false;

        $('#lieu_naissance_mere').val("");
        document.getElementById('lieu_naissance_mere').readOnly = false;

        $('#code_pays_mere').val("");
        $('#telephone_mere').val("");
        $('#domicile_numero_mere').val("");
        $('#domicile_nomvoie_mere').val("");
        $('#domicile_quartier_mere').val("");
        $('#domicile_ville_mere').val("");


        var profession_mere = $("#profession_mere");
            profession_mere.val("");
            $("#profession_mere option:selected").text();

        var code_nationalite_mere = $("#code_nationalite_mere");
            code_nationalite_mere.val("");
            $("#code_nationalite_mere option:selected").text();
            document.getElementById('code_nationalite_mere').disabled = false;

        var code_type_document_mere = $("#code_type_document_mere");
        code_type_document_mere.val("");
        $("#code_type_document_mere option:selected").text();
        document.getElementById('code_type_document_mere').disabled = false;

        var numero_document_mere = $("#numero_document_mere");
        numero_document_mere.val("");
        $("#numero_document_mere option:selected").text();
        document.getElementById('numero_document_mere').disabled = false;

        var niveau_instruction_mere = $("#niveau_instruction_mere");
        niveau_instruction_mere.val("");
        $("#niveau_instruction_mere option:selected").text();
        document.getElementById('niveau_instruction_mere').disabled = false;


        $('#statut_personne_mere').val("");
        $('#type_date_naissance_mere').val("EXACTE");
        document.getElementById('type_date_naissance_mere').checked="";
        document.getElementById('type_date_naissance_mere').disabled = false;
        document.getElementById('statut_personne_mere').disabled = false;

    });



    function getArrComUrbaine(codeparent,cle){
        var route = "{{ route('declarationNaissance.search.arrond') }}";
        var option = "<option> Selectionnez </option>";

        $.ajax({
            url: route,
            data: 'id=' +codeparent,
            dataType:
                'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    console.log(value.lib_localite);
                    option += '<option value="'+value.code_localite+'">'+value.lib_localite+'</option>';
                });
                $("#"+cle).html(option);
                // console.log(json);
            }
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

    function undisabledOtherAdress()
    {
        $('#domicile_pays_mere').prop('disabled',false);
        $('#domicile_ville_mere').prop('disabled',false);
        $('#domicile_arrondissement_mere').prop('disabled',false);
        $('#domicile_quartier_mere').prop('disabled',false);
        $('#domicile_typevoie_mere').prop('disabled',false);
        $('#domicile_numero_mere').removeAttr('disabled');
        $('#domicile_nomvoie_mere').removeAttr('disabled');
    }


</script>
@endsection
