@extends('layout.app')
@section('titre')
   Référentiel — Localités
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
/* Page localités — charte SIFEC */
.sifec-localite-page { --sl-green:#006B31; --sl-mid:#009E49; --sl-light:#21B931; --sl-gold:#FBDE4A; }
.sl-hero {
    background: linear-gradient(135deg, var(--sl-green) 0%, var(--sl-mid) 52%, var(--sl-light) 100%);
    border-radius: 16px;
    padding: 1.5rem 1.75rem;
    color: #fff;
    box-shadow: 0 14px 40px rgba(0, 107, 49, 0.28);
    position: relative;
    overflow: hidden;
}
.sl-hero::after {
    content: '';
    position: absolute;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(251, 222, 74, 0.12);
    top: -80px; right: -60px;
    pointer-events: none;
}
.sl-hero h1 { font-size: 1.35rem; font-weight: 700; letter-spacing: 0.02em; margin: 0 0 .35rem; }
.sl-hero p { margin: 0; opacity: .92; font-size: .9rem; max-width: 42rem; }
.sl-hero .btn-light {
    background: rgba(255,255,255,.95);
    border: none;
    color: var(--sl-green);
    font-weight: 600;
    border-radius: 10px;
    padding: .5rem 1rem;
}
.sl-hero .btn-light:hover { background: #fff; color: var(--sl-mid); }
.sl-hero .btn-outline-light {
    border-radius: 10px;
    font-weight: 600;
    border-color: rgba(255,255,255,.55);
    color: #fff;
}
.sl-hero .btn-outline-light:hover { background: rgba(255,255,255,.15); color: #fff; }
.sl-stat {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 22px rgba(0,0,0,.06);
    transition: transform .2s ease, box-shadow .2s ease;
    height: 100%;
}
.sl-stat:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.08); }
.sl-stat .card-body { padding: 1rem 1.15rem; }
.sl-stat-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.sl-stat-val { font-size: 1.5rem; font-weight: 700; color: #1a1a1a; line-height: 1.1; }
.sl-stat-lbl { font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; font-weight: 600; }
.sl-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(0,0,0,.055);
    overflow: hidden;
}
.sl-card .card-header {
    background: linear-gradient(90deg, rgba(0,107,49,.06) 0%, rgba(33,185,49,.04) 100%);
    border-bottom: 1px solid rgba(0,107,49,.1);
    padding: 1rem 1.25rem;
}
.sl-card .card-header h5 { margin: 0; font-size: 1rem; font-weight: 700; color: var(--sl-green); }
.sl-filter-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; font-weight: 700; color: #6c757d; margin-bottom: .35rem; }
.sl-table-wrap { border-radius: 12px; border: 1px solid rgba(0,0,0,.06); overflow: hidden; }
.sl-table thead th {
    background: linear-gradient(180deg, #f8faf9 0%, #eef5f1 100%);
    color: var(--sl-green);
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: .05em;
    font-weight: 700;
    border-bottom: 2px solid rgba(0,158,73,.2);
    padding: .85rem .75rem;
    white-space: nowrap;
}
.sl-table tbody td { padding: .75rem .75rem; vertical-align: middle; border-color: rgba(0,0,0,.05); }
.sl-table tbody tr:hover { background: rgba(0,158,73,.04); }
.sl-code {
    font-size: .75rem;
    background: rgba(0,107,49,.08);
    color: var(--sl-green);
    padding: .2rem .45rem;
    border-radius: 6px;
    font-weight: 600;
}
.sl-badge-type {
    display: inline-block;
    font-size: .72rem;
    font-weight: 600;
    padding: .28rem .55rem;
    border-radius: 8px;
    background: rgba(39,129,213,.1);
    color: #1a5a8a;
}
.sl-parent { font-size: .875rem; color: #495057; }
.sl-root { font-size: .8rem; color: #6c757d; font-style: italic; }
.sl-btn-icon {
    border-radius: 8px !important;
    padding: .35rem .55rem !important;
}
.sl-empty-icon {
    width: 72px; height: 72px; margin: 0 auto;
    border-radius: 50%;
    background: rgba(0,158,73,.1);
    color: var(--sl-mid);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.75rem;
}
.sl-result-pill {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .75rem;
    border-radius: 999px;
    background: rgba(0,158,73,.1);
    color: var(--sl-green);
    font-size: .8rem;
    font-weight: 600;
}
.sl-modal-header {
    background: linear-gradient(135deg, var(--sl-green) 0%, var(--sl-mid) 100%);
    border: none;
}
.sl-modal-header .modal-title { font-weight: 700; font-size: 1.05rem; }
.sl-info-bar {
    border-radius: 12px;
    border: 1px solid rgba(0,107,49,.15);
    background: linear-gradient(90deg, rgba(0,158,73,.06) 0%, rgba(255,255,255,.9) 100%);
}
.sl-row-num { width: 3rem; text-align: center; }
</style>
@endsection
@section('corps')
@php
    $localitesCount = $localites ? $localites->count() : 0;
    $typesCount = $typeLocalites->count();
@endphp
<div class="sifec-localite-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-map-location-dot me-2 opacity-90"></i>Référentiel des localités</h1>
                <p>Structure hiérarchique du territoire : chaque enregistrement est rattaché à un <strong>type</strong> et éventuellement à une <strong>localité parente</strong>. Utilisez la recherche pour parcourir la base (jusqu’à 500 résultats par requête).</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2 justify-content-lg-end">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addLocaliteModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouvelle localité
                </button>
                <a href="{{ route('typelocalite.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-layer-group me-1"></i> Types de localité
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#006B31,#009E49);">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Affichage initial</div>
                        <div class="sl-stat-val">{{ $localitesCount }}</div>
                        <div class="small text-muted">Dernières créations</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#2781d5,#5a9fd4);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Types référencés</div>
                        <div class="sl-stat-val">{{ $typesCount }}</div>
                        <div class="small text-muted">Niveaux hiérarchiques</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card sl-stat sl-info-bar h-100">
                <div class="card-body d-flex align-items-start gap-2 py-3">
                    <i class="fas fa-circle-info text-success mt-1"></i>
                    <div class="small text-muted mb-0">Les écrans historiques (département, commune/district, etc.) ont été unifiés ici. Les codes <code class="small">LOC_*</code> et <code class="small">TPLOC_*</code> restent les clés métier.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card mb-4">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Recherche</h5>
        </div>
        <div class="card-body pt-3 pb-4">
            <form id="form-search-localites" class="row g-3 align-items-end">
                <div class="col-md-4 col-lg-4">
                    <label class="sl-filter-label" for="filter-lib-localite">Libellé</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-magnifying-glass"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="lib_localite" id="filter-lib-localite" placeholder="Ex. Brazzaville, Makelekele…" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <label class="sl-filter-label" for="filter-code-type-localite">Type</label>
                    <select name="code_type_localite" id="filter-code-type-localite" class="form-select">
                        <option value="">Tous les types</option>
                        @foreach ($typeLocalites as $type)
                            <option value="{{ $type->code_type_localite }}">{{ $type->lib_type_localite }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-5 d-flex flex-wrap gap-2 align-items-center">
                    <button type="submit" class="btn btn-success px-4" style="border-radius:10px;font-weight:600;">
                        <i class="fas fa-search me-1"></i> Rechercher
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset-filters-localites" style="border-radius:10px;">
                        <i class="fas fa-rotate-left me-1"></i> Réinitialiser
                    </button>
                    <span id="count-results" class="sl-result-pill ms-md-2 d-none d-md-inline-flex"></span>
                </div>
            </form>
            <div class="mt-2 d-md-none">
                <span id="count-results-mobile" class="sl-result-pill"></span>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Résultats</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addLocaliteModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive sl-table-wrap">
                <table id="table-localites" class="table table-hover sl-table mb-0 align-middle" style="min-width:920px">
                    <thead>
                        <tr>
                            <th class="sl-row-num">#</th>
                            <th>Code</th>
                            <th>Localité</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-localites">
                        @include('referentiel::localite.partials.table-localites', ['localites' => $localites])
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Ajout -->
    <div class="modal fade" id="addLocaliteModal" tabindex="-1" aria-labelledby="addLocaliteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
                    <div class="modal-header sl-modal-header text-white border-0 py-3">
                    <h5 class="modal-title" id="addLocaliteModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Nouvelle localité
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                <form method="POST" action="{{ route('localite.store') }}" id="addLocaliteForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                            <!-- 1. Type de localité (en premier) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                                <select name="code_type_localite" id="add_code_type_localite" class="form-control form-control-lg @error('code_type_localite') is-invalid @enderror" required>
                                    <option value="">Choisissez un type</option>
                                    @foreach ($typeLocalites as $type)
                                        <option value="{{ $type->code_type_localite }}" {{ old('code_type_localite') == $type->code_type_localite ? 'selected' : '' }}>
                                            {{ $type->lib_type_localite }}
                                        </option>
                                        @endforeach
                                    </select>
                                @error("code_type_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Sélectionnez d'abord le type de localité que vous souhaitez enregistrer
                                </small>
                            </div>

                            <!-- 2. Localité parent (se charge selon le type sélectionné) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Localité parent <span id="add_parent_required" class="text-danger" style="display:none;">*</span></label>
                                <select name="code_localite_parent" id="add_code_localite_parent" class="form-control form-control-lg" disabled>
                                    <option value="">Sélectionnez d'abord un type de localité</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i><span id="add_parent_help">Sélectionnez d'abord le type de localité pour voir les parents disponibles</span>
                                </small>
                            </div>

                            <!-- 3. Libellé de la localité (en dernier) -->
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la localité <span class="text-danger">*</span></label>
                                <input type="text" name="lib_localite" class="form-control form-control-lg @error('lib_localite') is-invalid @enderror"
                                       value="{{ old("lib_localite") }}" placeholder="Ex: Brazzaville, Pointe-Noire..." required>
                                @error("lib_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Saisissez le nom de la localité à enregistrer
                                </small>
                                </div>

                            <!-- 4. Pompes funèbres -->
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="pompes_funebres" id="add_pompes_funebres" value="1" {{ old('pompes_funebres') ? 'checked' : '' }} disabled>
                                    <label class="form-check-label fw-bold" for="add_pompes_funebres">
                                        Pompes funèbres
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Disponible uniquement pour Commune ou Arrondissement
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                            <i class="fas fa-check me-1"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
                                </div>

    <!-- Modals Modification -->
    @foreach ($localites as $item)
    <div class="modal fade" id="editLocaliteModal{{ $item->code_localite }}" tabindex="-1" aria-labelledby="editLocaliteModalLabel{{ $item->code_localite }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
                <div class="modal-header sl-modal-header text-white border-0 py-3">
                    <h5 class="modal-title" id="editLocaliteModalLabel{{ $item->code_localite }}">
                        <i class="fas fa-pen-to-square me-2"></i>Modifier — {{ $item->lib_localite }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form action="{{ route('localite.update', $item->code_localite) }}" method="POST" id="editLocaliteForm{{ $item->code_localite }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <label class="form-label fw-bold">Libellé de la localité <span class="text-danger">*</span></label>
                                <input class="form-control form-control-lg @error('lib_localite') is-invalid @enderror"
                                       name="lib_localite" type="text" value="{{ $item->lib_localite }}" required>
                                @error("lib_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label fw-bold">Type de localité <span class="text-danger">*</span></label>
                                <select name="code_type_localite" id="edit_code_type_localite{{ $item->code_localite }}" class="form-control form-control-lg @error('code_type_localite') is-invalid @enderror" required>
                                    @foreach ($typeLocalites as $type)
                                        <option value="{{ $type->code_type_localite }}" {{ $type->code_type_localite == $item->code_type_localite ? 'selected' : '' }}>
                                            {{ $type->lib_type_localite }}
                                        </option>
                                        @endforeach
                                    </select>
                                @error("code_type_localite")
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label fw-bold">Localité parent <span id="edit_parent_required{{ $item->code_localite }}" class="text-danger" style="display:none;">*</span></label>
                                <select name="code_localite_parent" id="edit_code_localite_parent{{ $item->code_localite }}" class="form-control form-control-lg" data-current-id="{{ $item->code_localite }}" data-current-type="{{ $item->code_type_localite }}" data-current-parent="{{ $item->code_localite_parent }}">
                                    <option value="">Chargement...</option>
                                </select>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Les parents disponibles dépendent du type de localité
                                </small>
                            </div>
                            <div class="mb-3 col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="pompes_funebres" id="edit_pompes_funebres{{ $item->code_localite }}" value="1" {{ $item->pompes_funebres ? 'checked' : '' }}
                                           @if(!in_array($item->code_type_localite, ['TPLOC_0003', 'TPLOC_0004'])) disabled @endif>
                                    <label class="form-check-label fw-bold" for="edit_pompes_funebres{{ $item->code_localite }}">
                                        Pompes funèbres
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Disponible uniquement pour Commune ou Arrondissement
                                </small>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-semibold">
                            <i class="fas fa-check me-1"></i>Mettre à jour
                        </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
      <script>
          // Fonction de confirmation de suppression avec flashAlert (SweetAlert2)
          function confirmDelete(code, libelle) {
              var formId = 'deleteForm' + code;
              var form = document.getElementById(formId);

              if (!form) {
                  console.error('Formulaire non trouvé:', formId);
                  Swal.fire({
                      title: 'Erreur',
                      text: 'Formulaire de suppression non trouvé: ' + formId,
                      icon: 'error',
                      confirmButtonText: 'OK'
                  });
                  return;
              }

              Swal.fire({
                  title: 'Êtes-vous sûr ?',
                  html: 'Voulez-vous vraiment supprimer la localité <strong>' + libelle + '</strong> ?',
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#CE1126',
                  cancelButtonColor: '#009639',
                  confirmButtonText: 'Oui, supprimer',
                  cancelButtonText: 'Annuler',
                  buttonsStyling: false,
                  customClass: {
                      confirmButton: 'btn btn-danger me-2',
                      cancelButton: 'btn btn-success'
                  }
              }).then((result) => {
                  if (result.value === true || result.isConfirmed === true) {
                      form.submit();
                  }
              });
          }

          // Variable pour stocker l'instance DataTables
          var tableLocalites = null;

          // Fonction pour rechercher les localités côté serveur
          function searchLocalitesServer() {
              var formData = $('#form-search-localites').serialize();
              formData += '&_token={{ csrf_token() }}';

              $.ajax({
                  url: "{{ route('localite.filter') }}",
                  type: 'POST',
                  data: formData,
                  beforeSend: function() {
                      $('#tbody-localites').html('<tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm text-success me-2" role="status"></span> Chargement…</td></tr>');
                      $('#count-results').text('').addClass('d-none');
                      $('#count-results-mobile').text('');
                  },
                  success: function(response) {
                      try {
                          if (response.code === '200') {
                              // Détruire DataTables complètement avant de modifier le contenu
                              if ($.fn.DataTable.isDataTable('#table-localites')) {
                                  try {
                                      tableLocalites.destroy();
                                  } catch(e) {
                                      console.log('Erreur lors de la destruction de DataTables:', e);
                                  }
                                  tableLocalites = null;
                              }
                              // Vider complètement le tbody et le remplacer par les nouvelles données
                              $('#tbody-localites').empty().html(response.data);

                              // Afficher le nombre de résultats
                              var countText = response.count + ' résultat(s)';
                              if (response.limite_atteinte) {
                                  countText += ' — limite 500 affichés, affinez la recherche';
                              }
                              $('#count-results').text(countText).removeClass('d-none');
                              $('#count-results-mobile').text(countText);

                              // Réinitialiser DataTables avec les nouvelles données (même si vide)
                              setTimeout(function() {
                                  try {
                                      // Vérifier si la table a des données (plus d'une ligne ou pas de classe text-center)
                                      var rows = $('#tbody-localites tr');
                                      var firstTd = rows.first().find('td').first();
                                      var isEmptyState = rows.length === 0 || firstTd.hasClass('sl-empty') || firstTd.attr('colspan') === '6';

                                      if (!isEmptyState && rows.length > 0) {
                                          tableLocalites = $('#table-localites').DataTable({
                                              "language": {
                                                  "search": "Rechercher:",
                                                  "lengthMenu": "Afficher _MENU_ éléments",
                                                  "info": "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                                                  "infoEmpty": "Affichage de 0 à 0 sur 0 éléments",
                                                  "infoFiltered": "(filtré sur _MAX_ éléments au total)",
                                                  "loadingRecords": "Chargement...",
                                                  "zeroRecords": "Aucun élément correspondant trouvé",
                                                  "emptyTable": "Aucune donnée disponible dans le tableau",
                                                  "paginate": {
                                                      "first": "Premier",
                                                      "last": "Dernier",
                                                      "next": "Suivant",
                                                      "previous": "Précédent"
                                                  }
                                              },
                                              "paging": false,
                                              "searching": true,
                                              "info": false,
                                              "ordering": true,
                                              "destroy": true
                                          });
                                      } else {
                                          tableLocalites = null;
                                      }
                                  } catch(e) {
                                      console.error('Erreur lors de l\'initialisation de DataTables:', e);
                                  }
                              }, 100);
                          } else {
                              flashAlert("Erreur", "error", response.message || "Une erreur est survenue lors de la recherche");
                          }
                      } catch(e) {
                          console.error('Erreur lors du traitement de la réponse:', e);
                          flashAlert("Erreur", "error", "Erreur lors du traitement de la réponse");
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('Erreur AJAX:', error);
                      var errorMessage = "Erreur lors de la recherche des localités";
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errorMessage = xhr.responseJSON.message;
                      } else if (xhr.responseJSON && xhr.responseJSON.error) {
                          errorMessage = xhr.responseJSON.error;
                      }
                      flashAlert("Erreur", "error", errorMessage);
                  }
              });
          }

          // Fonction pour charger les parents disponibles selon le type de localité
          function loadAvailableParents(selectElement, codeTypeLocalite, currentValue = null, excludeId = null) {
              if (!codeTypeLocalite) {
                  $(selectElement).html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  return;
              }

              var url = "{{ route('localite.available.parents.by.type', ':type') }}".replace(':type', codeTypeLocalite);
              if (excludeId) {
                  url = "{{ route('localite.available.parents', ':id') }}".replace(':id', excludeId) + '?type=' + codeTypeLocalite;
              }

              $(selectElement).html('<option value="">Chargement...</option>');

              $.ajax({
                  url: url,
                  method: 'GET',
                  dataType: 'json',
                  success: function(data) {
                      var options = '';

                      // Seul le Département peut être racine (pas de parent)
                      if (codeTypeLocalite === 'TPLOC_0001') {
                          options = '<option value="">Aucune (Département - localité racine)</option>';
                      } else {
                          // Pour tous les autres types, un parent est obligatoire
                          options = '<option value="">-- Sélectionnez un parent --</option>';
                      }

                      $.each(data, function(index, parent) {
                          var typeLabel = parent.typelocalite ? ' (' + parent.typelocalite.lib_type_localite + ')' : '';
                          var selected = (currentValue && currentValue === parent.code_localite) ? 'selected' : '';
                          options += '<option value="' + parent.code_localite + '" ' + selected + '>' + parent.lib_localite + typeLabel + '</option>';
                      });

                      $(selectElement).html(options);
                  },
                  error: function(xhr, status, error) {
                      console.error('Erreur lors du chargement des parents disponibles:', error);
                      $(selectElement).html('<option value="">Erreur lors du chargement</option>');
                  }
              });
          }

          // Fonction pour gérer l'activation/désactivation du champ pompes_funebres
          function togglePompesFunebres(codeTypeLocalite, checkboxId) {
              var checkbox = $('#' + checkboxId);
              if (codeTypeLocalite === 'TPLOC_0003' || codeTypeLocalite === 'TPLOC_0004') {
                  checkbox.prop('disabled', false);
              } else {
                  checkbox.prop('disabled', true);
                  checkbox.prop('checked', false);
              }
          }

          // Event listener pour les boutons de suppression (délégation d'événements)
          $(document).ready(function() {
              $('#count-results').text('{{ $localitesCount }} ligne(s) — aperçu').removeClass('d-none');
              $('#count-results-mobile').text('{{ $localitesCount }} ligne(s)');

              var initRows = $('#tbody-localites tr');
              if (initRows.length && !initRows.first().find('td.sl-empty').length) {
                  try {
                      if (!$.fn.DataTable.isDataTable('#table-localites')) {
                          tableLocalites = $('#table-localites').DataTable({
                              language: {
                                  search: 'Filtrer le tableau :',
                                  lengthMenu: 'Afficher _MENU_',
                                  zeroRecords: 'Aucune ligne',
                                  emptyTable: '—',
                                  info: '',
                                  infoEmpty: '',
                                  infoFiltered: ''
                              },
                              paging: false,
                              searching: true,
                              info: false,
                              ordering: true
                          });
                      }
                  } catch (e) { /* ignore */ }
              }

              // Charger les parents disponibles lors du changement de type (modal ajout)
              $('#add_code_type_localite').on('change', function() {
                  var codeTypeLocalite = $(this).val();
                  var selectParent = $('#add_code_localite_parent');
                  var parentRequired = $('#add_parent_required');
                  var parentHelp = $('#add_parent_help');

                  if (codeTypeLocalite) {
                      // Activer le champ parent et charger les parents disponibles
                      selectParent.prop('disabled', false);

                      // Pour le Département, le parent n'est pas requis
                      if (codeTypeLocalite === 'TPLOC_0001') {
                          selectParent.prop('required', false);
                          parentRequired.hide();
                          parentHelp.text('Le Département est une localité racine (pas de parent)');
                      } else {
                          selectParent.prop('required', true);
                          parentRequired.show();
                          parentHelp.text('Sélectionnez un parent selon la hiérarchie');
                      }

                      loadAvailableParents(selectParent, codeTypeLocalite);
                      togglePompesFunebres(codeTypeLocalite, 'add_pompes_funebres');
                  } else {
                      // Désactiver le champ parent et réinitialiser
                      selectParent.prop('disabled', true);
                      selectParent.prop('required', false);
                      selectParent.html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                      parentRequired.hide();
                      parentHelp.text('Sélectionnez d\'abord le type de localité pour voir les parents disponibles');
                      $('#add_pompes_funebres').prop('disabled', true).prop('checked', false);
                  }
              });

              // Initialiser le champ parent et pompes_funebres au chargement (modal ajout)
              var initialType = $('#add_code_type_localite').val();
              if (initialType) {
                  $('#add_code_type_localite').trigger('change');
              } else {
                  $('#add_code_localite_parent').prop('disabled', true);
                  $('#add_code_localite_parent').html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  $('#add_pompes_funebres').prop('disabled', true);
              }

              // Réinitialiser le formulaire lors de l'ouverture du modal
              $('#addLocaliteModal').on('show.bs.modal', function() {
                  // Réinitialiser tous les champs
                  $('#addLocaliteForm')[0].reset();
                  $('#add_code_type_localite').val('').trigger('change');
                  $('#add_code_localite_parent').prop('disabled', true);
                  $('#add_code_localite_parent').html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                  $('#add_pompes_funebres').prop('disabled', true).prop('checked', false);
                  $('#add_parent_required').hide();
                  $('#add_parent_help').text('Sélectionnez d\'abord le type de localité pour voir les parents disponibles');
              });
              // Soumission du formulaire de recherche
              $('#form-search-localites').on('submit', function(e) {
                  e.preventDefault();
                  searchLocalitesServer();
              });

              // Réinitialiser les filtres
              $('#btn-reset-filters-localites').on('click', function() {
                  $('#form-search-localites')[0].reset();
                  // Recharger les données initiales (20 derniers)
                  location.reload();
              });

              $(document).on('click', '.btn-delete', function(e) {
                  e.preventDefault();
                  e.stopPropagation();

                  var button = $(this);
                  var code = button.data('code');
                  var libelle = button.data('libelle');

                  if (code && libelle) {
                      confirmDelete(code, libelle);
                  } else {
                      Swal.fire({
                          title: 'Erreur',
                          text: 'Données manquantes pour la suppression',
                          type: 'error',
                          confirmButtonText: 'OK'
                      });
                  }
              });

              // Charger les parents disponibles lors de la modification
              @foreach ($localites as $item)
              (function() {
                  var modalId = '#editLocaliteModal{{ $item->code_localite }}';
                  var selectParent = $('#edit_code_localite_parent{{ $item->code_localite }}');
                  var selectType = $('#edit_code_type_localite{{ $item->code_localite }}');
                  var currentId = '{{ $item->code_localite }}';
                  var currentType = '{{ $item->code_type_localite }}';
                  var currentParent = '{{ $item->code_localite_parent }}';

                  // Charger les parents disponibles lors de l'ouverture du modal
                  $(modalId).on('show.bs.modal', function() {
                      loadEditAvailableParents(selectParent, currentType, currentId, currentParent);
                      toggleEditPompesFunebres(currentType, 'edit_pompes_funebres{{ $item->code_localite }}');
                  });

                  // Charger les parents disponibles lors du changement de type
                  selectType.on('change', function() {
                      var newType = $(this).val();
                      loadEditAvailableParents(selectParent, newType, currentId, currentParent);
                      toggleEditPompesFunebres(newType, 'edit_pompes_funebres{{ $item->code_localite }}');
                  });
              })();
              @endforeach

              // Fonction pour charger les parents disponibles dans le modal de modification
              function loadEditAvailableParents(selectElement, codeTypeLocalite, excludeId, currentParentValue) {
                  if (!codeTypeLocalite) {
                      $(selectElement).html('<option value="">Sélectionnez d\'abord un type de localité</option>');
                      return;
                  }

                  // Utiliser la route avec exclusion si un ID est fourni
                  var url;
                  if (excludeId) {
                      url = "{{ route('localite.available.parents', ':id') }}".replace(':id', excludeId) + '?type=' + codeTypeLocalite;
                  } else {
                      url = "{{ route('localite.available.parents.by.type', ':type') }}".replace(':type', codeTypeLocalite);
                  }

                  $(selectElement).html('<option value="">Chargement...</option>');

                  $.ajax({
                      url: url,
                      method: 'GET',
                      dataType: 'json',
                      success: function(data) {
                          var options = '';

                          // Seul le Département peut être racine (pas de parent)
                          if (codeTypeLocalite === 'TPLOC_0001') {
                              options = '<option value="">Aucune (Département - localité racine)</option>';
                          } else {
                              options = '<option value="">-- Sélectionnez un parent --</option>';
                          }

                          $.each(data, function(index, parent) {
                              var typeLabel = parent.typelocalite ? ' (' + parent.typelocalite.lib_type_localite + ')' : '';
                              var selected = (currentParentValue && currentParentValue === parent.code_localite) ? 'selected' : '';
                              options += '<option value="' + parent.code_localite + '" ' + selected + '>' + parent.lib_localite + typeLabel + '</option>';
                          });

                          $(selectElement).html(options);

                          // Si le parent actuel n'est pas dans la liste (peut arriver si le type change), le sélectionner quand même
                          if (currentParentValue && !$(selectElement).find('option[value="' + currentParentValue + '"]').length) {
                              $(selectElement).append('<option value="' + currentParentValue + '" selected>Parent actuel (non disponible avec ce type)</option>');
                          }
                      },
                      error: function(xhr, status, error) {
                          console.error('Erreur lors du chargement des parents disponibles:', error);
                          $(selectElement).html('<option value="">Erreur lors du chargement</option>');
                      }
                  });
              }

              // Fonction pour gérer l'activation/désactivation du champ pompes_funebres dans le modal de modification
              function toggleEditPompesFunebres(codeTypeLocalite, checkboxId) {
                  var checkbox = $('#' + checkboxId);
                  if (codeTypeLocalite === 'TPLOC_0003' || codeTypeLocalite === 'TPLOC_0004') {
                      checkbox.prop('disabled', false);
                  } else {
                      checkbox.prop('disabled', true);
                      if (!checkbox.prop('checked')) {
                          checkbox.prop('checked', false);
                      }
                  }
              }
          });
      </script>
@endsection
