{{--
  Bloc certificat PKI utilisateur (style profil SIFEC) — pleine largeur.
  @param \App\Models\InstitutionUser|null $affectation  Affectation active (tr_ins_user)
  @param \App\Models\User|null $user  Utilisateur du profil (pour routes)
--}}
@php
    use App\Support\GuotSignataires;
    use App\Services\GuotEnrollmentService;
    use Modules\Referentiel\Entities\RaisonRevocation;
    use Illuminate\Support\Facades\Schema;

    $affectation = $affectation ?? null;
    $user = $user ?? $affectation?->user;
    $isSignataire = GuotSignataires::isSignataire($affectation?->code_fonction);
    $pkiConfigured = app(GuotEnrollmentService::class)->isConfigured();
    $guotUserId = $affectation?->guot_user_id;
    $certSerial = $affectation?->guot_user_cert_serial;
    $notBefore = $affectation?->guot_user_cert_not_before;
    $notAfter = $affectation?->guot_user_cert_not_after;
    $verifierUrl = $affectation?->guot_user_verifier_url;
    $raisonsRevocation = Schema::hasTable('tr_raison_revocation')
        ? RaisonRevocation::actives()
        : collect();
    $formRevokeId = 'form-guot-revoke-'.($affectation->cui ?? 'na');
    $fmt = static function ($d) {
        if (empty($d)) {
            return '—';
        }

        return \Carbon\Carbon::parse($d)->format('d/m/Y H:i');
    };
@endphp

