@forelse ($professions ?? [] as $item)
<tr>
    <td class="text-muted small sl-row-num">{{ $loop->iteration }}</td>
    <td><span class="sl-lib fw-semibold text-dark">{{ $item->lib_profession }}</span></td>
    <td class="text-end sl-actions">
        <div class="sl-actions-group" role="group" aria-label="Actions">
            <button type="button" class="sl-btn-action sl-btn-action-edit" data-bs-toggle="modal" data-bs-target="#editProfessionModal{{ $item->code_profession }}" title="Modifier">
                <i class="fas fa-pen" aria-hidden="true"></i>
            </button>
            <button type="button" class="sl-btn-action sl-btn-action-delete btn-delete" data-code="{{ $item->code_profession }}" data-libelle="{{ $item->lib_profession }}" title="Supprimer">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
            </button>
        </div>
        <form action="{{ route('profession.destroy', $item->code_profession) }}" method="post" class="d-none" id="deleteForm{{ $item->code_profession }}">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="3" class="text-center sl-empty py-5">
        <div class="sl-empty-icon mb-3"><i class="fas fa-briefcase"></i></div>
        <p class="text-muted mb-1 fw-semibold">Aucune profession à afficher</p>
        <p class="small text-muted mb-0">Affinez la recherche ou ajoutez une entrée.</p>
    </td>
</tr>
@endforelse
