@forelse ($localites as $index => $item)
<tr>
    <td><span class="badge badge-primary badge-custom">{{ $loop->iteration }}</span></td>
    <td><strong>{{ $item->lib_localite }}</strong></td>
    <td>{{ $item->typelocalite ? $item->typelocalite->lib_type_localite : 'N/A' }}</td>
    <td>
        @if($item->localiteParent)
            <span class="text-muted"><i class="fas fa-level-up-alt me-1"></i>{{ $item->localiteParent->lib_localite }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>
        <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editLocaliteModal{{ $item->code_localite }}" title="Modifier">
                <i class="fas fa-pencil-alt"></i>
            </button>
            <form action="{{ route('localite.destroy', $item->code_localite) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_localite }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_localite }}" data-libelle="{{ $item->lib_localite }}" title="Supprimer">
                    <i class="fa fa-trash"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center">
        <div class="py-4">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <p class="text-muted">Aucune localité trouvée</p>
        </div>
    </td>
</tr>
@endforelse

