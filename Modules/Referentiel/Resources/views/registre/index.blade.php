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
                                    <span class="small text-muted mb-0">Sélectionnez les registres en attente, puis un seul code OTP paraphé le lot (SMS et e-mail si configuré).</span>
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
    {{-- DEBUT VALIDATION REGISTRE (OTP paraphe — charte SIFEC + compteurs) --}}
    <div class="modal fade" id="modal-registre-paraphage" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-semibold" id="modal-paraphage-title">Validation du registre (paraphe)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer">
                    </button>
                </div>
                <div class="modal-body pt-3">
                    <input type="hidden" id="code_registre">
                    <input type="hidden" id="paraphe_mode" value="single">
                    <input type="hidden" id="paraphe_bulk_codes_json" value="">
                    <p id="paraphe-bulk-summary" class="small fw-semibold text-success mb-3 d-none" role="status"></p>
                    <p class="small text-muted mb-3">
                        Le code reçu par SMS est valable <strong>1 minute</strong>.
                        Vous disposez de <strong>3 tentatives</strong> par code.
                        Après 3 échecs, attendez <strong>3 minutes</strong> avant de pouvoir en demander un nouveau.
                    </p>
                    <div id="paraphe-otp-alert" class="alert alert-secondary small py-2 mb-3 d-none" role="status"></div>

                    {{-- Jauge seule sur une ligne (fond neutre) + libellé sous la jauge (masqués ensemble en temporisation) --}}
                    <div class="mb-3" id="otp-expiry-meter-section">
                        <div class="sifec-otp-meter sifec-otp-meter--surface w-100"
                             id="otp-expiry-progress-wrap"
                             role="progressbar"
                             aria-label="Temps restant avant expiration du code OTP"
                             aria-live="polite"
                             aria-valuemin="0"
                             aria-valuemax="60"
                             aria-valuenow="60"
                             aria-valuetext="60 secondes restantes">
                            <div class="sifec-otp-meter__track">
                                <div class="sifec-otp-meter__fill" id="otp-expiry-progress-bar" style="width: 100%;"></div>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0" id="otp-expiry-hint"></p>
                        <span id="otp-countdown-expiry" class="visually-hidden">—</span>
                    </div>

                    {{-- Tentatives : simple ligne de texte, sans encart coloré --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3 pb-2 border-bottom" id="otp-attempts-line">
                        <span class="small text-muted mb-0">Tentatives (code incorrect ou renvoi du code)</span>
                        <span class="fw-bold sifec-otp-attempts-count mb-0">
                            <span id="otp-attempts-used">0</span><span class="text-muted fw-normal"> / </span><span id="otp-max-attempts">3</span>
                        </span>
                    </div>
                    <div id="otp-lockout-wrap" class="alert alert-warning small py-2 mb-3 d-none" role="alert">
                        <strong>Temporisation active.</strong>
                        Nouveau code ou nouvelle saisie possible dans
                        <span class="sifec-otp-badge sifec-otp-badge--timer sifec-otp-badge--compact d-inline-flex ms-1 align-middle" role="status">
                            <span class="sifec-otp-badge__mesh" aria-hidden="true">
                                <span class="sifec-otp-badge__orb sifec-otp-badge__orb--light"></span>
                                <span class="sifec-otp-badge__blob sifec-otp-badge__blob--1"></span>
                            </span>
                            <span class="sifec-otp-badge__label"><span id="otp-lockout-countdown">—</span></span>
                        </span>.
                    </div>
                    <div id="otp-expired-wrap" class="alert alert-danger small py-2 mb-3 d-none" role="alert">
                        Ce code a expiré. Utilisez «&nbsp;Renvoyer le code&nbsp;» pour en recevoir un nouveau (sauf pendant une temporisation).
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="otp_paraphage">Code de validation (6 chiffres)<span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control form-control-lg text-center fw-bold mx-auto"
                               id="otp_paraphage"
                               maxlength="6"
                               minlength="6"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               autocomplete="one-time-code"
                               placeholder="• • • • • •"
                               title="Saisissez uniquement 6 chiffres (0 à 9)"
                               style="letter-spacing: 0.45em; font-size: 1.35rem; max-width: 22rem;"
                               required>
                    </div>
                    <p class="small text-muted mb-2">Saisissez le code reçu par SMS (et par e-mail si configuré).</p>
                    <p class="small mb-0">
                        <span class="text-muted">Code non reçu ?</span>
                        <a href="#" id="resend-otp-link" class="fw-semibold" style="color: #009E49;">Renvoyer le code</a>
                        <span id="resend-otp-disabled" class="text-muted d-none"></span>
                    </p>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-sm text-white px-4" id="btn-validate" style="background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%); border: none;">Valider</button>
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

                var paraphOtpExpiryTimer = null;
                var paraphOtpLockoutTimer = null;
                var paraphOtpMaxAttempts = 3;
                var paraphOtpExpiryTotalSec = 60;

                function clearRegistreParaphTimers() {
                    if (paraphOtpExpiryTimer) {
                        clearInterval(paraphOtpExpiryTimer);
                        paraphOtpExpiryTimer = null;
                    }
                    if (paraphOtpLockoutTimer) {
                        clearInterval(paraphOtpLockoutTimer);
                        paraphOtpLockoutTimer = null;
                    }
                }

                function formatParaphMmSs(totalSeconds) {
                    totalSeconds = Math.max(0, parseInt(totalSeconds, 10) || 0);
                    var m = Math.floor(totalSeconds / 60);
                    var s = totalSeconds % 60;
                    return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                }

                function updateOtpExpiryProgressVisual(left) {
                    var total = paraphOtpExpiryTotalSec > 0 ? paraphOtpExpiryTotalSec : 60;
                    var safeLeft = Math.max(0, parseInt(left, 10) || 0);
                    var pct = total > 0 ? Math.max(0, Math.min(100, (safeLeft / total) * 100)) : 0;
                    var $bar = $('#otp-expiry-progress-bar');
                    var $wrap = $('#otp-expiry-progress-wrap');
                    $bar.css('width', pct + '%');
                    $wrap.attr('aria-valuenow', safeLeft);
                    $wrap.attr('aria-valuemax', total);
                    var vt = safeLeft <= 0 ? 'Code expiré' : (safeLeft + (safeLeft > 1 ? ' secondes restantes' : ' seconde restante'));
                    $wrap.attr('aria-valuetext', vt);
                    $('#otp-countdown-expiry').text(formatParaphMmSs(safeLeft));
                    var urgent = safeLeft > 0 && safeLeft <= 15;
                    $bar.toggleClass('sifec-otp-meter__fill--urgent', urgent);
                    var $hint = $('#otp-expiry-hint');
                    if (safeLeft > 0) {
                        $hint.text(safeLeft + (safeLeft > 1 ? ' secondes restantes' : ' seconde restante'))
                            .removeClass('text-danger fw-semibold')
                            .addClass('text-muted');
                    } else {
                        $hint.text('Délai écoulé — demandez un nouveau code.')
                            .removeClass('text-muted')
                            .addClass('text-danger fw-semibold');
                    }
                }

                /** Affiche actions déjà comptées / maximum (ex. 0/3 puis 1/3 … 3/3) : mauvais code ou renvoi avec code encore valide. */
                function setParaphAttemptsUsedDisplay(used, max) {
                    var $m = $('#modal-registre-paraphage');
                    var u = parseInt(used, 10);
                    var m = parseInt(max, 10);
                    if (isNaN(m) || m < 1) {
                        m = paraphOtpMaxAttempts;
                    }
                    if (isNaN(u) || u < 0) {
                        u = 0;
                    }
                    if (u > m) {
                        u = m;
                    }
                    $m.find('#otp-attempts-used').first().text(u);
                    $m.find('#otp-max-attempts').first().text(m);
                }

                function applyParaphAttemptsFromResponse(response) {
                    if (!response || typeof response !== 'object') {
                        return;
                    }
                    var max = parseInt(response.otp_max_attempts, 10);
                    if (isNaN(max) || max < 1) {
                        max = paraphOtpMaxAttempts;
                    }
                    var used = NaN;
                    if (Object.prototype.hasOwnProperty.call(response, 'attempts_used')) {
                        used = parseInt(response.attempts_used, 10);
                    } else if (Object.prototype.hasOwnProperty.call(response, 'remaining_attempts')) {
                        var rem = parseInt(response.remaining_attempts, 10);
                        if (!isNaN(rem) && rem >= 0) {
                            used = max - rem;
                        }
                    }
                    if (!isNaN(used) && used >= 0) {
                        setParaphAttemptsUsedDisplay(used, max);
                    }
                }

                /** Accepte code API renvoyé en string ou nombre (JSON PHP / jQuery). */
                function paraphOtpResponseCode(response) {
                    if (!response || response.code === undefined || response.code === null) {
                        return NaN;
                    }
                    return parseInt(response.code, 10);
                }

                function showParaphAlert(type, text) {
                    var $a = $('#paraphe-otp-alert');
                    $a.removeClass('d-none alert-secondary alert-success alert-danger alert-warning');
                    if (type === 'success') {
                        $a.addClass('alert-success');
                    } else if (type === 'danger') {
                        $a.addClass('alert-danger');
                    } else if (type === 'warning') {
                        $a.addClass('alert-warning');
                    } else {
                        $a.addClass('alert-secondary');
                    }
                    $a.text(text);
                }

                function hideParaphAlert() {
                    $('#paraphe-otp-alert').addClass('d-none').text('');
                }

                function enableParaphResendUi() {
                    $('#resend-otp-link').removeClass('d-none disabled text-muted');
                    $('#resend-otp-disabled').addClass('d-none').text('');
                }

                function disableParaphResendUi() {
                    $('#resend-otp-link').addClass('d-none');
                    $('#resend-otp-disabled').removeClass('d-none');
                }

                function startParaphExpiryCountdown(totalSec) {
                    clearRegistreParaphTimers();
                    paraphOtpExpiryTotalSec = parseInt(totalSec, 10) || 60;
                    $('#otp-lockout-wrap').addClass('d-none');
                    $('#otp-expired-wrap').addClass('d-none');
                    $('#otp-expiry-meter-section').removeClass('d-none');
                    $('#otp-attempts-line').removeClass('d-none');
                    $('#btn-validate').prop('disabled', false);
                    $('#otp_paraphage').prop('disabled', false).val('');
                    enableParaphResendUi();
                    hideParaphAlert();

                    var left = paraphOtpExpiryTotalSec;
                    updateOtpExpiryProgressVisual(left);
                    paraphOtpExpiryTimer = setInterval(function () {
                        left--;
                        updateOtpExpiryProgressVisual(left);
                        if (left <= 0) {
                            clearInterval(paraphOtpExpiryTimer);
                            paraphOtpExpiryTimer = null;
                            updateOtpExpiryProgressVisual(0);
                            $('#otp-expired-wrap').removeClass('d-none');
                            $('#btn-validate').prop('disabled', true);
                            $('#otp_paraphage').prop('disabled', true);
                            showParaphAlert('warning', 'Le délai de validité du code est écoulé. Renvoyez un nouveau code (si aucune temporisation ne s’affiche).');
                        }
                    }, 1000);
                }

                function startParaphLockoutCountdown(totalSec) {
                    clearRegistreParaphTimers();
                    $('#otp-expiry-meter-section').addClass('d-none');
                    $('#otp-attempts-line').addClass('d-none');
                    $('#otp-expired-wrap').addClass('d-none');
                    $('#otp-lockout-wrap').removeClass('d-none');
                    $('#btn-validate').prop('disabled', true);
                    $('#otp_paraphage').prop('disabled', true).val('');
                    disableParaphResendUi();
                    showParaphAlert('danger', 'Trop de tentatives incorrectes ou temporisation serveur. Patientez avant un nouveau code.');

                    var left = totalSec;
                    $('#otp-lockout-countdown').text(formatParaphMmSs(left));
                    $('#resend-otp-disabled').text('Nouveau code disponible dans ' + formatParaphMmSs(left));

                    paraphOtpLockoutTimer = setInterval(function () {
                        left--;
                        if (left <= 0) {
                            clearInterval(paraphOtpLockoutTimer);
                            paraphOtpLockoutTimer = null;
                            $('#otp-lockout-wrap').addClass('d-none');
                            enableParaphResendUi();
                            $('#btn-validate').prop('disabled', false);
                            $('#otp_paraphage').prop('disabled', false);
                            hideParaphAlert();
                            showParaphAlert('secondary', 'Vous pouvez demander un nouveau code.');
                        } else {
                            $('#otp-lockout-countdown').text(formatParaphMmSs(left));
                            $('#resend-otp-disabled').text('Nouveau code disponible dans ' + formatParaphMmSs(left));
                        }
                    }, 1000);
                }

                function requestParaphOtpForRegistre(code_registre, openModalOnSuccess, isResend) {
                    var url = "{{ route('registre.send.otp', ':id') }}";
                    url = url.replace(':id', code_registre);
                    if (isResend) {
                        url += (url.indexOf('?') === -1 ? '?' : '&') + 'resend=1';
                    }
                    $(".over-loader-page").fadeIn(600);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json'
                    }).done(function (response) {
                        $(".over-loader-page").fadeOut(600);
                        if (String(response.code) === '200') {
                            $("#code_registre").val(code_registre);
                            paraphOtpMaxAttempts = parseInt(response.otp_max_attempts, 10) || 3;
                            var sentUsed = parseInt(response.attempts_used, 10);
                            setParaphAttemptsUsedDisplay(!isNaN(sentUsed) ? sentUsed : 0, paraphOtpMaxAttempts);
                            var validSec = parseInt(response.valid_for_seconds, 10) || 60;
                            if (openModalOnSuccess) {
                                $("#modal-registre-paraphage").modal('show');
                            }
                            startParaphExpiryCountdown(validSec);
                            if (openModalOnSuccess) {
                                showParaphAlert('success', response.message || 'Code envoyé. Saisissez-le avant la fin du compte à rebours.');
                            } else {
                                flashAlert('Code OTP', 'success', response.message || 'Nouveau code envoyé.');
                            }
                        } else if (String(response.code) === '184' && response.retry_after_seconds) {
                            if (openModalOnSuccess) {
                                $("#code_registre").val(code_registre);
                                $("#modal-registre-paraphage").modal('show');
                            }
                            applyParaphAttemptsFromResponse(response);
                            startParaphLockoutCountdown(parseInt(response.retry_after_seconds, 10) || 180);
                            flashAlert('Réponse', 'error', response.message || 'Temporisation active.');
                        } else {
                            flashAlert('Réponse', 'error', response.message || 'Erreur.');
                        }
                    }).fail(function (xhr) {
                        $(".over-loader-page").fadeOut(600);
                        var msg = 'Impossible d\'envoyer le code.';
                        if (xhr.status === 429) {
                            msg = 'Trop de demandes. Réessayez dans une minute.';
                        }
                        flashAlert('Réponse', 'error', msg);
                    });
                }

                function getRegistreCsrfToken() {
                    return $('meta[name="csrf-token"]').attr('content') || '';
                }

                function requestParaphOtpBulk(codes, openModalOnSuccess, isResend) {
                    var url = "{{ route('registre.send.otp.bulk') }}";
                    $(".over-loader-page").fadeIn(600);
                    $.ajax({
                        url: url,
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ codes: codes, resend: !!isResend }),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': getRegistreCsrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json'
                        }
                    }).done(function (response) {
                        $(".over-loader-page").fadeOut(600);
                        if (String(response.code) === '200') {
                            paraphOtpMaxAttempts = parseInt(response.otp_max_attempts, 10) || 3;
                            var sentUsed = parseInt(response.attempts_used, 10);
                            setParaphAttemptsUsedDisplay(!isNaN(sentUsed) ? sentUsed : 0, paraphOtpMaxAttempts);
                            var validSec = parseInt(response.valid_for_seconds, 10) || 60;
                            if (openModalOnSuccess) {
                                $('#modal-registre-paraphage').modal('show');
                            }
                            startParaphExpiryCountdown(validSec);
                            if (openModalOnSuccess) {
                                showParaphAlert('success', response.message || 'Code envoyé. Saisissez-le avant la fin du compte à rebours.');
                            } else {
                                flashAlert('Code OTP', 'success', response.message || 'Nouveau code envoyé.');
                            }
                        } else if (String(response.code) === '184' && response.retry_after_seconds) {
                            if (openModalOnSuccess) {
                                $('#modal-registre-paraphage').modal('show');
                            }
                            applyParaphAttemptsFromResponse(response);
                            startParaphLockoutCountdown(parseInt(response.retry_after_seconds, 10) || 180);
                            flashAlert('Réponse', 'error', response.message || 'Temporisation active.');
                        } else {
                            flashAlert('Réponse', 'error', response.message || 'Erreur.');
                        }
                    }).fail(function (xhr) {
                        $(".over-loader-page").fadeOut(600);
                        var msg = 'Impossible d\'envoyer le code.';
                        if (xhr.status === 429) {
                            msg = 'Trop de demandes. Réessayez dans une minute.';
                        }
                        flashAlert('Réponse', 'error', msg);
                    });
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

                $('#btn-parapher-selection').on('click', function () {
                    var codes = $('.registre-bulk-cb:checked').map(function () { return $(this).val(); }).get();
                    if (!codes.length) {
                        flashAlert('Sélection', 'error', 'Cochez au moins un registre.');
                        return;
                    }
                    $('#paraphe_mode').val('bulk');
                    $('#paraphe_bulk_codes_json').val(JSON.stringify(codes));
                    $('#code_registre').val('');
                    var n = codes.length;
                    $('#modal-paraphage-title').text('Validation groupée (' + n + ' registre' + (n > 1 ? 's' : '') + ')');
                    $('#paraphe-bulk-summary').removeClass('d-none').text(
                        n + ' registre(s) seront paraphés avec le même code OTP.'
                    );
                    requestParaphOtpBulk(codes, true, false);
                });

                function sanitizeOtpParaphInput($field) {
                    var v = ($field.val() || '').replace(/\D/g, '').slice(0, 6);
                    if ($field.val() !== v) {
                        $field.val(v);
                    }
                }

                $('#otp_paraphage').on('input.otpDigits', function () {
                    sanitizeOtpParaphInput($(this));
                });
                $('#otp_paraphage').on('keydown.otpDigits', function (e) {
                    if ($.inArray(e.keyCode, [8, 9, 13, 27, 46, 35, 36, 37, 38, 39, 40]) !== -1) {
                        return;
                    }
                    if (e.ctrlKey || e.metaKey) {
                        return;
                    }
                    var ch = e.key;
                    if (ch && ch.length === 1 && !/[0-9]/.test(ch)) {
                        e.preventDefault();
                    }
                });
                $('#otp_paraphage').on('paste.otpDigits', function (e) {
                    e.preventDefault();
                    var clip = (e.originalEvent && e.originalEvent.clipboardData)
                        ? e.originalEvent.clipboardData.getData('text')
                        : (window.clipboardData ? window.clipboardData.getData('Text') : '');
                    var v = (clip || '').replace(/\D/g, '').slice(0, 6);
                    $(this).val(v);
                });

                $('#modal-registre-paraphage').on('hidden.bs.modal', function () {
                    clearRegistreParaphTimers();
                    hideParaphAlert();
                    $('#otp_paraphage').val('');
                    $('#paraphe_mode').val('single');
                    $('#paraphe_bulk_codes_json').val('');
                    $('#modal-paraphage-title').text('Validation du registre (paraphe)');
                    $('#paraphe-bulk-summary').addClass('d-none').text('');
                    var sa = document.getElementById('registre-bulk-select-all');
                    if (sa) {
                        sa.indeterminate = false;
                        sa.checked = false;
                    }
                    $('.registre-bulk-cb').prop('checked', false);
                    updateRegistreBulkSelectionUi();
                });

                $("a.show-validation-modal").on("click", function () {
                    var code_registre = $(this).attr("href");
                    $('#paraphe_mode').val('single');
                    $('#paraphe_bulk_codes_json').val('');
                    $('#modal-paraphage-title').text('Validation du registre (paraphe)');
                    $('#paraphe-bulk-summary').addClass('d-none').text('');
                    requestParaphOtpForRegistre(code_registre, true);
                    return false;
                });

                $('#resend-otp-link').on('click', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('disabled')) {
                        return false;
                    }
                    if ($('#paraphe_mode').val() === 'bulk') {
                        var raw = $('#paraphe_bulk_codes_json').val();
                        var codesBulk = [];
                        try {
                            codesBulk = JSON.parse(raw || '[]');
                        } catch (errBulk) {
                            codesBulk = [];
                        }
                        if (!codesBulk.length) {
                            return false;
                        }
                        requestParaphOtpBulk(codesBulk, false, true);
                        return false;
                    }
                    var code_registre = $('#code_registre').val();
                    if (!code_registre) {
                        return false;
                    }
                    requestParaphOtpForRegistre(code_registre, false, true);
                    return false;
                });

                $("#btn-validate").on("click", function () {
                    var otp_paraphage = ($("#otp_paraphage").val() || '').replace(/\D/g, '');
                    if (otp_paraphage === "" || otp_paraphage.length !== 6 || !/^\d{6}$/.test(otp_paraphage)) {
                        alert("Veuillez saisir le code à 6 chiffres reçu par SMS (et par e-mail si configuré).");
                        return false;
                    }
                    var mode = $('#paraphe_mode').val();
                    var $btn = $(this);
                    $btn.prop("disabled", true);
                    $btn.html("Traitement en cours ...");

                    function handleParaphValidateResponse(response) {
                        if (!response || typeof response !== 'object') {
                            flashAlert("Réponse", "error", "Réponse serveur invalide.");
                            return;
                        }
                        var rc = paraphOtpResponseCode(response);
                        if (rc === 200) {
                            clearRegistreParaphTimers();
                            flashAlert("Réponse", "success", response.message);
                            $("#modal-registre-paraphage").modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 4000);
                            return;
                        }
                        if (rc === 184) {
                            applyParaphAttemptsFromResponse(response);
                            if (response.retry_after_seconds) {
                                startParaphLockoutCountdown(parseInt(response.retry_after_seconds, 10) || 180);
                            }
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 185) {
                            clearRegistreParaphTimers();
                            $('#otp-expiry-meter-section').addClass('d-none');
                            $('#otp-expired-wrap').removeClass('d-none');
                            $('#btn-validate').prop('disabled', true);
                            $('#otp_paraphage').prop('disabled', true);
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 183) {
                            applyParaphAttemptsFromResponse(response);
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 180) {
                            flashAlert("Réponse", "error", response.message || 'Données invalides.');
                            return;
                        }
                        flashAlert("Réponse", "error", response.message || 'Erreur inattendue.');
                    }

                    function finishParaphValidateAjax() {
                        $btn.prop("disabled", false);
                        $btn.html("Valider");
                    }

                    if (mode === 'bulk') {
                        var rawCodes = $('#paraphe_bulk_codes_json').val();
                        var codesBulkVal = [];
                        try {
                            codesBulkVal = JSON.parse(rawCodes || '[]');
                        } catch (errVal) {
                            codesBulkVal = [];
                        }
                        if (!codesBulkVal.length) {
                            finishParaphValidateAjax();
                            alert('La sélection groupée est invalide. Fermez le modal et recommencez.');
                            return false;
                        }
                        $.ajax({
                            url: "{{ route('registre.validate.otp.bulk') }}",
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({ codes: codesBulkVal, otp_paraphage: otp_paraphage }),
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': getRegistreCsrfToken(),
                                'X-Requested-With': 'XMLHttpRequest',
                                Accept: 'application/json'
                            }
                        }).done(function (response) {
                            finishParaphValidateAjax();
                            handleParaphValidateResponse(response);
                        }).fail(function (xhr) {
                            finishParaphValidateAjax();
                            var parsed = xhr.responseJSON;
                            if (!parsed && xhr.responseText) {
                                try {
                                    parsed = JSON.parse(xhr.responseText);
                                } catch (e) {
                                    parsed = null;
                                }
                            }
                            if (parsed && typeof parsed === 'object') {
                                var rc = paraphOtpResponseCode(parsed);
                                if (rc === 183) {
                                    applyParaphAttemptsFromResponse(parsed);
                                    flashAlert("Réponse", "error", parsed.message || 'Code OTP incorrect.');
                                    return;
                                }
                                if (rc === 180) {
                                    flashAlert("Réponse", "error", parsed.message || 'Données invalides.');
                                    return;
                                }
                            }
                            var msg = 'Erreur lors de la validation.';
                            if (xhr.status === 429) {
                                msg = 'Trop de tentatives. Patientez quelques instants.';
                            }
                            flashAlert('Réponse', 'error', msg);
                        });
                        return false;
                    }

                    var code_registre = $("#code_registre").val();
                    if (code_registre === "") {
                        finishParaphValidateAjax();
                        alert("Identifiant registre manquant. Fermez le modal et rouvrez depuis Parapher.");
                        return false;
                    }

                    $.ajax({
                        url: "{{ route('registre.validate.otp') }}",
                        method: 'POST',
                        data: {
                            code_registre: code_registre,
                            otp_paraphage: otp_paraphage
                        },
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json'
                        }
                    }).done(function (response) {
                        finishParaphValidateAjax();
                        handleParaphValidateResponse(response);
                    }).fail(function (xhr) {
                        finishParaphValidateAjax();
                        var parsed = xhr.responseJSON;
                        if (!parsed && xhr.responseText) {
                            try {
                                parsed = JSON.parse(xhr.responseText);
                            } catch (e) {
                                parsed = null;
                            }
                        }
                        if (parsed && typeof parsed === 'object') {
                            var rc = paraphOtpResponseCode(parsed);
                            if (rc === 183) {
                                applyParaphAttemptsFromResponse(parsed);
                                flashAlert("Réponse", "error", parsed.message || 'Code OTP incorrect.');
                                return;
                            }
                            if (rc === 180) {
                                flashAlert("Réponse", "error", parsed.message || 'Données invalides.');
                                return;
                            }
                        }
                        var msg = 'Erreur lors de la validation.';
                        if (xhr.status === 429) {
                            msg = 'Trop de tentatives. Patientez quelques instants.';
                        }
                        flashAlert('Réponse', 'error', msg);
                    });

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
