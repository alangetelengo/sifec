@extends('layout.app')
@section('titre')
Constatation de décès
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
                <h4>Liste des Certificats de constatation de décès</h4>
                <a href="{{ route('centreHygiene.create') }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer déclaration  <i class="fa fa-plus-circle"></i></button></a>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($declarationdeces as $dd)
                                    <tr width="100%">
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $dd->defunt->nom.' '.$dd->defunt->prenom }}</td>
                                        <td>{{ $dd->defunt->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>{{ date("d-m-Y", strtotime($dd->date_heure_deces)) }}</td>
                                        {{-- <td>{{$dd->lieu_deces}}</td> --}}
                                        <td>{{$dd->lieuDeces->lib_localite}} ({{ $dd->lieuDeces->localiteParent->lib_localite }})</td>
                                        <td>{{ $dd->declarant->nom.' '.$dd->declarant->prenom }}</td>
                                        @if($dd->mouvements()->get("statut")->last()->statut == "En cours")
                                        <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $dd->mouvements()->get("statut")->last()->statut }} de saisie</span></td>
                                        @endif
                                        @if($dd->mouvements()->get("statut")->last()->statut == "Envoyée")
                                        <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Transférée à l'institution supérieure </span></td>
                                        @endif
                                        <td>
                                            @if($dd->mouvements()->get("statut")->last()->statut == "En cours" || $dd->mouvements()->get("statut")->last()->statut == "Renvoyée")
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ $dd->code_declaration_deces }}" class="btn btn-warning show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                            </div>
                                            @endif
                                            <div class="btn-group btn-group-xs">
                                                <a href="{{ route('declarationDeces.etat',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1"><i class="fas fa-print"></i></a>

                                                @if($dd->acte !== null)
                                                <a href="{{ route('acteDeces.display',$dd->code_declaration_deces) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir l'acte"><i class="fas fa-eye"></i></a>
                                                @endif

                                                <a href="{{ route('declarationDeces.edit',$dd->code_declaration_deces) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>

                                                <form  action="{{ route('declarationDeces.destroy',$dd->code_declaration_deces) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom & Prénom</th>
                                        <th>Sexe</th>
                                        <th>Date</th>
                                        <th>Décédé(e) A</th>
                                        <th>Déclarant</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
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
                        {{-- <input type="hidden" id="code_declaration_deces"> --}}
                        <label class="form-label">Transmission du certificat de constatation de décès N°</label>
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
@endsection
@section("scripts")

<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    {{-- <script>
           $(function(){
            $("a.show-envoie-modal").on("click",function(){
                var me = $(this);
                var action = me.attr("href");
                var modal = $("#modal-envoyer-declaration");

                // $("#code_declaration_deces").val(action).attr("readonly",true);
                modal.modal("show");

                return false;

            });

            $("button.generate").on("click", function () {

                var code_registre = $("#code_registre").val();
                var code_declaration_deces = $("#code_declaration_deces").val();
                var route = "{{ route('acteDeces.generate') }}";

                $.post(route, {code_registre:code_registre,code_declaration_deces:code_declaration_deces}, function(reponse) {

                    if(reponse.code == "200"){
                        console.log("success",reponse.message.success);
                        $("#modal-acte").modal("hide");
                        var url = "{{ route('acteDeces.display',':id') }}";
                        url = url.replace(':id',code_declaration_deces);
                        window.open(url);
                        location.reload();

                    }else{
                        var errors = reponse.message;
                        if(errors.length > 0){
                            console.log("erros",errors);
                        }
                    }

                },"json");

                return false;

            });
        });
    </script> --}}


    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){

                var codeDeclaration = $(this).attr('href');

                $("#code_declaration_deces").val(codeDeclaration);
                $("#codedeclaration").val(codeDeclaration);

                $("#modal-declaration-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cdd = $("#codedeclaration").val();
                var route = "{{ route('declarationDeces.mouvement') }}";
                var data = {
                    code_declaration_deces:cdd
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
        });
    </script>
@endsection
