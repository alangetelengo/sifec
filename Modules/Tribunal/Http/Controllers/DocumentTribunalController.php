<?php

namespace Modules\Tribunal\Http\Controllers;

use App\Models\Jugement;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Deces\Entities\MouvementDeces;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Mariage\Entities\MouvementMariage;
use Modules\Mariage\Entities\DeclarationMariage;
use Modules\Naissance\Entities\MouvementNaissance;
use Modules\Naissance\Entities\Declarationnaissance;

class DocumentTribunalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function indexRequisitions()
    {
        // institution
        $institution = auth()->user()->affectationActive()->institution;
        $unserInstitution = null;
        // récupérer le type de l'institution
        if ($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == 'TCINS_0001') { // centre d'état civil
            $unserInstitution = $institution->institutionParent;
        }
        if ($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == 'TCINS_0002') { // le tribunal
            $unserInstitution = $institution;
        }

        // récupérer toutes les réquisitions du tribunal avec leurs relations
        $requisitions = \App\Models\Requisition::where('code_institution', $unserInstitution->code_institution)
            ->with(['typeRequisition', 'declarationNaissance', 'declarationDeces', 'declarationMariage'])
            ->get();

        // On prépare les dossiers pour la vue
        $dossiers = $requisitions->map(function ($requisition) {
            // Déterminer le module et la déclaration associée
            if ($requisition->declarationNaissance) {
                $module = 'naissance';
                $declaration = $requisition->declarationNaissance;
                //recupere le mouvement d'envoi de la requisition au centre d'etat civil  	MOUV_0011(Document transmis au centre d’état civil)
                $mouvement = MouvementNaissance::where('code_declaration_naissance', $declaration->code_declaration_naissance)
                    ->where('code_mouvement', 'MOUV_0011')
                    ->first();
            } elseif ($requisition->declarationDeces) {
                $module = 'deces';
                $declaration = $requisition->declarationDeces;
                //recupere le mouvement d'envoi de la requisition au centre d'etat civil  	MOUV_0011(Document transmis au centre d’état civil)
                $mouvement = MouvementDeces::where('code_declaration_deces', $declaration->code_declaration_deces)
                    ->where('code_mouvement', 'MOUV_0011')
                    ->first();
            } elseif ($requisition->declarationMariage) {
                $module = 'mariage';
                $declaration = $requisition->declarationMariage;
                //recupere le mouvement d'envoi de la requisition au centre d'etat civil  	MOUV_0011(Document transmis au centre d’état civil)
                $mouvement = MouvementMariage::where('code_declaration_mariage', $declaration->code_declaration_mariage)
                    ->where('code_mouvement', 'MOUV_0011')
                    ->first();
            } else {
                $module = 'inconnu';
                $declaration = null;
            }


            return (object)[
                'module' => $module,
                'declaration' => $declaration,
                'requisition' => $requisition,
                'mouvement' => $mouvement,
                'created_at' => $requisition->created_at,
                // Ajoute ici d'autres champs si besoin
            ];
        });

        return view('tribunal::documents.requisition', compact('dossiers'));
    }

    public function indexJugements()
    {
        //institution
        $institution = auth()->user()->affectationActive()->institution;
        $unserInstitution = null;
        //recuperer le type de l'institution
        if($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == 'TCINS_0001'){ //centre d'état civil
            //recuperer l'institution de l'utilisateur
            $unserInstitution = $institution->institutionParent;
        }
        if($institution->typeInstitution->typeCategorieInstitution->code_type_categorie_ins == 'TCINS_0002'){ //le tribunal
            //recuperer l'institution de l'utilisateur
            $unserInstitution = $institution;
        }

        $jugements = Jugement::where('code_institution', $unserInstitution->code_institution)->get();





        return view('tribunal::documents.jugement', compact('jugements'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('tribunal::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('tribunal::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('tribunal::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
