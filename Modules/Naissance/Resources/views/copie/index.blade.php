@extends('layout.app')
@section('titre')
Copie d'acte de naissance
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
                <h4>Liste des copies d'actes de naissance</h4>
                <form action="{{route('acte.naissance.getcopie')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="code_acte_naissance" placeholder="Code acte de naissance" required>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary mb-2 btn-block">Créer copie</button>
                        </div>
                    </div>
                </form>
           </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>N° Copie</th>
                                        <th>N° Acte</th>
                                        <th>Enfant: Nom</th>
                                        <th>Enfant: Prénom</th>
                                        <th>Enfant: Date naissance</th>
                                        <th>Enfant: Sexe</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($copies as $copie)
                                    <tr width="100%">
                                        <td>{{ $copie->code_copie_acte }}</td>
                                        <td>{{ $copie->niupp }}</td>
                                        <td>{{ $copie->actenaissance->declaration->enfant->nom }}</td>
                                        <td>{{ $copie->actenaissance->declaration->enfant->prenom }}</td>
                                        <td>{{ date("d-m-Y", strtotime($copie->actenaissance->declaration->enfant->date_naissance)) }}</td>
                                        <td>{{ $copie->actenaissance->declaration->enfant->sexe == "M" ? "Masculin" : "Féminin" }}</td>

                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown">
                                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('acte.naissance.copie',$copie->code_copie_acte) }}" target="_blank">Générer copie acte</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>N° Copie</th>
                                        <th>N° Acte</th>
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
                        {{-- @if($registre != null)
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Régistre <span class="text-danger">*</span></label>
                            <select id="code_acte" class="form-control form-control wide">
                                <option value="{{ $registre->acteRegistres->where("used",0)->first()->code_acte }}">{{$registre->code_registre }} ({{$registre->lib_registre}})</option>
                            </select>
                        </div>
                        @endif
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Numéro déclaration naissance <span class="text-danger">*</span></label>
                            <input id="code_declaration_naissance" type="text" class="form-control" class="form-control">
                        </div> --}}
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
                var route = "{{ route('acte.generate') }}";

                $.post(route, {code_acte:code_acte,code_declaration_naissance:code_declaration_naissance}, function(reponse) {

                    if(reponse.code == "200"){
                        console.log("success",reponse.message.success);
                        $("#modal-acte").modal("hide");
                        var url = "{{ route('acte.naissance.display',':id') }}";
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
