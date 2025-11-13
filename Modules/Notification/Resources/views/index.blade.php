@extends('layout.app')
@section('titre')
    Notifications
@endsection

@section('styles')
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
                <h4>Liste des notifications</h4>
                <div class="float-end">
                    <button type="button" class="btn btn-success btn-sm" onclick="markAllAsRead()">
                        <i class="fa fa-check"></i> Tout marquer comme lu
                    </button>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="notifications-table" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifications as $notification)
                                    <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $notification->data['message'] ?? 'Nouvelle notification' }}</strong>
                                            @if(isset($notification->data['url']))
                                                <br><small class="text-muted">Cliquez pour voir le dossier</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $typeClass = 'badge-info';
                                                $typeLabel = 'Notification';

                                                if(str_contains($notification->type, 'ActeAValiderNotification')) {
                                                    $typeClass = 'badge-success';
                                                    $typeLabel = 'Acte Naissance';
                                                } elseif(str_contains($notification->type, 'ActeDecesAValiderNotification')) {
                                                    $typeClass = 'badge-danger';
                                                    $typeLabel = 'Acte Décès';
                                                } elseif(str_contains($notification->type, 'ActeMariageAValiderNotification')) {
                                                    $typeClass = 'badge-warning';
                                                    $typeLabel = 'Acte Mariage';
                                                } elseif(str_contains($notification->type, 'DeclarationEnvoyeeCentreNotification')) {
                                                    $typeClass = 'badge-primary';
                                                    $typeLabel = 'Déclaration';
                                                } elseif(str_contains($notification->type, 'DocumentImporteTribunalNotification')) {
                                                    $typeClass = 'badge-dark';
                                                    $typeLabel = 'Document Tribunal';
                                                } elseif(str_contains($notification->type, 'RectificationEnvoyeeTribunalNotification')) {
                                                    $typeClass = 'badge-secondary';
                                                    $typeLabel = 'Rectification';
                                                }
                                            @endphp
                                            <span class="badge {{ $typeClass }}">{{ $typeLabel }}</span>
                                        </td>
                                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($notification->read_at)
                                                <span class="badge badge-success">Lu</span>
                                            @else
                                                <span class="badge badge-warning">Non lu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-xs">
                                                @if(!$notification->read_at)
                                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                                       class="btn btn-sm btn-primary"
                                                       title="Marquer comme lu">
                                                        <i class="fa fa-check"></i> Lu
                                                    </a>
                                                @endif

                                                @if(isset($notification->data['url']))
                                                    <a href="{{ $notification->data['url'] }}"
                                                       class="btn btn-sm btn-info"
                                                       title="Voir le dossier">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fa fa-info-circle"></i> Aucune notification trouvée
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $notifications->links() }}
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
<script>
$(document).ready(function() {
    $('#notifications-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[3, "desc"]], // Trier par date décroissante
        "columnDefs": [
            { "orderable": false, "targets": [5] } // Désactiver le tri sur la colonne Actions
        ]
    });
});

function markAllAsRead() {
    if (confirm('Voulez-vous vraiment marquer toutes les notifications comme lues ?')) {
        // Ici tu peux ajouter une route AJAX pour marquer toutes comme lues
        window.location.href = "{{ route('notifications.markAllAsRead') }}";
    }
}
</script>
@endsection
