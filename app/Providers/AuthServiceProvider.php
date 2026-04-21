<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability, array $arguments = []) {
            if ($user === null) {
                return null;
            }

            // Laisser les policies / autres checks (ex. 'update', 'viewAny') au reste du pipeline.
            if (! is_string($ability) || ! str_starts_with($ability, 'module.')) {
                return null;
            }

            // Super administrateur (FONC_0011) : accès à toutes les habilités module.* (évite écarts menu / tr_ff / module désactivé).
            $codeFonction = optional($user->fonction())->code_fonction;
            if ($codeFonction === 'FONC_0011') {
                return true;
            }

            return $user->toutesfonctionnalites()->pluck('lib_technique')->unique()->contains($ability);
        });
    }
}
