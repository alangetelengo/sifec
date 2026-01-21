@php
    $religionsCount = $religions ? $religions->count() : 0;
@endphp
@if($religionsCount > 0)
    @foreach ($religions as $item)
    <tr>
        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
        <td><strong>{{ $item->lib_religion }}</strong></td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editReligionModal{{ $item->code_religion }}" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <form action="{{ route('religion.destroy', $item->code_religion) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_religion }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_religion }}" data-libelle="{{ $item->lib_religion }}" title="Supprimer">
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
                <p class="text-muted">Aucune religion trouvée (Total: {{ $religionsCount }})</p>
            </div>
        </td>
    </tr>
@endif

