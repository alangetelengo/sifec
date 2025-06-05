
<script src="{{ asset('tpl/vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js') }}"></script>
<script src="{{ asset('tpl/vendor/jquery-validation/jquery.validate.min.js') }}"></script>
<!-- Form validate init -->
<script src="{{ asset('tpl/js/plugins-init/jquery.validate-init.js') }}"></script>

     <!-- Daterangepicker -->
     <script src="{{ asset("tpl/js/plugins-init/bs-daterange-picker-init.js") }}"></script>
     <!-- Clockpicker init -->
     <script src="{{ asset("tpl/js/plugins-init/clock-picker-init.js") }}"></script>
     <!-- asColorPicker init -->
     <script src="{{ asset("tpl/js/plugins-init/jquery-asColorPicker.init.js") }}"></script>
     <!-- Material color picker init -->
     <script src="{{ asset("tpl/js/plugins-init/material-date-picker-init.js") }}"></script>
     <!-- Pickdate -->
     <script src="{{ asset("tpl/js/plugins-init/pickadate-init.js") }}"></script>

    <!-- This Page JS -->
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.steps.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/wizard/jquery.validate.min.js") }}"></script>
    <script src="{{ asset("tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.js") }}"></script>
    <script src="{{ asset("tpl/js/bootstrap-datepicker.min.js") }}"></script>

    <script>
        //Custom design form example
        $(".tab-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onFinished: function (event, currentIndex) {
                Swal.fire("Certificat Enregistré !", "Déclarion est en cours de traiatement, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

            }
        });

        // Rechercher un défunt
        $('#rechercher').on("click", function (event) {
            event.preventDefault();
            // data = [];
            var nom = $("#nom_defunt_recherche");
            var prenom = $("#prenom_defunt_recherche");
            var sexe = $("#sexe_defunt_recherche");
            var telephone = $("#telephone_defunt_recherche");
            var statut = $("#statut_personne_defunt_recherche");

            var data = {
                nom: nom.val(),
                prenom: prenom.val(),
                sexe: sexe.val(),
                telephone: telephone.val(),
                statut: statut.val()
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
                        $("#resultatDefunt").html(table);

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

                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');
                            var numero_document = $(this).data('numero_document');
                            var code_type_document = $(this).data('code_type_document');
                            var statut_personne = $(this).data('statut_personne');
                            var type_date_naissance = $(this).data('type_date_naissance');



                            $("#nom_defunt").val(nom);
                            $("#prenom_defunt").val(prenom);
                            $("#date_naissance_defunt").val(date_naissance);
                            $("#sexe_defunt").val(sexe);
                            $("#domicile_numero_defunt").val(numero);
                            $("#domicile_nomvoie_defunt").val(rue);
                            $("#domicile_quartier_defunt").val(quartier);
                            if($(this).data('arrondissement')===null)
                            {
                            $("#domicile_quartier_defunt").val("choisissez");
                            }else{
                                $("#domicile_quartier_defunt").val(arrondissement);
                            }



                            $("#code_nationalite_defunt").val(code_nationalite);
                            $("#code_profession_defunt").val(code_profession);
                            $("#lieu_naissance_defunt").val(lieu_naissance);
                            $("#niveau_instruction_defunt").val(niveau_instruction);
                            $("#numero_document_defunt").val(numero_document);
                            $("#code_type_document_defunt").val(code_type_document);
                            $("#statut_personne_defunt").val(statut_personne);
                            $("#type_date_naissance_defunt").val(type_date_naissance);


                            if($(this).data('type_date_naissance')==="EXACTE")
                            {
                                $("#type_date_naissance_defunt").val("EXACTE");
                                document.getElementById('type_date_naissance_defunt').checked="";
                            }else{
                                $("#type_date_naissance_defunt").val("ESTIME");
                                document.getElementById('type_date_naissance_defunt').checked="ESTIME";

                            }

                            document.getElementById('nom_defunt').readOnly = true;
                            document.getElementById('prenom_defunt').readOnly = true;
                            document.getElementById('date_naissance_defunt').readOnly = true;
                            document.getElementById('lieu_naissance_defunt').readOnly = true;
                            document.getElementById('code_nationalite_defunt').disabled = true;
                            document.getElementById('type_date_naissance_defunt').disabled = true;
                            document.getElementById('sexe_defunt').disabled = true;


                            $("#defuntmodal").modal('hide');
                            // console.log(response.personnes);
                            //getdocument(choix);

                        });

                    }
                });
        });

        // Rechercher un conjoint
        $('#rechercherconjoint').on("click", function (event) {
            event.preventDefault();

            var nom = $("#nom_conjoint_recherche");
            var prenom = $("#prenom_conjoint_recherche");
            var sexe = $("#sexe_conjoint_recherche");
            var telephone = $("#telephone_conjoint_recherche");

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
                                        '<th><strong>Naissance</strong></th>'+
                                        '<th><strong>Sexe</strong></th>'+
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
                        $("#resultatConjoint").html(table);

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

                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession = $(this).data('code_profession');
                            var lieu_naissance = $(this).data('lieu_naissance');
                            var niveau_instruction = $(this).data('niveau_instruction');
                            var numero_document = $(this).data('numero_document');
                            var code_type_document = $(this).data('code_type_document');
                            var statut_personne = $(this).data('statut_personne');
                            var type_date_naissance = $(this).data('type_date_naissance');



                            $("#nom_conjoint").val(nom);
                            $("#code_conjoint").val(choix);
                            $("#prenom_conjoint").val(prenom);
                            $("#domicile_pays_conjoint").val(indicatif);
                            $("#telephone_conjoint").val(telephone);
                            $("#date_naissance_conjoint").val(date_naissance);
                            $("#sexe_conjoint").val(sexe);
                            $("#domicile_numero_conjoint").val(numero);
                            $("#domicile_nomvoie_conjoint").val(rue);
                            $("#domicile_quartier_conjoint").val(quartier);
                            if($(this).data('arrondissement')===null)
                            {
                              $("#domicile_arrondissement_conjoint").val("choisissez");
                            }else{
                                $("#domicile_arrondissement_conjoint").val(arrondissement);
                            }



                            $("#code_nationalite_conjoint").val(code_nationalite);
                            $("#code_profession_conjoint").val(code_profession);
                            $("#lieu_naissance_conjoint").val(lieu_naissance);
                            $("#niveau_instruction_conjoint").val(niveau_instruction);
                            $("#numero_document_conjoint").val(numero_document);
                            $("#code_type_document_conjoint").val(code_type_document);
                            $("#statut_personne_conjoint").val(statut_personne);
                            $("#type_date_naissance_conjoint").val(type_date_naissance);


                            if($(this).data('type_date_naissance')==="EXACTE")
                            {
                                $("#type_date_naissance_conjoint").val("EXACTE");
                                document.getElementById('type_date_naissance_conjoint').checked="";
                            }else{
                                $("#type_date_naissance_conjoint").val("ESTIME");
                                document.getElementById('type_date_naissance_conjoint').checked="ESTIME";

                            }


                            document.getElementById('nom_conjoint').readOnly = true;
                            document.getElementById('telephone_conjoint').disabled = true;
                            document.getElementById('prenom_conjoint').readOnly = true;
                            document.getElementById('date_naissance_conjoint').readOnly = true;
                            document.getElementById('lieu_naissance_conjoint').disabled = true;
                            document.getElementById('code_nationalite_conjoint').disabled = true;
                            document.getElementById('domicile_pays_conjoint').disabled  = true;
                            document.getElementById('statut_personne_conjoint').disabled = true;
                             document.getElementById('type_date_naissance_conjoint').disabled = true;
                            document.getElementById('sexe_conjoint').disabled = true;
                            document.getElementById('domicile_typevoie_conjoint').disabled = true;
                            document.getElementById('domicile_pays_conjoint').disabled = true;
                            document.getElementById('code_type_document_conjoint').disabled = true;
                            document.getElementById('statut_personne_conjoint').disabled = true;

                            $("#conjointmodal").modal('hide');
                            // console.log(response.personnes);

                         //   getdocument(choix);

                        });

                    }
                });
        });

         // Rechercher un père
         $('#rechercherpere').on("click", function (event) {
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
                            $("#sexe_pere").val(sexe);
                            $("#domicile_numero_pere").val(numero);
                            $("#domicile_nomvoie_pere").val(rue);
                            $("#domicile_quartier_pere").val(quartier);
                            if($(this).data('arrondissement')===null)
                            {
                              $("#domicile_arrondissement_pere").val("choisissez");
                            }else{
                                $("#domicile_arrondissement_pere").val(arrondissement);
                            }


                            $("#telephone_pere").val(telephone);
                            $("#code_nationalite_pere").val(code_nationalite);
                            $("#code_profession_pere").val(code_profession);
                            $("#lieu_naissance_pere").val(lieu_naissance);
                            $("#niveau_instruction_pere").val(niveau_instruction);
                            $("#numero_document_pere").val(numero_document);
                            $("#code_type_document_pere").val(code_type_document);
                            $("#statut_personne_pere").val(statut_personne);
                            $("#type_date_naissance_pere").val(type_date_naissance);
                            $("#domicile_pays_pere").val(indicatif);

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
                            $("#domicile_numero_mere").val(numero);
                            $("#domicile_nomvoie_mere").val(rue);
                            $("#domicile_quartier_mere").val(quartier);
                            if($(this).data('arrondissement')===null)
                            {
                              $("#domicile_arrondissement_mere").val("choisissez");
                            }else{
                                $("#domicile_arrondissement_mere").val(arrondissement);
                            }


                            $("#telephone_mere").val(telephone);
                            $("#code_nationalite_mere").val(code_nationalite);
                            $("#code_profession_mere").val(code_profession);
                            $("#lieu_naissance_mere").val(lieu_naissance);
                            $("#niveau_instruction_mere").val(niveau_instruction);
                            $("#numero_document_mere").val(numero_document);
                            $("#code_type_document_mere").val(code_type_document);
                            $("#statut_personne_mere").val(statut_personne);
                            $("#type_date_naissance_mere").val(type_date_naissance);
                            $("#domicile_pays_mere").val(indicatif);

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
                                '" data-sexe="'+response.personnes[i].sexe+
                                '" data-numero="'+response.personnes[i].numero_rue+
                                '" data-rue="'+response.personnes[i].avenue+
                                '" data-quartier="'+response.personnes[i].quartier+
                                '" data-arrondissement="'+response.personnes[i].code_arrondissement+
                                '" data-telephone="'+response.personnes[i].phone+
                                '" data-indicatif="'+response.personnes[i].indicatif+
                                '" data-code_nationalite="'+response.personnes[i].code_nationalite+
                                '" data-code_profession_declarant="'+response.personnes[i].code_profession_declarant+
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

                            var code_nationalite = $(this).data('code_nationalite');
                            var code_profession_declarant = $(this).data('code_profession_declarant');
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
                            $("#domicile_numero_declarant").val(numero);
                            $("#domicile_nomvoie_declarant").val(rue);
                            $("#domicile_quartier_declarant").val(quartier);
                            if($(this).data('arrondissement')===null)
                            {
                              $("#domicile_arrondissement_declarant").val("choisissez");
                            }else{
                                $("#domicile_arrondissement_declarant").val(arrondissement);
                            }


                            $("#telephone_declarant").val(telephone);
                            $("#code_nationalite_declarant").val(code_nationalite);
                            $("#code_profession_declarant").val(code_profession_declarant);
                            $("#lieu_naissance_declarant").val(lieu_naissance);
                            $("#niveau_instruction_declarant").val(niveau_instruction);
                            $("#numero_document_declarant").val(numero_document);
                            $("#code_type_document_declarant").val(code_type_document);
                            $("#statut_personne_declarant").val(statut_personne);
                            $("#type_date_naissance_declarant").val(type_date_naissance);
                            $("#domicile_pays_declarant").val(indicatif);

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
                            //document.getElementById('statut_personne_declarant').disabled = true;


                            $("#declarantmodal").modal('hide');
                          //  getdocumentdeclarant(choix);
                            //console.log(response.personnes);

                        });

                    }
                });
        });

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
                return currentIndex > newIndex || !(2 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
            },
            onFinishing: function (event, currentIndex) {
                return form.validate().settings.ignore = ":disabled", form.valid()
            },
            onFinished: function (event, currentIndex) {
                var selected = [];
                for (var option of document.getElementById('code_cause_deces').options)
                {
                    if (option.selected) {
                        selected.push(option.text);
                    }
                }
if(($('#code_situation_matrimoniale_defunt').val()==="SMAT_0001"))
{
    conjoint="<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:5px'>2)CONJOINT </td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_conjoint").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_conjoint").value+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_naissance_conjoint").value)+"</span></td><td style='padding:5px'>Acte Mariage<br><span style='font-weight:bold;'>"+document.getElementById("num_acte_mariage").value+"</span></td><td style='padding:5px'>Date Mariage<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_mariage").value)+"</span></td><td style='padding:5px'>CEC<br><span style='font-weight:bold;'>"+document.getElementById("cec_mariage").value+"</span></td></tr>";
}else{
    conjoint="";
}
                Swal.fire({
                    width:2500,
                    position: 'top',
                    title: "Enrégistrer le certificat de constatation de décès ?",
                    icon: 'question',
                    //text: "Assurez-vous, puis confirmez ! \n\n",
html:
     "<input type='button' value='Imprimer cette page' class=\"btn btn-primary\" onClick='printDiv(\"printcontent\")'><div id='printcontent'><br><table style='border:1px solid black; width:100%; padding:10px; text-align:left'>"

//DECLARATION
    +"<tr><td style='padding:5px'>Date décès</td><td style='padding:5px'><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_deces").value)+" </span></td><td style='padding:5px'>Heure</td><td style='padding:5px'><span style='font-weight:bold;'> "+document.getElementById("heure_deces").value+"</span></td><td style='padding:5px'>Lieu de décès</td><td style='padding:5px'> <span style='font-weight:bold;'>"+document.getElementById("lieu_deces").value+"</span></td></tr>"

