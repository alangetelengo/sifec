<form method="GET" action="{{ route('demandeDocument.index') }}" class="row g-3">
    {{-- Préserver l'origine si déjà sélectionnée --}}
    @if(request()->has('origine'))
        <input type="hidden" name="origine" value="{{ request('origine') }}">
    @endif

    <div class="col-md-2">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select form-control">
            <option value="">Tous</option>
            @foreach($statuts as $statut)
                <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>
                    {{ $statut }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Type document</label>
        <select name="type_document" class="form-select form-control">
            <option value="">Tous</option>
            @foreach($typesDocuments as $type)
                <option value="{{ $type->code_type_document_demande }}" {{ request('type_document') == $type->code_type_document_demande ? 'selected' : '' }}>
                    {{ $type->lib_type_document_demande }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Type acte</label>
        <select name="type_acte" class="form-select form-control">
            <option value="">Tous</option>
            @foreach($typesActes as $type)
                <option value="{{ $type->code_type_acte }}" {{ request('type_acte') == $type->code_type_acte ? 'selected' : '' }}>
                    {{ $type->lib_type_acte }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Date début</label>
        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Date fin</label>
        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
    </div>

    <div class="col-md-2">
        <label class="form-label">Recherche</label>
        <input type="text" name="recherche" class="form-control" placeholder="N° acte, nom..." value="{{ request('recherche') }}">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-filter"></i> Filtrer
        </button>
        <a href="{{ route('demandeDocument.index') }}" class="btn btn-secondary">
            <i class="fas fa-redo"></i> Réinitialiser
        </a>
    </div>
</form>
