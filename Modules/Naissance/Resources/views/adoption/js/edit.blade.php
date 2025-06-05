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

    function loadData(codeparent){
        var route = "{{ route('declarationNaissance.get.document', ':id') }}";
        route = route.replace(":id",codeparent);
        $.get(route,function(response){
            $("tbody#tbody").html(response);
        });
    }


$(function(){
    //CAS D'ADOPTION
    var type_adoption = "";
    // Fonction pour gérer l'état des champs
    function toggleReadonly(isReadonly) {
        $('#nom_enfant, #prenom_enfant, #sexe_enfant, #date_naissance_enfant, #lieu_naissance_enfant, #heure_naissance_enfant, #code_localite_enfant, #code_lieu_survenance, #code_nationalite_enfant, #profession_enfant, #niveau_instruction_enfant, #code_type_document_enfant, #numero_document_enfant,#domicile_pays_enfant,#domicile_ville_enfant,#autredomicile_ville_enfant,#domicile_arrondissement_enfant,#domicile_quartier_enfant,#domicile_typevoie_enfant,#domicile_numero_enfant,#domicile_nomvoie_enfant,#code_pays_enfant,#telephone_enfant,#email_enfant,#code_situation_matrimoniale,#nombre_enfants,#date_heure_declaration,#statut_personne_enfant,#nom_pere,#prenom_pere,#date_naissance_pere,#lieu_naissance_pere,#code_localite_pere,#code_nationalite_pere,#profession_pere,#niveau_instruction_pere,#code_type_document_pere,#numero_document_pere,#domicile_pays_pere,#domicile_ville_pere,#domicile_arrondissement_pere,#domicile_quartier_pere,#domicile_typevoie_pere,#domicile_numero_pere,#domicile_nomvoie_pere,#code_pays_pere,#telephone_pere,#email_pere,#statut_personne_pere,#nom_mere, #prenom_mere, #date_naissance_mere, #lieu_naissance_mere,#code_localite_mere, #code_nationalite_mere, #profession_mere, #niveau_instruction_mere,#code_type_document_mere,#numero_document_mere,#domicile_pays_mere,#domicile_ville_mere,#domicile_arrondissement_mere,#domicile_quartier_mere,#domicile_typevoie_mere,#domicile_numero_mere,#domicile_nomvoie_mere,#code_pays_mere,#telephone_mere,#email_mere,#statut_personne_mere')
            .prop('readonly', isReadonly) // pour les champs input
            .prop('disabled', isReadonly); // pour les champs select
    }

    // Initialisation des champs selon le bouton radio sélectionné au chargement de la page
    if ($('input[name="adoption"]:checked').val() === 'adoption partielle') {
        toggleReadonly(true);
    } else {
        toggleReadonly(false);
    }

    // Détection du changement du bouton radio
    $('input[name="adoption"]').change(function() {
        if ($(this).val() === 'adoption partielle') {
            type_adoption = $("#partielle").val();
            toggleReadonly(true);
        } else {
            toggleReadonly(false);
            type_adoption = $("#pleniere").val();
        }
    });
    //FIN CAS D'ADOPTION




    //GESTION DOCUMENT SCANNER
    var codeparent = "";
    var typedocumentpere = $("#typedocumentpere").val();
    var numerodocumentpere = $("#numerodocumentpere").val();
    var imagepere = $("#imagepere").val();


    var typedocumentmere = $("#typedocumentmere").val();
    var numerodocumentmere = $("#numerodocumentmere").val();
    var imagemere = $("#imagemere").val();

    var codeadoptant = $("#codeadoptant").val();
    var typedocumentadoptant = $("#typedocumentadoptant").val();
    var numerodocumentadoptant = $("#numerodocumentadoptant").val();
    var imageadoptant = $("#imageadoptant").val();


    $("button.choix").on("click", function(){

        var choix = $(this).attr("parent");
        codeparent = $("#code"+choix).val();
        $("#codeparent").val(codeparent);
        loadData(codeparent);

        if(choix == "pere"){
            $("button#btnImporter").prop("disabled",false);
            $("button#btnScanner").prop("disabled",false);
            $("button#btnScanner").html("Scanner la pièce");
            $("#pieceparentLabel").html("Joindre la pièce du "+choix);
            $("#reftypedocumentparent").val(typedocumentpere);
            $("#refdocumentparent").val(numerodocumentpere);
            if(imagepere != ""){
                $("button#btnImporter").prop("disabled",true);
                $("button#btnScanner").prop("disabled",true);
                $("button#btnScanner").html("Pièce déjà scannée.");
            }

        }
        if(choix == "mere"){
            $("button#btnImporter").prop("disabled",false);
            $("button#btnScanner").prop("disabled",false);
            $("button#btnScanner").html("Scanner la pièce");
            $("#pieceparentLabel").html("Joindre la pièce de la "+choix)
            $("#reftypedocumentparent").val(typedocumentmere);
            $("#refdocumentparent").val(numerodocumentmere);
            if(imagemere != ""){
                $("button#btnImporter").prop("disabled",true);
                $("button#btnScanner").prop("disabled",true);
                $("button#btnScanner").html("Pièce déjà scannée.");
            }
        }
        if(choix == "adoptant"){
            $("button#btnImporter").prop("disabled",false);
            $("button#btnScanner").prop("disabled",false);
            $("button#btnScanner").html("Scanner la pièce");
            $("#pieceparentLabel").html("Joindre la pièce du "+choix)
            $("#reftypedocumentparent").val(typedocumentadoptant);
            $("#refdocumentparent").val(numerodocumentadoptant);
            if(imageadoptant != ""){
                $("button#btnImporter").prop("disabled",true);
                $("button#btnScanner").prop("disabled",true);
                $("button#btnScanner").html("Pièce déjà scannée.");
            }
        }
        // return false;

    //DEBUT GESTION DES PIECES JOINTES
      // Valider la piece jointe
    $('#formPiece').on('submit', function(e){
        e.preventDefault();
        var form = this;

        // flashAlert('Confirmation',"success",'Confirmez-vous l\'ajout de cette pièce ? ')
        swal.fire({
            title:'Confirmation',
            html:'Confirmez-vous l\'ajout de cette pièce ? ',
            showCancelButton:true,
            showCloseButton:true,
            cancelButtonText:'Annuler',
            confirmButtonText:'Oui, enregistrer',
            cancelButtonColor:'#d33',
            confirmButtonColor:'#556ee6',
            width:500,
            allowOutsideClick:false
        })
        .then(function(result){
            if(result.value){
                $.ajax({
                    url:$(form).attr('action'),
                    method:$(form).attr('method'),
                    data:new FormData(form),
                    processData:false,
                    dataType:'json',
                    contentType:false,
                    beforeSend:function(){
                        $(form).find('span.error-text').text('');
                    },
                    success:function(data){
                        if(data.code == 0){
                            $.each(data.error, function(prefix, val){
                                $(form).find('span.'+prefix+'_error').text(val[0]);
                            });
                        }else if(data.code == 1){
                            // toastr.error(data.msg); // message d'erreur
                            flashAlert("Réponse","error",data.msg);
                        }else{
                            // Actualisation de la liste
                            // activerPiecesJointes();
                            btn_scanner(); // appel a la fonction
                            // Vider les champs
                            // document.getElementById("C_REF_PCE").value="";
                            // document.getElementById("L_OBS_PCE").value="";
                            // document.getElementById("D_DT_PCE").value="";
                            // document.getElementById("file").value="";
                            // Message succes
                            // toastr.success(data.msg);

                            flashAlert("Réponse","success",data.msg);
                            loadData(data.codeparent);
                            btn_importer();
                            // console.log(loadData(data.codeparent));
                            // setTimeout(() => {
                            //     location.reload();
                            // }, 2000);
                        }
                    }
                });
                return false;
            }
        });
    });

    //DEBUT SCANNAGE
    $('#form1').on('submit', function(e){
        e.preventDefault();
        if (scanner.submitFormWithImages('form1', imagesScanned, function (xhr) {
            if (xhr.readyState == 4) { // 4: request finished and response is ready
                if (xhr.responseText == "1") {

                    document.getElementById('images').innerHTML = ''; // clear images
                    imagesScanned = [];
                    flashAlert("Réponse","success",'Enregistement de la pièce scannée effectué avec succès');
                    btnScanner();

                } else {
                    // flashAlert("Réponse","error",'Enregistement de la pièce scannée effectué avec succès');
                    console.log(xhr.responseText);

                }
            }
            })) {
            document.getElementById('server_response').innerHTML = "Soumission, veuillez rester prêt ...";
            } else {
            document.getElementById('server_response').innerHTML = "Soumission du formulaire annulée. Veuillez d'abord scanner..";
            // console.log('Veuillez d\'abord scanner le document ...');
            return ;
        }

    });
    //FIN SCANNAGE



});
    //FIN GESTION DES PIECES JOINTES
















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

    //TRAITEMENT DES TEMOINS
    $("#nombre_temoin").on("change", function() {
        var nombre = parseInt($(this).val());

        switch (nombre) {
            case 0:
                hideTemoin();
                break;
            case 1:
                $("#temoin1").show();
                $("#temoin2").hide();
                $("#temoin3").hide();
                break;
            case 2:
                $("#temoin1").show();
                $("#temoin2").show();
                $("#temoin3").hide();
                break;
            case 3:
                $("#temoin1").show();
                $("#temoin2").show();
                $("#temoin3").show();
                break;

            default:
                hideTemoin();
                break;
        }
    });
    //info temoin 1
    $("#code_localite_temoin1").change(function (e) {
        e.preventDefault();
        var code_localite_temoin1 = $(this).val();
        var lieunaistemoin1 = $("#code_localite_temoin1 option:selected").text();

        if(lieunaistemoin1 != null || lieunaistemoin1 != ''){
            $("#lieu_naissance_temoin1").val(lieunaistemoin1);
        }
    });
    $('#domicile_pays_temoin1').on('change', function () {
        var pays = $('#domicile_pays_temoin1').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_temoin1').removeClass('d-none');
            $('div.autredomicile_ville_temoin1').addClass('d-none');

            $('#domicile_ville_temoin1').prop('disabled', false);
            $('#domicile_arrondissement1').prop('disabled', false);
            $('#domicile_quartier_temoin1').prop('disabled', false);

        }else{
            $("div.domicile_ville_temoin1").addClass("d-none");
            $("div.domicile_arrondissement1").addClass("d-none");
            $("div.domicile_quartier_temoin1").addClass('d-none');
            $('#domicile_ville_temoin1').prop('disabled', true);
            $('#domicile_arrondissement1').prop('disabled', true);
            $('#domicile_quartier_temoin1').prop('disabled', true);

            $('div.autredomicile_ville_temoin1').removeClass('d-none');
            $('#autredomicile_ville_temoin1').prop('disabled',false);
        }
    });

    $("#domicile_ville_temoin1").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_temoin1").removeClass("d-none");
            $('#domicile_arrondissement_temoin1').prop('disabled',false);

            var domicilevilletemoin_1 = $("#domicile_ville_temoin1 option:selected").text();
            var ville = '<option>'+domicilevilletemoin_1+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_temoin1');
        }
    });

    $("#domicile_arrondissement_temoin1").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_temoin1").removeClass('d-none');
            $('#domicile_quartier_temoin1').prop('disabled',false);

            var domicilearrondetemoin_1 = $("#domicile_arrondissement_temoin1 option:selected").text();
            getQuartierVillage(localiteParent,'domicile_quartier_temoin1');

        }
    });

    $("#domicile_quartier_temoin1").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_temoin1 option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_temoin1").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });
    //info temoin 2
    $("#code_localite_temoin2").change(function (e) {
        e.preventDefault();
        var code_localite_temoin2 = $(this).val();
        var lieunaistemoin1 = $("#code_localite_temoin2 option:selected").text();

        if(lieunaistemoin1 != null || lieunaistemoin1 != ''){
            $("#lieu_naissance_temoin2").val(lieunaistemoin1);
        }
    });

    $('#domicile_pays_temoin2').on('change', function () {
        var pays = $('#domicile_pays_temoin2').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_temoin2').removeClass('d-none');
            $('div.autredomicile_ville_temoin2').addClass('d-none');

            $('#domicile_ville_temoin2').prop('disabled', false);
            $('#domicile_arrondissement_temoin2').prop('disabled', false);
            $('#domicile_quartier_temoin2').prop('disabled', false);

        }else{
            $("div.domicile_ville_temoin2").addClass("d-none");
            $("div.domicile_arrondissement_temoin2").addClass("d-none");
            $("div.domicile_quartier_temoin2").addClass('d-none');
            $('#domicile_ville_temoin2').prop('disabled', true);
            $('#domicile_arrondissement_temoin2').prop('disabled', true);
            $('#domicile_quartier_temoin2').prop('disabled', true);

            $('div.autredomicile_ville_temoin2').removeClass('d-none');
            $('#autredomicile_ville_temoin2').prop('disabled',false);
        }
    });

    $("#domicile_ville_temoin2").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_temoin2").removeClass("d-none");
            $('#domicile_arrondissement_temoin2').prop('disabled',false);

            var domicilevilletemoin_2 = $("#domicile_ville_temoin2 option:selected").text();
            var ville = '<option>'+domicilevilletemoin_2+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_temoin2');
        }
    });

    $("#domicile_arrondissement_temoin2").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_temoin2").removeClass('d-none');
            $('#domicile_quartier_temoin2').prop('disabled',false);

            var domicilearrondetemoin_2 = $("#domicile_arrondissement_temoin2 option:selected").text();
            getQuartierVillage(localiteParent,'domicile_quartier_temoin2');

        }
    });

    $("#domicile_quartier_temoin2").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_temoin2 option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_temoin2").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });
    //info temoin 3
    $("#code_localite_temoin3").change(function (e) {
        e.preventDefault();
        var code_localite1 = $(this).val();
        var lieunaistemoin1 = $("#code_localite_temoin3 option:selected").text();

        if(lieunaistemoin1 != null || lieunaistemoin1 != ''){
            $("#lieu_naissance_temoin3").val(lieunaistemoin1);
        }
    });
    $('#domicile_pays_temoin3').on('change', function () {
        var pays = $('#domicile_pays_temoin3').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_temoin3').removeClass('d-none');
            $('div.autredomicile_ville_temoin3').addClass('d-none');

            $('#domicile_ville_temoin3').prop('disabled', false);
            $('#domicile_arrondissement_temoin3').prop('disabled', false);
            $('#domicile_quartier_temoin3').prop('disabled', false);

        }else{
            $("div.domicile_ville_temoin3").addClass("d-none");
            $("div.domicile_arrondissement_temoin3").addClass("d-none");
            $("div.domicile_quartier_temoin3").addClass('d-none');
            $('#domicile_ville_temoin3').prop('disabled', true);
            $('#domicile_arrondissement_temoin3').prop('disabled', true);
            $('#domicile_quartier_temoin3').prop('disabled', true);

            $('div.autredomicile_ville_temoin3').removeClass('d-none');
            $('#autredomicile_ville_temoin3').prop('disabled',false);
        }
    });

    $("#domicile_ville_temoin3").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_temoin3").removeClass("d-none");
            $('#domicile_arrondissement_temoin3').prop('disabled',false);

            var domicilevilletemoin_1 = $("#domicile_ville_temoin1 option:selected").text();
            var ville = '<option>'+domicilevilletemoin_1+'</option>';
            getArrComUrbaine(localiteParent,'domicile_arrondissement_temoin3');
        }
    });

    $("#domicile_arrondissement_temoin3").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_temoin3").removeClass('d-none');
            $('#domicile_quartier_temoin3').prop('disabled',false);

            var domicilearrondetemoin_1 = $("#domicile_arrondissement_temoin3 option:selected").text();
            getQuartierVillage(localiteParent,'domicile_quartier_temoin3');

        }
    });

    $("#domicile_quartier_temoin3").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_temoin3 option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_temoin3").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });
    //FIN TRAITEMENT DES TEMOINS
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

    $("#code_localite_adoptant").change(function (e) {
        e.preventDefault();
        var codeLocaliteDeclarant = $(this).val();
        var lieunaisadoptant = $("#code_localite_adoptant option:selected").text();

        if(codeLocaliteDeclarant != 'LOC_4247'){
            $("#lieu_naissance_adoptant").val(lieunaisadoptant);
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
            getArrComUrbaine(localiteParent,'domicile_arrondissement_mere');
        }
    });

    $("#domicile_arrondissement_mere").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_mere").removeClass('d-none');

            var domicilearrondemere = $("#domicile_arrondissement_mere option:selected").text();
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

    //adresse adoptant
    $('#domicile_pays_adoptant').on('change', function () {
        var pays = $('#domicile_pays_adoptant').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_adoptant').removeClass('d-none');
            $('div.autredomicile_ville_adoptant').addClass('d-none');

            $('#domicile_ville_adoptant').prop('disabled', false);
            $('#domicile_arrondissement_adoptant').prop('disabled', false);
            $('#domicile_quartier_adoptant').prop('disabled', false);

        }else{
            $("div.domicile_ville_adoptant").addClass("d-none");
            $("div.domicile_arrondissement_adoptant").addClass("d-none");
            $("div.domicile_quartier_adoptant").addClass('d-none');
            $('#domicile_ville_adoptant').prop('disabled', true);
            $('#domicile_arrondissement_adoptant').prop('disabled', true);
            $('#domicile_quartier_adoptant').prop('disabled', true);
            $('div.autredomicile_ville_adoptant').removeClass('d-none');
            $('#autredomicile_ville_adoptant').prop('disabled',false);
        }
    });

    $("#domicile_ville_adoptant").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_arrondissement_adoptant").removeClass("d-none");

            var domicilevilleadoptant = $("#domicile_ville_adoptant option:selected").text();
            getArrComUrbaine(localiteParent,'domicile_arrondissement_adoptant');
        }
    });

    $("#domicile_arrondissement_adoptant").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_adoptant").removeClass('d-none');
            getQuartierVillage(localiteParent,'domicile_quartier_adoptant');
        }
    });

    $("#domicile_quartier_adoptant").on('change', function(){
        var q = $(this).val();
        if(q != "" || q !=null){
            var quartier = '<option>'+$("#domicile_quartier_adoptant option:selected").text()+'</option>';
        }
    });

    $("#domicile_typevoie_adoptant").on('change', function(){
        var typevoie = $(this).val();
        if(typevoie != "" || typevoie !=null){
            var tvoie = '<option>'+typevoie+'</option>';
        }
    });
    //fin adresse adoptant
    // adresse enfant
        $('#domicile_pays_enfant').on('change', function () {
        var pays = $('#domicile_pays_enfant').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_enfant').removeClass('d-none');
            $('div.autredomicile_ville_enfant').addClass('d-none');

            $('#domicile_ville_enfant').prop('disabled', false);
            $('#domicile_arrondissement_enfant').prop('disabled', false);
            $('#domicile_quartier_enfant').prop('disabled', false);

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

            var domicilevilleenfant = $("#domicile_ville_enfant option:selected").text();
            getArrComUrbaine(localiteParent,'domicile_arrondissement_enfant');
        }
    });

    $("#domicile_arrondissement_enfant").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" || localiteParent !=null){
            $("div.domicile_quartier_enfant").removeClass('d-none');

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
    //fin adresse enfant

    //traitement adoptant soit pere ou mere ou autre
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
    //Fin traitement adoptant soit pere ou mere ou autre
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

        $('#email_mere').val("");
        document.getElementById('email_mere').readOnly = false;

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

            if((($('#pereadoptant').is(':checked')))&&($('#statut_personne_pere').val()==="VIVANT"))
            {

                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_adoptant').style.visibility = 'hidden';

                $('#nom_adoptant').val($('#nom_pere').val());
                document.getElementById('nom_adoptant').readOnly = true;

                $('#prenom_adoptant').val($('#prenom_pere').val());
                document.getElementById('prenom_adoptant').readOnly = true;

                $('#email_adoptant').val($('#email_pere').val());
                document.getElementById('email_adoptant').readOnly = true;

                $('#date_naissance_adoptant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_adoptant').readOnly = true;

                $('#lieu_naissance_adoptant').val($('#lieu_naissance_pere').val());
                document.getElementById('lieu_naissance_adoptant').readOnly = true;

                $('#telephone_adoptant').val($('#telephone_pere').val());
                document.getElementById('telephone_adoptant').readOnly = true;

                $('#domicile_numero_adoptant').val($('#domicile_numero_pere').val());
                document.getElementById('domicile_numero_adoptant').readOnly = true;

                $('#domicile_nomvoie_adoptant').val($('#domicile_nomvoie_pere').val());
                document.getElementById('domicile_nomvoie_adoptant').readOnly = true;

                $('#domicile_typevoie_adoptant').val($('#domicile_typevoie_pere').val());
                $('#domicile_typevoie_adoptant').attr('readOnly','readOnly');

                $('#domicile_pays_adoptant').val($('#domicile_pays_pere option:selected').text());
                document.getElementById('domicile_pays_adoptant').readOnly = true;

                $('#numero_document_adoptant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_adoptant').readOnly = true;

                $('#statut_personne_adoptant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_adoptant').val($('#type_date_naissance_pere').val());

                var sexe_adoptant = $("#sexe_adoptant");
                    sexe_adoptant.val("M");
                $("#sexe_adoptant option:selected").text();
                document.getElementById('sexe_adoptant').disabled = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0001");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;
                var profession_adoptant = $("#profession_adoptant");
                var profession_pere = $("#profession_pere");
                profession_adoptant.val(profession_pere.val());
                document.getElementById('profession_adoptant').disabled = true;

                var code_type_document_adoptant = $("#code_type_document_adoptant");
                var code_type_document_pere = $("#code_type_document_pere");
                code_type_document_adoptant.val(code_type_document_pere.val());
                document.getElementById('code_type_document_adoptant').disabled = true;

                var code_nationalite_adoptant = $("#code_nationalite_adoptant");
                var code_nationalite_pere = $("#code_nationalite_pere");
                code_nationalite_adoptant.val(code_nationalite_pere.val());
                document.getElementById('code_nationalite_adoptant').disabled = true;

                var code_localite_adoptant = $("#code_localite_adoptant");
                var code_localite_pere = $("#code_localite_pere");
                code_localite_adoptant.val(code_localite_pere.val());
                code_localite_adoptant.prop("disabled",true);

                var niveau_instruction_adoptant = $("#niveau_instruction_adoptant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_adoptant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_adoptant').disabled = true;

                var code_pays_adoptant = $("#code_pays_adoptant");
                var code_pays_pere = $("#code_pays_pere");
                code_pays_adoptant.val(code_pays_pere.val());
                document.getElementById('code_pays_adoptant').disabled = true;

                var domicile_ville_adoptant = $("#domicile_ville_adoptant");
                var domicile_ville_pere = $("#domicile_ville_pere");
                domicile_ville_adoptant.val(domicile_ville_pere.val());

                var domicile_arrondissement_adoptant = $("#domicile_arrondissement_adoptant");
                var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                domicile_arrondissement_adoptant.val(domicile_arrondissement_pere.val());

                var domicile_quartier_adoptant = $("#domicile_quartier_adoptant");
                var domicile_quartier_pere = $("#domicile_quartier_pere");
                domicile_quartier_adoptant.val(domicile_quartier_pere.val());

                $("#domicile_pays_adoptant").prop('disabled', true);
                $("#domicile_ville_adoptant").prop('disabled', true);
                $("#domicile_arrondissement_adoptant").prop('disabled', true);
                $("#domicile_quartier_adoptant").prop('disabled', true);

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

        $('input:radio[name="autreadoptant"]').change(function()
        {
            ///si on coche père
            if (($(this).val() === 'pere')&&($('#statut_personne_pere').val()==="VIVANT"))
            {
                //Traitement input
                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_adoptant').style.visibility = 'hidden';

                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_adoptant').style.visibility = 'hidden';

                $('#nom_adoptant').val($('#nom_pere').val());
                document.getElementById('nom_adoptant').readOnly = true;

                $('#prenom_adoptant').val($('#prenom_pere').val());
                document.getElementById('prenom_adoptant').readOnly = true;

                $('#email_adoptant').val($('#email_pere').val());
                document.getElementById('email_adoptant').readOnly = true;

                $('#date_naissance_adoptant').val($('#date_naissance_pere').val());
                document.getElementById('date_naissance_adoptant').readOnly = true;

                $('#lieu_naissance_adoptant').val($('#lieu_naissance_pere').val());
                document.getElementById('lieu_naissance_adoptant').readOnly = true;

                $('#telephone_adoptant').val($('#telephone_pere').val());
                document.getElementById('telephone_adoptant').readOnly = true;

                $('#numero_document_adoptant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_adoptant').readOnly = true;

                $('#statut_personne_adoptant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_adoptant').val($('#type_date_naissance_pere').val());

                if($('#type_date_naissance_pere').val()==="EXACTE")
                {
                    document.getElementById('type_date_naissance_adoptant').checked="";
                }else{
                    document.getElementById('type_date_naissance_adoptant').checked="ESTIME";
                }

                document.getElementById('type_date_naissance_adoptant').disabled = true;
                document.getElementById('statut_personne_adoptant').disabled = true;


                //traitement select
                var sexe_adoptant = $("#sexe_adoptant");
                    sexe_adoptant.val("M");
                $("#sexe_adoptant option:selected").text();
                document.getElementById('sexe_adoptant').disabled = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0001");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;


                var profession_adoptant = $("#profession_adoptant");
                var profession_pere = $("#profession_pere");
                profession_adoptant.val(profession_pere.val());
                document.getElementById('profession_adoptant').disabled = true;


                var code_type_document_adoptant = $("#code_type_document_adoptant");
                var code_type_document_pere = $("#code_type_document_pere");
                code_type_document_adoptant.val(code_type_document_pere.val());
                document.getElementById('code_type_document_adoptant').disabled = true;

                var code_nationalite_adoptant = $("#code_nationalite_adoptant");
                var code_nationalite_pere = $("#code_nationalite_pere");
                code_nationalite_adoptant.val(code_nationalite_pere.val());
                document.getElementById('code_nationalite_adoptant').disabled = true;

                var niveau_instruction_adoptant = $("#niveau_instruction_adoptant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_adoptant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_adoptant').disabled = true;

                var code_pays_adoptant = $("#code_pays_adoptant");
                var code_pays_pere = $("#code_pays_pere");
                code_pays_adoptant.val(code_pays_pere.val());
                document.getElementById('code_pays_adoptant').disabled = true;


                $('#domicile_numero_adoptant').val($('#domicile_numero_pere').val());
                document.getElementById('domicile_numero_adoptant').readOnly = true;

                var domicile_ville_adoptant = $("#domicile_ville_adoptant");
                var domicile_ville_pere = $("#domicile_ville_pere");
                domicile_ville_adoptant.val(domicile_ville_pere.val());

                var domicile_arrondissement_adoptant = $("#domicile_arrondissement_adoptant");
                var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                domicile_arrondissement_adoptant.val(domicile_arrondissement_pere.val());

                var domicile_quartier_adoptant = $("#domicile_quartier_adoptant");
                var domicile_quartier_pere = $("#domicile_quartier_pere");
                domicile_quartier_adoptant.val(domicile_quartier_pere.val());


                //  $('#domicile_typevoie_adoptant').attr('readOnly','readOnly');

                $("#domicile_pays_adoptant").prop('disabled', true);
                $("#domicile_ville_adoptant").prop('disabled', true);
                $("#domicile_arrondissement_adoptant").prop('disabled', true);
                $("#domicile_quartier_adoptant").prop('disabled', true);
                $("#domicile_typevoie_adoptant").prop('disabled', true);
            }

            if (($(this).val() === 'mere')&&($('#statut_personne_mere').val()==="VIVANT"))
            {
                document.getElementById('hide_mere').style.visibility = 'visible';
                document.getElementById('search_adoptant').style.visibility = 'hidden';

                $('#nom_adoptant').val($('#nom_mere').val());
                document.getElementById('nom_adoptant').readOnly = true;

                $('#prenom_adoptant').val($('#prenom_mere').val());
                document.getElementById('prenom_adoptant').readOnly = true;

                $('#email_adoptant').val($('#email_mere').val());
                document.getElementById('email_adoptant').readOnly = true;

                $('#date_naissance_adoptant').val($('#date_naissance_mere').val());
                document.getElementById('date_naissance_adoptant').readOnly = true;

                $('#lieu_naissance_adoptant').val($('#lieu_naissance_mere').val());
                document.getElementById('lieu_naissance_adoptant').readOnly = true;

                $('#telephone_adoptant').val($('#telephone_mere').val());
                document.getElementById('telephone_adoptant').readOnly = true;

                $('#domicile_numero_adoptant').val($('#domicile_numero_mere').val());
                document.getElementById('domicile_numero_adoptant').readOnly = true;

                $('#domicile_nomvoie_adoptant').val($('#domicile_nomvoie_mere').val());
                document.getElementById('domicile_nomvoie_adoptant').readOnly = true;

                $('#domicile_typevoie_adoptant').val($('#domicile_typevoie_mere').val());
                $('#domicile_typevoie_adoptant').attr('readOnly','readOnly');


                $('#numero_document_adoptant').val($('#numero_document_mere').val());
                document.getElementById('numero_document_adoptant').readOnly = true;

                $('#statut_personne_adoptant').val($('#statut_personne_mere').val());

                $('#type_date_naissance_adoptant').val($('#type_date_naissance_mere').val());

                var sexe_adoptant = $("#sexe_adoptant");
                    sexe_adoptant.val("F");
                $("#sexe_adoptant option:selected").text();
                document.getElementById('sexe_adoptant').disabled = true;

                var filiation = $("#filiation");
                    filiation.val("FIL_0001");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;
                var profession_adoptant = $("#profession_adoptant");
                var profession_mere = $("#profession_mere");
                profession_adoptant.val(profession_mere.val());
                document.getElementById('profession_adoptant').disabled = true;

                var code_type_document_adoptant = $("#code_type_document_adoptant");
                var code_type_document_mere = $("#code_type_document_mere");
                code_type_document_adoptant.val(code_type_document_mere.val());
                document.getElementById('code_type_document_adoptant').disabled = true;

                var code_nationalite_adoptant = $("#code_nationalite_adoptant");
                var code_nationalite_mere = $("#code_nationalite_mere");
                code_nationalite_adoptant.val(code_nationalite_mere.val());
                document.getElementById('code_nationalite_adoptant').disabled = true;

                var code_localite_adoptant = $("#code_localite_adoptant");
                var code_localite_mere = $("#code_localite_mere");
                code_localite_adoptant.val(code_localite_mere.val());
                code_localite_adoptant.prop("disabled",true);

                var niveau_instruction_adoptant = $("#niveau_instruction_adoptant");
                var niveau_instruction_mere = $("#niveau_instruction_mere");
                niveau_instruction_adoptant.val(niveau_instruction_mere.val());
                document.getElementById('niveau_instruction_adoptant').disabled = true;

                var code_pays_adoptant = $("#code_pays_adoptant");
                var code_pays_mere = $("#code_pays_mere");
                code_pays_adoptant.val(code_pays_mere.val());
                document.getElementById('code_pays_adoptant').disabled = true;

                $('#domicile_pays_adoptant').val($('#domicile_pays_mere option:selected').text());
                document.getElementById('domicile_pays_adoptant').readOnly = true;

                var domicile_ville_adoptant = $("#domicile_ville_adoptant");
                var domicile_ville_mere = $("#domicile_ville_mere");
                domicile_ville_adoptant.val(domicile_ville_mere.val());

                $('#domicile_arrondissement_adoptant').attr('readOnly','readOnly');
                var domicile_arrondissement_adoptant = $("#domicile_arrondissement_adoptant");
                var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
                domicile_arrondissement_adoptant.val(domicile_arrondissement_mere.val());

                $('#domicile_quartier_adoptant').attr('readOnly','readOnly');
                var domicile_quartier_adoptant = $("#domicile_quartier_adoptant");
                var domicile_quartier_mere = $("#domicile_quartier_mere");
                domicile_quartier_adoptant.val(domicile_quartier_mere.val());

            }

            if (($(this).val() === 'autre'))
            {

                $("#domicile_pays_adoptant").prop("disabled", false);
                $("#domicile_ville_adoptant").prop("disabled", false);
                $("#domicile_arrondissement_adoptant").prop("disabled", false);
                $("#domicile_quartier_adoptant").prop("disabled", false);
                $("#domicile_typevoie_adoptant").prop("disabled", false);

                $('#nom_adoptant').val("");
                document.getElementById('nom_adoptant').readOnly = false;

                document.getElementById('domicile_numero_adoptant').readOnly = false;
                document.getElementById('domicile_nomvoie_adoptant').readOnly = false;
                $('#prenom_adoptant').val("");
                document.getElementById('prenom_adoptant').readOnly = false;
                $('#email_adoptant').val("");
                document.getElementById('email_adoptant').readOnly = false;

                $('#date_naissance_adoptant').val("");
                document.getElementById('date_naissance_adoptant').readOnly = false;

                $('#lieu_naissance_adoptant').val("");
                document.getElementById('lieu_naissance_adoptant').readOnly = false;

                $('#code_pays_adoptant').val("");
                document.getElementById('code_pays_adoptant').disabled = false;

                $('#telephone_adoptant').val("");
                document.getElementById('telephone_adoptant').readOnly = false;

                var profession_adoptant = $("#profession_adoptant");
                    profession_adoptant.val("");
                    $("#profession_adoptant option:selected").text();
                    document.getElementById('profession_adoptant').disabled = false;

                var code_localite_adoptant = $("#code_localite_adoptant");
                    code_localite_adoptant.val("");
                    $("#code_localite_adoptant option:selected").text();
                    document.getElementById('code_localite_adoptant').disabled = false;


                var code_nationalite_adoptant = $("#code_nationalite_adoptant");
                    code_nationalite_adoptant.val("");
                    $("#code_nationalite_adoptant option:selected").text();
                    document.getElementById('code_nationalite_adoptant').disabled = false;

                var code_type_document_adoptant = $("#code_type_document_adoptant");
                code_type_document_adoptant.val("");
                $("#code_type_document_adoptant option:selected").text();
                document.getElementById('code_type_document_adoptant').disabled = false;

                var numero_document_adoptant = $("#numero_document_adoptant");
                numero_document_adoptant.val("");
                document.getElementById('numero_document_adoptant').readOnly = false;

                var niveau_instruction_adoptant = $("#niveau_instruction_adoptant");
                niveau_instruction_adoptant.val("");
                $("#niveau_instruction_adoptant option:selected").text();
                document.getElementById('niveau_instruction_adoptant').disabled = false;

                var sexe_adoptant = $("#sexe_adoptant");
                sexe_adoptant.val("");
                $("#sexe_adoptant option:selected").text();
                document.getElementById('sexe_adoptant').disabled = false;

                var filiation = $("#filiation");
                    filiation.val("");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = false;

                $('#statut_personne_adoptant').val("VIVANT");


                var type_date_naissance_adoptant = $("#type_date_naissance_adoptant");
                type_date_naissance_adoptant.val("EXACTE");
                $("#type_date_naissance_adoptant option:selected").text();
                document.getElementById('type_date_naissance_adoptant').disabled = false;

                document.getElementById('type_date_naissance_adoptant').checked="";
                document.getElementById('type_date_naissance_adoptant').disabled = false;
                document.getElementById('statut_personne_adoptant').disabled = false;


                $('#domicile_pays_adoptant').val("");

                $("#domicile_ville_adoptant").val("");

                $("#domicile_arrondissement_adoptant").val("");

                $("#domicile_quartier_adoptant").val("");

                $('#domicile_numero_adoptant').val("");
                $('#domicile_nomvoie_adoptant').val("");
                $('#domicile_typevoie_adoptant').val("");
                // $('#domicile_typevoie_adoptant').attr('readOnly',false);


            }

        });


});

    var temoinsEnfant = [];
    //insertion témoin dans un tableau
    function insertTemoin(code_temoin, nom,prenom,sexe,date_naissance,
    code_nationalite,lieu_naissance,code_type_document,numero_document,code_localite,
    domicile_pays,domicile_ville,
    domicile_arrondissement,domicile_quartier,
    domicile_typevoie,domicile_numero,domicile_nomvoie)
    {
        temoinsEnfant.push({
            code_temoin: code_temoin,
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
         var code_declaration_naissance = $("#code_declaration_naissance");
         var code_pere = $("#code_pere");
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

           //Informations témoins
        var nombre_temoin = $("#nombre_temoin").val();
        var code_temoin,nom_temoin,prenom_temoin,sexe_temoin,date_naissance_temoin,code_nationalite_temoin,
        lieu_naissance_temoin,
        code_type_document_temoin,numero_document_temoin,code_localite_temoin,domicile_pays_temoin,
        autredomicile_ville_temoin,domicile_arrondissement_temoin,
        domicile_quartier_temoin,domicile_typevoie_temoin,domicile_numero_temoin,
        domicile_nomvoie_temoin;

        temoinsEnfant = [];
        for (var index = 1;  nombre_temoin >= index; index++) {

            code_temoin = code_temoin+''+index;
            nom_temoin = nom_temoin+''+index;
            prenom_temoin = prenom_temoin+''+index;
            sexe_temoin = sexe_temoin+''+index;
            date_naissance_temoin = date_naissance_temoin+''+index;
            code_nationalite_temoin = code_nationalite_temoin+''+index;
            lieu_naissance_temoin = lieu_naissance_temoin+''+index;
            code_type_document_temoin = code_type_document_temoin+''+index;
            numero_document_temoin =  numero_document_temoin+''+index,
            code_localite_temoin = code_localite_temoin+''+index;
            domicile_pays_temoin = domicile_pays_temoin+''+index;
            domicile_ville_temoin = autredomicile_ville_temoin+''+index;
            domicile_arrondissement_temoin = domicile_arrondissement_temoin+''+index;
            domicile_quartier_temoin = domicile_quartier_temoin+''+index;
            domicile_typevoie_temoin = domicile_typevoie_temoin+''+index;
            domicile_numero_temoin =  domicile_numero_temoin+''+index;
            domicile_nomvoie_temoin = domicile_nomvoie_temoin+''+index;

            nom_temoin = $("#nom_temoin"+index+"").val();
            prenom_temoin = $("#prenom_temoin"+index+"").val();
            sexe_temoin = $("#sexe_temoin"+index+"").val();
            date_naissance_temoin = $("#datenais_temoin"+index+"").val();
            code_nationalite_temoin = $("#code_nationalite_temoin"+index+"").val();
            lieu_naissance_temoin = $("#lieu_naissance_temoin"+index+"").val();
            code_type_document_temoin = $("#code_type_document_temoin"+index+"").val();
            numero_document_temoin =  $("#numero_document_temoin"+index+"").val();
            code_localite_temoin =  $("#code_localite_temoin"+index+"").val();
            domicile_pays_temoin =  $("#domicile_pays_temoin"+index+"").val();
            domicile_ville_temoin = $("#autredomicile_ville_temoin"+index+"").val();
            domicile_arrondissement_temoin =  $("#domicile_arrondissement_temoin"+index+"").val();
            domicile_quartier_temoin =  $("#domicile_quartier_temoin"+index+"").val();
            domicile_typevoie_temoin =  $("#domicile_typevoie_temoin"+index+"").val();
            domicile_numero_temoin =   $("#domicile_numero_temoin"+index+"").val();
            domicile_nomvoie_temoin =  $("#domicile_nomvoie_temoin"+index+"").val();

            insertTemoin(nom_temoin,prenom_temoin,sexe_temoin,date_naissance_temoin,code_nationalite_temoin,lieu_naissance_temoin,code_type_document_temoin,numero_document_temoin,code_localite_temoin,domicile_pays_temoin,autredomicile_ville_temoin,domicile_arrondissement_temoin,domicile_quartier_temoin,domicile_typevoie_temoin,domicile_numero_temoin,domicile_nomvoie_temoin);

        }

         //information mere
         var code_mere = $("#code_mere");
         var nom_mere = $("#nom_mere");
         var prenom_mere = $("#prenom_mere");
         var date_naissance_mere = $("#date_naissance_mere");
         var lieu_naissance_mere = $("#lieu_naissance_mere");
         var code_localite_mere = $("#code_localite_mere");
         var code_pays_mere = $("#code_pays_mere");
         var telephone_mere = $("#telephone_mere");
         var telephone_parent = $("#telephone_parent");

         var email_mere = $("#email_mere");
         var profession_mere = $("#profession_mere");
         var code_nationalite_mere = $("#code_nationalite_mere");
         var niveau_instruction_mere = $("#niveau_instruction_mere");
         var code_type_document_mere = $("#code_type_document_mere");
         var numero_document_mere = $("#numero_document_mere");
         formation_sanitaire_naissance = $("#formation_sanitaire_naissance");
         //déclarant
         var code_adoptant = $("#code_adoptant");
         var nom_adoptant = $("#nom_adoptant");
         var prenom_adoptant = $("#prenom_adoptant");
         var date_naissance_adoptant = $("#date_naissance_adoptant");
         var lieu_naissance_adoptant = $("#lieu_naissance_adoptant");
         var code_localite_adoptant = $("#code_localite_adoptant");
         var code_pays_adoptant = $("#code_pays_adoptant");
         var telephone_adoptant = $("#telephone_adoptant");
         var niveau_instruction_adoptant = $("#niveau_instruction_adoptant");

         var telephone_adoptant = $("#telephone_adoptant");
         var profession_adoptant = $("#profession_adoptant");
         var code_nationalite_adoptant = $("#code_nationalite_adoptant");
         var filiation = $("#filiation");
         var sexe_adoptant = $("#sexe_adoptant");
         var email_adoptant = $("#email_adoptant");
         var code_type_document_adoptant = $("#code_type_document_adoptant");
         var numero_document_adoptant = $("#numero_document_adoptant");

         // enfant
         var code_enfant = $("#code_enfant");
         var nom_enfant = $("#nom_enfant");
         var prenom_enfant = $("#prenom_enfant");
         var date_naissance_enfant = $("#date_naissance_enfant");
         var lieu_naissance_enfant = $("#lieu_naissance_enfant");
         var code_localite_enfant = $("#code_localite_enfant");
         var code_situation_matrimoniale = $("#code_situation_matrimoniale");
         var lieu_survenance = $("#code_lieu_survenance");
         var profession_enfant = $("#profession_enfant");
         var statut_personne_enfant = $("#statut_personne_enfant");


         var nationalite_enfant = $("#nationalite_enfant");
         var code_nationalite_enfant = $("#code_nationalite_enfant");
         var niveau_instruction_enfant = $("#niveau_instruction_enfant");
         var code_type_document_enfant = $("#code_type_document_enfant");
         var numero_document_enfant = $("#numero_document_enfant");
         var sexe_enfant = $("#sexe_enfant");
         var heure_naissance_enfant = $("#heure_naissance_enfant");
         var nombre_enfants = $("#nombre_enfants");

         var domicile_pays_enfant = $("#domicile_pays_enfant");
         var domicile_ville_enfant = $("#domicile_ville_enfant");
         var domicile_arrondissement_enfant = $("#domicile_arrondissement_enfant");
         var domicile_quartier_enfant = $("#domicile_quartier_enfant");
         var domicile_typevoie_enfant = $("#domicile_typevoie_enfant");
         var domicile_numero_enfant = $("#domicile_numero_enfant");
         var domicile_nomvoie_enfant = $("#domicile_nomvoie_enfant");
         var code_pays_enfant = $("#code_pays_enfant");
         var telephone_enfant = $("#telephone_enfant");
         var email_enfant = $("#email_enfant");



         var type_date_naissance_mere = $("#type_date_naissance_mere");
         var statut_personne_mere = $("#statut_personne_mere");

         var type_date_naissance_pere = $("#type_date_naissance_pere");
         var statut_personne_pere = $("#statut_personne_pere");

         var type_date_naissance_adoptant = $("#type_date_naissance_adoptant");
         var statut_personne_adoptant = $("#statut_personne_adoptant");

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
         var domicile_nomvoie_mere = $("#domicile_nomvoie_adoptant");

         var pereadoptant = $("#pereadoptant").val();
         var mereadoptant = $("#mereadoptant").val();
         var autreadoptant = $("#autreadoptant").val();


        var domicile_pays_adoptant = $("#domicile_pays_adoptant");
        var domicile_ville_adoptant = $("#domicile_ville_adoptant");
        var domicile_arrondissement_adoptant = $("#domicile_arrondissement_adoptant");
        var domicile_quartier_adoptant = $("#domicile_quartier_adoptant");
        var domicile_typevoie_adoptant = $("#domicile_typevoie_adoptant");
        var domicile_numero_adoptant = $("#domicile_numero_adoptant");
        var domicile_nomvoie_adoptant = $("#domicile_nomvoie_adoptant");

        var date_heure_declaration = $("#date_heure_declaration");
        var type_declaration = $("#type_declaration");
        var formation_sanitaire_naissance = $("#formation_sanitaire_naissance");
        var type_adoption = "";


        if ($('input[name="adoption"]:checked').val() === 'adoption partielle') {
               type_adoption = $("#partielle");
        } else {
            type_adoption = $("#pleniere");
        }


        var niupp = $("#niupp");
        var code_jugement = $("#code_jugement");


        var data =
        {
            //Témoins
            code_declaration_naissance: code_declaration_naissance.val(),
            //uniquement pour le update de l'objet
            code_pere: code_pere.val(),
            code_mere: code_mere.val(),
            code_adoptant: code_adoptant.val(),
            code_enfant: code_enfant.val(),

            statut_personne_enfant:statut_personne_enfant.val(),
            code_profession_enfant: profession_enfant.val(),
            code_nationalite_enfant: code_nationalite_enfant.val(),
            niveau_instruction_enfant: niveau_instruction_enfant.val(),
            code_type_document_enfant: code_type_document_enfant.val(),
            numero_document_enfant: numero_document_enfant.val(),

            domicile_pays_enfant: domicile_pays_enfant.val(),
            domicile_ville_enfant: domicile_ville_enfant.val(),
            domicile_arrondissement_enfant: domicile_arrondissement_enfant.val(),
            domicile_quartier_enfant: domicile_quartier_enfant.val(),
            domicile_typevoie_enfant: domicile_typevoie_enfant.val(),
            domicile_numero_enfant: domicile_numero_enfant.val(),
            domicile_nomvoie_enfant: domicile_nomvoie_enfant.val(),
            code_pays_enfant: code_pays_enfant.val(),
            telephone_enfant: telephone_enfant.val(),
            email_enfant: email_enfant.val(),

            //fin uniquement pour le update de l'objet

            temoins: temoinsEnfant,
            nombre_temoin: nombre_temoin,
             // données du père
            nom_pere:nom_pere.val(),
            prenom_pere:prenom_pere.val(),
            date_naissance_pere:date_naissance_pere.val(),
            lieu_naissance_pere:lieu_naissance_pere.val(),
            code_localite_pere:code_localite_pere.val(),
            code_profession_pere:profession_pere.val(),
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
            code_profession_mere:profession_mere.val(),
            code_nationalite_mere:code_nationalite_mere.val(),
            niveau_instruction_mere:niveau_instruction_mere.val(),
            code_pays_pere :code_pays_pere.val(),
            telephone_pere :telephone_pere.val(),
            code_pays_mere :code_pays_mere.val(),
            telephone_mere :telephone_mere.val(),
            telephone_parent:telephone_parent.val(),
            code_type_document_mere:code_type_document_mere.val(),
            numero_document_mere:numero_document_mere.val(),
             // données du déclarant
            nom_adoptant:nom_adoptant.val(),
            prenom_adoptant:prenom_adoptant.val(),
            date_naissance_adoptant:date_naissance_adoptant.val(),
            lieu_naissance_adoptant:lieu_naissance_adoptant.val(),
            code_localite_adoptant:code_localite_adoptant.val(),
            code_profession_adoptant:profession_adoptant.val(),
            code_nationalite_adoptant:code_nationalite_adoptant.val(),
            niveau_instruction_adoptant:niveau_instruction_adoptant.val(),
            filiation:filiation.val(),
            code_pays_adoptant :code_pays_adoptant.val(),
            telephone_adoptant :telephone_adoptant.val(),
            sexe_adoptant:sexe_adoptant.val(),
            code_type_document_adoptant:code_type_document_adoptant.val(),
            numero_document_adoptant:numero_document_adoptant.val(),
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
            type_date_naissance_adoptant:type_date_naissance_adoptant.val(),
            statut_personne_adoptant:statut_personne_adoptant.val(),
            type_date_naissance_mere:type_date_naissance_mere.val(),
            statut_personne_mere:statut_personne_mere.val(),
            type_date_naissance_pere:type_date_naissance_pere.val(),
            statut_personne_pere:statut_personne_pere.val(),
            email_pere:email_pere.val(),
            email_mere:email_mere.val(),
            email_adoptant:email_adoptant.val(),
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
            domicile_pays_adoptant:domicile_pays_adoptant.val(),
            domicile_ville_adoptant:domicile_ville_adoptant.val(),
            domicile_arrondissement_adoptant:domicile_arrondissement_adoptant.val(),
            domicile_quartier_adoptant:domicile_quartier_adoptant.val(),
            domicile_typevoie_adoptant:domicile_typevoie_adoptant.val(),
            domicile_numero_adoptant:domicile_numero_adoptant.val(),
            domicile_nomvoie_adoptant:domicile_nomvoie_adoptant.val(),
            date_heure_declaration:date_heure_declaration.val(),
            type_declaration:type_declaration.val(),
            formation_sanitaire_naissance:formation_sanitaire_naissance.val(),
            niupp:niupp.val(),
            code_jugement:code_jugement.val(),
            type_adoption:type_adoption.val()
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
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><td style='font-weight:bold; padding:2px'>3)DECLARANT</td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_adoptant.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_adoptant.val()+"</span></td><td style='padding:2px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_adoptant" ).options[ document.getElementById( "sexe_adoptant" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_adoptant.val())+"</span></td><td style='padding:2px'>Lieu<br><span style='font-weight:bold;'>"+ lieu_naissance_adoptant.val()+"</span></td>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_adoptant.val()+" "+domicile_nomvoie_adoptant.val()+" "+$("#domicile_ville_adoptant option:selected").text()+" "+$("#domicile_arrondissement_adoptant option:selected").text()+" "+$("#domicile_quartier_adoptant option:selected").text()+"</span></td><td style='padding:2px'>Filiation<br><span style='font-weight:bold;'>"+document.getElementById( "filiation" ).options[ document.getElementById( "filiation" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Téléphone<br><span style='font-weight:bold;'>"+ telephone_adoptant.val()+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_adoptant" ).options[ document.getElementById( "profession_adoptant" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Nationalite<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_adoptant" ).options[ document.getElementById( "code_nationalite_adoptant" ).selectedIndex ].text+"</span></tr>"


            +"<tr><td style='padding:5px;' colspan=11><hr></td></tr></table></div>",
             type: "warning",
             showCancelButton: !0,
             confirmButtonText: "Modifier",
             cancelButtonText: "Annuler",
             reverseButtons: !0
         }).then((result)=>
          {

            if (result.value==true)
            {
                if($('input[name="adoption"]:checked').val() === 'adoption partielle') {
                    type_adoption = $("#partielle").val();
                }else{
                    type_adoption = $("#pleniere").val();
                }

                var typeDeclaration = $("#typeDeclaration").val();
                var url = "{{ route('certificatNonInscription.index') }}";
                var laroute = "";
                var typeMethode = "";
                var codeDeclarationNaissance =  $("#code_declaration_naissance").val();
                var routeupdate = "{{route('declarationNaissance.update', ':id')}}";
                var route_adoprion_pleniere = "{{route('declarationNaissance.store.adoption.pleniere')}}";

                routeupdate = routeupdate.replace(':id',codeDeclarationNaissance);

                if(type_adoption == "adoption pleniere")
                {
                    laroute = route_adoprion_pleniere;
                    typeMethode = "POST";
                }
                else{
                    laroute = routeupdate;
                    typeMethode = "PUT";
                }

                // alert(type_adoption);
                // return false;
                $.ajax({
                    url : laroute,
                    type: typeMethode,
                    data: data,
                    success: function(response){
                        if(response.code == "200")
                        {
                            flashAlert("Opération réussie","success",response.message);
                            setTimeout(() => {
                                window.open(url);
                            }, 2000);

                        }else{
                            swal.fire("Opération échouée!", response.message, "error");
                        }
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
             finish: "Modifier"
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
              numero_document_adoptant:
              {
                  required: true,

               },
               code_type_document_adoptant:
               {
                   required: true,

                },
              filiation:
              {
                  required: true,

               },
              nom_adoptant:
              {
                  required: true,

               },

              date_naissance_adoptant:
              {
                  required: true,

               },

              lieu_naissance_adoptant:
              {
                  required: true,

               },
              code_nationalite_adoptant:
              {
                  required: true,

               },
              telephone_adoptant:
              {
                  required: true,

               },
              statut_personne_adoptant:
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

            numero_document_adoptant:
            {
                required: "Veuillez saisir un numero de pièce",

            },
            code_type_document_adoptant:
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
               nom_adoptant:
               {
                   required: "Veuillez saisir un nom",

                },

               date_naissance_adoptant:
               {
                   required: "Veuillez selectionner une date",

                },

               lieu_naissance_adoptant:
               {
                   required: "Veuillez saisir un lieu",

                },
               code_nationalite_adoptant:
               {
                   required: "Veuillez selectionner une nationalité",

                },
               telephone_adoptant:
               {
                   required: "Veuillez saisir un numero de téléphone",

                },
               statut_personne_adoptant:
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

        $('#email_mere').val("");
        document.getElementById('email_mere').readOnly = false;

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
            telephone: telephone.val()
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
                                    '" data-email="'+response.personnes[i].email_personnelle+
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
             telephone: telephone.val()
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
                             '" data-email="'+response.personnes[i].email_personnelle+
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

                 }
             });
     });

     // Rechercher un déclarant
     $('#rechercheradoptant').on("click", function (event) {
         event.preventDefault();

         var nom = $("#nom_adoptant_recherche");
         var prenom = $("#prenom_adoptant_recherche");
         var sexe = $("#sexe_adoptant_recherche");
         var telephone = $("#telephone_adoptant_recherche");
         var statut = $('#statut_personne_adoptant_recherche');

         var data = {
             nom: nom.val(),
             prenom: prenom.val(),
             sexe: sexe.val(),
             telephone: telephone.val(),
             statut:statut.val()
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
                             '" data-email="'+response.personnes[i].email_personnelle+
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
                         var email = $(this).data('emal');

                         var code_nationalite = $(this).data('code_nationalite');
                         var code_profession = $(this).data('code_profession');
                         var lieu_naissance = $(this).data('lieu_naissance');
                         var niveau_instruction = $(this).data('niveau_instruction');
                         var numero_document = $(this).data('numero_document');
                         var code_type_document = $(this).data('code_type_document');
                         var statut_personne = $(this).data('statut_personne');
                         var type_date_naissance = $(this).data('type_date_naissance');


                         $("#nom_adoptant").val(nom);
                         $("#prenom_adoptant").val(prenom);
                         $("#date_naissance_adoptant").val(date_naissance);
                         $("#sexe_adoptant").val(sexe);
                         $("#email_adoptant").val(sexe);
                         $("#domicile_quartier_adoptant").val(quartier);
                         if($(this).data('arrondissement')===null)
                         {
                           $("#domicile_ville_adoptant").val("choisissez");
                         }else{
                             $("#domicile_ville_adoptant").val(arrondissement);
                         }

                         $("#telephone_adoptant").val(telephone);
                         $("#code_nationalite_adoptant").val(code_nationalite);
                         $("#profession_adoptant").val(code_profession);
                         $("#lieu_naissance_adoptant").val(lieu_naissance);
                         $("#niveau_instruction_adoptant").val(niveau_instruction);
                         $("#numero_document_adoptant").val(numero_document);
                         $("#code_type_document_adoptant").val(code_type_document);
                         $("#statut_personne_adoptant").val(statut_personne);
                         $("#type_date_naissance_adoptant").val(type_date_naissance);
                         $("#code_pays_adoptant").val(indicatif);

                         if($(this).data('type_date_naissance')==="EXACTE")
                         {
                             $("#type_date_naissance_adoptant").val("EXACTE");
                             document.getElementById('type_date_naissance_adoptant').checked="";
                         }else{
                             $("#type_date_naissance_adoptant").val("ESTIME");
                             document.getElementById('type_date_naissance_adoptant').checked="ESTIME";

                         }

                         document.getElementById('nom_adoptant').readOnly = true;
                         document.getElementById('prenom_adoptant').readOnly = true;
                         document.getElementById('date_naissance_adoptant').readOnly = true;
                         document.getElementById('lieu_naissance_adoptant').readOnly = true;
                         document.getElementById('code_nationalite_adoptant').disabled = true;
                         document.getElementById('type_date_naissance_adoptant').disabled = true;
                         document.getElementById('statut_personne_adoptant').disabled = true;

                         $("#adoptantmodal").modal('hide');

                     });

                 }
             });
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
