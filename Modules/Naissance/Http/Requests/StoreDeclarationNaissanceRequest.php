<?php

namespace Modules\Naissance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeclarationNaissanceRequest extends FormRequest
{
    public function authorize()
    {
        // Autorisation à affiner si besoin
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
        ];
    }
}
