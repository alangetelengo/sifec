@forelse ($institutions as $index => $institution)
@php
    $hasGuot = filled($institution->guot_institution_id);
@endphp
<tr>
    <td class="text-center"><span class="sl-num">{{ $loop->iteration }}</span></td>
    <td><strong>{{ $institution->lib_institution }}</strong></td>
    <td>
        @if($institution->institutionParent)
            <span class="text-muted small"><i class="fas fa-level-up-alt me-1"></i>{{ $institution->institutionParent->lib_institution }}</span>
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td><span class="small">{{ $institution->typeInstitution ? $institution->typeInstitution->lib_type_institution : '—' }}</span></td>
    <td>{{ $institution->lieu ? $institution->lieu->lib_localite : 'ETRANGER' }}</td>
    <td>
        @if($institution->sceau)
            <img src="{{ asset('app/'.$institution->sceau) }}" alt="" width="48" height="48" class="rounded border object-fit-cover">
        @else
            <span class="text-muted">—</span>
        @endif
    </td>
    <td>
        @if($hasGuot)
            <span class="badge rounded-pill" style="background:rgba(0,158,73,.15);color:#006B31;" title="{{ $institution->guot_institution_id }}">
                <i class="fas fa-stamp me-1"></i>Lié
            </span>
        @else
            <span class="badge rounded-pill" style="background:rgba(176,42,55,.12);color:#a02834;">
                <i class="fas fa-exclamation-circle me-1"></i>Manquant
            </span>
        @endif
    </td>
    <td>
        @if($institution->statut == "1")
            <span class="badge rounded-pill" style="background:rgba(0,158,73,.15);color:#006B31;">Actif</span>
        @else
            <span class="badge rounded-pill" style="background:rgba(176,42,55,.12);color:#a02834;">Inactif</span>
        @endif
    </td>
    <td class="text-end sl-actions">
        <div class="sl-actions-group justify-content-end">
            <a href="{{ route('institution.edit', $institution->code_institution) }}" class="sl-btn-action sl-btn-action-edit" title="Modifier">
                <i class="fas fa-pen"></i>
            </a>
            <form action="{{ route('institution.destroy', $institution->code_institution) }}" method="post" class="d-inline" id="deleteForm{{ $institution->code_institution }}">
                @csrf
                @method('DELETE')
                <button class="sl-btn-action sl-btn-action-delete btn-delete" type="button" data-code="{{ $institution->code_institution }}" data-libelle="{{ e($institution->lib_institution) }}" title="Supprimer">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center py-5">
        <div class="sl-empty-icon mx-auto mb-2"><i class="fas fa-inbox"></i></div>
        <p class="text-muted mb-0">Aucune institution trouvée.</p>
    </td>
</tr>
@endforelse
