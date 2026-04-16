<?php

namespace App\Support;

use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

class SifecSidebarMenuBuilder
{
    /**
     * Arbre de menus pour la sidebar (profondeur quelconque), après filtrage droits / visibilité par fonction.
     */
    public function tree(): Collection
    {
        $user = Auth::user();
        $flat = MenuItem::query()->orderBy('sort_order')->get();

        return $this->filterTree($this->buildTree($flat), $user);
    }

    /**
     * @param  Collection<int, MenuItem>  $items
     */
    protected function buildTree(Collection $items, ?string $parentCode = null): Collection
    {
        return $items
            ->where('code_parent', $parentCode)
            ->values()
            ->map(function (MenuItem $item) use ($items) {
                $children = $this->buildTree($items, $item->code_menu_item);
                $item->setRelation('children', $children);

                return $item;
            });
    }

    /**
     * @param  Collection<int, MenuItem>  $nodes
     * @return Collection<int, MenuItem>
     */
    protected function filterTree(Collection $nodes, ?User $user): Collection
    {
        $out = collect();

        foreach ($nodes as $node) {
            if (! $this->isNodeVisible($node, $user)) {
                continue;
            }

            $filteredChildren = $this->filterTree($node->children, $user);

            if ($node->is_group && $filteredChildren->isEmpty()) {
                continue;
            }

            $node->setRelation('children', $filteredChildren);
            $out->push($node);
        }

        return $out;
    }

    protected function isNodeVisible(MenuItem $item, ?User $user): bool
    {
        if ($item->permission_gate && ! Gate::allows($item->permission_gate)) {
            return false;
        }

        $codeFonction = null;
        if ($user && $user->affectationActive()) {
            $codeFonction = $user->affectationActive()->fonction?->code_fonction;
        }

        $showOnly = $item->visibility_show_only_fonctions;
        if (is_array($showOnly) && count($showOnly) > 0) {
            if ($codeFonction === null || ! in_array($codeFonction, $showOnly, true)) {
                return false;
            }
        }

        $hideFor = $item->visibility_hide_fonctions;
        if (is_array($hideFor) && count($hideFor) > 0 && $codeFonction !== null) {
            if (in_array($codeFonction, $hideFor, true)) {
                return false;
            }
        }

        if ($item->route_name && ! SifecBusinessModuleAccess::isRouteAllowedByModuleState($item->route_name)) {
            return false;
        }

        return true;
    }

    /**
     * URL résolue pour un item feuille (ou groupe sans route).
     */
    public static function href(MenuItem $item): string
    {
        if ($item->is_group) {
            return 'javascript:void()';
        }

        if ($item->route_name && Route::has($item->route_name)) {
            return route($item->route_name);
        }

        if ($item->external_path !== null && $item->external_path !== '') {
            if ($item->external_path === '#') {
                return '#';
            }
            if (str_starts_with($item->external_path, 'javascript:')) {
                return $item->external_path;
            }

            return url($item->external_path);
        }

        return '#';
    }

    public static function anchorClasses(MenuItem $item, int $depth): string
    {
        if ($item->anchor_class) {
            return trim($item->anchor_class);
        }

        if ($item->children->isNotEmpty()) {
            return $depth === 0 ? 'has-arrow ai-icon' : 'has-arrow';
        }

        return '';
    }
}
