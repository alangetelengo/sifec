@extends('layout.app')
@section('titre')
   Référentiel — Types de localité
@endsection
@section('styles')
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
/* Même charte que la page Localités (référentiel géographique SIFEC) */
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
.sl-lib-type { font-size: .95rem; font-weight: 600; color: #1a1a1a; }
.sl-btn-icon {
    border-radius: 8px !important;
    padding: .35rem .55rem !important;
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
    $typesCount = $typeLocalites->count();
@endphp
<div class="sifec-localite-page container-fluid px-0">
    <div class="sl-hero mb-4">
        <div class="row align-items-center g-3 position-relative" style="z-index:1">
            <div class="col-lg">
                <h1><i class="fas fa-layer-group me-2 opacity-90"></i>Types de localité</h1>
                <p>Niveaux de la nomenclature territoriale (département, commune, quartier, etc.). Chaque fiche dans <strong>Localités</strong> référence un de ces types. Les codes <code class="text-white text-opacity-75">TPLOC_*</code> sont générés automatiquement à la création.</p>
            </div>
            <div class="col-lg-auto d-flex flex-wrap gap-2 justify-content-lg-end">
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addTypeLocaliteModal">
                    <i class="fas fa-plus-circle me-1"></i> Nouveau type
                </button>
                <a href="{{ route('localite.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-map-location-dot me-1"></i> Localités
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#2781d5,#5a9fd4);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Types en base</div>
                        <div class="sl-stat-val">{{ $typesCount }}</div>
                        <div class="small text-muted">Nomenclature</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card sl-stat">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="sl-stat-icon text-white" style="background:linear-gradient(135deg,#006B31,#009E49);">
                        <i class="fas fa-key"></i>
                    </div>
                    <div>
                        <div class="sl-stat-lbl">Format code</div>
                        <div class="sl-stat-val" style="font-size:1.1rem;font-family:ui-monospace,monospace;">TPLOC_*</div>
                        <div class="small text-muted">Identifiant technique</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card sl-stat sl-info-bar h-100">
                <div class="card-body d-flex align-items-start gap-2 py-3">
                    <i class="fas fa-circle-info text-success mt-1"></i>
                    <div class="small text-muted mb-0">La suppression d’un type n’est possible que s’il n’est référencé par aucune localité. Préférez en général ajuster les libellés plutôt que multiplier les types.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card sl-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Liste des types</h5>
            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addTypeLocaliteModal" style="border-radius:10px;">
                <i class="fas fa-plus me-1"></i> Ajouter
            </button>
        </div>
        <div class="card-body p-0 p-md-3">
            <div class="table-responsive sl-table-wrap">
                <table id="table-type-localites" class="table table-hover sl-table mb-0 align-middle" style="min-width:720px">
                    <thead>
                        <tr>
                            <th class="sl-row-num">#</th>
                            <th>Code</th>
                            <th>Libellé</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($typeLocalites as $item)
                        <tr>
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td><code class="sl-code">{{ $item->code_type_localite }}</code></td>
                            <td><span class="sl-lib-type">{{ $item->lib_type_localite }}</span></td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-primary sl-btn-icon" data-bs-toggle="modal" data-bs-target="#editTypeLocaliteModal{{ $item->code_type_localite }}" title="Modifier">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger sl-btn-icon btn-delete"
                                            data-code="{{ $item->code_type_localite }}"
                                            data-libelle="{{ $item->lib_type_localite }}"
                                            title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <form action="{{ route('typelocalite.destroy', $item->code_type_localite) }}" method="post" class="d-none" id="deleteForm{{ $item->code_type_localite }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                Aucun type de localité enregistré.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal ajout --}}
<div class="modal fade" id="addTypeLocaliteModal" tabindex="-1" aria-labelledby="addTypeLocaliteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="addTypeLocaliteModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Nouveau type de localité
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form method="POST" action="{{ route('typelocalite.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
                        <input type="text" name="lib_type_localite" class="form-control form-control-lg rounded-3 @error('lib_type_localite') is-invalid @enderror"
                               value="{{ old('lib_type_localite') }}" placeholder="Ex. Département, Commune, Quartier…" required>
                        @error('lib_type_localite')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Le code <code>TPLOC_*</code> sera attribué automatiquement.</small>
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

{{-- Modals édition --}}
@foreach ($typeLocalites as $item)
<div class="modal fade" id="editTypeLocaliteModal{{ $item->code_type_localite }}" tabindex="-1" aria-labelledby="editTypeLocaliteModalLabel{{ $item->code_type_localite }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header sl-modal-header text-white border-0 py-3">
                <h5 class="modal-title" id="editTypeLocaliteModalLabel{{ $item->code_type_localite }}">
                    <i class="fas fa-pen-to-square me-2"></i>Modifier — {{ $item->lib_type_localite }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('typelocalite.update', $item->code_type_localite) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Libellé <span class="text-danger">*</span></label>
                        <input class="form-control form-control-lg rounded-3 @error('lib_type_localite') is-invalid @enderror"
                               name="lib_type_localite" type="text" value="{{ $item->lib_type_localite }}" required>
                        @error('lib_type_localite')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-muted">Code (lecture seule)</label>
                        <input class="form-control rounded-3 bg-light" type="text" value="{{ $item->code_type_localite }}" disabled readonly>
                        <small class="text-muted"><i class="fas fa-lock me-1"></i>Clé primaire métier — non modifiable</small>
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
<script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
(function () {
    function confirmDelete(code, libelle) {
        var form = document.getElementById('deleteForm' + code);
        if (!form) {
            Swal.fire({ title: 'Erreur', text: 'Formulaire introuvable.', icon: 'error', confirmButtonText: 'OK' });
            return;
        }
        Swal.fire({
            title: 'Confirmer la suppression ?',
            html: 'Le type <strong>' + libelle + '</strong> ne peut être supprimé que s’il n’est utilisé par aucune localité.',
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
        }).then(function (result) {
            if (result.isConfirmed === true || result.value === true) {
                form.submit();
            }
        });
    }

    $(document).ready(function () {
        if ($.fn.DataTable && $('#table-type-localites tbody tr').length && !$('#table-type-localites tbody tr td[colspan]').length) {
            try {
                $('#table-type-localites').DataTable({
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
                    ordering: true,
                    order: [[2, 'asc']]
                });
            } catch (e) { /* ignore */ }
        }

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var code = $(this).data('code');
            var libelle = $(this).data('libelle');
            if (code && libelle) {
                confirmDelete(code, libelle);
            }
        });
    });
})();
</script>
@endsection
