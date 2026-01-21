@php
    $nationalitesCount = $nationalites ? $nationalites->count() : 0;
@endphp
@if($nationalitesCount > 0)
    @foreach ($nationalites as $item)
    <tr>
        <td><span class="badge badge-primary">{{ $loop->iteration }}</span></td>
        <td><strong>{{ $item->lib_nationalite }}</strong></td>
        <td>
            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-primary shadow btn-xs sharp" data-bs-toggle="modal" data-bs-target="#editNationaliteModal{{ $item->code_nationalite }}" title="Modifier">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <form action="{{ route('nationalite.destroy', $item->code_nationalite) }}" method="post" class="d-inline" id="deleteForm{{ $item->code_nationalite }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger shadow btn-xs sharp btn-delete" type="button" data-code="{{ $item->code_nationalite }}" data-libelle="{{ $item->lib_nationalite }}" title="Supprimer">
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
                <p class="text-muted">Aucune nationalité trouvée (Total: {{ $nationalitesCount }})</p>
            </div>
        </td>
    </tr>
@endif

