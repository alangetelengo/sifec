@extends('layout.app')
@section('titre')
Modification d'un utilisateur
@endsection
@section('styles')

@endsection

@section('corps')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Modifier un utilisateur</h4>
                     {{-- <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target=".personne">Rechercher une personne</button> --}}

                     <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalPersonne">
                        Rechercher une personne
                    </button>
                </div>
                <div class="card-body">
                    <div class="basic-form">
                        <form method="POST" action="{{ route("utilisateur.update", $user->code_user) }}" enctype="multipart/form-data">
                            @csrf
                            @method("PUT")
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" class="form-control @error('nom') is-invalid @enderror" id="nom_personne" value="{{ $user->personne->nom }}" name="nom">
                                    @error("nom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Prénom(s)</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" value="{{ $user->personne->prenom }}" id="prenom_personne"  name="prenom">
                                    @error("prenom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select name="sexe" id="sexe_personne" class="form-control form-control wide @error("sexe") is-invalid @enderror">
                                        <option value="M" {{ $user->personne->sexe == "M" ? "selected" : ""  }}>Masculin</option>
                                        <option value="F" {{ $user->personne->sexe == "F" ? "selected" : ""  }}>Feminin</option>
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
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance_personne" value="{{ $user->personne->date_naissance }}" name="date_naissance">
                                    @error("date_naissance")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                                    <input type="text" id="lieu_naissance_personne" class="form-control @error('lieu_naissance') is-invalid @enderror"  value="{{ $user->personne->lieu_naissance }}" name="lieu_naissance">
                                    @error("lieu_naissance")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div id="code_localite">

                                    </div>


                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Niveau d'instruction </label>
                                    <select name="niveau_instruction" id="niveau_instruction_personne" class="form-control form-control wide">
                                        @foreach ($niveauInstructions as $item)
                                        <option value="{{ $item }}" {{ $user->personne->niveau_instruction == $item ? "selected" : "" }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error("niveau_instruction")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                                    <select name="code_nationalite" id="code_nationalite_personne" class="form-control form-control wide @error("code_nationalite") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($nationalites as $nationalite)
                                            <option value="{{ $nationalite->code_nationalite }}" {{ $user->personne->nationalite->code_nationalite == $nationalite->code_nationalite ? "selected" : "" }}>{{ $nationalite->lib_nationalite }}</option>
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
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse_personne" value="{{ $user->personne->adresse }}" name="adresse">
                                    @error("adresse")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Téléphone </label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror" id="telephone_personne" value="{{ $user->personne->telephone }}" name="telephone">
                                    @error("telephone")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Type de pièce d'identité <span class="text-danger">*</span></label>
                                    <select name="code_type_document" class="form-control form-control wide @error("code_type_document") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($typeDocuments as $item)
                                            {{-- <option value="{{ $item->code_type_document }}" {{ $user->personne->document->code_type_document == $item->code_type_document ? "selected" : "" }}>{{ $item->lib_type_document }}</option> --}}
                                            <option value="{{ $item->code_type_document }}" >{{ $item->lib_type_document }}</option>
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
                                    <input type="text" class="form-control @error('numero_document') is-invalid @enderror" value="" name="numero_document">
                                    @error("numero_document")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Fonction <span class="text-danger">*</span></label>
                                    <select name="code_fonction" id="code_fonction_personne" class="form-control form-control wide @error("code_fonction") is-invalid @enderror">
                                        @foreach ($fonctions as $fonction)
                                            <option value="{{ $fonction->code_fonction }}" {{ $user->affectationActive()->fonction->code_fonction == $fonction->code_fonction ? "selected" : "" }}>{{ $fonction->lib_fonction }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_fonction")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Centre d'etat civil <span class="text-danger">*</span></label>
                                    <select name="code_institution" class="form-control form-control wide @error("code_institution") is-invalid @enderror">
                                            <option value="">Choisissez</option>
                                        @foreach ($institutions as $institution)
                                            <option value="{{ $institution->code_institution }}" {{ $user->affectationActive()->institution->code_institution == $institution->code_institution ? "selected" : "" }}>{{ $institution->lib_institution }}</option>
                                        @endforeach
                                    </select>
                                    @error("code_institution")
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>



                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" required class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}"  name="email">
                                    @error("email")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select name="active" class="form-control form-control wide @error("active") is-invalid @enderror">
                                        <option value="1" {{ $user->status == "1" ? "selected" : "" }} >Activer</option>
                                        <option value="0" {{ $user->status == "0" ? "selected" : "" }}>Désactiver</option>
                                    </select>
                                    @error("active")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-sm btn-primary">Modifier</button>
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
                                    <input type="text" class="form-control" class="form-control @error('nom') is-invalid @enderror"  placeholder="Nom" id="nom">
                                    @error("nom")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Prénom(s)</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror"  placeholder="Prénom" id="prenom">
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
                                    <input type="date" max="<?php echo date("Y-m-d"); ?>" min="<?php $jour=date("Y-m-d"); echo date('Y-m-d', strtotime($jour. ' - 18 years'));?>" class="form-control @error('date_naissance') is-invalid @enderror" id="date_naissance">
                                    @error("date_naissance")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-2 col-md-4">
                                    <label class="form-label">Téléphone </label>
                                    <input type="text" class="form-control @error('telephone') is-invalid @enderror"  id="telephone">
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


});




</script>
@endsection
