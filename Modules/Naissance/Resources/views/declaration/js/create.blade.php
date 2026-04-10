@section("scripts")

<script>
 hideTemoin();


 function hideTemoin(){
    $("#temoin1").hide();
    $("#temoin2").hide();
    $("#temoin3").hide();
 }

    function dateFrench(dat){
        var date = new Date(dat);
        return date.getDate()+ "/"+(date.getMonth() + 1 )+"/"+date.getFullYear();
    }
    function printDiv(divName) {
         var printContents = document.getElementById(divName).innerHTML;
         w=window.open();
         w.document.write(printContents);
         w.print();
         w.close();
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

    /**
     * Traite et formate les messages d'erreur reçus du serveur
     */
    function traiterMessageErreur(response) {
        var message = response.message;

        // Si le message est un objet, extraire le premier message
        if (typeof message === 'object' && message !== null) {
            var messages = Object.values(message);
            if (messages.length > 0) {
                message = messages[0];
            } else {
                message = "Une erreur s'est produite";
            }
        }

        // Si le message est un tableau, prendre le premier élément
        if (Array.isArray(message)) {
            message = message.length > 0 ? message[0] : "Une erreur s'est produite";
        }

        // Si le message est vide ou undefined, utiliser un message par défaut
        if (!message || message.trim() === '') {
            message = "Une erreur inattendue s'est produite";
        }

        return message;
    }


$(function(){

    // alert("ok");

    // $("a.show-piece-declarant-modal").on('click', function(){
    //     $("#modal-add-piece-declarant").modal('show');
    // });


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

    $("#code_localite_enfant").change(function (e) {
        e.preventDefault();
        var codeLocaliteEnfant = $(this).val();
        var lieunaisenfant = $("#code_localite_enfant option:selected").text();

        if(codeLocaliteEnfant != 'LOC_4247'){
            $("#lieu_naissance_enfant").val(lieunaisenfant);
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

            $('#domicile_ville_pere').prop('readonly', true);
            $('#domicile_arrondissement_pere').prop('readonly', true);
            $('#domicile_quartier_pere').prop('readonly', true);

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

            $('#domicile_ville_mere').prop('readonly', true);
            $('#domicile_arrondissement_mere').prop('readonly', true);
            $('#domicile_quartier_mere').prop('readonly', true);

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

            $('#domicile_ville_declarant').prop('readonly', true);
            $('#domicile_arrondissement_declarant').prop('readonly', true);
            $('#domicile_quartier_declarant').prop('readonly', true);

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

    //adresse enfant
    $('#domicile_pays_enfant').on('change', function () {
        var pays = $('#domicile_pays_enfant').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_enfant').removeClass('d-none');
            $('div.autredomicile_ville_enfant').addClass('d-none');

            $('#domicile_ville_enfant').prop('readonly', true);
            $('#domicile_arrondissement_enfant').prop('readonly', true);
            $('#domicile_quartier_enfant').prop('readonly', true);

        }else{
            $("div.domicile_ville_enfant").addClass("d-none");
            $("div.domicile_arrondissement_enfant").addClass("d-none");
            $("div.domicile_quartier_enfant").addClass('d-none');
            $('#domicile_ville_enfant').prop('disabled', true);
            $('#domicile_arrondissement_enfant').prop('disabled', true);
            $('#domicile_quartier_enfant').prop('disabled', true);

            $('div.autredomicile_ville_enfant').removeClass('d-none');
            $('#autredomicile_ville_enfant').prop('disabled',false);
        }
    });

    $("#domicile_ville_enfant").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_enfant").removeClass("d-none");
            $('#domicile_arrondissement_enfant').prop('disabled',false);

            var domicilevilleenfant = $("#domicile_ville_enfant option:selected").text();
            var ville = '<option>'+domicilevilleenfant+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_enfant');
        }
    });

    $("#domicile_arrondissement_enfant").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_enfant").removeClass('d-none');
            $('#domicile_quartier_enfant').prop('disabled',false);

            var domicilearrondeenfant = $("#domicile_arrondissement_enfant option:selected").text();
            getQuartierVillage(localiteParent,'domicile_quartier_enfant');

        }
    });

    $("#domicile_quartier_enfant").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_enfant option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_enfant").on('change', function(){
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
        $('#email_professionnel_pere').val("");

        $('#lieu_naissance_pere').val("");
        document.getElementById('lieu_naissance_pere').readOnly = false;

        $('#code_localite_pere').val("");

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

        $('#email_mere').val("");
        document.getElementById('email_mere').readOnly = false;
        $('#email_professionnel_mere').val("");

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
                $('#email_professionnel_declarant').val($('#email_professionnel_pere').val());
                document.getElementById('email_professionnel_declarant').readOnly = true;

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

                $('#email_declarant').val($('#email_pere').val());
                document.getElementById('email_declarant').readOnly = true;
                $('#email_professionnel_declarant').val($('#email_professionnel_pere').val());
                document.getElementById('email_professionnel_declarant').readOnly = true;

                $('#date_naissance_declarant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#lieu_naissance_declarant').val($('#lieu_naissance_pere').val());
                document.getElementById('lieu_naissance_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_pere').val());
                document.getElementById('telephone_declarant').readOnly = true;

                $('#numero_document_declarant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_pere').val());

                if($('#type_date_naissance_pere').val()==="EXACTE")
                {
                    document.getElementById('type_date_naissance_declarant').checked="";
                }else{
                    document.getElementById('type_date_naissance_declarant').checked="ESTIME";
                }

                document.getElementById('type_date_naissance_declarant').disabled = true;
                document.getElementById('statut_personne_declarant').disabled = true;


                //traitement select
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

                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_declarant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var code_pays_declarant = $("#code_pays_declarant");
                var code_pays_pere = $("#code_pays_pere");
                code_pays_declarant.val(code_pays_pere.val());
                document.getElementById('code_pays_declarant').disabled = true;


                $('#domicile_numero_declarant').val($('#domicile_numero_pere').val());
                document.getElementById('domicile_numero_declarant').readOnly = true;

                var domicile_ville_declarant = $("#domicile_ville_declarant");
                var domicile_ville_pere = $("#domicile_ville_pere");
                domicile_ville_declarant.val(domicile_ville_pere.val());

                var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
                var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                domicile_arrondissement_declarant.val(domicile_arrondissement_pere.val());

                var domicile_quartier_declarant = $("#domicile_quartier_declarant");
                var domicile_quartier_pere = $("#domicile_quartier_pere");
                domicile_quartier_declarant.val(domicile_quartier_pere.val());


                //  $('#domicile_typevoie_declarant').attr('readOnly','readOnly');

                $("#domicile_pays_declarant").prop('disabled', true);
                $("#domicile_ville_declarant").prop('disabled', true);
                $("#domicile_arrondissement_declarant").prop('disabled', true);
                $("#domicile_quartier_declarant").prop('disabled', true);
                $("#domicile_typevoie_declarant").prop('disabled', true);
            }

            if (($(this).val() === 'mere')&&($('#statut_personne_mere').val()==="VIVANT"))
            {
                document.getElementById('hide_mere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';

                $('#nom_declarant').val($('#nom_mere').val());
                document.getElementById('nom_declarant').readOnly = true;

                $('#prenom_declarant').val($('#prenom_mere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                $('#email_declarant').val($('#email_mere').val());
                document.getElementById('email_declarant').readOnly = true;
                $('#email_professionnel_declarant').val($('#email_professionnel_mere').val());
                document.getElementById('email_professionnel_declarant').readOnly = true;

                $('#date_naissance_declarant').val($('#date_naissance_mere').val());
                document.getElementById('date_naissance_declarant').readOnly = true;

                $('#lieu_naissance_declarant').val($('#lieu_naissance_mere').val());
                document.getElementById('lieu_naissance_declarant').readOnly = true;

                $('#telephone_declarant').val($('#telephone_mere').val());
                document.getElementById('telephone_declarant').readOnly = true;

                $('#domicile_numero_declarant').val($('#domicile_numero_mere').val());
                document.getElementById('domicile_numero_declarant').readOnly = true;

                $('#domicile_nomvoie_declarant').val($('#domicile_nomvoie_mere').val());
                document.getElementById('domicile_nomvoie_declarant').readOnly = true;

                $('#domicile_typevoie_declarant').val($('#domicile_typevoie_mere').val());
                $('#domicile_typevoie_declarant').attr('readOnly','readOnly');


                $('#numero_document_declarant').val($('#numero_document_mere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_mere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_mere').val());

                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("F");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0002"); /* Mère = FIL_0002 (était incorrectement FIL_0001) */
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;
                var profession_declarant = $("#profession_declarant");
                var profession_mere = $("#profession_mere");
                profession_declarant.val(profession_mere.val());
                document.getElementById('profession_declarant').disabled = true;

                var code_type_document_declarant = $("#code_type_document_declarant");
                var code_type_document_mere = $("#code_type_document_mere");
                code_type_document_declarant.val(code_type_document_mere.val());
                document.getElementById('code_type_document_declarant').disabled = true;

                var code_nationalite_declarant = $("#code_nationalite_declarant");
                var code_nationalite_mere = $("#code_nationalite_mere");
                code_nationalite_declarant.val(code_nationalite_mere.val());
                document.getElementById('code_nationalite_declarant').disabled = true;

                var code_localite_declarant = $("#code_localite_declarant");
                var code_localite_mere = $("#code_localite_mere");
                code_localite_declarant.val(code_localite_mere.val());
                code_localite_declarant.prop("disabled",true);

                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_mere = $("#niveau_instruction_mere");
                niveau_instruction_declarant.val(niveau_instruction_mere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var code_pays_declarant = $("#code_pays_declarant");
                var code_pays_mere = $("#code_pays_mere");
                code_pays_declarant.val(code_pays_mere.val());
                document.getElementById('code_pays_declarant').disabled = true;

                $('#domicile_pays_declarant').val($('#domicile_pays_mere option:selected').text());
                document.getElementById('domicile_pays_declarant').readOnly = true;

                var domicile_ville_declarant = $("#domicile_ville_declarant");
                var domicile_ville_mere = $("#domicile_ville_mere");
                domicile_ville_declarant.val(domicile_ville_mere.val());

                $('#domicile_arrondissement_declarant').attr('readOnly','readOnly');
                var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
                var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
                domicile_arrondissement_declarant.val(domicile_arrondissement_mere.val());

                $('#domicile_quartier_declarant').attr('readOnly','readOnly');
                var domicile_quartier_declarant = $("#domicile_quartier_declarant");
                var domicile_quartier_mere = $("#domicile_quartier_mere");
                domicile_quartier_declarant.val(domicile_quartier_mere.val());

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

                document.getElementById('domicile_numero_declarant').readOnly = false;
                document.getElementById('domicile_nomvoie_declarant').readOnly = false;
                $('#prenom_declarant').val("");
                document.getElementById('prenom_declarant').readOnly = false;
                $('#email_declarant').val("");
                document.getElementById('email_declarant').readOnly = false;
                $('#email_professionnel_declarant').val("");
                document.getElementById('email_professionnel_declarant').readOnly = false;

                $('#date_naissance_declarant').val("");
                document.getElementById('date_naissance_declarant').readOnly = false;

                $('#lieu_naissance_declarant').val("");
                document.getElementById('lieu_naissance_declarant').readOnly = false;

                $('#code_pays_declarant').val("");
                document.getElementById('code_pays_declarant').disabled = false;

                $('#telephone_declarant').val("");
                document.getElementById('telephone_declarant').readOnly = false;

                var profession_declarant = $("#profession_declarant");
                    profession_declarant.val("");
                    $("#profession_declarant option:selected").text();
                    document.getElementById('profession_declarant').disabled = false;

                var code_localite_declarant = $("#code_localite_declarant");
                    code_localite_declarant.val("");
                    $("#code_localite_declarant option:selected").text();
                    document.getElementById('code_localite_declarant').disabled = false;


                var code_nationalite_declarant = $("#code_nationalite_declarant");
                    code_nationalite_declarant.val("");
                    $("#code_nationalite_declarant option:selected").text();
                    document.getElementById('code_nationalite_declarant').disabled = false;

                var code_type_document_declarant = $("#code_type_document_declarant");
                code_type_document_declarant.val("");
                $("#code_type_document_declarant option:selected").text();
                document.getElementById('code_type_document_declarant').disabled = false;

                var numero_document_declarant = $("#numero_document_declarant");
                numero_document_declarant.val("");
                document.getElementById('numero_document_declarant').readOnly = false;

                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                niveau_instruction_declarant.val("");
                $("#niveau_instruction_declarant option:selected").text();
                document.getElementById('niveau_instruction_declarant').disabled = false;

                var sexe_declarant = $("#sexe_declarant");
                sexe_declarant.val("");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = false;

                var filiation = $("#filiation");
                    filiation.val("");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = false;

                $('#statut_personne_declarant').val("VIVANT");


                var type_date_naissance_declarant = $("#type_date_naissance_declarant");
                type_date_naissance_declarant.val("EXACTE");
                $("#type_date_naissance_declarant option:selected").text();
                document.getElementById('type_date_naissance_declarant').disabled = false;

                document.getElementById('type_date_naissance_declarant').checked="";
                document.getElementById('type_date_naissance_declarant').disabled = false;
                document.getElementById('statut_personne_declarant').disabled = false;


                $('#domicile_pays_declarant').val("");

                $("#domicile_ville_declarant").val("");

                $("#domicile_arrondissement_declarant").val("");

                $("#domicile_quartier_declarant").val("");

                $('#domicile_numero_declarant').val("");
                $('#domicile_nomvoie_declarant').val("");
                $('#domicile_typevoie_declarant').val("");
                // $('#domicile_typevoie_declarant').attr('readOnly',false);


            }

        });


});

    var temoinsEnfant = [];
    //insertion témoin dans un tableau
    function insertTemoin(nom,prenom,sexe,date_naissance,
    code_nationalite,lieu_naissance,code_type_document,numero_document,code_localite,
    domicile_pays,domicile_ville,
    domicile_arrondissement,domicile_quartier,
    domicile_typevoie,domicile_numero,domicile_nomvoie)
    {
        temoinsEnfant.push({
            nom_temoin: nom,
            prenom_temoin: prenom,
            sexe_temoin: sexe,
            date_naissance_temoin: date_naissance,
            code_nationalite_temoin: code_nationalite,
            lieu_naissance_temoin: lieu_naissance,
            code_type_document_temoin: code_type_document,
            numero_document_temoin: numero_document,
            code_localite_temoin: code_localite,
            domicile_pays_temoin: domicile_pays,
            domicile_ville_temoin: domicile_ville,
            domicile_arrondissement_temoin: domicile_arrondissement,
            domicile_quartier_temoin: domicile_quartier,
            domicile_typevoie_temoin: domicile_typevoie,
            domicile_numero_temoin:  domicile_numero,
            domicile_nomvoie_temoin: domicile_nomvoie
        });
    }

    //VERIFICATION FORMULAIRE
    var form = $(".validation-wizard").show();

    function soumission()
    {
        // console.log(insertTemoin())
        console.log(temoinsEnfant);
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
         var email_mere = $("#email_mere");
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
         // FIN CODE SETH
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

        var domicile_pays_enfant = $("#domicile_pays_enfant");
        var domicile_ville_enfant = $("#domicile_ville_enfant");
        var autredomicile_ville_enfant = $("#autredomicile_ville_enfant");
        var domicile_arrondissement_enfant = $("#domicile_arrondissement_enfant");
        var domicile_quartier_enfant = $("#domicile_quartier_enfant");
        var domicile_typevoie_enfant = $("#domicile_typevoie_enfant");
        var domicile_numero_enfant = $("#domicile_numero_enfant");
        var domicile_nomvoie_enfant = $("#domicile_nomvoie_enfant");

         //var code_nationalite_enfant = $("#code_nationalite_enfant");
         var sexe_enfant = $("#sexe_enfant");
         var heure_naissance_enfant = $("#heure_naissance_enfant");
         var nombre_enfants = $("#nombre_enfants");
         var statut_personne_enfant = $("#statut_personne_enfant");

         var type_date_naissance_mere = $("#type_date_naissance_mere");
         var statut_personne_mere = $("#statut_personne_mere");


         var type_date_naissance_pere = $("#type_date_naissance_pere");
         var statut_personne_pere = $("#statut_personne_pere");

         var type_date_naissance_declarant = $("#type_date_naissance_declarant");
         var statut_personne_declarant = $("#statut_personne_declarant");

         var domicile_pays_pere = $("#domicile_pays_pere");
         var domicile_ville_pere = $("#domicile_ville_pere");
         var autredomicile_ville_pere = $("#autredomicile_ville_pere");
         var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
         var domicile_quartier_pere = $("#domicile_quartier_pere");
         var domicile_typevoie_pere = $("#domicile_typevoie_pere");
         var domicile_numero_pere = $("#domicile_numero_pere");
         var domicile_nomvoie_pere = $("#domicile_nomvoie_pere");

         var domicile_pays_mere = $("#domicile_pays_mere");
         var domicile_ville_mere = $("#domicile_ville_mere");
         var autredomicile_ville_mere = $("#autredomicile_ville_mere");
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
        var autredomicile_ville_declarant = $("#autredomicile_ville_declarant");
        var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
        var domicile_quartier_declarant = $("#domicile_quartier_declarant");
        var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
        var domicile_numero_declarant = $("#domicile_numero_declarant");
        var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");

        var date_heure_declaration = $("#date_heure_declaration");
        var type_declaration = $("#type_declaration");
        var type_declarant = $("#type_declarant");
        var personne_declaree = $("#personne_declaree");
        var formation_sanitaire_naissance = $("#formation_sanitaire_naissance");

        //champs obligatoires
        var champs = [
                    nom_pere,
                        date_naissance_pere,
                        code_nationalite_pere,

                        telephone_pere,
                        profession_pere,
                        nom_mere,
                        date_naissance_mere,
                        profession_mere,
                        code_nationalite_mere,


                        nom_declarant,
                        code_pays_pere,
                        code_pays_declarant,
                        code_pays_mere,
                        filiation,
                        code_nationalite_declarant,

                        date_naissance_declarant,

                        domicile_pays_declarant,
                        telephone_declarant,
                        domicile_numero_declarant,
                        domicile_pays_declarant,
                        domicile_ville_declarant,
                        domicile_arrondissement_declarant,

                        sexe_declarant,
                        nom_enfant,
                        sexe_enfant,
                        date_naissance_enfant,
                        lieu_naissance_enfant,
                        lieu_survenance,
                        code_situation_matrimoniale,
                        heure_naissance_enfant,
                        code_type_document_declarant,
                        numero_document_declarant
                        ];

        // Choix de la ville selon le pays
        let ville_pere = domicile_pays_pere.val() === 'Congo' ? domicile_ville_pere.val() : autredomicile_ville_pere.val();
        let ville_mere = domicile_pays_mere.val() === 'Congo' ? domicile_ville_mere.val() : autredomicile_ville_mere.val();
        let ville_enfant = domicile_pays_enfant.val() === 'Congo' ? domicile_ville_enfant.val() : autredomicile_ville_enfant.val();
        let ville_declarant = domicile_pays_declarant.val() === 'Congo' ? domicile_ville_declarant.val() : autredomicile_ville_declarant.val();

        var data =
        {
            // images_pere: images_pere,
            // images_mere: images_mere,
            // images_declarant: images_declarant,
             // données du père
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
              // données de la mère
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
            statut_personne_enfant:statut_personne_enfant.val(),
            type_date_naissance_declarant:type_date_naissance_declarant.val(),
            statut_personne_declarant:statut_personne_declarant.val(),
            type_date_naissance_mere:type_date_naissance_mere.val(),
            statut_personne_mere:statut_personne_mere.val(),
            type_date_naissance_pere:type_date_naissance_pere.val(),
            statut_personne_pere:statut_personne_pere.val(),
            email_pere:email_pere.val(),
            email_mere:email_mere.val(),
            email_declarant:email_declarant.val(),
            email_professionnel_pere:$("#email_professionnel_pere").val() || '',
            email_professionnel_mere:$("#email_professionnel_mere").val() || '',
            email_professionnel_declarant:$("#email_professionnel_declarant").val() || '',
            domicile_pays_pere:domicile_pays_pere.val(),


            domicile_arrondissement_pere:domicile_arrondissement_pere.val(),
            domicile_quartier_pere:domicile_quartier_pere.val(),
            domicile_typevoie_pere:domicile_typevoie_pere.val(),
            domicile_numero_pere:domicile_numero_pere.val(),
            domicile_nomvoie_pere:domicile_nomvoie_pere.val(),
            domicile_pays_mere:domicile_pays_mere.val(),
            domicile_arrondissement_mere:domicile_arrondissement_mere.val(),
            domicile_quartier_mere:domicile_quartier_mere.val(),
            domicile_typevoie_mere:domicile_typevoie_mere.val(),
            domicile_numero_mere:domicile_numero_mere.val(),
            domicile_nomvoie_mere:domicile_nomvoie_mere.val(),
            domicile_pays_declarant:domicile_pays_declarant.val(),
            domicile_arrondissement_declarant:domicile_arrondissement_declarant.val(),
            domicile_quartier_declarant:domicile_quartier_declarant.val(),
            domicile_typevoie_declarant:domicile_typevoie_declarant.val(),
            domicile_numero_declarant:domicile_numero_declarant.val(),
            domicile_nomvoie_declarant:domicile_nomvoie_declarant.val(),
            date_heure_declaration:date_heure_declaration.val(),
            type_declaration:type_declaration.val(),
            type_declarant:type_declarant.val(),
            personne_declaree:personne_declaree.val(),
            formation_sanitaire_naissance:formation_sanitaire_naissance.val(),

            domicile_pays_enfant:domicile_pays_enfant.val(),
            domicile_arrondissement_enfant:domicile_arrondissement_enfant.val(),
            domicile_quartier_enfant:domicile_quartier_enfant.val(),
            domicile_typevoie_enfant:domicile_typevoie_enfant.val(),
            domicile_numero_enfant:domicile_numero_enfant.val(),
            domicile_nomvoie_enfant:domicile_nomvoie_enfant.val(),

            domicile_ville_pere: ville_pere,
            domicile_ville_mere: ville_mere,
            domicile_ville_enfant: ville_enfant,
            domicile_ville_declarant: ville_declarant,

            _token: '{{ csrf_token() }}'
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
                Swal.close();
                if (typeof sifecSwalLoading === 'function') {
                    sifecSwalLoading('Enregistrement...');
                }
                $.post("{{route('declarationNaissance.store')}}",data,function(response)
                {
                    if (typeof Swal !== 'undefined') { Swal.close(); }
                    if(response.code == "200")
                    {
                        var type_declaration = $("#type_declaration").val();
                        var url = "";

                        // Vérifier le type d'institution de l'utilisateur
                        var userInstitutionType = "{{ Auth::user()->affectationActive()->institution->code_type_institution ?? '' }}";

                        // Si c'est une formation sanitaire, toujours rediriger vers l'index principal
                        if(userInstitutionType !== "TPINS_0002") {
                            url = "{{ route('declarationNaissance.index') }}";
                        } else {
                            // Pour les centres d'état civil, utiliser la logique normale
                            if(type_declaration == "DECLARATION DE NAISSANCE"){
                                url = "{{ route('declarationNaissance.index') }}";
                            }else if(type_declaration == "CERTIFICAT DE DESTRUCTION DE L'ACTE"){
                                url = "{{ route('certificatDestruction.index') }}";
                            }else if(type_declaration == "CERTIFICAT DE TRANSCRIPTION"){
                                url = "{{ route('certificatTranscription.index') }}";
                            }else{
                                url = "{{ route('certificatNonInscription.index') }}";
                            }
                        }

                        flashAlert("Opération réussie","success",response.message);

                        setTimeout(() => {
                            window.open(url);
                        }, 2000);

                    }else{
                        // Gestion améliorée des messages d'erreur
                        var messageErreur = traiterMessageErreur(response);
                        flashAlert("Opération échouée","error",messageErreur);
                    }

                }).fail(function(xhr) {
                    if (typeof Swal !== 'undefined') { Swal.close(); }
                    // Gestion des erreurs de connexion
                    var messageErreur = "Erreur de connexion. Veuillez vérifier votre connexion internet et réessayer.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        messageErreur = traiterMessageErreur(xhr.responseJSON);
                    }
                    flashAlert("Erreur de connexion","error",messageErreur);
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

            nom_pere:
            {
                required: true,

            },

             date_naissance_pere:
             {
                 required: true,

              },

             lieu_naissance_pere:
             {
                 required: true,

              },
             code_nationalite_pere:
             {
                 required: true,

              },

             statut_personne_pere:
             {
                required: true,

              },
              nom_mere:
             {
                 required: true,

              },

             date_naissance_mere:
             {
                 required: true,

              },

             lieu_naissance_mere:
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
              numero_document_declarant:
              {
                  required: true,

               },
               code_type_document_declarant:
               {
                   required: true,

                },
              filiation:
              {
                  required: true,

               },
              nom_declarant:
              {
                  required: true,

               },

              date_naissance_declarant:
              {
                  required: true,

               },

              lieu_naissance_declarant:
              {
                  required: true,

               },
              code_nationalite_declarant:
              {
                  required: true,

               },
              telephone_declarant:
              {
                  required: true,

               },
              statut_personne_declarant:
              {
                  required: true,

               },
             sexe_enfant:{
                 required:true,

             },
             nom_enfant:
             {
                 required: true,

              },
              date_naissance_enfant:{
                 required:true,
              },
              lieu_naissance_enfant:{
                 required:true,
              },
              heure_naissance_enfant:{
                 required:true,
              },
              code_situation_matrimoniale:{
                 required:true,
              },
              nombre_enfants:{
                 required:true,
              },
              nombre_temoin:{
                 required:true,
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
             filiation:
             {
                 required: "Veuillez selectionner la filiation",

              },
             nom_enfant:
             {
             required: "Veuillez saisir le nom",
             },
             sexe_enfant:
             {
             required: "Veuillez selectionner le sexe",
             },
             date_naissance_enfant:
             {
                 required: "Veuillez selectionner une date",
             },
             lieu_naissance_enfant:{
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
             code_situation_matrimoniale:{
             required: "Veuillez selectionner un element",
             },
             nombre_enfants:{
                 required: "Veuillez choisir le nombre des enfants",
             },
             nombre_temoin:{
                 required: "Veuillez choisir le nombre des témoins",
             },
             nom_pere:
             {
                 required: "Veuillez saisir un nom",

              },

             date_naissance_pere:
             {
                 required: "Veuillez selectionner une date",

              },

             lieu_naissance_pere:
             {
                 required: "Veuillez saisir un lieu",

              },
             code_nationalite_pere:
             {
                 required: "Veuillez selectionner une nationalité",

              },

             statut_personne_pere:
             {
                 required: "Veuillez selectionner un statut",

              },
              nom_mere:
              {
                  required: "Veuillez saisir un nom",

               },

              date_naissance_mere:
              {
                  required: "Veuillez selectionner une date",

               },

              lieu_naissance_mere:
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
               nom_declarant:
               {
                   required: "Veuillez saisir un nom",

                },

               date_naissance_declarant:
               {
                   required: "Veuillez selectionner une date",

                },

               lieu_naissance_declarant:
               {
                   required: "Veuillez saisir un lieu",

                },
               code_nationalite_declarant:
               {
                   required: "Veuillez selectionner une nationalité",

                },
               telephone_declarant:
               {
                   required: "Veuillez saisir un numero de téléphone",

                },
               statut_personne_declarant:
               {
                   required: "Veuillez selectionner un statut",

                },
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
        $('#email_professionnel_pere').val("");

        $('#lieu_naissance_pere').val("");
        document.getElementById('lieu_naissance_pere').readOnly = false;

        $('#code_localite_pere').val("");

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

        $('#email_mere').val("");
        document.getElementById('email_mere').readOnly = false;
        $('#email_professionnel_mere').val("");

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
        // Rechercher un père
    $('#rechercher').on("click", function (event) {
        event.preventDefault();
        // data = [];
        var nom = $("#nom_pere_recherche");
        var prenom = $("#prenom_pere_recherche");
        var sexe = $("#sexe_pere_recherche");
        var telephone = $("#telephone_pere_recherche");
        var data = {
            nom: nom.val(),
            prenom: prenom.val(),
            sexe: sexe.val(),
            telephone: telephone.val(),
            _token: '{{ csrf_token() }}'
        };

        var int = 0;
        var table = '<div class="table-responsive">'+
                        '<table id="example" class="table table-responsive-md table-hover">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th>#</th>'+
                                    '<th><strong>Nom et prénom</strong></th>'+
                                    '<th><strong>Sexe</strong></th>'+
                                    '<th><strong>Naissance</strong></th>'+
                                    '<th><strong>Téléphone</strong></th>'+
                                    '<th><strong>Piece</strong></th>'+
                            ' </tr>'+
                            '</thead>'+
                            '<tbody>';

        var btnRechPere = this;
        if (typeof sifecBtnLoading === 'function') {
            sifecBtnLoading(btnRechPere, 'Recherche...');
        }
        //traitement ajax
        $.ajax({
                url: "{{ route('declarationNaissance.recherchePersonne') }}",
                type: "POST",
                data: data,
                success: function(response){

                    if(response.personnes.length > 0)
                    {

                        for( var i=0; i < response.personnes.length ; i++){
                            int ++;

                            table +='<tr class="tr" data-choix="'+response.personnes[i].id+
                                    '" data-code_type_document="'+response.personnes[i].code_type_document+
                                    '" data-numero_document="'+response.personnes[i].numero_document+
                                    '" data-nom="'+response.personnes[i].nom+
                                    '" data-prenom="'+response.personnes[i].prenom+
                                    '" data-date_naissance="'+response.personnes[i].date_naissance+
                                    '" data-email="'+response.personnes[i].email_personnelle+'" data-email-professionnel="'+(response.personnes[i].email_professionnelle || '')+
                                    '" data-sexe="'+response.personnes[i].sexe+
                                    '" data-numero="'+response.personnes[i].numero_rue+
                                    '" data-rue="'+response.personnes[i].avenue+
                                    '" data-quartier="'+response.personnes[i].quartier+
                                    '" data-arrondissement="'+response.personnes[i].code_arrondissement+
                                    '" data-telephone="'+response.personnes[i].phone+
                                    '" data-indicatif="'+response.personnes[i].indicatif+
                                    '" data-code_nationalite="'+response.personnes[i].code_nationalite+
                                    '" data-code_profession="'+response.personnes[i].code_profession+
                                    '" data-lieu_naissance="'+response.personnes[i].lieu_naissance+
                                    '" data-niveau_instruction="'+response.personnes[i].niveau_instruction+
                                    '" data-statut_personne="'+response.personnes[i].statut_personne+
                                    '" data-type_date_naissance="'+response.personnes[i].type_date_naissance+
                                    '" data-nom="'+response.personnes[i].nom+'">'+

                                    '<td><strong>'+int+'</strong></td>'+
                                    '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                    '<td>'+response.personnes[i].sexe+'</td>'+
                                    '<td>'+response.personnes[i].date_naissance+'</td>'+
                                    '<td>'+response.personnes[i].indicatif+response.personnes[i].phone+'</td>'+
                                    '<td>'+response.personnes[i].numero_document+'</td>'+

                                    '</tr>';

                    }
                    }
                    table += "</tbody><thead><tr><th>#</th><th>Nom et prénom</th><th>Sexe</th><th>Naissance</th><th>Téléphone</th><th></th></tr></thead></table></div>";
                    $("#resultatPere").html(table);

                    $("tr.tr").on("click", function (){
                        var choix = $(this).data('choix');
                        var nom = $(this).data('nom');
                        var prenom = $(this).data('prenom');
                        var date_naissance = $(this).data('date_naissance');
                        var sexe = $(this).data('sexe');
                        var numero = $(this).data('numero');
                        var rue = $(this).data('rue');
                        var quartier = $(this).data('quartier');
                        var arrondissement = $(this).data('arrondissement');
                        var telephone = $(this).data('telephone');
                        var indicatif = $(this).data('indicatif');
                        var email = $(this).data('email');
                        var emailProfessionnel = $(this).data('emailProfessionnel') || '';

                        var code_nationalite = $(this).data('code_nationalite');
                        var code_profession = $(this).data('code_profession');
                        var lieu_naissance = $(this).data('lieu_naissance');
                        var niveau_instruction = $(this).data('niveau_instruction');
                        var numero_document = $(this).data('numero_document');
                        var code_type_document = $(this).data('code_type_document');
                        var statut_personne = $(this).data('statut_personne');
                        var type_date_naissance = $(this).data('type_date_naissance');

                        $("#nom_pere").val(nom);
                        $("#prenom_pere").val(prenom);
                        $("#date_naissance_pere").val(date_naissance);
                        $("#email_pere").val(email);
                        $("#email_professionnel_pere").val(emailProfessionnel);
                        $("#sexe_pere").val(sexe);
                        $("#domicile_numero_pere").val(numero);
                        $("#domicile_nomvoie_pere").val(rue);
                        $("#domicile_quartier_pere").val(quartier);
                        if($(this).data('arrondissement')===null)
                        {
                        $("#domicile_ville_pere").val("choisissez");
                        }else{
                            $("#domicile_ville_pere").val(arrondissement);
                        }

                        $("#telephone_pere").val(telephone);
                        $("#code_nationalite_pere").val(code_nationalite);
                        $("#profession_pere").val(code_profession);
                        $("#lieu_naissance_pere").val(lieu_naissance);
                        $("#niveau_instruction_pere").val(niveau_instruction);
                        $("#numero_document_pere").val(numero_document);
                        $("#code_type_document_pere").val(code_type_document);
                        $("#statut_personne_pere").val(statut_personne);
                        $("#type_date_naissance_pere").val(type_date_naissance);
                        $("#code_pays_pere").val(indicatif);

                        if($(this).data('type_date_naissance')==="EXACTE")
                        {
                            $("#type_date_naissance_pere").val("EXACTE");
                            document.getElementById('type_date_naissance_pere').checked="";
                        }else{
                            $("#type_date_naissance_pere").val("ESTIME");
                            document.getElementById('type_date_naissance_pere').checked="ESTIME";

                        }
                        document.getElementById('nom_pere').readOnly = true;
                        document.getElementById('prenom_pere').readOnly = true;
                        document.getElementById('date_naissance_pere').readOnly = true;
                        document.getElementById('lieu_naissance_pere').readOnly = true;
                        document.getElementById('code_nationalite_pere').disabled = true;
                        document.getElementById('type_date_naissance_pere').disabled = true;
                        document.getElementById('statut_personne_pere').disabled = true

                        $("#rmodal").modal('hide');
                        // console.log(response.personnes);

                    //  getdocument(choix);

                    });

                },
                error: function() {
                    if (typeof flashAlert === 'function') {
                        flashAlert('Erreur', 'error', 'Impossible de lancer la recherche.');
                    }
                },
                complete: function() {
                    if (typeof sifecBtnReset === 'function') {
                        sifecBtnReset(btnRechPere);
                    }
                }
        });
    });

     // Rechercher une mère
     $('#recherchermere').on("click", function (event) {
         event.preventDefault();

         var nom = $("#nom_mere_recherche");
         var prenom = $("#prenom_mere_recherche");
         var sexe = $("#sexe_mere_recherche");
         var telephone = $("#telephone_mere_recherche");
         var data = {
             nom: nom.val(),
             prenom: prenom.val(),
             sexe: sexe.val(),
             telephone: telephone.val(),
             _token: '{{ csrf_token() }}'
         };

         var int = 0;
         var table = '<div class="table-responsive">'+
                         '<table class="table table-responsive-md table-hover">'+
                             '<thead>'+
                                 '<tr>'+
                                     '<th>#</th>'+
                                     '<th><strong>Nom et prénom</strong></th>'+
                                     '<th><strong>Sexe</strong></th>'+
                                     '<th><strong>Naissance</strong></th>'+
                                     '<th><strong>Téléphone</strong></th>'+
                                     '<th><strong>Piece</strong></th>'+
                             ' </tr>'+
                             '</thead>'+
                             '<tbody>';
         var btnRechMere = this;
         if (typeof sifecBtnLoading === 'function') {
             sifecBtnLoading(btnRechMere, 'Recherche...');
         }
         //traitement ajax
         $.ajax({
                 url: "{{ route('declarationNaissance.recherchePersonne') }}",
                 type: "POST",
                 data: data,
                 success: function(response){
                     if(response.personnes.length > 0){

                         for( var i=0; i < response.personnes.length ; i++){
                             int ++;

                             table +='<tr class="tr" data-choix="'+response.personnes[i].id+
                             '" data-code_type_document="'+response.personnes[i].code_type_document+
                             '" data-numero_document="'+response.personnes[i].numero_document+
                             '" data-nom="'+response.personnes[i].nom+
                             '" data-prenom="'+response.personnes[i].prenom+
                             '" data-date_naissance="'+response.personnes[i].date_naissance+
                             '" data-email="'+response.personnes[i].email_personnelle+'" data-email-professionnel="'+(response.personnes[i].email_professionnelle || '')+
                             '" data-sexe="'+response.personnes[i].sexe+
                             '" data-numero="'+response.personnes[i].numero_rue+
                             '" data-rue="'+response.personnes[i].avenue+
                             '" data-quartier="'+response.personnes[i].quartier+
                             '" data-arrondissement="'+response.personnes[i].code_arrondissement+
                             '" data-telephone="'+response.personnes[i].phone+
                             '" data-indicatif="'+response.personnes[i].indicatif+
                             '" data-code_nationalite="'+response.personnes[i].code_nationalite+
                             '" data-code_profession="'+response.personnes[i].code_profession+
                             '" data-lieu_naissance="'+response.personnes[i].lieu_naissance+
                             '" data-niveau_instruction="'+response.personnes[i].niveau_instruction+
                             '" data-statut_personne="'+response.personnes[i].statut_personne+
                             '" data-type_date_naissance="'+response.personnes[i].type_date_naissance+
                             '" data-nom="'+response.personnes[i].nom+'">'+
                             '<td><strong>'+int+'</strong></td>'+
                             '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                             '<td>'+response.personnes[i].sexe+'</td>'+
                             '<td>'+response.personnes[i].date_naissance+'</td>'+
                             '<td>'+response.personnes[i].indicatif+response.personnes[i].phone+'</td>'+
                             '<td>'+response.personnes[i].numero_document+'</td>'+
                             '</tr>';
                        }
                     }
                     table += "</tr></tbody></table></div>";
                     $("#resultatMere").html(table);

                     $("tr.tr").on("click", function (){
                         var choix = $(this).data('choix');
                         var nom = $(this).data('nom');
                         var prenom = $(this).data('prenom');
                         var date_naissance = $(this).data('date_naissance');
                         var sexe = $(this).data('sexe');
                         var numero = $(this).data('numero');
                         var rue = $(this).data('rue');
                         var quartier = $(this).data('quartier');
                         var arrondissement = $(this).data('arrondissement');
                         var telephone = $(this).data('telephone');
                         var indicatif = $(this).data('indicatif');
                         var email = $(this).data('email');
                         var emailProfessionnel = $(this).data('emailProfessionnel') || '';

                         var code_nationalite = $(this).data('code_nationalite');
                         var code_profession = $(this).data('code_profession');
                         var lieu_naissance = $(this).data('lieu_naissance');
                         var niveau_instruction = $(this).data('niveau_instruction');
                         var numero_document = $(this).data('numero_document');
                         var code_type_document = $(this).data('code_type_document');
                         var statut_personne = $(this).data('statut_personne');
                         var type_date_naissance = $(this).data('type_date_naissance');

                         $("#nom_mere").val(nom);
                         $("#prenom_mere").val(prenom);
                         $("#date_naissance_mere").val(date_naissance);
                         $("#sexe_mere").val(sexe);
                         $("#email_mere").val(email);
                         $("#email_professionnel_mere").val(emailProfessionnel);
                         $("#domicile_numero_mere").val(numero);
                         $("#domicile_nomvoie_mere").val(rue);
                         $("#domicile_quartier_mere").val(quartier);
                         if($(this).data('arrondissement')===null)
                         {
                           $("#domicile_ville_mere").val("choisissez");
                         }else{
                             $("#domicile_ville_mere").val(arrondissement);
                         }

                         $("#telephone_mere").val(telephone);
                         $("#code_nationalite_mere").val(code_nationalite);
                         $("#profession_mere").val(code_profession);
                         $("#lieu_naissance_mere").val(lieu_naissance);
                         $("#niveau_instruction_mere").val(niveau_instruction);
                         $("#numero_document_mere").val(numero_document);
                         $("#code_type_document_mere").val(code_type_document);
                         $("#statut_personne_mere").val(statut_personne);
                         $("#type_date_naissance_mere").val(type_date_naissance);
                         $("#code_pays_mere").val(indicatif);

                         if($(this).data('type_date_naissance')==="EXACTE")
                         {
                             $("#type_date_naissance_mere").val("EXACTE");
                             document.getElementById('type_date_naissance_mere').checked="";
                         }else{
                             $("#type_date_naissance_mere").val("ESTIME");
                             document.getElementById('type_date_naissance_mere').checked="ESTIME";

                         }
                         document.getElementById('nom_mere').readOnly = true;
                         document.getElementById('prenom_mere').readOnly = true;
                         document.getElementById('date_naissance_mere').readOnly = true;
                         document.getElementById('lieu_naissance_mere').readOnly = true;
                         document.getElementById('code_nationalite_mere').disabled = true;
                         document.getElementById('type_date_naissance_mere').disabled = true;
                         document.getElementById('statut_personne_mere').disabled = true;


                         $("#meremodal").modal('hide');
                        // getdocumentmere(choix);
                         // console.log(response.personnes);

                     });

                 },
                 error: function() {
                     if (typeof flashAlert === 'function') {
                         flashAlert('Erreur', 'error', 'Impossible de lancer la recherche.');
                     }
                 },
                 complete: function() {
                     if (typeof sifecBtnReset === 'function') {
                         sifecBtnReset(btnRechMere);
                     }
                 }
         });
     });

     // Rechercher un déclarant
     $('#rechercherdeclarant').on("click", function (event) {
         event.preventDefault();

         var nom = $("#nom_declarant_recherche");
         var prenom = $("#prenom_declarant_recherche");
         var sexe = $("#sexe_declarant_recherche");
         var telephone = $("#telephone_declarant_recherche");
         var statut = $('#statut_personne_declarant_recherche');

         var data = {
             nom: nom.val(),
             prenom: prenom.val(),
             sexe: sexe.val(),
             telephone: telephone.val(),
             statut:statut.val(),
             _token: '{{ csrf_token() }}'
         };

         var int = 0;

         var table = '<div class="table-responsive">'+
                         '<table class="table table-responsive-md table-hover">'+
                             '<thead>'+
                                 '<tr>'+
                                     '<th>#</th>'+
                                     '<th><strong>Nom et prénom</strong></th>'+
                                     '<th><strong>Sexe</strong></th>'+
                                     '<th><strong>Naissance</strong></th>'+
                                     '<th><strong>Téléphone</strong></th>'+
                                     '<th><strong>Piece</strong></th>'+
                             ' </tr>'+
                             '</thead>'+
                             '<tbody>';

         var btnRechDecl = this;
         if (typeof sifecBtnLoading === 'function') {
             sifecBtnLoading(btnRechDecl, 'Recherche...');
         }
         //traitement ajax
         $.ajax({
                 url: "{{ route('declarationNaissance.recherchePersonne') }}",
                 type: "POST",
                 data: data,

                 success: function(response){

                     if(response.personnes.length > 0){

                         for( var i=0; i < response.personnes.length ; i++){
                             int ++;

                             table +='<tr class="tr" data-choix="'+response.personnes[i].id+
                             '" data-code_type_document="'+response.personnes[i].code_type_document+
                             '" data-numero_document="'+response.personnes[i].numero_document+
                             '" data-nom="'+response.personnes[i].nom+
                             '" data-prenom="'+response.personnes[i].prenom+
                             '" data-date_naissance="'+response.personnes[i].date_naissance+
                             '" data-email="'+response.personnes[i].email_personnelle+'" data-email-professionnel="'+(response.personnes[i].email_professionnelle || '')+
                             '" data-sexe="'+response.personnes[i].sexe+
                             '" data-numero="'+response.personnes[i].numero_rue+
                             '" data-rue="'+response.personnes[i].avenue+
                             '" data-quartier="'+response.personnes[i].quartier+
                             '" data-arrondissement="'+response.personnes[i].code_arrondissement+
                             '" data-telephone="'+response.personnes[i].phone+
                             '" data-indicatif="'+response.personnes[i].indicatif+
                             '" data-code_nationalite="'+response.personnes[i].code_nationalite+
                             '" data-code_profession="'+response.personnes[i].code_profession+
                             '" data-lieu_naissance="'+response.personnes[i].lieu_naissance+
                             '" data-niveau_instruction="'+response.personnes[i].niveau_instruction+
                             '" data-statut_personne="'+response.personnes[i].statut_personne+
                             '" data-type_date_naissance="'+response.personnes[i].type_date_naissance+
                             '" data-nom="'+response.personnes[i].nom+'">'+

                             '<td><strong>'+int+'</strong></td>'+
                             '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                             '<td>'+response.personnes[i].sexe+'</td>'+
                             '<td>'+response.personnes[i].date_naissance+'</td>'+
                             '<td>'+response.personnes[i].indicatif+response.personnes[i].phone+'</td>'+
                             '<td>'+response.personnes[i].numero_document+'</td>'+
                             '</tr>';
                        }
                     }
                     table += "</tr></tbody></table></div>";
                     $("#resultatDeclarant").html(table);

                     $("tr.tr").on("click", function (){
                         var choix = $(this).data('choix');
                         var nom = $(this).data('nom');
                         var prenom = $(this).data('prenom');
                         var date_naissance = $(this).data('date_naissance');
                         var sexe = $(this).data('sexe');
                         var numero = $(this).data('numero');
                         var rue = $(this).data('rue');
                         var quartier = $(this).data('quartier');
                         var arrondissement = $(this).data('arrondissement');
                         var telephone = $(this).data('telephone');
                         var indicatif = $(this).data('indicatif');
                         var email = $(this).data('email');
                         var emailProfessionnel = $(this).data('emailProfessionnel') || '';

                         var code_nationalite = $(this).data('code_nationalite');
                         var code_profession = $(this).data('code_profession');
                         var lieu_naissance = $(this).data('lieu_naissance');
                         var niveau_instruction = $(this).data('niveau_instruction');
                         var numero_document = $(this).data('numero_document');
                         var code_type_document = $(this).data('code_type_document');
                         var statut_personne = $(this).data('statut_personne');
                         var type_date_naissance = $(this).data('type_date_naissance');


                         $("#nom_declarant").val(nom);
                         $("#prenom_declarant").val(prenom);
                         $("#date_naissance_declarant").val(date_naissance);
                         $("#sexe_declarant").val(sexe);
                         $("#email_declarant").val(email);
                         $("#email_professionnel_declarant").val(emailProfessionnel);
                         $("#domicile_quartier_declarant").val(quartier);
                         if($(this).data('arrondissement')===null)
                         {
                           $("#domicile_ville_declarant").val("choisissez");
                         }else{
                             $("#domicile_ville_declarant").val(arrondissement);
                         }


                         $("#telephone_declarant").val(telephone);
                         $("#code_nationalite_declarant").val(code_nationalite);
                         $("#profession_declarant").val(code_profession);
                         $("#lieu_naissance_declarant").val(lieu_naissance);
                         $("#niveau_instruction_declarant").val(niveau_instruction);
                         $("#numero_document_declarant").val(numero_document);
                         $("#code_type_document_declarant").val(code_type_document);
                         $("#statut_personne_declarant").val(statut_personne);
                         $("#type_date_naissance_declarant").val(type_date_naissance);
                         $("#code_pays_declarant").val(indicatif);

                         if($(this).data('type_date_naissance')==="EXACTE")
                         {
                             $("#type_date_naissance_declarant").val("EXACTE");
                             document.getElementById('type_date_naissance_declarant').checked="";
                         }else{
                             $("#type_date_naissance_declarant").val("ESTIME");
                             document.getElementById('type_date_naissance_declarant').checked="ESTIME";

                         }

                         document.getElementById('nom_declarant').readOnly = true;
                         document.getElementById('prenom_declarant').readOnly = true;
                         document.getElementById('date_naissance_declarant').readOnly = true;
                         document.getElementById('lieu_naissance_declarant').readOnly = true;
                         document.getElementById('code_nationalite_declarant').disabled = true;
                         document.getElementById('type_date_naissance_declarant').disabled = true;
                         document.getElementById('statut_personne_declarant').disabled = true;

                         $("#declarantmodal").modal('hide');
                       //  getdocumentdeclarant(choix);
                         //console.log(response.personnes);

                     });

                 },
                 error: function() {
                     if (typeof flashAlert === 'function') {
                         flashAlert('Erreur', 'error', 'Impossible de lancer la recherche.');
                     }
                 },
                 complete: function() {
                     if (typeof sifecBtnReset === 'function') {
                         sifecBtnReset(btnRechDecl);
                     }
                 }
         });
     });

    function getArrComUrbaine(codeparent,cle){
        var route = "{{ route('declarationNaissance.search.arrond') }}";
        var option = "<option value=''> Selectionnez </option>";

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
        var option = "<option value=''> Selectionnez </option>";

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

    // Gestion dynamique de l'adresse de l'enfant selon le pays
    $('#domicile_pays_enfant').on('change', function() {
        if ($(this).val() === 'Congo') {
            $('#congo_hierarchie_adresse').removeClass('d-none');
            $('#autre_pays_adresse').addClass('d-none');
        } else {
            $('#congo_hierarchie_adresse').addClass('d-none');
            $('#autre_pays_adresse').removeClass('d-none');
        }
    });
    // Initialisation à l'ouverture (Congo sélectionné par défaut)
    if ($('#domicile_pays_enfant').val() === 'Congo') {
        $('#congo_hierarchie_adresse').removeClass('d-none');
        $('#autre_pays_adresse').addClass('d-none');
    } else {
        $('#congo_hierarchie_adresse').addClass('d-none');
        $('#autre_pays_adresse').removeClass('d-none');
    }

    if ($('#domicile_pays_pere').length) {
        $('#domicile_pays_pere').trigger('change');
    }

</script>
@endsection
