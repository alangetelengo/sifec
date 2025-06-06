<?php

namespace Modules\Authentification\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use App\Models\InstitutionUser;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Referentiel\Entities\Document;
use Modules\Referentiel\Entities\Fonction;
use Modules\Referentiel\Entities\Personne;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Log;
use Modules\Authentification\Entities\Fonctionnalite;
use Modules\Referentiel\Entities\Profession;
use Modules\Referentiel\Entities\Commune;
use Modules\Referentiel\Entities\Departement;
use Modules\Referentiel\Entities\District;
use Modules\Referentiel\Entities\Institution;
use Modules\Referentiel\Entities\Localite;
use Modules\Referentiel\Entities\Nationalite;
use Modules\Referentiel\Entities\TypeDocument;
use Modules\Referentiel\Entities\SituationMatrimoniale;
use Modules\Referentiel\Entities\TypeInstitution;
use Modules\Authentification\Entities\Module;


class UserController extends Controller
{

    public function index()
    {
        $users = User::whereHas("personne")->where(["status" =>1])->get();
        return view('authentification::utilisateur.index',compact('users'));
    }

    public function create()
    {
        $situationMatrimoniales = SituationMatrimoniale::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $niveauInstructions = Sifec::niveauInstructions();
        $typeDocuments =  TypeDocument::all();
        $fonctions =  Fonction::all();
        $institutions = Institution::all();
        $typeInstitutions = TypeInstitution::all();
        $departements = Departement::all();
        $localites = Localite::where('code_type_localite','TPLOC_0002')->Orwhere('code_type_localite','TPLOC_0003')->get();

        return view('authentification::utilisateur.create',compact("professions","nationalites","situationMatrimoniales","niveauInstructions","typeDocuments","fonctions","institutions","typeInstitutions","departements","localites"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "nom" => ["required", "string"],
            "sexe" => ["required", "string"],
            "date_naissance" => ["required"],
            "code_nationalite" => ["required"],
            "adresse" => ["required"],
            "numero_document" => ["required","string"],
            "code_type_document" => ["required"],
            "code_fonction" => ["required"],
            "code_institution" => ["required"],
        ]);

    // dd($request->all());

        DB::beginTransaction();
        try{

            $lieu_naissance = Localite::find($request->code_localite)->lib_localite;

            $requestUniqueString = $request->nom.$request->prenom.$request->sexe.$request->date_naissance.$lieu_naissance;

            // dd($requestUniqueString);

            $personne = Personne::where("personne_string",$requestUniqueString)->first();

            if($personne == null){

                $personne = new Personne();
                $personne->code_personne = Sifec::genererCodeUniqueReferentiel($personne,"code_personne",8,"PRS_");
                $personne->nom = $request->nom;
                $personne->prenom = $request->prenom;
                $personne->sexe = $request->sexe;
                $personne->date_naissance = Carbon::parse($request->date_naissance);
                $personne->niveau_instruction = $request->niveau_instruction;
                $personne->code_nationalite = $request->code_nationalite;
                $personne->lieu_naissance = Localite::find($request->code_localite)->lib_localite;
                $personne->code_localite  = $request->code_localite;
                $personne->telephone = $request->pseudo;
                $personne->adresse = $request->adresse;
                $personne->personne_string = $requestUniqueString;
                $personne->save();

                $document = new Document();
                $document->code_document = Sifec::genererCodeUniqueReferentiel($document,"code_document",8,"DOC_");
                $document->numero_document = $request->numero_document;
                $document->code_type_document = $request->code_type_document;
                $document->code_personne = $personne->code_personne;
                $document->save();
            }

            $existeCompte = User::where("code_personne",$personne->code_personne)->first();

            if($existeCompte == null){
                $user = new User();
                $user->code_user = Sifec::genererCodeUniqueReferentiel($user,"code_user",8,"USR_");
                $user->pseudo = $request->pseudo;
                $user->email = $request->email;
                $user->password = Hash::make("123456");
                $user->code_personne = $personne->code_personne;
                $user->status = 1;
                $user->save();
            }


             //Vérification du poste libre dans cette institution
            $poste = InstitutionUser::where("code_fonction", $request->code_fonction)->where("code_institution", $request->code_institution)->first();

            // if($poste !=null){

            //     $libPoste = $poste->fonction->lib_fonction;

            //     if($poste->active == 1){
            //         toastr()->error("Ce poste <strong> $libPoste </strong> est déjà occupé pour cette institution !", "Gestion d'utilisateur");
            //         return back()->withInput();
            //      }
            // }

            $insUser = new InstitutionUser();
            $insUser->cui = Sifec::genererCodeUniqueReferentiel($insUser,"cui",8,"CUI_");
            $insUser->code_institution = $request->code_institution;
            $insUser->code_user = $user->code_user;
            $insUser->code_fonction = $request->code_fonction;
            $insUser->active = 1;
            $insUser->save();

            DB::commit();

            toastr()->success("Utilisateur crée avec succès");
            return redirect()->route("utilisateur.index");

        }catch(Exception $e){
            Log::channel('sifec')->error($e->getMessage());
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back()->withInput();
        }

    }



    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('authentification::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {

        $user = User::find($id);

        if($user == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des modules");
            return back();
        }

        $situationMatrimoniales = SituationMatrimoniale::all();
        $professions = Profession::all();
        $nationalites = Nationalite::all();
        $niveauInstructions = Sifec::niveauInstructions();
        $typeDocuments =  TypeDocument::all();
        $fonctions =  Fonction::all();
        $institutions = Institution::all();
        $typeInstitutions = TypeInstitution::all();



        return view('authentification::utilisateur.edit',compact("user","professions","nationalites","situationMatrimoniales","niveauInstructions","typeDocuments","fonctions","institutions","typeInstitutions"));

    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $personne = Personne::find($user->personne->code_personne);
        // $document = Document::find($user->personne->document->code_document);

        $insUser = InstitutionUser::find($user->affectationActive()->cui);



        if ($personne == null){
            toastr()->error("Impossible de charger cette page");
            return redirect()->back();
        }


        $request->validate([
            "nom" => ["required", "string"],
            "sexe" => ["required", "string"],
            "date_naissance" => ["required"],
            "code_nationalite" => ["required"],
            "adresse" => ["required"],
            "numero_document" => ["required","string"],
            "code_type_document" => ["required"],
            "code_fonction" => ["required"],
            "code_institution" => ["required"],
        ]);


        DB::beginTransaction();

        try{
        //     $personne->nom = $request->nom;
        //     $personne->prenom = $request->prenom;
        //     $personne->sexe = $request->sexe;
        //     $personne->date_naissance = Carbon::parse($request->date_naissance);
        //     $personne->niveau_instruction = $request->niveau_instruction;
        //     $personne->code_nationalite = $request->code_nationalite;
        //     $personne->lieu_naissance  = $request->lieu_naissance;
        //    // $personne->code_localite  = $request->code_localite;
        //     $personne->telephone = $request->telephone;
        //     $personne->adresse = $request->adresse;
        //     $personne->save();

            $document = new Document;
            $document->code_document = Sifec::genererCodeUniqueReferentiel($document,"code_document",8, "DOC_");

            $document->numero_document = $request->numero_document;
            $document->code_type_document = $request->code_type_document;
            $document->code_personne = $personne->code_personne;
            $document->save();

            $insUser->code_user = $user->code_user;
            $insUser->code_institution = $request->code_institution;
            $insUser->code_fonction = $request->code_fonction;
            $insUser->save();


            $user->email = $request->email;
            $user->save();

            DB::commit();

            toastr()->success("Utilisateur modifié avec succès");
            return redirect()->route("utilisateur.index");

        }catch(Exception $e){
            DB::rollBack();

            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function profile($id){

        $user = User::find($id);
        //charger les permissions
        $permissions = Fonctionnalite::all();
        if($user == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des utilisateurs");
            return back();
        }

        return view('authentification::utilisateur.profile',compact('user','permissions'));
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

    public function signature(Request $request, $id)
    {

        $request->validate([
            "signature"=>["required","image","mimes:png,jpg"],
        ]);


        $user = User::find($id);
        if($user == null){
            toastr()->error("Impossible d'effectuer cette opération","Gestion des utilisateurs");
            return back();
        }

        DB::beginTransaction();
        try{
            $signature = $request->signature->store("signature");
            $personne = Personne::find($user->code_personne);
            $personne->signature = $signature;

            $personne->save();
            DB::commit();
            toastr()->success("Signature ajoutée avec succès");
            return back();

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage(),"Gestion des utilisateurs");
            return back();
        }

    }

    public function searchUser()
    {
        $personnes =   Personne::select("*")
                    ->where('supprimer',0)
                    ->where('nom', 'LIKE', '%'.request('nom'))
                    ->where('date_naissance', 'LIKE', '%'.request('date_naissance'))
                    ->where('sexe', 'LIKE', '%'.request('sexe'))
                    ->where('telephone', 'LIKE', '%'.request('telephone'))
                    ->orWhere('prenom', 'LIKE', '%'.request('prenom'))
                    ->get();

            return response()->json([
            "personnes" => $personnes
            ]);


    }

    public function SearDistrict(){
        $codedeprtement = request('id');
        $districts = District::where("code_departement",$codedeprtement)->get();

        return $districts;
    }

    public function SearCommune(){
        $codedeprtement = request('id');
        $communes = Commune::where("code_departement",$codedeprtement)->get();

        return $communes;
    }
    public function SearInstitution(){
        $codetypeinstitution = request('id');
        $institutions = Institution::where("code_type_institution",$codetypeinstitution)->get();

        return $institutions;
    }

    //Ajouter une fonctionnalite a un user
    public function assignerFonctionnalite($id)
    {
        // dd($id);
        $user = User::find($id);

        if($user == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }

        $modules = Module::all();

        return view("authentification::utilisateur.assignation", compact("user","modules"));

    }

    //enregistrer l'assignation
    public function storeAssigner(Request $request, $id)
    {
        $fonction = Fonction::find($id);

        if($fonction == null){
            toastr()->error("Impossible de charger cette page");
            return back();
        }


        DB::beginTransaction();
        try{

            if($request->fonctionnalites != null){
                $fonction->fonctionnalites()->sync($request->fonctionnalites);
            }

            DB::commit();

            toastr()->success("Fonctionnalité assignée avec succès","Gestion des fonctions");
            return redirect()->route("fonction.index");

        }catch(Exception $e){
            DB::rollBack();
            toastr()->error($e->getMessage());
            return back()->withInput();

        }
    }

}
