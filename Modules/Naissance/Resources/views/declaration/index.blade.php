

@extends('layout.app')
@section('titre')
Déclaration
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des déclarations de naissance
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des déclarations de naissance</h4>
                <a href="{{ route("declarationNaissance.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer déclaration  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Type: Document</th>
                                        <th>Statut: Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($declarations as $dn)
                                    <tr width="100%">
                                        <td>{{ $dn->code_declaration_naissance }}</td>
                                        <td>{{ $dn->declarant->nom.' '.$dn->Declarant->prenom }}</td>
                                        <td>{{ $dn->enfant->nom }}</td>
                                        <td>{{ $dn->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($dn->enfant->date_naissance)) }}</td>
                                        <td>{{ $dn->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        @if($dn->type_declaration != "FICHE DE MATERNITE" )
                                            <td>
                                                {{ $dn->type_declaration }}
                                            </td>
                                        @else
                                            <td>
                                                <a href="{{ $dn->code_declaration_naissance }}" telephone="{{ $dn->mere->telephone_parent }}" class="badge badge badge-danger show-send-sms-to-parent" style="font-size: 13px;font-weight:600;">{{ $dn->type_declaration }}</a>
                                            </td>
                                        @endif
                                        @if($dn->mouvements->last()->statut == "Renvoyée")
                                        <td><a href="{{ $dn->code_declaration_naissance }}" cmouvtnais="{{ $dn->mouvements->last()->code_mouvement_naissance }}" obs="{{ $dn->mouvements->last()->observation }}" class="show-detail-renvoie" title="{{ $dn->mouvements->last()->motif_renvoi }}">
                                            <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">document renvoyé </span></a>
                                        </td>
                                        @endif

                                         @if($dn->mouvements->last()->statut == "En cours")
                                        <td><span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">
                                            {{ $dn->mouvements->last()->statut }} de traitement</span>
                                        </td>
                                        @endif
                                        @if($dn->mouvements->last()->statut == "Envoyée")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                                            Transféré à l'institution supérieure </span>
                                        </td>
                                        @endif
                                        @if($dn->mouvements->last()->statut == "Envoye au tribunal")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">
                                            Transféré au tribunal </span>
                                        </td>
                                        @endif
                                        <td style="width: 15%">
                                            <div class="btn-group btn-group-xs">
                                                {{-- cacher ce bouton uniquement pour le ayant la permission --}}
                                                    {{-- @if($dn->mouvements->last()->statut == "Envoyée")
                                                        <a href="{{ $dn->code_declaration_naissance }}" class="btn btn-danger show-to-send-back shadow btn-xs sharp me-1" title="Renvoyer" ><i class="fas fa-plane"></i></a>
                                                    @endif --}}
                                                {{-- cacher ce bouton uniquement pour le ayant la permission --}}
                                                @if($dn->mouvements->last()->statut == "En cours" || $dn->mouvements->last()->statut == "Renvoyée")
                                                    @if($dn->type_declaration != "FICHE DE MATERNITE")
                                                    <a href="{{ $dn->code_declaration_naissance }}" class="btn btn-warning show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                                    @endif
                                                @endif

                                                @if($dn->mouvements->last()->statut == "En cours" || $dn->mouvements->last()->statut == "Renvoyée")
                                                    <a href="{{ route('declarationNaissance.edit',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
                                                    <a href="{{ route('declarationNaissance.joindre.document',$dn->code_declaration_naissance) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Joindre document"><i class="fa fa-file"></i></a>
                                                    {{-- <a href="{{ $dn->code_declaration_naissance }}" class="btn btn-info show-piece-parent-modal shadow btn-xs sharp me-1" title="Joindre pièce"><i class="fa fa-file"></i></a> --}}
                                                @endif

                                                <a href="{{ route('declarationNaissance.etat',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-print"></i></a>

                                                @if($dn->acte != null)
                                                    <a href="{{ route('acteNaissance.display',$dn->code_declaration_naissance) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                @endif
                                                <form  action="{{ route('declarationNaissance.destroy',$dn->code_declaration_naissance) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    {{-- <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button> --}}
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                       {{--  <th>Père</th>
                                        <th>Mère</th> --}}
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Type: Document</th>
                                        <th>Statut: Document</th>
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
{{-- DEBUT ENVOIS DECLARATION --}}
<div class="modal fade" id="modal-declaration-send" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        {{-- <input type="hidden" id="code_declaration_naissance"> --}}
                        <label class="form-label">Transmission de la déclaration N°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclaration">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS DECLARATION --}}

{{-- DEBUT DETAILS RENVOIE DECLARATION --}}
<div class="modal fade" id="modal-declaration-send-back" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> Détail du renvoie</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Document n°</label>
                        <input type="text" readonly class="form-control"  placeholder="" id="codedeclarationback">
                        <input type="hidden" class="form-control" id="codemouvementnaissance">
                    </div>

                    <div class="mb-2 col-md-12">
                        <label class="form-label">Motif du renvoi <span class="text-danger">*</span></label>
                        <select id="motif_renvoi" name="motif_renvoi" class="form-control" readonly>
                            {{-- <option value="" disabled selected>Selectionner</option>
                            <option value="erreur materielle">Erreur matérielle</option>
                            <option value="Ajouter nom/prenom">Ajouter nom/prénom</option>
                            <option value="rectifier nom/prenom">Rectifier nom/prénom</option> --}}
                        </select>
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Observation</label>
                        <textarea id="observation" cols="105" rows="5" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN DETAILS RENVOIE DECLARATION --}}



