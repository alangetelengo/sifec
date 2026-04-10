@extends('layout.app')

@section('titre')
    Permissions utilisateur
@endsection

@php
    $nomComplet = trim(($user->personne->nom ?? '') . ' ' . ($user->personne->prenom ?? ''));
    if ($nomComplet === '') {
        $nomComplet = $user->email ?? 'Utilisateur';
    }
    $aff = $user->affectationActive();
    $selectedTotal = count($userPermissionCodes);
@endphp

@section('styles')
<style>
    .page-ap-sifec {
        --ap-ink: #1a2e26;
        --ap-ink-muted: #5c6d66;
        --ap-green: #0f5132;
        --ap-green-soft: #e8f0eb;
        --ap-green-mid: #1b6f4a;
        --ap-cream: #fafaf8;
        --ap-paper: #ffffff;
        --ap-line: #e2e8e4;
        --ap-gold: #9a7b2c;
        --ap-gold-soft: #f5f0e6;
        --ap-shadow: 0 1px 3px rgba(26, 46, 38, 0.06);
        --ap-shadow-lg: 0 12px 40px rgba(26, 46, 38, 0.08);
        --ap-radius: 14px;
        --ap-radius-sm: 10px;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        margin: -0.35rem -0.5rem 0;
        padding: 0.5rem 0.5rem 2.5rem;
        min-height: calc(100vh - 100px);
        background: linear-gradient(180deg, var(--ap-cream) 0%, #eef1ee 100%);
    }

    .page-ap-sifec .ap-breadcrumb {
        font-size: 0.875rem;
        margin-bottom: 1rem;
        background: var(--ap-paper);
        border: 1px solid var(--ap-line);
        border-radius: var(--ap-radius-sm);
        padding: 0.65rem 1.15rem;
        box-shadow: var(--ap-shadow);
    }
    .page-ap-sifec .ap-breadcrumb .breadcrumb { margin-bottom: 0; }
    .page-ap-sifec .ap-breadcrumb .breadcrumb-item { color: #475569 !important; }
    .page-ap-sifec .ap-breadcrumb .breadcrumb-item a {
        color: var(--ap-green-mid) !important;
        font-weight: 600;
        text-decoration: none;
    }
    .page-ap-sifec .ap-breadcrumb .breadcrumb-item a:hover {
        color: var(--ap-green) !important;
        text-decoration: underline;
    }
    .page-ap-sifec .ap-breadcrumb .breadcrumb-item.active {
        color: var(--ap-ink) !important;
        font-weight: 700;
    }
    .page-ap-sifec .ap-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        color: #94a3b8 !important;
    }

    .page-ap-sifec .ap-shell {
        max-width: 1040px;
        margin: 0 auto;
    }

    .page-ap-sifec .ap-hero {
        position: relative;
        border-radius: var(--ap-radius);
        padding: 1.5rem 1.5rem 1.35rem;
        margin-bottom: 1.25rem;
        overflow: hidden;
        color: #fff;
        background-color: #009E49;
        background-image: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%);
        box-shadow: 0 8px 32px rgba(0, 107, 49, 0.22);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .page-ap-sifec .ap-hero::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        top: -60px;
        right: -40px;
        pointer-events: none;
    }
    .page-ap-sifec .ap-hero-inner { position: relative; z-index: 1; }
    .page-ap-sifec .ap-hero h1 {
        font-size: 1.35rem;
        font-weight: 600;
        letter-spacing: -0.02em;
        margin: 0 0 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        flex-wrap: wrap;
    }
    .page-ap-sifec .ap-hero-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }
    .page-ap-sifec .ap-hero-meta {
        font-size: 0.875rem;
        opacity: 0.95;
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem 1.25rem;
        margin-bottom: 1rem;
    }
    .page-ap-sifec .ap-hero-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .page-ap-sifec .ap-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .page-ap-sifec .ap-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.85rem;
        background: rgba(255, 255, 255, 0.16);
        border-radius: 999px;
        font-size: 0.8125rem;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .page-ap-sifec .ap-chip strong { font-weight: 800; }

    .page-ap-sifec .btn-ap-ghost {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff !important;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: var(--ap-radius-sm);
        text-decoration: none !important;
        transition: background 0.15s, border-color 0.15s;
    }
    .page-ap-sifec .btn-ap-ghost:hover {
        background: rgba(255, 255, 255, 0.24);
        color: #fff !important;
    }

    .page-ap-sifec .ap-toolbar {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding: 1rem 1.15rem;
        background: var(--ap-paper);
        border: 1px solid var(--ap-line);
        border-radius: var(--ap-radius-sm);
        box-shadow: var(--ap-shadow);
    }
    .page-ap-sifec .ap-toolbar-search {
        flex: 1 1 220px;
        min-width: 200px;
    }
    .page-ap-sifec .ap-toolbar-search .form-control {
        border-radius: 10px;
        border-color: var(--ap-line);
        padding-left: 2.5rem;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: 0.75rem 50%;
        background-size: 1rem;
    }
    .page-ap-sifec .ap-toolbar-search .form-control:focus {
        border-color: var(--ap-green-mid);
        box-shadow: 0 0 0 3px rgba(27, 111, 74, 0.12);
    }
    .page-ap-sifec .ap-toolbar-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.4rem;
    }
    .page-ap-sifec .ap-tbtn {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        border: 1px solid var(--ap-line);
        background: var(--ap-paper);
        color: var(--ap-ink) !important;
        white-space: nowrap;
    }
    .page-ap-sifec .ap-tbtn:hover {
        background: var(--ap-green-soft);
        border-color: rgba(15, 81, 50, 0.2);
        color: var(--ap-green) !important;
    }

    .page-ap-sifec .ap-info {
        font-size: 0.8125rem;
        color: var(--ap-ink-muted);
        padding: 0.85rem 1.1rem;
        background: linear-gradient(135deg, var(--ap-green-soft) 0%, #f0f6f2 100%);
        border: 1px solid rgba(15, 81, 50, 0.12);
        border-radius: var(--ap-radius-sm);
        margin-bottom: 1.25rem;
        display: flex;
        gap: 0.65rem;
        align-items: flex-start;
    }
    .page-ap-sifec .ap-info i {
        color: var(--ap-green-mid);
        margin-top: 0.15rem;
        flex-shrink: 0;
    }

    .page-ap-sifec .perm-accordion .accordion-item {
        border: 1px solid var(--ap-line);
        border-radius: var(--ap-radius-sm) !important;
        overflow: hidden;
        margin-bottom: 0.85rem;
        box-shadow: var(--ap-shadow);
        background: var(--ap-paper);
    }
    .page-ap-sifec .perm-accordion .accordion-button {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ap-ink);
        padding: 1rem 1.15rem;
        background: linear-gradient(180deg, #fafcfb 0%, #f4f7f5 100%);
        box-shadow: none;
    }
    .page-ap-sifec .perm-accordion .accordion-button:not(.collapsed) {
        background: linear-gradient(180deg, var(--ap-green-soft) 0%, #e0ebe4 100%);
        color: var(--ap-green);
        box-shadow: none;
    }
    .page-ap-sifec .perm-accordion .accordion-button::after {
        filter: opacity(0.55);
    }
    .page-ap-sifec .perm-acc-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.3em 0.65em;
        border-radius: 999px;
        background: var(--ap-gold-soft);
        color: #6b5420;
        margin-left: 0.5rem;
        vertical-align: middle;
    }
    .page-ap-sifec .perm-acc-badge--ok {
        background: var(--ap-green-soft);
        color: var(--ap-green);
    }

    .page-ap-sifec .perm-acc-body {
        padding: 1rem 1.15rem 1.15rem;
        background: #fcfdfc;
        border-top: 1px solid var(--ap-line);
    }
    .page-ap-sifec .perm-mod-toolbar {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 0.35rem;
        margin-bottom: 0.85rem;
    }
    .page-ap-sifec .perm-mod-toolbar .btn {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.35rem 0.65rem;
        border-radius: 8px;
    }

    .page-ap-sifec .perm-parent-row {
        margin-top: 0.75rem;
        margin-bottom: 0.35rem;
        padding: 0.5rem 0.65rem;
        background: var(--ap-paper);
        border-radius: 8px;
        border: 1px solid #e8ece9;
    }
    .page-ap-sifec .perm-parent-row .form-check-input {
        width: 1.1rem;
        height: 1.1rem;
        margin-top: 0.15rem;
        border-color: var(--ap-green-mid);
        cursor: pointer;
    }
    .page-ap-sifec .perm-parent-row .form-check-input:checked {
        background-color: var(--ap-green-mid);
        border-color: var(--ap-green-mid);
    }
    .page-ap-sifec .perm-parent-label {
        font-weight: 700;
        font-size: 0.875rem;
        color: var(--ap-green);
        cursor: pointer;
        margin-left: 0.35rem;
    }

    .page-ap-sifec .perm-child-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 0.35rem 1rem;
        margin-left: 0;
        padding-left: 0.5rem;
        border-left: 3px solid var(--ap-green-soft);
        margin-bottom: 0.5rem;
    }
    .page-ap-sifec .perm-child-item {
        display: flex;
        align-items: flex-start;
        gap: 0.45rem;
        padding: 0.4rem 0.5rem;
        border-radius: 8px;
        transition: background 0.15s;
    }
    .page-ap-sifec .perm-child-item:hover {
        background: rgba(27, 111, 74, 0.06);
    }
    .page-ap-sifec .perm-child-item .form-check-input {
        margin-top: 0.2rem;
        border-color: #94a3b8;
        cursor: pointer;
    }
    .page-ap-sifec .perm-child-item .form-check-input:checked {
        background-color: var(--ap-green-mid);
        border-color: var(--ap-green-mid);
    }
    .page-ap-sifec .perm-child-item label {
        font-size: 0.8125rem;
        color: var(--ap-ink);
        cursor: pointer;
        line-height: 1.35;
        margin: 0;
        font-weight: 500;
    }

    .page-ap-sifec .ap-footer {
        position: sticky;
        bottom: 0;
        z-index: 10;
        margin-top: 1.5rem;
        padding: 1rem 1.15rem;
        background: linear-gradient(180deg, rgba(250, 250, 248, 0.92) 0%, var(--ap-cream) 100%);
        backdrop-filter: blur(8px);
        border: 1px solid var(--ap-line);
        border-radius: var(--ap-radius-sm);
        box-shadow: 0 -4px 24px rgba(26, 46, 38, 0.08);
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 0.75rem;
    }
    .page-ap-sifec .btn-ap-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.6rem 1.15rem;
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--ap-ink-muted) !important;
        background: var(--ap-paper);
        border: 1px solid var(--ap-line);
        border-radius: var(--ap-radius-sm);
        text-decoration: none !important;
    }
    .page-ap-sifec .btn-ap-cancel:hover {
        background: #f1f5f4;
        color: var(--ap-ink) !important;
    }
    .page-ap-sifec .btn-ap-save {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.65rem 1.5rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: #fff;
        background: linear-gradient(135deg, var(--ap-green-mid), var(--ap-green));
        border: none;
        border-radius: var(--ap-radius-sm);
        box-shadow: 0 4px 16px rgba(15, 81, 50, 0.25);
    }
    .page-ap-sifec .btn-ap-save:hover {
        color: #fff;
        filter: brightness(1.05);
    }

    .page-ap-sifec .ap-empty {
        text-align: center;
        padding: 2.5rem 1.5rem;
        color: var(--ap-ink-muted);
        background: var(--ap-paper);
        border-radius: var(--ap-radius-sm);
        border: 1px dashed var(--ap-line);
    }

    .page-ap-sifec .btn-ap-save.sifec-btn-loading {
        pointer-events: none;
        opacity: 0.92;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid page-ap-sifec">
    <nav class="ap-breadcrumb" aria-label="Fil d'Ariane">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}">Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}">Profil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Permissions</li>
        </ol>
    </nav>

    <div class="ap-shell">
        <div class="ap-hero">
            <div class="ap-hero-inner">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1" style="min-width: 220px;">
                        <h1>
                            <span class="ap-hero-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
                            Droits &amp; fonctionnalités
                        </h1>
                        <div class="ap-hero-meta">
                            <span><i class="fas fa-user"></i> {{ $nomComplet }}</span>
                            <span><i class="fas fa-envelope"></i> {{ $user->email }}</span>
                            <span><i class="fas fa-building"></i> {{ $aff?->institution?->lib_institution ?? 'Non affecté' }}</span>
                            <span><i class="fas fa-briefcase"></i> {{ $aff?->fonction?->lib_fonction ?? 'Non définie' }}</span>
                        </div>
                        <div class="ap-chips">
                            <span class="ap-chip"><i class="fas fa-check-circle"></i> <strong>{{ $selectedTotal }}</strong> / {{ $totalFonctionnalites }} permissions actives</span>
                            <span class="ap-chip"><i class="fas fa-layer-group"></i> <strong>{{ $modules->count() }}</strong> modules</span>
                        </div>
                    </div>
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-ap-ghost align-self-start">
                        <i class="fas fa-arrow-left"></i> Retour au profil
                    </a>
                </div>
            </div>
        </div>

        <div class="ap-info" role="note">
            <i class="fas fa-info-circle"></i>
            <span>
                Cochez les fonctionnalités accessibles <strong>en complément</strong> du profil métier (fonction). Les cases reflètent l’ensemble des droits effectifs (directs + hérités de la fonction). Enregistrement : droits <strong>directs</strong> sur le compte uniquement.
            </span>
        </div>

        <form action="{{ route('utilisateur.assigner.store', $user->code_user) }}" method="POST" id="assignationForm">
            @csrf

            <div class="ap-toolbar">
                <div class="ap-toolbar-search">
                    <label class="visually-hidden" for="permSearch">Filtrer les modules et permissions</label>
                    <input type="search" class="form-control" id="permSearch" placeholder="Rechercher un module ou une permission…" autocomplete="off">
                </div>
                <div class="ap-toolbar-actions">
                    <button type="button" class="ap-tbtn" id="btnExpandAll"><i class="fas fa-chevron-down me-1"></i>Tout déplier</button>
                    <button type="button" class="ap-tbtn" id="btnCollapseAll"><i class="fas fa-chevron-up me-1"></i>Tout replier</button>
                    <button type="button" class="ap-tbtn" onclick="selectAllPermissions(true)"><i class="fas fa-check-double me-1"></i>Tout cocher</button>
                    <button type="button" class="ap-tbtn" onclick="selectAllPermissions(false)"><i class="fas fa-eraser me-1"></i>Tout décocher</button>
                </div>
            </div>

            @if($modules->isEmpty())
                <div class="ap-empty">
                    <i class="fas fa-folder-open fa-2x mb-3 d-block opacity-50"></i>
                    Aucun module n’est disponible dans le référentiel.
                </div>
            @else
                <div class="accordion perm-accordion" id="accordion-permissions">
                    @foreach ($modules as $module)
                        @php
                            $parents = $module->fonctionnalites->filter(function ($f) {
                                return empty($f->code_fonctionnalite_parent);
                            })->sortBy('lib_fonctionnalite');
                            $codesInModule = $module->fonctionnalites->pluck('code_fonctionnalite');
                            $selMod = $codesInModule->filter(function ($c) use ($userPermissionCodes) {
                                return in_array($c, $userPermissionCodes, true);
                            })->count();
                            $totMod = $codesInModule->count();
                            $searchBlob = \Illuminate\Support\Str::lower(
                                $module->lib_module.' '.$module->fonctionnalites->pluck('lib_fonctionnalite')->implode(' ')
                            );
                        @endphp
                        <div class="accordion-item perm-module-card" data-search="{{ e($searchBlob) }}">
                            <h2 class="accordion-header" id="heading{{ $module->code_module }}">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $module->code_module }}"
                                        aria-expanded="false"
                                        aria-controls="collapse{{ $module->code_module }}">
                                    <i class="fas fa-puzzle-piece me-2 text-success"></i>
                                    {{ $module->lib_module }}
                                    <span class="perm-acc-badge {{ $selMod > 0 ? 'perm-acc-badge--ok' : '' }}">{{ $selMod }}/{{ $totMod }}</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $module->code_module }}"
                                 class="accordion-collapse collapse"
                                 aria-labelledby="heading{{ $module->code_module }}">
                                <div class="accordion-body perm-acc-body">
                                    <div class="perm-mod-toolbar">
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="moduleSelectAll('{{ $module->code_module }}', true)">
                                            <i class="fas fa-check-double"></i> Tout cocher (module)
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="moduleSelectAll('{{ $module->code_module }}', false)">
                                            <i class="fas fa-times"></i> Tout décocher (module)
                                        </button>
                                    </div>

                                    @forelse ($parents as $parent)
                                        <div class="perm-parent-row">
                                            <div class="form-check d-flex align-items-start">
                                                <input class="form-check-input permission-checkbox parent-checkbox"
                                                       type="checkbox"
                                                       name="fonctionnalites[]"
                                                       value="{{ $parent->code_fonctionnalite }}"
                                                       id="parent_{{ $parent->code_fonctionnalite }}"
                                                       {{ in_array($parent->code_fonctionnalite, $userPermissionCodes, true) ? 'checked' : '' }}
                                                       onchange="toggleChildren('{{ $parent->code_fonctionnalite }}')">
                                                <label class="perm-parent-label" for="parent_{{ $parent->code_fonctionnalite }}">{{ $parent->lib_fonctionnalite }}</label>
                                            </div>
                                        </div>
                                        @php
                                            $children = $module->fonctionnalites->where('code_fonctionnalite_parent', $parent->code_fonctionnalite)->sortBy('lib_fonctionnalite');
                                        @endphp
                                        <div class="perm-child-grid" id="children_{{ $parent->code_fonctionnalite }}">
                                            @foreach ($children as $child)
                                                <div class="perm-child-item">
                                                    <input class="form-check-input permission-checkbox child-checkbox"
                                                           type="checkbox"
                                                           name="fonctionnalites[]"
                                                           value="{{ $child->code_fonctionnalite }}"
                                                           id="child_{{ $child->code_fonctionnalite }}"
                                                           data-parent="{{ $parent->code_fonctionnalite }}"
                                                           {{ in_array($child->code_fonctionnalite, $userPermissionCodes, true) ? 'checked' : '' }}>
                                                    <label for="child_{{ $child->code_fonctionnalite }}">{{ $child->lib_fonctionnalite }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @empty
                                        <p class="text-muted small mb-0"><em>Aucune fonctionnalité dans ce module.</em></p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="ap-footer">
                <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn-ap-cancel">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn-ap-save" id="assignationSubmitBtn">
                    <i class="fas fa-save"></i> Enregistrer les permissions
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleChildren(parentCode) {
    var parentCb = document.getElementById('parent_' + parentCode);
    if (!parentCb) return;
    document.querySelectorAll('#children_' + parentCode + ' .child-checkbox').forEach(function (cb) {
        cb.checked = parentCb.checked;
    });
}

function moduleSelectAll(moduleCode, check) {
    var collapse = document.getElementById('collapse' + moduleCode);
    if (!collapse) return;
    collapse.querySelectorAll('.permission-checkbox').forEach(function (cb) {
        cb.checked = check;
    });
}

function selectAllPermissions(check) {
    document.querySelectorAll('#assignationForm .permission-checkbox').forEach(function (cb) {
        cb.checked = check;
    });
}

function setAllAccordionExpanded(expand) {
    document.querySelectorAll('#accordion-permissions .accordion-collapse').forEach(function (el) {
        if (expand) {
            el.classList.add('show');
        } else {
            el.classList.remove('show');
        }
    });
    document.querySelectorAll('#accordion-permissions .accordion-button').forEach(function (btn) {
        btn.classList.toggle('collapsed', !expand);
        btn.setAttribute('aria-expanded', expand ? 'true' : 'false');
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.child-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
            if (this.checked) {
                var parent = document.getElementById('parent_' + this.getAttribute('data-parent'));
                if (parent) parent.checked = true;
            }
        });
    });

    var search = document.getElementById('permSearch');
    if (search) {
        search.addEventListener('input', function () {
            var q = this.value.toLowerCase().trim();
            document.querySelectorAll('.perm-module-card').forEach(function (card) {
                var hay = (card.getAttribute('data-search') || '').toLowerCase();
                card.style.display = !q || hay.indexOf(q) !== -1 ? '' : 'none';
            });
        });
    }

    var btnEx = document.getElementById('btnExpandAll');
    var btnCol = document.getElementById('btnCollapseAll');
    if (btnEx) btnEx.addEventListener('click', function () { setAllAccordionExpanded(true); });
    if (btnCol) btnCol.addEventListener('click', function () { setAllAccordionExpanded(false); });

    var assignForm = document.getElementById('assignationForm');
    var assignBtn = document.getElementById('assignationSubmitBtn');
    if (assignForm && assignBtn) {
        assignForm.addEventListener('submit', function () {
            if (assignBtn.getAttribute('data-sifec-submitting') === '1') return;
            assignBtn.setAttribute('data-sifec-submitting', '1');
            if (!assignBtn.getAttribute('data-sifec-html')) {
                assignBtn.setAttribute('data-sifec-html', assignBtn.innerHTML);
            }
            assignBtn.disabled = true;
            assignBtn.setAttribute('aria-busy', 'true');
            assignBtn.classList.add('sifec-btn-loading');
            assignBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2" aria-hidden="true"></i>Enregistrement en cours…';
        });
    }
});
</script>
@endsection
