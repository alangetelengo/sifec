@extends('layout.app')
@section('titre')
    Gestion des Utilisateurs
@endsection
@section('styles')
<style>
    /* Sélection en masse */
    .bulk-panel {
        background: #1a1a2e;
        border-radius: 8px; padding: 12px 18px;
        display: none; margin-bottom: 14px;
        border-left: 4px solid #F7B731;
        align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .bulk-panel.visible { display: flex; }
    .bulk-count { color: #F7B731; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; }
    .bulk-info-text { color: rgba(255,255,255,0.55); font-size: 0.8rem; }
    .user-checkbox { width: 17px; height: 17px; cursor: pointer; accent-color: #009A44; }
    .select-all-checkbox { width: 17px; height: 17px; accent-color: #F7B731; cursor: pointer; }
    tr.row-selected td { background-color: rgba(0,154,68,0.08) !important; }
    .user-avatar {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; color: #fff; flex-shrink: 0;
    }
</style>
@endsection

@section('corps')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                </h4>
                <a href="{{ route('utilisateur.create') }}" class="btn btn-sm btn-primary">
                    <i class="fa fa-user-plus me-1"></i> Nouvel utilisateur
                </a>
            </div>
            <div class="card-body">

                {{-- ── Statistiques ──────────────────────────────────────────────────── --}}
                <div class="row g-3 mb-4">
                    <div class="col-xl-3 col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-header text-white text-center py-2" style="background:#009A44;">
                                <i class="fas fa-users me-1"></i> Total utilisateurs
                            </div>
                            <div class="card-body text-center py-3">
                                <div style="font-size:2rem;font-weight:800;color:#009A44;">{{ $users->total() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-header text-white text-center py-2" style="background:#28a745;">
                                <i class="fas fa-user-check me-1"></i> Actifs
                            </div>
                            <div class="card-body text-center py-3">
                                <div style="font-size:2rem;font-weight:800;color:#28a745;">{{ $allUsers->where('status', 1)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-header text-white text-center py-2" style="background:#DC241F;">
                                <i class="fas fa-user-times me-1"></i> Inactifs
                            </div>
                            <div class="card-body text-center py-3">
                                <div style="font-size:2rem;font-weight:800;color:#DC241F;">{{ $allUsers->where('status', 0)->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card overflow-hidden">
                            <div class="card-header text-white text-center py-2" style="background:#c89200;">
                                <i class="fas fa-shield-alt me-1"></i> 2FA activée
                            </div>
                            <div class="card-body text-center py-3">
                                <div style="font-size:2rem;font-weight:800;color:#c89200;">{{ $allUsers->where('google2fa_enabled', true)->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Filtres ───────────────────────────────────────────────────────── --}}
                <div class="mb-2 d-flex justify-content-end">
                    <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter me-1"></i> Filtres et Recherche
                        @if(request()->hasAny(['status','institution','fonction','search']))
                            <span class="badge bg-warning text-dark ms-1">actifs</span>
                        @endif
                    </button>
                </div>
                @php $filtersOpen = request()->hasAny(['status','institution','fonction','search']); @endphp
                <div class="collapse {{ $filtersOpen ? 'show' : '' }}" id="filtersCollapse">
                    <div class="card card-body mb-3">
                        <form method="GET" action="{{ route('utilisateur.index') }}" id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Statut</label>
                                    <select class="form-control" name="status" id="statusFilter">
                                        <option value="">Tous les statuts</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Actif</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactif</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Institution</label>
                                    <select class="form-control" name="institution" id="institutionFilter">
                                        <option value="">Toutes les institutions</option>
                                        @foreach($allUsers->map(fn($u) => $u->affectationActive()?->institution?->lib_institution)->filter()->unique() as $institution)
                                            <option value="{{ $institution }}" {{ request('institution') == $institution ? 'selected' : '' }}>{{ $institution }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Fonction</label>
                                    <select class="form-control" name="fonction" id="fonctionFilter">
                                        <option value="">Toutes les fonctions</option>
                                        @foreach($allUsers->map(fn($u) => $u->affectationActive()?->fonction?->lib_fonction)->filter()->unique() as $fonction)
                                            <option value="{{ $fonction }}" {{ request('fonction') == $fonction ? 'selected' : '' }}>{{ $fonction }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Recherche</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="searchInput"
                                               placeholder="Nom, email..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        <a href="{{ route('utilisateur.index') }}" class="btn btn-danger" title="Réinitialiser">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ── Panneau actions groupées (bulk 2FA) ──────────────────────────── --}}
                <div class="bulk-panel" id="bulkPanel">
                    <div class="bulk-count">
                        <i class="fas fa-check-square"></i>
                        <span id="selectedCount">0</span> utilisateur(s) sélectionné(s)
                    </div>
                    <div class="d-flex gap-2 flex-wrap align-items-center ms-auto">
                        <span class="bulk-info-text">Choisir une action :</span>
                        <button type="button" class="btn btn-sm btn-success" onclick="confirmBulk('enable')">
                            <i class="fas fa-shield-alt me-1"></i> Activer la 2FA
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulk('disable')">
                            <i class="fas fa-shield-virus me-1"></i> Désactiver la 2FA
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="clearSelection()">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                    </div>
                </div>

                {{-- ── Tableau ───────────────────────────────────────────────────────── --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead style="background:#009A44;color:#fff;">
                            <tr>
                                <th width="40" class="text-center" style="background:#009A44;color:#fff;">
                                    <input type="checkbox" class="select-all-checkbox" id="selectAll" title="Tout sélectionner">
                                </th>
                                <th width="45" class="text-center" style="background:#009A44;color:#fff;">#</th>
                                <th style="background:#009A44;color:#fff;">Agent</th>
                                <th style="background:#009A44;color:#fff;">Login</th>
                                <th style="background:#009A44;color:#fff;">Centre État Civil</th>
                                <th style="background:#009A44;color:#fff;">Fonction</th>
                                <th class="text-center" style="background:#009A44;color:#fff;">Statut</th>
                                <th class="text-center" style="background:#009A44;color:#fff;">2FA</th>
                                <th class="text-center" style="background:#009A44;color:#fff;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                        @foreach ($users as $user)
                            <tr class="user-row" data-id="{{ $user->code_user }}">
                                <td class="text-center">
                                    <input type="checkbox" class="user-checkbox" name="user_ids[]"
                                           value="{{ $user->code_user }}" onchange="updateBulkPanel()">
                                </td>
                                <td class="text-center"><strong>{{ $i++ }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($user->personne && $user->personne->signature)
                                            @php
                                                $signaturePath = $user->personne->signature;
                                                if (strpos($signaturePath, 'signature/') === 0) {
                                                    $signaturePath = str_replace('signature/', 'signatures/', $signaturePath);
                                                }
                                                if (strpos($signaturePath, 'signatures/') === 0 || strpos($signaturePath, 'storage/') === 0) {
                                                    $imageUrl = asset($signaturePath);
                                                } else {
                                                    $imageUrl = asset('storage/'.$signaturePath);
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="Avatar" class="user-avatar"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="user-avatar" style="display:none;background:{{ ($user->personne->sexe ?? '') == 'F' ? '#DC241F' : '#009A44' }};">
                                                <i class="fas fa-{{ ($user->personne->sexe ?? '') == 'F' ? 'female' : 'male' }}"></i>
                                            </div>
                                        @else
                                            <div class="user-avatar" style="background:{{ ($user->personne->sexe ?? '') == 'F' ? '#DC241F' : '#009A44' }};">
                                                <i class="fas fa-{{ ($user->personne->sexe ?? '') == 'F' ? 'female' : 'male' }}"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <strong style="font-size:0.875rem;">{{ $user->personne->nom ?? 'N/A' }} {{ $user->personne->prenom ?? 'N/A' }}</strong>
                                            @if($user->personne && $user->personne->telephone)
                                                <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $user->personne->telephone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><small>{{ $user->email }}</small></td>
                                <td>
                                    @php $lib_ins = $user->affectationActive()?->institution?->lib_institution; @endphp
                                    <small>{{ $lib_ins ?? '—' }}</small>
                                </td>
                                <td>
                                    @php $lib_fn = $user->affectationActive()?->fonction?->lib_fonction; @endphp
                                    <small>{{ $lib_fn ?? '—' }}</small>
                                </td>
                                <td class="text-center">
                                    @if($user->status == 1)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->hasTwoFactorEnabled())
                                        <span class="badge" style="background:#009A44;color:#fff;font-size:0.8rem;padding:5px 10px;">
                                            <i class="fas fa-shield-alt me-1"></i>Activée
                                        </span>
                                    @else
                                        <span class="badge" style="background:#e0e0e0;color:#555;font-size:0.8rem;padding:5px 10px;border:1px solid #ccc;">
                                            <i class="fas fa-shield-alt me-1"></i>Non
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <a href="{{ route('utilisateur.profile',$user->code_user) }}"
                                           class="btn btn-xs sharp btn-success shadow" title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('utilisateur.edit',$user->code_user) }}"
                                           class="btn btn-xs sharp btn-warning shadow" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('utilisateur.assigner.permission',$user->code_user) }}"
                                           class="btn btn-xs sharp btn-info shadow" title="Gérer les permissions">
                                            <i class="fas fa-key"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-xs sharp shadow {{ $user->status ? 'btn-secondary' : 'btn-success' }}"
                                                onclick="toggleUserStatus('{{ $user->code_user }}', {{ $user->status }})"
                                                title="{{ $user->status ? 'Désactiver le compte' : 'Activer le compte' }}">
                                            <i class="fas fa-{{ $user->status ? 'pause' : 'play' }}"></i>
                                        </button>
                                        <button type="button"
                                                class="btn btn-xs sharp btn-danger shadow"
                                                onclick="deleteUser('{{ $user->code_user }}', '{{ $user->personne->nom }} {{ $user->personne->prenom }}')"
                                                title="Supprimer l'utilisateur">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ── Pagination ────────────────────────────────────────────────────── --}}
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted">
                            Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }}
                            sur {{ $users->total() }} utilisateurs
                        </small>
                        <form method="GET" action="{{ route('utilisateur.index') }}" id="perPageForm" class="d-flex align-items-center gap-1">
                            @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <label class="mb-0"><small>Afficher :</small></label>
                            <select name="per_page" class="form-control form-control-sm" style="width:auto;" onchange="this.form.submit()">
                                <option value="10"  {{ request('per_page') == 10  ? 'selected' : '' }}>10</option>
                                <option value="15"  {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25"  {{ request('per_page') == 25  ? 'selected' : '' }}>25</option>
                                <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </form>
                    </div>
                    <div>{{ $users->links('pagination::bootstrap-4') }}</div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- Modal suppression --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Formulaire caché pour toggle statut --}}
<form id="toggleStatusForm" method="POST" action="" style="display:none;">
    @csrf
    @method('PATCH')
</form>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#statusFilter, #institutionFilter, #fonctionFilter').on('change', function() {
        $('#filterForm').submit();
    });
});

// ── Sélection en masse ────────────────────────────────────────────────────
function updateBulkPanel() {
    var checked = document.querySelectorAll('.user-checkbox:checked');
    var count   = checked.length;
    document.getElementById('selectedCount').textContent = count;

    var panel = document.getElementById('bulkPanel');
    if (count > 0) { panel.classList.add('visible'); }
    else           { panel.classList.remove('visible'); }

    document.querySelectorAll('.user-row').forEach(function(row) {
        var cb = row.querySelector('.user-checkbox');
        if (cb && cb.checked) { row.classList.add('row-selected'); }
        else                  { row.classList.remove('row-selected'); }
    });

    var total     = document.querySelectorAll('.user-checkbox').length;
    var selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked       = (count === total && total > 0);
        selectAll.indeterminate = (count > 0 && count < total);
    }
}

document.getElementById('selectAll').addEventListener('change', function() {
    var checked = this.checked;
    document.querySelectorAll('.user-checkbox').forEach(function(cb) { cb.checked = checked; });
    updateBulkPanel();
});

function clearSelection() {
    document.querySelectorAll('.user-checkbox').forEach(function(cb) { cb.checked = false; });
    document.getElementById('selectAll').checked       = false;
    document.getElementById('selectAll').indeterminate = false;
    document.getElementById('bulkPanel').classList.remove('visible');
    document.querySelectorAll('.user-row').forEach(function(r) { r.classList.remove('row-selected'); });
}

// ── Bulk 2FA ──────────────────────────────────────────────────────────────
function executeBulk2FA(action, userIds) {
    $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', true).css('opacity','0.6');
    $.ajax({
        url: '{{ route("utilisateur.bulk-2fa") }}',
        method: 'POST',
        data: { action: action, user_ids: userIds },
        traditional: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Opération réussie', html: response.message, icon: 'success',
                    confirmButtonColor: action === 'enable' ? '#009A44' : '#DC241F',
                    confirmButtonText: 'OK'
                }).then(function() { window.location.reload(); });
            } else {
                Swal.fire({ title: 'Erreur', html: response.message || 'Une erreur est survenue.', icon: 'error', confirmButtonColor: '#DC241F' });
                $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', false).css('opacity','1');
            }
        },
        error: function(xhr) {
            var msg;
            if (xhr.status === 419) { msg = 'Session expirée. Rechargez la page et réessayez.'; }
            else if (xhr.status === 422) {
                try { var j = $.parseJSON(xhr.responseText); msg = j.message || 'Données invalides.'; } catch(e) { msg = 'Données invalides (422).'; }
            } else {
                try { var j2 = $.parseJSON(xhr.responseText); msg = j2.message || 'Erreur serveur (' + xhr.status + ').'; } catch(e) { msg = 'Erreur serveur (' + xhr.status + ').'; }
            }
            Swal.fire({ title: 'Erreur', html: msg, icon: 'error', confirmButtonColor: '#DC241F' });
            $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', false).css('opacity','1');
        }
    });
}