{{-- DEBUT ENVOIS NOTIFICATION AU PARENT --}}
<div class="modal fade" id="modal-notification-send-parent" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title"> </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label">Envoyer la notification au numéro</label>
                        <input type="hidden" id="codedn">
                        <input type="text" readonly class="form-control"  placeholder="" id="tepehoneparent">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send-notification">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS NOTIFICATION AU PARENT --}}


@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                // $("#code_declaration_naissance").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-declaration-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cdn = $("#codedeclaration").val();
                var route = "{{ route('declarationNaissance.mouvement') }}";
                var data = {
                    code_declaration_naissance:cdn
                };

                $(this).attr("disabled",true);
                $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-declaration-send").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });


            $("a.show-detail-renvoie").on("click", function(){
                var motif = $(this).attr("title");
                var cdd = $(this).attr("href");
                var cmvtn = $(this).attr("cmouvtnais");
                var obs = $(this).attr("obs");

                $("#codedeclarationback").val(cdd);
                $("#observation").val(obs);
                $("#motif_renvoi").html("<option>"+motif+"</option>");

                $("#modal-declaration-send-back").modal("show");
                return false;
            });

            // //Debut Add piece joint
            $("a.show-piece-parent-modal").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                // $("#code_declaration_naissance").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-add-piece-parent").modal("show");
                return false;
            });
            // //Fin Add piece joint


            //envoie de notification au parent de la mere de l'enfant
            $("a.show-send-sms-to-parent").on("click", function(){
                var codedn = $(this).attr('href');
                var tepehoneParent = $(this).attr('telephone');

                $("#tepehoneparent").val(tepehoneParent);
                $("#codedn").val(codedn);
                $("#modal-notification-send-parent").modal("show");
                return false;
            });

            $("#btn-send-notification").on('click', function(){
                var phone = $("#tepehoneparent").val();
                var codedn = $("#codedn").val();
                var url = "{{ route('fiche_maternite.send.notification', ':id') }}";
                url = url.replace(":id", codedn);

                    $.get(url, function(response){

                    if(response.code == "200"){

                        flashAlert("Réponse","success",response.message);
                        $("#modal-notification-send-parent").modal('hide');
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 2000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });
                return false;
            });

        });
    </script>

@endsection
