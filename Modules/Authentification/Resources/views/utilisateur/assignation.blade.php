@extends('layout.app')
@section('titre')
    Assignation des Permissions
@endsection
@section('styles')
@endsection
@section('corps')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-block">
                <h4>Assignation des permissions à
                    <strong>{{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}</strong>
                </h4>
                <p class="mb-0 text-muted small">
                    <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                    &nbsp;|&nbsp;
                    <i class="fas fa-building me-1"></i>{{ $user->affectationActive()?->institution?->lib_institution ?? 'Non affecté' }}
                    &nbsp;|&nbsp;
                    <i class="fas fa-briefcase me-1"></i>{{ $user->affectationActive()?->fonction?->lib_fonction ?? 'Non définie' }}
                </p>
            </div>
            <div class="card-body">
                <form action="{{ route('utilisateur.assigner.store', $user->code_user) }}" method="POST" id="assignationForm">
                    @csrf
                    <div class="accordion accordion-primary" id="accordion-permissions">
                        @forelse ($modules as $module)
                            @php
                                $parents         = $module->fonctionnalites->filter(fn($f) => empty($f->code_fonctionnalite_parent))->sortBy('lib_fonctionnalite');
                                $userPermissions = $user->toutesfonctionnalites()->pluck('code_fonctionnalite')->toArray();
                            @endphp
                            <div class="accordion-item">
                                <div class="accordion-header rounded-lg"
                                    id="heading{{ $module->code_module }}"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $module->code_module }}"
                                    aria-controls="collapse{{ $module->code_module }}"
                                    aria-expanded="false"
                                    role="button">
                                    <span class="accordion-header-icon"></span>
                                    <span class="accordion-header-text">
                                        {{ $module->lib_module }}
                                        <span class="badge bg-light text-dark ms-2">{{ $module->fonctionnalites->count() }} fonctionnalités</span>
                                    </span>
                                    <span class="accordion-header-indicator"></span>
                                </div>
                                <div id="collapse{{ $module->code_module }}"
                                    class="collapse"
                                    aria-labelledby="heading{{ $module->code_module }}">
                                    <div class="accordion-body-text">
                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button"
                                                class="btn btn-xs btn-outline-success me-1"
                                                onclick="moduleSelectAll('{{ $module->code_module }}', true)">
                                                <i class="fas fa-check-double"></i> Tout cocher
                                            </button>
                                            <button type="button"
                                                class="btn btn-xs btn-outline-secondary"
                                                onclick="moduleSelectAll('{{ $module->code_module }}', false)">
                                                <i class="fas fa-times"></i> Tout décocher
                                            </button>
                                        </div>
                                        <div class="row">
                                            @forelse ($parents as $parent)
                                                <div class="col-12 mt-2 mb-1">
                                                    <strong class="text-success">
                                                        <input class="form-check-input permission-checkbox parent-checkbox me-1"
                                                               type="checkbox"
                                                               name="fonctionnalites[]"
                                                               value="{{ $parent->code_fonctionnalite }}"
                                                               id="parent_{{ $parent->code_fonctionnalite }}"
                                                               {{ in_array($parent->code_fonctionnalite, $userPermissions) ? 'checked' : '' }}
                                                               onchange="toggleChildren('{{ $parent->code_fonctionnalite }}')">
                                                        <label for="parent_{{ $parent->code_fonctionnalite }}">{{ $parent->lib_fonctionnalite }}</label>
                                                    </strong>
                                                </div>
                                                @php
                                                    $children = $module->fonctionnalites->where('code_fonctionnalite_parent', $parent->code_fonctionnalite)->sortBy('lib_fonctionnalite');
                                                @endphp
                                                <div class="row ms-3" id="children_{{ $parent->code_fonctionnalite }}">
                                                    @foreach ($children as $child)
                                                        <div class="col-md-6">
                                                            <label for="child_{{ $child->code_fonctionnalite }}">
                                                                <input class="form-check-input permission-checkbox child-checkbox"
                                                                       type="checkbox"
                                                                       name="fonctionnalites[]"
                                                                       value="{{ $child->code_fonctionnalite }}"
                                                                       id="child_{{ $child->code_fonctionnalite }}"
                                                                       data-parent="{{ $parent->code_fonctionnalite }}"
                                                                       {{ in_array($child->code_fonctionnalite, $userPermissions) ? 'checked' : '' }}>
                                                                {{ $child->lib_fonctionnalite }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @empty
                                                <p class="text-muted ps-2"><em>Aucune fonctionnalité disponible.</em></p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Aucun module disponible.</div>
                        @endforelse
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-sm btn-danger">Retour</a>
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function toggleChildren(parentCode) {
    const parentCb = document.getElementById('parent_' + parentCode);
    document.querySelectorAll('#children_' + parentCode + ' .child-checkbox').forEach(function(cb) {
        cb.checked = parentCb.checked;
    });
}

function moduleSelectAll(moduleCode, check) {
    const collapse = document.getElementById('collapse' + moduleCode);
    if (!collapse) return;
    collapse.querySelectorAll('.permission-checkbox').forEach(function(cb) {
        cb.checked = check;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.child-checkbox').forEach(function(cb) {
        cb.addEventListener('change', function () {
            if (this.checked) {
                const parent = document.getElementById('parent_' + this.getAttribute('data-parent'));
                if (parent) parent.checked = true;
            }
        });
    });
});
</script>
@endsection
