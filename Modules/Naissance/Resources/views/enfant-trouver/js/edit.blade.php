<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs4de6.kxcdn.com/print.min.css" />

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
                        $("#domicile_rue_pere").val(rue);
                        $("#domicile_quartier_pere").val(quartier);
                        if($(this).data('arrondissement')===null)
                        {
                          $("#domicile_arrondissement_pere").val("choisissez");
                        }else{
                            $("#domicile_arrondissement_pere").val(arrondissement);
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
                        document.getElementById('statut_personne_pere').disabled = true;


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
                          $("#domicile_district_mere").val("choisissez");
                        }else{
                            $("#domicile_district_mere").val(arrondissement);
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


                        $("#nom_declarant").val(nom);
                        $("#prenom_declarant").val(prenom);
                        $("#date_naissance_declarant").val(date_naissance);
                        $("#sexe_declarant").val(sexe);
                        $("#email_declarant").val(sexe);
                        $("#domicile_numero_declarant").val(numero);
                        $("#domicile_nomvoie_declarant").val(rue);
                        $("#domicile_quartier_declarant").val(quartier);
                        if($(this).data('arrondissement')===null)
                        {
                          $("#domicile_district_declarant").val("choisissez");
                        }else{
                            $("#domicile_district_declarant").val(arrondissement);
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

                }
            });
    });
