@extends('layout.app')
@section('titre')
Liste des réquisitions importées par le tribunal
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
                <h4>Liste des réquisitions importées par le tribunal</h4>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th style="min-width: 110px;">Module</th>
                                        <th style="min-width: 180px;">Document émis</th>
                                        <th style="min-width: 160px;">Document reçu</th>
                                        <th style="min-width: 140px;">Date de réception</th>
                                        <th style="min-width: 100px;">Statut</th>
                                        <th style="min-width: 120px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dossiers as $dossier)
                                        <tr>

                                            <td>{{ $dossier->module }}</td>
                                            <td>{{ $dossier->declaration->type_declaration }}</td>
                                            <td>{{ $dossier->requisition->typeRequisition->lib_type_requisition ?? '' }}</td>
                                            <td>{{ $dossier->created_at ? $dossier->created_at->format('d/m/Y H:i') : '' }}</td>
                                            <td>
                                                {{ !empty($dossier->mouvement) ? $dossier->mouvement->lib_mouvement : 'Document non transmis' }}
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-primary">Voir détails</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune réquisition trouvée</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

@endsection
