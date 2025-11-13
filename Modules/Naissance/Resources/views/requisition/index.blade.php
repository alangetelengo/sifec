@extends('layout.app')
@section('titre')
réquisition
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des réquisitions
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des réquisitions</h4>
                {{-- <a href="{{ route("declarationNaissance.create") }}"><button type="button" class="btn btn-info m-t-2 float-end text-white" >Créer déclaration  <i class="fa fa-plus-circle"></i></button></a> --}}
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Document :</th>
                                        <th>Réquisition :</th>
                                        <th>Numéro réquisition :</th>
                                        <th>Date réquisition :</th>
                                        <th>Statut :</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                        $dernierMouvement = null;
                                        if (isset($item->declarationNaissance->mouvements) && $item->declarationNaissance->mouvements && $item->declarationNaissance->mouvements->count()) {
                                            $dernierMouvement = $item->declarationNaissance->mouvements->sortByDesc('created_at')->first();
                                        }
                                    @endphp
                                    @foreach ($requisitions as $item)
                                    {{-- @isset($item->declarationNaissance) --}}
                                        {{-- @if($item->declarationNaissance->type_declarant == "Personne physique" && $item->declarationNaissance->mouvements->last()->statut == "Envoye au tribunal" || $item->declarationNaissance->mouvements->last()->statut == "Envoyée") --}}
                                        <tr width="100%">
                                            <td>
                                                {{$i++}}
                                            </td>
                                            <td>
                                                @if($item->declarationNaissance != null)
                                                    {{ $item->declarationNaissance->type_declarant }} <strong style="color: red">N°: {{ $item->declarationNaissance->numero_certificat }}</strong>
                                                @elseif($item->rectification != null)
                                                    FICHE DE RECTIFICATION <strong style="color: red">N°: {!! $item->rectification->numero_rectification !!}</strong>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ strtoupper($item->type_requisition) ?? " -" }}</td>
                                            <td>{{ $item->num_requisition ?? " -" }}</td>
                                            <td>{{ $item->date_requisition ? date("d-m-Y", strtotime($item->date_requisition)) : " -" }}</td>
                                            <td>
                                                @if($item->statut_document == "En cours de traitement")
                                                <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">{{ $item->statut_document }} </span>
                                                @else
                                                <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $item->statut_document }} </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    @if($item->declarationNaissance)
                                                        <a href="{{ route('declarationNaissance.etat', $item->declarationNaissance->code_declaration_naissance) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir certificat">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if($item->rectification)
                                                        <a href="{{ route('rectification.show', $item->rectification->code_rectification) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir fiche de rectification">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                    @if($item->num_requisition == "")
                                                        <a href="{{ route('requisition.edit',$item->code_requisition) }}" class="btn btn-dark shadow btn-xs sharp me-1" title="importer le document"><i class="fas fa-pencil-alt"></i></a>
                                                    @else
                                                    <a href="{{ route('requisition.show',$item->code_requisition) }}" target="_blank" class="btn btn-success shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-eye"></i></a>
                                                    @endif
                                                    @can('module.fonctionnalites.requisitions')
                                                        @if($item->type_requisition != "" && $item->statut_document != "Envoyée")

                                                        <a href="{{ $item->code_requisition }}"
                                                           typeRequisition="{{ $item->type_requisition }}"
                                                           sendToCec="{{ $item->code_institution }}"
                                                           libelleCec="{{ $item->institution->lib_institution }}"
                                                           @if($item->declarationNaissance)
                                                               cdn="{{ $item->declarationNaissance->code_declaration_naissance }}"
                                                           @elseif($item->rectification)
                                                               cdn="{{ $item->rectification->code_rectification }}"
                                                           @endif
                                                           numerojug="{{ $item->num_requisition }}"
                                                           class="btn btn-info show-to-send shadow btn-xs sharp me-1"
                                                           title="Envoyer">
                                                            <i class="fas fa-plane"></i>
                                                        </a>
                                                        @endif
                                                    @endcan


                                                    @if($item->statut_document != "Envoyée")
                                                    <form  action="{{ route('requisition.destroy',$item->code_requisition) }}" method="POST" style="display: inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                        {{-- @endif --}}
                                    {{-- @endisset --}}

                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Document :</th>
                                        <th>Réquisition :</th>
                                        <th>Numéro réquisition :</th>
                                        <th>Date réquisition :</th>
                                        <th>Statut du réquisition :</th>
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

{{-- DEBUT ENVOIS DOCUMENT-requisition --}}
<div class="modal fade" id="modal-requisition-send" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="numrequisition"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" readonly class="form-control"  id="coderequisition">
                    <input type="hidden" readonly class="form-control"  id="codeDn">
                    <input type="hidden" readonly class="form-control"  id="send_to">
                    <div class="mb-2 col-md-12">
                        <label class="form-label"> Centre d'Etat civil concerné</label>
                        <select id="libCec" class="form-control" readOnly>

                        </select>
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
{{-- FIN ENVOIS DOCUMENT-requisition --}}
@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>
        $(function(){
            $("a.show-to-send").on("click", function(){
                var codereq = $(this).attr("href");
                var typeRequisition = $(this).attr("typeRequisition");
                var numJugmt = $(this).attr("numerojug");
                var codeDn = $(this).attr("cdn");
                var sendToCec = $(this).attr("cec");
                var libelleCec = $(this).attr("libelleCec");
                var option = "<option>"+libelleCec+"</option>";

                $("#coderequisition").val(codereq);
                $("#codeDn").val(codeDn);
                $("#send_to").val(sendToCec);
                $("#libCec").html(option);

                $("#numrequisition").html("Transmission de la "+typeRequisition+" N° "+numJugmt);

                $("#modal-requisition-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cjugmt = $("#coderequisition").val();
                var codeDeclarationNaissance = $("#codeDn").val();
                var sendto = $("#send_to").val();
                var route = "{{ route('requisition.send') }}";
                var data = {
                    code_requisition:cjugmt,
                    send_to:sendto,
                    cdn:codeDeclarationNaissance
                };

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-requisition-send").modal('hide');
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
