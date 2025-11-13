<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeclarationNaissanceRequest extends FormRequest
{
    public function authorize()
    {
        // Autoriser tous les utilisateurs authentifiés (à adapter si besoin)
        return true;
    }

    public function rules()
    {
        return [
            'code_declarant' => 'required|exists:personnes,code_personne',
            'declarant_nom' => 'required|string|max:255',
            'declarant_prenom' => 'nullable|string|max:255',
            'declarant_sexe' => 'required|in:M,F',
            'declarant_date_naissance' => 'nullable|date',
            'declarant_lieu_naissance' => 'nullable|string|max:255',
            'code_pere' => 'nullable|exists:personnes,code_personne',
            'code_mere' => 'nullable|exists:personnes,code_personne',
            'code_enfant' => 'nullable|exists:personnes,code_personne',
            'nombre_enfant' => 'required|integer|min:1',
            'date_heure_declaration' => 'required|date',
            'date_naissance_enfant' => 'required|date',
            'heure_naissance_enfant' => 'required',
            'lieu_survenance' => 'nullable|string|max:255',
            'code_situation_matrimoniale' => 'nullable|string|max:255',
            'type_declaration' => 'required|string|max:255',
            'formation_sanitaire_naissance' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'code_declarant.required' => 'Veuillez sélectionner un déclarant existant.',
            'code_declarant.exists' => 'Le déclarant sélectionné n\'existe pas.',
            'declarant_nom.required' => 'Le nom du déclarant est obligatoire.',
            'declarant_sexe.required' => 'Le sexe du déclarant est obligatoire.',
            'nombre_enfant.required' => 'Le nombre d\'enfants est obligatoire.',
            'date_heure_declaration.required' => 'La date et l\'heure de déclaration sont obligatoires.',
            'date_naissance_enfant.required' => 'La date de naissance de l\'enfant est obligatoire.',
            'heure_naissance_enfant.required' => 'L\'heure de naissance de l\'enfant est obligatoire.',
            'type_declaration.required' => 'Le type de déclaration est obligatoire.',
        ];
    }
}
