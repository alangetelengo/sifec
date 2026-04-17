@extends('layout.app')
@section('titre')
Fiche de rectification
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<style>
    /* Évite le chevauchement visuel entre la ligne d’actions et un éventuel pied de tableau DataTables */
    .rectification-fiches-dt td.rectif-col-action,
    .rectification-fiches-dt th.rectif-col-action {
        vertical-align: middle;
        min-width: 10.5rem;
    }
    .rectification-fiches-dt .rectif-actions {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 0.35rem;
    }
    .rectification-fiches-dt .rectif-actions form {
        display: inline-flex;
        margin: 0;
    }
</style>

@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4> Liste des fiches de rectification</h4>
                    <a href="{{ route("rectification.create") }}"><button type="button" class="btn btn-sm btn-warning">Créer une fiche de rectification</button></a>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display rectification-fiches-dt" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Numéro d'acte à rectifier</th>
                                            <th>Type acte</th>
                                            <th>Réquisition</th>
                                            <th>Réquerant</th>
                                            <th>Statut</th>
                                            <th class="text-end rectif-col-action">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rectifications as $rectification)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $rectification->numero_rectification }}</td>
                                                <td>{{ $rectification->numero_acte }}</td>
                                                <td>{{ $rectification->typeActe->lib_type_acte}}</td>
                                                <td>{{ $rectification->requisition != null ? "En attente du tribunal" : "En cours de traitement" }}</td>
                                                <td>{{ $rectification->nom_prenom_requerant ?? "-" }}</td>
                                                 <td>
                                                    @if($rectification->statut == "En cours de traitement")
                                                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">{{ $rectification->statut }} </span>
                                                    @else
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $rectification->statut }} </span>
                                                    @endif
                                                </td>
                                                <td class="text-end rectif-col-action">
                                                    <div class="rectif-actions">
                                                        <a href="{{ route('rectification.etat',$rectification->numero_acte) }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary" title="Voir la fiche de rectification" aria-label="Voir la fiche de rectification"><i class="fas fa-eye" aria-hidden="true"></i></a>
                                                        @if ($rectification->statut == 'En cours de traitement')
                                                            <a href="{{ $rectification->code_rectification }}" numerefiche="{{ $rectification->numero_rectification }}" requerant="{{ $rectification->nom_prenom_requerant }}" class="btn btn-sm btn-info show-to-send text-white" title="Envoyer la fiche de rectification au tribunal" aria-label="Envoyer la fiche au tribunal"><i class="fas fa-plane" aria-hidden="true"></i></a>
                                                            <a href="{{ route('rectification.edit', $rectification->code_rectification) }}" class="btn btn-sm btn-success" title="Modifier la fiche de rectification" aria-label="Modifier la fiche"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                            <form action="{{ route('rectification.destroy', $rectification->code_rectification) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer la fiche de rectification" aria-label="Supprimer la fiche"><i class="fas fa-trash" aria-hidden="true"></i></button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-to-send" tabindex="-1" aria-labelledby="modalSendLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalSendLabel">
                        <i class="fas fa-paper-plane"></i> Confirmation d’envoi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3" role="alert">
                        Êtes-vous sûr de vouloir envoyer la fiche de rectification&nbsp;?
                    </div>
                    <ul class="list-group mb-3">
                        <li class="list-group-item">
                            <strong>Numéro de la fiche:</strong>
                            <span id="numerefiche" class="text-primary"></span>
                        </li>
                        <!-- Ajoute ici d'autres infos si besoin, par exemple : -->
                        <li class="list-group-item"><strong>Requérant :</strong> <span id="nomrequerant"></span></li>
                    </ul>
                    <input type="hidden" id="coderec">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">
                        <i class="fas fa-paper-plane"></i> Envoyer
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@section('scripts')
    <!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){
                var coderectification = $(this).attr("href");
                var numerefiche = $(this).attr("numerefiche");
                var nomrequerant = $(this).attr("requerant");

                $("#numerefiche").html(numerefiche);
                $("#nomrequerant").html(nomrequerant);
                $("#coderec").val(coderectification);
                $("#modal-to-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var codeRect = $("#coderec").val();
                var route = "{{ route('rectification.send',':id') }}";
                route = route.replace(':id', codeRect);

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.get(route, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-to-send").modal('hide');
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