//DEFUNT
    +"<tr><td style='padding:10px' colspan='6'><hr></td></tr><tr><td style='font-weight:bold; padding:5px'>1)DEFUNT<br></td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_defunt").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_defunt").value+"</span></td><td style='padding:5px'>Sexe<br><span style='font-weight:bold;'>"+document.getElementById( "sexe_defunt" ).options[ document.getElementById( "sexe_defunt" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_naissance_defunt").value)+"</span></td><td style='padding:5px'>Lieu<br><span style='font-weight:bold;'>"+document.getElementById("cec_naissance").value+"</span></td></tr><tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Acte naissance<BR><span style='font-weight:bold;'>"+document.getElementById("num_acte_naissance").value+"</span></td><td style='padding:5px'>Mairie<br><span style='font-weight:bold;'>"+document.getElementById("cec_naissance").value+"</span></td><td style='padding:5px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "code_profession_defunt" ).options[ document.getElementById( "code_profession_defunt" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Adresse<br><span style='font-weight:bold;'>"+document.getElementById("domicile_nomvoie_defunt").value+"</span></td><td style='padding:5px'></td></tr><tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Sit. Matrimoniale<br><span style='font-weight:bold;'>"+document.getElementById( "code_situation_matrimoniale_defunt" ).options[document.getElementById( "code_situation_matrimoniale_defunt" ).selectedIndex].text +" </span></td><td style='padding:5px'>Réligion<br><span style='font-weight:bold;'> "+document.getElementById( "code_religion_defunt" ).options[ document.getElementById( "code_religion_defunt" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Nationalité<br><span style='font-weight:bold;'>"+document.getElementById( "code_nationalite_defunt" ).options[ document.getElementById( "code_nationalite_defunt" ).selectedIndex ].text+"</span></td></tr>"


//Conjoint
// +conjoint
// +"<tr><td style='font-weight:bold; padding:5px'>3)PERE : </td><td style='padding:5px'>Nom et prénom <br><span style='font-weight:bold;'>"+document.getElementById("nom_pere").value+" "+document.getElementById("prenom_pere").value+"</span></td><td style='font-weight:bold; padding:5px'>4)MERE </td><td style='padding:5px'>Nom et prenom <br><span style='font-weight:bold;'>"+document.getElementById("nom_mere").value+" "+document.getElementById("prenom_mere").value+"</span></td></tr>"

  //DECLARANT
    +"<tr><td style='padding:10px' colspan='6'><hr></td></tr><td style='font-weight:bold; padding:5px'>3)DECLARANT</td><td style='padding:5px'>Nom<br><span style='font-weight:bold;'>"+ document.getElementById("nom_declarant").value +" </span></td><td style='padding:5px'>Prenom<br><span style='font-weight:bold;'> "+document.getElementById("prenom_declarant").value+"</span></td><td style='padding:5px'>Date naissance<br><span style='font-weight:bold;'>"+dateFrench(document.getElementById("date_naissance_declarant").value)+"</span></td><td style='padding:5px'>Sexe<br><span style='font-weight:bold;'> "+document.getElementById( "sexe_declarant" ).options[ document.getElementById( "sexe_declarant" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Lieu<br><span style='font-weight:bold;'>"+document.getElementById("lieu_naissance_declarant").value+"</span></td><td style='padding:5px'>Adresse<br><span style='font-weight:bold;'>"+document.getElementById("domicile_nomvoie_declarant").value+"</span></td><tr>"
    +"<tr><td style='font-weight:bold; padding:5px'></td><td style='padding:5px'>Cause de décès <br><span style='font-weight:bold;'>"+selected+"</span></td><td style='padding:5px'>Filiation<br><span style='font-weight:bold;'>"+document.getElementById( "code_filiation_declarant" ).options[ document.getElementById( "code_filiation_declarant" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Téléphone<br><span style='font-weight:bold;'>"+document.getElementById("telephone_declarant").value+"</span></td><td style='padding:5px'>Profession<br><span style='font-weight:bold;'>"+document.getElementById( "code_profession_declarant" ).options[ document.getElementById( "code_profession_declarant" ).selectedIndex ].text+"</span></td><td style='padding:5px'>Nationalite<br><span style='font-weight:bold;'>"+document.getElementById("code_nationalite_declarant").options[ document.getElementById( "code_nationalite_declarant" ).selectedIndex ].text+"</span></tr>"


    +"<tr><td style='padding:5px;' colspan=11><hr style='border:none;'></td></tr></table><bR><br>Assurez-vous, puis confirmez !</div> ",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Oui, Confirmer!",
                    cancelButtonText: "Non, Annuler!",
                    reverseButtons: !0
                }).then(function (e)
                {

                    if (e.value === true)
                    {
                        let token = $('meta[name="csrf-token"]').attr('content');

                        //information du défunt
                        var type_declaration = $("#type_declaration");
                        var niveau_instruction_defunt = $("#niveau_instruction_defunt");

                        var heure_deces = $("#heure_deces");
                        var date_deces= $("#date_deces");
                        var nom_defunt = $("#nom_defunt");
                        var prenom_defunt = $("#prenom_defunt");
                        var sexe_defunt = $("#sexe_defunt");
                        var date_naissance_defunt = $("#date_naissance_defunt");
                        var lieu_naissance_defunt = $("#lieu_naissance_defunt");
                        var code_profession_defunt = $("#code_profession_defunt");
                        var code_situation_matrimoniale_defunt= $("#code_situation_matrimoniale_defunt");
                        var code_nationalite_defunt = $("#code_nationalite_defunt");
                        var code_religion_defunt = $("#code_religion_defunt");
                        var lieu_survenance_code = $("#lieu_survenance_code");
                        var lieu_deces = $("#lieu_deces");
                        var domicile_pays_defunt = $("#domicile_pays_defunt");
                        var domicile_numero_defunt = $("#domicile_numero_defunt");
                        var domicile_nomvoie_defunt = $("#domicile_nomvoie_defunt");
                        var domicile_quartier_defunt = $("#domicile_quartier_defunt");
                        var domicile_typevoie_defunt = $("#domicile_typevoie_defunt");
                        var domicile_arrondissement_defunt = $("#domicile_arrondissement_defunt");
                        var domicile_ville_defunt = $("#domicile_ville_defunt");
                        var num_acte_naissance = $("#num_acte_naissance");
                        var cec_naissance = $("#cec_naissance");


                        // informations du père
                        var nom_pere = $("#nom_pere");
                        var prenom_pere = $("#prenom_pere");
                        var date_naissance_pere = $("#date_naissance_pere");
                        var lieu_naissance_pere = $("#lieu_naissance_pere");
                        var domicile_pere = $("#domicile_pere");
                        var domicile_pays_pere = $("#domicile_pays_pere");
                        var telephone_pere = $("#telephone_pere");
                        var domicile_numero_pere = $("#domicile_numero_pere");
                        var domicile_nomvoie_pere = $("#domicile_nomvoie_pere");
                        var domicile_quartier_pere = $("#domicile_quartier_pere");
                        var domicile_typevoie_pere = $("#domicile_typevoie_pere");
                        var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                        var domicile_ville_pere = $("#domicile_ville_pere");
                        var code_profession_pere = $("#code_profession_pere");
                        var code_nationalite_pere = $("#code_nationalite_pere");
                        var niveau_instruction_pere = $("#niveau_instruction_pere");
                        var code_type_document_pere = $("#code_type_document_pere");
                        var numero_document_pere = $("#numero_document_pere");

                        //information mère
                        var nom_mere = $("#nom_mere");
                        var prenom_mere = $("#prenom_mere");
                        var date_naissance_mere = $("#date_naissance_mere");
                        var lieu_naissance_mere = $("#lieu_naissance_mere");
                        var domicile_pays_mere = $("#domicile_pays_mere");
                        var telephone_mere = $("#telephone_mere");
                        var domicile_numero_mere = $("#domicile_numero_mere");
                        var domicile_nomvoie_mere = $("#domicile_nomvoie_mere");
                        var domicile_quartier_mere = $("#domicile_quartier_mere");
                        var domicile_typevoie_mere = $("#domicile_typevoie_mere");
                        var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
                        var domicile_ville_mere = $("#domicile_ville_mere");
                        var code_profession_mere = $("#code_profession_mere");
                        var code_nationalite_mere = $("#code_nationalite_mere");
                        var niveau_instruction_mere = $("#niveau_instruction_mere");
                        var code_type_document_mere = $("#code_type_document_mere");
                        var numero_document_mere = $("#numero_document_mere");

                        //information conjoint
                        var nom_conjoint = $("#nom_conjoint");
                        var prenom_conjoint = $("#prenom_conjoint");
                        var date_mariage = $("#date_mariage");
                        var cec_mariage = $("#cec_mariage");
                        var code_regime = $("#code_regime");
                        var sexe_conjoint = $("#sexe_conjoint");
                        var domicile_typevoie_conjoint = $("#domicile_typevoie_conjoint");
                        var code_type_document_conjoint = $("#code_type_document_conjoint");
                        var statut_personne_conjoint = $("#statut_personne_conjoint");
                        var num_acte_mariage = $("#num_acte_mariage");
                        var date_naissance_conjoint = $("#date_naissance_conjoint");

                        var code_nationalite_conjoint =  $("#code_nationalite_conjoint");
                        var domicile_pays_conjoint = $("#domicile_pays_conjoint");
                        var telephone_conjoint = $("#telephone_conjoint");
                        var domicile_numero_conjoint = $("#domicile_numero_conjoint");
                        var domicile_nomvoie_conjoint = $("#domicile_nomvoie_conjoint");
                        var domicile_quartier_conjoint = $("#domicile_quartier_conjoint");
                        var domicile_typevoie_conjoint = $("#domicile_typevoie_conjoint");
                        var domicile_arrondissement_conjoint = $("#domicile_arrondissement_conjoint");
                        var domicile_ville_conjoint = $("#domicile_ville_conjoint");
                        var lieu_naissance_conjoint = $("#lieu_naissance_conjoint");
                        var code_profession_conjoint = $("#code_profession_conjoint");

                        //information déclarant
                        var nom_declarant = $("#nom_declarant");
                        var prenom_declarant = $("#prenom_declarant");
                        var date_naissance_declarant = $("#date_naissance_declarant");
                        var lieu_naissance_declarant = $("#lieu_naissance_declarant");
                        var domicile_pays_declarant = $("#domicile_pays_declarant");
                        var telephone_declarant = $("#telephone_declarant");
                        var domicile_numero_declarant = $("#domicile_numero_declarant");
                        var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");
                        var domicile_quartier_declarant = $("#domicile_quartier_declarant");
                        var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
                        var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
                        var domicile_ville_declarant = $("#domicile_ville_declarant");
                        var sexe_declarant = $("#sexe_declarant");
                        var filiation = $("#code_filiation_declarant");
                        var telephone_declarant = $("#telephone_declarant");
                        var code_profession_declarant = $("#code_profession_declarant");
                        var code_nationalite_declarant = $("#code_nationalite_declarant");
                        var code_cause_deces = $("#code_cause_deces");


                        var statut_personne_pere = $("#statut_personne_pere");
                        var statut_personne_conjoint = $("#statut_personne_conjoint");
                        var statut_personne_mere = $("#statut_personne_mere");
                        var statut_personne_declarant = $("#statut_personne_declarant");

                        var type_date_naissance_pere = $("#type_date_naissance_pere");
                        var type_date_naissance_mere = $("#type_date_naissance_mere");
                        var type_date_naissance_conjoint = $("#type_date_naissance_conjoint");
                        var type_date_naissance_declarant = $("#type_date_naissance_declarant");


                        $.ajax({
                            type: 'POST',
                            url: "{{route('centreHygiene.store')}}",
                            data: {
                                 type_declaration:type_declaration.val(),

                                 statut_personne_pere:statut_personne_pere.val(),
                                 niveau_instruction_defunt:niveau_instruction_defunt.val(),
                                 statut_personne_conjoint:statut_personne_conjoint.val(),
                                 statut_personne_mere:statut_personne_mere.val(),
                                 statut_personne_declarant:statut_personne_declarant.val(),

                                 type_date_naissance_pere:type_date_naissance_pere.val(),
                                 type_date_naissance_mere :type_date_naissance_mere.val(),
                                 type_date_naissance_conjoint:type_date_naissance_conjoint.val(),
                                 type_date_naissance_declarant:type_date_naissance_declarant.val(),

                                heure_deces:heure_deces.val(),
                                date_deces: date_deces.val(),
                                nom_defunt: nom_defunt.val(),
                                prenom_defunt: prenom_defunt.val(),
                                sexe_defunt: sexe_defunt.val(),
                                date_naissance_defunt: date_naissance_defunt.val(),
                                lieu_naissance_defunt: lieu_naissance_defunt.val(),
                                code_profession_defunt: code_profession_defunt.val(),
                                code_situation_matrimoniale_defunt: code_situation_matrimoniale_defunt.val(),
                                code_nationalite_defunt: code_nationalite_defunt.val(),
                                code_religion_defunt: code_religion_defunt.val(),
                                lieu_survenance_code: lieu_survenance_code.val(),
                                lieu_deces: "BRAZZAVILLE",

                                domicile_numero_defunt:domicile_numero_defunt.val(),
                                domicile_nomvoie_defunt: domicile_nomvoie_defunt.val(),
                                domicile_quartier_defunt:domicile_quartier_defunt.val(),
                                domicile_typevoie_defunt:domicile_typevoie_defunt.val(),
                                domicile_arrondissement_defunt: domicile_arrondissement_defunt.val(),
                                domicile_ville_defunt: domicile_ville_defunt.val(),
                                domicile_pays_defunt: domicile_pays_defunt.val(),



                                sexe_conjoint: sexe_conjoint.val(),
                                domicile_typevoie_conjoint: domicile_typevoie_conjoint.val(),
                                domicile_pays_conjoint: domicile_pays_conjoint.val(),
                                code_type_document_conjoint: code_type_document_conjoint.val(),
                                statut_personne_conjoint: statut_personne_conjoint.val(),
                                telephone_conjoint:telephone_conjoint.val(),

                                domicile_pays_conjoint:domicile_pays_conjoint.val(),
                                domicile_numero_conjoint:domicile_numero_conjoint.val(),
                                domicile_nomvoie_conjoint: domicile_nomvoie_conjoint.val(),
                                domicile_quartier_conjoint:domicile_quartier_conjoint.val(),
                                domicile_typevoie_conjoint:domicile_typevoie_conjoint.val(),
                                domicile_arrondissement_conjoint: domicile_arrondissement_conjoint.val(),
                                domicile_ville_conjoint: domicile_ville_conjoint.val(),


                                nom_conjoint: nom_conjoint.val(),
                                prenom_conjoint: prenom_conjoint.val(),
                                code_profession_conjoint:code_profession_conjoint.val(),
                                lieu_naissance_conjoint:lieu_naissance_conjoint.val(),
                                code_nationalite_conjoint:code_nationalite_conjoint.val(),
                                date_naissance_conjoint: date_naissance_conjoint.val(),
                                date_mariage: date_mariage.val(),
                                cec_mariage: cec_mariage.val(),
                                code_regime: code_regime.val(),
                                num_acte_mariage: num_acte_mariage.val(),

                                  // données du père
                                  nom_pere:nom_pere.val(),
                                  prenom_pere:prenom_pere.val(),
                                  date_naissance_pere:date_naissance_pere.val(),
                                  lieu_naissance_pere:lieu_naissance_pere.val(),
                                  //domicile_pere:domicile_pere.val(),
                                  code_profession_pere:code_profession_pere.val(),
                                  code_nationalite_pere:code_nationalite_pere.val(),
                                  niveau_instruction_pere:niveau_instruction_pere.val(),

                                  domicile_pays_pere:domicile_pays_pere.val(),
                                  telephone_pere:telephone_pere.val(),

                                  domicile_numero_pere:domicile_numero_pere.val(),
                                  domicile_nomvoie_pere: domicile_nomvoie_pere.val(),
                                  domicile_quartier_pere:domicile_quartier_pere.val(),
                                  domicile_typevoie_pere:domicile_typevoie_pere.val(),
                                  domicile_arrondissement_pere: domicile_arrondissement_pere.val(),
                                  domicile_ville_pere: domicile_ville_pere.val(),
                                  domicile_pays_pere: domicile_pays_pere.val(),

                                  code_type_document_pere:code_type_document_pere.val(),
                                  numero_document_pere:numero_document_pere.val(),

                                  // données de la mère
                                  nom_mere:nom_mere.val(),
                                  prenom_mere:prenom_mere.val(),
                                  date_naissance_mere:date_naissance_mere.val(),
                                  lieu_naissance_mere:lieu_naissance_mere.val(),

                                  code_profession_mere:code_profession_mere.val(),
                                  code_nationalite_mere:code_nationalite_mere.val(),
                                  niveau_instruction_mere:niveau_instruction_mere.val(),
                                  domicile_pays_mere:domicile_pays_mere.val(),
                                  telephone_mere:telephone_mere.val(),

                                  domicile_numero_mere:domicile_numero_mere.val(),
                                  domicile_nomvoie_mere: domicile_nomvoie_mere.val(),
                                  domicile_quartier_mere:domicile_quartier_mere.val(),
                                  domicile_typevoie_mere:domicile_typevoie_mere.val(),
                                  domicile_arrondissement_mere: domicile_arrondissement_mere.val(),
                                  domicile_ville_mere: domicile_ville_mere.val(),
                                  domicile_pays_mere: domicile_pays_mere.val(),

                                  code_type_document_mere:code_type_document_mere.val(),
                                  numero_document_mere:numero_document_mere.val(),

                                nom_declarant: nom_declarant.val(),
                                prenom_declarant: prenom_declarant.val(),
                                sexe_declarant: sexe_declarant.val(),
                                date_naissance_declarant: date_naissance_declarant.val(),
                                lieu_naissance_declarant: lieu_naissance_declarant.val(),
                                domicile_pays_declarant:domicile_pays_declarant.val(),
                                telephone_declarant:telephone_declarant.val(),

                                domicile_numero_declarant:domicile_numero_declarant.val(),
                                domicile_nomvoie_declarant: domicile_nomvoie_declarant.val(),
                                domicile_quartier_declarant:domicile_quartier_declarant.val(),
                                domicile_typevoie_declarant:domicile_typevoie_declarant.val(),
                                domicile_arrondissement_declarant: domicile_arrondissement_declarant.val(),
                                domicile_ville_declarant: domicile_ville_declarant.val(),
                                domicile_pays_declarant: domicile_pays_declarant.val(),

                                filiation: filiation.val(),
                                code_profession_declarant: code_profession_declarant.val(),

                                code_nationalite_declarant: code_nationalite_declarant.val(),
                                code_cause_deces: code_cause_deces.val(),
                                num_acte_naissance: num_acte_naissance.val(),
                                cec_naissance: cec_naissance.val()
                            },
                           // data: {_token: token},
                            success: function(response )
                            {

                                if (response.success==true)
                                {
                                    swal.fire("Enrégistré!", "Certificat de constatation de décès crée avec succès", "success");
                                    var url= "{{ route('centreHygiene.index') }}";
                                    setTimeout(() => {
                                        window.open(url);
                                    }, 4000);
                                } else {
                                    swal.fire("Erreur!", response.message, "error");
                                }
                            },
                            error: function (resp) {
                                swal.fire("Erreur!", "Sumething went wrong.", "error");
                            }
                        });

                    } else {
                        e.dismiss;
                    }

                }, function (dismiss) {
                    return false;
                });

            }

        }), $("#contactUsForm").validate({
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
            rules:
             {
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
                heure_deces: {
                    required: true,
                   // minlength: 50
                },
                num_acte_naissance:{
                    required: true,
                },
                date_deces: {
                    required: true,
                },
                nom_defunt: {
                required: true,
                maxlength: 50
                },
                sexe_defunt: {
                required: true,
                },
                sexe_declarant: {
                    required: true,
                    },
                telephone_declarant: {
                        required: true,
                        },
                code_profession_declarant: {
                            required: true,
                            },
                date_naissance_defunt: {
                    required: true,
                },
                lieu_naissance_defunt: {
                    required: true,
                },
                code_profession_defunt: {
                    required: true,
                },
                code_situation_matrimoniale_defunt: {
                    required: true,
                },
                code_nationalite_defunt: {
                    required: true,
                },
                code_religion_defunt: {
                    required: true,
                },
                lieu_survenance_code: {
                    required: true,
                },
                lieu_deces: {
                    required: true,
                },
                domicile_defunt: {
                required: true,
                maxlength:300
                },
                nom_declarant: {
                    required: true,
                    maxlength: 50
                },
                date_naissance_declarant: {
                    required: true,
                },
                lieu_naissance_declarant: {
                    required: true,
                },
                domicile_declarant: {
                    required: true,
                },
                code_filiation_declarant: {
                    required: true,
                },
                },
                messages: {
                    sexe_declarant: {
                        required: "Veuillez selectionner le sexe",
                        },
                    telephone_declarant: {
                            required: "Veuillez saisir un numero de téléphone",
                            },
                    code_profession_declarant: {
                                required: "Veuillez selectionner la profession",
                                },
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
                num_acte_naissance:{
                    required: "Veuillez saisir le numero de l\'acte de naissance",
                },
                date_deces: {
                required: "Veuillez saisir la date du décès",
                //maxlength: "Votre nom ne doit comporter 50 caractères."
                },
                heure_deces: {
                    required: "Veuillez saisir l'heure du décès",
                    //maxlength: "Votre nom ne doit comporter 50 caractères."
                },

                nom_defunt: {
                required: "Veuillez saisir le nom du défunt",
                maxlength: "Le nom ne doit pas dépasser 50 caractères."
                },
                sexe_defunt: {
                required: "Veuillez choisir le sexe du défunt.",
                },
                date_naissance_defunt: {
                    required: "Veuillez saisir la date de naissance du défunt.",
                },
                lieu_naissance_defunt: {
                    required: "Veuillez saisir le lieu de naissance du défunt.",
                },
                code_profession_defunt: {
                    required: "Veuillez choisir la profession du défunt.",
                },
                code_situation_matrimoniale_defunt: {
                    required: "Veuillez choisir la situation matrimoniale du défunt.",
                },
                code_nationalite_defunt: {
                    required: "Veuillez choisir la nationalité du défunt.",
                },
                code_religion_defunt: {
                    required: "Veuillez choisir la réligion du défunt.",
                },
                lieu_survenance_code: {
                    required: "Veuillez choisir le lieu de survenance du décès.",
                },
                lieu_deces: {
                    required: "Veuillez choisir le lieu de décés.",
                },
                domicile_defunt: {
                required: "Veuillez entrer l'adresse du défunt",
                maxlength: "L'adresse ne doit dépasser 300 caractères."
                },
                nom_declarant: {
                    required: "Veuillez saisir le nom du déclarant.",
                    maxlength: "Le nom ne doit pas dépasser 50 caractères."
                },
                date_naissance_declarant: {
                    required: "Veuillez saisir la date de naissance du déclarant.",
                },
                lieu_naissance_declarant: {
                    required: "Veuillez saisir le lieu de naissance du déclarant.",
                },
                domicile_declarant: {
                    required: "Veuillez saisir l'adresse du déclarant.",
                },
                code_filiation_declarant: {
                    required: "Veuillez choisir la filiation du déclarant.",
                },
                },


        })

    </script>
    <script>
        $(document).ready(function()
        {
            function getQuartierVillage(codeparent,cle){
                var route = "{{ route('declarationNaissance.search.quartier.village',':id') }}";
                route = route.replace(":id", codeparent);
                var option = "<option> Selectionnez </option>";
                $.get(route, function (data) {
                    console.log(data.localites.code_localite);
                    for( var i=0; i < data.localites.length ; i++){
                        option +='<option value="'+data.localites[i].code_localite+'">'+data.localites[i].lib_localite;
                    }
                    option += +'</option>';
                    // var cle = 'domicile_quartier_defunt';
                    $("#"+cle).html(option);
                });
            }

            function getArrComUrbaine(codeparent,cle){
                var route = "{{ route('declarationNaissance.search.arrond.comurb',':id') }}";
                route = route.replace(":id", codeparent);
                var option = "<option> Selectionnez </option>";
                $.get(route, function (data) {
                    console.log(data.localites.code_localite);
                    for( var i=0; i < data.localites.length ; i++){
                        option +='<option value="'+data.localites[i].code_localite+'">'+data.localites[i].lib_localite;
                    }
                    option += +'</option>';
                    $("#"+cle).html(option);
                });
            }

            $('#departementcongo_defunt').hide();
            $('#domicile_pays_defunt').on('change', function () {
                var pays = $('#domicile_pays_defunt').val();
                if (pays == 'Congo') {
                    $('#departementcongo_defunt').show();
                    $('#autredepartement_defunt').hide();
                } else {
                    $('#departementcongo_defunt').hide();
                    $('#autredepartement_defunt').show();
                }
            });

            $('#departementcongo_conjoint').hide();
            $('#domicile_pays_conjoint').on('change', function () {
                var pays = $('#domicile_pays_conjoint').val();
                if (pays == 'Congo') {
                    $('#departementcongo_conjoint').show();
                    $('#autredepartement_conjoint').hide();
                } else {
                    $('#departementcongo_conjoint').hide();
                    $('#autredepartement_conjoint').show();
                }
            });

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

            $("#domicile_ville_defunt").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_arrondissement_defunt');
                }
                return false;
            });

            $("#domicile_arrondissement_defunt").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_quartier_defunt');
                }
                return false;
            });

            // Conjoint
            $("#domicile_ville_conjoint").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_arrondissement_conjoint');
                }
                return false;
            });

            $("#domicile_arrondissement_conjoint").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_quartier_conjoint');
                }
                return false;
            });

            // Père
            $("#domicile_ville_pere").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_arrondissement_pere');
                }
                return false;
            });

            $("#domicile_arrondissement_pere").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_quartier_pere');
                }
                return false;
            });

            // Mère
            $("#domicile_ville_mere").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_arrondissement_mere');
                }
                return false;
            });

            $("#domicile_arrondissement_mere").on("change", function(){
                var localiteParent = $(this).val();
                if(localiteParent != "" || localiteParent !=null){
                    getArrComUrbaine(localiteParent,'domicile_quartier_mere');
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
                    getArrComUrbaine(localiteParent,'domicile_quartier_declarant');
                }
                return false;
            });

            //Traitement input


            $('#clear_defunt').click(function()
            {
                $("#type_date_naissance_defunt").val("EXACTE");
                document.getElementById('type_date_naissance_defunt').checked="";
                document.getElementById('type_date_naissance_defunt').disabled = false;

                $('#nom_defunt').val("");
                document.getElementById('nom_defunt').readOnly = false;

                $('#prenom_defunt').val("");
                document.getElementById('prenom_defunt').readOnly = false;

                document.getElementById('sexe_defunt').disabled = false;

                $('#date_naissance_defunt').val("");
                document.getElementById('date_naissance_defunt').readOnly = false;

                $('#lieu_naissance_defunt').val("");
                document.getElementById('lieu_naissance_defunt').readOnly = false;
                //$('#telephone_defunt').val("");
                //document.getElementById('telephone_defunt').readOnly = false;

                $('#domicile_numero_defunt').val("");
                document.getElementById('domicile_numero_defunt').readOnly = false;

                $('#domicile_nomvoie_defunt').val("");
                document.getElementById('domicile_nomvoie_defunt').readOnly = false;

                $('#domicile_quartier_defunt').val("");
                document.getElementById('domicile_quartier_defunt').readOnly = false;

                $('#domicile_typevoie_defunt').val("");
                document.getElementById('domicile_typevoie_defunt').readOnly = false;



                var domicile_quartier_defunt = $("#domicile_quartier_defunt");
                    domicile_quartier_defunt.val("");
                    $("#domicile_quartier_defunt option:selected").text();
                document.getElementById('domicile_quartier_defunt').disabled = false;

                var domicile_typevoie_defunt = $("#domicile_typevoie_defunt");
                    domicile_typevoie_defunt.val("");
                    $("#domicile_typevoie_defunt option:selected").text();
                document.getElementById('domicile_typevoie_defunt').disabled = false;

                var code_profession_defunt = $("#code_profession_defunt");
                    code_profession_defunt.val("");
                    $("#code_profession_defunt option:selected").text();
                document.getElementById('code_profession_defunt').disabled = false;

                //var code_pays_defunt = $("#code_pays_defunt");
                    //code_pays_defunt.val("");
                  //  $("#code_pays_defunt option:selected").text();
                ///document.getElementById('code_pays_defunt').disabled = false;

                var code_nationalite_defunt = $("#code_nationalite_defunt");
                    code_nationalite_defunt.val("");
                    $("#code_nationalite_defunt option:selected").text();
                document.getElementById('code_nationalite_defunt').disabled = false;

                $('#numero_document_defunt').val("");
                //document.getElementById('numero_document_defunt').readOnly = true;


                //traitement select
                //document.getElementById('statut_personne_defunt').disabled = false;


                $('#code_nationalite_defunt').val("");
                document.getElementById('code_nationalite_defunt').disabled = false;

                $('#code_profession_defunt').val("");
                document.getElementById('code_profession_defunt').disabled = false;

                //document.getElementById('code_type_document_defunt').disabled = true;
                //$('#numero_document_defunt').val("");
                //document.getElementById('numero_document_defunt').readOnly = false;

                //$('#code_type_document_defunt').val("");
                //document.getElementById('code_type_document_defunt').readOnly = false;

                document.getElementById('niveau_instruction_defunt').disabled = false;

            });

            $('#clear_pere').click(function()
            {
                $("#type_date_naissance_pere").val("EXACTE");
                document.getElementById('type_date_naissance_pere').checked="";
                document.getElementById('type_date_naissance_pere').disabled = false;

                $('#nom_pere').val("");
                document.getElementById('nom_pere').readOnly = false;

                $('#prenom_pere').val("");
                document.getElementById('prenom_pere').readOnly = false;

                $('#date_naissance_pere').val("");
                document.getElementById('date_naissance_pere').readOnly = false;

                $('#lieu_naissance_pere').val("");
                document.getElementById('lieu_naissance_pere').readOnly = false;
                $('#telephone_pere').val("");
                document.getElementById('telephone_pere').readOnly = false;

                $('#domicile_numero_pere').val("");
                document.getElementById('domicile_numero_pere').readOnly = false;

                $('#domicile_nomvoie_pere').val("");
                document.getElementById('domicile_nomvoie_pere').readOnly = false;

                $('#domicile_quartier_pere').val("");
                document.getElementById('domicile_quartier_pere').readOnly = false;

                $('#domicile_typevoie_pere').val("");
                document.getElementById('domicile_typevoie_pere').readOnly = false;



                var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
                    domicile_arrondissement_pere.val("");
                    $("#domicile_arrondissement_pere option:selected").text();
                document.getElementById('domicile_arrondissement_pere').disabled = false;

                var code_profession_pere = $("#code_profession_pere");
                    code_profession_pere.val("");
                    $("#code_profession_pere option:selected").text();
                document.getElementById('code_profession_pere').disabled = false;

                var domicile_pays_pere = $("#domicile_pays_pere");
                    domicile_pays_pere.val("");
                    $("#domicile_pays_pere option:selected").text();
                document.getElementById('domicile_pays_pere').disabled = false;

                var code_nationalite_pere = $("#code_nationalite_pere");
                    code_nationalite_pere.val("");
                    $("#code_nationalite_pere option:selected").text();
                document.getElementById('code_nationalite_pere').disabled = false;

                $('#numero_document_pere').val("");
                //document.getElementById('numero_document_pere').readOnly = true;


                //traitement select
                document.getElementById('statut_personne_pere').disabled = false;


                $('#code_nationalite_pere').val("");
                document.getElementById('code_nationalite_pere').disabled = false;

                $('#code_profession_pere').val("");
                document.getElementById('code_profession_pere').disabled = false;

                //document.getElementById('code_type_document_pere').disabled = true;
                $('#numero_document_pere').val("");
                document.getElementById('numero_document_pere').readOnly = false;

                $('#code_type_document_pere').val("");
                document.getElementById('code_type_document_pere').readOnly = false;

                document.getElementById('niveau_instruction_pere').disabled = false;

            });


            $('#clear_mere').click(function()

            {
                $("#type_date_naissance_mere").val("EXACTE");
                document.getElementById('type_date_naissance_mere').checked="";
                document.getElementById('type_date_naissance_mere').disabled = false;

                $('#nom_mere').val("");
                document.getElementById('nom_mere').readOnly = false;

                $('#prenom_mere').val("");
                document.getElementById('prenom_mere').readOnly = false;

                $('#date_naissance_mere').val("");
                document.getElementById('date_naissance_mere').readOnly = false;

                $('#lieu_naissance_mere').val("");
                document.getElementById('lieu_naissance_mere').readOnly = false;
                $('#telephone_mere').val("");
                document.getElementById('telephone_mere').readOnly = false;

                $('#domicile_numero_mere').val("");
                document.getElementById('domicile_numero_mere').readOnly = false;

                $('#domicile_nomvoie_mere').val("");
                document.getElementById('domicile_nomvoie_mere').readOnly = false;

                $('#domicile_quartier_mere').val("");
                document.getElementById('domicile_quartier_mere').readOnly = false;

                $('#domicile_typevoie_mere').val("");
                document.getElementById('domicile_typevoie_mere').readOnly = false;

                document.getElementById('statut_personne_mere').disabled = false;

                var code_profession_mere = $("#code_profession_mere");
                    code_profession_mere.val("");
                    $("#code_profession_mere option:selected").text();
                document.getElementById('code_profession_mere').disabled = false;

                var domicile_pays_mere = $("#domicile_pays_mere");
                    domicile_pays_mere.val("");
                    $("#domicile_pays_mere option:selected").text();
                document.getElementById('domicile_pays_mere').disabled = false;

                var code_nationalite_mere = $("#code_nationalite_mere");
                    code_nationalite_mere.val("");
                    $("#code_nationalite_mere option:selected").text();
                document.getElementById('code_nationalite_mere').disabled = false;

                $('#numero_document_mere').val("");
                //document.getElementById('numero_document_mere').readOnly = true;


                //traitement select

                $('#code_nationalite_mere').val("");
                document.getElementById('code_nationalite_mere').disabled = false;

                $('#code_profession_mere').val("");
                document.getElementById('code_profession_mere').disabled = false;

                //document.getElementById('code_type_document_mere').disabled = true;
                $('#numero_document_mere').val("");
                document.getElementById('numero_document_mere').readOnly = false;

                $('#code_type_document_mere').val("");
                document.getElementById('code_type_document_mere').readOnly = false;

                document.getElementById('niveau_instruction_mere').disabled = false;
            });

            $('#clear_declarant').click(function()
            {
                $("#type_date_naissance_declarant").val("EXACTE");
                document.getElementById('type_date_naissance_declarant').checked="";
                document.getElementById('type_date_naissance_declarant').disabled = false;

                $('#nom_declarant').val("");
                document.getElementById('nom_declarant').readOnly = false;

                $('#prenom_declarant').val("");
                document.getElementById('prenom_declarant').readOnly = false;

                $('#date_naissance_declarant').val("");
                document.getElementById('date_naissance_declarant').readOnly = false;

                $('#lieu_naissance_declarant').val("");
                document.getElementById('lieu_naissance_declarant').readOnly = false;

                $('#telephone_declarant').val("");
                document.getElementById('telephone_declarant').readOnly = false;

                $('#domicile_numero_declarant').val("");
                document.getElementById('domicile_numero_declarant').readOnly = false;

                $('#domicile_nomvoie_declarant').val("");
                document.getElementById('domicile_nomvoie_declarant').readOnly = false;

                $('#domicile_quartier_declarant').val("");
                document.getElementById('domicile_quartier_declarant').readOnly = false;

                $('#domicile_typevoie_declarant').val("");
                document.getElementById('domicile_typevoie_declarant').readOnly = false;

                document.getElementById('sexe_declarant').disabled = false;

                var domicile_arrondissement_declarant = $("#domicile_arrondissement_declarant");
                    domicile_arrondissement_declarant.val("");
                    $("#domicile_arrondissement_declarant option:selected").text();
                document.getElementById('domicile_arrondissement_declarant').disabled = false;

                var code_profession_declarant = $("#code_profession_declarant");
                    code_profession_declarant.val("");
                    $("#code_profession_declarant option:selected").text();
                document.getElementById('code_profession_declarant').disabled = false;

                var domicile_pays_declarant = $("#domicile_pays_declarant");
                    domicile_pays_declarant.val("");
                    $("#domicile_pays_declarant option:selected").text();
                document.getElementById('domicile_pays_declarant').disabled = false;

                var code_nationalite_declarant = $("#code_nationalite_declarant");
                    code_nationalite_declarant.val("");
                    $("#code_nationalite_declarant option:selected").text();
                document.getElementById('code_nationalite_declarant').disabled = false;

                $('#numero_document_declarant').val("");
                //document.getElementById('numero_document_declarant').readOnly = true;


                //traitement select

                document.getElementById('sexe_declarant').disabled = false;

                $('#code_filiation_declarant').val("");
                document.getElementById('code_filiation_declarant').disabled = false;

                $('#code_nationalite_declarant').val("");
                document.getElementById('code_nationalite_declarant').disabled = false;

                $('#code_profession_declarant').val("");
                document.getElementById('code_profession_declarant').disabled = false;

                //document.getElementById('code_type_document_declarant').disabled = true;
                $('#numero_document_declarant').val("");
                document.getElementById('numero_document_declarant').readOnly = false;

                $('#code_type_document_declarant').val("");
                document.getElementById('code_type_document_declarant').readOnly = false;

                document.getElementById('niveau_instruction_declarant').disabled = false;
            });



            $('#clear_conjoint').click(function()
            {
                $("#num_acte_mariage").val("");
                $("#cec_mariage").val("");
                $("#regime").val("");
                $("#date_mariage").val("");

                $("#code_conjoint").val("");
                $('#statut_personne_conjoint').val("");

                document.getElementById('statut_personne_conjoint').disable = false;


                $("#type_date_naissance_conjoint").val("");
                document.getElementById('type_date_naissance_conjoint').checked="";
                document.getElementById('type_date_naissance_conjoint').disabled=false;

                $('#nom_conjoint').val("");
                document.getElementById('nom_conjoint').readOnly = false;

                $('#prenom_conjoint').val("");
                document.getElementById('prenom_conjoint').readOnly = false;

                // $('#lieu_naissance_conjoint').val("");
                document.getElementById('lieu_naissance_conjoint').disabled = false;

                $('#date_naissance_conjoint').val("");
                document.getElementById('date_naissance_conjoint').readOnly = false;

               // {{--  $('#domicile_conjoint').val("");
                //document.getElementById('domicile_conjoint').readOnly = false;  --}}

                // $('#telephone_conjoint').val("");
                document.getElementById('telephone_conjoint').disabled = false;

                $('#domicile_numero_conjoint').val("");
                document.getElementById('domicile_numero_conjoint').readOnly = false;

                $('#domicile_nomvoie_conjoint').val("");
                document.getElementById('domicile_nomvoie_conjoint').readOnly = false;

                $('#domicile_quartier_conjoint').val("");
                document.getElementById('domicile_quartier_conjoint').readOnly = false;

                $('#domicile_typevoie_conjoint').val("");
                document.getElementById('domicile_typevoie_conjoint').readOnly = false;

                document.getElementById('sexe_conjoint').disabled = false;
                document.getElementById('domicile_typevoie_conjoint').disabled = false;
                document.getElementById('domicile_pays_conjoint').disabled = false;
                document.getElementById('code_type_document_conjoint').disabled = false;
                document.getElementById('statut_personne_conjoint').disabled = false;


                var domicile_arrondissement_conjoint = $("#domicile_arrondissement_conjoint");
                    domicile_arrondissement_conjoint.val("");
                    $("#domicile_arrondissement_conjoint option:selected").text();
                document.getElementById('domicile_arrondissement_conjoint').disabled = false;

                var code_profession_conjoint = $("#code_profession_conjoint");
                    code_profession_conjoint.val("");
                    $("#code_profession_conjoint option:selected").text();
                document.getElementById('code_profession_conjoint').disabled = false;

                var domicile_pays_conjoint = $("#domicile_pays_conjoint");
                    domicile_pays_conjoint.val("");
                    $("#domicile_pays_conjoint option:selected").text();
                document.getElementById('domicile_pays_conjoint').disabled = false;

                var code_nationalite_conjoint = $("#code_nationalite_conjoint");
                    code_nationalite_conjoint.val("");
                    $("#code_nationalite_conjoint option:selected").text();
                document.getElementById('code_nationalite_conjoint').disabled = false;

                $('#numero_document_conjoint').val("");
                document.getElementById('numero_document_conjoint').readOnly = false;

                $('#code_type_document_conjoint').val("");
                document.getElementById('code_type_document_conjoint').readOnly = false;
            });



           $('.validation-wizard').click(function()
            {

                $('.FIL_0001').hide();
                $('.FIL_0002').hide();
                document.getElementById("nom_conjoint").required = true;
                document.getElementById("prenom_conjoint").required = true;
                document.getElementById("date_naissance_conjoint").required = true;
                document.getElementById("lieu_naissance_conjoint").required = true;


                if($('#statut_personne_mere').val()==="DECEDE")
                {

                    document.getElementById('hide_mere').style.visibility = 'hidden';
                    document.getElementById('declarant_click').style.visibility = 'visible';

                }
                if($('#statut_personne_mere').val()==="VIVANT")
                {
                    document.getElementById('hide_mere').style.visibility = 'visible';
                }


                if($('#statut_personne_pere').val()==="DECEDE")
                {
                    document.getElementById('hide_pere').style.visibility = 'hidden';
                    document.getElementById('declarant_click').style.visibility = 'visible';

                }
                if($('#statut_personne_pere').val()==="VIVANT")
                {
                    document.getElementById('hide_pere').style.visibility = 'visible';
                }


                if((($('#code_situation_matrimoniale_defunt').val()!="SMAT_0001")))
                {
                   $('#code_conjoint').val("");
                   $("#num_acte_mariage").val("");
                   $("#cec_mariage").val("");
                   $("#regime").val("");
                   $("#date_mariage").val("");

                   $("#code_conjoint").val("");
                   $('#statut_personne_conjoint').val("");

                   document.getElementById("nom_conjoint").required = false;
                   document.getElementById("prenom_conjoint").required = false;
                   document.getElementById("date_naissance_conjoint").required = false;
                   document.getElementById("lieu_naissance_conjoint").required = false;

                   document.getElementById('statut_personne_conjoint').disable = false;


                   $("#type_date_naissance_conjoint").val("");
                   document.getElementById('type_date_naissance_conjoint').checked="";
                   document.getElementById('type_date_naissance_conjoint').disabled=false;

                   $('#nom_conjoint').val("");
                   document.getElementById('nom_conjoint').readOnly = false;

                   $('#prenom_conjoint').val("");
                   document.getElementById('prenom_conjoint').readOnly = false;

                   $('#lieu_naissance_conjoint').val("");
                   document.getElementById('lieu_naissance_conjoint').readOnly = false;

                   $('#date_naissance_conjoint').val("");
                   document.getElementById('date_naissance_conjoint').readOnly = false;

                  // {{--  $('#domicile_conjoint').val("");
                   //document.getElementById('domicile_conjoint').readOnly = false;  --}}

                //    $('#telephone_conjoint').val("");
                   document.getElementById('telephone_conjoint').disabled = false;

                   $('#domicile_numero_conjoint').val("");
                   document.getElementById('domicile_numero_conjoint').readOnly = false;

                   $('#domicile_nomvoie_conjoint').val("");
                   document.getElementById('domicile_nomvoie_conjoint').readOnly = false;

                   $('#domicile_quartier_conjoint').val("");
                   document.getElementById('domicile_quartier_conjoint').readOnly = false;

                   $('#domicile_typevoie_conjoint').val("");
                   document.getElementById('domicile_typevoie_conjoint').readOnly = false;



                   document.getElementById('sexe_conjoint').disabled = false;
                   document.getElementById('domicile_typevoie_conjoint').disabled = false;
                   document.getElementById('domicile_pays_conjoint').disabled = false;
                   document.getElementById('code_type_document_conjoint').disabled = false;
                   document.getElementById('statut_personne_conjoint').disabled = false;


                   var domicile_arrondissement_conjoint = $("#domicile_arrondissement_conjoint");
                       domicile_arrondissement_conjoint.val("");
                       $("#domicile_arrondissement_conjoint option:selected").text();
                //    document.getElementById('domicile_arrondissement_conjoint').disabled = false;

                   var code_profession_conjoint = $("#code_profession_conjoint");
                       code_profession_conjoint.val("");
                       $("#code_profession_conjoint option:selected").text();
                   document.getElementById('code_profession_conjoint').disabled = false;

                   var domicile_pays_conjoint = $("#domicile_pays_conjoint");
                       domicile_pays_conjoint.val("");
                       $("#domicile_pays_conjoint option:selected").text();
                   document.getElementById('domicile_pays_conjoint').disabled = false;

                   var code_nationalite_conjoint = $("#code_nationalite_conjoint");
                       code_nationalite_conjoint.val("");
                       $("#code_nationalite_conjoint option:selected").text();
                   document.getElementById('code_nationalite_conjoint').disabled = false;

                   $('#numero_document_conjoint').val("");
                   document.getElementById('numero_document_conjoint').readOnly = false;

                   $('#code_type_document_conjoint').val("");
                   document.getElementById('code_type_document_conjoint').readOnly = false;
                   document.getElementById('nom_conjoint').readOnly = true;
                   document.getElementById('prenom_conjoint').readOnly = true;
                   document.getElementById('date_naissance_conjoint').readOnly = true;
                   document.getElementById('date_mariage').readOnly = true;
                   document.getElementById('cec_mariage').readOnly = true;
                   document.getElementById('lieu_naissance_conjoint').disabled = true;
                   document.getElementById('telephone_conjoint').disabled = true;
                   document.getElementById('code_nationalite_conjoint').disabled = true;
                   document.getElementById('sexe_conjoint').disabled = true;
                   document.getElementById('domicile_pays_conjoint').disabled = true;
                   document.getElementById('code_type_document_conjoint').disabled = true;
                   document.getElementById('statut_personne_conjoint').disabled = true;
                   document.getElementById('code_profession_conjoint').disabled = true;
                   document.getElementById('code_regime').disabled = true;
                   document.getElementById('niveau_instruction_conjoint').disabled = true;
                   document.getElementById('domicile_numero_conjoint').readOnly = true;
                   document.getElementById('domicile_nomvoie_conjoint').readOnly = true;
                   document.getElementById('domicile_quartier_conjoint').readOnly = true;
                   document.getElementById('domicile_typevoie_conjoint').readOnly = true;
                   document.getElementById('domicile_arrondissement_conjoint').disabled = true;
                   document.getElementById('domicile_pays_conjoint').disabled = true;
                   document.getElementById('num_acte_mariage').readOnly = true;
                   document.getElementById('statut_personne_conjoint').disabled = true;

                   document.getElementById('code_type_document_conjoint').disabled = true;
                   document.getElementById('numero_document_conjoint').readOnly = true;

                   document.getElementById('search_conjoint').style.visibility = 'hidden';
                   document.getElementById('clear_conjoint').style.visibility = 'hidden';
                   document.getElementById('conjoint_click').style.visibility = 'hidden';


                }
                else{
                    if($('#code_conjoint').val()==="")
                    {
                        document.getElementById('nom_conjoint').readOnly = false;
                        document.getElementById('prenom_conjoint').readOnly = false;
                        document.getElementById('date_naissance_conjoint').readOnly = false;
                        document.getElementById('lieu_naissance_conjoint').disabled = false;
                        document.getElementById('telephone_conjoint').disabled = false;
                        document.getElementById('code_nationalite_conjoint').disabled = false;
                        document.getElementById('sexe_conjoint').disabled = false;
                        document.getElementById('domicile_typevoie_conjoint').disabled = false;
                        document.getElementById('domicile_pays_conjoint').disabled = false;
                        document.getElementById('code_type_document_conjoint').disabled = false;
                        document.getElementById('statut_personne_conjoint').disabled = false;
                        document.getElementById('code_profession_conjoint').disabled = false;
                    }

                    if($('#code_conjoint').val()!="")
                    {
                        document.getElementById('nom_conjoint').readOnly = true;
                        document.getElementById('prenom_conjoint').readOnly = true;
                        document.getElementById('date_naissance_conjoint').readOnly = true;
                        document.getElementById('lieu_naissance_conjoint').disabled = true;
                        document.getElementById('telephone_conjoint').disabled = true;
                        document.getElementById('code_nationalite_conjoint').disabled = true;
                        document.getElementById('sexe_conjoint').disabled = true;
                        document.getElementById('domicile_typevoie_conjoint').disabled = true;
                        document.getElementById('domicile_pays_conjoint').disabled = true;
                        document.getElementById('code_type_document_conjoint').disabled = true;
                        document.getElementById('statut_personne_conjoint').disabled = true;
                        document.getElementById('code_profession_conjoint').disabled = true;
                    }

                    document.getElementById('niveau_instruction_conjoint').disabled = false;
                    document.getElementById('domicile_numero_conjoint').readOnly = false;
                    document.getElementById('domicile_nomvoie_conjoint').readOnly = false;
                    document.getElementById('domicile_quartier_conjoint').readOnly = false;
                    document.getElementById('domicile_typevoie_conjoint').readOnly = false;
                    document.getElementById('domicile_arrondissement_conjoint').disabled = false;
                    document.getElementById('domicile_pays_conjoint').disabled = false;

                     document.getElementById('date_mariage').readOnly = false;
                     document.getElementById('cec_mariage').readOnly = false;
                     document.getElementById('code_regime').disabled = false;
                     document.getElementById('num_acte_mariage').readOnly = false;
                     document.getElementById('search_conjoint').style.visibility = 'visible';
                     document.getElementById('clear_conjoint').style.visibility = 'visible';
                     document.getElementById('statut_personne_conjoint').disabled = false;
                     document.getElementById('type_date_naissance_conjoint').disabled = false;
                     document.getElementById('code_type_document_conjoint').disabled = false;
                     document.getElementById('numero_document_conjoint').readOnly = false;


                    if(($('#statut_personne_conjoint').val()==="DECEDE"))
                        {
                            document.getElementById('conjoint_click').style.visibility = 'hidden';
                            document.getElementById('declarant_click').style.visibility = 'visible';
                        }

                        if(($('#statut_personne_conjoint').val()==="VIVANT"))
                {
                    document.getElementById('conjoint_click').style.visibility = 'visible';
                }


                }

            });


            var nom_pere = $("#nom_pere");
            var prenom_pere = $("#prenom_pere");
            var date_naissance_pere = $("#date_naissance_pere");
            var lieu_naissance_pere = $("#lieu_naissance_pere");
            //var domicile_pere = $("#domicile_pere");
            var domicile_numero_pere = $("#domicile_numero_pere");
            var domicile_nomvoie_pere = $("#domicile_nomvoie_pere");
            var domicile_quartier_pere = $("#domicile_quartier_pere");
            var domicile_typevoie_pere = $("#domicile_typevoie_pere");
            var domicile_arrondissement_pere = $("#domicile_arrondissement_pere");
            var domicile_pays_pere = $("#domicile_pays_pere");
            var telephone_pere = $("#telephone_pere");
            var code_profession_pere = $("#code_profession_pere");
            var code_nationalite_pere = $("#code_nationalite_pere");
            var niveau_instruction_pere = $("#niveau_instruction_pere");
            var code_type_document_pere = $("#code_type_document_pere");
            var numero_document_pere = $("#numero_document_pere");

            //information mère
            var nom_mere = $("#nom_mere");
            var prenom_mere = $("#prenom_mere");
            var date_naissance_mere = $("#date_naissance_mere");
            var lieu_naissance_mere = $("#lieu_naissance_mere");
            var domicile_numero_mere = $("#domicile_numero_mere");
            var domicile_nomvoie_mere = $("#domicile_nomvoie_mere");
            var domicile_quartier_mere = $("#domicile_quartier_mere");
            var domicile_typevoie_mere = $("#domicile_typevoie_mere");
            var domicile_arrondissement_mere = $("#domicile_arrondissement_mere");
            var domicile_pays_mere = $("#domicile_pays_mere");
            var telephone_mere = $("#telephone_mere");
            var code_profession_mere = $("#code_profession_mere");
            var code_nationalite_mere = $("#code_nationalite_mere");
            var niveau_instruction_mere = $("#niveau_instruction_mere");
            var code_type_document_mere = $("#code_type_document_mere");
            var numero_document_mere = $("#numero_document_mere");

            //information conjoint
            var nom_conjoint = $("#nom_conjoint");
            var prenom_conjoint = $("#prenom_conjoint");
            var date_mariage = $("#date_mariage");
            var cec_mariage = $("#cec_mariage");
            var code_regime = $("#code_regime");
            var sexe_conjoint = $("#sexe_conjoint");
            var domicile_pays_conjoint = $("#domicile_pays_conjoint");
            var code_type_document_conjoint = $("#code_type_document_conjoint");
            var statut_personne_conjoint = $("#statut_personne_conjoint");
            var num_acte_mariage = $("#num_acte_mariage");
            var date_naissance_conjoint = $("#date_naissance_conjoint");
            var code_profession_conjoint = $("#code_profession_conjoint");
            var code_nationalite_conjoint =  $("#code_nationalite_conjoint");
            var telephone_conjoint = $("#telephone_conjoint");
            var lieu_naissance_conjoint = $("#lieu_naissance_conjoint");
            var code_type_document_conjoint = $("#code_type_document_conjoint");
            var numero_document_conjoint = $("#numero_document_conjoint");

            var domicile_numero_conjoint = $("#domicile_numero_conjoint");
            var domicile_nomvoie_conjoint = $("#domicile_nomvoie_conjoint");
            var domicile_quartier_conjoint = $("#domicile_quartier_conjoint");
            var domicile_typevoie_conjoint = $("#domicile_typevoie_conjoint");
            var domicile_arrondissement_conjoint= $("#domicile_arrondissement_conjoint");
            var domicile_pays_conjoint = $("#domicile_pays_conjoint");

            var niveau_instruction_conjoint= $("#niveau_instruction_conjoint");

            //information déclarant
            var nom_declarant = $("#nom_declarant");
            var prenom_declarant = $("#prenom_declarant");
            var date_naissance_declarant = $("#date_naissance_declarant");
            var lieu_naissance_declarant = $("#lieu_naissance_declarant");
            //var domicile_declarant = $("#domicile_declarant");
            var domicile_numero_declarant = $("#domicile_numero_declarant");
            var domicile_nomvoie_declarant = $("#domicile_nomvoie_declarant");
            var domicile_quartier_declarant = $("#domicile_quartier_declarant");
            var domicile_typevoie_declarant = $("#domicile_typevoie_declarant");
            var domicile_arrondissement_declarant= $("#domicile_arrondissement_declarant");
            var sexe_declarant = $("#sexe_declarant");
            var code_filiation_declarant = $("#code_filiation_declarant");
            var telephone_declarant = $("#telephone_declarant");
            var code_profession_declarant = $("#code_profession_declarant");
            var code_nationalite_declarant = $("#code_nationalite_declarant");
            var code_cause_deces = $("#code_cause_deces");
            var domicile_pays_declarant = $("#domicile_pays_declarant");
            var code_type_document_declarant = $("#code_type_document_declarant");
            var numero_document_declarant = $("#numero_document_declarant");
            var niveau_instruction_declarant = $("#niveau_instruction_declarant");

            $('input:radio[name="autredeclarant"]').change(function(){

                var declarant = $(this).val();

                if(declarant != "" || declarant != null)
                {
                    //alert(nom_pere.val());
                    if(declarant == "pere")
                    {
                       sexe_declarant = sexe_declarant.val("M");
                        //alert(sexe_declarant);
                       nom_declarant.val(nom_pere.val());
                       prenom_declarant.val(prenom_pere.val());
                       date_naissance_declarant.val(date_naissance_pere.val());
                       lieu_naissance_declarant.val(lieu_naissance_pere.val());
                       domicile_numero_declarant.val(domicile_numero_pere.val());
                       domicile_nomvoie_declarant.val(domicile_nomvoie_pere.val());
                       domicile_quartier_declarant.val(domicile_quartier_pere.val());
                       domicile_typevoie_declarant.val(domicile_typevoie_pere.val());
                       domicile_arrondissement_declarant.val(domicile_arrondissement_pere.val());

                       domicile_pays_declarant.val(domicile_pays_pere.val());
                       telephone_declarant.val(telephone_pere.val());

                       code_profession_declarant.val(code_profession_pere.val());
                       $("#code_profession_declarant option:selected").text();
                       code_nationalite_declarant.val(code_nationalite_pere.val());
                       $("#code_nationalite_declarant option:selected").text();
                       //$("#sexe_declarant option[value=M]").attr('selected','selected');
                       $("#sexe_declarant option:selected").text();
                       code_filiation_declarant = code_filiation_declarant.val("FIL_0001");
                       $("#code_filiation_declarant option:selected").text();
                       code_type_document_declarant.val(code_type_document_pere.val());
                       $("#code_type_document_declarant option:selected").text();
                       numero_document_declarant.val(numero_document_pere.val());

                       niveau_instruction_declarant.val(niveau_instruction_pere.val());
                       $("#niveau_instruction_declarant option:selected").text();

                       document.getElementById('nom_declarant').readOnly = true;
                       document.getElementById('lieu_naissance_declarant').readOnly = true;
                       document.getElementById('code_nationalite_declarant').disabled = true;
                       document.getElementById('sexe_declarant').disabled = true;
                       document.getElementById('prenom_declarant').readOnly = true;
                       document.getElementById('code_filiation_declarant').disabled = true;
                       document.getElementById('date_naissance_declarant').readOnly = true;

                       document.getElementById('declarant_click').style.visibility = 'hidden';

                    }

                    else if(declarant == "mere")
                    {
                        document.getElementById('declarant_click').style.visibility = 'hidden';
                        sexe_declarant = sexe_declarant.val("F");
                        //alert(sexe_declarant);
                        nom_declarant.val(nom_mere.val());
                        prenom_declarant.val(prenom_mere.val());
                        date_naissance_declarant.val(date_naissance_mere.val());
                        lieu_naissance_declarant.val(lieu_naissance_mere.val());

                        domicile_numero_declarant.val(domicile_numero_mere.val());
                        domicile_nomvoie_declarant.val(domicile_nomvoie_mere.val());
                        domicile_quartier_declarant.val(domicile_quartier_mere.val());
                        domicile_typevoie_declarant.val(domicile_typevoie_mere.val());
                        domicile_arrondissement_declarant.val(domicile_arrondissement_mere.val());

                        domicile_pays_declarant.val(domicile_pays_mere.val());
                        telephone_declarant.val(telephone_mere.val());
                        code_profession_declarant.val(code_profession_mere.val());
                        $("#code_profession_declarant option:selected").text();
                        code_nationalite_declarant.val(code_nationalite_mere.val());
                        $("#code_nationalite_declarant option:selected").text();
                       // $("#sexe_declarant option[value=F]").attr('selected','selected');
                        $("#sexe_declarant option:selected").text();
                        code_filiation_declarant = code_filiation_declarant.val("FIL_0002");
                        $("#code_filiation_declarant option:selected").text();
                        code_type_document_declarant.val(code_type_document_mere.val());

                        $("#code_type_document_declarant option:selected").text();

                        niveau_instruction_declarant.val(niveau_instruction_mere.val());
                        $("#niveau_instruction_declarant option:selected").text();

                        numero_document_declarant.val(numero_document_mere.val());

                        document.getElementById('nom_declarant').readOnly = true;
                        document.getElementById('lieu_naissance_declarant').readOnly = true;
                        document.getElementById('code_nationalite_declarant').disabled = true;
                        document.getElementById('sexe_declarant').disabled = true;
                        document.getElementById('prenom_declarant').readOnly = true;
                        document.getElementById('code_filiation_declarant').disabled = true;
                        document.getElementById('date_naissance_declarant').readOnly = true;
                        document.getElementById('telephone_declarant').readOnly = true;
                        document.getElementById('domicile_pays_declarant').disabled = true;

                    }

                    else if(declarant == "conjoint")
                    {
                        document.getElementById('declarant_click').style.visibility = 'hidden';

                        sexe_declarant = sexe_declarant.val("M");
                        //alert(sexe_declarant);
                       nom_declarant.val(nom_conjoint.val());
                       prenom_declarant.val(prenom_conjoint.val());
                       date_naissance_declarant.val(date_naissance_conjoint.val());
                       lieu_naissance_declarant.val(lieu_naissance_conjoint.val());
                       domicile_numero_declarant.val(domicile_numero_conjoint.val());
                       domicile_nomvoie_declarant.val(domicile_nomvoie_conjoint.val());
                       domicile_quartier_declarant.val(domicile_quartier_conjoint.val());
                       domicile_typevoie_declarant.val(domicile_typevoie_conjoint.val());
                       domicile_arrondissement_declarant.val(domicile_arrondissement_conjoint.val());
                       $("#domicile_arrondissement_declarant option:selected").text();

                       domicile_pays_declarant.val(domicile_pays_conjoint.val());
                       $("#domicile_pays_declarant option:selected").text();

                       telephone_declarant.val(telephone_conjoint.val());

                       code_profession_declarant.val(code_profession_conjoint.val());
                       $("#code_profession_declarant option:selected").text();
                       code_nationalite_declarant.val(code_nationalite_conjoint.val());
                       $("#code_nationalite_declarant option:selected").text();
                       //$("#sexe_declarant option[value=M]").attr('selected','selected');
                       $("#sexe_declarant option:selected").text();
                       code_filiation_declarant = code_filiation_declarant.val("FIL_0008");
                       $("#code_filiation_declarant option:selected").text();
                       code_type_document_declarant.val(code_type_document_conjoint.val());
                       $("#code_type_document_declarant option:selected").text();
                       numero_document_declarant.val(numero_document_conjoint.val());

                       niveau_instruction_declarant.val(niveau_instruction_conjoint.val());
                       $("#niveau_instruction_declarant option:selected").text();

                       document.getElementById('nom_declarant').readOnly = true;
                       document.getElementById('lieu_naissance_declarant').readOnly = true;
                       document.getElementById('code_nationalite_declarant').disabled = true;
                       document.getElementById('sexe_declarant').disabled = true;
                       document.getElementById('prenom_declarant').readOnly = true;
                       document.getElementById('code_filiation_declarant').disabled = true;
                       document.getElementById('date_naissance_declarant').readOnly = true;

                       document.getElementById('declarant_click').style.visibility = 'hidden';
                    }

                    else if (declarant == "autre")
                    {
                        nom_declarant.val("");
                        document.getElementById('nom_declarant').readOnly = false;

                        prenom_declarant.val("");
                        document.getElementById('prenom_declarant').readOnly = false;

                        date_naissance_declarant.val("");
                        document.getElementById('date_naissance_declarant').readOnly = false;



                        lieu_naissance_declarant.val("");
                        document.getElementById('lieu_naissance_declarant').readOnly = false;

                        domicile_numero_declarant.val("");
                        document.getElementById('domicile_numero_declarant').readOnly = false;

                        domicile_nomvoie_declarant.val("");
                        document.getElementById('domicile_nomvoie_declarant').readOnly = false;

                        domicile_quartier_declarant.val("");
                        document.getElementById('domicile_quartier_declarant').readOnly = false;

                        domicile_typevoie_declarant.val("");
                        document.getElementById('domicile_typevoie_declarant').readOnly = false;


                        domicile_arrondissement_declarant.val("");
                        document.getElementById('domicile_arrondissement_declarant').disabled = false;

                        domicile_pays_declarant.val("");
                        document.getElementById('domicile_pays_declarant').disabled = false;

                        telephone_declarant.val("");
                        document.getElementById('telephone_declarant').readOnly = false;

                        code_profession_declarant.val("");
                        $("#code_profession_declarant option:selected").text();

                        code_nationalite_declarant.val("");
                        $("#code_nationalite_declarant option:selected").text();

                        sexe_declarant = sexe_declarant.val("M");
                        $("#sexe_declarant option:selected").text();

                        code_filiation_declarant = code_filiation_declarant.val("");
                        $("#code_filiation_declarant option:selected").text();

                        code_type_document_declarant.val("");
                        $("#code_type_document_declarant option:selected").text();
                        numero_document_declarant.val("");


                        document.getElementById('declarant_click').style.visibility = 'visible';
                        document.getElementById('nom_declarant').readOnly = false;
                        document.getElementById('lieu_naissance_declarant').readOnly = false;
                        document.getElementById('code_nationalite_declarant').disabled = false;
                        document.getElementById('sexe_declarant').disabled = false;
                        document.getElementById('prenom_declarant').readOnly = false;
                        document.getElementById('code_filiation_declarant').disabled = false;
                        document.getElementById('date_naissance_declarant').readOnly = false;
                        document.getElementById('type_date_naissance_declarant').disabled = false;
 }

                }

            });

        });
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


















