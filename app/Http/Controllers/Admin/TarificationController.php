<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Sifec\Sifec;
use Exception;
use Illuminate\Database\QueryException;
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

        $stats = [
            'total' => Tarificatrion::count(),
            'actifs' => Tarificatrion::where('actif', true)->count(),
            'nationaux' => Tarificatrion::whereNull('code_institution')->count(),
            'specifiques' => Tarificatrion::whereNotNull('code_institution')->count(),
        ];

        return view('admin.tarifs.index', compact('tarifs', 'typesDocuments', 'stats'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {

        $typesDocuments = TypeDocumentDemande::all();
        // $institutions = $this->mairieInstitutionUsersPourTarif();
        $institutions = Institution::where('code_type_institution', 'TPINS_0002')->get();

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

        $codeInstitution = $validated['code_institution'] ?? null;
        $codeInstitution = $codeInstitution === '' ? null : $codeInstitution;

        if ($codeInstitution !== null && ! $this->institutionEstMairie($codeInstitution)) {
            return back()->withErrors([
                'code_institution' => 'Seules les mairies (type TPINS_0002) peuvent avoir un tarif spécifique.',
            ])->withInput();
        }

        if ($this->tarifDejaExistantPourTypeEtCentre($validated['code_type_document_demande'], $codeInstitution)) {
            if ($codeInstitution !== null) {
                return back()->withErrors([
                    'code_institution' => 'Un tarif existe déjà pour ce type de document et cette mairie. Modifiez ou supprimez l’existant.',
                ])->withInput();
            }

            return back()->withErrors([
                'code_type_document_demande' => 'Un tarif national existe déjà pour ce type de document. Modifiez ou supprimez l’existant.',
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
            $tarif->code_institution = $codeInstitution;
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

            return redirect()
                ->route('admin.tarifs.index')
                ->with('success', 'Tarif créé avec succès.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur création tarif', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du tarif.');
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
            'commentaire' => 'nullable|string|max:1000',
        ]);

        try {
            $tarif = Tarificatrion::findOrFail($code);

            $tarif->prix = $validated['prix'];
            $tarif->date_debut_validite = $validated['date_debut_validite'] ?? $tarif->date_debut_validite;
            $tarif->date_fin_validite = $validated['date_fin_validite'] ?? null;
            // Case à cocher HTML : absent si décoché ; ne pas valider avec la règle « boolean » (échecs selon navigateur / Laravel).
            $tarif->actif = $request->boolean('actif');
            $tarif->commentaire = $validated['commentaire'] ?? null;

            $tarif->save();

            Log::channel('sifec')->info('Tarif modifié', [
                'code_tarification' => $tarif->code_tarification,
                'nouveau_prix' => $tarif->prix,
            ]);

            return redirect()
                ->route('admin.tarifs.index')
                ->with('success', 'Tarif modifié avec succès.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur modification tarif', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification du tarif.');
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

            return back()->with('success', "Tarif {$status} avec succès.");
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de la modification du statut.');
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

            return back()->with('success', 'Tarif supprimé avec succès.');
        } catch (QueryException $e) {
            Log::channel('sifec')->error('Suppression tarif refusée (contrainte SQL)', [
                'code_tarification' => $code,
                'error' => $e->getMessage(),
            ]);

            return back()->with(
                'error',
                'Impossible de supprimer ce tarif : il est peut-être encore référencé par d’autres données.'
            );
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur suppression tarif', [
                'code_tarification' => $code,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Erreur lors de la suppression du tarif.');
        }
    }

    protected function institutionEstMairie(string $codeInstitution): bool
    {
        return Institution::query()
            ->where('code_institution', $codeInstitution)
            ->where('code_type_institution', self::CODE_TYPE_INSTITUTION_MAIRIE)
            ->exists();
    }

    /**
     * Un seul enregistrement par couple (type de document, centre) ; tarif national = centre null.
     */
    protected function tarifDejaExistantPourTypeEtCentre(string $codeTypeDocumentDemande, ?string $codeInstitution): bool
    {
        $q = Tarificatrion::query()
            ->where('code_type_document_demande', $codeTypeDocumentDemande);

        if ($codeInstitution !== null) {
            $q->where('code_institution', $codeInstitution);
        } else {
            $q->whereNull('code_institution');
        }

        return $q->exists();
    }
}
