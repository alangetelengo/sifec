{{--
  Bloc cachet institutionnel (signature électronique) — style formulaires Referentiel.
  @param \Modules\Referentiel\Entities\Institution|null $institution
  @param bool $editable  Si true, champs ID / URL éditables + actions enrôlement
--}}
@php
    $institution = $institution ?? null;
    $editable = $editable ?? false;
    $guotId = old('guot_institution_id', $institution?->guot_institution_id);
    $certSerial = $institution?->guot_institution_cert_serial;
    $notBefore = $institution?->guot_institution_cert_not_before;
    $notAfter = $institution?->guot_institution_cert_not_after;
    $verifierUrl = old('guot_institution_verifier_url', $institution?->guot_institution_verifier_url);
    $canEnroll = $editable && $institution && $institution->exists;
    $fmt = static function ($d) {
        if (empty($d)) {
            return '—';
        }

        return \Carbon\Carbon::parse($d)->format('d/m/Y H:i');
    };
@endphp

<div class="mb-3 col-md-12">
    <div class="card border" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2 py-3">
            <h6 class="mb-0 fw-bold text-uppercase" style="font-size:0.8125rem; letter-spacing:.04em; color:#64748b;">
                <i class="fas fa-stamp me-2" style="color:#006B31;"></i>Cachet institutionnel (signature électronique)
            </h6>
            @include('partials.guot.badge-statut-certificat', [
                'actorId' => $guotId,
                'notAfter' => $notAfter,
            ])
        </div>
        <div class="card-body">
            <p class="small text-muted mb-3">
                Le <strong>cachet institutionnel</strong> est apposé sur les documents officiels lors de la signature électronique.
                Activez-le ci-dessous, ou saisissez manuellement l’identifiant fourni par l’administrateur.
            </p>

            @if($canEnroll)
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @unless($guotId)
                        <button type="button"
                                class="btn btn-success"
                                style="border-radius:10px;font-weight:600;"
                                onclick="return confirmGuotInstitutionEnroll(
                                    'guot-institution-enroll-form',
                                    'Activer le cachet institutionnel ?',
                                    'Créer le cachet institutionnel pour cette structure ?',
                                    'Oui, activer'
                                );">
                            <i class="fas fa-cloud-upload-alt me-1"></i> Activer le cachet
                        </button>
                    @else
                        <button type="submit"
                                form="guot-institution-sync-form"
                                class="btn btn-outline-success"
                                style="border-radius:10px;font-weight:600;">
                            <i class="fas fa-sync-alt me-1"></i> Actualiser le cachet
                        </button>
                        <button type="button"
                                class="btn btn-outline-secondary"
                                style="border-radius:10px;"
                                onclick="return confirmGuotInstitutionEnroll(
                                    'guot-institution-enroll-form',
                                    'Réparer le cachet institutionnel ?',
                                    'Tenter une récupération / réactivation du cachet institutionnel ?',
                                    'Oui, réparer'
                                );">
                            <i class="fas fa-redo me-1"></i> Réactiver / réparer
                        </button>
                    @endunless
                </div>
                @if($guotId && ! $certSerial)
                    <div class="alert alert-warning border-0 small mb-3">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Identifiant lié, mais informations du certificat incomplètes.
                        Cliquez sur <strong>Réactiver / réparer</strong>.
                    </div>
                @endif
            @endif

            @if($editable)
                <div class="mb-3">
                    <label class="form-label fw-bold" for="guot_institution_id">Identifiant du cachet</label>
                    <input type="text"
                           class="form-control form-control-lg @error('guot_institution_id') is-invalid @enderror"
                           id="guot_institution_id"
                           name="guot_institution_id"
                           value="{{ $guotId }}"
                           placeholder="ex. sifec-INS_xxxx"
                           maxlength="128">
                    @error('guot_institution_id')
                        <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Rempli automatiquement après activation ; ou collé par l’administrateur.
                    </small>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="guot_institution_verifier_url">URL de vérification publique</label>
                    <input type="url"
                           class="form-control form-control-lg @error('guot_institution_verifier_url') is-invalid @enderror"
                           id="guot_institution_verifier_url"
                           name="guot_institution_verifier_url"
                           value="{{ $verifierUrl }}"
                           placeholder="https://…"
                           maxlength="255">
                    @error('guot_institution_verifier_url')
                        <div class="invalid-feedback"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                    @enderror
                </div>
            @endif

            <div class="row g-3 mb-2">
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted mb-1">N° série certificat</label>
                    <div class="form-control form-control-lg bg-light">
                        @if($certSerial)
                            <code>{{ $certSerial }}</code>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold small text-muted mb-1">Validité</label>
                    <div class="form-control form-control-lg bg-light small">
                        {{ $fmt($notBefore) }} → {{ $fmt($notAfter) }}
                    </div>
                </div>
                @unless($editable)
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted mb-1">Identifiant du cachet</label>
                        <div class="form-control form-control-lg bg-light">
                            @if($guotId)
                                <code>{{ $guotId }}</code>
                            @else
                                <span class="text-muted">Non renseigné</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted mb-1">URL vérification</label>
                        <div class="form-control form-control-lg bg-light">
                            @if($verifierUrl)
                                <a href="{{ $verifierUrl }}" target="_blank" rel="noopener noreferrer" class="small">
                                    <i class="fas fa-external-link-alt me-1"></i>{{ \Illuminate\Support\Str::limit($verifierUrl, 48) }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </div>
                @endunless
            </div>

            @unless($guotId)
                <div class="alert alert-warning border-0 small mb-0 mt-2">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Sans cachet institutionnel, les documents de cette structure ne pourront pas être scellés électroniquement.
                </div>
            @endunless
        </div>
    </div>
</div>

@if($canEnroll)
@once
<style>
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
    function confirmGuotInstitutionEnroll(formId, title, html, confirmText) {
        var form = document.getElementById(formId);
        if (!form) {
            if (typeof flashAlert === 'function') {
                flashAlert('Erreur', 'error', 'Formulaire d’activation introuvable.');
            }
            return false;
        }
        if (typeof Swal === 'undefined') {
            if (window.confirm(html || title)) {
                form.submit();
            }
            return false;
        }
        Swal.fire({
            title: title || 'Confirmer ?',
            html: html || '',
            icon: 'warning',
            iconColor: '#c9a227',
            showCancelButton: true,
            focusCancel: true,
            confirmButtonText: confirmText || 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            buttonsStyling: false,
            customClass: {
                popup: 'sl-swal-referentiel',
                confirmButton: 'btn btn-success rounded-pill px-4 fw-semibold shadow-sm',
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
@endif
