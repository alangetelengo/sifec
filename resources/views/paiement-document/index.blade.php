
@extends('layout.app')
@section('titre')
Actes de naissance
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des actes de naissance</h4>
                <div class="row">
                    <div id="dupcreer">

                        {{-- <button class="btn btn-sm btn-primary mb-2"> <i class="fa fa-refresh"></i> Etat des recettes</button> --}}

                        <a href="{{ route('paiement_document.etatRecouvement') }}" target="_blank" class="btn btn-primary"> Etat des recettes</a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Date naissance</th>
                                        <th>Sexe</th>
										{{-- <th>Statut: Document</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($actes as $acte)
                                        <tr width="100%" style="color: black">
                                            <td>{{ $acte->niupp}}</td>
                                            <td>{{ $acte->declaration->enfant->nom }}</td>
                                            <td>{{ $acte->declaration->enfant->prenom }}</td>
                                            <td>{{ date("d-m-Y", strtotime($acte->declaration->enfant->date_naissance)) }}</td>
                                            <td>{{ $acte->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }} </td>
                                            {{-- <td style="width: 15%">
                                                @if($acte->statut == 0 && $acte->retirer == 1)
                                                <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Acte rétiré</span>
                                                @endif

                                                @if($acte->statut == 0 && $acte->retirer == 0)
                                                <span class="badge badge-danger" style="font-size: 13px;font-weight:600;">Acte produit non rétiré</span>
                                                @endif

                                                @if($acte->statut == 1 && $acte->retirer == 0)
                                                <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">Acte annulé</span>
                                                @endif
                                            </td> --}}
                                            <td style="width: 15%">
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ $acte->niupp }}" class="btn btn-warning show-acte-retrait-modal">Rétirer</a>

                                                    {{-- <a href="{{ route('declarationNaissance.etat',$acte->declaration->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>
                                                    <a href="{{ route('acteNaissance.display',$acte->declaration->code_declaration_naissance) }}" target="_blank" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('acteNaissance.copie',$acte->declaration->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir copie"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ route('acteNaissance.displayExtrait',$acte->declaration->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir extrait"><i class="fa fa-eye"></i></a>
                                                    <a href="{{ $acte->declaration->code_declaration_naissance }}" class="btn btn-warning show-annuler-acte shadow btn-xs sharp me-1" title="Annuler" ><i class="fa fa-times"></i></a> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Date naissance</th>
                                        <th>Sexe</th>
										{{-- <th>Statut: Document</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


{{-- DEBUT MODAL RECHERCHE ACTE DE NAISSANCE --}}
<div class="modal fade" id="modal-search-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rechercher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control"lass="form-control"  placeholder="" id="nom_recherche" required>
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Prénom(s)</label>
                        <input type="text" class="form-control"  placeholder="" id="prenom_recherche">
                    </div>
                </div>
                <div class="row">

                    <div class="mb-2 col-md-6">
                        <label class="form-label">Lieu de naissance </label>
                        <input type="tel" class="form-control"  id="lieu_recherche">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info text-white" id="rechercher">Rechercher</button>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Résultat de la recherche</h4>
                            </div>
                            <div class="card-body">
                                <div id="resultatrech"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL RECHERCHE ACTE DE NAISSACE --}}


{{-- DEBUT MODAL RETRAIT ACTE DE NAISSANCE --}}
<div class="modal fade" id="modal-demande-document" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Demande du document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="form-retrait">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Numéro de l'acte<span class="text-danger">*</span></label>
                        <input type="text" id="codeniupp" class="form-control" readonly>
                        <input type="hidden" id="codeacte">
                    </div>

                    <div class="mb-2 col-md-4">
                        <label class="form-label">Type de document <span class="text-danger">*</span></label>
                        <select id="type_document" name="type_document" class="form-control">
                                <option selected disabled>Selectionner</option>
                            @foreach ($typeDocumentDemandes as $item)
                                <option value="{{ $item->code_type_document_demande }}">{{ $item->lib_type_document_demande }}</option>
                            @endforeach
                        </select>
                        <span id="error_typeDoc" style="color: red"></span>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Mode de paiement <span class="text-danger">*</span></label>
                        <select id="mode_paiement" name="mode_paiement" class="form-control">
                            <option value="OTHER">ESPECE</option>
                            {{-- <option value="AM">AIRTEL MONEY</option> --}}
                            <option value="MOMO">MOMO</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4 d-none">
                        <label class="form-label">Montant à payer ($)<span class="text-danger"></span></label>
                        <input type="text" class="form-control" placeholder="" id="montant" value="10" readonly>
                    </div>
                    <div class="mb-2 col-md-4 lephone d-none">
                        <label class="form-label">Téléphone<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="" id="telephone">
                        <span id="error_telephone" style="color: red"></span>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Nom et prénom du demandeur<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" placeholder="" id="nom_interesse">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Téléphone du demandeur<span class="text-danger"></span></label>
                        <input type="text" class="form-control" placeholder="" id="telephone_interesse">
                    </div>
                </div>
                {{-- debut attente de paiement --}}
                <div class="row" id="attente-confirmation">
                    <div class="container" style="width: 90%; text-align: center; box-shadow: -2px -1px 28px 3px silver; padding:30px">
                        <img src="{{ asset('success_style/logo-sifec.gif')}}" style="width:27%; margin-bottom:20px">
                        <h2 class="" id="message">
                        <img src="{{ asset('success_style/loading.gif')}}" id="loader" style="width:50px"/><br>
                        {{-- <img src="{{ asset('success_style/img.png')}}" id="ok" style="width:50px"/> --}}
                        Votre demande est en attente de paiement</h2>

                        <input type="hidden"  id="idtransaction">
                        <div class="form-group">
                            <div class="col-md-12">
                                {{-- <h2 class="text-center" id="notification" style="font-size:15px;">Le paiement est en attente de validation, --}}
                                <h2 class="text-center" id="notification" style="font-size:15px;">Veuillez valider le paiement</h2>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- fin attente de paiement --}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-retrait">Payers</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN MODAL RETRAIT ACTE DE NAISSACE --}}
</div>
</div>
</div>
@endsection
@section("scripts")
<!-- Datatable -->
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
<script>

    var timer = "";
    $("#attente-confirmation").hide();
    $(function()
    {
        // Traitement du mode de paiement
        $("#mode_paiement").on("change", function(){
            var mode = $(this).val();
            if(mode != "OTHER"){
                $(".lephone").removeClass('d-none');
            }else{
                $(".lephone").addClass('d-none');
                // alert("pas espece");
            }
        });
        // Traitement de la demande du cocument
        $("a.show-acte-retrait-modal").on("click", function(){
            var codeniupp = $(this).attr("href");

            $("#codeniupp").val(codeniupp);
            $("#codeacte").val(codeniupp);
            $("#modal-demande-document").modal("show");
            return false;
        });
        $("#btn-retrait").on("click", function(){

            var montant = $("#montant").val();
            var telephone = $("#telephone").val();
            var mode_paiement = $("#mode_paiement").val();
            var typedocument = $("#type_document").val();



            if(typedocument == null || typedocument == ""){
                $("#error_typeDoc").html("Veuillez selectionner le type de document");
                return false;
            }

            if(mode_paiement == "AM" || mode_paiement == "MOMO"){
                if( telephone == null || telephone == ""){
                    $("#error_telephone").html("Veuillez entrer le numéro du téléphone pour le paiement");
                    return false;
                }
            }

            $("#attente-confirmation").show();
            $("#form-retrait").hide();

            // valideRetraitActe(donnees);
            transactionManager(telephone,montant,mode_paiement);
            return false;
        });

        // $("#refresh").on('click', function(){
        // });
    });

    function transactionManager(phone,amount,mode_paiement){
        var route = "{{ route('paiement_document.transmanager') }}";
        var data = {
                    montant:amount,
                    telephone:phone,
                    mode_paiement:mode_paiement
                };
        $.post(route,data, function(response){
            console.log(response.transid);
            $("#idtransaction").val(response.transid);
            timer = setInterval(myTimer, 20000);

        });
    }


    function myTimer() {

        //appel de la vérification
        // alert("Vérification statut_paiement");
        //Appel du service demandeActe

        var url = 'http://192.168.100.57/sifec/public/api/v1/statutPaiementMomo';
        var niupp = $("#codeacte").val();
        var transid = $("#idtransaction").val();
        var typedocument = $("#type_document").val();


        var data = {trans_id:transid};
        var codeDemande = $('#codeDemande').val();
        var statutDemande = "";
        var url2 = "";

        if(typedocument == "TDD_0001"){
            url2 = "{{ route('acteNaissance.copie', ':id') }}";
        }
        if(typedocument == "TDD_0002"){
            url2 = "{{ route('acteNaissance.displayExtrait', ':id') }}";
        }

        //Traitement de la réponse
        // var httpRequest =  $.get(connexion, transid);
        var httpRequest =  $.post(url,data);

        //Retour en cas de succès
        httpRequest.done(function(response){

            console.log(response);

            if(response == "successful"){
                $("#notification").text("Le paiement a été effectué avec succès");

                var nominteresse = $("#nom_interesse").val();
                var typedocument = $("#type_document").val();
                var montant = $("#montant").val();
                var telephone = $("#telephone").val();
                var telephoneInteresse = $("#telephone_interesse").val();
                var codeacte = $("#codeacte").val();
                var url = "{{ route('paiement_document.payement') }}";
                var data = {
                            niupp:codeacte,
                            nominteresse:nominteresse,
                            typedocument:typedocument,
                            montant:montant,
                            telephone_interesse:telephoneInteresse,
                            telephone:telephone
                        };
                $.post(url,data, function(response){

                    if(response.code = "200"){

                        url2 = url2.replace(":id", response.cdn);

                        flashAlert("Réponse", "success", response.message);
                        $("#modal-demande-document").modal("hide");
                        // alert(url2);
                        clearInterval(timer);
                        setTimeout(() => {
                            window.open(url2);
                        }, 3000);

                    }else{
                        // notification("error",response.message);
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
            if(response == "failed"){
                $("#notification").text("Paiement échouée, veuillez réessayer.");
                alert("Paiement a échoué");
            }
        });

    }
</script>
@endsection
