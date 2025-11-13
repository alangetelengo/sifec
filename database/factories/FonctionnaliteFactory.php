<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Constants\ModuleCodes;

class FonctionnaliteFactory extends Factory
{
    protected $model = \App\Models\Fonctionnalite::class;

    public function definition()
    {
        return [
            'code_fonctionnalite' => $this->faker->unique()->regexify('FNC_[0-9]{4}'),
            'lib_fonctionnalite' => $this->faker->sentence(3),
            'lib_technique' => $this->faker->slug,
            'description_fonctionnalite' => $this->faker->sentence(8),
            'code_fonctionnalite_parent' => null,
            'code_module' => $this->faker->randomElement([
                ModuleCodes::MENUS,
                ModuleCodes::NAISSANCE,
                ModuleCodes::DECES,
                ModuleCodes::MARIAGE
            ]),
            'etat_fonctionnalite' => 'Activé',
            'supprimer' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
