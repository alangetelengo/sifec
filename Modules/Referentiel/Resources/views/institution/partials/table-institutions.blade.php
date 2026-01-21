@forelse ($institutions as $index => $institution)
<tr>
    <td><span class="badge badge-primary badge-custom">{{ $loop->iteration }}</span></td>
    <td><strong>{{ $institution->lib_institution }}</strong></td>
    <td>
        @if($institution->institutionParent)
            <span class="text-muted"><i class="fas fa-level-up-alt me-1"></i>{{ $institution->institutionParent->lib_institution }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>{{ $institution->typeInstitution ? $institution->typeInstitution->lib_type_institution : 'N/A' }}</td>
    <td>{{ $institution->lieu ? $institution->lieu->lib_localite : 'ETRANGER' }}</td>
    <td>
        @if($institution->sceau)
            <img src='{{ asset("app/".$institution->sceau) }}' alt="sceau" width="50px" height="50px" class="img-thumbnail">
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>
        @if($institution->statut == "1")
            <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span>
        @else
            <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;">Désactivé</span>
        @endif
    </td>
    <td>
        <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editInstitutionModal{{ $institution->code_institution }}" title="Modifier">
                <i class="fas fa-pencil-alt"></i>
            </button>
            <form action="{{ route('institution.destroy', $institution->code_institution) }}" method="post" class="d-inline" id="deleteForm{{ $institution->code_institution }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $institution->code_institution }}" data-libelle="{{ $institution->lib_institution }}" title="Supprimer">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">
        <div class="py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucune institution trouvée</p>
        </div>
    </td>
</tr>
@endforelse

