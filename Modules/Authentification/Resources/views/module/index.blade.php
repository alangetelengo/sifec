@extends('layout.app')
@section('titre')
Liste des modules
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4> Liste des modules</h4>
                    <a href="{{ route("module.create") }}"><button type="button" class="btn btn-sm btn-warning">Créer un module</button></a>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" style="min-width: 845px">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Libéllé</th>
                                            <th>Desciption</th>
                                            <th>Fonctionnalités</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?= $i=1; ?>
                                        @forelse ($modules as $item)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->lib_module }}</td>
                                            <td>{{ $item->description_module }}</td>
                                            <td>
                                                <a href="{{ $item->code_module }}" class="show-module" data-module="{{ $item->lib_module }}" data-code="{{ $item->code_module }}">Voir fonctionnalités ({{  $item->fonctionnalites->count() }})</a>
                                            </td>
                                            @if($item->etat_module == "Activé")
                                            <td><span class="badge light badge-success" style="font-size: 13px;font-weight:600;">{{ $item->etat_module}}</span></td>
                                            @endif
                                            @if($item->etat_module == "Désactivé")
                                            <td><span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">{{ $item->etat_module}}</span></td>
                                            @endif
                                            <td>
                                                <div class="btn-group btn-group-xs">
                                                    <a href="{{ route('module.edit',$item->code_module) }}" class="btn btn-info shadow btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                </div>
                                                <div class="btn-group btn-group-xs">
                                                    <form action="{{ route("module.destroy", $item->code_module) }}" method="post">
                                                        @csrf
                                                        @method("DELETE")
                                                        <button class="btn btn-danger shadow btn-xs sharp" type="submit"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                            <div class="invalid-feedback">Aucune donnée disponible</div>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Libéllé</th>
                                            <th>Desciption</th>
                                            <th>Fonctionnalités</th>
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


        <div class="modal fade" id="modal-fonctionnalites" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="module-title">  </span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div id="fonctionnalites"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

      <script>
        $(function(){
            $("a.show-module").on("click",function(){
                var me = $(this);
                var code_module = me.attr("href");
                var lib_module = me.attr("data-module");
                var modal = $("#modal-fonctionnalites");

                $("span.module-title").html(lib_module);
                getFonctionnalites(code_module);
                modal.modal("show");

                return false;
            });
        });

        function getFonctionnalites(code_module){
            var route = "{{ route('module.fonctionnalites',':id') }}"

            route = route.replace(":id", code_module);

            $.get(route, function (data) {

                //console.log(data['fonctionnalites']["code_fonctionnalite"]);
                var int = 0;
                var table = '<div class="table-responsive">'+
                                '<table class="table table-responsive-md">'+
                                    '<thead>'+
                                        '<tr>'+
                                            '<th>#</th>'+
                                            '<th><strong>Libellé</strong></th>'+

                                            '<th><strong>Description</strong></th>'+
                                            '<th><strong>Etat</strong></th>'+
                                    ' </tr>'+
                                    '</thead>'+
                                    '<tbody>';

                if(data.fonctionnalites.length > 0){

                    for( var i=0; i < data.fonctionnalites.length ; i++){
                        int ++;
                        //alert(data.fonctionnalites[i].lib_fonctionnalite);
                        table +='<tr>'+
                                    '<td>'+int+'</td>'+
                                     '<td>'+data.fonctionnalites[i].lib_fonctionnalite+'</td>'+

                                     '<td>'+data.fonctionnalites[i].description_fonctionnalite+'</td>'+
                                     '<td>'+data.fonctionnalites[i].etat_fonctionnalite+'</td>';
                    }
                }
                table += "</tr></tbody></table></div>";
                    $("#fonctionnalites").html(table);



            });
        }
      </script>
@endsection
