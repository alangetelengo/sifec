@extends('layout.app')
@section('titre')
Registre Etat civil
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<style>
    /* Page registres — en-tête + modal création (charte SIFEC) */
    .sifec-registre-page .card-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(0, 107, 49, 0.12);
    }
    .sifec-registre-page .card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        color: #1a1a1a;
    }
    .sifec-registre-btn-add {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.55rem 1.25rem;
        font-weight: 600;
        font-size: 0.9rem;
        border: none;
        border-radius: 999px;
        color: #fff !important;
        background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%);
        box-shadow: 0 4px 14px rgba(0, 107, 49, 0.28);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .sifec-registre-btn-add:hover {
        color: #fff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 107, 49, 0.35);
    }
    .sifec-registre-btn-add:focus-visible {
        outline: none;
        box-shadow: 0 0 0 3px rgba(0, 158, 73, 0.35);
    }
    #modalCEC .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.12);
    }
    #modalCEC .modal-header {
        border: none;
        padding: 1.1rem 1.35rem;
        background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #0d7a45 100%);
        color: #fff;
    }
    #modalCEC .modal-title {
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: 0.01em;
    }
    #modalCEC .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.9;
    }
    #modalCEC .btn-close:hover {
        opacity: 1;
    }
    #modalCEC .modal-body {
        padding: 1.35rem 1.35rem 1.15rem;
        background: #fafbfa;
    }
    #modalCEC .sifec-registre-field {
        margin-bottom: 1.15rem;
    }
    #modalCEC .sifec-registre-field:last-of-type {
        margin-bottom: 0;
    }
    #modalCEC .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.4rem;
    }
    #modalCEC .form-control,
    #modalCEC .form-select {
        border-radius: 10px;
        border: 1px solid rgba(0, 107, 49, 0.18);
        padding: 0.55rem 0.85rem;
        background: #fff;
    }
    #modalCEC .form-control:focus,
    #modalCEC .form-select:focus {
        border-color: #009E49;
        box-shadow: 0 0 0 3px rgba(0, 158, 73, 0.18);
    }
    #modalCEC .form-control[readonly] {
        background: #f3f4f3;
        color: #1f2937;
    }
    #modalCEC .modal-footer {
        border-top: 1px solid rgba(0, 107, 49, 0.1);
        padding: 1rem 1.35rem;
        background: #fff;
        gap: 0.65rem;
    }
    #modalCEC .sifec-registre-btn-submit {
        border: none;
        border-radius: 999px;
        padding: 0.5rem 1.35rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%);
        box-shadow: 0 2px 10px rgba(0, 107, 49, 0.22);
    }
    #modalCEC .sifec-registre-btn-submit:hover {
        color: #fff;
        filter: brightness(1.03);
    }
    #modalCEC .sifec-registre-btn-close {
        border-radius: 999px;
        padding: 0.5rem 1.25rem;
        font-weight: 600;
        border: 1.5px solid #d1d5db;
        background: #fff;
        color: #4b5563;
    }
    #modalCEC .sifec-registre-btn-close:hover {
        background: #f9fafb;
        border-color: #9ca3af;
        color: #374151;
    }
    #modalCEC .invalid-feedback {
        font-size: 0.82rem;
    }
    /* Filtres tribunal (registre.tribunal) */
    .sifec-registre-filtres-tribunal {
        background: linear-gradient(180deg, #f8faf8 0%, #fff 100%);
        border: 1px solid rgba(0, 107, 49, 0.14);
        border-radius: 14px;
        padding: 1.1rem 1.25rem;
        margin-bottom: 1.25rem;
    }
    .sifec-registre-filtres-tribunal .sl-filter-label {
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        margin-bottom: 0.35rem;
    }
    /* Tableau tribunal : tient dans la largeur utile, sans scroll horizontal inutile */
    .table-responsive.sifec-registre-tribunal-table-host {
        overflow-x: visible;
        -webkit-overflow-scrolling: auto;
    }
    #table-registres-tribunal.sifec-registre-table-tribunal {
        table-layout: fixed;
        width: 100%;
        max-width: 100%;
        font-size: 0.82rem;
    }
    .sifec-registre-table-tribunal thead th {
        white-space: normal;
        vertical-align: middle;
        background: #f8faf8;
        border-bottom: 2px solid rgba(0, 107, 49, 0.18);
        line-height: 1.25;
        font-size: 0.78rem;
        font-weight: 600;
        /* Ne pas couper les mots d’un seul token (ex. « ACTION » lettre par lettre) */
        word-break: normal;
        overflow-wrap: break-word;
        hyphens: manual;
    }
    .sifec-registre-table-tribunal tbody td {
        vertical-align: middle;
        word-break: normal;
        overflow-wrap: normal;
        padding: 0.4rem 0.3rem;
    }
    /* Longs libellés de mairie : coupure seulement si nécessaire */
    .sifec-registre-table-tribunal td.col-cec {
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .sifec-registre-table-tribunal th.col-tribunal-num,
    .sifec-registre-table-tribunal td.col-tribunal-num {
        width: 2.25rem;
        text-align: center;
    }
    .sifec-registre-table-tribunal th.col-tribunal-lib,
    .sifec-registre-table-tribunal td.col-tribunal-lib {
        width: 13%;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .sifec-registre-table-tribunal th.col-tribunal-type,
    .sifec-registre-table-tribunal td.col-tribunal-type {
        width: 9%;
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .sifec-registre-table-tribunal th.col-cec,
    .sifec-registre-table-tribunal td.col-cec {
        width: 20%;
    }
    .sifec-registre-table-tribunal th.col-date {
        width: 8.5%;
        white-space: normal;
        text-align: center;
        line-height: 1.2;
        padding: 0.35rem 0.2rem;
    }
    .sifec-registre-table-tribunal td.col-date {
        white-space: nowrap;
        text-align: center;
    }
    .sifec-registre-table-tribunal th.col-acte-n {
        width: 5.5%;
        white-space: normal;
        text-align: center;
        line-height: 1.2;
        padding: 0.35rem 0.2rem;
    }
    .sifec-registre-table-tribunal td.col-acte-n {
        white-space: nowrap;
        text-align: center;
    }
    .sifec-registre-table-tribunal th.col-acte-transcrit {
        width: 9%;
        min-width: 5.5rem;
        white-space: nowrap;
        text-align: center;
        line-height: 1.2;
        padding: 0.35rem 0.2rem;
    }
    .sifec-registre-table-tribunal td.col-acte-transcrit {
        white-space: nowrap;
        text-align: center;
    }
    .sifec-registre-table-tribunal th.col-statut,
    .sifec-registre-table-tribunal td.col-statut {
        width: 10%;
    }
    .sifec-registre-table-tribunal th.col-select,
    .sifec-registre-table-tribunal td.col-select {
        width: 2.5rem;
        text-align: center;
        vertical-align: middle;
        padding-left: 0.2rem;
        padding-right: 0.2rem;
    }
    .sifec-registre-table-tribunal th.col-actions,
    .sifec-registre-table-tribunal td.col-actions {
        width: 5.5rem;
        min-width: 5.5rem;
        white-space: nowrap;
        text-align: right;
        vertical-align: middle;
        overflow: visible;
    }
    .sifec-registre-table-tribunal td.col-statut .badge {
        font-size: 0.72rem;
        font-weight: 600;
        white-space: normal;
        text-align: left;
        display: inline-block;
        max-width: 100%;
    }
    .sifec-registre-bulk-bar {
        padding: 0.65rem 0.9rem;
        background: #f0fdf4;
        border: 1px solid rgba(0, 158, 73, 0.22);
        border-radius: 12px;
        margin-bottom: 1rem;
    }
</style>

@endsection

@section('corps')
<div class="page-sifec-index sifec-registre-page">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-book me-2 text-success opacity-90"></i>
                        @if(!empty($vueTribunalRegistres))
                            Registres des centres d’état civil (votre juridiction)
                        @else
                            Liste des registres de l'état civil
                        @endif
                    </h4>
                    @can("module.registre.create")
                    @if(empty($vueTribunalRegistres))
                    <button type="button" class="sifec-registre-btn-add" data-bs-toggle="modal" data-bs-target="#modalCEC">
                        <i class="fas fa-plus-circle" aria-hidden="true"></i> Ajouter un registre
                    </button>
                    @endif
                    @endcan
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @if(!empty($vueTribunalRegistres))
                            <form method="get" action="{{ route('registre.tribunal') }}" class="sifec-registre-filtres-tribunal">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="sl-filter-label">Type de registre</div>
                                        <select name="code_type_registre" class="form-select form-control">
                                            <option value="">Tous</option>
                                            @foreach($typeRegistres as $tr)
                                                <option value="{{ $tr->code_type_registre }}" {{ request('code_type_registre') == $tr->code_type_registre ? 'selected' : '' }}>
                                                    {{ $tr->lib_type_registre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="sl-filter-label">Centre d’état civil</div>
                                        <select name="code_institution" class="form-select form-control">
                                            <option value="">Tous (juridiction)</option>
                                            @foreach($centresEtatCivilJuridiction as $cec)
                                                <option value="{{ $cec->code_institution }}" {{ request('code_institution') == $cec->code_institution ? 'selected' : '' }}>
                                                    {{ $cec->lib_institution }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="sl-filter-label">Année</div>
                                        <select name="annee" class="form-select form-control">
                                            <option value="">Toutes</option>
                                            @foreach($anneesFiltre as $y)
                                                <option value="{{ $y }}" {{ (string) request('annee') === (string) $y ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-lg-2">
                                        <div class="sl-filter-label">Statut</div>
                                        <select name="statut_registre" class="form-select form-control">
                                            <option value="">Tous</option>
                                            <option value="en_attente_validation" {{ request('statut_registre') === 'en_attente_validation' ? 'selected' : '' }}>En attente de validation</option>
                                            <option value="actif" {{ request('statut_registre') === 'actif' ? 'selected' : '' }}>Activé (paraphé)</option>
                                            <option value="cloture" {{ request('statut_registre') === 'cloture' ? 'selected' : '' }}>Clôturé</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 col-lg-6">
                                        <div class="sl-filter-label">Libellé du registre</div>
                                        <input type="text" name="recherche" class="form-control" value="{{ request('recherche') }}" placeholder="Rechercher dans le libellé…">
                                    </div>
                                    <div class="col-md-4 col-lg-6 d-flex flex-wrap gap-2 justify-content-lg-end pt-1 pt-lg-0">
                                        <button type="submit" class="btn btn-success px-4 fw-semibold" style="border-radius:10px;">
                                            <i class="fas fa-filter me-1"></i> Filtrer
                                        </button>
                                        <a href="{{ route('registre.tribunal') }}" class="btn btn-outline-secondary" style="border-radius:10px;">
                                            <i class="fas fa-redo me-1"></i> Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </form>
                            @endif

                            @if(!empty($vueTribunalRegistres))
                                @can('module.fonctionnalites.parapher')
                                <div class="sifec-registre-bulk-bar d-flex flex-wrap align-items-center gap-3">
                                    <button type="button" class="btn btn-success px-4 fw-semibold" id="btn-parapher-selection" style="border-radius:10px;" disabled>
                                        <i class="fas fa-file-signature me-1"></i> Parapher la sélection (<span id="registre-bulk-count">0</span>)
                                    </button>
                                    <span class="small text-muted mb-0">Sélectionnez les registres en attente, puis paraphez le lot par signature électronique.</span>
                                </div>
                                @endcan
                            @endif

                            <div class="table-responsive @if(!empty($vueTribunalRegistres)) sifec-registre-tribunal-table-host @endif">

                                <table @if(!empty($vueTribunalRegistres)) id="table-registres-tribunal" class="table table-bordered table-hover align-middle mb-0 w-100 sifec-registre-table-tribunal" @else id="example" class="display" @endif>
                                    <thead>
                                        <tr>
                                            @if(!empty($vueTribunalRegistres))
                                                @can('module.fonctionnalites.parapher')
                                                <th class="col-select">
                                                    <input type="checkbox" class="form-check-input mx-auto d-block" id="registre-bulk-select-all" title="Tout sélectionner" aria-label="Tout sélectionner">
                                                </th>
                                                @endcan
                                            @endif
                                            <th @if(!empty($vueTribunalRegistres)) class="col-tribunal-num" @endif>N°</th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-tribunal-lib" @endif>Registre</th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-tribunal-type" @endif>Type registre</th>
                                            @if(!empty($vueTribunalRegistres))
                                            <th class="col-cec">Centre d’état civil</th>
                                            @endif
                                            <th @if(!empty($vueTribunalRegistres)) class="col-date" title="Date d’ouverture du registre" @endif>
                                                @if(!empty($vueTribunalRegistres)) Ouverture @else Date ouverture @endif
                                            </th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-date" title="Date de fermeture du registre" @endif>
                                                @if(!empty($vueTribunalRegistres)) Fermeture @else Date fermeture @endif
                                            </th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-acte-n" title="Nombre d’actes prévus dans le registre" @endif>
                                                @if(!empty($vueTribunalRegistres)) Prévus @else Nombre d'acte prévu @endif
                                            </th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-acte-transcrit" title="Nombre d’actes déjà transcrits" @endif>
                                                @if(!empty($vueTribunalRegistres)) Transcrits @else Nombre d'acte transcrit @endif
                                            </th>
                                            <th @if(!empty($vueTribunalRegistres)) class="col-statut" @endif>Statut</th>
                                            <th class="@if(!empty($vueTribunalRegistres)) col-actions @endif">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($registres as $registre)
                                        <tr width="100%">
                                            @if(!empty($vueTribunalRegistres))
                                                @can('module.fonctionnalites.parapher')
                                                <td class="col-select">
                                                    @if($registre->sceau == null && (string) $registre->statut !== '1')
                                                        <input type="checkbox" class="form-check-input registre-bulk-cb mx-auto d-block" value="{{ $registre->code_registre }}" aria-label="Sélectionner {{ $registre->lib_registre }}">
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                                @endcan
                                            @endif
                                            <td @if(!empty($vueTribunalRegistres)) class="col-tribunal-num" @endif>{{ $i++ }}</td>
                                            <td @if(!empty($vueTribunalRegistres)) class="col-tribunal-lib" @endif>{{ $registre->lib_registre }}</td>
                                            <td @if(!empty($vueTribunalRegistres)) class="col-tribunal-type" @endif>{{ $registre->typeRegistre->lib_type_registre }}</td>
                                            @if(!empty($vueTribunalRegistres))
                                            <td class="col-cec">
                                                <span class="d-inline-block w-100">{{ \Illuminate\Support\Str::limit($registre->institutionUser?->institution?->lib_institution ?? '—', 80) }}</span>
                                            </td>
                                            @endif
                                            <td @if(!empty($vueTribunalRegistres)) class="col-date text-center" @endif>{{ $registre->date_ouverture ? date('d-m-Y', strtotime($registre->date_ouverture)) : '—' }}</td>
                                            <td @if(!empty($vueTribunalRegistres)) class="col-date text-center" @endif>{{ $registre->date_fermeture ? date('d-m-Y', strtotime($registre->date_fermeture)) : '—' }}</td>
                                            <td @if(!empty($vueTribunalRegistres)) class="col-acte-n text-center" @endif>{{ $registre->nombre_acte_prevu}}</td>
                                            <td @if(!empty($vueTribunalRegistres)) class="col-acte-transcrit text-center" @endif>{{ $registre->nombre_acte_transcrit }}</td>
                                            <td class="@if(!empty($vueTribunalRegistres)) col-statut @endif">
                                                @if($registre->statut == "0" && $registre->approbation_tribunal == null)
                                                    <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="registre en attente de validation">En cours de validation</span>
                                                @endif
                                                @if($registre->statut == "1" && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span>
                                                @endif

                                                @if($registre->nombre_acte_transcrit == $registre->nombre_acte_prevu && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;" title="Ce registre est remplit">[Remplit]</span>
                                                @endif
                                                @if($registre->signature_cloture_cec != "")
                                                <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="Ce registre est clôturé">Clôturé</span>
                                                @endif
                                            </td>
                                            <td class="@if(!empty($vueTribunalRegistres)) col-actions @endif">
                                                <div class="dropdown d-inline-block">
                                                    <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                    @if($registre->sceau == null)
                                                        @can('module.fonctionnalites.parapher')
                                                            <a href="{{ $registre->code_registre }}" class="dropdown-item show-validation-modal">Parapher</a>
                                                        @endcan
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0001")
                                                        <a  href="{{ route('registre.naissance', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0002")
                                                        <a  href="{{ route('registre.mariage', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0004")
                                                        <a  href="{{ route('registre.deces', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->statut == 1)
                                                        {{-- <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-cloturer-modal">Clôturer</a> --}}
                                                    @endif
                                                    @if(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) == 0)
                                                        {{-- @can('module.fonctionnalites.parapher') --}}
                                                            <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-add-leaflet-modal">Ajouter des feuillets</a>
                                                        {{-- @endcan --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @if(empty($vueTribunalRegistres))
                                    <tfoot>
                                        <tr>
                                            <th>N°</th>
                                            <th>Registre</th>
                                            <th>Type registre</th>
                                            <th>Date ouverture</th>
                                            <th>Date fermeture</th>
                                            <th>Nombre d'acte prévu</th>
                                            <th>Nombre d'acte transcrit</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


      <!-- Large modal -->
    <div class="modal fade" id="modalCEC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalRegistreCecTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalRegistreCecTitle">
                        <i class="fas fa-file-signature me-2 opacity-90"></i>Nouveau registre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <form id="form-registre-create" action="{{ route('registre.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-muted mb-3 mb-md-4">Renseignez le type, le libellé généré et le nombre d’actes prévus pour ce registre.</p>
                        <div class="sifec-registre-field">
                            <label class="form-label" for="codetyperegistre">Type de registre <span class="text-danger">*</span></label>
                            <select name="code_type_registre" class="form-select wide" id="codetyperegistre" required>
                                <option value="" disabled {{ old('code_type_registre') ? '' : 'selected' }}>Choisissez un type</option>
                                @if(Auth::user()->affectationActive()->institution->lieu->localiteParent->pompes_funebres == 0)
                                    @foreach (Modules\Referentiel\Entities\TypeRegistre::all() as $item)
                                        <option value="{{ $item->code_type_registre }}" {{ old('code_type_registre') == $item->code_type_registre ? 'selected' : '' }}>{{ $item->lib_type_registre }}</option>
                                    @endforeach
                                @else
                                    @foreach ($typeRegistres as $item)
                                        <option value="{{ $item->code_type_registre }}" {{ old('code_type_registre') == $item->code_type_registre ? 'selected' : '' }}>{{ $item->lib_type_registre }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="sifec-registre-field">
                            <label class="form-label" for="typeregistre">Libellé <span class="text-danger">*</span></label>
                            <input id="typeregistre" type="text" class="form-control @error('lib_registre') is-invalid @enderror" value="{{ old('lib_registre') }}" name="lib_registre" readonly required>
                            <input type="hidden" id="prefix" name="prefix">
                            @error('lib_registre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="sifec-registre-field">
                            <label class="form-label" for="nbre_acte_prevu">Nombre d’actes prévus <span class="text-danger">*</span></label>
                            <input id="nbre_acte_prevu" class="form-control @error('nbre_acte_prevu') is-invalid @enderror" name="nbre_acte_prevu" type="number" min="1" step="1" value="{{ old('nbre_acte_prevu') }}" placeholder="Ex. 100">
                            @error('nbre_acte_prevu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-none">
                            <label class="form-label">État <span class="text-danger">*</span></label>
                            <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                <option value="0" {{ old('statut') == '0' || old('statut') === null ? 'selected' : '' }}>Désactivé</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer flex-nowrap justify-content-end">
                        <button type="button" class="btn sifec-registre-btn-close" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn sifec-registre-btn-submit" id="btn-registre-create-submit">
                            <i class="fas fa-check me-1"></i> Valider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- DEBUT VALIDATION REGISTRE (paraphe électronique) --}}
    <div class="modal fade" id="modal-registre-paraphage" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-semibold" id="modal-paraphage-title">
                        <i class="fas fa-file-signature me-2" style="color:#006B31;"></i>Paraphe électronique du registre
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body pt-3">
                    <input type="hidden" id="code_registre">
                    <input type="hidden" id="paraphe_mode" value="single">
                    <input type="hidden" id="paraphe_bulk_codes_json" value="">
                    <p id="paraphe-bulk-summary" class="small fw-semibold text-success mb-3 d-none" role="status"></p>

                    <div class="alert alert-info py-2 mb-3">
                        <p class="mb-2 small fw-semibold mb-1">Comment procéder</p>
                        <ol class="small mb-2 ps-3">
                            <li>Vérifiez qu’il s’agit du bon registre (ou de la bonne sélection).</li>
                            <li>Sélectionnez votre fichier certificat <strong>.p12</strong> et saisissez sa passphrase.</li>
                            <li>Cliquez sur <strong>Parapher électroniquement</strong> : votre identité valide et scelle le paraphe.</li>
                            <li>Le registre passe au statut <strong>Activé</strong> et le centre d’état civil est informé.</li>
                        </ol>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Aucun code SMS n’est demandé. Utilisez le fichier <code>.p12</code> téléchargé depuis votre profil
                            et la passphrase affichée à ce moment-là (pas un ancien fichier ni un fichier de test).
                            Le tribunal doit avoir son cachet institutionnel configuré.
                        </p>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label small fw-semibold" for="paraphe_p12_file">Certificat électronique (.p12)</label>
                            <input type="file" class="form-control form-control-sm" id="paraphe_p12_file" accept=".p12,.pfx,application/x-pkcs12">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold" for="paraphe_p12_pin">Passphrase</label>
                            <input type="password" class="form-control form-control-sm" id="paraphe_p12_pin" autocomplete="off" placeholder="Passphrase du certificat">
                        </div>
                    </div>

                    <div id="paraphe-sign-feedback" class="alert alert-warning py-2 small d-none mb-0" role="status"></div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-sm text-white px-4" id="btn-validate"
                            style="background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%); border: none;">
                        <i class="fas fa-signature me-1"></i> Parapher électroniquement
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE VALIDATION REGISTRE --}}

    {{-- DEBUT CLÔTURER REGISTRE --}}
    <div class="modal fade" id="modal-registre-cloturer" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Clôture du registre </label>
                            <input type="text" readonly class="form-control" id="type_registre">
                        </div>
                        <div class="mb-2 col-md-12">
                            <input type="hidden" id="coderegistre">
                            <label class="form-label">Date de clôture<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_cloture" required>
                        </div>

                        <span class="text-success"><i>Veuillez saisir la date de clôture du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-cloturer">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE CLÔTURER REGISTRE --}}




     {{-- DEBUT AJOUT FEUILLETS DU REGISTRE --}}
     <div class="modal fade" id="modal-registre-add-leaflet" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title" id="libtyperegistre"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Ajouter des feuillets du registre </label>
                            <input type="number" class="form-control" id="nbreFeuillets" min="1">
                        </div>

                        <span id="msg_erreur"><i style="color: red">Veuillez saisir le nombre de feuillets du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-add-feuillets">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE AJOUT FEUILLETS DU REGISTRE --}}
</div>
</div>
</div>
@endsection
@section('scripts')
      @if(empty($vueTribunalRegistres))
      <!-- Datatable (liste CEC uniquement — évite double en-tête / pied sur la vue tribunal) -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>
      @endif
      <script src="{{ asset('js/vendor/forge.min.js') }}"></script>
      <script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
      <script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260717c"></script>

      <script>
        $(function() {
            $('#form-registre-create').on('submit', function () {
                var btn = document.getElementById('btn-registre-create-submit');
                if (btn && typeof window.sifecBtnLoading === 'function') {
                    window.sifecBtnLoading(btn, 'Enregistrement…');
                }
            });
            $('#modalCEC').on('hidden.bs.modal', function () {
                var btn = document.getElementById('btn-registre-create-submit');
                if (btn && typeof window.sifecBtnReset === 'function') {
                    window.sifecBtnReset(btn, '<i class="fas fa-check me-1"></i> Valider');
                }
            });

            $("#codetyperegistre").on("change", function() {

                var codetyperegistre = $(this).val();

                    if(codetyperegistre != null || codetyperegistre != ''){

                        var lib = $("#codetyperegistre option:selected").text();
                        $("#typeregistre").val("REGISTRE DE "+lib);
                        $("#prefix").val("R.A."+lib.substr(0,1)+"_");
                    }
                });

                function getRegistreCsrfToken() {
                    return $('meta[name="csrf-token"]').attr('content') || '';
                }

                function updateRegistreBulkSelectionUi() {
                    var $bulkAction = $('#btn-parapher-selection');
                    if (!$bulkAction.length) {
                        return;
                    }
                    var checked = $('.registre-bulk-cb:checked').length;
                    $('#registre-bulk-count').text(checked);
                    $bulkAction.prop('disabled', checked < 1);
                    var total = $('.registre-bulk-cb').length;
                    var el = document.getElementById('registre-bulk-select-all');
                    if (el) {
                        el.indeterminate = checked > 0 && checked < total;
                        el.checked = total > 0 && checked === total;
                    }
                }

                $(document).on('change', '.registre-bulk-cb', updateRegistreBulkSelectionUi);
                $('#registre-bulk-select-all').on('change', function () {
                    var on = $(this).prop('checked');
                    $('.registre-bulk-cb').prop('checked', on);
                    updateRegistreBulkSelectionUi();
                });

                function openGuotParapheModal(mode, codes) {
                    $('#paraphe-sign-feedback').addClass('d-none').empty();
                    $('#paraphe_p12_file').val('');
                    $('#paraphe_p12_pin').val('');
                    $('#paraphe_mode').val(mode);
                    if (mode === 'bulk') {
                        $('#paraphe_bulk_codes_json').val(JSON.stringify(codes || []));
                        $('#code_registre').val('');
                        var n = (codes || []).length;
                        $('#modal-paraphage-title').html('<i class="fas fa-file-signature me-2" style="color:#006B31;"></i>Paraphe électronique (' + n + ' registre' + (n > 1 ? 's' : '') + ')');
                        $('#paraphe-bulk-summary').removeClass('d-none').text(n + ' registre(s) seront paraphés par signature électronique.');
                    } else {
                        $('#paraphe_bulk_codes_json').val('');
                        $('#code_registre').val(codes);
                        $('#modal-paraphage-title').html('<i class="fas fa-file-signature me-2" style="color:#006B31;"></i>Paraphe électronique du registre');
                        $('#paraphe-bulk-summary').addClass('d-none').text('');
                    }
                    $('#modal-registre-paraphage').modal('show');
                }

                $('#btn-parapher-selection').on('click', function () {
                    var codes = $('.registre-bulk-cb:checked').map(function () { return $(this).val(); }).get();
                    if (!codes.length) {
                        flashAlert('Sélection', 'error', 'Cochez au moins un registre.');
                        return;
                    }
                    openGuotParapheModal('bulk', codes);
                });

                $('a.show-validation-modal').on('click', function () {
                    openGuotParapheModal('single', $(this).attr('href'));
                    return false;
                });

                $('#modal-registre-paraphage').on('hidden.bs.modal', function () {
                    $('#paraphe-sign-feedback').addClass('d-none').empty();
                    $('#paraphe_p12_file').val('');
                    $('#paraphe_p12_pin').val('');
                    $('#paraphe_mode').val('single');
                    $('#paraphe_bulk_codes_json').val('');
                    $('#code_registre').val('');
                    $('#modal-paraphage-title').html('<i class="fas fa-file-signature me-2" style="color:#006B31;"></i>Paraphe électronique du registre');
                    $('#paraphe-bulk-summary').addClass('d-none').text('');
                    var sa = document.getElementById('registre-bulk-select-all');
                    if (sa) {
                        sa.indeterminate = false;
                        sa.checked = false;
                    }
                    $('.registre-bulk-cb').prop('checked', false);
                    updateRegistreBulkSelectionUi();
                    $('#btn-validate').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Parapher électroniquement');
                });

                function resetParapheBtn($btn) {
                    $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Parapher électroniquement');
                }

                function showParapheError(msg) {
                    $('#paraphe-sign-feedback').removeClass('d-none').text(msg);
                    flashAlert('Réponse', 'error', msg);
                }

                function ajaxJson(url, data) {
                    return $.ajax({
                        url: url,
                        method: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify(data),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': getRegistreCsrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json'
                        }
                    });
                }

                $('#btn-validate').on('click', async function () {
                    var mode = $('#paraphe_mode').val();
                    var $btn = $(this);
                    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');
                    $('#paraphe-sign-feedback').addClass('d-none').empty();

                    var codes = [];
                    if (mode === 'bulk') {
                        try { codes = JSON.parse($('#paraphe_bulk_codes_json').val() || '[]'); } catch (e) { codes = []; }
                        if (!codes.length) {
                            resetParapheBtn($btn);
                            flashAlert('Sélection', 'error', 'Sélection groupée invalide.');
                            return false;
                        }
                    } else {
                        var code = $('#code_registre').val();
                        if (!code) {
                            resetParapheBtn($btn);
                            flashAlert('Erreur', 'error', 'Identifiant registre manquant.');
                            return false;
                        }
                        codes = [code];
                    }

                    var fileInput = document.getElementById('paraphe_p12_file');
                    var pin = $('#paraphe_p12_pin').val();
                    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
                        resetParapheBtn($btn);
                        showParapheError('Sélectionnez votre fichier certificat (.p12).');
                        return false;
                    }
                    if (!pin || !String(pin).trim()) {
                        resetParapheBtn($btn);
                        showParapheError('Saisissez la passphrase de votre certificat.');
                        return false;
                    }
                    if (typeof window.SifecP12Sign === 'undefined') {
                        resetParapheBtn($btn);
                        showParapheError('Bibliothèque de signature non chargée. Rechargez la page.');
                        return false;
                    }

                    try {
                        var prep = await ajaxJson("{{ route('registre.paraphe.prepare') }}", { codes: codes });
                        if (String(prep.code) !== '200' || !prep.token || !prep.items || !prep.items.length) {
                            resetParapheBtn($btn);
                            showParapheError((prep && prep.message) ? prep.message : 'Échec de la préparation du paraphe.');
                            return false;
                        }

                        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
                        var p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
                        var signatures = [];
                        for (var i = 0; i < prep.items.length; i++) {
                            var item = prep.items[i];
                            var signatureHex = await window.SifecP12Sign.signHashHex(
                                p12Binary,
                                pin,
                                item.document_hash,
                                prep.expected_serial || null
                            );
                            signatures.push({
                                code_registre: item.code_registre,
                                signature_hex: signatureHex
                            });
                        }

                        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Validation…');
                        var fin = await ajaxJson("{{ route('registre.paraphe.finalize') }}", {
                            token: prep.token,
                            signatures: signatures
                        });

                        resetParapheBtn($btn);
                        if (String(fin.code) === '200') {
                            flashAlert('Réponse', 'success', fin.message || 'Paraphe effectué.');
                            $('#modal-registre-paraphage').modal('hide');
                            setTimeout(function () { location.reload(); }, 1500);
                            return false;
                        }
                        showParapheError((fin && fin.message) ? fin.message : 'Échec du paraphe électronique.');
                    } catch (err) {
                        resetParapheBtn($btn);
                        var msg = 'Erreur lors de la signature électronique.';
                        if (err && err.responseJSON && err.responseJSON.message) {
                            msg = err.responseJSON.message;
                        } else if (err && err.message) {
                            msg = err.message;
                        }
                        showParapheError(msg);
                    }

                    return false;
                });

            $("a.show-cloturer-modal").on("click", function(){
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#type_registre").val(typeregistre);

                $("#modal-registre-cloturer").modal("show");
                return false;
            });

            $("#btn-cloturer").on("click",function(){
                var codereg = $("#coderegistre").val();
                var datecloture = $("#date_cloture").val();
                var route = "{{ route('registre.cloture') }}";
                var data = {
                    code_registre:codereg,
                    date_cloture:datecloture
                };

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-cloturer").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 4000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });



             //Affichage modal ajout de feuillets du registre
             $("a.show-add-leaflet-modal").on("click", function(){
                $("#msg_erreur").hide();
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#libtyperegistre").html("REGISTRE DE "+typeregistre);

                $("#modal-registre-add-leaflet").modal("show");
                return false;
            });

            //Traitement ajout de feuillets du registre
            $("#btn-add-feuillets").on("click",function(){
                var codereg = $("#coderegistre").val();
                var nbrefeuillets = $("#nbreFeuillets").val();
                var route = "{{ route('registre.add.feuillets') }}";
                var data = {
                    code_registre:codereg,
                    nbrefeuillets:nbrefeuillets
                };
                if(nbrefeuillets == "" || nbrefeuillets == null){
                    $("#msg_erreur").show(300);
                    return false;
                }

                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-add-leaflet").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 6000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });

        });
      </script>




@endsection
