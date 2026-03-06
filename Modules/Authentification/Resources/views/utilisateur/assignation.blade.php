@extends('layout.app')
@section('titre')
    Assignation des Permissions
@endsection

@section('styles')
<style>
    .breadcrumb-item a {
        color: #007bff !important;
        text-decoration: none;
    }
    .breadcrumb-item a:hover {
        color: #0056b3 !important;
        text-decoration: underline;
    }
    .breadcrumb-item.active {
        color: #6c757d;
    }
    .module-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    .module-card:hover {
        border-left-color: #667eea;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .module-header {
        background-color: #f8f9fa;
        color: #495057;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }
    .module-header:hover {
        background-color: #e9ecef;
    }
    .permission-item {
        padding: 10px;
        border-radius: 5px;
        transition: background-color 0.2s ease;
    }
    .permission-item:hover {
        background-color: #f8f9fa;
    }
    .permission-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    .parent-permission {
        background-color: #f8f9fa;
        border-left: 3px solid #6c757d;
        font-weight: 600;
        margin-bottom: 10px;
        padding: 12px;
        border: 1px solid #dee2e6;
    }
    .stats-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 10px;
    }
</style>
@endsection

@section('corps')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}"><i class="fas fa-home"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.index') }}"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li class="breadcrumb-item"><a href="{{ route('utilisateur.profile', $user->code_user) }}"><i class="fas fa-user"></i> {{ $user->personne->nom ?? '' }}</a></li>
            <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-key"></i> Permissions</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Assignation des Permissions</h4>
                    <a href="{{ route('utilisateur.profile', $user->code_user) }}">
                        <button type="button" class="btn btn-info m-t-2 float-end text-white">
                            <i class="fa fa-arrow-left"></i> Retour au Profil
                        </button>
                    </a>
                </div>
                <div class="card-body">

                    <!-- Informations de l'utilisateur -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-user-shield fa-3x text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-1">
                                    <i class="fas fa-user"></i>
                                    {{ $user->personne->nom ?? '' }} {{ $user->personne->prenom ?? '' }}
                                </h5>
                                <p class="mb-1">
                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-building"></i> {{ $user->affectationActive()?->institution?->lib_institution ?? "Non affecté" }}
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-briefcase"></i> {{ $user->affectationActive()?->fonction?->lib_fonction ?? "Non définie" }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="stats-badge bg-primary text-white">
                                    <i class="fas fa-key"></i>
                                    {{ $user->toutesfonctionnalites()->count() }} permissions actuelles
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire d'assignation -->
                    <form action="{{ route('utilisateur.assigner.store', $user->code_user) }}" method="POST" id="assignationForm">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-success" onclick="selectAll()">
                                            <i class="fas fa-check-double"></i> Tout sélectionner
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="deselectAll()">
                                            <i class="fas fa-times"></i> Tout désélectionner
                                        </button>
                                    </div>
                                    <div>
                                        <span class="badge bg-info">
                                            <i class="fas fa-layer-group"></i> {{ $modules->count() }} modules disponibles
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accordéon des modules -->
                        <div class="accordion accordion-primary-solid" id="accordion-modules">
                            @forelse ($modules as $module)
                                <div class="accordion-item module-card mb-3">
                                    <div class="accordion-header module-header rounded-lg" id="heading{{ $module->code_module }}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $module->code_module }}" aria-expanded="false" aria-controls="collapse{{ $module->code_module }}">
                                            <i class="fas fa-cubes me-2 text-muted"></i>
                                            <strong>{{ $module->lib_module }}</strong>
                                            <span class="badge bg-light text-dark ms-3">
                                                {{ $module->fonctionnalites->count() }} fonctionnalités
                                            </span>
                                        </button>
                                    </div>
                                    <div id="collapse{{ $module->code_module }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $module->code_module }}" data-bs-parent="#accordion-modules">
                                        <div class="accordion-body">
                                            @php
                                                // Grouper les fonctionnalités par parent (null ou chaîne vide)
                                                $parents = $module->fonctionnalites->filter(function($f) {
                                                    return empty($f->code_fonctionnalite_parent) || $f->code_fonctionnalite_parent === '';
                                                })->sortBy('lib_fonctionnalite');
                                                $userPermissions = $user->toutesfonctionnalites()->pluck('code_fonctionnalite')->toArray();
                                            @endphp

                                            @forelse ($parents as $parent)
                                                <!-- Parent Permission -->
                                                <div class="parent-permission rounded">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox parent-checkbox"
                                                               type="checkbox"
                                                               value="{{ $parent->code_fonctionnalite }}"
                                                               name="fonctionnalites[]"
                                                               id="parent_{{ $parent->code_fonctionnalite }}"
                                                               {{ in_array($parent->code_fonctionnalite, $userPermissions) ? 'checked' : '' }}
                                                               onchange="toggleChildren('{{ $parent->code_fonctionnalite }}')">
                                                        <label class="form-check-label" for="parent_{{ $parent->code_fonctionnalite }}">
                                                            <i class="fas fa-folder-open text-muted"></i>
                                                            <strong>{{ $parent->lib_fonctionnalite }}</strong>
                                                        </label>
                                                    </div>
                                                </div>

                                                <!-- Child Permissions -->
                                                @php
                                                    $children = $module->fonctionnalites->where('code_fonctionnalite_parent', $parent->code_fonctionnalite)->sortBy('lib_fonctionnalite');
                                                @endphp

                                                @if($children->count() > 0)
                                                    <div class="row ms-4 mb-3" id="children_{{ $parent->code_fonctionnalite }}">
                                                        @foreach ($children as $child)
                                                            <div class="col-md-6 mb-2">
                                                                <div class="permission-item">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input permission-checkbox child-checkbox"
                                                                               type="checkbox"
                                                                               value="{{ $child->code_fonctionnalite }}"
                                                                               name="fonctionnalites[]"
                                                                               id="child_{{ $child->code_fonctionnalite }}"
                                                                               data-parent="{{ $parent->code_fonctionnalite }}"
                                                                               {{ in_array($child->code_fonctionnalite, $userPermissions) ? 'checked' : '' }}>
                                                                        <label class="form-check-label" for="child_{{ $child->code_fonctionnalite }}">
                                                                            <i class="fas fa-angle-right text-muted"></i>
                                                                            {{ $child->lib_fonctionnalite }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @empty
                                                <p class="text-muted text-center py-3">
                                                    <i class="fas fa-info-circle"></i> Aucune fonctionnalité disponible pour ce module
                                                </p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Aucun module disponible
                                </div>
                            @endforelse
                        </div>

                        <!-- Boutons de soumission -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('utilisateur.profile', $user->code_user) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i> Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer les Permissions
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Sélectionner toutes les permissions
function selectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.checked = true;
    });
}

