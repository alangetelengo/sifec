@extends('layout.app')
@section('titre')
    Notifications
@endsection

@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .page-notifications-sifec {
        --n-ink: #1a2e26;
        --n-muted: #5c6d66;
        --n-green: #0f5132;
        --n-green-mid: #1b6f4a;
        --n-green-soft: #e8f0eb;
        --n-line: #e2e8e4;
        --n-paper: #ffffff;
        --n-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --n-radius: 14px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, #fafaf8 0%, #eef1ee 100%);
    }

    .page-notifications-sifec .n-card {
        border: 1px solid var(--n-line);
        border-radius: var(--n-radius);
        box-shadow: var(--n-shadow-lg);
        overflow: hidden;
        background: var(--n-paper);
    }

    .page-notifications-sifec .n-card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        background: linear-gradient(135deg, var(--n-green-soft) 0%, #f4f7f5 100%);
        border-bottom: 1px solid var(--n-line);
        padding: 1.15rem 1.35rem;
    }

    .page-notifications-sifec .n-card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--n-ink);
        letter-spacing: -0.02em;
    }

    .page-notifications-sifec .n-card-header p {
        margin: 0.4rem 0 0;
        font-size: 0.88rem;
        color: var(--n-muted);
        line-height: 1.5;
        max-width: 42rem;
    }

    .page-notifications-sifec .n-btn-mark-all {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.45rem 1rem;
        background: var(--n-green-mid);
        border: 1px solid var(--n-green-mid);
        color: #fff !important;
        box-shadow: 0 2px 8px rgba(27, 111, 74, 0.25);
        white-space: nowrap;
    }

    .page-notifications-sifec .n-btn-mark-all:hover {
        background: var(--n-green);
        border-color: var(--n-green);
        color: #fff !important;
    }

    .page-notifications-sifec .n-card-body {
        padding: 1.15rem 1.25rem 1.35rem;
    }

    .page-notifications-sifec .table-responsive {
        border-radius: 10px;
        border: 1px solid var(--n-line);
        overflow: auto;
        background: #fafcfb;
    }

    .page-notifications-sifec #notifications-table {
        margin: 0 !important;
    }

    .page-notifications-sifec #notifications-table thead th {
        background: #f0f4f1;
        color: var(--n-ink);
        font-weight: 600;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 2px solid #d5ded8 !important;
        padding: 0.75rem 0.85rem !important;
        white-space: nowrap;
    }

    .page-notifications-sifec #notifications-table tbody td {
        padding: 0.75rem 0.85rem !important;
        vertical-align: middle;
        border-color: #eef1ee !important;
        font-size: 0.9rem;
        color: #2d3d35;
    }

    .page-notifications-sifec #notifications-table tbody tr:hover td {
        background: rgba(232, 240, 235, 0.35) !important;
    }

    .page-notifications-sifec tr.notifications-table-unread td {
        background: rgba(232, 245, 237, 0.65) !important;
    }

    .page-notifications-sifec tr.notifications-table-unread:hover td {
        background: rgba(220, 237, 228, 0.85) !important;
    }

    .page-notifications-sifec .sifec-notif-table-msg {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        min-width: 0;
    }

    .page-notifications-sifec .sifec-notif-table-msg .sifec-notif-item-icon {
        flex-shrink: 0;
        width: 2.35rem;
        height: 2.35rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: #fff;
    }

    .page-notifications-sifec .sifec-notif-item-icon--default { background: linear-gradient(135deg, #64748b, #475569); }
    .page-notifications-sifec .sifec-notif-item-icon--success { background: linear-gradient(135deg, #1b6f4a, #0f5132); }
    .page-notifications-sifec .sifec-notif-item-icon--registre { background: linear-gradient(135deg, #0d9488, #0f766e); }
    .page-notifications-sifec .sifec-notif-item-icon--naissance { background: linear-gradient(135deg, #059669, #047857); }
    .page-notifications-sifec .sifec-notif-item-icon--deces { background: linear-gradient(135deg, #6b7280, #4b5563); }
    .page-notifications-sifec .sifec-notif-item-icon--mariage { background: linear-gradient(135deg, #d97706, #b45309); }
    .page-notifications-sifec .sifec-notif-item-icon--declaration { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .page-notifications-sifec .sifec-notif-item-icon--tribunal { background: linear-gradient(135deg, #4338ca, #3730a3); }
    .page-notifications-sifec .sifec-notif-item-icon--rectif { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    .page-notifications-sifec .sifec-notif-item-icon--info { background: linear-gradient(135deg, #0891b2, #0e7490); }

    .page-notifications-sifec .sifec-notif-table-msg-body {
        min-width: 0;
        flex: 1;
    }

    .page-notifications-sifec .msg-title {
        font-weight: 600;
        color: var(--n-ink);
        margin: 0;
        font-size: 0.9rem;
        line-height: 1.45;
        word-break: break-word;
    }

    .page-notifications-sifec .msg-hint {
        font-size: 0.78rem;
        color: var(--n-muted);
        margin: 0.25rem 0 0;
    }

    .page-notifications-sifec .badge-type-notif {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 0.35em 0.65em;
        border-radius: 8px;
    }

    .page-notifications-sifec .n-actions .btn {
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .page-notifications-sifec .n-actions .btn-outline-primary {
        color: var(--n-green-mid) !important;
        border-color: rgba(27, 111, 74, 0.45);
    }

    .page-notifications-sifec .n-actions .btn-outline-primary:hover {
        background: var(--n-green-mid) !important;
        border-color: var(--n-green-mid) !important;
        color: #fff !important;
    }

    .page-notifications-sifec .n-pagination {
        margin-top: 1.25rem;
    }

    .page-notifications-sifec .n-pagination .pagination {
        margin-bottom: 0;
    }

    .page-notifications-sifec .n-pagination .page-link {
        border-radius: 8px;
        margin: 0 2px;
        color: var(--n-green-mid);
        border-color: var(--n-line);
    }

    .page-notifications-sifec .n-pagination .page-item.active .page-link {
        background: var(--n-green-mid);
        border-color: var(--n-green-mid);
        color: #fff;
    }

    .page-notifications-sifec .sifec-notif-empty {
        text-align: center;
        padding: 1rem 0.5rem;
        color: #64748b;
    }

    .page-notifications-sifec .sifec-notif-empty-icon {
        display: block;
        font-size: 2rem;
        color: #cbd5e1;
        margin-bottom: 0.5rem;
    }

    .page-notifications-sifec .sifec-notif-empty-title {
        font-size: 1rem;
        font-weight: 600;
        color: #475569;
        margin: 0 0 0.25rem;
    }

    .page-notifications-sifec .sifec-notif-empty-hint {
        font-size: 0.88rem;
        margin: 0;
        color: #94a3a8;
    }

    .page-notifications-sifec #notifications-table_wrapper .dataTables_filter input {
        border-radius: 8px;
        border: 1px solid #cfd8d3;
        padding: 0.35rem 0.65rem;
    }

    .page-notifications-sifec #notifications-table_wrapper .dataTables_filter input:focus {
        border-color: var(--n-green-mid);
        box-shadow: 0 0 0 2px rgba(27, 111, 74, 0.2);
        outline: none;
    }

    .page-notifications-sifec #notifications-table_wrapper .dataTables_length select {
        border-radius: 8px;
        border-color: #cfd8d3;
    }
</style>
@endsection

@php
    $notifPreview = function ($data) {
        $raw = $data['message'] ?? 'Nouvelle notification';
        $normalized = preg_replace('#<br\s*/?>#i', ' ', $raw);
        $plain = trim(preg_replace('/\s+/u', ' ', strip_tags($normalized)));

        return \Illuminate\Support\Str::limit($plain, 280);
    };

    $notifMeta = function ($type) {
        $label = 'Notification';
        $variant = 'default';
        $icon = 'fa-bell';

        if (str_contains($type, 'RegistreValideParTribunal')) {
            $label = 'Registre validé';
            $variant = 'success';
            $icon = 'fa-check-circle';
        } elseif (str_contains($type, 'CreationRegistreParCec')) {
            $label = 'Nouveau registre';
            $variant = 'registre';
            $icon = 'fa-book';
        } elseif (str_contains($type, 'FeuilletRegistreAjoute')) {
            $label = 'Feuillet registre';
            $variant = 'registre';
            $icon = 'fa-file-alt';
        } elseif (str_contains($type, 'ActeAValider')) {
            $label = 'Acte naissance';
            $variant = 'naissance';
            $icon = 'fa-user';
        } elseif (str_contains($type, 'ActeDecesAValider')) {
            $label = 'Acte décès';
            $variant = 'deces';
            $icon = 'fa-file-text';
        } elseif (str_contains($type, 'ActeMariageAValider')) {
            $label = 'Acte mariage';
            $variant = 'mariage';
            $icon = 'fa-heart';
        } elseif (str_contains($type, 'DeclarationMariageEnvoyee')) {
            $label = 'Déclaration mariage';
            $variant = 'mariage';
            $icon = 'fa-heart';
        } elseif (str_contains($type, 'DeclarationEnvoyeeCentre')) {
            $label = 'Déclaration';
            $variant = 'declaration';
            $icon = 'fa-paper-plane';
        } elseif (str_contains($type, 'DocumentImporteTribunal')) {
            $label = 'Document tribunal';
            $variant = 'tribunal';
            $icon = 'fa-gavel';
        } elseif (str_contains($type, 'RectificationEnvoyeeTribunal')) {
            $label = 'Rectification';
            $variant = 'rectif';
            $icon = 'fa-edit';
        } elseif (str_contains($type, 'FormulaireTypeValide')) {
            $label = 'Formulaire';
            $variant = 'info';
            $icon = 'fa-check-square';
        } elseif (str_contains($type, 'DemandeDispenseEnvoyer')) {
            $label = 'Dispense';
            $variant = 'info';
            $icon = 'fa-envelope';
        }

        return compact('label', 'variant', 'icon');
    };

    $notifBadgeClass = function (string $variant): string {
        return match ($variant) {
            'success' => 'badge-success',
            'registre' => 'badge-info',
            'naissance' => 'badge-success',
            'deces' => 'badge-secondary',
            'mariage' => 'badge-warning',
            'declaration' => 'badge-primary',
            'tribunal' => 'badge-dark',
            'info' => 'badge-info',
            default => 'badge-secondary',
        };
    };
@endphp

@section('corps')
<div class="page-notifications-sifec">
<div class="row">
    <div class="col-xl-12">
        <div class="card n-card border-0">
            <div class="n-card-header">
                <div>
                    <h4>Centre de notifications</h4>
                    <p>
                        Registres, déclarations, actes à valider (dont validation par code OTP pour les actes de naissance),
                        documents tribunal et autres alertes métier.
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-sm n-btn-mark-all" onclick="markAllAsRead()">
                        <i class="fa fa-check me-1"></i>Tout marquer comme lu
                    </button>
                </div>
            </div>
            <div class="card-body n-card-body">
                <div class="table-responsive">
                    <table id="notifications-table" class="display table table-hover align-middle" style="min-width: 845px">
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
                                @php
                                    $meta = $notifMeta($notification->type);
                                    $preview = $notifPreview($notification->data ?? []);
                                    if ($meta['variant'] === 'rectif') {
                                        $badgeClass = 'badge badge-type-notif text-white';
                                        $badgeStyle = 'background-color:#6d28d9;border:none;';
                                    } else {
                                        $badgeClass = 'badge badge-type-notif ' . $notifBadgeClass($meta['variant']);
                                        $badgeStyle = null;
                                    }
                                @endphp
                                <tr class="{{ $notification->read_at ? '' : 'notifications-table-unread' }}">
                                    <td>{{ $loop->iteration + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                                    <td>
                                        <div class="sifec-notif-table-msg">
                                            <span class="sifec-notif-item-icon sifec-notif-item-icon--{{ $meta['variant'] }}" aria-hidden="true">
                                                <i class="fa {{ $meta['icon'] }}"></i>
                                            </span>
                                            <div class="sifec-notif-table-msg-body">
                                                <p class="msg-title">{{ $preview }}</p>
                                                @if(!empty($notification->data['url']))
                                                    <p class="msg-hint mb-0">Un lien « Ouvrir » est disponible dans la colonne Actions.</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="{{ $badgeClass }}" @if($badgeStyle) style="{{ $badgeStyle }}" @endif>{{ $meta['label'] }}</span>
                                    </td>
                                    <td data-order="{{ $notification->created_at->timestamp }}">
                                        <span class="fw-semibold" style="color:#1a2e26;">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                        <br><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($notification->read_at)
                                            <span class="badge badge-success">Lu</span>
                                        @else
                                            <span class="badge badge-warning text-dark">Non lu</span>
                                        @endif
                                    </td>
                                    <td class="n-actions">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$notification->read_at)
                                                <a href="{{ route('notifications.read', $notification->id) }}"
                                                   class="btn btn-outline-primary"
                                                   title="Marquer comme lu et ouvrir si un lien existe">
                                                    <i class="fa fa-check"></i> Lu
                                                </a>
                                            @endif
                                            @if(!empty($notification->data['url']))
                                                <a href="{{ $notification->data['url'] }}"
                                                   class="btn btn-outline-secondary"
                                                   title="Ouvrir la page liée">
                                                    <i class="fa fa-external-link"></i> Ouvrir
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="sifec-notif-empty">
                                            <span class="sifec-notif-empty-icon" aria-hidden="true"><i class="fa fa-inbox"></i></span>
                                            <p class="sifec-notif-empty-title">Aucune notification</p>
                                            <p class="sifec-notif-empty-hint">Vous n’avez pas encore reçu d’alerte sur cette plateforme.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($notifications->hasPages())
                    <div class="d-flex justify-content-center n-pagination">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
$(document).ready(function() {
    if ($('#notifications-table tbody tr').length && !$('#notifications-table tbody td[colspan]').length) {
        $('#notifications-table').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json'
            },
            paging: false,
            info: false,
            order: [[3, 'desc']],
            columnDefs: [
                { orderable: false, targets: [5] }
            ]
        });
    }
});

function markAllAsRead() {
    if (confirm('Marquer toutes les notifications comme lues ?')) {
        window.location.href = "{{ route('notifications.markAllAsRead') }}";
    }
}
</script>
@endsection
