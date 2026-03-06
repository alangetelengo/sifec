<?php

namespace Modules\Referentiel\Http\Controllers;

use Exception;
use App\Models\Appareil;
use App\Sifec\Sifec;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Referentiel\Entities\Institution;

class AppareilController extends Controller
{
    public function index()
    {
        $appareils = Appareil::with('institution')
            ->orderBy('date_enregistrement', 'desc')
            ->take(50)
            ->get();

        $institutions = Institution::orderBy('lib_institution')->get();

        $stats = [
            'total'    => Appareil::count(),
            'actifs'   => Appareil::where('statut', true)->count(),
            'inactifs' => Appareil::where('statut', false)->count(),
            'ordinateurs' => Appareil::where('type_appareil', 'ordinateur')->count(),
            'tablettes'   => Appareil::where('type_appareil', 'tablette')->count(),
            'smartphones' => Appareil::where('type_appareil', 'smartphone')->count(),
        ];

        return view('referentiel::appareil.index', compact('appareils', 'institutions', 'stats'));
    }

    public function filterAppareils(Request $request)
    {
        try {
            $query = Appareil::with('institution');

            if ($request->filled('nom_appareil')) {
                $query->where('nom_appareil', 'LIKE', '%' . trim($request->nom_appareil) . '%');
            }
            if ($request->filled('adresse_mac')) {
                $query->where('adresse_mac', 'LIKE', '%' . trim($request->adresse_mac) . '%');
            }
            if ($request->filled('type_appareil')) {
                $query->where('type_appareil', $request->type_appareil);
            }
            if ($request->filled('code_institution')) {
                $query->where('code_institution', $request->code_institution);
            }
            if ($request->filled('statut') && $request->statut !== '') {
                $query->where('statut', (bool) $request->statut);
            }

            $appareils = $query->orderBy('date_enregistrement', 'desc')->get();

            return response()->json([
                'success' => true,
                'html'    => view('referentiel::appareil.partials.table-appareils', compact('appareils'))->render(),
                'count'   => $appareils->count(),
            ]);
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur filtrage appareils : ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_appareil'    => ['required', 'string', 'max:100'],
            'adresse_mac'     => ['required', 'string', 'max:50', 'unique:tr_appareils,adresse_mac'],
            'type_appareil'   => ['required', 'in:ordinateur,tablette,smartphone,autre'],
            'code_institution'=> ['nullable', 'string'],
            'statut'          => ['nullable', 'boolean'],
        ], [
            'adresse_mac.unique' => 'Cette adresse MAC est déjà enregistrée dans le système.',
        ]);

        try {
            DB::beginTransaction();

            $appareil = new Appareil();
            $appareil->code_appareil     = Sifec::genererCodeUniqueReferentiel($appareil, 'code_appareil', 4, 'APP_');
            $appareil->nom_appareil      = $request->nom_appareil;
            $appareil->adresse_mac       = strtoupper(trim($request->adresse_mac));
            $appareil->type_appareil     = $request->type_appareil;
            $appareil->code_institution  = $request->code_institution ?: null;
            $appareil->enregistre_par    = Auth::user()->affectationActive()?->cui;
            $appareil->statut            = $request->statut ?? true;
            $appareil->date_enregistrement = now();
            $appareil->save();

            DB::commit();

            Log::channel('sifec')->info('Appareil enregistré', ['code' => $appareil->code_appareil, 'mac' => $appareil->adresse_mac]);

            toastr()->success("{$appareil->nom_appareil} enregistré avec succès", "Gestion des appareils");
            return redirect()->route('appareil.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('sifec')->error('Erreur création appareil : ' . $e->getMessage());
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $appareil = Appareil::find($id);

        if (!$appareil) {
            toastr()->error("Appareil introuvable.");
            return redirect()->back();
        }

        $request->validate([
            'nom_appareil'    => ['required', 'string', 'max:100'],
            'adresse_mac'     => ['required', 'string', 'max:50', "unique:tr_appareils,adresse_mac,{$id},code_appareil"],
            'type_appareil'   => ['required', 'in:ordinateur,tablette,smartphone,autre'],
            'code_institution'=> ['nullable', 'string'],
            'statut'          => ['nullable', 'boolean'],
        ], [
            'adresse_mac.unique' => 'Cette adresse MAC est déjà utilisée par un autre appareil.',
        ]);

        try {
            $appareil->nom_appareil     = $request->nom_appareil;
            $appareil->adresse_mac      = strtoupper(trim($request->adresse_mac));
            $appareil->type_appareil    = $request->type_appareil;
            $appareil->code_institution = $request->code_institution ?: null;
            $appareil->statut           = $request->statut ?? $appareil->statut;
            $appareil->save();

            Log::channel('sifec')->info('Appareil modifié', ['code' => $appareil->code_appareil]);

            toastr()->success("{$appareil->nom_appareil} modifié avec succès", "Gestion des appareils");
            return redirect()->route('appareil.index');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur modification appareil : ' . $e->getMessage());
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function toggleStatut($id)
    {
        try {
            $appareil = Appareil::findOrFail($id);
            $appareil->statut = !$appareil->statut;
            $appareil->save();

            $libStatut = $appareil->statut ? 'activé' : 'désactivé';
            Log::channel('sifec')->info("Appareil {$libStatut}", ['code' => $appareil->code_appareil]);

            return response()->json([
                'success' => true,
                'statut'  => $appareil->statut,
                'message' => "Appareil {$libStatut} avec succès.",
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $appareil = Appareil::find($id);

            if (!$appareil) {
                toastr()->error("Appareil introuvable.");
                return redirect()->back();
            }

            $nom = $appareil->nom_appareil;
            $appareil->delete();

            Log::channel('sifec')->info('Appareil supprimé', ['code' => $id]);

            toastr()->success("Suppression de « {$nom} » effectuée avec succès", "Gestion des appareils");
            return redirect()->route('appareil.index');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur suppression appareil : ' . $e->getMessage());
            toastr()->error("Erreur lors de la suppression : " . $e->getMessage());
            return redirect()->back();
        }
    }
}
