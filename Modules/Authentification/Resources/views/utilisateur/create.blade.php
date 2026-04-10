@extends('layout.app')
@section('titre')
    Nouvel Utilisateur
@endsection
@section('corps')
<div class="page-utilisateur-form-sifec">
<div class="row">
    <div class="col-12">
        <div class="card pu-card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Créer un nouvel utilisateur</h4>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-sm pu-btn-search-person"
                            data-bs-toggle="modal" data-bs-target="#modalPersonne">
                        <i class="fas fa-search me-1"></i> Personne existante
                    </button>
                    <a href="{{ route('utilisateur.index') }}" class="btn btn-sm pu-btn-back">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card-body">

                <div class="alert alert-info pu-alert-info">
                    <i class="fas fa-info-circle me-1"></i>
                    Le compte sera créé avec le mot de passe par défaut <strong>123456</strong>.
                    L'utilisateur devra le modifier lors de sa première connexion.
                </div>

                <form method="POST" action="{{ route('utilisateur.store') }}"
                      enctype="multipart/form-data" id="createUserForm">
                    @csrf
                    <input type="hidden" id="code_personne" name="code_personne">

                    {{-- ── INFORMATIONS PERSONNELLES ───────────────────────────────────── --}}
                    <div class="ligne"><h4>INFORMATIONS PERSONNELLES</h4></div>
                    <div class="row">
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Nom(s) <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('nom') is-invalid @enderror"
                                   id="nom_personne" name="nom"
                                   value="{{ old('nom') }}"
                                   placeholder="Nom de famille"
                                   oninput="this.value=this.value.toUpperCase()">
                            @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Prénom(s)</label>
                            <input type="text"
                                   class="form-control @error('prenom') is-invalid @enderror"
                                   id="prenom_personne" name="prenom"
                                   value="{{ old('prenom') }}"
                                   placeholder="Prénom(s)">
                            @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Sexe <span class="text-danger">*</span></label>
                            <select name="sexe" id="sexe_personne"
                                    class="form-control @error('sexe') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                <option value="M" {{ old('sexe')=='M'?'selected':'' }}>Masculin</option>
                                <option value="F" {{ old('sexe')=='F'?'selected':'' }}>Féminin</option>
                            </select>
                            @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2 col-md-4">
                            <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('date_naissance') is-invalid @enderror"
                                   id="date_naissance_personne" name="date_naissance"
                                   value="{{ old('date_naissance') }}"
                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                            @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Lieu de naissance <span class="text-danger">*</span></label>
                            <select id="code_localite" name="code_localite"
                                    class="form-control @error('code_localite') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($localites as $localite)
                                    <option value="{{ $localite->code_localite }}"
                                        {{ old('code_localite')==$localite->code_localite?'selected':'' }}>
                                        {{ $localite->lib_localite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_localite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-4">
                            <label class="form-label">Nationalité <span class="text-danger">*</span></label>
                            <select name="code_nationalite" id="code_nationalite_personne"
                                    class="form-control @error('code_nationalite') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($nationalites as $nat)
                                    <option value="{{ $nat->code_nationalite }}"
                                        {{ old('code_nationalite')==$nat->code_nationalite?'selected':'' }}>
                                        {{ $nat->lib_nationalite }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_nationalite')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Adresse / Domicile <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('adresse') is-invalid @enderror"
                                   id="adresse_personne" name="adresse"
                                   value="{{ old('adresse') }}"
                                   placeholder="Ex : Quartier Bacongo, Brazzaville">
                            @error('adresse')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="text"
                                   class="form-control @error('pseudo') is-invalid @enderror"
                                   id="pseudo_personne" name="pseudo"
                                   value="{{ old('pseudo') }}"
                                   placeholder="Ex : 06 XXX XX XX"
                                   oninput="verif_nombre(this)">
                            @error('pseudo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── PIÈCE D'IDENTITÉ ────────────────────────────────────────────── --}}
                    <div class="ligne"><h4>PIÈCE D'IDENTITÉ</h4></div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Type de pièce <span class="text-danger">*</span></label>
                            <select name="code_type_document"
                                    class="form-control @error('code_type_document') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($typeDocuments as $item)
                                    <option value="{{ $item->code_type_document }}"
                                        {{ old('code_type_document')==$item->code_type_document?'selected':'' }}>
                                        {{ $item->lib_type_document }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_type_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Numéro de la pièce <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('numero_document') is-invalid @enderror"
                                   name="numero_document"
                                   value="{{ old('numero_document') }}"
                                   placeholder="N° de la pièce d'identité">
                            @error('numero_document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── AFFECTATION & COMPTE ────────────────────────────────────────── --}}
                    <div class="ligne"><h4>AFFECTATION &amp; COMPTE</h4></div>
                    <div class="row">
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Fonction <span class="text-danger">*</span></label>
                            <select name="code_fonction" id="code_fonction_personne"
                                    class="form-control @error('code_fonction') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($fonctions as $fn)
                                    <option value="{{ $fn->code_fonction }}"
                                        {{ old('code_fonction')==$fn->code_fonction?'selected':'' }}>
                                        {{ $fn->lib_fonction }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_fonction')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-2 col-md-6">
                            <label class="form-label">Type de centre d'état civil <span class="text-danger">*</span></label>
                            <select name="code_type_institution" id="codetypeinstitution"
                                    class="form-control @error('code_type_institution') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                                @foreach ($typeInstitutions as $type)
                                    <option value="{{ $type->code_type_institution }}"
                                        {{ old('code_type_institution')==$type->code_type_institution?'selected':'' }}>
                                        {{ $type->lib_type_institution }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_type_institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2 col-md-6 cecrattache d-none">
                            <label class="form-label">Centre d'état civil rattaché <span class="text-danger">*</span></label>
                            <select name="code_institution" id="codeinstitution"
                                    class="form-control @error('code_institution') is-invalid @enderror">
                                <option value="" disabled selected>Selectionner</option>
                            </select>
                            @error('code_institution')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-2 col-md-6">
                            <label class="form-label">Adresse email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="exemple@domaine.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ── BOUTONS ─────────────────────────────────────────────────────── --}}
                    <div class="pu-form-actions">
                        <a href="{{ route('utilisateur.index') }}" class="btn btn-sm pu-btn-cancel">
                            <i class="fas fa-times me-1"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-sm pu-btn-submit" id="submitBtn">
                            <i class="fas fa-save me-1"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

{{-- ── MODAL RECHERCHE PERSONNE ─────────────────────────────────────────────── --}}
<div class="modal fade modal-utilisateur-sifec" id="modalPersonne" data-bs-backdrop="static"
     tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-search me-2"></i>Rechercher une personne existante
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_nom" placeholder="Nom de famille">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="modal_prenom" placeholder="Prénom(s)">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Sexe <span class="text-danger">*</span></label>
                        <select id="modal_sexe" class="form-control">
                            <option value="" disabled selected>Selectionner</option>
                            <option value="M">Masculin</option>
                            <option value="F">Féminin</option>
                        </select>
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="modal_date_naissance"
                               max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                    </div>
                    <div class="mb-2 col-md-4">
                        <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="modal_telephone" placeholder="Numéro de téléphone">
                    </div>
                    <div class="mb-2 col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary w-100 tosearch">
                            <i class="fas fa-search me-1"></i> Rechercher
                        </button>
                    </div>
                </div>
                <div id="resultatPersonne" class="mt-3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {

    // Charger les institutions selon le type sélectionné
    $('#codetypeinstitution').on('change', function () {
        var val = $(this).val();
        if (val) {
            $('.cecrattache').removeClass('d-none');
            getInstitution(val);
        } else {
            $('.cecrattache').addClass('d-none');
            $('#codeinstitution').html('<option value="" disabled selected>Selectionner</option>');
        }
    });

    // Validation champs modal avant recherche
    $('#modalPersonne input, #modalPersonne select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Recherche personne existante
    $('.tosearch').on('click', function (e) {
        e.preventDefault();
        var hasError = false;
        ['#modal_nom', '#modal_date_naissance', '#modal_sexe', '#modal_telephone'].forEach(function(sel) {
            var el = $(sel);
            if (!el.val()) { el.addClass('is-invalid'); hasError = true; }
            else            { el.removeClass('is-invalid'); }
        });
        if (hasError) return;

        var btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Recherche...').prop('disabled', true);

        $.ajax({
            url: "{{ route('utilisateur.search') }}",
            data: {
                nom:            $('#modal_nom').val(),
                prenom:         $('#modal_prenom').val(),
                date_naissance: $('#modal_date_naissance').val(),
                sexe:           $('#modal_sexe').val(),
                telephone:      $('#modal_telephone').val()
            },
            success: function (res) {
                btn.html('<i class="fas fa-search me-1"></i> Rechercher').prop('disabled', false);
                renderResults(res.personnes);
            },
            error: function () {
                btn.html('<i class="fas fa-search me-1"></i> Rechercher').prop('disabled', false);
                $('#resultatPersonne').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Erreur lors de la recherche.</div>');
            }
        });
    });

    $('#createUserForm').on('submit', function () {
        var btn = $('#submitBtn');
        if (btn.data('sifec-submitting')) return;
        btn.data('sifec-submitting', 1);
        if (!btn.data('sifec-html')) btn.data('sifec-html', btn.html());
        btn.prop('disabled', true).attr('aria-busy', 'true').addClass('sifec-btn-loading')
            .html('<i class="fas fa-spinner fa-spin me-1" aria-hidden="true"></i>Création en cours…');
    });
});

function renderResults(personnes) {
    var $c = $('#resultatPersonne');
    if (!personnes || !personnes.length) {
        $c.html('<div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i>Aucune personne trouvée.</div>');
        return;
    }
    var html = '<hr><p class="text-muted small">' + personnes.length + ' résultat(s)</p>';
    html += '<div class="table-responsive"><table class="table table-hover table-bordered align-middle">';
    html += '<thead><tr><th>#</th><th>Nom & Prénom</th><th>Naissance</th><th>Sexe</th><th>Téléphone</th><th>Action</th></tr></thead><tbody>';
    personnes.forEach(function(p, i) {
        html += '<tr>'
             + '<td>' + (i+1) + '</td>'
             + '<td><strong>' + (p.nom||'') + '</strong> ' + (p.prenom||'') + '</td>'
             + '<td>' + (p.date_naissance||'-') + '</td>'
             + '<td>' + (p.sexe==='F' ? 'Féminin' : 'Masculin') + '</td>'
             + '<td>' + (p.telephone||'-') + '</td>'
             + '<td><button type="button" class="btn btn-sm btn-success btn-select-person" data-p=\'' + JSON.stringify(p).replace(/'/g, '&#39;') + '\'>Choisir</button></td>'
             + '</tr>';
    });
    html += '</tbody></table></div>';
    $c.html(html);

    $('.btn-select-person').on('click', function () {
        var p = $(this).data('p');
        $('#code_personne').val(p.code_personne || '');
        $('#nom_personne').val(p.nom || '');
        $('#prenom_personne').val(p.prenom || '');
        $('#date_naissance_personne').val(p.date_naissance || '');
        $('#sexe_personne').val(p.sexe || '');
        $('#adresse_personne').val(p.adresse || '');
        $('#pseudo_personne').val(p.telephone || '');
        $('#code_nationalite_personne').val(p.code_nationalite || '');
        $('#modalPersonne').modal('hide');
        $('#createUserForm').prepend(
            '<div class="alert alert-success alert-dismissible fade show">'
            + '<i class="fas fa-check-circle me-2"></i><strong>' + (p.nom||'') + ' ' + (p.prenom||'') + '</strong> — données pré-remplies. Vérifiez et complétez.'
            + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'
        );
    });
}

function getInstitution(id) {
    $.get("{{ route('utilisateur.getinstitution') }}", { id: id }, function (data) {
        var opts = '<option value="" disabled selected>Selectionner</option>';
        (data || []).forEach(function(inst) {
            opts += '<option value="' + inst.code_institution + '">' + inst.lib_institution + '</option>';
        });
        $('#codeinstitution').html(opts);
    });
}
</script>
@endsection
