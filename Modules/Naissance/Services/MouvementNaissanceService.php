<?php
namespace Modules\Naissance\Services;

use Modules\Naissance\Entities\MouvementNaissance;

class MouvementNaissanceService
{
    public function enregistrerMouvement($codeDeclaration, $codeMouvement, $cui, $observation = null)
    {
        return MouvementNaissance::create([
            'code_declaration_naissance' => $codeDeclaration,
            'code_mouvement' => $codeMouvement,
            'cui' => $cui,
            'observation' => $observation,
            'date_mouvement' => now(),
        ]);
    }

    public function modifierMouvement($id, $data)
    {
        $mouvement = MouvementNaissance::findOrFail($id);
        $mouvement->update($data);
        return $mouvement;
    }

    public function supprimerMouvement($id)
    {
        $mouvement = MouvementNaissance::findOrFail($id);
        $mouvement->delete();
    }

    public function mouvementsPourDeclaration($codeDeclaration)
    {
        return MouvementNaissance::where('code_declaration_naissance', $codeDeclaration)
            ->orderBy('date_mouvement', 'desc')
            ->get();
    }
}
