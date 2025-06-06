
@extends('layout.app')
@section('titre')
Rectification Acte
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Formulaire de rectification d'acte</h4>
                 <a href="{{ route("rectification.index") }}"><button type="button" class="btn btn-sm btn-warning">Liste des rectifications</button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- <h5> <i class="flaticon-381-file-1"></i><strong style="font-weight:bolder"> Informations sur réquerant </strong></h5><hr> --}}
                             <div class="ligne">
                                <h4>Informations du réquerant</h4>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="nom_requerant" onkeyup="this.value = this.value.toUpperCase();">
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="prenom_requerant" onkeyup="this.value = this.value.replace(/\b\w/g, function(l) { return l.toUpperCase() })">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="telephone_requerant">
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Lien de parenté</label>
                                <select class="form-control" id="code_filiation_requerant">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($filiations as $item)
                                        <option value="{{ $item->code_filiation }}">{{ $item->lib_filiation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="ligne">
                                <h4>ADRESSE</h4>
                            </div>
                             <div class="mb-2 col-md-3">
                                <label class="form-label">Département <span class="text-danger">*</span></label>
                                <select class="form-control" id="code_departement">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($localites as $item)
                                        <option value="{{ $item->code_localite }}">{{ $item->lib_localite }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Commune/District <span class="text-danger">*</span></label>
                                <select class="form-control" id="sub_departement">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Arrondissement/communauté <span class="text-danger">*</span></label>
                                <select class="form-control" id="sub_arrondissement">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Quartier/village <span class="text-danger">*</span></label>
                                <select class="form-control" id="quartier">
                                    <option selected disabled>Sélectionner</option>

                                </select>
                            </div>

                            <div class="mb-2 col-md-3">
                                <label class="form-label">Type voie<span class="text-danger"></span></label>
                                <select class="form-control" id="domicile_typevoie_requerant">
                                    <option value="">Choisir</option>
                                    <option value="Avenue">Avenue</option>
                                    <option value="Boulevard">Boulevard</option>
                                    <option value="Impasse">Impasse</option>
                                    <option value="Rue">Rue</option>
                                    <option value="Autre">Autre</option>
                                </select>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">N° voie<span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="domicile_numero_requerant" placeholder="N° voie">
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Nom voie<span class="text-danger"></span></label>
                                <input type="text" class="form-control" id="domicile_nomvoie_requerant" placeholder="Nom voie" style="text-transform: capitalize">
                            </div>


                            {{-- <h5> <i class="flaticon-381-file-1"></i><strong style="font-weight:bolder"> Informations sur l'acte </strong></h5><hr> --}}
                            <div class="ligne">
                                <h4>Informations sur l'acte</h4>
                            </div>
                            <div class="mb-2 col-md-5">
                                <label class="form-label">Type d'acte <span class="text-danger">*</span></label>
                                <select type="text" class="form-control" id="type_acte">
                                    <option selected disabled>Sélectionner</option>
                                    @foreach ($typesActe as $type)
                                           <option value="{{ $type->code_type_acte }}" class="">{{ $type->lib_type_acte }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-2 col-md-5">
                                <label class="form-label">Numéro de l'acte <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"  placeholder="" id="numero_acte">
                            </div>

                             <div class="mb-2 col-md-2">
                                <button class="btn btn-success" style="margin-top:30px; height:45px" id="btn_afficher_form">Continuer</button>
                            </div>
                        </div>
                        <div class="row" style="margin-top:30px" id="form_rectification">
                            {{-- <h5> <i class="flaticon-381-layer-1"></i><strong style="font-weight:bolder"> Détails de la rectification </strong></h5><hr> --}}
                            <div class="ligne">
                                <h4>Détail de la rectification</h4>
                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Rubrique </label>
                                <select class="form-control"  id="rubrique">
                                    <option value="" selected disabled>Sélectionner</option>
                                   @foreach ($rubriques as $r)
                                           <option value="{{ $r->code_rubrique."-".$r->lib_technique."-".$r->entite_rubrique }}" class="{{ $r->code_type_acte }}">{{ $r->lib_rubrique }}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="mb-2 col-md-3">
                                <label class="form-label">Ancienne valeur </label>
                                <input type="tel" class="form-control" readonly id="anciennce_valeur">

                            </div>

                             <div class="mb-2 col-md-3">
                                <label class="form-label">Nouvelle valeur </label>
                                <input type="tel" class="form-control"  id="nouvelle_valeur">

                            </div>

                            <div class="mb-2 col-md-3">
                                <button class="btn btn-success form-control" style="margin-top:30px; height:45px;color:white" id="btn_enregistrer_rectification">  Enregistrer</button>

                            </div>

                            <hr>
                            {{-- Affichage du bouton d'impression si des rectifications sont présentes --}}
                            <div class="mb-2 col-md-12">
                                <button class="btn btn-primary" id="btn_imprimer_rectification" style="margin-top:30px; height:45px; color:white">Imprimer la rectification</button>
                            </div>

                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <td>Rubrique</td>
                                            <th>Ancienne valeur</th>
                                            <th>Nouvelle valeur</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_rectification">



                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>

        $(function(){

            $("#form_rectification").hide();
             $("#type_acte").focus(function(){
                $("#rubrique option").show();
            });

            //gestionnaire du fomulaire de rectification
            $("#btn_afficher_form").on("click",function(){
                var numeroActe = $("#numero_acte").val();
                var typeacte = $("#type_acte").val();

                //vider le tableau des rectifications
                 $("#tbody_rectification").empty();

                if(numeroActe == "" ){
                    flashAlert("Réponse","error","Veuillez renseigner le numéro d'acte");
                    return;
                }
                if(typeacte == ""){
                    flashAlert("Réponse","error","Veuillez renseigner le type d'acte");
                    return;
                }
                //appel de la fonction de recherche de l'acte
                rechercherActe(numeroActe,typeacte);

            });

            //grestionnaire du filtre des rubriques
            $("#type_acte").change(function(){
                $("#form_rectification").hide();
                var typeActe = $(this).val();
                if(typeActe==""){
                    $("#rubrique option").show();
                }
                $("#rubrique option[class!="+typeActe+"]").hide();
            });
            //rucuperation de l'ancienne valeure de rubrique selectionnée
            $("#rubrique").change(function(){
                var rubrique = $(this).val();
                var type_acte = $("#type_acte").val();
                var numero_acte = $("#numero_acte").val();

                //route recuperation de l'ancienne valeur
               var route = "{{ route('rectification.recup-old-value') }}";

                $.post(route, {rubrique:rubrique,numero_acte:numero_acte,type_acte:type_acte}, function(reponse) {
                    console.log(reponse);
                    $("#anciennce_valeur").val(reponse);
                });

            });

            // enregistrement de rectification btn_enregistrer_rectification
            $("#btn_enregistrer_rectification").on("click",function(){
                var route = "{{ route('rectification.store') }}";
                var rubrique = $("#rubrique").val();
                var typeActe = $("#type_acte").val();
                var numeroActe = $("#numero_acte").val();
                var anienneValeur = $("#anciennce_valeur").val();
                var newValeur = $("#nouvelle_valeur").val();
                //recupération des informations du réquerant
                var filiationRequerant = $("#code_filiation_requerant").val();
                var nomRequerant = $("#nom_requerant").val();
                var prenomRequerant = $("#prenom_requerant").val();
                var telephoneRequerant = $("#telephone_requerant").val();

                var communeDistrictRequerant = $("#sub_departement option:selected").text();
                var arrondRequerant = $("#sub_arrondissement option:selected").text();
                var quartierRequerant = $("#quartier option:selected").text();
                var domicileTypeVoieRequerant = $("#domicile_typevoie_requerant").val();
                var domicileNumeroRequerant = $("#domicile_numero_requerant").val();
                var domicileNomVoieRequerant = $("#domicile_nomvoie_requerant").val();


                // Vérification des champs requis
                if(typeActe == "" || numeroActe == "" || rubrique == "" || anienneValeur == "" || newValeur == ""){
                    flashAlert("Réponse","error","Veuillez renseigner tous les champs");
                    return;
                }
                if(nomRequerant == "" || prenomRequerant == "" || telephoneRequerant == ""){
                    flashAlert("Réponse","error","Veuillez renseigner les informations du réquerant");
                    return;
                }
                if(communeDistrictRequerant == "" || arrondRequerant == "" || quartierRequerant == ""){
                    flashAlert("Réponse","error","Veuillez renseigner l'adresse du réquerant");
                    return;
                }
                if(domicileTypeVoieRequerant == "" || domicileNumeroRequerant == "" || domicileNomVoieRequerant == ""){
                    flashAlert("Réponse","error","Veuillez renseigner les informations du domicile du réquerant");
                    return;
                }
                // Vérification que la rubrique est sélectionnée
                if(rubrique == null){
                    flashAlert("Réponse","error","Veuillez sélectionner une rubrique");
                    return;
                }
                // Vérification que le numéro d'acte est renseigné
                if(numeroActe == ""){
                    flashAlert("Réponse","error","Veuillez renseigner le numéro de l'acte");
                    return;
                }

                // Vérification que la nouvelle valeur est différente de l'ancienne valeur
                if(anienneValeur == newValeur){
                    flashAlert("Réponse","error","La nouvelle valeur doit être différente de l'ancienne valeur");
                    return;
                }

                 var librubrique = $("#rubrique option:selected").text();

                storeRectification(numeroActe,typeActe,anienneValeur,newValeur,rubrique,librubrique,nomRequerant,prenomRequerant,filiationRequerant,telephoneRequerant,communeDistrictRequerant,arrondRequerant,quartierRequerant,domicileTypeVoieRequerant,
                    domicileNumeroRequerant,domicileNomVoieRequerant);

            });

            //SUPPRESSION DE LA RECTIFICATION
            $(document).on("click",".btn-delete-rubrique",function(){
                var code = $(this).attr("code");

               //appel de la fonction de confirmation
                 deleteRectification(code);
            });


            //Impression de la rectification
             $(document).on("click", "#btn_imprimer_rectification", function() {
                var numeroActe = $("#numero_acte").val();

                // Vérifier si le numéro de l'acte est renseigné
                if (numeroActe === "") {
                    flashAlert("Réponse", "error", "Veuillez renseigner le numéro de l'acte pour l'impression.");
                    return;
                }
                // Rediriger vers la route d'impression avec le numéro de l'acte
                var route = "{{ route('rectification.etat', ':numeroActe') }}";
                route = route.replace(':numeroActe', numeroActe);
                window.location.href = route;

            });

            //permet de remplir le select des communes en fonction du département selectionné
            $("#code_departement").change(function(){
                var codeDepartement = $(this).val();
                var route = "{{ route('localite.commune.district',':id') }}";
                route = route.replace(':id', codeDepartement);
                $.get(route, function(response) {
                    $("#sub_departement").empty();
                    $("#sub_departement").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#sub_departement").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

            //permet de remplir le select des arrondissements en fonction de la commune selectionnée
            $("#sub_departement").change(function(){
                var codeCommune = $(this).val();
                var route = "{{ route('localite.arrondissement.communaute',':id') }}";
                route = route.replace(':id', codeCommune);
                $.get(route, function(response) {
                    $("#sub_arrondissement").empty();
                    $("#sub_arrondissement").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#sub_arrondissement").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

            //permet de remplir le select des quartiers en fonction de l'arrondissement selectionné
            $("#sub_arrondissement").change(function(){
                var codeArrondissement = $(this).val();
                var route = "{{ route('localite.quartier.village',':id') }}";
                route = route.replace(':id', codeArrondissement);
                $.get(route, function(response) {
                    $("#quartier").empty();
                    $("#quartier").append("<option selected disabled>Sélectionner</option>");
                    response.forEach(function(item){
                        $("#quartier").append("<option value='"+item.code_localite+"'>"+item.lib_localite+"</option>");
                    });
                });
            });

    });



    function rechercherActe(numeroacte,typeacte){
        var route = "{{ route('rectification.get.acte') }}";

        var data = {numero_acte:numeroacte,type_acte:typeacte};
        $.ajax({
            type: "POST",
            url: route,
            data: data,
            dataType: "json",
            success: function (response) {
                //  flashAlert("Réponse","success",response);
                if(response.code == "200"){

                    //affichage du formulaire
                    $("#form_rectification").show(300);
                    //rechercher les details de la rectification
                    getDetailsRectification(numeroacte,typeacte);
                }
                if(response.code == "180"){
                    flashAlert("Réponse","error",response.message);
                        //cache du formulaire
                    $("#form_rectification").hide(300);
                }
            }
        });
    }

    function storeRectification(numeroActe,typeActe,anienneValeur,newValeur,rubrique,librubrique,nomRequerant,prenomRequerant,filiationRequerant,telephoneRequerant,communeDistrictRequerant,arrondRequerant,quartierRequerant,domicileTypeVoieRequerant,
                    domicileNumeroRequerant,domicileNomVoieRequerant){
        var route = "{{ route('rectification.store') }}";
        var data = {numero_acte:numeroActe,type_acte:typeActe,old_value:anienneValeur,nouvelle_valeur:newValeur,rubrique:rubrique,
                    lib_rubrique:librubrique,nom_requerant:nomRequerant,prenom_requerant:prenomRequerant,filiation_requerant:filiationRequerant,telephone_requerant:telephoneRequerant,commune_district_requerant:communeDistrictRequerant,arrond_requerant:arrondRequerant,quartier_requerant:quartierRequerant,domicile_type_voie_requerant:domicileTypeVoieRequerant,
                    domicile_numero_requerant:domicileNumeroRequerant,domicile_nom_voie_requerant:domicileNomVoieRequerant};
        $.ajax({
            type: "POST",
            url: route,
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response.data)
                //
                if(response.code == "200"){
                   flashAlert("Réponse","success",response.message);
                    // $("#form_rectification").show(300);
                    //mise à jour du tableau des détails de la rectification et ajouter la valeur de la variable code=  pour la suppression
                    getDetailsRectification(numeroActe, typeActe);
                    $("#tbody_rectification").append("<tr><td>"+librubrique+"</td><td>"+anienneValeur+"</td><td>"+nouvelleValeur+"</td><td><button class='btn btn-danger btn-sm btn-delete-rubrique' code='"+response.data.details.code_detail_rectification+"' onclick='deleteRectification(\""+response.data.details.code_detail_rectification+"\")'>Supprimer</button></td></tr>");



                }
                if(response.code == "500" || response.code == "400"){
                    flashAlert("Réponse","error",response.message);
                    // $("#form_rectification").hide(300);
                }
            }
        });
    }

    //fonction de recuperation des details de la rectification d'un acte à partir du numero de l'acte saisie
    function getDetailsRectification(numeroActe,typeActe){
        var route = "{{ route('rectification.get.details') }}";
        var data = {numero_acte:numeroActe,type_acte:typeActe};
        $.ajax({
            type: "POST",
            url: route,
            data: data,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if(response.code == "200"){
                    //affichage des details de la rectification
                    $("#form_rectification").show(300);
                    //remplissage du tableau des rectifications
                    $("#tbody_rectification").empty();
                    response.data.forEach(function(item){
                        //ajout de la ligne dans le tableau
                         $("#tbody_rectification").append("<tr><td>"+item.lib_rubrique+"</td><td>"+item.ancienne_valeur+"</td><td>"+item.nouvelle_valeur+"</td><td><button class='btn btn-danger btn-sm btn-delete-rubrique' code='"+item.code_detail_rectification+"' onclick='deleteRectification(\""+item.code_detail_rectification+"\")'>Supprimer</button></td></tr>");
                    });
                }
                if(response.code == "500"){
                    flashAlert("Réponse","error",response.message);
                }
            }
        });
    }



    //Fonction de capitalize la saisie de la nouvelle_valeur, si lib_technique == 'nom' ou 'lieu_naissance' ou 'sexe' et le champ sera de type text,si le lib_technique == 'prenom' la première lettre de chaque mot sera en majuscule et le champ sera de type text,en fin si le lib_technique == 'date_naissance' le champ sera de type date et le format sera 'YYYY-MM-DD'
    $("#nouvelle_valeur").on("keyup",function(){
        var rubrique = $("#rubrique").val();
        var libTechnique = rubrique.split("-")[1];
        if(libTechnique == "nom" || libTechnique == "lieu_naissance"){
             $(this).attr("type","text");
            $(this).val($(this).val().toUpperCase());
        }else if(libTechnique == "prenom" || libTechnique == "sexe"){
             $(this).attr("type","text");
            $(this).val($(this).val().replace(/\b\w/g, function(l) { return l.toUpperCase() }));
        }else if(libTechnique == "date_naissance"){
            $(this).attr("type","date");
        }
    });


    //Fonction de flashAlert confirmation
    function flashAlertConfirmationWithCallback(titre,icon,message,callback){
        Swal.fire({
            title: titre,
            text: message,
            icon: icon,
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non'
        }).then((result) => {
            if (result.isConfirmed) {
                callback();
            }
        });
    }


    //Fonction de suppression de la rectification
    function deleteRectification(code){
          var route = "{{ route('rectification.destroy',':id') }}";
            route = route.replace(':id',code);
            //requete ajax pour supprimer la rectification

            $.ajax({
                type: "DELETE",
                url: route,
                dataType: "json",
                success: function (response) {
                    if(response.code == "200"){
                        flashAlert("Réponse","success",response.message);
                        //suppression de la ligne du tableau
                        $("button[code='"+code+"']").closest("tr").remove();
                    }else{
                        flashAlert("Réponse","error",response.message);
                    }
                }
            });
    }






</script>
@endsection
