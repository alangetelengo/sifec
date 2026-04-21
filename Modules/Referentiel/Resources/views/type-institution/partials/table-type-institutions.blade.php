@forelse ($typeInstitutions as $index => $item)
<tr>
    <td class="text-center"><span class="sl-num">{{ $loop->iteration }}</span></td>
    <td><strong>{{ $item->lib_type_institution }}</strong></td>
    <td>{{ $item->typeCategorieInstitution ? $item->typeCategorieInstitution->lib_type_categorie_institution : '—' }}</td>
    <td class="text-end sl-actions">
        <div class="sl-actions-group justify-content-end">
            <button type="button" class="sl-btn-action sl-btn-action-edit" data-bs-toggle="modal" data-bs-target="#editTypeInstitutionModal{{ $item->code_type_institution }}" title="Modifier">
                <i class="fas fa-pen"></i>
            </button>
            <form action="{{ route('typeInstitution.destroy', $item->code_type_institution) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_type_institution }}">
                @csrf
                @method('DELETE')
                <button class="sl-btn-action sl-btn-action-delete btn-delete" type="button" data-code="{{ $item->code_type_institution }}" data-libelle="{{ $item->lib_type_institution }}" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-center py-5">
        <div class="sl-empty-icon mx-auto mb-2"><i class="fas fa-inbox"></i></div>
        <p class="text-muted mb-0">Aucun type d’institution ne correspond à ces critères.</p>
    </td>
</tr>
@endforelse
