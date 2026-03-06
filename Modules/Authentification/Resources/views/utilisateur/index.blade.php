@extends('layout.app')
@section('titre')
    Gestion des Utilisateurs
@endsection
@section('styles')
<style>
    :root {
        --congo-green:  #009A44;
        --congo-yellow: #F7B731;
        --congo-red:    #DC241F;
        --congo-green-dark: #007A35;
        --congo-green-light: #e8f5ee;
    }
    .page-flag-banner {
        height: 6px;
        background: linear-gradient(to right,
            var(--congo-green) 0%, var(--congo-green) 33.3%,
            var(--congo-yellow) 33.3%, var(--congo-yellow) 66.6%,
            var(--congo-red) 66.6%, var(--congo-red) 100%);
        border-radius: 4px 4px 0 0; margin-bottom: 12px;
    }
    .stat-card {
        border: none; border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 6px 18px rgba(0,0,0,0.12); }
    .stat-card .stat-icon {
        width: 52px; height: 52px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-value { font-size: 1.8rem; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.4px; }

    /* Table */
    .table thead th {
        background: linear-gradient(135deg, var(--congo-green) 0%, var(--congo-green-dark) 100%) !important;
        color: #fff !important; font-size: 0.77rem; text-transform: uppercase;
        letter-spacing: 0.4px; border: none !important; padding: 12px 14px;
        font-weight: 700;
    }
    .table tbody tr { transition: background 0.15s; }
    .table tbody tr:hover { background-color: var(--congo-green-light); }
    .table td { padding: 10px 12px; border-color: #f0f2f5; vertical-align: middle; font-size: 0.875rem; }
    .table tbody tr:nth-child(even) td { background-color: #fafbfc; }
    .table tbody tr:hover td { background-color: var(--congo-green-light) !important; }

    .user-avatar {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: #fff !important; flex-shrink: 0;
    }
    .user-name { font-weight: 700; font-size: 0.875rem; color: #2d3748; display: block; }
    .user-phone { font-size: 0.775rem; color: #718096; }

    /* Forcer la visibilité du texte dans les cellules */
    .table td, .table td * { color: inherit; }
    .table td strong { color: #2d3748 !important; font-size: 0.875rem; }

    /* Filtres */
    .filters-panel {
        background: linear-gradient(135deg, #f8fffe 0%, #f0fdf8 100%);
        border: 1px solid rgba(0,154,68,0.2);
        border-radius: 8px; padding: 16px 20px; margin-bottom: 16px;
    }
    .filters-panel .form-label { font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }
    .filters-panel .form-control:focus,
    .filters-panel .form-select:focus {
        border-color: var(--congo-green);
        box-shadow: 0 0 0 3px rgba(0,154,68,0.12);
    }

    /* Forcer couleur email en noir dans les cellules */
    .table td strong, .table td a, .email-cell { color: #2d3748 !important; }

    /* Pagination */
    .pagination .page-link {
        color: var(--congo-green) !important;
        border-color: #dee2e6 !important;
    }
    .pagination .page-link:hover {
        background-color: var(--congo-green) !important;
        color: #fff !important; border-color: var(--congo-green) !important;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--congo-green) !important;
        border-color: var(--congo-green) !important; color: #fff !important;
    }
    .pagination .page-item.disabled .page-link { color: #6c757d !important; }

    /* Actions buttons */
    .action-btns .btn { border-radius: 5px; padding: 4px 8px; font-size: 0.78rem; }

    /* ===== SÉLECTION EN MASSE & 2FA ===== */
    .bulk-panel {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        border-radius: 10px; padding: 14px 20px;
        display: none; margin-bottom: 16px;
        border-left: 5px solid var(--congo-yellow);
        align-items: center; gap: 12px; flex-wrap: wrap;
    }
    .bulk-panel.visible { display: flex; animation: slideDown 0.25s ease; }
    @keyframes slideDown { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
    .bulk-count {
        color: var(--congo-yellow); font-weight: 700; font-size: 0.9rem;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-bulk-enable {
        background: linear-gradient(135deg, var(--congo-green), var(--congo-green-dark));
        color: #fff; border: none; border-radius: 7px;
        padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-bulk-enable:hover { opacity:0.9; transform:translateY(-1px); color:#fff; }
    .btn-bulk-disable {
        background: linear-gradient(135deg, #DC241F, #a81a15);
        color: #fff; border: none; border-radius: 7px;
        padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
        transition: all 0.2s; cursor: pointer;
    }
    .btn-bulk-disable:hover { opacity:0.9; transform:translateY(-1px); color:#fff; }
    .btn-bulk-cancel {
        color: rgba(255,255,255,0.6); border: 1px solid rgba(255,255,255,0.2);
        background: transparent; border-radius: 7px;
        padding: 7px 16px; font-size: 0.83rem; cursor: pointer;
    }
    .btn-bulk-cancel:hover { color:#fff; border-color:rgba(255,255,255,0.5); }
    .user-checkbox {
        width: 17px; height: 17px; cursor: pointer;
        accent-color: var(--congo-green);
    }
    tr.row-selected { background-color: rgba(0,154,68,0.08) !important; }
    .select-all-checkbox { width: 17px; height: 17px; accent-color: var(--congo-yellow); cursor: pointer; }
    .bulk-info-text { color: rgba(255,255,255,0.55); font-size: 0.8rem; }
</style>
@endsection

@section('corps')
<div class="row">
    <div class="col-12">

        <div class="page-flag-banner"></div>

        <div class="card border-0 shadow-sm" style="border-radius:10px;overflow:hidden;">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
                 style="background:linear-gradient(135deg,#009A44 0%,#007A35 100%);color:white;padding:16px 22px;">
                <h4 class="mb-0" style="color:#fff;font-weight:700;">
                    <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
                </h4>
                <a href="{{ route('utilisateur.create') }}" class="btn btn-sm px-4 fw-bold"
                   style="background:var(--congo-yellow,#F7B731);color:#333;border:none;border-radius:7px;">
                    <i class="fa fa-user-plus me-1"></i> Nouvel utilisateur
                </a>
            </div>
                <div class="card-body">

                    <!-- Statistiques -->
                    <div class="row g-3 mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card card">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon" style="background:linear-gradient(135deg,#009A44,#007A35);">
                                        <i class="fas fa-users text-white"></i>
                                    </div>
                                    <div>
                                        <div class="stat-value" style="color:#009A44;">{{ $users->total() }}</div>
                                        <div class="stat-label text-muted">Total utilisateurs</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:linear-gradient(to right,#009A44,#007A35);"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card card">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon" style="background:linear-gradient(135deg,#28a745,#1e7e34);">
                                        <i class="fas fa-user-check text-white"></i>
                                    </div>
                                    <div>
                                        <div class="stat-value text-success">{{ $allUsers->where('status', 1)->count() }}</div>
                                        <div class="stat-label text-muted">Actifs</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:linear-gradient(to right,#28a745,#1e7e34);"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card card">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon" style="background:linear-gradient(135deg,#DC241F,#a81a15);">
                                        <i class="fas fa-user-times text-white"></i>
                                    </div>
                                    <div>
                                        <div class="stat-value" style="color:#DC241F;">{{ $allUsers->where('status', 0)->count() }}</div>
                                        <div class="stat-label text-muted">Inactifs</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:linear-gradient(to right,#DC241F,#a81a15);"></div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="stat-card card">
                                <div class="card-body d-flex align-items-center gap-3 p-3">
                                    <div class="stat-icon" style="background:linear-gradient(135deg,#F7B731,#c89200);">
                                        <i class="fas fa-shield-alt text-white"></i>
                                    </div>
                                    <div>
                                        <div class="stat-value" style="color:#c89200;">{{ $allUsers->where('google2fa_enabled', true)->count() }}</div>
                                        <div class="stat-label text-muted">2FA activée</div>
                                    </div>
                                </div>
                                <div style="height:4px;background:linear-gradient(to right,#F7B731,#c89200);"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtres Collapsible -->
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <small class="text-muted">
                            @if(request()->hasAny(['status','institution','fonction','search']))
                                <span class="badge bg-warning text-dark me-2">
                                    <i class="fas fa-filter me-1"></i>Filtres actifs
                                </span>
                            @endif
                        </small>
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                            <i class="fas fa-filter me-1"></i> Filtres et Recherche
                            <i class="fas fa-chevron-down ms-1"></i>
                        </button>
                    </div>

                    @php $filtersOpen = request()->hasAny(['status','institution','fonction','search']); @endphp
                    <div class="collapse {{ $filtersOpen ? 'show' : '' }}" id="filtersCollapse">
                        <div class="filters-panel mb-4">
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

                    {{-- ===== ZONE BULK 2FA (soumission via $.ajax, pas de <form>) ===== --}}

                    {{-- Panneau d'actions groupées (affiché dès qu'une case est cochée) --}}
                    <div class="bulk-panel" id="bulkPanel">
                        <div class="bulk-count">
                            <i class="fas fa-check-square"></i>
                            <span id="selectedCount">0</span> utilisateur(s) sélectionné(s)
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center ms-auto">
                            <span class="bulk-info-text">Choisir une action :</span>
                            <button type="button" class="btn-bulk-enable" onclick="confirmBulk('enable')">
                                <i class="fas fa-shield-alt me-1"></i> Activer la 2FA
                            </button>
                            <button type="button" class="btn-bulk-disable" onclick="confirmBulk('disable')">
                                <i class="fas fa-shield-virus me-1"></i> Désactiver la 2FA
                            </button>
                            <button type="button" class="btn-bulk-cancel" onclick="clearSelection()">
                                <i class="fas fa-times me-1"></i> Annuler
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                        <div class="table-responsive">
                        <table class="table table-hover align-middle" style="border-radius:8px;overflow:hidden;">
                                <thead>
                                    <tr>
                                    <th width="40" class="text-center">
                                        <input type="checkbox" class="select-all-checkbox" id="selectAll" title="Tout sélectionner">
                                    </th>
                                    <th width="45" class="text-center">#</th>
                                    <th>Agent</th>
                                    <th>Login</th>
                                    <th>Centre État Civil</th>
                                    <th>Fonction</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">2FA</th>
                                    <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                                    @foreach ($users as $user)
                                        <tr class="user-row" data-id="{{ $user->code_user }}">
                                        <td class="text-center">
                                            <input type="checkbox"
                                                   class="user-checkbox"
                                                   name="user_ids[]"
                                                   value="{{ $user->code_user }}"
                                                   onchange="updateBulkPanel()">
                                        </td>
                                        <td class="text-center"><strong>{{ $i++ }}</strong></td>
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
                                                        <div class="user-avatar me-2" style="display:none;background:linear-gradient(135deg,#DC241F,#a81a15);">
                                                            <i class="fas fa-female"></i>
                                                        </div>
                                                    @else
                                                        <div class="user-avatar me-2" style="display:none;background:linear-gradient(135deg,#009A44,#007A35);">
                                                            <i class="fas fa-male"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    @if($user->personne && $user->personne->sexe == 'F')
                                                        <div class="user-avatar me-2" style="background:linear-gradient(135deg,#DC241F,#a81a15);">
                                                            <i class="fas fa-female"></i>
                                                        </div>
                                                    @else
                                                        <div class="user-avatar me-2" style="background:linear-gradient(135deg,#009A44,#007A35);">
                                                            <i class="fas fa-male"></i>
                                                        </div>
                                                    @endif
                                                @endif
                                                <div>
                                                    <span class="user-name">{{ $user->personne->nom ?? 'N/A' }} {{ $user->personne->prenom ?? 'N/A' }}</span>
                                                    @if($user->personne && $user->personne->telephone)
                                                        <span class="text-muted"><small><i class="fas fa-phone"></i> {{ $user->personne->telephone }}</small></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong style="color:#2d3748 !important;font-size:0.875rem;">{{ $user->email }}</strong>
                                            {{-- @if($user->pseudo && !empty($user->pseudo))
                                                <br><small class="text-muted">@{{ $user->pseudo }}</small>
                                            @endif --}}
                                        </td>
                                        <td>
                                            @php $lib_ins = $user->affectationActive()?->institution?->lib_institution; @endphp
                                            @if($lib_ins)
                                                <small style="color:#2d3748;font-weight:500;">{{ $lib_ins }}</small>
                                            @else
                                                <small style="color:#a0aec0;font-style:italic;">Non affecté</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php $lib_fn = $user->affectationActive()?->fonction?->lib_fonction; @endphp
                                            @if($lib_fn)
                                                <small style="color:#2d3748;">{{ $lib_fn }}</small>
                                            @else
                                                <small style="color:#a0aec0;font-style:italic;">Non défini</small>
                                            @endif
                                        </td>

                                        {{-- ===== STATUT ===== --}}
                                        <td class="text-center">
                                            @if($user->status == 1)
                                                <span style="display:inline-flex;align-items:center;gap:5px;background:#009A44;color:#fff;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;">
                                                    <span style="width:7px;height:7px;border-radius:50%;background:#6effa0;flex-shrink:0;"></span>
                                                    Actif
                                                </span>
                                            @else
                                                <span style="display:inline-flex;align-items:center;gap:5px;background:#DC241F;color:#fff;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;">
                                                    <span style="width:7px;height:7px;border-radius:50%;background:#ffb3b3;flex-shrink:0;"></span>
                                                    Inactif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ===== 2FA ===== --}}
                                        <td class="text-center">
                                            @if($user->hasTwoFactorEnabled())
                                                <span style="display:inline-flex;align-items:center;gap:5px;background:#0d6efd;color:#fff;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;"
                                                      title="Double authentification activée">
                                                    <i class="fas fa-shield-alt" style="font-size:0.7rem;"></i>
                                                    Activée
                                                </span>
                                            @else
                                                <span style="display:inline-flex;align-items:center;gap:5px;background:#6c757d;color:#fff;font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;"
                                                      title="Double authentification non activée">
                                                    <i class="fas fa-shield-alt" style="font-size:0.7rem;opacity:0.7;"></i>
                                                    Non
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ===== ACTIONS ===== --}}
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1 flex-wrap">
                                                {{-- Profil --}}
                                                <a href="{{ route('utilisateur.profile',$user->code_user) }}"
                                                   class="btn btn-xs sharp shadow"
                                                   style="background:#009A44;color:#fff;border:none;border-radius:5px;padding:5px 7px;"
                                                   title="Voir le profil">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                {{-- Modifier --}}
                                                <a href="{{ route('utilisateur.edit',$user->code_user) }}"
                                                   class="btn btn-xs sharp shadow"
                                                   style="background:#F7B731;color:#333;border:none;border-radius:5px;padding:5px 7px;"
                                                   title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- Permissions --}}
                                                <a href="{{ route('utilisateur.assigner.permission',$user->code_user) }}"
                                                   class="btn btn-xs sharp shadow"
                                                   style="background:#17a2b8;color:#fff;border:none;border-radius:5px;padding:5px 7px;"
                                                   title="Gérer les permissions">
                                                    <i class="fas fa-key"></i>
                                                </a>
                                                {{-- Activer / Désactiver le compte --}}
                                                <button type="button"
                                                        class="btn btn-xs sharp shadow"
                                                        style="background:{{ $user->status ? '#495057' : '#28a745' }};color:#fff;border:none;border-radius:5px;padding:5px 7px;"
                                                        onclick="toggleUserStatus('{{ $user->code_user }}', {{ $user->status }})"
                                                        title="{{ $user->status ? 'Désactiver le compte' : 'Activer le compte' }}">
                                                    <i class="fas fa-{{ $user->status ? 'pause' : 'play' }}"></i>
                                                </button>
                                                {{-- Supprimer --}}
                                                <button type="button"
                                                        class="btn btn-xs sharp shadow"
                                                        style="background:#DC241F;color:#fff;border:none;border-radius:5px;padding:5px 7px;"
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
                    {{-- fin zone bulk 2FA --}}

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
{{-- Formulaire caché pour toggle statut (soumission classique) --}}
<form id="toggleStatusForm" method="POST" action="" style="display:none;">
    @csrf
    @method('PATCH')
</form>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Auto-submit du formulaire lors du changement des filtres
    $('#statusFilter, #institutionFilter, #fonctionFilter').on('change', function() {
        $('#filterForm').submit();
    });
});

// ===== GESTION SÉLECTION EN MASSE =====

function updateBulkPanel() {
    var checked = document.querySelectorAll('.user-checkbox:checked');
    var count = checked.length;
    document.getElementById('selectedCount').textContent = count;

    var panel = document.getElementById('bulkPanel');
    if (count > 0) {
        panel.classList.add('visible');
    } else {
        panel.classList.remove('visible');
    }

    // Highlight des lignes sélectionnées
    document.querySelectorAll('.user-row').forEach(function(row) {
        var cb = row.querySelector('.user-checkbox');
        if (cb && cb.checked) {
            row.classList.add('row-selected');
        } else {
            row.classList.remove('row-selected');
        }
    });

    // État du "tout sélectionner"
    var total = document.querySelectorAll('.user-checkbox').length;
    var selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.checked = (count === total && total > 0);
        selectAll.indeterminate = (count > 0 && count < total);
    }
}

function executeBulk2FA(action, userIds) {
    // Désactiver les boutons pendant le traitement
    $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', true).css('opacity','0.6');

    $.ajax({
        url: '{{ route("utilisateur.bulk-2fa") }}',
        method: 'POST',
        data: {
            action: action,
            user_ids: userIds
        },
        traditional: false,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Succès : afficher résultat puis recharger
                Swal.fire({
                    title: 'Opération réussie',
                    html: response.message,
                    icon: 'success',
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
            if (xhr.status === 419) {
                msg = 'Session expirée. Rechargez la page et réessayez.';
            } else if (xhr.status === 422) {
                try { var j = $.parseJSON(xhr.responseText); msg = j.message || 'Données invalides.'; } catch(e) { msg = 'Données invalides (422).'; }
            } else {
                try { var j2 = $.parseJSON(xhr.responseText); msg = j2.message || 'Erreur serveur (' + xhr.status + ').'; } catch(e) { msg = 'Erreur serveur (' + xhr.status + '). Consultez sifec.log.'; }
            }
            Swal.fire({ title: 'Erreur', html: msg, icon: 'error', confirmButtonColor: '#DC241F' });
            $('.btn-bulk-enable, .btn-bulk-disable').prop('disabled', false).css('opacity','1');
        }
    });
}

// Tout sélectionner / désélectionner
document.getElementById('selectAll').addEventListener('change', function() {
    var checked = this.checked;
    document.querySelectorAll('.user-checkbox').forEach(function(cb) {
        cb.checked = checked;
    });
    updateBulkPanel();
});

function clearSelection() {
    document.querySelectorAll('.user-checkbox').forEach(function(cb) { cb.checked = false; });
    document.getElementById('selectAll').checked = false;
    document.getElementById('selectAll').indeterminate = false;
    document.getElementById('bulkPanel').classList.remove('visible');
    document.querySelectorAll('.user-row').forEach(function(r) { r.classList.remove('row-selected'); });
}

function confirmBulk(action) {
    var userIds = $.map($('.user-checkbox:checked'), function(cb){ return $(cb).val(); });
    if (userIds.length === 0) return;

    var label = action === 'enable'
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
        // Compatible ancienne API (result = true) ET nouvelle API (result.isConfirmed)
        var confirmed = (result === true) || (result && result.value === true) || (result && result.isConfirmed === true);
        if (!confirmed) return;

        executeBulk2FA(action, userIds);
    });
}

// ===== ACTIVER / DÉSACTIVER UN COMPTE =====
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

        // Soumission classique par formulaire
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
