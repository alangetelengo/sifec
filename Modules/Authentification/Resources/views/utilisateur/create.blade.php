@extends('layout.app')
@section('titre')
Créer une personne
@endsection
@section('styles')

@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer un utilisateur</h4>
                     {{-- <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target=".personne">Rechercher une personne</button> --}}

                     <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalPersonne">
                        Rechercher une personne
                    </button>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route("utilisateur.store") }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="hidden" id="code_personne" name="code_personne">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('nom') is-invalid @enderror" id="nom_personne" value="{{ old("nom") }}" placeholder="Nom" name="nom">
                                    @error("nom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Prénom(s)</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" value="{{ old("prenom") }}" id="prenom_personne" placeholder="Prénom" name="prenom">
                                    @error("prenom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select name="sexe" id="sexe_personne" class="form-control form-control wide @error("sexe") is-invalid @enderror">
                                        <option value="">Choisissez</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Feminin</option>
                                    </select>
                                    @error("sexe")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance_personne" value="{{ old("date_naissance") }}" name="date_naissance">
                                    @error("date_naissance")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                    <select id="code_localite" name="code_localite" class="form-control form-control wide">
                                        <option value="">Choisissez</option>
                                        @foreach ($localites as $dept)
                                        <option value="{{ $dept->code_localite }}">{{ $dept->lib_localite }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="mb-2 col-md-4 communes d-none">
                                    <label class="form-label">Commune <span class="text-danger">*</span></label>
                                    <select name="code_commune" id="communes" class="form-control form-control codecommune wide">

                                    </select>
                                </div>
                                <div class="mb-2 col-md-4 districts d-none">
                                    <label class="form-label">District <span class="text-danger">*</span></label>
                                    <select name="code_district" id="districts" class="form-control form-control codedistrict wide">

                                    </select>
                                </div> --}}
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                    <select name="code_nationalite" id="code_nationalite_personne" class="form-control form-control wide @error("code_nationalite") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($nationalites as $nationalite)
                                            <option value="{{ $nationalite->code_nationalite }}">{{ $nationalite->lib_nationalite }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_nationalite")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Domicile *</label>
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse_personne" value="{{ old("adresse") }}" name="adresse">
                                    @error("adresse")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Téléphone </label>
                                    <input type="text" class="form-control @error('pseudo') is-invalid @enderror" value="{{ old("pseudo") }}" name="pseudo">
                                    @error("pseudo")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Type de pièce d'identité <span class="text-danger">*</span></label>
                                    <select name="code_type_document" class="form-control form-control wide @error("code_type_document") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($typeDocuments as $item)
                                            <option value="{{ $item->code_type_document }}">{{ $item->lib_type_document }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_type_document")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Numéro de la pièce d'identité <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('numero_document') is-invalid @enderror" value="{{ old("numero_document") }}" name="numero_document">
                                    @error("numero_document")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Fonction <span class="text-danger">*</span></label>
                                    <select name="code_fonction" id="code_fonction_personne" class="form-control form-control wide @error("code_fonction") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($fonctions as $fonction)
                                            <option value="{{ $fonction->code_fonction }}">{{ $fonction->lib_fonction }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_fonction")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Type centre d'état civil <span class="text-danger">*</span></label>
                                    <select name="code_type_institution" id="codetypeinstitution" class="form-control form-control wide @error("code_type_institution") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($typeInstitutions as $type_institution)
                                            <option value="{{ $type_institution->code_type_institution }}">{{ $type_institution->lib_type_institution }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_fonction")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4 cecrattache d-none">
                                    <label class="form-label">Centre d'état civil rattaché <span class="text-danger">*</span></label>
                                    <select  name="code_institution" class="form-control wide" id="codeinstitution">

                                    </select>
                                    @error("code_institution")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" required class="form-control @error('email') is-invalid @enderror" value="{{ old("email") }}" placeholder="Email" name="email">
                                    @error("email")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                            <a href="{{ route("utilisateur.index") }}"><button type="button" class="btn btn-sm btn-danger">Retour</button></a>
                        </form>

                    </div>
                </div>
            </div>
        </div>




            <!-- Large modal -->
            <div class="modal fade" id="modalPersonne" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Information de la personne</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">


                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('nom') is-invalid @enderror" value="{{ old("nom") }}" placeholder="Nom" id="nom">
                                    @error("nom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Prénom(s)</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" value="{{ old("prenom") }}" placeholder="Prénom" id="prenom">
                                    @error("prenom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select id="sexe" class="form-control form-control wide @error("sexe") is-invalid @enderror">
                                        <option value="">Choisissez</option>
                                        <option value="M">Masculin</option>
                                        <option value="F">Feminin</option>
                                    </select>
                                    @error("sexe")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" min="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old("date_naissance") }}" id="date_naissance">
                                    @error("date_naissance")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Téléphone </label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" value="{{ old("telephone") }}" id="telephone">
                                    @error("telephone")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>


                                <div class="mb-2 col-md-4">
                                    <br><br>
                                    <button type="submit" class="btn btn-sm btn-primary tosearch">Valider</button>
                                </div>

                            </div>

                        <div class="row">
                            <div id="resultatPersonne"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
                </div>
            </div>
    </div>
@endsection
@section('scripts')
<script>

$(function (){



    $(".tosearch").on("click", function (event) {
        event.preventDefault();

        var nom = $("#nom");
        var prenom = $("#prenom");
        var date_naissance = $("#date_naissance");
        var sexe = $("#sexe");
        var telephone = $("#telephone");

        var champs = [nom,date_naissance,sexe,telephone];
        var champsVide = [];

        for (let i = 0; i < champs.length; i++) {

            if (champs[i].val() == '' || champs[i].val() == null) {

                champsVide.push(champs[i]);
            }

        }
        //vérification des champs vides
        for(var i = 0; i < champsVide.length; i++)
        {
            champsVide[i].addClass("is-invalid");
        }
        //si un champ obligatoire est null ou vide alors il ne passe pas à l'étape suivante
        if(champsVide.length > 0){

            return false;
        }

        var data = {
            nom: nom.val(),
            prenom: prenom.val(),
            date_naissance: date_naissance.val(),
            telephone: telephone.val(),
        };

        var int = 0;

        var table = '<div class="table-responsive">'+
                        '<table class="table table-responsive-md">'+
                            '<thead>'+
                                '<tr>'+
                                    '<th>#</th>'+
                                    '<th><strong>NOM et prénom</strong></th>'+
                                    '<th><strong>Date de naissance</strong></th>'+
                                    '<th><strong>Sexe</strong></th>'+
                                    '<th><strong>Téléphone</strong></th>'+
                                    '<th><strong>Choisir</strong></th>'+
                            ' </tr>'+
                            '</thead>'+
                            '<tbody>';
            //traitement ajax
            $.ajax({
                url: "{{ route('utilisateur.search') }}",
                data: data,
                success: function(response){
                    //$("#resultatPersonne").html(response.personnes);

                    if(response.personnes.length > 0){

                        for( var i=0; i < response.personnes.length ; i++){
                            int ++;
                            //alert(response.personnes[i].lib_fonctionnalite);
                            table +='<tr>'+
                                        '<td><strong>'+int+'</strong></td>'+
                                        '<td>'+response.personnes[i].nom+" "+response.personnes[i].prenom+'</td>'+
                                        '<td>'+response.personnes[i].date_naissance+'</td>'+
                                        '<td>'+response.personnes[i].sexe+'</td>'+
                                        '<td>'+response.personnes[i].telephone+'</td>'+
                                        '<td><div class="d-flex"><label class="radio-inline mr-1"><input type="radio" name="choix" value="'+response.personnes[i].code_personne+'" name="choix"></div>'+'</td>';
                        }
                    }
                    table += "</tr></tbody></table></div>";
                    $("#resultatPersonne").html(table);

                    $("input[type='radio']").on("click", function (){
                        var choix = $("input[name='choix']:checked").val();
                        for(var i=0; i < response.personnes.length; i++){
                            //var dateNais = new Date(response.personnes[i].date_naissance).toISOString().split('T')[0];

                            $("#code_personne").val(response.personnes[i].code_personne);
                            $("#nom_personne").val(response.personnes[i].nom);
                            $("#prenom_personne").val(response.personnes[i].prenom);
                            $("#date_naissance_personne").val(response.personnes[i].date_naissance);
                            $("#sexe_personne").val(response.personnes[i].sexe);
                            $("#adresse_personne").val(response.personnes[i].adresse);
                            $("#telephone_personne").val(response.personnes[i].telephone);
                            $("#code_nationalite_personne").val(response.personnes[i].code_nationalite);
                            $("#code_profession_personne").val(response.personnes[i].code_profession);
                            $("#lieu_naissance_personne").val(response.personnes[i].lieu_naissance);
                            $("#niveau_instruction_personne").val(response.personnes[i].niveau_instruction);
                            $("#pseudo_personne").val(response.personnes[i].telephone);
                        }
                        $("#modalPersonne").modal('hide');
                        console.log(response.personnes);

                    });

                }
            });

    });


    //choisir le lieu de naissance pour user
    $("#codedepartement").change(function(e){
        e.preventDefault();
        var codepartement = $(this).val();
        if(codepartement != null || codepartement !=""){

            getCommunes(codepartement);
            getDistricts(codepartement);
            $("div.communes").removeClass("d-none");
            $("div.districts").removeClass("d-none");
            $("select.codedistrict").removeAttr("disabled");
            $("select.codecommune").removeAttr("disabled");


        }else{
            $("div.communes").addClass("d-none");
            $("div.districts").addClass("d-none");
        }

    });
    //choisir le cec pour user
    $("#codetypeinstitution").change(function(e){
        e.preventDefault();
        var codeCec = $(this).val();
        if(codeCec != null || codeCec !=""){
            getInstitution(codeCec);
            $("div.cecrattache").removeClass("d-none");
        }else{
            $("div.cecrattache").addClass("d-none");
        }

    });

    $("#communes").change(function(e) {
        e.preventDefault();
        var codecommune = $(this).val();
        if(codecommune != null || codecommune != ""){
            $("div.communes").removeClass("d-none");
            $("select.codecommune").removeAttr("disabled");

            $("div.districts").addClass("d-none");
            $("select.codedistrict").attr("disabled","disabled");
        }
    });
    $("#districts").change(function(e) {
        e.preventDefault();
        var codedistrict = $(this).val();
        if(codedistrict != null || codedistrict != ""){
            $("div.districts").removeClass("d-none");
            $("select.codedistrict").removeAttr("disabled");

            $("div.communes").addClass("d-none");
            $("select.codecommune").attr("disabled","disabled");
        }
    });
});


function getInstitution(id){
    var option = "<option value=''>Selectionnez</option>";

    $.get("{{ route('utilisateur.getinstitution') }}", { id:id }, function(data){

        if(data.length > 0){

            for (var i = 0; i < data.length; i++) {
                option += "<option value="+data[i].code_institution+">"+data[i].lib_institution+"</option>";
            }
        }
        $("#codeinstitution").html(option);

    });
}

function getDistricts(id){
    var out = "<option value=''>Selectionnez</option>";

    $.get("{{ route('utilisateur.getDistricts') }}", { id:id }, function(reponse){

        if(reponse.length > 0){

            for(var i= 0; i < reponse.length; i++){

                out += "<option value="+reponse[i].code_district+">"+reponse[i].lib_district+"</option>";
            }
        }
        $("#districts").html(out);
    });
}
function getCommunes(id){
    var out = "<option value=''>Selectionnez</option>";

    $.get("{{ route('utilisateur.getCommunes') }}", { id:id }, function(result){

        if(result.length > 0){

            for(var i= 0; i < result.length; i++){

                out += "<option value="+result[i].code_commune+">"+result[i].lib_commune+"</option>";
            }
        }
        $("#communes").html(out);

            // if((jQuery.isEmptyObject(result))){
            //     console.log("objet vide")
            //     // $("#communauteurbainesection").hide();
            //     return;
            // }else {
            //     // $("#communauteurbainesection").show();
            //     console.log("objet existe")
            //     return;
            // }
    });
}



</script>
@endsection
