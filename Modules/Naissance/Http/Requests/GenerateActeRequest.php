<?php

namespace Modules\Naissance\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateActeRequest extends FormRequest
{
    public function authorize()
    {
        // Ici, tu peux ajouter une logique d'autorisation plus fine si besoin
        return true;
    }

    public function rules()
    {
        return [
            'code_declaration_naissance' => 'required|string|exists:t_declaration_naissance,code_declaration_naissance',
        ];
    }
}
