@forelse ($localites as $index => $item)
<tr>
    <td class="text-muted small sl-row-num">{{ $loop->iteration }}</td>
    <td><code class="sl-code">{{ $item->code_localite }}</code></td>
    <td>
        <span class="sl-lib fw-semibold text-dark">{{ $item->lib_localite }}</span>
    </td>
    <td>
        @if($item->typelocalite)
            <span class="sl-badge-type">{{ $item->typelocalite->lib_type_localite }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>
        @if($item->localiteParent)
            <span class="sl-parent"><i class="fas fa-level-up-alt me-1 opacity-50"></i>{{ $item->localiteParent->lib_localite }}</span>
        @else
            <span class="sl-root"><i class="fas fa-sitemap me-1"></i>Racine</span>
        @endif
    </td>
    <td class="text-end sl-actions">
        <div class="sl-actions-group" role="group" aria-label="Actions">
            <button type="button" class="sl-btn-action sl-btn-action-edit" data-bs-toggle="modal" data-bs-target="#editLocaliteModal{{ $item->code_localite }}" title="Modifier">
                <i class="fas fa-pen" aria-hidden="true"></i>
            </button>
            <button type="button" class="sl-btn-action sl-btn-action-delete btn-delete" data-code="{{ $item->code_localite }}" data-libelle="{{ $item->lib_localite }}" title="Supprimer">
                <i class="fas fa-trash-alt" aria-hidden="true"></i>
            </button>
        </div>
        <form action="{{ route('localite.destroy', $item->code_localite) }}" method="post" class="d-none" id="deleteForm{{ $item->code_localite }}">
            @csrf
            @method('DELETE')
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center sl-empty">
        <div class="py-5">
            <div class="sl-empty-icon mb-3"><i class="fas fa-map-location-dot"></i></div>
            <p class="text-muted mb-1 fw-semibold">Aucune localité à afficher</p>
            <p class="small text-muted mb-0">Affinez la recherche ou ajoutez une nouvelle localité.</p>
        </div>
    </td>
</tr>
@endforelse
