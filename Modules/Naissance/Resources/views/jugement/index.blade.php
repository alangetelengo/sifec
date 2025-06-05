@extends('layout.app')
@section('titre')
Jugements
@endsection
@section("styles")
<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
@endsection
@section('sous-titre')
    Liste des jugements
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Liste des jugements</h4>
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
                                        <th>Type :</th>
                                        <th>Numéro :</th>
                                        <th>Du :</th>
                                        <th>Statut du Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($jugements as $item)
                                    <tr width="100%">
                                        <td>
                                            {{$i++}}
                                        </td>
                                        <td>{{ $item->type_jugement }}</td>
                                        <td>{{ $item->num_jugement }}</td>
                                        <td>{{ $item->date_jugement != "" ? date("d-m-Y", strtotime($item->date_jugement)) : "" }}</td>
                                        <td>
                                            @if($item->statut_document == "En cours de traitement")
                                            <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;">{{ $item->statut_document }} </span>
                                            @else
                                            <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $item->statut_document }} </span>
                                            @endif
                                        </td>

                                        {{-- <td style="width: 15%"> --}}
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                {{-- @if($item->statut_document != "Envoye")
                                                <a href="{{ route('jugement.edit',$item->code_jugement) }}" class="btn btn-dark shadow btn-xs sharp me-1" title={{ $item->cui == null ? "Traiter le document" : "Modifier" }}  ><i class="fas fa-pencil-alt"></i></a>

                                                    @if($item->cui != null)

                                                    <a href="{{ $item->code_jugement }}" typejugmt="{{ $item->type_jugement }}" send_to_cec="{{ $item->code_institution }}" numerojug="{{ $item->num_jugement }}" class="btn btn-info show-to-send-to-cec shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                                    @else
                                                    <a href="{{ $item->code_jugement }}" typejugmt="{{ $item->type_jugement }}" numerojug="{{ $item->num_jugement }}" class="btn btn-info show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>
                                                    @endif
                                                @endif --}}
                                                <a href="{{ $item->code_jugement }}" typejugmt="{{ $item->type_jugement }}" numerojug="{{ $item->num_jugement }}" class="btn btn-info show-to-send shadow btn-xs sharp me-1" title="Envoyer" ><i class="fas fa-plane"></i></a>

                                                <a href="{{ route('jugement.show',$item->code_jugement) }}" target="_blank" class="btn btn-warning shadow btn-xs sharp me-1" title="Voir document"><i class="fas fa-eye"></i></a>
                                                @if($item->statut_document != "Envoye")
                                                <form  action="{{ route('jugement.destroy',$item->code_jugement) }}" method="POST" style="display: inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Supprimer"><i class="fa fa-trash"></i></button>
                                                </form>
                                                @endif
                                                @can('module.ActeNaissance.certificatNonInscription.create')
                                                    @if($item->statut_document == "Envoye")
                                                    @can("module.ActeNaissance.jugementAutorisation.create")
                                                        <a href="{{ route('declarationNaissance.jugement',$item->code_jugement) }}" class="btn btn-success shadow btn-xs sharp me-1" title="Traiter le document"><i class="fas fa-pencil-alt"></i></a>
                                                    @endcan
                                                    {{-- <a href="{{ route('jugement.create',$item->code_jugement) }}" class="btn btn-success shadow btn-xs sharp me-1" title="Traiter le document alange"><i class="fas fa-pencil-alt"></i></a> --}}
                                                    @endif
                                                @endcan
                                                @if($item->type_jugement == "JUGEMENT D'ANNULATION D'ACTE")
                                                    @can('module.ActeNaissance.annulerActe')
                                                    <a href="{{ route('declarationNaissance.jugement',$item->code_jugement) }}" class="btn btn-success shadow btn-xs sharp me-1" title="Traiter le document"><i class="fas fa-pencil-alt"></i></a>
                                                    {{-- <a href="{{ route('jugement.create',$item->code_jugement) }}" class="btn btn-success shadow btn-xs sharp me-1" title="Traiter le document"><i class="fas fa-pencil-alt"></i></a> --}}
                                                    @endcan
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Type :</th>
                                        <th>Numéro :</th>
                                        <th>Du :</th>
                                        <th>Statut du Document</th>
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

{{-- DEBUT ENVOIS DOCUMENT-JUGEMENT TOUT EN SACHANT DEJA LE CEC DE PROVENANCE DU JUGMENT --}}
<div class="modal fade" id="modal-jugement-send-to-cec" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="numjugementcec"></span></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <input type="hidden" class="form-control"  id="codejugementcec">
            <input type="hidden" class="form-control"  id="send_to_cec">
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send-to-cec">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

{{-- DEBUT ENVOIS DOCUMENT-JUGEMENT --}}
<div class="modal fade" id="modal-jugement-send" data-bs-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span id="numjugement"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" readonly class="form-control"  id="codejugement">
                    @isset($cecsDestinations)
                    <div class="mb-2 col-md-12">
                        <label class="form-label"> Centre d'Etat civil concerné</label>
                        <select id="send_to" class="form-control">
                            <option value="" disabled selected>Selectionner</option>
                            @if (count($cecsDestinations)>0)
                                @foreach ($cecsDestinations as $cec)
                                    <option value="{{$cec->code_institution }}">{{$cec->lib_institution }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @endisset
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info btn-sm text-white" id="btn-send">Envoyer</button>
                <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
{{-- FIN ENVOIS DOCUMENT-JUGEMENT --}}
@endsection
@section("scripts")
<!-- Datatable -->
    <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
    <script>
        $(function(){
            $("a.show-to-send-to-cec").on("click", function(){
                var codeJug = $(this).attr("href");
                var typeJugmt = $(this).attr("typejugmt");
                var numJugmt = $(this).attr("numerojug");
                var sendtocec = $(this).attr("send_to_cec");

                $("#codejugementcec").val(codeJug);
                $("#send_to_cec").val(sendtocec);
                $("#numjugementcec").html("Transmission du "+typeJugmt+" N° "+numJugmt);

                $("#modal-jugement-send-to-cec").modal("show");
                return false;
            });
            $("#btn-send-to-cec").on("click",function(){
                var cjugmt = $("#codejugementcec").val();
                var sendto = $("#send_to_cec").val();
                var route = "{{ route('jugement.send') }}";
                var data = {
                    code_jugement:cjugmt,
                    send_to:sendto
                };
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-jugement-send").modal('hide');
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


// fin
            $("a.show-to-send").on("click", function(){
                var codeJug = $(this).attr("href");
                var typeJugmt = $(this).attr("typejugmt");
                var numJugmt = $(this).attr("numerojug");

                $("#codejugement").val(codeJug);
                $("#numjugement").html("Transmission du "+typeJugmt+" N° "+numJugmt);

                $("#modal-jugement-send").modal("show");
                return false;
            });

            $("#btn-send").on("click",function(){
                var cjugmt = $("#codejugement").val();
                var sendto = $("#send_to").val();
                var route = "{{ route('jugement.send') }}";
                var data = {
                    code_jugement:cjugmt,
                    send_to:sendto
                };
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-jugement-send").modal('hide');
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
