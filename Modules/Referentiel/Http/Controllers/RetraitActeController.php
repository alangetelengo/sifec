<?php

namespace Modules\Referentiel\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use Modules\Referentiel\Entities\Personne;

class RetraitActeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('referentiel::retrait-acte.index');
    }

    /**
     * Affiche le résultat de consultation naissance (GET, PRG après {@see searchActeRetire}).
     */
    public function consultationNaissanceResult(string $cdn): Renderable|RedirectResponse
    {
        $acte = ActeNaissance::where('code_declaration_naissance', $cdn)
            ->with(['retrait', 'declaration.enfant', 'declaration.pere', 'declaration.mere'])
            ->first();

        if ($acte === null) {
            flash()->error('Acte introuvable ou lien expiré. Effectuez une nouvelle recherche.');

            return redirect()->route('retrait.index');
        }

        if ($acte->signature_mairie === null) {
            flash()->error('Acte de naissance en cours de production !');

            return redirect()->route('retrait.index');
        }

        return view('referentiel::retrait-acte.index', compact('acte'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('referentiel::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function searchActeRetire(Request $request)
    {

        $request->validate([
            'nom_enfant' => ['required', 'string'],
            'sexe_enfant' => ['required', 'string'],
            'annee_naissance_enfant' => ['required'],
        ]);

        $nom = $request->nom_enfant;
        $prenom = $request->prenom_enfant;
        $sexe = $request->sexe_enfant;
        $annee = $request->annee_naissance_enfant;
        $resultatRecherche = [];
        // $personnes->declarations->acte

        // recherche de la personne
        $personnes = Personne::where('nom', 'LIKE', "%{$nom}%")->where('prenom', 'LIKE', "%{$prenom}%")->where('sexe', $sexe)->whereYear('date_naissance', $annee)->get();
        // vérification personnes

        if (count($personnes) == 0) {
            flash()->error('Aucune information trouvée !');

            return back()->withInput();
        }
        // recherche de la declaration de chaque personne
        foreach ($personnes as $personne) {

            // declaration de naissance
            $dn = Declarationnaissance::where('code_enfant', $personne->code_personne)->first();

            if ($dn == null) {
                flash()->error('Aucune déclaration de naissance trouvée avec ces informations !');

                return back()->withInput();
            } else {
                // vérification de l'acte de naissance
                $acte = ActeNaissance::where('code_declaration_naissance', $dn->code_declaration_naissance)
                    ->with(['retrait', 'declaration.enfant', 'declaration.pere', 'declaration.mere'])
                    ->first();

                if ($acte === null) {
                    flash()->error('Acte de naissance introuvable pour cette déclaration.');

                    return back()->withInput();
                }

                if ($acte->signature_mairie == null) {

                    flash()->error('Acte de naissance en cours de production !');

                    return back()->withInput();
                }

                return redirect()->route('retrait.consultation.naissance', ['cdn' => $acte->code_declaration_naissance]);
            }
        }

        flash()->error('Aucun acte exploitable pour cette recherche.');

        return back()->withInput();
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('referentiel::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('referentiel::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
