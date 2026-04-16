@php
    $m = $menuItem ?? null;
@endphp

<div class="smi-panel mb-3">
    <div class="smi-panel-title"><i class="fas fa-tag me-2"></i>Identification</div>
    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Code (clé primaire) <span class="text-danger">*</span></label>
            <input type="text" name="code_menu_item" class="form-control @error('code_menu_item') is-invalid @enderror"
                   value="{{ old('code_menu_item', $m?->code_menu_item) }}" {{ isset($menuItem) ? 'readonly' : '' }} required
                   pattern="[A-Z0-9_]+" maxlength="40" title="MAJUSCULES, chiffres et underscore"
                   autocomplete="off">
            @error('code_menu_item')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Ex. <code>MENU_CEC_NA</code> — non modifiable après création.</div>
        </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Entrée parente</label>
            <select name="code_parent" class="form-control form-control wide @error('code_parent') is-invalid @enderror">
                <option value="">— Racine (niveau principal) —</option>
                @foreach($parents as $p)
                    @if(!isset($menuItem) || $p->code_menu_item !== $menuItem->code_menu_item)
                        <option value="{{ $p->code_menu_item }}" {{ old('code_parent', $m?->code_parent) === $p->code_menu_item ? 'selected' : '' }}>
                            {{ $p->code_menu_item }} — {{ $p->libelle }}
                        </option>
                    @endif
                @endforeach
            </select>
            @error('code_parent')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-12">
            <label class="form-label">Libellé affiché <span class="text-danger">*</span></label>
            <input type="text" name="libelle" class="form-control @error('libelle') is-invalid @enderror" value="{{ old('libelle', $m?->libelle) }}" required maxlength="191">
            @error('libelle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-md-4">
            <label class="form-label">Ordre d’affichage</label>
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $m?->sort_order ?? 0) }}" min="0" required>
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-md-8 d-flex align-items-end">
            <div class="form-check pb-1">
                <input type="hidden" name="is_group" value="0">
                <input type="checkbox" name="is_group" id="is_group" class="form-check-input" value="1" {{ old('is_group', $m?->is_group) ? 'checked' : '' }}>
                <label class="form-check-label font-weight-bold" for="is_group">Entrée groupe (sous-menu sans lien direct)</label>
            </div>
        </div>
    </div>
</div>

<div class="smi-panel mb-3">
    <div class="smi-panel-title"><i class="fas fa-link me-2"></i>Lien & navigation</div>
    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Nom de route Laravel</label>
            <input type="text" name="route_name" class="form-control font-monospace @error('route_name') is-invalid @enderror" value="{{ old('route_name', $m?->route_name) }}" placeholder="ex. acteNaissance.index" autocomplete="off">
            @error('route_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Chemin URL (si pas de route)</label>
            <input type="text" name="external_path" class="form-control font-monospace @error('external_path') is-invalid @enderror" value="{{ old('external_path', $m?->external_path) }}" placeholder="/, #, javascript:void(0)" autocomplete="off">
            @error('external_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-12">
            <label class="form-label">Icône (classe CSS)</label>
            <input type="text" name="lib_icone" class="form-control @error('lib_icone') is-invalid @enderror" value="{{ old('lib_icone', $m?->lib_icone) }}" placeholder="ex. flaticon-381-layer-1">
            @error('lib_icone')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="smi-panel mb-3">
    <div class="smi-panel-title"><i class="fas fa-key me-2"></i>Permissions & visibilité</div>
    <div class="row">
        <div class="mb-2 col-12">
            <label class="form-label">Permission Gate (<code>lib_technique</code>)</label>
            <input type="text" name="permission_gate" class="form-control font-monospace @error('permission_gate') is-invalid @enderror" value="{{ old('permission_gate', $m?->permission_gate) }}" placeholder="ex. module.menus.cec" autocomplete="off">
            @error('permission_gate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="form-text">Laisser vide pour une entrée visible par tout utilisateur connecté.</div>
        </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Masquer pour fonctions (JSON)</label>
            <textarea name="visibility_hide_json" rows="3" class="form-control font-monospace form-control-sm @error('visibility_hide_json') is-invalid @enderror" placeholder='["FONC_0017"]'>{{ old('visibility_hide_json', isset($m) && $m->visibility_hide_fonctions ? json_encode($m->visibility_hide_fonctions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
            @error('visibility_hide_json')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Afficher seulement pour (JSON)</label>
            <textarea name="visibility_show_only_json" rows="3" class="form-control font-monospace form-control-sm @error('visibility_show_only_json') is-invalid @enderror" placeholder='["FONC_0018"]'>{{ old('visibility_show_only_json', isset($m) && $m->visibility_show_only_fonctions ? json_encode($m->visibility_show_only_fonctions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : '') }}</textarea>
            @error('visibility_show_only_json')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="smi-panel mb-0">
    <div class="smi-panel-title"><i class="fas fa-paint-brush me-2"></i>Classes CSS (Metismenu)</div>
    <div class="row">
        <div class="mb-2 col-md-6">
            <label class="form-label">Classes sur l’ancre</label>
            <input type="text" name="anchor_class" class="form-control font-monospace @error('anchor_class') is-invalid @enderror" value="{{ old('anchor_class', $m?->anchor_class) }}" placeholder="ex. has-arrow ai-icon">
            @error('anchor_class')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-2 col-md-6">
            <label class="form-label">Classes supplémentaires</label>
            <input type="text" name="anchor_extra_classes" class="form-control @error('anchor_extra_classes') is-invalid @enderror" value="{{ old('anchor_extra_classes', $m?->anchor_extra_classes) }}" placeholder="ex. chercheacte">
            @error('anchor_extra_classes')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
