@extends('layout.app')
@section('titre')
    Gestion des Utilisateurs
@endsection
@section('styles')
<style>
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .status-badge {
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }
    .table td {
        vertical-align: middle;
    }
    #filtersCollapse {
        transition: all 0.3s ease;
    }
    /* Styles de pagination personnalisés */
    .pagination .page-link {
        color: #000000 !important; /* Numéros en noir - forcé */
        background-color: #ffffff !important; /* Fond blanc */
        border: 1px solid #dee2e6 !important;
    }
    .pagination .page-link:hover {
        color: #fff !important;
        background-color: #28a745 !important; /* Vert au hover */
        border-color: #28a745 !important;
    }
    .pagination .page-item.active .page-link {
        background-color: #28a745 !important; /* Vert pour la page active */
        border-color: #28a745 !important;
        color: #fff !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #6c757d !important; /* Gris pour désactivé */
        background-color: #ffffff !important;
        cursor: not-allowed !important;
    }
</style>
@endsection

@section('corps')
    <!-- row -->
<div class="row">
        <div class="col-12">
        <div class="card">
            <div class="card-header">
                    <h4>Liste des Utilisateurs</h4>
                    <a href="{{ route('utilisateur.create') }}">
                        <button type="button" class="btn btn-info m-t-2 float-end text-white">
                            Nouvel utilisateur <i class="fa fa-plus-circle"></i>
                        </button>
                    </a>
                </div>
                <div class="card-body">

                    <!-- Statistiques -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary rounded-circle p-3">
                                                <i class="fas fa-users text-white fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Total Utilisateurs</h6>
                                            <h4 class="mb-0 text-primary">{{ $users->total() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-success rounded-circle p-3">
                                                <i class="fas fa-user-check text-white fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Utilisateurs Actifs</h6>
                                            <h4 class="mb-0 text-success">{{ $allUsers->where('status', 1)->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-warning rounded-circle p-3">
                                                <i class="fas fa-user-times text-white fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">Utilisateurs Inactifs</h6>
                                            <h4 class="mb-0 text-warning">{{ $allUsers->where('status', 0)->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-0 shadow-sm">
                    <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="bg-info rounded-circle p-3">
                                                <i class="fas fa-shield-alt text-white fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="text-muted mb-1">2FA Activée</h6>
                                            <h4 class="mb-0 text-info">{{ $allUsers->where('google2fa_enabled', true)->count() }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres Collapsible -->
                    <div class="mb-3 text-end">
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                            <i class="fas fa-filter"></i> Filtres et Recherche
                        </button>
                    </div>

                    <div class="collapse" id="filtersCollapse">
                        <div class="card card-body mb-4">
                            <form method="GET" action="{{ route('utilisateur.index') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Statut</label>
                                        <select class="form-select" name="status" id="statusFilter">
                                            <option value="">Tous les statuts</option>
                                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Actif</option>
                                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactif</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Institution</label>
                                        <select class="form-select" name="institution" id="institutionFilter">
                                            <option value="">Toutes les institutions</option>
                                            @foreach($allUsers->map(fn($u) => $u->affectationActive()?->institution?->lib_institution)->filter()->unique() as $institution)
                                                <option value="{{ $institution }}" {{ request('institution') == $institution ? 'selected' : '' }}>{{ $institution }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Fonction</label>
                                        <select class="form-select" name="fonction" id="fonctionFilter">
                                            <option value="">Toutes les fonctions</option>
                                            @foreach($allUsers->map(fn($u) => $u->affectationActive()?->fonction?->lib_fonction)->filter()->unique() as $fonction)
                                                <option value="{{ $fonction }}" {{ request('fonction') == $fonction ? 'selected' : '' }}>{{ $fonction }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Recherche</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search" id="searchInput" placeholder="Nom, email..." value="{{ request('search') }}">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <a href="{{ route('utilisateur.index') }}" class="btn btn-danger" title="Réinitialiser">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table -->
                        <div class="table-responsive">
                        <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                    <th width="50"><strong>#</strong></th>
                                    <th><strong>Agent</strong></th>
                                    <th><strong>Login</strong></th>
                                    <th><strong>Centre État Civil</strong></th>
                                    <th><strong>Fonction</strong></th>
                                    <th><strong>Statut</strong></th>
                                    <th><strong>Double Auth.</strong></th>
                                    <th><strong>Actions</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                                    @foreach ($users as $user)
                                        <tr>
                                        <td><strong>{{ $i++ }}</strong></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->personne && $user->personne->signature)
                                                    @php
                                                        // Gestion du chemin de la signature
                                                        $signaturePath = $user->personne->signature;
                                                        // Remplacer 'signature/' par 'signatures/' si nécessaire
                                                        if (strpos($signaturePath, 'signature/') === 0) {
                                                            $signaturePath = str_replace('signature/', 'signatures/', $signaturePath);
                                                        }
                                                        // Construire l'URL
                                                        if (strpos($signaturePath, 'signatures/') === 0 || strpos($signaturePath, 'storage/') === 0) {
                                                            $imageUrl = asset($signaturePath);
                                                        } else {
                                                            $imageUrl = asset('storage/'.$signaturePath);
                                                        }
                                                    @endphp
                                                    <img src="{{ $imageUrl }}" alt="Avatar" class="user-avatar me-2" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    @if($user->personne && $user->personne->sexe == 'F')
                                                        <div class="user-avatar d-flex align-items-center justify-content-center text-white me-2" style="display: none; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                            <i class="fas fa-female"></i>
                                                        </div>
                                                    @else
                                                        <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white me-2" style="display: none;">
                                                            <i class="fas fa-male"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    @if($user->personne && $user->personne->sexe == 'F')
                                                        <div class="user-avatar d-flex align-items-center justify-content-center text-white me-2" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                                            <i class="fas fa-female"></i>
                                                        </div>
                                                    @else
                                                        <div class="user-avatar bg-primary d-flex align-items-center justify-content-center text-white me-2">
                                                            <i class="fas fa-male"></i>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $user->personne->nom ?? 'N/A' }} {{ $user->personne->prenom ?? 'N/A' }}</h6>
                                                    @if($user->personne && $user->personne->telephone)
                                                        <span class="text-muted"><small><i class="fas fa-phone"></i> {{ $user->personne->telephone }}</small></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $user->email }}</strong>
                                            {{-- @if($user->pseudo && !empty($user->pseudo))
                                                <br><small class="text-muted">@{{ $user->pseudo }}</small>
                                            @endif --}}
                                        </td>
                                        <td>{{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}</td>
                                        <td>{{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non défini" }}</td>
                                        <td>
                                            @if ($user->status == 1)
                                                <span class="badge light badge-success">
                                                    <i class="fa fa-circle text-success me-1"></i>Actif
                                                </span>
                                            @else
                                                <span class="badge light badge-danger">
                                                    <i class="fa fa-circle text-danger me-1"></i>Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->hasTwoFactorEnabled())
                                                <span class="badge light badge-info">
                                                    <i class="fa fa-shield-alt text-info me-1"></i>Activée
                                                </span>
                                            @else
                                                <span class="badge light badge-secondary">
                                                    <i class="fa fa-shield-alt text-secondary me-1"></i>Désactivée
                                                </span>
                                                @endif
                                            </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('utilisateur.profile',$user->code_user) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Voir le profil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('utilisateur.edit',$user->code_user) }}" class="btn btn-warning shadow btn-xs sharp me-1" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('utilisateur.assigner.permission',$user->code_user) }}" class="btn btn-info shadow btn-xs sharp me-1" title="Permissions">
                                                    <i class="fas fa-key"></i>
                                                </a>
                                                <button type="button" class="btn btn-{{ $user->status ? 'secondary' : 'success' }} shadow btn-xs sharp me-1" onclick="toggleUserStatus('{{ $user->code_user }}', {{ $user->status }})" title="{{ $user->status ? 'Désactiver' : 'Activer' }}">
                                                    <i class="fas fa-{{ $user->status ? 'pause' : 'play' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger shadow btn-xs sharp" onclick="deleteUser('{{ $user->code_user }}', '{{ $user->personne->nom }} {{ $user->personne->prenom }}')" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-3">
                                <small>Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs</small>
                            </span>
                            <form method="GET" action="{{ route('utilisateur.index') }}" id="perPageForm" class="d-flex align-items-center">
                                @foreach(request()->except(['page', 'per_page']) as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <label class="me-2 mb-0"><small>Afficher :</small></label>
                                <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                        </div>
                        <div>
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmer la suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer l'utilisateur <strong id="userName"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit du formulaire lors du changement des filtres
    $('#statusFilter, #institutionFilter, #fonctionFilter').on('change', function() {
        $('#filterForm').submit();
    });
});

// Fonctions d'action
function toggleUserStatus(userId, currentStatus) {
    const action = currentStatus ? 'désactiver' : 'activer';

    // Créer un modal de confirmation personnalisé
    const modalHtml = `
        <div class="modal fade" id="confirmToggleModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirmer l'action</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir <strong>${action}</strong> cet utilisateur ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-warning" onclick="executeToggle('${userId}', ${currentStatus})">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Supprimer l'ancien modal s'il existe
    const existingModal = document.getElementById('confirmToggleModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Ajouter le nouveau modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('confirmToggleModal'));
    modal.show();
}

function executeToggle(userId, currentStatus) {
    const action = currentStatus ? 'désactiver' : 'activer';

    // Fermer le modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmToggleModal'));
    modal.hide();

    // Afficher le message avec flashAlert
    flashAlert('info', 'Fonctionnalité de ' + action + ' en cours de développement');
}

function deleteUser(userId, userName) {
    $('#userName').text(userName);
    $('#deleteForm').attr('action', '/utilisateur/' + userId);
    $('#deleteModal').modal('show');
}

// Flash alert function
function flashAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      type === 'warning' ? 'alert-warning' : 'alert-info';
    const icon = type === 'success' ? 'fa-check-circle' :
                 type === 'error' ? 'fa-exclamation-circle' :
                 type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas ${icon}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', alertHtml);

    setTimeout(() => {
        const alert = document.querySelector('.alert:last-of-type');
        if (alert) {
            alert.remove();
        }
    }, 3000);
}
</script>
@endsection
