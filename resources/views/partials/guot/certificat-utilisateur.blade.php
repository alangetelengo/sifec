{{--
  Bloc certificat PKI utilisateur (style profil SIFEC).
  @param \App\Models\InstitutionUser|null $affectation  Affectation active (tr_ins_user)
  @param \App\Models\User|null $user  Utilisateur du profil (pour routes)
--}}
@php
    use App\Support\GuotSignataires;
    use App\Services\GuotEnrollmentService;

    $affectation = $affectation ?? null;
    $user = $user ?? $affectation?->user;
    $isSignataire = GuotSignataires::isSignataire($affectation?->code_fonction);
    $pkiConfigured = app(GuotEnrollmentService::class)->isConfigured();
    $guotUserId = $affectation?->guot_user_id;
    $certSerial = $affectation?->guot_user_cert_serial;
    $notBefore = $affectation?->guot_user_cert_not_before;
    $notAfter = $affectation?->guot_user_cert_not_after;
    $verifierUrl = $affectation?->guot_user_verifier_url;
    $fmt = static function ($d) {
        if (empty($d)) {
            return '—';
        }

        return \Carbon\Carbon::parse($d)->format('d/m/Y H:i');
    };
@endphp

<div class="sifec-profile-card mb-3">
    <div class="sifec-profile-card-h d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-certificate"></i> Certificat numérique (signature électronique)</span>
        @if($isSignataire || $guotUserId)
            @include('partials.guot.badge-statut-certificat', [
                'actorId' => $guotUserId,
                'notAfter' => $notAfter,
            ])
        @else
            <span class="badge bg-secondary">Non concerné</span>
        @endif
    </div>

    @if($affectation && ! $isSignataire && empty($guotUserId))
        <div class="p-3">
            <div class="sifec-profile-sig-zone">
                <i class="fas fa-user-shield fa-2x text-muted mb-2 d-block opacity-50"></i>
                <span class="text-muted small d-block mb-1">
                    Cette affectation (<strong>{{ $affectation->fonction?->lib_fonction ?? $affectation->code_fonction }}</strong>)
                    n’est pas un poste de <strong>responsable signataire</strong>.
                </span>
                <span class="text-muted small">
                    L’activation du certificat est réservée à : {{ GuotSignataires::description() }}
                </span>
            </div>
        </div>
    @elseif(session('guot_enroll_pending') && empty($guotUserId) && $isSignataire)
        <div class="px-3 pt-3">
            <div class="alert alert-warning border-0 small mb-0 py-2">
                <i class="fas fa-hourglass-half me-1"></i>
                <strong>Activation demandée</strong>
                — {{ $pkiConfigured ? 'vous pouvez relancer l’émission ci-dessous.' : 'service de signature non disponible pour le moment.' }}
                @if(is_array(session('guot_enroll_params')))
                    <span class="d-block mt-1 text-muted">
                        Profil :
                        {{ session('guot_enroll_params.profile', 'user_auth_enc') }}
                        · Pays : {{ session('guot_enroll_params.country', 'CG') }}
                        @if(!empty(session('guot_enroll_params.organization')))
                            · Org. : {{ session('guot_enroll_params.organization') }}
                        @endif
                    </span>
                @endif
            </div>
        </div>
    @endif

    @if($affectation && $guotUserId)
        <dl class="sifec-profile-dl">
            <div>
                <dt>Identifiant du signataire</dt>
                <dd><code class="small">{{ $guotUserId }}</code></dd>
            </div>
            <div>
                <dt>N° série certificat</dt>
                <dd>
                    @if($certSerial)
                        <code class="small">{{ $certSerial }}</code>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </dd>
            </div>
            <div>
                <dt>Émis le</dt>
                <dd>{{ $fmt($notBefore) }}</dd>
            </div>
            <div>
                <dt>Expire le</dt>
                <dd>{{ $fmt($notAfter) }}</dd>
            </div>
            @if($verifierUrl)
            <div>
                <dt>Vérification</dt>
                <dd>
                    <a href="{{ $verifierUrl }}" target="_blank" rel="noopener noreferrer" class="sifec-profile-link small">
                        <i class="fas fa-external-link-alt me-1"></i>Vérifier le certificat
                    </a>
                </dd>
            </div>
            @endif
        </dl>
        <div class="px-3 pb-3">
            <div class="alert alert-light border small mb-0 py-2">
                <i class="fas fa-info-circle me-1 text-muted"></i>
                Le fichier <strong>.p12</strong> contient votre certificat. Vous obtiendrez la passphrase sur l’écran suivant.
            </div>
            <div class="d-grid gap-2 mt-2">
                @if($pkiConfigured && $user?->code_user)
                    <form method="POST" action="{{ url('/utilisateur/'.$user->code_user.'/profile/guot-p12') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm w-100 text-white fw-semibold"
                                style="background: linear-gradient(135deg, #006B31, #009E49); border: none;">
                            <i class="fas fa-download me-1"></i>Télécharger le certificat (.p12)
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-sm text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;" disabled>
                        <i class="fas fa-download me-1"></i>Télécharger le certificat (.p12)
                    </button>
                @endif
            </div>
        </div>
    @elseif(! $affectation)
        <div class="p-3">
            <div class="sifec-profile-sig-zone">
                <i class="fas fa-user-slash fa-2x text-muted mb-2 d-block opacity-50"></i>
                <span class="text-muted small">Aucune affectation active — le certificat est lié à l’affectation institutionnelle.</span>
            </div>
        </div>
    @elseif($isSignataire)
        <div class="p-3">
            <div class="sifec-profile-sig-zone mb-3">
                <i class="fas fa-id-badge fa-2x text-muted mb-2 d-block opacity-50"></i>
                <span class="text-muted small d-block mb-1">Aucun certificat numérique pour ce responsable.</span>
                <span class="text-muted small">L’activation créera votre identité électronique pour signer les documents.</span>
            </div>
            @if($pkiConfigured && $user?->code_user)
                <form method="POST" action="{{ route('utilisateur.guot.enroll', $user->code_user) }}">
                    @csrf
                    <input type="hidden" name="guot_organization" value="{{ session('guot_enroll_params.organization', 'SIFEC') }}">
                    <input type="hidden" name="guot_organizational_unit" value="{{ session('guot_enroll_params.organizational_unit') }}">
                    <input type="hidden" name="guot_country" value="{{ session('guot_enroll_params.country', 'CG') }}">
                    <input type="hidden" name="guot_profile" value="{{ session('guot_enroll_params.profile', 'user_auth_enc') }}">
                    <button type="submit" class="btn btn-sm w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;">
                        <i class="fas fa-plus me-1"></i>Activer le certificat numérique
                    </button>
                </form>
            @else
                <button type="button" class="btn btn-sm w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;" disabled title="Service de signature non configuré">
                    <i class="fas fa-plus me-1"></i>Activer le certificat numérique
                </button>
                <div class="form-text small mt-2 mb-0">
                    <i class="fas fa-info-circle me-1"></i>Service de signature électronique non disponible. Contactez l’administrateur.
                </div>
            @endif
        </div>
    @endif
</div>