// Désélectionner toutes les permissions
function deselectAll() {
    document.querySelectorAll('.permission-checkbox').forEach(function(checkbox) {
        checkbox.checked = false;
    });
}

// Toggle les permissions enfants quand le parent est coché/décoché
function toggleChildren(parentCode) {
    const parentCheckbox = document.getElementById('parent_' + parentCode);
    const isChecked = parentCheckbox.checked;

    const childrenContainer = document.getElementById('children_' + parentCode);
    if (childrenContainer) {
        const childCheckboxes = childrenContainer.querySelectorAll('.child-checkbox');
        childCheckboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    }
}

// Vérifier automatiquement le parent si un enfant est coché
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.child-checkbox').forEach(function(childCheckbox) {
        childCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const parentCode = this.getAttribute('data-parent');
                const parentCheckbox = document.getElementById('parent_' + parentCode);
                if (parentCheckbox && !parentCheckbox.checked) {
                    parentCheckbox.checked = true;
                }
            }
        });
    });

    // Confirmation avant soumission
    document.getElementById('assignationForm').addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
        if (checkedCount === 0) {
            e.preventDefault();
            flashAlert('Attention', 'warning', 'Veuillez sélectionner au moins une permission avant d\'enregistrer.');
            return false;
        }
    });
});
</script>
@endsection
