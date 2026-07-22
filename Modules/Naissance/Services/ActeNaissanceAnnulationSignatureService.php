<?php

namespace Modules\Naissance\Services;

use App\Models\User;
use App\Services\GuotDocumentSignatureService;
use App\Support\GuotSignataires;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\Naissance\Entities\ActeNaissance;
use Modules\Naissance\Entities\Declarationnaissance;
use PkiSdk\TrustException;

/**
 * Annulation d'acte de naissance par signature électronique (.p12 / GUOT).
 */
class ActeNaissanceAnnulationSignatureService
{
    private const CACHE_TTL_MINUTES = 30;

    public function __construct(
        private GuotDocumentSignatureService $guot,
        private MouvementService $mouvementService,
    ) {}

    /**
     * @param  list<string>  $codesDeclaration
     * @return array{ok: bool, message: string, token?: string, expected_serial?: string|null, items?: list<array{code_declaration: string, document_hash: string, libelle: string}>}
     */
    public function prepare(User $user, array $codesDeclaration, string $motif, ?string $observation = null): array
    {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'items' => []];
        }

        $motif = trim($motif);
        if ($motif === '') {
            return ['ok' => false, 'message' => 'Le motif d\'annulation est obligatoire.', 'items' => []];
        }

        $observation = trim((string) $observation);
        $codes = array_values(array_unique(array_filter(array_map('strval', $codesDeclaration))));
        if ($codes === []) {
            return ['ok' => false, 'message' => 'Aucun acte à annuler.', 'items' => []];
        }

        $actorId = (string) $ctx['actor_id'];
        $cacheItems = [];
        $publicItems = [];

        foreach ($codes as $code) {
            /** @var ActeNaissance|null $acte */
            $acte = ActeNaissance::query()
                ->where('code_declaration_naissance', $code)
                ->first();

            if ($acte === null) {
                return ['ok' => false, 'message' => 'Acte introuvable : '.$code, 'items' => []];
            }

            if ($acte->deleted_at !== null) {
                return ['ok' => false, 'message' => 'Acte déjà annulé : '.$code, 'items' => []];
            }

            $payload = $this->buildAnnulationPayload($acte, $motif, $observation, $actorId);
            $hash = $this->hashPayload($payload);

            $cacheItems[$code] = [
                'hash' => $hash,
                'payload' => $payload,
                'motif' => $motif,
                'observation' => $observation,
            ];

            $enfant = trim(($acte->declaration?->enfant?->nom ?? '').' '.($acte->declaration?->enfant?->prenom ?? ''));
            $publicItems[] = [
                'code_declaration' => $code,
                'document_hash' => $hash,
                'libelle' => $enfant !== '' ? $enfant : $code,
            ];
        }

        $token = Str::random(40);
        Cache::put($this->cacheKey($user, $token), [
            'codes' => $codes,
            'motif' => $motif,
            'observation' => $observation,
            'items' => $cacheItems,
            'cui' => (string) $ctx['cui'],
            'actor_id' => $actorId,
            'institution_id' => (string) $ctx['institution_id'],
        ], now()->addMinutes(self::CACHE_TTL_MINUTES));

        return [
            'ok' => true,
            'message' => 'Prêt à annuler. Sélectionnez votre certificat (.p12) et confirmez.',
            'token' => $token,
            'expected_serial' => $ctx['cert_serial'] ?? null,
            'items' => $publicItems,
        ];
    }

    /**
     * @param  list<array{code_declaration?: string, signature_hex: string}>  $signatures
     * @return array{ok: bool, message: string, cancelled: int}
     */
    public function finalize(
        User $user,
        string $token,
        array $signatures,
        ?string $ip = null,
        ?string $userAgent = null,
    ): array {
        $ctx = $this->assertSignerContext($user);
        if (! ($ctx['ok'] ?? false)) {
            return ['ok' => false, 'message' => $ctx['message'], 'cancelled' => 0];
        }

        $prep = Cache::get($this->cacheKey($user, $token));
        if (! is_array($prep) || empty($prep['items'])) {
            return ['ok' => false, 'message' => 'Session d\'annulation expirée. Rouvrez la modale et recommencez.', 'cancelled' => 0];
        }

        $byCode = [];
        foreach ($signatures as $row) {
            if (! is_array($row)) {
                continue;
            }
            $code = (string) ($row['code_declaration'] ?? '');
            $hex = (string) ($row['signature_hex'] ?? '');
            if ($code !== '' && $hex !== '') {
                $byCode[$code] = $hex;
            }
        }

        $cancelled = 0;
        $errors = [];

        foreach ($prep['codes'] as $code) {
            $code = (string) $code;
            if (! isset($byCode[$code])) {
                $errors[] = $code.': signature manquante';
                continue;
            }
            if (! isset($prep['items'][$code])) {
                $errors[] = $code.': préparation introuvable';
                continue;
            }

            try {
                $this->finalizeUn(
                    $user,
                    $code,
                    $prep['items'][$code],
                    $byCode[$code],
                    (string) $prep['actor_id'],
                    (string) $prep['institution_id'],
                    $ip,
                );
                $cancelled++;
            } catch (TrustException $e) {
                Log::channel('sifec')->error('Annulation acte naissance (trust-api)', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            } catch (Exception $e) {
                Log::channel('sifec')->error('Annulation acte naissance', [
                    'code' => $code,
                    'error' => $e->getMessage(),
                ]);
                $errors[] = $code.': '.$e->getMessage();
            }
        }

        if ($cancelled > 0) {
            Cache::forget($this->cacheKey($user, $token));
        }

        if ($cancelled === 0) {
            return [
                'ok' => false,
                'message' => $errors !== [] ? implode(' | ', $errors) : 'Aucun acte annulé.',
                'cancelled' => 0,
            ];
        }

        $message = $cancelled.' acte(s) annulé(s) électroniquement.';
        if ($errors !== []) {
            $message .= ' Échecs : '.implode(' | ', $errors);
        }

        return ['ok' => true, 'message' => $message, 'cancelled' => $cancelled];
    }

    /**
     * @param  array{hash: string, payload: array<string, mixed>, motif: string, observation: string}  $prepared
     */
    private function finalizeUn(
        User $user,
        string $codeDeclaration,
        array $prepared,
        string $signatureHex,
        string $actorId,
        string $institutionId,
        ?string $ip,
    ): void {
        DB::transaction(function () use ($user, $codeDeclaration, $prepared, $signatureHex, $actorId, $institutionId, $ip) {
            /** @var ActeNaissance|null $acte */
            $acte = ActeNaissance::query()
                ->where('code_declaration_naissance', $codeDeclaration)
                ->lockForUpdate()
                ->first();

            if ($acte === null) {
                throw new Exception('Acte introuvable.');
            }

            if ($acte->deleted_at !== null) {
                throw new Exception('Acte déjà annulé.');
            }

            $hash = (string) $prepared['hash'];
            $expectedHash = $this->hashPayload($prepared['payload']);
            if ($hash !== $expectedHash) {
                throw new Exception('Intégrité de la demande d\'annulation compromise.');
            }

            $l2 = $this->guot->verifyClientDocumentSignature(
                $hash,
                $signatureHex,
                $actorId,
                'annulation_acte_naissance',
                'annulation_acte_naissance_'.$codeDeclaration,
            );

            $this->guot->sealDocument(
                $hash,
                $institutionId,
                'annulation_acte_naissance',
                'annulation_acte_naissance_'.$codeDeclaration,
                is_string($l2['doc_sig_id'] ?? null) ? $l2['doc_sig_id'] : null,
            );

            $acte->deleted_at = now();
            $acte->motif_annulation = (string) $prepared['motif'];
            $acte->observation_annulation = (string) $prepared['observation'];
            $acte->statut = 1;
            $acte->save();

            $declaration = Declarationnaissance::find($codeDeclaration);
            if ($declaration !== null) {
                $this->mouvementService->ajouterEvenementActe($user, $declaration, 'annulé', $prepared['observation'] ?? null);
            }

            Log::channel('sifec')->info('Acte naissance annulé (.p12) OK', [
                'code' => $codeDeclaration,
                'doc_sig_id' => $l2['doc_sig_id'] ?? null,
                'motif' => $prepared['motif'],
                'ip' => $ip,
            ]);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function buildAnnulationPayload(ActeNaissance $acte, string $motif, string $observation, string $actorId): array
    {
        $acte->loadMissing(['declaration.enfant']);

        $payload = [
            'action' => 'annulation_acte_naissance',
            'actor_id' => $actorId,
            'code_declaration' => (string) $acte->code_declaration_naissance,
            'document_type' => 'acte_naissance',
            'motif' => $motif,
            'niupp' => (string) ($acte->niupp ?? ''),
            'observation' => $observation,
        ];
        ksort($payload);

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function hashPayload(array $payload): string
    {
        return hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR));
    }

    private function cacheKey(User $user, string $token): string
    {
        return 'acte_naissance_annulation_p12:'.$user->code_user.':'.$token;
    }

    /**
     * @return array{ok: bool, message?: string, actor_id?: string, institution_id?: string, cui?: string, cert_serial?: string|null}
     */
    private function assertSignerContext(User $user): array
    {
        if (! $this->guot->isConfigured()) {
            return ['ok' => false, 'message' => 'Service de signature électronique non configuré. Contactez l’administrateur.'];
        }

        $affectation = $user->affectationActive();
        if ($affectation === null || ! filled($affectation->cui)) {
            return ['ok' => false, 'message' => 'Aucune affectation active (CUI) pour cet utilisateur.'];
        }

        if (! GuotSignataires::isSignataire($affectation->code_fonction)) {
            return ['ok' => false, 'message' => 'Votre fonction n’est pas autorisée à annuler électroniquement un acte.'];
        }

        if (! filled($affectation->guot_user_id)) {
            return ['ok' => false, 'message' => 'Vous n’avez pas de certificat numérique actif. Demandez l’activation depuis votre profil.'];
        }

        $affectation->loadMissing('institution');
        $institutionId = $affectation->institution?->guot_institution_id;
        if (! filled($institutionId)) {
            return [
                'ok' => false,
                'message' => 'Cachet institutionnel manquant : configurez-le sur la fiche de l’institution avant d’annuler.',
            ];
        }

        return [
            'ok' => true,
            'actor_id' => (string) $affectation->guot_user_id,
            'institution_id' => (string) $institutionId,
            'cui' => (string) $affectation->cui,
            'cert_serial' => $affectation->guot_user_cert_serial ? (string) $affectation->guot_user_cert_serial : null,
        ];
    }
}
