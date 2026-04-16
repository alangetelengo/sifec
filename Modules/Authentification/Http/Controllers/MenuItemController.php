<?php

namespace Modules\Authentification\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->affectationActive()?->fonction?->code_fonction !== 'FONC_0011') {
                abort(403, 'Réservé au super administrateur.');
            }

            return $next($request);
        });
    }

    public function index(): View
    {
        $items = MenuItem::query()
            ->with('parent')
            ->orderBy('sort_order')
            ->orderBy('code_menu_item')
            ->get();

        return view('authentification::menu_item.index', compact('items'));
    }

    public function create(): View
    {
        $parents = MenuItem::query()
            ->orderBy('sort_order')
            ->orderBy('libelle')
            ->get(['code_menu_item', 'libelle', 'code_parent']);

        return view('authentification::menu_item.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null);

        DB::beginTransaction();
        try {
            MenuItem::create($data);
            DB::commit();

            return redirect()->route('menu-item.index')->with('success', 'Entrée de menu créée.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id): View
    {
        $menuItem = MenuItem::query()->findOrFail($id);
        $parents = MenuItem::query()
            ->where('code_menu_item', '!=', $menuItem->code_menu_item)
            ->orderBy('sort_order')
            ->orderBy('libelle')
            ->get(['code_menu_item', 'libelle', 'code_parent']);

        return view('authentification::menu_item.edit', compact('menuItem', 'parents'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $menuItem = MenuItem::query()->findOrFail($id);

        $data = $this->validated($request, $menuItem->code_menu_item);

        DB::beginTransaction();
        try {
            $menuItem->update($data);
            DB::commit();

            return redirect()->route('menu-item.index')->with('success', 'Entrée de menu mise à jour.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        $menuItem = MenuItem::query()->findOrFail($id);

        if ($menuItem->children()->exists()) {
            return redirect()->route('menu-item.index')->with('error', 'Supprimez ou déplacez d’abord les sous-menus.');
        }

        $menuItem->delete();

        return redirect()->route('menu-item.index')->with('success', 'Entrée supprimée.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?string $ignoreCode = null): array
    {
        $uniqueCode = Rule::unique('tr_menu_item', 'code_menu_item');
        if ($ignoreCode !== null) {
            $uniqueCode->ignore($ignoreCode, 'code_menu_item');
        }

        $rules = [
            'code_menu_item' => [
                'required',
                'string',
                'max:40',
                'regex:/^[A-Z0-9_]+$/',
                $uniqueCode,
            ],
            'code_parent' => ['nullable', 'string', 'max:40', 'exists:tr_menu_item,code_menu_item'],
            'libelle' => ['required', 'string', 'max:191'],
            'lib_icone' => ['nullable', 'string', 'max:120'],
            'route_name' => ['nullable', 'string', 'max:160'],
            'external_path' => ['nullable', 'string', 'max:255'],
            'permission_gate' => ['nullable', 'string', 'max:160'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:999999'],
            'is_group' => ['sometimes', 'boolean'],
            'anchor_class' => ['nullable', 'string', 'max:255'],
            'anchor_extra_classes' => ['nullable', 'string', 'max:255'],
            'visibility_hide_json' => ['nullable', 'string'],
            'visibility_show_only_json' => ['nullable', 'string'],
        ];

        $validated = $request->validate($rules);

        if (! empty($validated['code_parent']) && $validated['code_parent'] === $validated['code_menu_item']) {
            throw ValidationException::withMessages([
                'code_parent' => 'Un élément ne peut pas être son propre parent.',
            ]);
        }

        if ($ignoreCode && ! empty($validated['code_parent']) && $this->wouldCreateCycle($ignoreCode, $validated['code_parent'])) {
            throw ValidationException::withMessages([
                'code_parent' => 'Ce parent créerait une boucle dans la hiérarchie.',
            ]);
        }

        $hide = $this->decodeJsonArray($validated['visibility_hide_json'] ?? null, 'visibility_hide_json');
        $show = $this->decodeJsonArray($validated['visibility_show_only_json'] ?? null, 'visibility_show_only_json');

        unset($validated['visibility_hide_json'], $validated['visibility_show_only_json']);

        $validated['is_group'] = $request->boolean('is_group');
        $validated['code_parent'] = ! empty($validated['code_parent']) ? $validated['code_parent'] : null;
        $validated['visibility_hide_fonctions'] = ($hide !== null && count($hide) > 0) ? $hide : null;
        $validated['visibility_show_only_fonctions'] = ($show !== null && count($show) > 0) ? $show : null;

        return $validated;
    }

    /**
     * @return array<int, string>|null
     */
    protected function decodeJsonArray(?string $raw, string $fieldKey): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded)) {
            throw ValidationException::withMessages([
                $fieldKey => 'JSON invalide : attendu un tableau de chaînes (ex. ["FONC_0017"]).',
            ]);
        }

        return array_values(array_filter(array_map('strval', $decoded)));
    }

    protected function wouldCreateCycle(string $itemCode, ?string $newParentCode): bool
    {
        if ($newParentCode === null || $newParentCode === '') {
            return false;
        }

        if ($newParentCode === $itemCode) {
            return true;
        }

        $current = $newParentCode;
        $guard = 0;
        while ($current && $guard < 500) {
            if ($current === $itemCode) {
                return true;
            }
            $current = MenuItem::query()->where('code_menu_item', $current)->value('code_parent');
            $guard++;
        }

        return false;
    }
}
