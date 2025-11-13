<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
    <script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
    <!-- Form validate init -->
    <script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>
     <!-- Daterangepicker -->
     <script src="{{ asset('tpl/js/plugins-init/bs-daterange-picker-init.js') }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset('tpl/js/plugins-init/clock-picker-init.js') }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset('tpl/js/plugins-init/jquery-asColorPicker.init.js') }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset('tpl/js/plugins-init/material-date-picker-init.js') }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset('tpl/js/plugins-init/pickadate-init.js') }}"></script>
    <!-- This Page JS -->
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script>

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
            // Vérifier si c'est une erreur de validation
            if (response.errors) {
                var errors = Object.values(response.errors);
                if (errors.length > 0) {
                    message = errors[0][0] || "Erreur de validation";
                } else {
                    message = "Une erreur de validation s'est produite";
                }
            } else {
                message = "Une erreur inattendue s'est produite lors de l'enregistrement";
            }
        }

        return message;
    }

    $(document).ready(function(){
        //dissimulation par défaut de la notification épouse mineure
        $("#notificationEpouseMineure").hide();
        $("#notificationEpouxMineure").hide();
        $("#notificationPreMariage").hide();

        // Initialisation du wizard
        var form = $(".validation-wizard").show();

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
            // Validation des dates avant soumission
            var dateNaissanceEpoux = $("#date_naissance_epoux").val();
            var dateEditionEpoux = $("#date_emission_acte_naissance_epoux").val();
            var dateNaissanceEpouse = $("#date_naissance_epouse").val();
            var dateEditionEpouse = $("#date_emission_acte_naissance_epouse").val();

            var validEpoux = validateDateEditionActe(dateNaissanceEpoux, dateEditionEpoux, "notificationDateActeEpoux");
            var validEpouse = validateDateEditionActe(dateNaissanceEpouse, dateEditionEpouse, "notificationDateActeEpouse");

            if (!validEpoux || !validEpouse) {
                alert("Veuillez corriger les erreurs de dates avant de continuer.");
                return false;
            }

            soumission();
            console.log(soumission())
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


    });


    $(function(){
        hideEtrangepoux();
        hideEtrangepouse();
        hideEnfant();

        $('#etranger').hide();
        $('#etrangere').hide();
        $('#congolais').show();
        $('#congolaise').show();

        //CAS EPOUX
        $("#etrangerepoux").click(function(){
            var origineEpoux = $(this).val();
            // var code_nationalite = $("#code_nationalite").val();
            $('#etranger').show();
            $('#congolais').hide();
            $(".localiteepoux").hide();
            $("#localiteepoux").prop("disabled",true);
            $(".autrelieunaissanceepoux").removeClass("d-none");
            $(".autrelieunaissanceepoux").prop("disabled",false);

            $(".codececepoux").hide();
            $("#code_cec_epoux").prop("disabled",true);
            $(".autrececnaissanceepoux").removeClass("d-none");
            $(".autrececnaissanceepoux").prop("disabled",false);
            $("#code_nationalite_epoux").prop("disabled",false);
            ShowEtrangepoux();
        });

        $("#nationaleepoux").click(function(){
            var origineEpoux = $(this).val();
            $('#etranger').hide();
            $('#congolais').show();
            $("#code_nationalite_epoux").prop("disabled",true);

            $(".localiteepoux").show();
            $("#localiteepoux").prop("disabled",false);

            $(".codececepoux").show();
            $("#code_cec_epoux").prop("disabled",false);

            $(".autrececnaissanceepoux").addClass("d-none");
            $(".autrececnaissanceepoux").prop("disabled",true);

            $(".autrelieunaissanceepoux").addClass("d-none");
            $(".autrelieunaissanceepoux").prop("disabled",true);

            hideEtrangepoux();
        });

        //CAS EPOUSE
        $("#etrangerepouse").click(function(){
            var origineEpouse = $(this).val();
            // var code_nationalite = $("#code_nationalite").val();
            $('#etrangere').show();
            $('#congolaise').hide();

            $("#code_nationalite_epouse").prop("disabled",false);
            ShowEtrangepouse();
        });

        $("#nationaleepoux").click(function(){
            var origineEpouse = $(this).val();
            $('#etrangere').hide();
            $('#congolaise').show();
            $("#code_nationalite_epouse").prop("disabled",true);
            hideEtrangepoux();
        });

        $("#sit_matrimoniale_epoux").on("change", function(){
            var sitMat = $(this).val();
            if(sitMat != "" || sitMat != null){
                if (sitMat == "SMAT_0001") {

                    $(".numactemariageepoux").fadeIn(2000);
                    $(".pre_mariage_epoux").fadeOut();
                    $(".numajugementdivorceepoux").fadeOut();
                    $(".numactedecesepouse").fadeOut();
                    $("#numero_acte_mariage_epoux").prop("disabled",false);
                    // $("#regime_epoux").prop("disabled",false);
                 }
                 else if (sitMat == "SMAT_0002") {

                    $(".pre_mariage_epoux").fadeIn(2000);
                    $(".numajugementdivorceepoux").fadeOut();
                    $(".numactemariageepoux").fadeOut();
                    $(".numactemariageepoux").fadeOut();
                    $(".numactedecesepouse").fadeOut();
                    $("#numero_jugement_divorce_epoux").prop("disabled",false);

                    $("#date_pre_mariage_epoux").prop("disabled",false);
                    $("#parent_paternel_epoux").prop("disabled",false);
                    $("#parent_maternel_epoux").prop("disabled",false);

                }
                 else if (sitMat == "SMAT_0005") {

                    $(".numajugementdivorceepoux").fadeIn(2000);
                    $(".pre_mariage_epoux").fadeOut();
                    $(".numactemariageepoux").fadeOut();
                    $(".numactedecesepouse").fadeOut();
                    $("#numero_jugement_divorce_epoux").prop("disabled",false);

                    $("#date_pre_mariage_epoux").prop("disabled",true);
                    $("#parent_paternel_epoux").prop("disabled",true);
                    $("#parent_maternel_epoux").prop("disabled",true);

                }
                 else if (sitMat == "SMAT_0006") {

                    $(".numactedecesepouse").fadeIn(2000);
                    $(".pre_mariage_epoux").fadeOut();
                    $(".numactemariageepoux").fadeOut();
                    $(".numajugementdivorceepoux").fadeOut();
                    $("#numero_acte_deces_epouse").prop("disabled",false);

                    $("#date_pre_mariage_epoux").prop("disabled",true);
                    $("#parent_paternel_epoux").prop("disabled",true);
                    $("#parent_maternel_epoux").prop("disabled",true);

                }
                 else{
                    $(".numactemariageepoux").fadeOut();
                    $("#numero_acte_mariage_epoux").prop("disabled",true);

                    $(".pre_mariage_epoux").fadeOut();
                    $("#date_pre_mariage_epoux").prop("disabled",true);
                    $("#parent_paternel_epoux").prop("disabled",true);
                    $("#parent_maternel_epoux").prop("disabled",true);

                    $(".numajugementdivorceepoux").fadeOut();
                    $("#numero_jugement_divorce_epoux").prop("disabled",true);

                    $(".numactedecesepouse").fadeOut();
                    $("#numero_acte_deces_epouse").prop("disabled",true);
                }

            }
        });

         // VERIFICATION NOMBRE D'ENFANTS - VERSION OPTIMISEE
         $("#nombre_enfant").on('change', function(){
            var nombre = parseInt($(this).val()) || 0;

            // Utiliser la fonction optimisée
            showEnfants(nombre);
        });

        //FIN CAS EPOUX

        //CAS DE EPOUSE
        $("#etrangerepouse").click(function(){
            var origineEpouse = $(this).val();
            $("#code_nationalite_epouse").prop("disabled",false);
            ShowEtrangepouse();
        });

        $("#nationaleepouse").click(function(){
            var origineEpouse = $(this).val();
            $("#code_nationalite_epouse").prop("disabled",true);
            hideEtrangepouse();
        });

        $("#sit_matrimoniale_epouse").on("change", function(){
            var sitMat = $(this).val();
            if(sitMat != "" || sitMat != null){

                if (sitMat == "SMAT_0001") {

                        $(".numactemariageepouse").fadeIn(2000);
                        $(".pre_mariage_epouse").fadeOut();
                        $(".numajugementdivorceepouse").fadeOut();
                        $(".numactedecesepoux").fadeOut();
                        $("#numero_acte_mariage_epouse").prop("disabled",false);
                        // $("#regime_epouse").prop("disabled",false);
                    }
                    else if (sitMat == "SMAT_0002") {

                        $(".pre_mariage_epouse").fadeIn(2000);
                        $(".numactemariageepouse").fadeOut();
                        $(".numactemariageepouse").fadeOut();
                        $(".numactedecesepoux").fadeOut();
                        $("#numero_jugement_divorce_epouse").prop("disabled",false);

                        $("#date_pre_mariage_epouse").prop("disabled",false);
                        $("#parent_paternel_epouse").prop("disabled",false);
                        $("#parent_maternel_epouse").prop("disabled",false);

                    }
                    else if (sitMat == "SMAT_0005") {

                        $(".numajugementdivorceepouse").fadeIn(2000);
                        $(".pre_mariage_epouse").fadeOut();
                        $(".numactemariageepouse").fadeOut();
                        $(".numactedecesepoux").fadeOut();
                        $("#numero_jugement_divorce_epouse").prop("disabled",false);

                        $("#date_pre_mariage_epouse").prop("disabled",true);
                        $("#parent_paternel_epouse").prop("disabled",true);
                        $("#parent_maternel_epouse").prop("disabled",true);

                    }
                    else if (sitMat == "SMAT_0006") {

                        $(".numactedecesepoux").fadeIn(2000);
                        $(".pre_mariage_epouse").fadeOut();
                        $(".numactemariageepouse").fadeOut();
                        $(".numajugementdivorceepouse").fadeOut();
                        $("#numero_acte_deces_epoux").prop("disabled",false);

                        $("#date_pre_mariage_epouse").prop("disabled",true);
                        $("#parent_paternel_epouse").prop("disabled",true);
                        $("#parent_maternel_epouse").prop("disabled",true);

                    }
                    else{
                        $(".numactemariageepouse").fadeOut();
                        $("#numero_acte_mariage_epouse").prop("disabled",true);

                        $(".pre_mariage_epouse").fadeOut();
                        $("#date_pre_mariage_epouse").prop("disabled",true);
                        $("#parent_paternel_epouse").prop("disabled",true);
                        $("#parent_maternel_epouse").prop("disabled",true);

                        $(".numajugementdivorceepouse").fadeOut();
                        $("#numero_jugement_divorce_epouse").prop("disabled",true);

                        $(".numactedecesepoux").fadeOut();
                        $("#numero_acte_deces_epoux").prop("disabled",true);
                    }
            }
        });

        $("#domicile_numero_epoux").blur(function(){
            var domicilenumeroepoux = $(this).val();
            if(domicilenumeroepoux !='' || domicilenumeroepoux != null){
                var domicilenumeroepoux = $("#domicile_numero_epoux").val();
                $("#same_domicile_numero").val(domicilenumeroepoux);
            }
        });
        $("#domicile_nomvoie_epoux").blur(function(){
            var nomvoie = $(this).val();
            if(nomvoie !='' || nomvoie != null){
                $("#same_domicile_nomvoie").val(nomvoie);
            }
        });

        $("#date_pre_mariage_epoux").blur(function(){
            var datePreMariage = $(this).val();
            if(datePreMariage !='' || datePreMariage != null){
                $("#date_pre_mariage_epouse").val(datePreMariage);
                $("#date_pre_mariage_epouse").prop("readonly", true);
            }
        });
        return false;
        //FIN CAS EPOUSE

    });

    var enfants = [];

    // Fonction optimisée d'insertion d'enfant dans le tableau
    function insertEnfant(nom, prenom, sexe, dateNais, lieuNais){
        // Validation des données obligatoires
        if(!nom || !prenom) {
            console.warn('Nom et prénom sont obligatoires pour ajouter un enfant');
            return false;
        }

        enfants.push({
            nom: nom.trim(),
            prenom: prenom.trim(),
            sexe: sexe || '',
            date_naissance: dateNais || '',
            lieu_naissance: lieuNais || ''
        });

        return true;
    }

    // Fonction utilitaire pour vider la liste des enfants
    function clearEnfants() {
        enfants = [];
    }
    //GESTION IDENTIFICATION ENFANT - VERSION OPTIMISEE
    function hideEnfant(){
        // Masquer tous les enfants d'un coup avec un sélecteur
        for(let i = 1; i <= 9; i++) {
            $('#enfant' + i).hide();
        }
    }

    // Fonction optimisée pour afficher les enfants
    function showEnfants(nombre) {
        hideEnfant(); // Masquer tous d'abord

        // Afficher seulement le nombre requis
        for(let i = 1; i <= nombre; i++) {
            $('#enfant' + i).show();
        }
    }

    function hideEtrangepoux(){
        $(".origineEpoux").fadeOut();
    }
    function ShowEtrangepoux(){
        $(".origineEpoux").fadeIn(2000);
    }

    function hideEtrangepouse(){
        $(".origineEpouse").fadeOut();
    }
    function ShowEtrangepouse(){
        $(".origineEpouse").fadeIn(2000);
    }

    function soumission(){
     // informations de l'époux
        var type_declaration = $("#type_declaration");
        var type_mariage = $("#type_mariage");
        var nom_epoux = $("#nom_epoux");
        var prenom_epoux = $("#prenom_epoux");
        var date_naissance_epoux = $("#date_naissance_epoux");
        var date_emission_acte_naissance_epoux = $("#date_emission_acte_naissance_epoux");
        var date_autorisation_ambassade_epoux = $("#date_autorisation_ambassade_epoux");
        var code_localite_epoux = $("#code_localite_epoux");
        var lieu_naissance_epoux = $("#lieu_naissance_epoux");
        var cec_naissance_epoux = $("#cec_naissance_epoux");
        var lieu_naissance_epoux = $("#lieu_naissance_epoux");

        var num_acte_naissance_epoux = $("#num_acte_naissance_epoux");
        var centre_etat_civil_epoux = $("#centre_etat_civil_epoux");
        var code_nationalite_epoux = $("#code_nationalite_epoux");
        var code_profession_epoux = $("#code_profession_epoux");

        var nom_pere_epoux = $("#nom_pere_epoux");
        var nom_mere_epoux = $("#nom_mere_epoux");
        var autorisation_ambassade_epoux = $("#autorisation_ambassade_epoux");
        var certificat_residence_epoux = $("#certificat_residence_epoux");
        var date_emission_certificat_residence_epoux = $("#date_emission_certificat_residence_epoux");
        var sit_matrimoniale_epoux = $("#sit_matrimoniale_epoux");
        var numero_acte_mariage_epoux = $("#numero_acte_mariage_epoux");
        var code_type_document_epoux = $("#code_type_document_epoux");
        var numero_document_epoux = $("#numero_document_epoux");

        //Informations enfants
        var nombre_enfant = $("#nombre_enfant").val();
        var nom,prenom,sexe,date_naissance,lieu_naissance;

        enfants = [];

        // Boucle optimisée pour collecter les données des enfants
        for (var index = 1; index <= nombre_enfant; index++) {
            var nom = $("#nom" + index).val();
            var prenom = $("#prenom" + index).val();
            var sexe = $("#sexe" + index).val();
            var date_naissance = $("#datenaiss" + index).val();
            var lieu_naissance = $("#lieunaiss" + index).val();

            // Insérer seulement si les champs obligatoires sont remplis
            if(nom && prenom) {
                insertEnfant(nom, prenom, sexe, date_naissance, lieu_naissance);
            }
        }


        // informations témoins de l'époux 1 et 2
        var nom_t_epoux_1 = $("#nom_t_epoux_1");
        var prenom_t_epoux_1 = $("#prenom_t_epoux_1");
        var date_naissance_t_epoux_1 = $("#date_naissance_t_epoux_1");
        var cec_naissance_t_epoux_1 = $("#cec_t_epoux_1");
        var lieu_naissance_t_epoux_1 = $("#lieu_naissance_t_epoux_1");
        var code_localite_t_epoux_1 = $("#code_localite_t_epoux_1");
        var date_naissance_t_epoux_1 = $("#date_naissance_t_epoux_1");
        var code_nationalite_t_epoux_1 = $("#code_nationalite_t_epoux_1");
        var code_profession_t_epoux_1 = $("#code_profession_t_epoux_1");
        var code_type_document_t_epoux_1 = $("#code_type_document_t_epoux_1");
        var numero_document_t_epoux_1 = $("#numero_document_t_epoux_1");

        var nom_t_epoux_2 = $("#nom_t_epoux_2");
        var prenom_t_epoux_2 = $("#prenom_t_epoux_2");
        var cec_naissance_t_epoux_2 = $("#cec_t_epoux_2");
        var lieu_naissance_t_epoux_2 = $("#lieu_naissance_t_epoux_2");
        var code_localite_t_epoux_2 = $("#code_localite_t_epoux_2");
        var date_naissance_t_epoux_2 = $("#date_naissance_t_epoux_2");
        var code_nationalite_t_epoux_2 = $("#code_nationalite_t_epoux_2");
        var code_profession_t_epoux_2 = $("#code_profession_t_epoux_2");
        var code_type_document_t_epoux_2 = $("#code_type_document_t_epoux_2");
        var numero_document_t_epoux_2 = $("#numero_document_t_epoux_2");

        //debut adresse témoins epoux
        var domicile_pays_temoins_epoux = $("#domicile_pays_temoins_epoux");
        var domicile_pays_temoins_epoux = $("#domicile_pays_temoins_epoux");
        var domicile_ville_temoins_epoux = $("#domicile_ville_temoins_epoux");
        var domicile_arrondissement_temoins_epoux = $("#domicile_arrondissement_temoins_epoux");
        var domicile_quartier_temoins_epoux = $("#domicile_quartier_temoins_epoux");
        var domicile_typevoie_temoins_epoux = $("#domicile_typevoie_temoins_epoux");
        var domicile_nomvoie_temoins_epoux = $("#domicile_nomvoie_temoins_epoux");
        var domicile_numero_temoins_epoux = $("#domicile_numero_temoins_epoux");
        //fin adresse témoins epoux

        // informations de l'épouse
        var nom_epouse = $("#nom_epouse");
        var prenom_epouse = $("#prenom_epouse");
        var date_naissance_epouse = $("#date_naissance_epouse");
        var date_emission_acte_naissance_epouse = $("#date_emission_acte_naissance_epouse");
        var cec_naissance_epouse = $("#cec_naissance_epouse");
        var lieu_naissance_epouse = $("#lieu_naissance_epouse");
        var code_localite_epouse = $("#code_localite_epouse");
        var date_autorisation_ambassade_epouse = $("#date_autorisation_ambassade_epouse");
        var num_acte_naissance_epouse = $("#num_acte_naissance_epouse");
        var centre_etat_civil_epouse = $("#centre_etat_civil_epouse");
        var code_nationalite_epouse = $("#code_nationalite_epouse");
        var code_profession_epouse = $("#code_profession_epouse");


        //adresse époux
        var domicile_pays_epoux = $("#domicile_pays_epoux");
        var domicile_ville_epoux = $("#domicile_ville_epoux");
        var domicile_arrondissement_epoux = $("#domicile_arrondissement_epoux");
        var domicile_quartier_epoux = $("#domicile_quartier_epoux");
        var domicile_typevoie_epoux = $("#domicile_typevoie_epoux");
        var domicile_nomvoie_epoux = $("#domicile_nomvoie_epoux");
        var domicile_numero_epoux = $("#domicile_numero_epoux");

        // Variables pour les adresses épouse (valeurs string, pas objets jQuery)
        var domicile_pays_epouse_val = "";
        var domicile_ville_epouse_val = "";
        var domicile_arrondissement_epouse_val = "";
        var domicile_quartier_epouse_val = "";
        var domicile_typevoie_epouse_val = "";
        var domicile_nomvoie_epouse_val = "";
        var domicile_numero_epouse_val = "";

        //fin epoux
        // GESTION DES ADRESSES ÉPOUSE
        if($("#sameadress").is(':checked')){
            // Si même adresse que l'époux, copier les valeurs de l'époux
            domicile_pays_epouse_val = $("#domicile_pays_epoux").val();
            domicile_ville_epouse_val = $("#domicile_ville_epoux").val();
            domicile_arrondissement_epouse_val = $("#domicile_arrondissement_epoux").val();
            domicile_quartier_epouse_val = $("#domicile_quartier_epoux").val();
            domicile_typevoie_epouse_val = $("#domicile_typevoie_epoux").val();
            domicile_nomvoie_epouse_val = $("#domicile_nomvoie_epoux").val();
            domicile_numero_epouse_val = $("#domicile_numero_epoux").val();
        } else {
            // Si adresse différente, prendre les valeurs des champs épouse
            domicile_pays_epouse_val = $("#domicile_pays_epouse").val();
            domicile_ville_epouse_val = $("#domicile_ville_epouse").val();
            domicile_arrondissement_epouse_val = $("#domicile_arrondissement_epouse").val();
            domicile_quartier_epouse_val = $("#domicile_quartier_epouse").val();
            domicile_typevoie_epouse_val = $("#domicile_typevoie_epouse").val();
            domicile_nomvoie_epouse_val = $("#domicile_nomvoie_epouse").val();
            domicile_numero_epouse_val = $("#domicile_numero_epouse").val();
        }

        var nom_pere_epouse = $("#nom_pere_epouse");
        var nom_mere_epouse = $("#nom_mere_epouse");
        var chef_famille = $("#chef_famille");
        var filiation = $("#filiation");
        var autorisation_ambassade_epouse = $("#autorisation_ambassade_epouse");
        var certificat_residence_epouse = $("#certificat_residence_epouse");
        var date_emission_certificat_residence_epouse = $("#date_emission_certificat_residence_epouse");
        var sit_matrimoniale_epouse = $("#sit_matrimoniale_epouse");
        var numero_acte_mariage_epouse = $("#numero_acte_mariage_epouse");
        var code_type_document_epouse = $("#code_type_document_epouse");
        var numero_document_epouse = $("#numero_document_epouse");

         // informations témoins de l'épouse 1 et 2
        var nom_t_epouse_1 = $("#nom_t_epouse_1");
        var prenom_t_epouse_1 = $("#prenom_t_epouse_1");
        var date_naissance_t_epouse_1 = $("#date_naissance_t_epouse_1");
        var cec_naissance_t_epouse_1 = $("#cec_t_epouse_1");
        var lieu_naissance_t_epouse_1 = $("#lieu_naissance_t_epouse_1");
        var code_localite_t_epouse_1 = $("#code_localite_t_epouse_1");
        var code_nationalite_t_epouse_1 = $("#code_nationalite_t_epouse_1");
        var code_profession_t_epouse_1 = $("#code_profession_t_epouse_1");
        var code_type_document_t_epouse_1 = $("#code_type_document_t_epouse_1");
        var numero_document_t_epouse_1 = $("#numero_document_t_epouse_1");

         //adresse témoins épouse
        var domicile_pays_temoins_epouse = $("#domicile_pays_temoins_epouse");
        var domicile_ville_temoins_epouse = $("#domicile_ville_temoins_epouse");
        var domicile_arrondissement_temoins_epouse = $("#domicile_arrondissement_temoins_epouse");
        var domicile_quartier_temoins_epouse = $("#domicile_quartier_temoins_epouse");
        var domicile_typevoie_temoins_epouse = $("#domicile_typevoie_temoins_epouse");
        var domicile_nomvoie_temoins_epouse = $("#domicile_nomvoie_temoins_epouse");
        var domicile_numero_temoins_epouse = $("#domicile_numero_temoins_epouse");
        //fin adresse témoins épouse

        var nom_t_epouse_2 = $("#nom_t_epouse_2");
        var prenom_t_epouse_2 = $("#prenom_t_epouse_2");
        var cec_naissance_t_epouse_2 = $("#cec_t_epouse_2");
        var lieu_naissance_t_epouse_2 = $("#lieu_naissance_t_epouse_2");
        var code_localite_t_epouse_2 = $("#code_localite_t_epouse_2");
        var date_naissance_t_epouse_2 = $("#date_naissance_t_epouse_2");
        var code_nationalite_t_epouse_2 = $("#code_nationalite_t_epouse_2");
        var code_profession_t_epouse_2 = $("#code_profession_t_epouse_2");
        var code_type_document_t_epouse_2 = $("#code_type_document_t_epouse_2");
        var numero_document_t_epouse_2 = $("#numero_document_t_epouse_2");


        //Informations mariage
        var date_pre_mariage_epoux = $("#date_pre_mariage_epoux");
        var parent_paternel_epoux = $("#parent_paternel_epoux");
        var parent_maternel_epoux = $("#parent_maternel_epoux");
        var date_pre_mariage_epouse = $("#date_pre_mariage_epouse");
        var montant_dot = $("#montant_dot");
        var parent_paternel_epouse = $("#parent_paternel_epouse");
        var parent_maternel_epouse = $("#parent_maternel_epouse");


        var examens_prenuptiaux = $(".examens_prenuptiaux");
        var regime_mariage = $("#regime_mariage");
        var option_mariage = $("#option_mariage");
        var date_declaration_mariage = $("#date_declaration_mariage");
        var date_ceremonie_mariage = $("#date_ceremonie_mariage");
        var lieu_ceremonie_mariage = $("#lieu_ceremonie_mariage");

        // Variables pour l'adresse de cérémonie
        var domicile_pays_ceremonie = $("#domicile_pays_ceremonie");
        var domicile_ville_ceremonie = $("#domicile_ville_ceremonie");
        var autredomicile_ville_ceremonie = $("#autredomicile_ville_ceremonie");
        var domicile_arrondissement_ceremonie = $("#domicile_arrondissement_ceremonie");
        var domicile_quartier_ceremonie = $("#domicile_quartier_ceremonie");
        var domicile_typevoie_ceremonie = $("#domicile_typevoie_ceremonie");
        var domicile_numero_ceremonie = $("#domicile_numero_ceremonie");
        var domicile_nomvoie_ceremonie = $("#domicile_nomvoie_ceremonie");
        var numero_jugement_divorce_epouse = $("#numero_jugement_divorce_epouse");
        var numero_jugement_divorce_epoux = $("#numero_jugement_divorce_epoux");
        var numero_acte_mariage_epoux = $("#numero_acte_mariage_epoux");
        var numero_acte_deces_epouse = $("#numero_acte_deces_epouse");
        var numero_acte_deces_epoux = $("#numero_acte_deces_epoux");

        //récupération des données relatives au mandant
        var mandant_epoux = $("#nom_mandant_epoux").val()+" "+$("#nom_mandant_epoux").val();
        var mandant_epouse = $("#prenom_mandant_epouse").val()+" "+$("#prenom_mandant_epouse").val();


        var data = {
                enfants: enfants,
                 // informations de l'époux
                type_declaration: type_declaration.val(),
                type_mariage: type_mariage.val(),

                nom_epoux: nom_epoux.val(),
                prenom_epoux :prenom_epoux.val(),
                date_naissance_epoux :date_naissance_epoux.val(),
                date_emission_acte_naissance_epoux :date_emission_acte_naissance_epoux.val(),
                cec_naissance_epoux :cec_naissance_epoux.val(),
                code_localite_epoux :code_localite_epoux.val(),
                lieu_naissance_epoux:lieu_naissance_epoux.val(),
                date_autorisation_ambassade_epoux:date_autorisation_ambassade_epoux.val(),
                num_acte_naissance_epoux :num_acte_naissance_epoux.val(),
                centre_etat_civil_epoux :centre_etat_civil_epoux.val(),
                code_nationalite_epoux :code_nationalite_epoux.val(),
                code_profession_epoux :code_profession_epoux.val(),
                //adresse epoux
                domicile_pays_epoux :domicile_pays_epoux.val(),
                domicile_ville_epoux :domicile_ville_epoux.val(),
                domicile_quartier_epoux :domicile_quartier_epoux.val(),
                domicile_arrondissement_epoux :domicile_arrondissement_epoux.val(),
                domicile_typevoie_epoux :domicile_typevoie_epoux.val(),
                domicile_nomvoie_epoux :domicile_nomvoie_epoux.val(),
                domicile_numero_epoux :domicile_numero_epoux.val(),
                //fin adresse epoux
                nom_pere_epoux :nom_pere_epoux.val(),
                nom_mere_epoux :nom_mere_epoux.val(),
                autorisation_ambassade_epoux :autorisation_ambassade_epoux.val(),
                certificat_residence_epoux :certificat_residence_epoux.val(),
                date_emission_certificat_residence_epoux :date_emission_certificat_residence_epoux.val(),
                sit_matrimoniale_epoux :sit_matrimoniale_epoux.val(),
                numero_acte_mariage_epoux :numero_acte_mariage_epoux.val(),
                code_type_document_epoux :code_type_document_epoux.val(),
                numero_document_epoux :numero_document_epoux.val(),
                 //adresse epouse
                domicile_pays_epouse :domicile_pays_epouse_val,
                domicile_ville_epouse :domicile_ville_epouse_val,
                domicile_quartier_epouse :domicile_quartier_epouse_val,
                domicile_arrondissement_epouse :domicile_arrondissement_epouse_val,
                domicile_typevoie_epouse :domicile_typevoie_epouse_val,
                domicile_nomvoie_epouse :domicile_nomvoie_epouse_val,
                domicile_numero_epouse :domicile_numero_epouse_val,
                //fin adresse epouse
                // informations de l'épouse
                nom_epouse :nom_epouse.val(),
                prenom_epouse :prenom_epouse.val(),
                date_naissance_epouse :date_naissance_epouse.val(),
                date_emission_acte_naissance_epouse :date_emission_acte_naissance_epouse.val(),
                cec_naissance_epouse :cec_naissance_epouse.val(),
                code_localite_epouse :code_localite_epouse.val(),
                lieu_naissance_epouse:lieu_naissance_epouse.val(),
                date_autorisation_ambassade_epouse:date_autorisation_ambassade_epouse.val(),
                num_acte_naissance_epouse :num_acte_naissance_epouse.val(),
                centre_etat_civil_epouse :centre_etat_civil_epouse.val(),
                code_nationalite_epouse :code_nationalite_epouse.val(),
                code_profession_epouse :code_profession_epouse.val(),
                nom_pere_epouse :nom_pere_epouse.val(),
                nom_mere_epouse :nom_mere_epouse.val(),
                chef_famille :chef_famille.val(),
                filiation :filiation.val(),
                autorisation_ambassade_epouse :autorisation_ambassade_epouse.val(),
                certificat_residence_epouse :certificat_residence_epouse.val(),
                date_emission_certificat_residence_epouse :date_emission_certificat_residence_epouse.val(),
                sit_matrimoniale_epouse :sit_matrimoniale_epouse.val(),
                numero_acte_mariage_epouse :numero_acte_mariage_epouse.val(),
                code_type_document_epouse :code_type_document_epouse.val(),
                numero_document_epouse :numero_document_epouse.val(),
                // informations témoins de l'époux 1 et 2
                nom_t_epoux_1 :nom_t_epoux_1.val(),
                prenom_t_epoux_1 :prenom_t_epoux_1.val(),
                cec_naissance_t_epoux_1 :cec_naissance_t_epoux_1.val(),
                code_localite_t_epoux_1 :code_localite_t_epoux_1.val(),
                lieu_naissance_t_epoux_1:lieu_naissance_t_epoux_1.val(),
                date_naissance_t_epoux_1 :date_naissance_t_epoux_1.val(),
                code_nationalite_t_epoux_1 :code_nationalite_t_epoux_1.val(),
                code_profession_t_epoux_1 :code_profession_t_epoux_1.val(),
                code_type_document_t_epoux_1 :code_type_document_t_epoux_1.val(),
                numero_document_t_epoux_1 :numero_document_t_epoux_1.val(),

                nom_t_epoux_2 :nom_t_epoux_2.val(),
                prenom_t_epoux_2 :prenom_t_epoux_2.val(),
                cec_naissance_t_epoux_2 :cec_naissance_t_epoux_2.val(),
                code_localite_t_epoux_2 :code_localite_t_epoux_2.val(),
                lieu_naissance_t_epoux_2:lieu_naissance_t_epoux_2.val(),
                date_naissance_t_epoux_2 :date_naissance_t_epoux_2.val(),
                code_nationalite_t_epoux_2 :code_nationalite_t_epoux_2.val(),
                code_profession_t_epoux_2 :code_profession_t_epoux_2.val(),
                code_type_document_t_epoux_2 :code_type_document_t_epoux_2.val(),
                numero_document_t_epoux_2 :numero_document_t_epoux_2.val(),
                //infos adresse des temoins de l'époux
                domicile_pays_t_epoux_1 :domicile_pays_temoins_epoux.val(),
                domicile_ville_t_epoux_1 :domicile_ville_temoins_epoux.val(),
                domicile_quartier_t_epoux_1 :domicile_quartier_temoins_epoux.val(),
                domicile_arrondissement_t_epoux_1 :domicile_arrondissement_temoins_epoux.val(),
                domicile_typevoie_t_epoux_1 :domicile_typevoie_temoins_epoux.val(),
                domicile_nomvoie_t_epoux_1 :domicile_nomvoie_temoins_epoux.val(),
                domicile_numero_t_epoux_1 :domicile_numero_temoins_epoux.val(),
                //fin adresse temoins_epoux
                // informations témoins de l'épouse 1 et 2
                nom_t_epouse_1 :nom_t_epouse_1.val(),
                prenom_t_epouse_1 :prenom_t_epouse_1.val(),
                cec_naissance_t_epouse_1 :cec_naissance_t_epouse_1.val(),
                code_localite_t_epouse_1 :code_localite_t_epouse_1.val(),
                lieu_naissance_t_epouse_1:lieu_naissance_t_epouse_1.val(),
                date_naissance_t_epouse_1 :date_naissance_t_epouse_1.val(),
                code_nationalite_t_epouse_1 :code_nationalite_t_epouse_1.val(),
                code_profession_t_epouse_1 :code_profession_t_epouse_1.val(),
                code_type_document_t_epouse_1 :code_type_document_t_epouse_1.val(),
                numero_document_t_epouse_1 :numero_document_t_epouse_1.val(),
                nom_t_epouse_2 :nom_t_epouse_2.val(),
                prenom_t_epouse_2 :prenom_t_epouse_2.val(),
                cec_naissance_t_epouse_2 :cec_naissance_t_epouse_2.val(),
                code_localite_t_epouse_2 :code_localite_t_epouse_2.val(),
                lieu_naissance_t_epouse_2:lieu_naissance_t_epouse_2.val(),
                date_naissance_t_epouse_2 :date_naissance_t_epouse_2.val(),
                code_nationalite_t_epouse_2 :code_nationalite_t_epouse_2.val(),
                code_profession_t_epouse_2 :code_profession_t_epouse_2.val(),
                code_type_document_t_epouse_2 :code_type_document_t_epouse_2.val(),
                numero_document_t_epouse_2 :numero_document_t_epouse_2.val(),
                //infos adresse des temoins de l'épouse
                domicile_pays_t_epouse_1 :domicile_pays_temoins_epouse.val(),
                domicile_ville_t_epouse_1 :domicile_ville_temoins_epouse.val(),
                domicile_quartier_t_epouse_1 :domicile_quartier_temoins_epouse.val(),
                domicile_arrondissement_t_epouse_1 :domicile_arrondissement_temoins_epouse.val(),
                domicile_typevoie_t_epouse_1 :domicile_typevoie_temoins_epouse.val(),
                domicile_nomvoie_t_epouse_1 :domicile_nomvoie_temoins_epouse.val(),
                domicile_numero_t_epouse_1 :domicile_numero_temoins_epouse.val(),
                //fin adresse temoins_epouse
                //Informations mariage
                examens_prenuptiaux :examens_prenuptiaux.val(),
                date_pre_mariage_epoux :date_pre_mariage_epoux.val(),
                parent_paternel_epoux :parent_paternel_epoux.val(),
                parent_maternel_epoux :parent_maternel_epoux.val(),
                date_pre_mariage_epouse :date_pre_mariage_epouse.val(),
                montant_dot :montant_dot.val(),
                parent_paternel_epouse :parent_paternel_epouse.val(),
                parent_maternel_epouse :parent_maternel_epouse.val(),
                regime_mariage :regime_mariage.val(),
                option_mariage :option_mariage.val(),
                date_declaration_mariage :date_declaration_mariage.val(),
                date_ceremonie_mariage :date_ceremonie_mariage.val(),
                lieu_ceremonie_mariage :lieu_ceremonie_mariage.val(),

                // Adresse de cérémonie
                domicile_pays_ceremonie :domicile_pays_ceremonie.val(),
                domicile_ville_ceremonie :domicile_ville_ceremonie.val(),
                autredomicile_ville_ceremonie :autredomicile_ville_ceremonie.val(),
                domicile_arrondissement_ceremonie :domicile_arrondissement_ceremonie.val(),
                domicile_quartier_ceremonie :domicile_quartier_ceremonie.val(),
                domicile_typevoie_ceremonie :domicile_typevoie_ceremonie.val(),
                domicile_numero_ceremonie :domicile_numero_ceremonie.val(),
                domicile_nomvoie_ceremonie :domicile_nomvoie_ceremonie.val(),
                numero_jugement_divorce_epoux :numero_jugement_divorce_epoux.val(),
                numero_jugement_divorce_epouse :numero_jugement_divorce_epouse.val(),
                numero_acte_mariage_epoux :numero_acte_mariage_epoux.val(),
                numero_acte_mariage_epouse :numero_acte_mariage_epouse.val(),
                numero_acte_deces_epoux :numero_acte_deces_epoux.val(),
                numero_acte_deces_epouse :numero_acte_deces_epouse.val(),

                //chargement des données concernant le mandant
                nom_prenom_mandant_epoux:mandant_epoux,
                nom_prenom_mandant_epouse:mandant_epouse

            };

        //Traitement formulaire
        $.post("{{ route('declarationMariage.store') }}", data, function(response){
            console.log('Réponse du serveur:', response); // Debug

            // Vérifier si la réponse est valide
            // if (!response) {
            //     flashAlert("Opération échouée","error","Aucune réponse du serveur");
            //     return;
            // }

            // Vérifier le code de réponse (string ou number)
            var codeResponse = response.code;
            if (codeResponse == "200" || codeResponse == 200) {
                flashAlert("Opération réussie","success",response.message || "La déclaration de mariage a été enregistrée avec succès");
                var url = "{{ route('declarationMariage.index') }}";
                setTimeout(() => {
                    window.location.href = url; // Utiliser window.location.href au lieu de window.open
                }, 2000);
            } else {
                // Gestion améliorée des messages d'erreur
                var messageErreur = traiterMessageErreur(response);
                flashAlert("Opération échouée","error",messageErreur);
            }
        }).fail(function(xhr) {
            console.log('Erreur AJAX:', xhr); // Debug
            // Gestion des erreurs de connexion
            var messageErreur = "Erreur de connexion. Veuillez vérifier votre connexion internet et réessayer.";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                messageErreur = traiterMessageErreur(xhr.responseJSON);
            } else if (xhr.responseText) {
                try {
                    var responseData = JSON.parse(xhr.responseText);
                    if (responseData.message) {
                        messageErreur = traiterMessageErreur(responseData);
                    }
                } catch (e) {
                    // Si ce n'est pas du JSON, utiliser le texte brut
                    messageErreur = xhr.responseText || messageErreur;
                }
            }
            flashAlert("Erreur de connexion","error",messageErreur);
        });
        return false;

    }

    //contrôle de la maturité de l"épouse
    $("#date_naissance_epouse").blur(function(){
        //réucpération de l'année couranrt
        var dateCourante = new Date();
        var anneeCourante = dateCourante.getFullYear();
       // alert(anneeCourante);
        var anneeNaissanceEpouse = new Date($(this).val()).getFullYear();
        //calcul de la différence par rapport à l'année actuelle
        var diffAnnee = anneeCourante - anneeNaissanceEpouse;
        //affichage de la notification
        if(diffAnnee<18){
            $("#notificationEpouseMineure").show(300);
        }else{
            $("#notificationEpouseMineure").hide(300);
        }

    });


     //contrôle de la maturité de l"épouse
     $("#date_naissance_epoux").blur(function(){

        //réucpération de l'année couranrt
        var dateCourante = new Date();

        var anneeCourante = dateCourante.getFullYear();
       // alert(anneeCourante);
        var anneeNaissanceEpouse = new Date($(this).val()).getFullYear();
        //calcul de la différence par rapport à l'année actuelle
        var diffAnnee = anneeCourante - anneeNaissanceEpouse;
        //affichage de la notification
        if(diffAnnee<21){
            $("#notificationEpouxMineure").show(300);
        }else{
            $("#notificationEpouxMineure").hide(300);
        }
        return false;
    });


      //contrôle de la date du pré-mariage par rapport à la date mariage
      $("#date_ceremonie_mariage").blur(function(){
       //alert("contrôle date date pré et céré..");
       //réucpération de la date pré-mariage
       var datePre = $("#date_pre_mariage_epoux").val();
       //récupération de la date mariage
       var dateMariage = $(this).val();
      // alert(anneeCourante);
      // var anneeNaissanceEpouse = new Date($(this).val()).getFullYear();
       //calcul de la différence par rapport à l'année actuelle
       //var diff = dateMariage - anneeNaissanceEpouse;
       //affichage de la notification
       if(dateMariage<=datePre){
            //affichage de la barre de notification
           $("#notificationPreMariage").show(300);
           //dissimulation du bouton enregistrer
           $("a[href='#finish']").attr("style", 'display:none;');
        //    $("a[href='#finish']").attr("href", '#');
       }else{
           $("#notificationPreMariage").hide(300);
           $("a[href='#finish']").attr("style", 'display:block;');
        //    $("a[href='#finish']").attr("href", '#finish');
       }
       return false;
   });

   /*$("#date_mariage_valeur").blur(function(){
       //alert("contrôle date date pré et céré..");
       //réucpération de la date pré-mariage
       var datePre = $("#date_pre_mariage_epoux").val();
       //récupération de la date mariage
       var dateMariage = $(this).val();
      // alert(anneeCourante);
      // var anneeNaissanceEpouse = new Date($(this).val()).getFullYear();
       //calcul de la différence par rapport à l'année actuelle
       //var diff = dateMariage - anneeNaissanceEpouse;
       //affichage de la notification
       alert(dateMariage);
       if(dateMariage<=datePre){
            //affichage de la barre de notification
           $("#notificationPreMariage").show(300);
           //dissimulation du bouton enregistrer
           $("a[href='#next']").attr("style", 'background:silver;');
           $("a[href='#next']").attr("href", '#');
       }else{
           $("#notificationPreMariage").hide(300);
       }
       return false;
   });*/

    // ========== GESTION DES ADRESSES ==========

    // Fonctions utilitaires pour récupérer les données d'adresse
    function getArrComUrbaine(codeLocalite, selectId) {
        var route = "{{ route('declarationNaissance.search.arrond') }}";
        var option = "<option value=''>Choisir</option>";

        $.ajax({
            url: route,
            data: 'id=' + codeLocalite,
            dataType: 'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    option += '<option value="'+value.code_localite+'">'+value.lib_localite+'</option>';
                });
                $("#"+selectId).html(option);
            },
            error: function() {
                console.log('Erreur lors du chargement des arrondissements');
            }
        });
    }

    function getQuartierVillage(codeLocalite, selectId) {
        var route = "{{ route('declarationNaissance.search.quartier') }}";
        var option = "<option value=''>Choisir</option>";

        $.ajax({
            url: route,
            data: 'id=' + codeLocalite,
            dataType: 'json',
            success: function(json) {
                $.each(json, function (index, value) {
                    option += '<option value="'+value.code_localite+'">'+value.lib_localite+'</option>';
                });
                $("#"+selectId).html(option);
            },
            error: function() {
                console.log('Erreur lors du chargement des quartiers');
            }
        });
    }

    // ========== ADRESSE ÉPOUX ==========
    $('#domicile_pays_epoux').on('change', function () {
        var pays = $('#domicile_pays_epoux').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_epoux').removeClass('d-none');
            $('div.autredomicile_ville_epoux').addClass('d-none');
            $('div.domicile_arrondissement_epoux').addClass('d-none');
            $('div.domicile_quartier_epoux').addClass('d-none');

            $('#domicile_ville_epoux').prop('disabled', false);
            $('#domicile_arrondissement_epoux').prop('disabled', true);
            $('#domicile_quartier_epoux').prop('disabled', true);
            $('#autredomicile_ville_epoux').prop('disabled', true);

        } else {
            $("div.domicile_ville_epoux").addClass("d-none");
            $("div.domicile_arrondissement_epoux").addClass("d-none");
            $("div.domicile_quartier_epoux").addClass('d-none');
            $('#domicile_ville_epoux').prop('disabled', true);
            $('#domicile_arrondissement_epoux').prop('disabled', true);
            $('#domicile_quartier_epoux').prop('disabled', true);

            $('div.autredomicile_ville_epoux').removeClass('d-none');
            $('#autredomicile_ville_epoux').prop('disabled', false);
        }
    });

    $("#domicile_ville_epoux").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_arrondissement_epoux").removeClass("d-none");
            $('#domicile_arrondissement_epoux').prop('disabled', false);

            getArrComUrbaine(localiteParent, 'domicile_arrondissement_epoux');
        }
    });

    $("#domicile_arrondissement_epoux").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_quartier_epoux").removeClass('d-none');
            $('#domicile_quartier_epoux').prop('disabled', false);

            getQuartierVillage(localiteParent, 'domicile_quartier_epoux');
        }
    });

    // ========== ADRESSE ÉPOUSE ==========
    // Initialisation par défaut : champs désactivés
    $(document).ready(function(){
        $(".adresseepouse select, .adresseepouse input").prop('disabled', true);
        $("#otheradress").prop('checked', true); // Par défaut "NON" sélectionné
        $(".adresseepouse select, .adresseepouse input").prop('disabled', false);
    });

    // Gestion du radio "Même adresse que l'époux"
    $("input[name='adressepouse']").on("change", function(){
        if($("#sameadress").is(':checked')){
            // OUI - Même adresse que l'époux
            // Copier les valeurs de l'époux vers l'épouse
            $("#domicile_pays_epouse").val($("#domicile_pays_epoux").val());
            $("#domicile_ville_epouse").val($("#domicile_ville_epoux").val());
            $("#domicile_arrondissement_epouse").val($("#domicile_arrondissement_epoux").val());
            $("#domicile_quartier_epouse").val($("#domicile_quartier_epoux").val());
            $("#domicile_typevoie_epouse").val($("#domicile_typevoie_epoux").val());
            $("#domicile_numero_epouse").val($("#domicile_numero_epoux").val());
            $("#domicile_nomvoie_epouse").val($("#domicile_nomvoie_epoux").val());

            // Désactiver tous les champs d'adresse de l'épouse
            $(".adresseepouse select, .adresseepouse input").prop('disabled', true);

            // Masquer les divs de cascade géographique
            $('div.autredomicile_ville_epouse').addClass('d-none');
            $('div.domicile_arrondissement_epouse').addClass('d-none');
            $('div.domicile_quartier_epouse').addClass('d-none');

        } else if($("#otheradress").is(':checked')) {
            // NON - Adresse différente
            // Activer tous les champs d'adresse de l'épouse
            $(".adresseepouse select, .adresseepouse input").prop('disabled', false);

            // Réinitialiser les valeurs
            $("#domicile_pays_epouse").val("");
            $("#domicile_ville_epouse").val("");
            $("#domicile_arrondissement_epouse").html('<option value="">Choisir</option>');
            $("#domicile_quartier_epouse").html('<option value="">Choisir</option>');
            $("#domicile_typevoie_epouse").val("");
            $("#domicile_numero_epouse").val("");
            $("#domicile_nomvoie_epouse").val("");

            // Masquer les divs de cascade géographique
            $('div.autredomicile_ville_epouse').addClass('d-none');
            $('div.domicile_arrondissement_epouse').addClass('d-none');
            $('div.domicile_quartier_epouse').addClass('d-none');
        }
    });

    $('#domicile_pays_epouse').on('change', function () {
        if($("#otheradress").is(':checked')) {
            var pays = $('#domicile_pays_epouse').val();
            if (pays == 'Congo') {
                $('div.domicile_ville_epouse').removeClass('d-none');
                $('div.autredomicile_ville_epouse').addClass('d-none');
                $('div.domicile_arrondissement_epouse').addClass('d-none');
                $('div.domicile_quartier_epouse').addClass('d-none');

                $('#domicile_ville_epouse').prop('disabled', false);
                $('#domicile_arrondissement_epouse').prop('disabled', true);
                $('#domicile_quartier_epouse').prop('disabled', true);
                $('#autredomicile_ville_epouse').prop('disabled', true);

            } else {
                $("div.domicile_ville_epouse").addClass("d-none");
                $("div.domicile_arrondissement_epouse").addClass("d-none");
                $("div.domicile_quartier_epouse").addClass('d-none');
                $('#domicile_ville_epouse').prop('disabled', true);
                $('#domicile_arrondissement_epouse').prop('disabled', true);
                $('#domicile_quartier_epouse').prop('disabled', true);

                $('div.autredomicile_ville_epouse').removeClass('d-none');
                $('#autredomicile_ville_epouse').prop('disabled', false);
            }
        }
    });

    $("#domicile_ville_epouse").on("change", function(){
        if($("#otheradress").is(':checked')) {
            var localiteParent = $(this).val();

            if(localiteParent != "" && localiteParent != null){
                $("div.domicile_arrondissement_epouse").removeClass("d-none");
                $('#domicile_arrondissement_epouse').prop('disabled', false);

                getArrComUrbaine(localiteParent, 'domicile_arrondissement_epouse');
            }
        }
    });

    $("#domicile_arrondissement_epouse").on("change", function(){
        if($("#otheradress").is(':checked')) {
            var localiteParent = $(this).val();
            if(localiteParent != "" && localiteParent != null){
                $("div.domicile_quartier_epouse").removeClass('d-none');
                $('#domicile_quartier_epouse').prop('disabled', false);

                getQuartierVillage(localiteParent, 'domicile_quartier_epouse');
            }
        }
    });

    // ========== ADRESSE TÉMOINS ÉPOUX ==========
    $('#domicile_pays_temoins_epoux').on('change', function () {
        var pays = $('#domicile_pays_temoins_epoux').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_temoins_epoux').removeClass('d-none');
            $('div.autredomicile_ville_temoins_epoux').addClass('d-none');
            $('div.domicile_arrondissement_temoins_epoux').addClass('d-none');
            $('div.domicile_quartier_temoins_epoux').addClass('d-none');

            $('#domicile_ville_temoins_epoux').prop('disabled', false);
            $('#domicile_arrondissement_temoins_epoux').prop('disabled', true);
            $('#domicile_quartier_temoins_epoux').prop('disabled', true);
            $('#autredomicile_ville_temoins_epoux').prop('disabled', true);

        } else {
            $("div.domicile_ville_temoins_epoux").addClass("d-none");
            $("div.domicile_arrondissement_temoins_epoux").addClass("d-none");
            $("div.domicile_quartier_temoins_epoux").addClass('d-none');
            $('#domicile_ville_temoins_epoux').prop('disabled', true);
            $('#domicile_arrondissement_temoins_epoux').prop('disabled', true);
            $('#domicile_quartier_temoins_epoux').prop('disabled', true);

            $('div.autredomicile_ville_temoins_epoux').removeClass('d-none');
            $('#autredomicile_ville_temoins_epoux').prop('disabled', false);
        }
    });

    $("#domicile_ville_temoins_epoux").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_arrondissement_temoins_epoux").removeClass("d-none");
            $('#domicile_arrondissement_temoins_epoux').prop('disabled', false);

            getArrComUrbaine(localiteParent, 'domicile_arrondissement_temoins_epoux');
        }
    });

    $("#domicile_arrondissement_temoins_epoux").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_quartier_temoins_epoux").removeClass('d-none');
            $('#domicile_quartier_temoins_epoux').prop('disabled', false);

            getQuartierVillage(localiteParent, 'domicile_quartier_temoins_epoux');
        }
    });

    // ========== ADRESSE TÉMOINS ÉPOUSE ==========
    $('#domicile_pays_temoins_epouse').on('change', function () {
        var pays = $('#domicile_pays_temoins_epouse').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_temoins_epouse').removeClass('d-none');
            $('div.autredomicile_ville_temoins_epouse').addClass('d-none');
            $('div.domicile_arrondissement_temoins_epouse').addClass('d-none');
            $('div.domicile_quartier_temoins_epouse').addClass('d-none');

            $('#domicile_ville_temoins_epouse').prop('disabled', false);
            $('#domicile_arrondissement_temoins_epouse').prop('disabled', true);
            $('#domicile_quartier_temoins_epouse').prop('disabled', true);
            $('#autredomicile_ville_temoins_epouse').prop('disabled', true);

        } else {
            $("div.domicile_ville_temoins_epouse").addClass("d-none");
            $("div.domicile_arrondissement_temoins_epouse").addClass("d-none");
            $("div.domicile_quartier_temoins_epouse").addClass('d-none');
            $('#domicile_ville_temoins_epouse').prop('disabled', true);
            $('#domicile_arrondissement_temoins_epouse').prop('disabled', true);
            $('#domicile_quartier_temoins_epouse').prop('disabled', true);

            $('div.autredomicile_ville_temoins_epouse').removeClass('d-none');
            $('#autredomicile_ville_temoins_epouse').prop('disabled', false);
        }
    });

    $("#domicile_ville_temoins_epouse").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_arrondissement_temoins_epouse").removeClass("d-none");
            $('#domicile_arrondissement_temoins_epouse').prop('disabled', false);

            getArrComUrbaine(localiteParent, 'domicile_arrondissement_temoins_epouse');
        }
    });

    $("#domicile_arrondissement_temoins_epouse").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_quartier_temoins_epouse").removeClass('d-none');
            $('#domicile_quartier_temoins_epouse').prop('disabled', false);

            getQuartierVillage(localiteParent, 'domicile_quartier_temoins_epouse');
        }
    });

    // ========== VALIDATION DES DATES D'ACTES DE NAISSANCE ==========

    // Fonction pour valider que la date d'édition >= date de naissance
    function validateDateEditionActe(dateNaissance, dateEdition, notificationId) {
        if (dateNaissance && dateEdition) {
            var naissance = new Date(dateNaissance);
            var edition = new Date(dateEdition);

            if (edition < naissance) {
                $("#" + notificationId).show();
                return false;
            } else {
                $("#" + notificationId).hide();
                return true;
            }
        }
        return true;
    }

    // Validation pour l'époux
    $("#date_naissance_epoux, #date_emission_acte_naissance_epoux").on("change blur", function(){
        var dateNaissance = $("#date_naissance_epoux").val();
        var dateEdition = $("#date_emission_acte_naissance_epoux").val();
        validateDateEditionActe(dateNaissance, dateEdition, "notificationDateActeEpoux");
    });

    // Validation pour l'épouse
    $("#date_naissance_epouse, #date_emission_acte_naissance_epouse").on("change blur", function(){
        var dateNaissance = $("#date_naissance_epouse").val();
        var dateEdition = $("#date_emission_acte_naissance_epouse").val();
        validateDateEditionActe(dateNaissance, dateEdition, "notificationDateActeEpouse");
    });

    // ========== ADRESSE CÉRÉMONIE ==========
    // Gestion de l'affichage de l'adresse détaillée selon le lieu de cérémonie
    $("#lieu_ceremonie_mariage").on("change", function(){
        var lieuCeremonie = $(this).val();

        // Si le lieu nécessite une adresse détaillée (domicile privé par exemple)
        if(lieuCeremonie == "Domicile" || lieuCeremonie == "Autre lieu privé") {
            $(".adresse_ceremonie_details").removeClass("d-none");
            $("#domicile_pays_ceremonie").val("Congo").trigger('change'); // Par défaut Congo
        } else {
            $(".adresse_ceremonie_details").addClass("d-none");
            // Réinitialiser les champs
            $("#domicile_pays_ceremonie").val("");
            $("#domicile_ville_ceremonie").val("");
            $("#domicile_arrondissement_ceremonie").html('<option value="">Choisir</option>');
            $("#domicile_quartier_ceremonie").html('<option value="">Choisir</option>');
        }
    });

    $('#domicile_pays_ceremonie').on('change', function () {
        var pays = $('#domicile_pays_ceremonie').val();
        if (pays == 'Congo') {
            $('div.domicile_ville_ceremonie').removeClass('d-none');
            $('div.autredomicile_ville_ceremonie').addClass('d-none');
            $('div.domicile_arrondissement_ceremonie').addClass('d-none');
            $('div.domicile_quartier_ceremonie').addClass('d-none');

            $('#domicile_ville_ceremonie').prop('disabled', false);
            $('#domicile_arrondissement_ceremonie').prop('disabled', true);
            $('#domicile_quartier_ceremonie').prop('disabled', true);
            $('#autredomicile_ville_ceremonie').prop('disabled', true);

        } else {
            $("div.domicile_ville_ceremonie").addClass("d-none");
            $("div.domicile_arrondissement_ceremonie").addClass("d-none");
            $("div.domicile_quartier_ceremonie").addClass('d-none');
            $('#domicile_ville_ceremonie').prop('disabled', true);
            $('#domicile_arrondissement_ceremonie').prop('disabled', true);
            $('#domicile_quartier_ceremonie').prop('disabled', true);

            $('div.autredomicile_ville_ceremonie').removeClass('d-none');
            $('#autredomicile_ville_ceremonie').prop('disabled', false);
        }
    });

    $("#domicile_ville_ceremonie").on("change", function(){
        var localiteParent = $(this).val();

        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_arrondissement_ceremonie").removeClass("d-none");
            $('#domicile_arrondissement_ceremonie').prop('disabled', false);

            getArrComUrbaine(localiteParent, 'domicile_arrondissement_ceremonie');
        }
    });

    $("#domicile_arrondissement_ceremonie").on("change", function(){
        var localiteParent = $(this).val();
        if(localiteParent != "" && localiteParent != null){
            $("div.domicile_quartier_ceremonie").removeClass('d-none');
            $('#domicile_quartier_ceremonie').prop('disabled', false);

            getQuartierVillage(localiteParent, 'domicile_quartier_ceremonie');
        }
    });

    }); // Fermeture du $(document).ready()

</script>
