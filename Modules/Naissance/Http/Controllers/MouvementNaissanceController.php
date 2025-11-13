<?php
namespace Modules\Naissance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Services\MouvementNaissanceService;
use Illuminate\Support\Facades\Auth;

class MouvementNaissanceController extends Controller
{
    public function historique($id)
    {
        $declaration = Declarationnaissance::findOrFail($id);
        $mouvements = app(MouvementNaissanceService::class)
            ->mouvementsPourDeclaration($declaration->code_declaration_naissance);
        return view('naissance::mouvements.historique_mouvements', compact('declaration', 'mouvements'));
    }

    public function create($id)
    {
        $declaration = Declarationnaissance::findOrFail($id);
        return view('naissance::mouvements.ajouter_mouvement', compact('declaration'));
    }

    public function store(Request $request, $id)
    {
        $declaration = Declarationnaissance::findOrFail($id);
        $data = $request->validate([
            'code_mouvement' => 'required|string',
            'observation' => 'nullable|string',
        ]);
        app(MouvementNaissanceService::class)->enregistrerMouvement(
            $declaration->code_declaration_naissance,
            $data['code_mouvement'],
            Auth::user()->cui,
            $data['observation'] ?? null
        );
        toastr()->success('Mouvement ajouté avec succès !');
        return redirect()->route('naissance.mouvements.historique', $declaration->id);
    }

    public function edit($id)
    {
        $mouvement = MouvementNaissance::findOrFail($id);
        return view('naissance::mouvements.edit_mouvement', compact('mouvement'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'code_mouvement' => 'required|string',
            'observation' => 'nullable|string',
        ]);
        app(MouvementNaissanceService::class)->modifierMouvement($id, $data);
        toastr()->success('Mouvement modifié avec succès !');
        $mouvement = MouvementNaissance::findOrFail($id);
        return redirect()->route('naissance.mouvements.historique', $mouvement->code_declaration_naissance);
    }

    public function destroy($id)
    {
        $mouvement = MouvementNaissance::findOrFail($id);
        $codeDeclaration = $mouvement->code_declaration_naissance;
        app(MouvementNaissanceService::class)->supprimerMouvement($id);
        toastr()->success('Mouvement supprimé avec succès !');
        return redirect()->route('naissance.mouvements.historique', $codeDeclaration);
    }
}
