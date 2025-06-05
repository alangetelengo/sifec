@extends('layout.app')
@section('titre')
Fiche de rectification
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

@endsection

@section('corps')
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
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Type acte</th>
                                            <th>Numéro d'acte à rectifier</th>
                                            <th>Réquisition</th>
                                            <th>Réquerant</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rectifications as $rectification)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $rectification->numero_rectification }}</td>
                                                <td>{{ $rectification->numero_acte }}</td>
                                                <td>{{ $rectification->typeActe->lib_type_acte}}</td>
                                                <td>{{ $rectification->requisition->document_requisition != "" ? "En attente du tribunal" : "En cours de traitement" }}</td>
                                                <td>{{ $rectification->nom_prenom_requerant ?? "-" }}</td>
                                                 <td>
                                                    @if($rectification->statut == "En cours de traitement")
                                                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">{{ $rectification->statut }} </span>
                                                    @else
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $rectification->statut }} </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('rectification.etat',$rectification->numero_acte) }}" target="_blank" class="btn btn-sm btn-primary" title="Voir la fiche de rectification"><i class="fas fa-eye"></i></a>
                                                    {{-- envoyer la rectification --}}
                                                    @if ($rectification->statut == 'En cours de traitement')
                                                    <a href="{{ route('rectification.send', $rectification->code_rectification) }}" numeFiche={{ $rectification->numero_rectification }} class="btn btn-sm btn-info show-to-send" title="Envoyer la fiche de rectification au tribunal"><i class="fas fa-plane"></i></a>
                                                    <a href="{{ route('rectification.edit', $rectification->code_rectification) }}" class="btn btn-sm btn-success" title="Modifier la fiche de rectification"><i class="fas fa-pencil-alt"></i></a>
                                                    <form action="{{ route('rectification.destroy', $rectification->code_rectification) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer la fiche de rectification"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                           <th>#</th>
                                            <th>Numéro</th>
                                            <th>Type acte</th>
                                            <th>Numéro d'acte à rectifier</th>
                                            <th>Réquisition</th>
                                            <th>Réquerant</th>
                                             <th>Statut</th>
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
    <div class="modal fade" id="modal-to-send" data-bs-backdrop="static">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span id="numerefiche"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <input type="hidden" id="coderec">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">Envoyer</button>
                        <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Fermer</button>
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
                var numFiche = $(this).attr("numeFiche");

                $("#numerefiche").html("Transmission de la fiche de rectification N° "+numFiche);
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