<div class="sifec-profile-card mb-0">
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
                    La génération du certificat est réservée à : {{ GuotSignataires::description() }}
                </span>
            </div>
        </div>
    @elseif(session('guot_enroll_pending') && empty($guotUserId) && $isSignataire)
        <div class="px-3 pt-3">
            <div class="alert alert-warning border-0 small mb-0 py-2">
                <i class="fas fa-hourglass-half me-1"></i>
                <strong>Génération demandée</strong>
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
        <div class="p-3">
            <div class="row g-3 g-lg-4 align-items-start">
                <div class="col-lg-6">
                    <dl class="sifec-profile-dl mb-0 border-0">
                        <div>
                            <dt>Identifiant du signataire</dt>
                            <dd><span class="sifec-guot-mono">{{ $guotUserId }}</span></dd>
                        </div>
                        <div>
                            <dt>N° série certificat</dt>
                            <dd>
                                @if($certSerial)
                                    <span class="sifec-guot-mono">{{ $certSerial }}</span>
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
                    <div class="alert alert-light border small mb-0 mt-3 py-2">
                        <i class="fas fa-info-circle me-1 text-muted"></i>
                        Le fichier <strong>.p12</strong> contient votre certificat. Vous obtiendrez la passphrase sur l’écran suivant.
                    </div>
                    <div class="mt-2">
                        @if($pkiConfigured && $user?->code_user)
                            <form method="POST" action="{{ url('/utilisateur/'.$user->code_user.'/profile/guot-p12') }}" class="m-0">
                                @csrf
                                <button type="submit" class="btn btn-sm w-100 text-white fw-semibold"
                                        style="background: linear-gradient(135deg, #006B31, #009E49); border: none;">
                                    <i class="fas fa-download me-1"></i>Télécharger le certificat (.p12)
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-sm w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;" disabled>
                                <i class="fas fa-download me-1"></i>Télécharger le certificat (.p12)
                            </button>
                        @endif
                    </div>
                </div>

                <div class="col-lg-6">
                    @if($pkiConfigured && $user?->code_user)
                        <div class="sifec-guot-revoke-panel rounded-3 p-3 h-100">
                            <div class="fw-semibold small mb-2 sifec-guot-revoke-title">
                                <i class="fas fa-ban me-1"></i>Révocation du certificat
                            </div>
                            <p class="small text-muted mb-3">
                                Action définitive côté GUOT. Les signatures déjà émises restent valides.
                                Pour un remplaçant, générez un nouveau certificat après révocation.
                            </p>
                            <form method="POST"
                                  action="{{ route('utilisateur.guot.revoke', $user->code_user) }}"
                                  id="{{ $formRevokeId }}"
                                  class="m-0"
                                  enctype="multipart/form-data">
                                @csrf
                                <label class="form-label small fw-semibold text-muted mb-1" for="guot-revoke-reason-{{ $affectation->cui }}">
                                    Raison de révocation <span class="sifec-guot-required">*</span>
                                </label>
                                <select name="code_raison_revocation"
                                        id="guot-revoke-reason-{{ $affectation->cui }}"
                                        class="form-select form-select-sm mb-2 @error('code_raison_revocation') is-invalid @enderror"
                                        required>
                                    <option value="">— Choisir —</option>
                                    @forelse($raisonsRevocation as $raison)
                                        <option value="{{ $raison->code_raison_revocation }}"
                                            @selected(old('code_raison_revocation') === $raison->code_raison_revocation)>
                                            {{ $raison->lib_raison_revocation }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Aucune raison configurée (migration tr_raison_revocation)</option>
                                    @endforelse
                                </select>
                                @error('code_raison_revocation')
                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                @enderror

                                <label class="form-label small fw-semibold text-muted mb-1" for="guot-revoke-file-{{ $affectation->cui }}">
                                    Document justificatif <span class="text-muted fw-normal">(optionnel)</span>
                                </label>
                                <input type="file"
                                       name="justificatif"
                                       id="guot-revoke-file-{{ $affectation->cui }}"
                                       class="form-control form-control-sm mb-1 @error('justificatif') is-invalid @enderror"
                                       accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png">
                                <div class="form-text small mb-2">PDF, JPG ou PNG — 5&nbsp;Mo max</div>
                                @error('justificatif')
                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                @enderror

                                <button type="button"
                                        class="btn btn-sm w-100 sifec-guot-revoke-btn fw-semibold"
                                        onclick="confirmGuotRevoke('{{ $formRevokeId }}')">
                                    <i class="fas fa-ban me-1"></i>Révoquer le certificat
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
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
            <div class="row g-3 align-items-center">
                <div class="col-lg-8">
                    <div class="sifec-profile-sig-zone mb-0">
                        <i class="fas fa-id-badge fa-2x text-muted mb-2 d-block opacity-50"></i>
                        <span class="text-muted small d-block mb-1">Aucun certificat numérique pour ce responsable.</span>
                        <span class="text-muted small">La génération créera votre identité électronique pour signer les documents.</span>
                    </div>
                </div>
                <div class="col-lg-4">
                    @if($pkiConfigured && $user?->code_user)
                        <form method="POST" action="{{ route('utilisateur.guot.enroll', $user->code_user) }}">
                            @csrf
                            <input type="hidden" name="guot_organization" value="{{ session('guot_enroll_params.organization', 'SIFEC') }}">
                            <input type="hidden" name="guot_organizational_unit" value="{{ session('guot_enroll_params.organizational_unit') }}">
                            <input type="hidden" name="guot_country" value="{{ session('guot_enroll_params.country', 'CG') }}">
                            <input type="hidden" name="guot_profile" value="{{ session('guot_enroll_params.profile', 'user_auth_enc') }}">
                            <button type="submit" class="btn btn-sm w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;">
                                <i class="fas fa-plus me-1"></i>Générer le certificat
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-sm w-100 text-white fw-semibold" style="background: linear-gradient(135deg, #006B31, #009E49); border: none;" disabled title="Service de signature non configuré">
                            <i class="fas fa-plus me-1"></i>Générer le certificat
                        </button>
                        <div class="form-text small mt-2 mb-0">
                            <i class="fas fa-info-circle me-1"></i>Service de signature électronique non disponible. Contactez l’administrateur.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@once
<style>
.sifec-guot-mono {
    display: inline-block;
    max-width: 100%;
    word-break: break-all;
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: .8125rem;
    font-weight: 600;
    color: #0f172a;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: .2rem .45rem;
}
.sifec-guot-revoke-panel {
    background: #f8fafc;
    border: 1px solid #cbd5e1;
}
.sifec-guot-revoke-title {
    color: #334155;
}
.sifec-guot-required {
    color: #b45309;
    font-weight: 700;
}
.sifec-guot-revoke-btn {
    color: #fff !important;
    background: linear-gradient(135deg, #b45309, #c2410c) !important;
    border: none !important;
}
.sifec-guot-revoke-btn:hover,
.sifec-guot-revoke-btn:focus {
    color: #fff !important;
    background: linear-gradient(135deg, #92400e, #9a3412) !important;
}
.swal2-popup.sl-swal-referentiel {
    border-radius: 14px !important;
    padding: 1.5rem 1.35rem 1.35rem !important;
    box-shadow: 0 14px 40px rgba(0, 0, 0, .12) !important;
    border: none !important;
}
.swal2-popup.sl-swal-referentiel .swal2-title {
    color: #006B31 !important;
    font-size: 1.1rem !important;
    font-weight: 700 !important;
    padding: 0 0 .5rem !important;
}
.swal2-popup.sl-swal-referentiel .swal2-html-container { color: #495057 !important; font-size: .95rem !important; }
.swal2-popup.sl-swal-referentiel .swal2-actions { gap: .5rem !important; margin-top: 1.15rem !important; }
</style>
<script>
    function confirmGuotRevoke(formId) {
        var form = document.getElementById(formId);
        if (!form) {
            if (typeof flashAlert === 'function') {
                flashAlert('Erreur', 'error', 'Formulaire de révocation introuvable.');
            }
            return false;
        }
        var reason = form.querySelector('[name="code_raison_revocation"]');
        if (reason && !reason.value) {
            if (typeof flashAlert === 'function') {
                flashAlert('Raison manquante', 'warning', 'Veuillez sélectionner une raison de révocation.');
            } else {
                reason.focus();
            }
            return false;
        }
        if (typeof Swal === 'undefined') {
            if (window.confirm('Révoquer ce certificat de façon définitive ?')) {
                form.submit();
            }
            return false;
        }
        Swal.fire({
            title: 'Révoquer le certificat ?',
            html: 'Cette action est <strong>définitive</strong> côté GUOT. Les signatures déjà émises restent valides, mais ce signataire ne pourra plus signer. Pour un remplaçant, il faudra générer un nouveau certificat.',
            icon: 'warning',
            iconColor: '#c9a227',
            showCancelButton: true,
            focusCancel: true,
            confirmButtonText: 'Oui, révoquer',
            cancelButtonText: 'Annuler',
            buttonsStyling: false,
            customClass: {
                popup: 'sl-swal-referentiel',
                confirmButton: 'btn btn-danger rounded-pill px-4 fw-semibold shadow-sm',
                cancelButton: 'btn btn-outline-secondary rounded-pill px-3 fw-semibold'
            }
        }).then(function (result) {
            if (result.value === true || result.isConfirmed === true) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endonce
