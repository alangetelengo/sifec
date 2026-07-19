<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuotSignelecConfig;
use App\Models\InstitutionUser;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Modules\Referentiel\Entities\Fonction;
use Modules\Referentiel\Entities\Institution;

class SignelecController extends Controller
{
    /**
     * Catégories d'institution devant disposer d'un cachet institutionnel GUOT (L3) :
     * centre d'état civil (TCINS_0001) et formation sanitaire (TCINS_0003).
     *
     * @var list<string>
     */
    private const CATEGORIES_CACHET = ['TCINS_0001', 'TCINS_0003'];

    /**
     * Tableau de bord SIGNELEC (pilotage enrôlements + cachets).
     */
    public function dashboard(): View
    {
        $stats = $this->buildDashboardStats();

        return view('admin.signelec.dashboard', compact('stats'));
    }

    /**
     * Checklist institutions / cachets institutionnels GUOT.
     */
    public function institutions(Request $request): View
    {
        $statut = $request->input('statut_pki', 'all');
        $q = trim((string) $request->input('q', ''));

        $query = Institution::query()
            ->with(['typeInstitution', 'lieu'])
            ->whereHas('typeInstitution', function ($builder) {
                $builder->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET);
            })
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($inner) use ($q) {
                    $inner->where('lib_institution', 'like', '%'.$q.'%')
                        ->orWhere('code_institution', 'like', '%'.$q.'%')
                        ->orWhere('guot_institution_id', 'like', '%'.$q.'%');
                });
            });

        if (Schema::hasColumn('tr_institution', 'guot_institution_id')) {
            if ($statut === 'lie') {
                $query->whereNotNull('guot_institution_id')->where('guot_institution_id', '!=', '');
            } elseif ($statut === 'manquant') {
                $query->where(function ($builder) {
                    $builder->whereNull('guot_institution_id')->orWhere('guot_institution_id', '');
                });
            } elseif ($statut === 'expire') {
                $query->whereNotNull('guot_institution_cert_not_after')
                    ->where('guot_institution_cert_not_after', '<', now());
            } elseif ($statut === 'expire_bientot') {
                $query->whereNotNull('guot_institution_cert_not_after')
                    ->whereBetween('guot_institution_cert_not_after', [now(), now()->addDays(30)]);
            }
        }

        $institutions = $query->orderBy('lib_institution')->paginate(20)->withQueryString();

        $compteurs = [
            'total' => Institution::query()
                ->whereHas('typeInstitution', fn ($b) => $b->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET))
                ->count(),
            'liees' => 0,
            'manquantes' => 0,
            'expire_bientot' => 0,
        ];

        if (Schema::hasColumn('tr_institution', 'guot_institution_id')) {
            $base = Institution::query()->whereHas('typeInstitution', fn ($b) => $b->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET));
            $compteurs['liees'] = (clone $base)->whereNotNull('guot_institution_id')->where('guot_institution_id', '!=', '')->count();
            $compteurs['manquantes'] = $compteurs['total'] - $compteurs['liees'];
            $compteurs['expire_bientot'] = (clone $base)
                ->whereNotNull('guot_institution_cert_not_after')
                ->whereBetween('guot_institution_cert_not_after', [now(), now()->addDays(30)])
                ->count();
        } else {
            $compteurs['manquantes'] = $compteurs['total'];
        }

        return view('admin.signelec.institutions', compact('institutions', 'statut', 'q', 'compteurs'));
    }

    /**
     * Signataires à enrôler / déjà enrôlés.
     */
    public function signataires(Request $request): View
    {
        $statut = $request->input('statut_pki', 'all');
        $q = trim((string) $request->input('q', ''));
        $codeInstitution = $request->input('code_institution');

        $query = InstitutionUser::query()
            ->with(['user.personne', 'fonction', 'institution'])
            ->where('active', 1)
            ->whereIn('code_fonction', GuotSignataires::codes())
            ->when($codeInstitution, fn ($b) => $b->where('code_institution', $codeInstitution))
            ->when($q !== '', function ($builder) use ($q) {
                $builder->where(function ($inner) use ($q) {
                    $inner->where('cui', 'like', '%'.$q.'%')
                        ->orWhere('guot_user_id', 'like', '%'.$q.'%')
                        ->orWhereHas('user.personne', function ($p) use ($q) {
                            $p->where('nom', 'like', '%'.$q.'%')
                                ->orWhere('prenom', 'like', '%'.$q.'%');
                        })
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('email', 'like', '%'.$q.'%');
                        });
                });
            });

        if (Schema::hasColumn('tr_ins_user', 'guot_user_id')) {
            if ($statut === 'enrole') {
                $query->whereNotNull('guot_user_id')->where('guot_user_id', '!=', '');
            } elseif ($statut === 'non_enrole') {
                $query->where(function ($builder) {
                    $builder->whereNull('guot_user_id')->orWhere('guot_user_id', '');
                });
            } elseif ($statut === 'expire') {
                $query->whereNotNull('guot_user_cert_not_after')
                    ->where('guot_user_cert_not_after', '<', now());
            } elseif ($statut === 'expire_bientot') {
                $query->whereNotNull('guot_user_cert_not_after')
                    ->whereBetween('guot_user_cert_not_after', [now(), now()->addDays(30)]);
            }
        }

        $signataires = $query->latest('created_at')->paginate(20)->withQueryString();

        $baseResponsables = InstitutionUser::query()
            ->where('active', 1)
            ->whereIn('code_fonction', GuotSignataires::codes());

        $compteurs = [
            'total' => (clone $baseResponsables)->count(),
            'enroles' => 0,
            'non_enroles' => 0,
            'expire_bientot' => 0,
        ];

        if (Schema::hasColumn('tr_ins_user', 'guot_user_id')) {
            $compteurs['enroles'] = (clone $baseResponsables)->whereNotNull('guot_user_id')->where('guot_user_id', '!=', '')->count();
            $compteurs['non_enroles'] = $compteurs['total'] - $compteurs['enroles'];
            $compteurs['expire_bientot'] = (clone $baseResponsables)
                ->whereNotNull('guot_user_cert_not_after')
                ->whereBetween('guot_user_cert_not_after', [now(), now()->addDays(30)])
                ->count();
        } else {
            $compteurs['non_enroles'] = $compteurs['total'];
        }

        $institutions = Institution::query()
            ->whereHas('typeInstitution', fn ($b) => $b->where('code_type_categorie_ins', 'TCINS_0001'))
            ->orderBy('lib_institution')
            ->get(['code_institution', 'lib_institution']);

        $signataireDescription = GuotSignataires::description();

        return view('admin.signelec.signataires', compact(
            'signataires',
            'statut',
            'q',
            'codeInstitution',
            'compteurs',
            'institutions',
            'signataireDescription'
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildDashboardStats(): array
    {
        $cecTotal = Institution::query()
            ->whereHas('typeInstitution', fn ($b) => $b->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET))
            ->count();

        $cecLiees = 0;
        $cecExpireBientot = 0;
        if (Schema::hasColumn('tr_institution', 'guot_institution_id')) {
            $cecLiees = Institution::query()
                ->whereHas('typeInstitution', fn ($b) => $b->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET))
                ->whereNotNull('guot_institution_id')
                ->where('guot_institution_id', '!=', '')
                ->count();
            $cecExpireBientot = Institution::query()
                ->whereHas('typeInstitution', fn ($b) => $b->whereIn('code_type_categorie_ins', self::CATEGORIES_CACHET))
                ->whereNotNull('guot_institution_cert_not_after')
                ->whereBetween('guot_institution_cert_not_after', [now(), now()->addDays(30)])
                ->count();
        }

        $signatairesTotal = InstitutionUser::query()
            ->where('active', 1)
            ->whereIn('code_fonction', GuotSignataires::codes())
            ->count();
        $signatairesEnroles = 0;
        $signatairesExpireBientot = 0;
        if (Schema::hasColumn('tr_ins_user', 'guot_user_id')) {
            $signatairesEnroles = InstitutionUser::query()
                ->where('active', 1)
                ->whereIn('code_fonction', GuotSignataires::codes())
                ->whereNotNull('guot_user_id')
                ->where('guot_user_id', '!=', '')
                ->count();
            $signatairesExpireBientot = InstitutionUser::query()
                ->where('active', 1)
                ->whereIn('code_fonction', GuotSignataires::codes())
                ->whereNotNull('guot_user_cert_not_after')
                ->whereBetween('guot_user_cert_not_after', [now(), now()->addDays(30)])
                ->count();
        }

        $docsL1 = 0;
        $docsL2 = 0;
        $docsL3 = 0;
        foreach (['t_acte_naissance', 't_acte_mariage', 't_acte_deces', 't_demande_document'] as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }
            if (Schema::hasColumn($table, 'proof_id')) {
                $docsL1 += (int) DB::table($table)->whereNotNull('proof_id')->where('proof_id', '!=', '')->count();
            }
            if (Schema::hasColumn($table, 'doc_sig_id')) {
                $docsL2 += (int) DB::table($table)->whereNotNull('doc_sig_id')->where('doc_sig_id', '!=', '')->count();
            }
            if (Schema::hasColumn($table, 'doc_seal_id')) {
                $docsL3 += (int) DB::table($table)->whereNotNull('doc_seal_id')->where('doc_seal_id', '!=', '')->count();
            }
        }

        $trustConfigured = filled(config('pki.url')) && filled(config('pki.api_key'));

        return [
            'cec_total' => $cecTotal,
            'cec_liees' => $cecLiees,
            'cec_manquantes' => max(0, $cecTotal - $cecLiees),
            'cec_expire_bientot' => $cecExpireBientot,
            'signataires_total' => $signatairesTotal,
            'signataires_enroles' => $signatairesEnroles,
            'signataires_non_enroles' => max(0, $signatairesTotal - $signatairesEnroles),
            'signataires_expire_bientot' => $signatairesExpireBientot,
            'docs_l1' => $docsL1,
            'docs_l2' => $docsL2,
            'docs_l3' => $docsL3,
            'trust_configured' => $trustConfigured,
            'trust_url' => config('pki.url') ?: env('PKI_TRUST_API_URL'),
        ];
    }

    /**
     * Paramétrage admin : fonctions éligibles à l’enrôlement PKI.
     */
    public function parametres(): View
    {
        $config = GuotSignelecConfig::instance();
        $selected = GuotSignelecConfig::signataireFonctions();
        $fonctions = Fonction::query()
            ->where(function ($q): void {
                $q->where('supprimer', 0)->orWhereNull('supprimer');
            })
            ->orderBy('lib_fonction')
            ->get(['code_fonction', 'lib_fonction']);

        return view('admin.signelec.parametres', compact('config', 'selected', 'fonctions'));
    }

    public function updateParametres(Request $request)
    {
        $validated = $request->validate([
            'signataire_fonctions' => ['nullable', 'array'],
            'signataire_fonctions.*' => ['string', 'exists:tr_fonction,code_fonction'],
            'certificat_signature_obligatoire' => ['nullable', 'boolean'],
        ]);

        try {
            $config = GuotSignelecConfig::instance();
            $config->signataire_fonctions = array_values($validated['signataire_fonctions'] ?? []);
            if (Schema::hasColumn('t_guot_signelec_config', 'certificat_signature_obligatoire')) {
                $config->certificat_signature_obligatoire = (bool) $request->boolean('certificat_signature_obligatoire');
            }
            $config->save();

            Log::channel('sifec')->info('Paramètres SIGNELEC mis à jour', [
                'signataire_fonctions' => $config->signataire_fonctions,
                'certificat_signature_obligatoire' => $config->certificat_signature_obligatoire ?? null,
            ]);

            return redirect()
                ->route('admin.signelec.parametres')
                ->with('success', 'Fonctions éligibles à l’enrôlement PKI enregistrées.');
        } catch (Exception $e) {
            Log::channel('sifec')->error('Erreur sauvegarde config SIGNELEC', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Erreur lors de l’enregistrement.');
        }
    }
}
