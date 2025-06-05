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

    $(document).ready(function(){
        //dissimulation par défaut de la notification épouse mineure
        $("#notificationEpouseMineure").hide();
        $("#notificationEpouxMineure").hide();
        $("#notificationPreMariage").hide();
    });
    // var nom_enfant = $("#nom_enfant");

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

         // VERIFICATION NOMBRE D'ENFANTS
         $("#nombre_enfant").on('change', function(){
            var nombre = parseInt($(this).val());

            hideEnfant();
            switch (nombre) {
                case 0:
                    hideEnfant();
                    break;
                case 1:
                    $('#enfant1').show(true);

                    break;
                case 2:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    break;
                case 3:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    break;
                case 4:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    break;
                case 5:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    $('#enfant5').show(true);
                    break;
                case 6:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    $('#enfant5').show(true);
                    $('#enfant6').show(true);
                    break;
                case 7:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    $('#enfant5').show(true);
                    $('#enfant6').show(true);
                    $('#enfant7').show(true);
                    break;
                case 8:
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    $('#enfant5').show(true);
                    $('#enfant6').show(true);
                    $('#enfant7').show(true);
                    $('#enfant8').show(true);
                    break;
                case 9:
                    // alert('Ok');
                    $('#enfant1').show(true);
                    $('#enfant2').show(true);
                    $('#enfant3').show(true);
                    $('#enfant4').show(true);
                    $('#enfant5').show(true);
                    $('#enfant6').show(true);
                    $('#enfant7').show(true);
                    $('#enfant8').show(true);
                    $('#enfant9').show(true);
                    break;
                default:
                hideEnfant();
                    break;
            }
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
    //indertion enfant dans le tableau
    function insertEnfant(nom,prenom,sexe,dateNais,lieuNais){
        enfants.push({
            nom: nom,
            prenom: prenom,
            sexe: sexe,
            date_naissance: dateNais,
            lieu_naissance: lieuNais
        });
    }
    //GESTION IDENTIFICATION ENFANT
    function hideEnfant(){
        $('#enfant1').hide(true);
        $('#enfant2').hide(true);
        $('#enfant3').hide(true);
        $('#enfant4').hide(true);
        $('#enfant5').hide(true);
        $('#enfant6').hide(true);
        $('#enfant7').hide(true);
        $('#enfant8').hide(true);
        $('#enfant9').hide(true);
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

        for (var index = 1; nombre_enfant >= index; index++) {
            nom = nom+''+index;
            prenom = prenom+''+index;
            sexe = sexe+''+index;
            date_naissance = date_naissance+''+index;
            lieu_naissance = lieu_naissance+''+index;

            nom = $("#nom"+index+"").val();
            prenom = $("#prenom"+index+"").val();
            sexe = $("#sexe"+index+"").val();
            date_naissance = $("#datenaiss"+index+"").val();
            lieu_naissance = $("#lieunaiss"+index+"").val();

            insertEnfant(nom,prenom,sexe,date_naissance,lieu_naissance);
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

        var domicile_pays_epouse = "";
        var domicile_ville_epouse = "";
        var domicile_arrondissement_epouse = "";
        var domicile_quartier_epouse = "";
        var domicile_typevoie_epouse = "";
        var domicile_nomvoie_epouse = "";
        var domicile_numero_epouse = "";

        //fin epoux
        //POUR LES MEMES ADRESSES
        var sameadress = $("#sameadress").val();
        var otheradress = $("#otheradress").val();


        if(sameadress == 1){
             //adresse épouse
           domicile_pays_epouse = $("#domicile_pays_epoux");
           domicile_pays_epouse = $("#domicile_pays_epoux");
           domicile_ville_epouse = $("#domicile_ville_epoux");
           domicile_arrondissement_epouse = $("#domicile_arrondissement_epoux");
           domicile_quartier_epouse = $("#domicile_quartier_epoux");
           domicile_typevoie_epouse = $("#domicile_typevoie_epoux");
           domicile_nomvoie_epouse = $("#domicile_nomvoie_epoux");
           domicile_numero_epouse = $("#domicile_numero_epoux");
            //fin adresse epouse
        }
        if(otheradress == 1){
           //adresse épouse
           domicile_pays_epouse = $("#domicile_pays_epouse");
           domicile_pays_epouse = $("#domicile_pays_epouse");
           domicile_ville_epouse = $("#domicile_ville_epouse");
           domicile_arrondissement_epouse = $("#domicile_arrondissement_epouse");
           domicile_quartier_epouse = $("#domicile_quartier_epouse");
           domicile_typevoie_epouse = $("#domicile_typevoie_epouse");
           domicile_nomvoie_epouse = $("#domicile_nomvoie_epouse");
           domicile_numero_epouse = $("#domicile_numero_epouse");
            //fin adresse epouse
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

        var lib_quartier_ceremonie = $("#lib_quartier_ceremonie");
        var lib_village_ceremonie = $("#lib_village_ceremonie");
        var domicile_ville_ceremonie = $("#domicile_ville_ceremonie");
        var domicile_arrondissement_ceremonie = $("#domicile_arrondissement_ceremonie");
        var domicile_ceremonie = $("#domicile_ceremonie");
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
                domicile_pays_epouse :domicile_pays_epouse.val(),
                domicile_ville_epouse :domicile_ville_epouse.val(),
                domicile_quartier_epouse :domicile_quartier_epouse.val(),
                domicile_arrondissement_epouse :domicile_arrondissement_epouse.val(),
                domicile_typevoie_epouse :domicile_typevoie_epouse.val(),
                domicile_nomvoie_epouse :domicile_nomvoie_epouse.val(),
                domicile_numero_epouse :domicile_numero_epouse.val(),
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
                lib_quartier_ceremonie :lib_quartier_ceremonie.val(),
                lib_village_ceremonie :lib_village_ceremonie.val(),
                domicile_ville_ceremonie :domicile_ville_ceremonie.val(),
                domicile_arrondissement_ceremonie :domicile_arrondissement_ceremonie.val(),
                domicile_ceremonie :domicile_ceremonie.val(),
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

            if(response.code == "200"){
                flashAlert("Opération réussie","success",response.message);
                var url = "{{ route('declarationMariage.index') }}";
                setTimeout(() => {
                    window.open(url);
                }, 4000);
            }else{
                var outString = "<ul>";
                    for (const [key, value] of Object.entries(response.message)) {
                        outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                }
                outString += "</ul>";
                flashAlert("Une erreur est suvernue","error",outString);
            }
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
</script>
