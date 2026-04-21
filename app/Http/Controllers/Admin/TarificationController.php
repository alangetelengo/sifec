<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sifec\Sifec;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Mobile\Entities\Tarificatrion;
use Modules\Mobile\Entities\TypeDocumentDemande;
use Modules\Referentiel\Entities\Institution;

class TarificationController extends Controller
{
    /**
     * Type d'institution « Mairie » (tr_type_institution) — sous-catégorie CEC mais seul type éligible aux tarifs par institution ici.
     *
     * @see TypeInstitutionSeeder (TPINS_0002)
     */
    private const CODE_TYPE_INSTITUTION_MAIRIE = 'TPINS_0002';

    /**
     * Liste des tarifs
     */
    public function index(Request $request)
    {
        $query = Tarificatrion::with(['typeDocumentDemande', 'institution']);

        // Filtre par type de document
        if ($request->filled('type_document')) {
            $query->where('code_type_document_demande', $request->type_document);
        }

        // Filtre par statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif);
        }

        // Filtre tarifs nationaux / spécifiques
        if ($request->filled('type_tarif')) {
            if ($request->type_tarif === 'national') {
                $query->whereNull('code_institution');
            } else {
                $query->whereNotNull('code_institution');
            }
        }

        $tarifs = $query->orderBy('date_debut_validite', 'desc')->paginate(20);
        $typesDocuments = TypeDocumentDemande::all();

        return view('admin.tarifs.index', compact('tarifs', 'typesDocuments'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {

        $typesDocuments = TypeDocumentDemande::all();
        // $institutions = $this->mairieInstitutionUsersPourTarif();
        $institutions = Institution::where("code_type_institution", "TPINS_0002")->get();

        return view('admin.tarifs.create', compact('typesDocuments', 'institutions'));
    }

    /**
     * Enregistrer un nouveau tarif
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_type_document_demande' => 'required|exists:tr_type_document_demande,code_type_document_demande',
            'code_institution' => 'nullable|exists:tr_institution,code_institution',
            'prix' => 'required|numeric|min:0',
            'date_debut_validite' => 'nullable|date',
            'date_fin_validite' => 'nullable|date|after_or_equal:date_debut_validite',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        if (! empty($validated['code_institution']) && ! $this->institutionEstMairie($validated['code_institution'])) {
            return back()->withErrors([
                'code_institution' => 'Seules les mairies (type TPINS_0002) peuvent avoir un tarif spécifique.',
            ])->withInput();
        }

        try {
            $tarif = new Tarificatrion;
            $tarif->code_tarification = app(Sifec::class)->genererCodeUniqueReferentiel(
                $tarif,
                'code_tarification',
                10,
                'TARIF_'
            );

            $tarif->code_type_document_demande = $validated['code_type_document_demande'];
            $tarif->code_institution = $validated['code_institution'] ?? null;
            $tarif->prix = $validated['prix'];
            $tarif->date_debut_validite = $validated['date_debut_validite'] ?? now();
            $tarif->date_fin_validite = $validated['date_fin_validite'] ?? null;
            $tarif->actif = true;
            $tarif->commentaire = $validated['commentaire'] ?? null;

            $tarif->save();

            Log::channel('sifec')->info('Tarif créé', [
                'code_tarification' => $tarif->code_tarification,
                'prix' => $tarif->prix,
            ]);

            flash()->success('Tarif créé avec succès.');

            return redirect()->route('admin.tarifs.index');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur création tarif', ['error' => $e->getMessage()]);
            flash()->error('Erreur lors de la création du tarif.');

            return back()->withInput();
        }
    }

    /**
     * Formulaire d'édition
     */
    public function edit($code)
    {
        $tarif = Tarificatrion::with('institution')->findOrFail($code);
        $typesDocuments = TypeDocumentDemande::all();

        return view('admin.tarifs.edit', compact('tarif', 'typesDocuments'));
    }

    /**
     * Mettre à jour un tarif
     */
    public function update(Request $request, $code)
    {
        $validated = $request->validate([
            'prix' => 'required|numeric|min:0',
            'date_debut_validite' => 'nullable|date',
            'date_fin_validite' => 'nullable|date|after_or_equal:date_debut_validite',
            'actif' => 'boolean',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        try {
            $tarif = Tarificatrion::findOrFail($code);

            $tarif->prix = $validated['prix'];
            $tarif->date_debut_validite = $validated['date_debut_validite'] ?? $tarif->date_debut_validite;
            $tarif->date_fin_validite = $validated['date_fin_validite'] ?? null;
            $tarif->actif = $request->has('actif');
            $tarif->commentaire = $validated['commentaire'] ?? null;

            $tarif->save();

            Log::channel('sifec')->info('Tarif modifié', [
                'code_tarification' => $tarif->code_tarification,
                'nouveau_prix' => $tarif->prix,
            ]);

            flash()->success('Tarif modifié avec succès.');

            return redirect()->route('admin.tarifs.index');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur modification tarif', ['error' => $e->getMessage()]);
            flash()->error('Erreur lors de la modification du tarif.');

            return back()->withInput();
        }
    }

    /**
     * Activer/Désactiver un tarif
     */
    public function toggleActif($code)
    {
        try {
            $tarif = Tarificatrion::findOrFail($code);
            $tarif->actif = ! $tarif->actif;
            $tarif->save();

            $status = $tarif->actif ? 'activé' : 'désactivé';
            flash()->success("Tarif {$status} avec succès.");

            return back();
        } catch (Exception $e) {
            flash()->error('Erreur lors de la modification du statut.');

            return back();
        }
    }

    /**
     * Supprimer un tarif
     */
    public function destroy($code)
    {
        try {
            $tarif = Tarificatrion::findOrFail($code);
            $tarif->delete();

            Log::channel('sifec')->warning('Tarif supprimé', [
                'code_tarification' => $code,
            ]);

            flash()->success('Tarif supprimé avec succès.');

            return back();
        } catch (Exception $e) {
            flash()->error('Erreur lors de la suppression du tarif.');

            return back();
        }
    }

    protected function institutionEstMairie(string $codeInstitution): bool
    {
        return Institution::query()
            ->where('code_institution', $codeInstitution)
            ->where('code_type_institution', self::CODE_TYPE_INSTITUTION_MAIRIE)
            ->exists();
    }
}
