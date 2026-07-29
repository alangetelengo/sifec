{{--
  Option enrôlement PKI à la création / édition utilisateur (style SIFEC).
  Réservé aux responsables (GuotSignataires) — pas aux agents de saisie.
  Champs : enroll_now, guot_organization, guot_organizational_unit, guot_country, guot_profile
--}}
@php
    use App\Support\GuotSignataires;

    $enrollNow = (string) old('enroll_now', '0') === '1';
    $signataireCodes = GuotSignataires::codes();
    $signataireDescription = GuotSignataires::description();
@endphp

<div class="ligne"><h4>CERTIFICAT NUMÉRIQUE (SIGNATURE ÉLECTRONIQUE)</h4></div>

<div class="card border mb-3" style="border-radius: 12px; overflow: hidden;"
     id="guot-enroll-card"
     data-signataire-codes='@json($signataireCodes)'>
    <div class="card-body">
        <div id="guot-enroll-agents-notice" class="alert alert-light border small mb-3 py-2 d-none">
            <i class="fas fa-info-circle me-1" style="color:#006B31;"></i>
            La génération du certificat est réservée aux <strong>responsables</strong>
            ({{ $signataireDescription }}).
            Les agents de saisie n’ont pas de certificat numérique.
        </div>

        <div id="guot-enroll-eligible">
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="enroll_now" name="enroll_now" value="1"
                       {{ $enrollNow ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="enroll_now">
                    Générer le certificat immédiatement
                </label>
            </div>
            <p class="small text-muted mb-0">
                Certificat pour ce <strong>responsable</strong> (signature électronique des actes / documents).
            </p>

            <div id="guot-enroll-params" class="mt-3 pt-3 border-top {{ $enrollNow ? '' : 'd-none' }}">
                <p class="small fw-bold text-uppercase mb-3" style="letter-spacing:.04em; color:#64748b;">
                    <i class="fas fa-certificate me-1" style="color:#006B31;"></i>Paramètres du certificat
                </p>
                <div class="row">
                    <div class="mb-2 col-md-12">
                        <label class="form-label" for="guot_organization">Organisation</label>
                        <input type="text" id="guot_organization" name="guot_organization"
                               class="form-control @error('guot_organization') is-invalid @enderror"
                               value="{{ old('guot_organization', 'SIFEC') }}"
                               placeholder="Ex. Ministère de l’Intérieur / SIFEC"
                               maxlength="150">
                        @error('guot_organization')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label" for="guot_organizational_unit">Unité / Service</label>
                        <input type="text" id="guot_organizational_unit" name="guot_organizational_unit"
                               class="form-control @error('guot_organizational_unit') is-invalid @enderror"
                               value="{{ old('guot_organizational_unit') }}"
                               placeholder="Ex. Direction état civil"
                               maxlength="150">
                        @error('guot_organizational_unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2 col-md-6">
                        <label class="form-label" for="guot_country">Code pays (ISO 2) <span class="text-danger guot-req {{ $enrollNow ? '' : 'd-none' }}">*</span></label>
                        <input type="text" id="guot_country" name="guot_country"
                               class="form-control text-uppercase @error('guot_country') is-invalid @enderror"
                               value="{{ old('guot_country', 'CG') }}"
                               maxlength="2" placeholder="CG"
                               oninput="this.value=this.value.toUpperCase()">
                        @error('guot_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2 col-md-12">
                        <label class="form-label" for="guot_profile">Profil du certificat <span class="text-danger guot-req {{ $enrollNow ? '' : 'd-none' }}">*</span></label>
                        <select id="guot_profile" name="guot_profile"
                                class="form-control @error('guot_profile') is-invalid @enderror">
                            <option value="user_auth_enc" {{ old('guot_profile', 'user_auth_enc') === 'user_auth_enc' ? 'selected' : '' }}>
                                Authentification et signature (recommandé)
                            </option>
                            <option value="tls_client" {{ old('guot_profile') === 'tls_client' ? 'selected' : '' }}>
                                Authentification client TLS
                            </option>
                            <option value="tls_server" {{ old('guot_profile') === 'tls_server' ? 'selected' : '' }}>
                                TLS / serveur web
                            </option>
                            <option value="tls_dual" {{ old('guot_profile') === 'tls_dual' ? 'selected' : '' }}>
                                Double usage TLS
                            </option>
                        </select>
                        @error('guot_profile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            <div class="alert alert-warning border-0 small mb-0 mt-2 py-2">
                <i class="fas fa-info-circle me-1"></i>
                L’utilisateur sera créé puis son certificat numérique sera activé si le service de signature est disponible.
            </div>
            </div>
        </div>
    </div>
</div>
