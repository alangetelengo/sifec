@extends('layout.app')
@section('titre')
Réquisitions
@endsection
@section("styles")

<!-- Datatable -->
    <link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4>Requisitions aux fins de déclaration tardive de naissance</h4>
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
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i=1;
                                    @endphp
                                    @foreach ($requisitions as $requisition)
                                    <tr width="100%">
                                        <td>{{ $requisition->numero_req}}/{{date("d-M-Y", strtotime($requisition->created_at))}}</td>
                                        <td>{{ $requisition->declarant->nom.' '.$requisition->Declarant->prenom }}</td>
                                        <td>{{ $requisition->enfant->nom }}</td>
                                        <td>{{ $requisition->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($requisition->enfant->date_naissance)) }}</td>
                                        <td>{{ $requisition->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('certificatNonInscription.etat',$requisition->code_declaration_naissance) }}" target="_blank">Voir certificat</a>
                                                    @if($requisition->top_requisition == 1)
                                                        <a class="dropdown-item" href="{{route('RequisitionTardive.etat',$requisition->code_declaration_naissance)}}" target="_blank">Voir la réquisition</a>
                                                        @else
                                                        <form action="{{ route('RequisitionTardive.generateRequisition',$requisition->code_declaration_naissance) }}" method="post">
                                                            @csrf
                                                            @method("PUT")
                                                            <button type="submit" class="dropdown-item">Générer la réquisition</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Déclarant</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
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

<div class="modal fade" id="modal-acte" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><span class="module-title">  </span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    @if($registre != null)
                    <div class="mb-2 col-md-6">
                        <label class="form-label">Régistre <span class="text-danger">*</span></label>
                        <input id="code_registre" type="text" class="form-control" class="form-control" readonly value="{{ $registre->code_registre }}">
                    </div>
                    @else
                    <label class="form-label"> <span class="text-danger">Aucun registre disponible</span></label>
                    @endif

                    <div class="mb-2 col-md-6">
                        <label class="form-label">Numéro de la déclaration <span class="text-danger">*</span></label>
                        <input id="code_declaration_naissance" type="text" class="form-control" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary light generate">Valider</button>
                <button type="button" class="btn btn-sm btn-danger light" data-bs-dismiss="modal">Fermer</button>
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
         $("a.show-acte-modal").on("click",function(){
             var me = $(this);
             var action = me.attr("href");
             var modal = $("#modal-acte");

             $("#code_declaration_naissance").val(action).attr("readonly",true);
             modal.modal("show");

             return false;

         });

         $("button.generate").on("click", function () {

             var code_acte = $("#code_acte").val();
             var code_declaration_naissance = $("#code_declaration_naissance").val();
             var route = "{{ route('requisition.generateacte') }}";

             $.post(route, {code_acte:code_acte,code_declaration_naissance:code_declaration_naissance}, function(reponse) {

                 if(reponse.code == "200"){
                     console.log("success",reponse.message.success);
                     $("#modal-acte").modal("hide");
                     var url = "{{ route('acteNaissance.display',':id') }}";
                     url = url.replace(':id',code_declaration_naissance);
                     window.open(url);
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
    </script>
@endsection