</script>
<script>
    $(document).ready(function()
    {
        $('#departementcongo_pere').hide();
        $('#domicile_pays_pere').on('change', function () {
            var pays = $('#domicile_pays_pere').val();
            if (pays == 'Congo') {
                $('#departementcongo_pere').show();
                $('#autredepartement_pere').hide();
            } else {
                $('#departementcongo_pere').hide();
                $('#autredepartement_pere').show();
            }
        });

        $('#departementcongo_mere').hide();
        $('#domicile_pays_mere').on('change', function () {
            var pays = $('#domicile_pays_mere').val();
            if (pays == 'Congo') {
                $('#departementcongo_mere').show();
                $('#autredepartement_mere').hide();
            } else {
                $('#departementcongo_mere').hide();
                $('#autredepartement_mere').show();
            }
        });

        $('#departementcongo_declarant').hide();
        $('#domicile_pays_declarant').on('change', function () {
            var pays = $('#domicile_pays_declarant').val();
            if (pays == 'Congo') {
                $('#departementcongo_declarant').show();
                $('#autredepartement_declarant').hide();
            } else {
                $('#departementcongo_declarant').hide();
                $('#autredepartement_declarant').show();
            }
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
            $('#domicile_district_pere').val("");


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
            $('#domicile_district_mere').val("");


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

                $('#domicile_quartier_declarant').val($('#domicile_quartier_pere').val());
                document.getElementById('domicile_quartier_declarant').readOnly = true;

                // $("#domicile_pays_pere option:selected").text();
                // document.getElementById('sexe_declarant').disabled = true;

                var domicile_pays_declarant = $("#domicile_pays_declarant");
                var domicile_pays_pere = $("#domicile_pays_pere");
                domicile_pays_declarant.val(domicile_pays_pere.val());
                document.getElementById('domicile_pays_declarant').disabled = true;


                $('#numero_document_declarant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_pere').val());

              //  alert($('#type_date_naissance_pere').val());
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


                //var filiation = $("#filiation");
                  //  filiation.val("FIL_0001");
                //$("#filiation option:selected").text();
                //document.getElementById('filiation').disabled = true;


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


                var domicile_district_declarant = $("#domicile_district_declarant");
                var domicile_district_pere = $("#domicile_district_pere");
                domicile_district_declarant.val(domicile_district_pere.val());
                document.getElementById('domicile_district_declarant').disabled = true;

                var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
                var domicile_typevoie_pere = $("#domicile_typevoie_pere");
                domicile_typevoie_declarant.val(domicile_typevoie_pere.val());
                document.getElementById('domicile_typevoie_declarant').disabled = true;

                // $('#domicile_ville_declarant').val($('#domicile_ville_pere').val());
                // document.getElementById('domicile_ville_declarant').readOnly = true;

                var domicile_ville_declarant = $("#domicile_ville_declarant");
                var domicile_ville_pere = $("#domicile_ville_pere");
                domicile_ville_declarant.val(domicile_ville_pere.val());
                document.getElementById('domicile_ville_declarant').disabled = true;

                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_declarant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var domicile_pays_declarant = $("#domicile_pays_declarant");
                var domicile_pays_pere = $("#domicile_pays_pere");
                domicile_pays_declarant.val(domicile_pays_pere.val());
                document.getElementById('domicile_pays_declarant').disabled = true;

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

                $('#domicile_quartier_declarant').val($('#domicile_quartier_pere').val());
                document.getElementById('domicile_quartier_declarant').readOnly = true;



                $('#numero_document_declarant').val($('#numero_document_pere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_pere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_pere').val());

              //  alert($('#type_date_naissance_pere').val());
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

                //var filiation = $("#filiation");
                  //  filiation.val("FIL_0001");
                //$("#filiation option:selected").text();
                //document.getElementById('filiation').disabled = true;


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


                var domicile_district_declarant = $("#domicile_district_declarant");
                var domicile_district_pere = $("#domicile_district_pere");
                domicile_district_declarant.val(domicile_district_pere.val());
                document.getElementById('domicile_district_declarant').disabled = true;


                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_pere = $("#niveau_instruction_pere");
                niveau_instruction_declarant.val(niveau_instruction_pere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var code_pays_declarant = $("#code_pays_declarant");
                var code_pays_pere = $("#code_pays_pere");
                code_pays_declarant.val(code_pays_pere.val());
                document.getElementById('code_pays_declarant').disabled = true;
            }



             if (($(this).val() === 'mere')&&($('#statut_personne_mere').val()==="VIVANT"))
            {

                document.getElementById('search_declarant').style.visibility = 'hidden';
                //Traitement input
                document.getElementById('hide_pere').style.visibility = 'visible';
                document.getElementById('search_declarant').style.visibility = 'hidden';

                $('#nom_declarant').val($('#nom_mere').val());
                document.getElementById('nom_declarant').readOnly = true;

                $('#prenom_declarant').val($('#prenom_mere').val());
                document.getElementById('prenom_declarant').readOnly = true;

                $('#email_declarant').val($('#email_mere').val());
                document.getElementById('email_declarant').readOnly = true;

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

                $('#domicile_quartier_declarant').val($('#domicile_quartier_mere').val());
                document.getElementById('domicile_quartier_declarant').readOnly = true;


                $('#numero_document_declarant').val($('#numero_document_mere').val());
                document.getElementById('numero_document_declarant').readOnly = true;

                $('#statut_personne_declarant').val($('#statut_personne_mere').val());

                $('#type_date_naissance_declarant').val($('#type_date_naissance_mere').val());

              //  alert($('#type_date_naissance_mere').val());
                if($('#type_date_naissance_mere').val()==="EXACTE")
                {
                    document.getElementById('type_date_naissance_declarant').checked="";
                }else{
                    document.getElementById('type_date_naissance_declarant').checked="ESTIME";
                }

                document.getElementById('type_date_naissance_declarant').disabled = true;
                document.getElementById('statut_personne_declarant').disabled = true;


                //traitement select
                var sexe_declarant = $("#sexe_declarant");
                    sexe_declarant.val("F");
                $("#sexe_declarant option:selected").text();
                document.getElementById('sexe_declarant').disabled = true;

                var filiation = $("#filiation");
                filiation.val("FIL_0002");
                $("#filiation option:selected").text();
                document.getElementById('filiation').disabled = true;

                //var filiation = $("#filiation");
                  //  filiation.val("FIL_0001");
                //$("#filiation option:selected").text();
                //document.getElementById('filiation').disabled = true;


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


                var domicile_district_declarant = $("#domicile_district_declarant");
                var domicile_district_mere = $("#domicile_district_mere");
                domicile_district_declarant.val(domicile_district_mere.val());
                document.getElementById('domicile_district_declarant').disabled = true;


                var niveau_instruction_declarant = $("#niveau_instruction_declarant");
                var niveau_instruction_mere = $("#niveau_instruction_mere");
                niveau_instruction_declarant.val(niveau_instruction_mere.val());
                document.getElementById('niveau_instruction_declarant').disabled = true;

                var code_pays_declarant = $("#code_pays_declarant");
                var code_pays_mere = $("#code_pays_mere");
                code_pays_declarant.val(code_pays_mere.val());
                document.getElementById('code_pays_declarant').disabled = true;

            }
            if (($(this).val() === 'autre'))
            {
                document.getElementById('search_declarant').style.visibility = 'visible';

                $('#nom_declarant').val("");
                document.getElementById('nom_declarant').readOnly = false;

                $('#prenom_declarant').val("");
                document.getElementById('prenom_declarant').readOnly = false;

                $('#email_declarant').val("");
                document.getElementById('email_declarant').readOnly = false;

                $('#date_naissance_declarant').val("");
                document.getElementById('date_naissance_declarant').readOnly = false;

                $('#lieu_naissance_declarant').val("");
                document.getElementById('lieu_naissance_declarant').readOnly = false;

                $('#code_pays_declarant').val("");
                document.getElementById('code_pays_declarant').disabled = false;

                $('#telephone_declarant').val("");
                document.getElementById('telephone_declarant').readOnly = false;

                $('#domicile_numero_declarant').val("");
                document.getElementById('domicile_numero_declarant').readOnly = false;

                $('#domicile_nomvoie_declarant').val("");
                document.getElementById('domicile_nomvoie_declarant').readOnly = false;


                $('#domicile_quartier_declarant').val("");
                document.getElementById('domicile_quartier_declarant').readOnly = false;

                $('#domicile_district_declarant').val("");
                document.getElementById('domicile_district_declarant').disabled = false;


                 var profession_declarant = $("#profession_declarant");
                     profession_declarant.val("");
                     $("#profession_declarant option:selected").text();
                     document.getElementById('profession_declarant').disabled = false;

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
                sexe_declarant.val("F");
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

            }

        });

    });
