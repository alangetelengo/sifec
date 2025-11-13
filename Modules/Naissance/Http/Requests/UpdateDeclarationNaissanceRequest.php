<?php

namespace Modules\Naissance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeclarationNaissanceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code_pere' => ['required', 'string'],
            'code_mere' => ['required', 'string'],
            'code_enfant' => ['required', 'string'],
            'nombre_enfant' => ['required', 'numeric'],
            'date_naissance_enfant' => ['required', 'date'],
            'heure_naissance_enfant' => ['required', 'max:5', 'min:5'],
            'sexe_enfant' => ['required'],
            'filiation' => ['required'],
            'code_situation_matrimoniale' => ['required'],
            // Ajoute d'autres règles selon les besoins du formulaire
        ];
    }
}
