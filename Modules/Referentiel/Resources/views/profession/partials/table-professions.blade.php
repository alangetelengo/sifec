@php
    $professionsCount = $professions ? $professions->count() : 0;
@endphp
@if($professionsCount > 0)
    @foreach ($professions as $item)
    <tr>
        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
        <td><strong>{{ $item->lib_profession }}</strong></td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editProfessionModal{{ $item->code_profession }}" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <form action="{{ route('profession.destroy', $item->code_profession) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_profession }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_profession }}" data-libelle="{{ $item->lib_profession }}" title="Supprimer">
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
                <p class="text-muted">Aucune profession trouvée (Total: {{ $professionsCount }})</p>
            </div>
        </td>
    </tr>
@endif