</script>


<script>


    var nom_enfant = $("#nom_enfant");

    var form = $(".validation-wizard").show();

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Enrégistrer"
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
                nom_enfant:{
                    required: "Veuillez saisir le nom",
                    },
                sexe_enfant:{
                        required: "Veuillez selectionner le sexe",
                        },
                date_naissance_enfant:{
                    required: "Veuillez selectionner une date",
                },
                lieu_naissance_enfant:{
                    required: "Veuillez saisir le lieu de naissance",
                },
                heure_naissance_enfant:{
                    required: "Veuillez saisir selectionner une heure",
                 },
                 heure_naissance_enfant:{
                    required: "Veuillez saisir selectionner une heure",
                 },
                 code_situation_matrimoniale:{
                    required: "Veuillez selectionner un element",
                 },
                 nombre_enfants:{
                        required: "Veuillez saisir le nombre des enfants",
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

    })
</script>

<script>
    function soumission(){
        // informations du père
        var nom_pere = $("#nom_pere");
        var prenom_pere = $("#prenom_pere");
        var date_naissance_pere = $("#date_naissance_pere");
        var lieu_naissance_pere = $("#lieu_naissance_pere");
        var email_pere = $("#email_pere");
        var code_pays_pere = $("#code_pays_pere");
        var telephone_pere = $("#telephone_pere");
        // var domicile_numero_pere = $("#domicile_numero_pere");
        // var domicile_rue_pere = $("#domicile_rue_pere");
        // var domicile_quartier_pere = $("#domicile_quartier_pere");
        // var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
        // FIN SETH CODE AJOUTE
        var telephone_pere = $("#telephone_pere");
        var profession_pere = $("#profession_pere");
        var code_nationalite_pere = $("#code_nationalite_pere");
        var niveau_instruction_pere = $("#niveau_instruction_pere");
        var code_type_document_pere = $("#code_type_document_pere");
        var numero_document_pere = $("#numero_document_pere");
        var formation_sanitaire_naissance = $("#formation_sanitaire_naissance");


        //information mere
        var nom_mere = $("#nom_mere");
        var prenom_mere = $("#prenom_mere");
        var date_naissance_mere = $("#date_naissance_mere");
        var lieu_naissance_mere = $("#lieu_naissance_mere");
        var code_pays_mere = $("#code_pays_mere");
        var telephone_mere = $("#telephone_mere");
        var email_mere = $("#email_mere");
        // var domicile_numero_mere = $("#domicile_numero_mere");
        // var domicile_nomvoie_mere = $("#domicile_nomvoie_mere");
        // var domicile_quartier_mere = $("#domicile_quartier_mere");
        // var domicile_district_mere = $("#domicile_district_mere");
        var profession_mere = $("#profession_mere");
        var code_nationalite_mere = $("#code_nationalite_mere");
        var niveau_instruction_mere = $("#niveau_instruction_mere");
        var code_type_document_mere = $("#code_type_document_mere");
        var numero_document_mere = $("#numero_document_mere");

        //déclarant
        var nom_declarant = $("#nom_declarant");
        var prenom_declarant = $("#prenom_declarant");
        var date_naissance_declarant = $("#date_naissance_declarant");
        var lieu_naissance_declarant = $("#lieu_naissance_declarant");
        var code_pays_declarant = $("#code_pays_declarant");
        var telephone_declarant = $("#telephone_declarant");
        // var domicile_numero_declarant = $("#domicile_numero_declarant");
        // var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");
        // var domicile_quartier_declarant = $("#domicile_quartier_declarant");
        // var domicile_district_declarant = $("#domicile_district_declarant");
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

        // enfant
        var nom_enfant = $("#nom_enfant");
        var prenom_enfant = $("#prenom_enfant");
        var date_naissance_enfant = $("#date_naissance_enfant");
        var lieu_naissance_enfant = $("#lieu_naissance_enfant");
        var code_situation_matrimoniale = $("#code_situation_matrimoniale");
        var lieu_survenance = $("#code_lieu_survenance");

        //var code_nationalite_enfant = $("#code_nationalite_enfant");
        var sexe_enfant = $("#sexe_enfant");
        var heure_naissance_enfant = $("#heure_naissance_enfant");
        var nombre_enfants = $("#nombre_enfants");

        var type_date_naissance_mere = $("#type_date_naissance_mere");
        var statut_personne_mere = $("#statut_personne_mere");

        var type_date_naissance_pere = $("#type_date_naissance_pere");
        var statut_personne_pere = $("#statut_personne_pere");

        var type_date_naissance_declarant = $("#type_date_naissance_declarant");
        var statut_personne_declarant = $("#statut_personne_declarant");
        var _method = $("input[name='_method']").val();

        var domicile_pays_pere = $("#domicile_pays_pere");
        var domicile_ville_pere = $("#domicile_ville_pere");
        var domicile_district_pere = $("#domicile_district_pere");
        var domicile_quartier_pere = $("#domicile_quartier_pere");
        var domicile_typevoie_pere = $("#domicile_typevoie_pere");
        var domicile_numero_pere = $("#domicile_numero_pere");
        var domicile_nomvoie_pere = $("#domicile_nomvoie_pere");

        var domicile_pays_mere = $("#domicile_pays_mere");
        var domicile_ville_mere = $("#domicile_ville_mere");
        var domicile_district_mere = $("#domicile_district_mere");
        var domicile_quartier_mere = $("#domicile_quartier_mere");
        var domicile_typevoie_mere = $("#domicile_typevoie_mere");
        var domicile_numero_mere = $("#domicile_numero_mere");
        var domicile_nomvoie_mere = $("#domicile_nomvoie_mere");

        var domicile_pays_declarant = $("#domicile_pays_declarant");
        var domicile_ville_declarant = $("#domicile_ville_declarant");
        var domicile_district_declarant = $("#domicile_district_declarant");
        var domicile_quartier_declarant = $("#domicile_quartier_declarant");
        var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
        var domicile_numero_declarant = $("#domicile_numero_declarant");
        var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");
        var date_heure_declaration = $("#date_heure_declaration");

        //champs obligatoires
        var champs = [nom_pere,
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

                        code_pays_declarant,
                        telephone_declarant,

                        sexe_declarant,
                        nom_enfant,
                        sexe_enfant,
                        date_naissance_enfant,
                        lieu_naissance_enfant,
                        lieu_survenance,
                        code_situation_matrimoniale,
                        heure_naissance_enfant,
                        code_type_document_declarant,
                        numero_document_declarant,
                         /* type_date_naissance_mere,
                           statut_personne_mere,
                           type_date_naissance_pere,
                           statut_personne_pere,
                           type_date_naissance_declarant,
                           statut_personne_declarant*/
                        ];

        // var champsVides = [];

        // for(var i = 0; i < champs.length; i++)
        // {
        //     if(champs[i].val() == "" || champs[i].val() == null)
        //     {
        //         champsVides.push(champs[i]);
        //     }

        // }

        //vérification des champs vides
        // for(var i = 0; i < champsVides.length; i++)
        // {
        //     champsVides[i].addClass("is-invalid");
        // }

        //si un champ obligatoire est null ou vide alors il ne passe pas à l'étape suivante
        // if(champsVides.length > 0)
        // {
        //     return false;
        // }

        // alert(champs);

        // alert(champsVides.length);

        var data =
        {
            // données du père
            nom_pere:nom_pere.val(),
            prenom_pere:prenom_pere.val(),
            date_naissance_pere:date_naissance_pere.val(),
            lieu_naissance_pere:lieu_naissance_pere.val(),

            profession_pere:profession_pere.val(),
            code_nationalite_pere:code_nationalite_pere.val(),
            niveau_instruction_pere:niveau_instruction_pere.val(),
            // SETH CODE AJOUTE
            code_pays_pere:code_pays_pere.val(),
            // SETH CODE AJOUTE
            telephone_pere:telephone_pere.val(),
            code_type_document_pere:code_type_document_pere.val(),
            numero_document_pere:numero_document_pere.val(),
             // données de la mère
            nom_mere:nom_mere.val(),
            prenom_mere:prenom_mere.val(),
            date_naissance_mere:date_naissance_mere.val(),
            lieu_naissance_mere:lieu_naissance_mere.val(),

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

            profession_declarant:profession_declarant.val(),
            code_nationalite_declarant:code_nationalite_declarant.val(),
            niveau_instruction_declarant:niveau_instruction_declarant.val(),

            filiation:filiation.val(),

            // SETH CODE AJOUTE
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
            code_situation_matrimoniale:code_situation_matrimoniale.val(),
            // code_nationalite_enfant:code_nationalite_enfant.val(),
            sexe_enfant:sexe_enfant.val(),
            heure_naissance_enfant:heure_naissance_enfant.val(),
            lieu_survenance:lieu_survenance.val(),
            nombre_enfant:nombre_enfants.val(),


            type_date_naissance_declarant:type_date_naissance_declarant.val(),
            statut_personne_declarant:statut_personne_declarant.val(),
            type_date_naissance_mere:type_date_naissance_mere.val(),
            statut_personne_mere:statut_personne_mere.val(),
            type_date_naissance_pere:type_date_naissance_pere.val(),
            statut_personne_pere:statut_personne_pere.val(),

            domicile_pays_pere:domicile_pays_pere.val(),
            domicile_ville_pere:domicile_ville_pere.val(),
            domicile_district_pere:domicile_district_pere.val(),
            domicile_quartier_pere:domicile_quartier_pere.val(),
            domicile_typevoie_pere:domicile_typevoie_pere.val(),
            domicile_numero_pere:domicile_numero_pere.val(),
            domicile_nomvoie_pere:domicile_nomvoie_pere.val(),

            domicile_pays_mere:domicile_pays_mere.val(),
            domicile_ville_mere:domicile_ville_mere.val(),
            domicile_district_mere:domicile_district_mere.val(),
            domicile_quartier_mere:domicile_quartier_mere.val(),
            domicile_typevoie_mere:domicile_typevoie_mere.val(),
            domicile_numero_mere:domicile_numero_mere.val(),
            domicile_nomvoie_mere:domicile_nomvoie_mere.val(),

            domicile_pays_declarant:domicile_pays_declarant.val(),
            domicile_ville_declarant:domicile_ville_declarant.val(),
            domicile_district_declarant:domicile_district_declarant.val(),
            domicile_quartier_declarant:domicile_quartier_declarant.val(),
            domicile_typevoie_declarant:domicile_typevoie_declarant.val(),
            domicile_numero_declarant:domicile_numero_declarant.val(),
            domicile_nomvoie_declarant:domicile_nomvoie_declarant.val(),
            formation_sanitaire_naissance:formation_sanitaire_naissance.val(),

            email_pere:email_pere.val(),
            email_mere:email_mere.val(),
            email_declarant:email_declarant.val(),
            _method:_method
           // statut_personne_enfant:statut_personne_declarant.val()



        };


        //traitement ajax

        Swal.fire({
            width:2500,
            position: 'top',
            title: "Enrégistrer la déclaration de naissance ?",
            icon: 'question',
            html:
"<input type='button' value='Imprimer cette page' class=\"btn btn-primary\" onClick='printDiv(\"printcontent\")'><div id='printcontent'><br><table style='border:0px solid black; width:100%; padding:10px; text-align:left'>"

//DECLARATION
//+"<tr><td style='padding:2px; font-weight:bold;'><b>DECLARATION DU:</b></td><td style='padding:2px'><span style='font-weight:bold;'>"+dateFrench(date_naissance_enfant.val())+" </span></td><td style='padding:2px'>Lieu de naissance</td><td style='padding:2px'> <span style='font-weight:bold;'>"+lieu_naissance_enfant.val()+"</span></td></tr>"

//ENFANT
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>1)ENFANT<br></td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_enfant.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_enfant.val()+"</span></td><td style='padding:2px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_enfant" ).options[ document.getElementById( "sexe_enfant" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Lieu<br><span style='font-weight:bold;'>"+lieu_naissance_enfant.val()+"</span></td></tr>"
+"<tr><td></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_enfant.val())+"</span></td><td style='padding:2px'>Heure</td><td style='padding:2px'><span style='font-weight:bold;'> "+heure_naissance_enfant.val()+"</span></td></tr>"

//PERE
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>2)PERE </td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_pere.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_pere.val()+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_pere.val())+"</span></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_pere.val()+" "+domicile_nomvoie_declarant.val()+" "+domicile_district_declarant.text()+"</span></td><td style='padding:2px'>Telephone<br><span style='font-weight:bold;'>"+telephone_pere.val()+"</span></td></tr>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Document<br><span style='font-weight:bold;'>"+numero_document_pere.val()+"</span></td><td style='padding:2px'>Lieu naissance<br><span style='font-weight:bold;'>"+lieu_naissance_pere.val()+"</span></td><td style='padding:2px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_pere" ).options[ document.getElementById( "code_nationalite_pere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_pere" ).options[ document.getElementById( "profession_pere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Niveau <br><span style='font-weight:bold;'>"+document.getElementById( "niveau_instruction_pere" ).options[ document.getElementById( "niveau_instruction_pere" ).selectedIndex ].text+"</span></td></tr>"



//mere
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:2px'>2)MERE </td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_mere.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_mere.val()+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_mere.val())+"</span></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_mere.val()+" "+domicile_nomvoie_mere.val()+" "+domicile_district_mere.text()+"</span></td><td style='padding:2px'>Niveau <br><span style='font-weight:bold;'>"+document.getElementById( "niveau_instruction_mere" ).options[ document.getElementById( "niveau_instruction_mere" ).selectedIndex ].text +"</span></td></tr>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Document<br><span style='font-weight:bold;'>"+numero_document_mere.val()+"</span></td><td style='padding:2px'>Lieu naissance<br><span style='font-weight:bold;'>"+lieu_naissance_mere.val()+"</span></td><td style='padding:2px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_mere" ).options[ document.getElementById( "code_nationalite_mere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_mere" ).options[ document.getElementById( "profession_mere" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Telephone<br><span style='font-weight:bold;'>"+telephone_mere.val()+"</span></td></tr>"

+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Nombre enfant <br><span style='font-weight:bold;'>"+nombre_enfants.val()+"</span></td></tr>"


//DECLARANT
+"<tr><td style='padding:10px' colspan='6'><hr></td></tr><td style='font-weight:bold; padding:2px'>3)DECLARANT</td><td style='padding:2px'>Nom<br><span style='font-weight:bold;'>"+ nom_declarant.val() +" </span></td><td style='padding:2px'>Prenom<br><span style='font-weight:bold;'> "+prenom_declarant.val()+"</span></td><td style='padding:2px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_declarant" ).options[ document.getElementById( "sexe_declarant" ).selectedIndex ].text+"</span></td><td style='padding:2px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(date_naissance_declarant.val())+"</span></td><td style='padding:2px'>Lieu<br><span style='font-weight:bold;'>"+ lieu_naissance_declarant.val()+"</span></td>"
+"<tr><td style='font-weight:bold; padding:2px'></td><td style='padding:2px'>Adresse<br><span style='font-weight:bold;'>"+domicile_numero_declarant.val()+" "+domicile_nomvoie_declarant.val()+" "+domicile_district_declarant.text()+"</span></td><td style='padding:2px'>Filiation<br><span style='font-weight:bold;'>"+document.getElementById( "filiation" ).options[ document.getElementById( "filiation" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Téléphone<br><span style='font-weight:bold;'>"+ telephone_declarant.val()+"</span></td><td style='padding:2px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "profession_declarant" ).options[ document.getElementById( "profession_declarant" ).selectedIndex ].text +"</span></td><td style='padding:2px'>Nationalite<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_declarant" ).options[ document.getElementById( "code_nationalite_declarant" ).selectedIndex ].text+"</span></tr>"



+"<tr><td style='padding:5px;' colspan=11><hr style='border:none;'></td></tr></table><bR><br>Assurez-vous, puis confirmez !</div>",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Oui, Confirmer!",
            cancelButtonText: "Non, Annuler!",
            reverseButtons: !0
        }).then((result)=>
         {

            if (result.value==true)
             {
                var code = "{{$dn->code_declaration_naissance}}";
                var route = "{{route('declarationNaissance.update',':id')}}";
                route = route.replace(':id',code);

                // console.log(data);
                // return false;

                $.post(route,data,function(response)
                {
                    if(response.code == "200")
                    {
                        flashAlert("Opération réussie","success",response.message);
                        var url = "{{ route('declarationNaissance.index') }}";
                        setTimeout(() => {
                            window.open(url);
                        }, 4000);
                    }else{
                        console.log(response);
                        return false;
                        var outString = "<ul>";
                            for (const [key, value] of Object.entries(response.message))
                            {
                            outString+= `<li style='text-align:left;color:red; list-style:disc !important; font-size:12px'>${value}</li>`;
                            }
                        outString += "</ul>";
                        flashAlert("Une erreur est suvernue","error",outString);
                    }

                });
            }

        });

        return false;

    }

</script>
<script>
function dateFrench(dat){
    var date = new Date(dat);
    return date.getDate()+ "/"+(date.getMonth() + 1 )+"/"+date.getFullYear();
  }
</script>


<script>

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        w=window.open();
        w.document.write(printContents);
        w.print();
        w.close();
    }
</script>
