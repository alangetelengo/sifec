@extends('layout.app')
@section('titre') Gestion des Appareils @endsection

@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    /* ── Couleurs SIFEC ───────────────────────────────────────────── */
    :root {
        --sifec-green:  #009E49;
        --sifec-green2: #21B931;
        --sifec-yellow: #FBDE4A;
        --sifec-red:    #DC241F;
        --sifec-dark:   #006B31;
    }

    /* ── En-tête de page ─────────────────────────────────────────── */
    .app-page-header {
        background: linear-gradient(135deg, var(--sifec-dark) 0%, var(--sifec-green) 55%, var(--sifec-green2) 100%);
        border-radius: 14px;
        padding: 20px 28px;
        margin-bottom: 24px;
        color: #fff;
        box-shadow: 0 6px 22px rgba(0,158,73,.28);
        position: relative;
        overflow: hidden;
    }
    .app-page-header::after {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
    }
    .app-page-header .page-title  { font-size: 1.45rem; font-weight: 700; margin: 0; }
    .app-page-header .page-sub    { opacity: .88; font-size: .9rem; margin-top: 4px; }

    /* ── Cartes statistiques ─────────────────────────────────────── */
    .stat-card {
        border: none;
        border-radius: 12px;
        padding: 18px 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,.12);
        transition: transform .2s;
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card .stat-icon {
        position: absolute;
        right: 16px; top: 16px;
        font-size: 2.4rem;
        opacity: .22;
    }
    .stat-card .stat-num  { font-size: 2rem; font-weight: 700; line-height: 1; }
    .stat-card .stat-lbl  { font-size: .82rem; opacity: .9; margin-top: 4px; }
    .stat-card.sc-total   { background: linear-gradient(135deg, #006B31, #009E49); }
    .stat-card.sc-actif   { background: linear-gradient(135deg, #1a8a2e, #21B931); }
    .stat-card.sc-inactif { background: linear-gradient(135deg, #9b1915, #DC241F); }
    .stat-card.sc-type    { background: linear-gradient(135deg, #c8960a, #FBDE4A); color: #333; }
    .stat-card.sc-type .stat-lbl { color: #555; }

    /* ── Card contenu principal ──────────────────────────────────── */
    .main-card .card-header {
        background: linear-gradient(90deg, var(--sifec-dark), var(--sifec-green));
        color: #fff;
        border-radius: 10px 10px 0 0 !important;
        padding: 14px 20px;
    }

    /* ── Badges statut ───────────────────────────────────────────── */
    .badge-actif   { background-color: #009E49; color: #fff; padding: 5px 12px; border-radius: 30px; font-size: .78rem; }
    .badge-inactif { background-color: #DC241F; color: #fff; padding: 5px 12px; border-radius: 30px; font-size: .78rem; }

    /* ── Icônes par type ─────────────────────────────────────────── */
    .app-type-icon { font-size: 1.15rem; }
    .app-type-icon.ordinateur { color: #2781d5; }
    .app-type-icon.tablette   { color: #009E49; }
    .app-type-icon.smartphone { color: #c8960a; }
    .app-type-icon.autre      { color: #888; }

    /* ── MAC address ─────────────────────────────────────────────── */
    .mac-address {
        font-family: 'Courier New', monospace;
        background: #f1f5f9;
        border-radius: 4px;
        padding: 2px 7px;
        font-size: .82rem;
        color: #334;
        letter-spacing: .05em;
    }

    /* ── Boutons actions ─────────────────────────────────────────── */
    .btn-edit   { background: #2781d5; color: #fff; border: none; }
    .btn-edit:hover   { background: #1a5fa0; color: #fff; }
    .btn-delete { background: #DC241F; color: #fff; border: none; }
    .btn-delete:hover { background: #9b1915; color: #fff; }
    .btn-toggle { border: none; }
    .btn-toggle.on  { background: #009E49; color: #fff; }
    .btn-toggle.on:hover  { background: #006B31; color: #fff; }
    .btn-toggle.off { background: #888; color: #fff; }
    .btn-toggle.off:hover { background: #555; color: #fff; }

    /* ── Modal headers ───────────────────────────────────────────── */
    .modal-header-green { background: linear-gradient(90deg, #006B31, #009E49); color: #fff; }
    .modal-header-blue  { background: linear-gradient(90deg, #1a5fa0, #2781d5); color: #fff; }
    .modal-header-green .btn-close,
    .modal-header-blue  .btn-close { filter: invert(1); }

    /* ── Filtres ─────────────────────────────────────────────────── */
    .filter-card { background: #f8fdf9; border: 1px solid #d4edda; border-radius: 10px; padding: 16px 20px; margin-bottom: 18px; }

    /* ── Bootstrap Select (selectpicker) ────────────────────────── */
    .bootstrap-select .dropdown-toggle { background: #fff; border: 1px solid #ced4da; border-radius: 6px; color: #333; }
    .bootstrap-select .dropdown-toggle:focus { box-shadow: 0 0 0 .2rem rgba(0,158,73,.25); border-color: #009E49; }
    .bootstrap-select .dropdown-menu { border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,.12); }
    .bootstrap-select .bs-searchbox input { border-radius: 6px; border: 1px solid #009E49; }
    .bootstrap-select .bs-searchbox input:focus { box-shadow: 0 0 0 .15rem rgba(0,158,73,.25); border-color: #009E49; outline: none; }
    .bootstrap-select .dropdown-item.active,
    .bootstrap-select .dropdown-item:active { background-color: #009E49; }
    /* Agrandir le bouton pour matcher le form-control */
    .bootstrap-select.form-control { padding: 0; border: none; }
    .bootstrap-select.form-control > .dropdown-toggle { width: 100%; height: calc(1.5em + .75rem + 2px); padding: .375rem .75rem; }

    /* ── Table ───────────────────────────────────────────────────── */
    #table-appareils thead tr th { background: #009E49 !important; color: #fff !important; border: none; font-weight: 600; font-size: .88rem; }
    #table-appareils tbody tr:hover { background: #f0faf4 !important; }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--sifec-green) !important;
        color: #fff !important;
        border-color: var(--sifec-green) !important;
        border-radius: 6px;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
<div class="row">
<div class="col-12">

    {{-- ── En-tête ──────────────────────────────────────────────── --}}
    <div class="app-page-header d-flex justify-content-between align-items-center">
        <div>
            <div class="page-title"><i class="fas fa-laptop me-2"></i>Gestion des Appareils Autorisés</div>
            <div class="page-sub">Whitelist des équipements habilités à signer les actes d'état civil</div>
        </div>
        <button type="button" class="btn btn-light fw-bold" data-bs-toggle="modal" data-bs-target="#modalAjoutAppareil">
            <i class="fas fa-plus-circle me-2 text-success"></i>Enregistrer un appareil
        </button>
    </div>

    {{-- ── Cartes statistiques ──────────────────────────────────── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-2">
            <div class="stat-card sc-total">
                <i class="fas fa-laptop stat-icon"></i>
                <div class="stat-num">{{ $stats['total'] }}</div>
                <div class="stat-lbl">Total appareils</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card sc-actif">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-num">{{ $stats['actifs'] }}</div>
                <div class="stat-lbl">Actifs</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card sc-inactif">
                <i class="fas fa-ban stat-icon"></i>
                <div class="stat-num">{{ $stats['inactifs'] }}</div>
                <div class="stat-lbl">Désactivés</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card sc-type" style="background:linear-gradient(135deg,#1a5fa0,#2781d5);color:#fff;">
                <i class="fas fa-desktop stat-icon"></i>
                <div class="stat-num">{{ $stats['ordinateurs'] }}</div>
                <div class="stat-lbl" style="color:#ddf;">Ordinateurs</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card sc-type">
                <i class="fas fa-tablet-alt stat-icon"></i>
                <div class="stat-num">{{ $stats['tablettes'] }}</div>
                <div class="stat-lbl">Tablettes</div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="stat-card sc-type" style="background:linear-gradient(135deg,#5a2e8a,#8b5cf6);color:#fff;">
                <i class="fas fa-mobile-alt stat-icon"></i>
                <div class="stat-num">{{ $stats['smartphones'] }}</div>
                <div class="stat-lbl" style="color:#ece;">Smartphones</div>
            </div>
        </div>
    </div>

    {{-- ── Card principale ────────────────────────────────────────── --}}
    <div class="card main-card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Liste des appareils enregistrés</span>
            <span id="count-results" class="badge bg-light text-dark"></span>
        </div>
        <div class="card-body">

            {{-- Filtres --}}
            <div class="filter-card">
                <form id="form-search-appareils">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Nom de l'appareil</label>
                            <input type="text" class="form-control form-control-sm" name="nom_appareil" placeholder="Rechercher par nom…">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Adresse MAC</label>
                            <input type="text" class="form-control form-control-sm" name="adresse_mac" placeholder="XX:XX:XX:…">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Type</label>
                            <select name="type_appareil" class="form-control form-control-sm">
                                <option value="">Tous les types</option>
                                <option value="ordinateur">Ordinateur</option>
                                <option value="tablette">Tablette</option>
                                <option value="smartphone">Smartphone</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small">Institution</label>
                            <select name="code_institution"
                                    class="form-control form-control-sm selectpicker"
                                    data-live-search="true"
                                    data-live-search-placeholder="Rechercher..."
                                    data-size="7"
                                    title="Toutes les institutions">
                                <option value="">Toutes les institutions</option>
                                @foreach ($institutions as $inst)
                                    <option value="{{ $inst->code_institution }}">{{ $inst->lib_institution }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold small">Statut</label>
                            <select name="statut" class="form-control form-control-sm">
                                <option value="">Tous</option>
                                <option value="1">Actif</option>
                                <option value="0">Désactivé</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-search me-1"></i>Rechercher
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary ms-1" id="btn-reset-filters">
                            <i class="fas fa-redo me-1"></i>Réinitialiser
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tableau --}}
            <div id="table-container">
                @include('referentiel::appareil.partials.table-appareils', ['appareils' => $appareils])
            </div>

        </div>
    </div>

</div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     MODAL — Ajouter un appareil
════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalAjoutAppareil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-green">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Enregistrer un nouvel appareil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('appareil.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom de l'appareil <span class="text-danger">*</span></label>
                            <input type="text" name="nom_appareil" class="form-control @error('nom_appareil') is-invalid @enderror"
                                   value="{{ old('nom_appareil') }}" placeholder="Ex : PC-CEC-BRAZZA-01" required>
                            @error('nom_appareil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Adresse MAC <span class="text-danger">*</span></label>
                            <input type="text" name="adresse_mac" id="add-mac"
                                   class="form-control @error('adresse_mac') is-invalid @enderror"
                                   value="{{ old('adresse_mac') }}"
                                   placeholder="AA:BB:CC:DD:EE:FF" maxlength="50" required>
                            <div class="form-text text-muted"><i class="fas fa-info-circle me-1"></i>Format : AA:BB:CC:DD:EE:FF (17 caractères)</div>
                            @error('adresse_mac') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Type d'appareil <span class="text-danger">*</span></label>
                            <select name="type_appareil" class="form-control @error('type_appareil') is-invalid @enderror" required>
                                <option value="">— Choisir —</option>
                                <option value="ordinateur" {{ old('type_appareil') == 'ordinateur' ? 'selected' : '' }}>💻 Ordinateur</option>
                                <option value="tablette"   {{ old('type_appareil') == 'tablette'   ? 'selected' : '' }}>📱 Tablette</option>
                                <option value="smartphone" {{ old('type_appareil') == 'smartphone' ? 'selected' : '' }}>📲 Smartphone</option>
                                <option value="autre"      {{ old('type_appareil') == 'autre'      ? 'selected' : '' }}>🔧 Autre</option>
                            </select>
                            @error('type_appareil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Institution responsable</label>
                            <select name="code_institution"
                                    class="form-control selectpicker"
                                    data-live-search="true"
                                    data-live-search-placeholder="Tapez pour rechercher..."
                                    data-size="8"
                                    title="— Aucune institution associée —">
                                <option value="">— Aucune institution associée —</option>
                                @foreach ($institutions as $inst)
                                    <option value="{{ $inst->code_institution }}" {{ old('code_institution') == $inst->code_institution ? 'selected' : '' }}>
                                        {{ $inst->lib_institution }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Statut initial</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statut" value="1" id="statut-actif" checked>
                                    <label class="form-check-label text-success fw-bold" for="statut-actif">
                                        <i class="fas fa-check-circle me-1"></i>Actif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statut" value="0" id="statut-inactif">
                                    <label class="form-check-label text-danger fw-bold" for="statut-inactif">
                                        <i class="fas fa-ban me-1"></i>Désactivé
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Aide visuelle sur le format MAC --}}
                    <div class="alert alert-info mt-3 mb-0 py-2 px-3" style="font-size:.85rem;">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Comment obtenir l'adresse MAC ?</strong>
                        Windows : <code>getmac /v</code> ou <code>ipconfig /all</code> &nbsp;|&nbsp;
                        Linux/Mac : <code>ip link show</code> ou <code>ifconfig</code>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     MODALS — Modifier (un par appareil)
════════════════════════════════════════════════════════════════ --}}
@foreach ($appareils as $ap)
<div class="modal fade" id="modalEdit{{ $ap->code_appareil }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-blue">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Modifier — {{ $ap->nom_appareil }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('appareil.update', $ap->code_appareil) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom de l'appareil <span class="text-danger">*</span></label>
                            <input type="text" name="nom_appareil" class="form-control" value="{{ $ap->nom_appareil }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Adresse MAC <span class="text-danger">*</span></label>
                            <input type="text" name="adresse_mac" class="form-control" value="{{ $ap->adresse_mac }}" maxlength="50" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Type d'appareil <span class="text-danger">*</span></label>
                            <select name="type_appareil" class="form-control" required>
                                <option value="ordinateur" {{ $ap->type_appareil == 'ordinateur' ? 'selected' : '' }}>💻 Ordinateur</option>
                                <option value="tablette"   {{ $ap->type_appareil == 'tablette'   ? 'selected' : '' }}>📱 Tablette</option>
                                <option value="smartphone" {{ $ap->type_appareil == 'smartphone' ? 'selected' : '' }}>📲 Smartphone</option>
                                <option value="autre"      {{ $ap->type_appareil == 'autre'      ? 'selected' : '' }}>🔧 Autre</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Institution responsable</label>
                            <select name="code_institution"
                                    class="form-control selectpicker"
                                    data-live-search="true"
                                    data-live-search-placeholder="Tapez pour rechercher..."
                                    data-size="8"
                                    title="— Aucune —">
                                <option value="">— Aucune —</option>
                                @foreach ($institutions as $inst)
                                    <option value="{{ $inst->code_institution }}"
                                        {{ $ap->code_institution == $inst->code_institution ? 'selected' : '' }}>
                                        {{ $inst->lib_institution }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Statut</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statut" value="1"
                                           id="edit-statut-actif-{{ $ap->code_appareil }}"
                                           {{ $ap->statut ? 'checked' : '' }}>
                                    <label class="form-check-label text-success fw-bold" for="edit-statut-actif-{{ $ap->code_appareil }}">
                                        <i class="fas fa-check-circle me-1"></i>Actif
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="statut" value="0"
                                           id="edit-statut-inactif-{{ $ap->code_appareil }}"
                                           {{ !$ap->statut ? 'checked' : '' }}>
                                    <label class="form-check-label text-danger fw-bold" for="edit-statut-inactif-{{ $ap->code_appareil }}">
                                        <i class="fas fa-ban me-1"></i>Désactivé
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-muted">Code</label>
                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $ap->code_appareil }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- ════════════════════════════════════════════════════════════════
     MODAL — Détail appareil
════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(90deg,#006B31,#009E49);color:#fff;">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Détail de l'appareil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
            </div>
            <div class="modal-body" id="detail-body">
                {{-- rempli par JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
$(function () {

    // ── Initialiser selectpicker sur tous les selects concernés ───────────────
    $('.selectpicker').selectpicker({
        noneSelectedText: '— Choisir —',
        liveSearchStyle: 'contains',
    });

    // Re-init lors de l'ouverture des modals (pour les modals d'édition)
    $(document).on('show.bs.modal', '.modal', function () {
        $(this).find('.selectpicker').selectpicker('refresh');
    });

    // ── DataTable ──────────────────────────────────────────────────────────
    function initDataTable() {
        if ($.fn.DataTable.isDataTable('#table-appareils')) {
            $('#table-appareils').DataTable().destroy();
        }
        $('#table-appareils').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            },
            pageLength: 15,
            order: [[5, 'desc']],
            columnDefs: [{ orderable: false, targets: [6] }],
            responsive: true
        });
    }
    initDataTable();

    // ── Filtre AJAX ────────────────────────────────────────────────────────
    $('#form-search-appareils').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url:  '{{ route("appareil.filter") }}',
            type: 'POST',
            data: $(this).serialize() + '&_token={{ csrf_token() }}',
            beforeSend: function () {
                $('#table-container').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-success"></i></div>');
            },
            success: function (res) {
                if (res.success) {
                    $('#table-container').html(res.html);
                    $('#count-results').text(res.count + ' résultat(s)');
                    initDataTable();
                }
            },
            error: function () {
                toastr.error('Erreur lors de la recherche.');
            }
        });
    });

    // ── Réinitialiser filtres ──────────────────────────────────────────────
    $('#btn-reset-filters').on('click', function () {
        $('#form-search-appareils')[0].reset();
        $('#count-results').text('');
        $('#form-search-appareils').submit();
    });

    // ── Formatage automatique adresse MAC ─────────────────────────────────
    $('#add-mac').on('input', function () {
        let v = $(this).val().replace(/[^0-9a-fA-F]/g, '').toUpperCase();
        let formatted = v.match(/.{1,2}/g)?.join(':') ?? v;
        if (formatted.length > 17) formatted = formatted.substring(0, 17);
        $(this).val(formatted);
    });

    // ── Toggle statut (AJAX) ───────────────────────────────────────────────
    $(document).on('click', '.btn-toggle-statut', function () {
        const btn  = $(this);
        const code = btn.data('code');
        const nom  = btn.data('nom');
        const actuel = btn.data('statut') == '1';

        Swal.fire({
            title: actuel ? 'Désactiver cet appareil ?' : 'Activer cet appareil ?',
            html:  `<strong>${nom}</strong> sera ${actuel ? 'désactivé et ne pourra plus signer' : 'activé et autorisé à signer'}.`,
            icon:  actuel ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: actuel ? '#DC241F' : '#009E49',
            cancelButtonColor:  '#6c757d',
            confirmButtonText: actuel ? '<i class="fas fa-ban"></i> Désactiver' : '<i class="fas fa-check"></i> Activer',
            cancelButtonText:  'Annuler',
        }).then(result => {
            if (!result.isConfirmed) return;
            $.ajax({
                url:  `/appareil/${code}/toggle-statut`,
                type: 'PATCH',
                data: { _token: '{{ csrf_token() }}' },
                success: function (res) {
                    if (res.success) {
                        toastr.success(res.message, 'Appareils');
                        setTimeout(() => location.reload(), 900);
                    }
                },
                error: function () { toastr.error('Erreur lors de la mise à jour du statut.'); }
            });
        });
    });

    // ── Suppression ────────────────────────────────────────────────────────
    $(document).on('click', '.btn-delete-appareil', function () {
        const code = $(this).data('code');
        const nom  = $(this).data('nom');

        Swal.fire({
            title: 'Supprimer cet appareil ?',
            html:  `<strong>${nom}</strong> sera retiré de la whitelist.<br><small class="text-danger">Cette action est irréversible.</small>`,
            icon:  'warning',
            showCancelButton:   true,
            confirmButtonColor: '#DC241F',
            cancelButtonColor:  '#009E49',
            confirmButtonText:  '<i class="fas fa-trash"></i> Supprimer',
            cancelButtonText:   'Annuler',
        }).then(result => {
            if (!result.isConfirmed) return;
            const form = $('<form>', { method: 'POST', action: `/appareil/${code}/destroy` });
            form.append($('<input>', { type: 'hidden', name: '_token',  value: '{{ csrf_token() }}' }));
            form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
            $('body').append(form);
            form.submit();
        });
    });

    // ── Bouton Détail ──────────────────────────────────────────────────────
    $(document).on('click', '.btn-detail-appareil', function () {
        const data = $(this).data();
        $('#detail-body').html(`
            <table class="table table-sm table-borderless">
                <tr><th style="width:40%">Code</th><td><code>${data.code}</code></td></tr>
                <tr><th>Nom</th><td>${data.nom}</td></tr>
                <tr><th>Adresse MAC</th><td><span class="mac-address">${data.mac}</span></td></tr>
                <tr><th>Type</th><td>${data.type}</td></tr>
                <tr><th>Institution</th><td>${data.institution || '—'}</td></tr>
                <tr><th>Statut</th><td>${data.statut == '1'
                    ? '<span class="badge-actif"><i class=\"fas fa-check-circle me-1\"></i>Actif</span>'
                    : '<span class="badge-inactif"><i class=\"fas fa-ban me-1\"></i>Désactivé</span>'
                }</td></tr>
                <tr><th>Enregistré le</th><td>${data.date}</td></tr>
            </table>
        `);
        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    });

    // ── Ouvrir le modal d'ajout si erreur de validation ───────────────────
    @if ($errors->any())
        new bootstrap.Modal(document.getElementById('modalAjoutAppareil')).show();
    @endif
});
</script>
@endsection