function confirmBulk(action) {
    var userIds = $.map($('.user-checkbox:checked'), function(cb) { return $(cb).val(); });
    if (userIds.length === 0) return;

    var label    = action === 'enable'
        ? '<span style="color:#009A44;font-weight:700;">ACTIVER</span> la double authentification'
        : '<span style="color:#DC241F;font-weight:700;">DÉSACTIVER</span> la double authentification';
    var extraMsg = action === 'enable'
        ? '<br><br><small style="color:#856404;background:#fff3cd;padding:8px 12px;border-radius:6px;display:block;margin-top:8px;">📧 Chaque utilisateur recevra son QR code et ses codes de récupération par email.</small>'
        : '<br><br><small style="color:#721c24;background:#f8d7da;padding:8px 12px;border-radius:6px;display:block;margin-top:8px;">⚠️ La 2FA sera immédiatement désactivée pour les utilisateurs sélectionnés.</small>';

    Swal.fire({
        title: 'Confirmer l\'action',
        html: 'Vous allez ' + label + ' pour <strong>' + userIds.length + ' utilisateur(s)</strong>.' + extraMsg,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: action === 'enable' ? '✅ Activer la 2FA' : '🔓 Désactiver la 2FA',
        cancelButtonText: 'Annuler',
        confirmButtonColor: action === 'enable' ? '#009A44' : '#DC241F',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
    }).then(function(result) {
        var confirmed = (result === true) || (result && result.value === true) || (result && result.isConfirmed === true);
        if (!confirmed) return;
        executeBulk2FA(action, userIds);
    });
}

// ── Activer / Désactiver un compte ────────────────────────────────────────
function toggleUserStatus(userId, currentStatus) {
    var action = currentStatus ? 'désactiver' : 'activer';
    var label  = currentStatus ? 'Désactiver' : 'Activer';
    var color  = currentStatus ? '#DC241F' : '#009A44';

    Swal.fire({
        title: 'Confirmer l\'action',
        html: 'Êtes-vous sûr de vouloir <strong style="color:' + color + ';">' + action + '</strong> ce compte utilisateur ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: label,
        cancelButtonText: 'Annuler',
        confirmButtonColor: color,
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
    }).then(function(result) {
        var confirmed = (result === true) || (result && result.value === true) || (result && result.isConfirmed === true);
        if (!confirmed) return;
        var form = document.getElementById('toggleStatusForm');
        form.action = '/utilisateur/' + userId + '/toggle-status';
        form.submit();
    });
}

function deleteUser(userId, userName) {
    $('#userName').text(userName);
    $('#deleteForm').attr('action', '/utilisateur/' + userId);
    $('#deleteModal').modal('show');
}
</script>
@endsection
