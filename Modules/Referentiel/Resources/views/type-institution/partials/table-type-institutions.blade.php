@forelse ($typeInstitutions as $index => $item)
<tr>
    <td><span class="badge badge-primary badge-custom">{{ $loop->iteration }}</span></td>
    <td><strong>{{ $item->lib_type_institution }}</strong></td>
    <td>{{ $item->typeCategorieInstitution ? $item->typeCategorieInstitution->lib_type_categorie_institution : 'N/A' }}</td>
    <td>
        <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editTypeInstitutionModal{{ $item->code_type_institution }}" title="Modifier">
                <i class="fas fa-pencil-alt"></i>
            </button>
            <form action="{{ route('typeInstitution.destroy', $item->code_type_institution) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_institution }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_type_institution }}" data-libelle="{{ $item->lib_type_institution }}" title="Supprimer">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center">
        <div class="py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucun type d'institution trouvé</p>
        </div>
    </td>
</tr>
@endforelse

