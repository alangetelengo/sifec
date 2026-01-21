@php
    $causeDecesCount = $causeDeces ? $causeDeces->count() : 0;
@endphp
@if($causeDecesCount > 0)
    @foreach ($causeDeces as $item)
    <tr>
        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
        <td><strong>{{ $item->lib_cause_deces }}</strong></td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editCauseDecesModal{{ $item->code_cause_deces }}" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <form action="{{ route('causedeces.destroy', $item->code_cause_deces) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_cause_deces }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_cause_deces }}" data-libelle="{{ $item->lib_cause_deces }}" title="Supprimer">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="3" class="text-center">
            <div class="py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aucune cause de décès trouvée (Total: {{ $causeDecesCount }})</p>
            </div>
        </td>
    </tr>
@endif

