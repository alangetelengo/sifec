<?php

namespace Modules\Naissance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdoptionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom_enfant' => ['required'],
            'date_naissance_enfant' => ['required', 'date'],
            'code_situation_matrimoniale' => ['required'],
            'sexe_enfant' => ['required'],
            'heure_naissance_enfant' => ['required', 'max:5', 'min:5'],
            'nombre_enfant' => ['required', 'numeric'],
            'code_jugement' => ['required'],
            // Ajoute d'autres règles selon les besoins du formulaire
        ];
    }
}
