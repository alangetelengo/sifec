<?php

namespace App\Support;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as RouteFacade;
use Modules\Authentification\Entities\Module;

class SifecBusinessModuleAccess
{
    /**
     * @var list<string>|null
     */
    protected static ?array $activeModuleCodes = null;

    /**
     * Résout le code_module métier à partir du nom de route (via le contrôleur).
     */
    public static function resolveModuleCodeForRoute(?string $routeName): ?string
    {
        if ($routeName === null || $routeName === '') {
            return null;
        }

        $byName = config('sifec_domain_modules.route_to_module', []);
        if (is_array($byName) && isset($byName[$routeName]) && is_string($byName[$routeName]) && $byName[$routeName] !== '') {
            return $byName[$routeName];
        }

        if (! RouteFacade::has($routeName)) {
            return null;
        }

        $route = RouteFacade::getRoutes()->getByName($routeName);
        if (! $route instanceof Route) {
            return null;
        }

        $controllerClass = self::routeControllerClass($route);
        if ($controllerClass === null || $controllerClass === '') {
            return null;
        }

        $map = config('sifec_domain_modules.namespace_to_module', []);
        if ($map === [] || ! is_array($map)) {
            return null;
        }

        uksort($map, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

        foreach ($map as $namespacePrefix => $codeModule) {
            if (! is_string($namespacePrefix) || ! is_string($codeModule)) {
                continue;
            }
            if (str_starts_with($controllerClass, $namespacePrefix)) {
                return $codeModule;
            }
        }

        return null;
    }

    public static function isModuleActive(string $codeModule): bool
    {
        if (self::$activeModuleCodes === null) {
            self::$activeModuleCodes = Module::query()
                ->where('etat_module', 'Activé')
                ->pluck('code_module')
                ->all();
        }

        return in_array($codeModule, self::$activeModuleCodes, true);
    }

    /**
     * La route est autorisée si elle n’est pas rattachée à un module métier, ou si ce module est activé.
     */
    public static function isRouteAllowedByModuleState(?string $routeName): bool
    {
        $code = self::resolveModuleCodeForRoute($routeName);
        if ($code === null) {
            return true;
        }

        return self::isModuleActive($code);
    }

    protected static function routeControllerClass(Route $route): ?string
    {
        $action = $route->getAction();
        $uses = $action['controller'] ?? $action['uses'] ?? null;

        if ($uses instanceof \Closure) {
            return null;
        }

        if (is_string($uses)) {
            if (str_contains($uses, '@')) {
                return explode('@', $uses, 2)[0] ?: null;
            }
            if (class_exists($uses)) {
                return $uses;
            }
        }

        if (is_array($uses) && isset($uses[0])) {
            $first = $uses[0];

            return is_object($first) ? get_class($first) : (is_string($first) ? $first : null);
        }

        return null;
    }
}
