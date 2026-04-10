<?php

namespace Modules\Deces\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Modules\Deces\Entities\DDecesCause;
use Modules\Referentiel\Entities\Regime;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Personne;
use Modules\Referentiel\Entities\Religion;
use Modules\Referentiel\Entities\Filiation;
use Illuminate\Contracts\Support\Renderable;
use Modules\Deces\Entities\DeclarationDeces;
use Modules\Referentiel\Entities\CauseDeces;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\Arrondissement;
use Modules\Referentiel\Entities\LieuSurvenance;
use Modules\Referentiel\Entities\SituationMatrimoniale;

class CentreHygieneController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $typeDeclaration = "CERTIFICAT DE CONSTATATION DE DECES";
        $declarations = Auth::user()->institution()->declarationsDeces()->where("type_declaration",$typeDeclaration);
        return view('deces::centre-hygiene.index',compact("declarations"));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
       
         $title = "Créer un certificat de constatation de décès";
        $type_declaration = "CERTIFICAT DE CONSTATATION DE DECES";
        $cecMariage = Institution::where("code_type_institution","TPINS_0002")->get();
        $instructions = Sifec::niveauInstructions();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $religions = Religion::all();
        $lieusurvenances = LieuSurvenance::all();
        $filiations= Filiation::all();
        $regimes= Regime::all();
        $causesDeces= CauseDeces::all();
        $situationMatrimoniales = SituationMatrimoniale::all();
        $typedocuments = TypeDocument::all();
        $arrondissement = Localite::where('code_type_localite','TPLOC_0004')->Orwhere('code_type_localite','TPLOC_0005')->get();
        $quartierVillages = Localite::where('code_type_localite','TPLOC_0007')->Orwhere('code_type_localite','TPLOC_0008')->get();
        $countries = collect( json_decode(file_get_contents(public_path("codes_pays.json"))));
        $codeUserLocalite = Auth::user()->affectationActive()->institution->lieu->localiteParent->code_localite;

        $lieuxDeces = Localite::where("code_localite_parent",$codeUserLocalite)->get();

        $decesDeclarationStoreUrl = route('centreHygiene.store');
        $decesDeclarationSuccessListUrl = route('centreHygiene.index');

        return view('deces::declaration.create', compact(
            'title',
            'type_declaration',
            'quartierVillages',
            'cecMariage',
            'countries',
            'arrondissement',
            'instructions',
            'typedocuments',
            'causesDeces',
            'regimes',
            'localites',
            'professions',
            'nationalites',
            'situationMatrimoniales',
            'religions',
            'lieusurvenances',
            'filiations',
            'lieuxDeces',
            'decesDeclarationStoreUrl',
            'decesDeclarationSuccessListUrl'
        ));

    }

    public function etat($id)
    {
        $typeDeclaration = "CERTIFICAT DE CONSTATATION DE DECES";
        $ddc = DeclarationDeces::with(['lieuDeces', 'lieuSurvenance', 'defunt', 'pere', 'mere', 'religion', 'situationMat', 'regime', 'conjoint', 'filiation'])
            ->where(['code_declaration_deces' => $id, 'type_declaration' => $typeDeclaration])
            ->first();

        if($ddc == null){
            toastr()->error("Aucun certificat de constatation de décès trouvé");
            return back();
        }

        view()->share("tester", "sifec");
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');

        $html2pdf->writeHTML(view('deces::etats.certificats.certificat_constatation_deces',compact("ddc"))->render());
        return $html2pdf->output($ddc->code_declaration_deces.".pdf");

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */

    public function store(Request $request)
    {
        Log::channel('sifec')->info($request->all());

        //validation du formulaire
        // dd($request->code_cause_deces);
        // début de la transaction


        $dateNaissancePere = Carbon::create($request->date_naissance_pere);
        $dateNaissanceEnfant = Carbon::create($request->date_naissance_defunt);
        $dateNaissanceMere = Carbon::create($request->date_naissance_mere);
        $differenceAgeEnfantPere = $dateNaissancePere->diffInYears($dateNaissanceEnfant);
        $differenceAgeEnfantMere = $dateNaissanceMere->diffInYears($dateNaissanceEnfant);

        if($differenceAgeEnfantPere < 14){
            return response()->json([

                "message"=>"La différence d'age entre père et enfant doit être supérieure ou égale à 14 ans"
            ]);
        }

        if($differenceAgeEnfantMere < 12){
            return response()->json([

                "message"=>"La différence d'age entre mère et enfant doit être supérieure ou égale à 12 ans"
            ]);
        }


        DB::beginTransaction();
        try{


                //Traitements d'enregistrement du defunt
                $defuntUniqueString = Sifec::uniqueString($request,"_defunt",$request->sexe_defunt);
                $defunt = Personne::where("personne_string",$defuntUniqueString)->first();
                if($defunt==null)
                {
                  $defunt = sifec::savePersonne($request,"_defunt",$request->sexe_defunt,$defuntUniqueString);
                }
                else
                {
                      $defunt->statut_personne = "DECEDE";
                      $defunt->save();
                }


                //Traitement d'enregistrement du pere
                $pereUniqueString = Sifec::uniqueString($request,"_pere","M");
                $pere = Personne::where("personne_string",$pereUniqueString)->first();
                if($pere==null)
                 {
                   $pere = sifec::savePersonne($request,"_pere","M",$pereUniqueString);
                 }



                //Traitement d'enregistrement de la mere
                $mereUniqueString = Sifec::uniqueString($request,"_mere","F");
                $mere = Personne::where("personne_string",$mereUniqueString)->first();
                if($mere==null)
                {
                 $mere = sifec::savePersonne($request,"_mere","F",$mereUniqueString);
                }

                //Traitementt du conjoint
                $codeconjoint="";

                $conjointUniqueString = Sifec::uniqueString($request,"_conjoint",$request->sexe_conjoint);
                $conjoint = Personne::where("personne_string",$conjointUniqueString)->first();

                if(($conjoint==null))
                {
                  if($request->nom_conjoint!=null)
                  {
                    $conjoint = sifec::savePersonne($request,"_conjoint",$request->sexe_conjoint,$conjointUniqueString);
                    $codeconjoint=$conjoint->code_personne;
                  }
                }
                else
                {
                    $codeconjoint=$conjoint->code_personne;
                }



                //Traitement du declarant
                $declarantUniqueString = Sifec::uniqueString($request,"_declarant",$request->sexe_declarant);
                $declarant = Personne::where("personne_string",$declarantUniqueString)->first();
                 if($declarant==null)
                  {
                   $declarant = sifec::savePersonne($request,"_declarant",$request->sexe_declarant,$declarantUniqueString);
                  }


                // déclaration de décès
                $ddeces = new DeclarationDeces;
                $codeddeces = Sifec::genererCodeUniqueReferentiel($ddeces,"code_declaration_deces",8,"CDD_");

                $ddeces->code_declaration_deces = $codeddeces;
                $ddeces->date_heure_declaration=now();
                $ddeces->date_heure_deces = $request->date_deces." ".$request->heure_deces.":00" ;
                $ddeces->code_lieu_survenance = $request->lieu_survenance_code;
                $ddeces->date_mariage = $request->date_mariage;
                $ddeces->code_regime  = $request->code_regime;
                $ddeces->domicile_defunt = $request->domicile_defunt;
                $ddeces->cec_mariage= $request->cec_mariage;
                $ddeces->cec_naissance= $request->cec_naissance;
                if ($request->filled('code_lieu_deces')) {
                    $lieuDecesLoc = Localite::query()->where('code_localite', $request->code_lieu_deces)->first();
                    if ($lieuDecesLoc === null) {
                        DB::rollBack();

                        return response()->json([
                            'success' => false,
                            'message' => 'Lieu de décès invalide (localité inconnue).',
                        ], 422);
                    }
                    $ddeces->lieu_deces = $lieuDecesLoc->code_localite;
                } elseif ($request->filled('lieu_deces')) {
                    $ddeces->lieu_deces = $request->lieu_deces;
                } else {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Le lieu de décès est obligatoire.',
                    ], 422);
                }
                $ddeces->num_acte_mariage=$request->num_acte_mariage;
                $ddeces->num_acte_naissance=$request->num_acte_naissance;
                $ddeces->type_declarant = "Personne physique";
                $ddeces->type_declaration = "CERTIFICAT DE CONSTATATION DE DECES";
                $ddeces->code_religion =$request->code_religion_defunt;
                $ddeces->code_pere = $pere->code_personne;
                $ddeces->code_mere = $mere->code_personne;
                $ddeces->nom_medecin = $request->nom_medecin;
                $ddeces->numero_certificat = Sifec::genererCodeUniqueReferentiel($ddeces,"numero_certificat",4,"");
                $ddeces->code_user_institution  = Auth::user()->affectationActive()->cui;

                if ($codeconjoint != "") {
                    $ddeces->code_conjoint = $codeconjoint;
                }
                $ddeces->code_filiation = $request->filiation;
                $ddeces->code_declarant = $declarant->code_personne;
                $ddeces->code_defunt = $defunt->code_personne;
                $ddeces->code_situation_matrimoniale = $request->code_situation_matrimoniale_defunt;

                $deces_existant = DeclarationDeces::where("code_defunt",$defunt->code_personne)->first();
                if($deces_existant==null)
                {
                $ddeces->save();

                //transaction
                // $transaction = new MouvementDeces();
                // $transaction->code_mouvement_deces = Sifec::genererCodeUniqueReferentiel($transaction,"code_mouvement_deces",4,"MDD_");
                // $transaction->statut = "En cours";
                // $transaction->code_declaration_deces = $ddeces->code_declaration_deces;
                // $transaction->cui = Auth::user()->affectationActive()->cui;
                // $transaction->save();

                $causes = $request->code_cause_deces;
                if($causes !=null){
                    foreach($causes as $cause){
                        DDecesCause::create([
                            'code_declaration_deces' => $ddeces->code_declaration_deces,
                            'code_cause_deces' => $cause
                        ]);
                    }

                }
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Certificat de constatation de décès déjà créé',
                ]);
            }

                DB::commit();

                return response()->json([
                    'code' => '200',
                    'success' => true,
                    'message' => 'Certificat de constatation de décès créé avec succès',
                ]);

                // toastr()->success('Déclaration de décès créée avec succès');

                // return redirect()->route("declarationDeces.index");
                // return redirect()->back();

        }catch(Exception $e){
                DB::rollBack();
                return response()->json([
                    "message"=>$e->getMessage()
                ]);
        }

        Log::channel("sifec")->info($request->all());

        return response()->json([
            "message"=>"Bonjour à tous!"
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('deces::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('deces::edit');
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
