@extends('layout.app')
@section('titre')
Registre Etat civil
@endsection
@section('styles')

<link href="{{ asset('tpl/vendor/jquery-smartwizard/dist/css/smart_wizard.min.css')}}" rel="stylesheet">
<link href="{{ asset('tpl/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">

@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
<div class="an-body">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des registres de l'état civil</h4>
                    @can("module.registre.create")
                    <button type="button" class="btn btn-sm btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalCEC">
                        Ajouter
                    </button>
                    @endcan
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">

                                <table id="example" class="display">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Registre</th>
                                            <th>Type registre</th>
                                            <th>Date ouverture</th>
                                            <th>Date fermeture</th>
                                            <th>Nombre d'acte prévu</th>
                                            <th>Nombre d'acte transcrit</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; ?>
                                        @foreach ($registres as $registre)
                                        <tr width="100%">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $registre->lib_registre }}</td>
                                            <td>{{ $registre->typeRegistre->lib_type_registre }}</td>
                                            <td>{{ date("d-m-Y", strtotime($registre->date_ouverture)) }}</td>
                                            <td>{{ date("d-m-Y", strtotime($registre->date_fermeture)) }}</td>
                                            <td>{{ $registre->nombre_acte_prevu}}</td>
                                            <td>{{ $registre->nombre_acte_transcrit }}</td>
                                            <td>
                                                @if($registre->statut == "0" && $registre->approbation_tribunal == null)
                                                    <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="registre en attente de validation">Encours de validation</span>
                                                @endif
                                                @if($registre->statut == "1" && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-success" style="font-size: 13px;font-weight:600;">Activé</span>
                                                @endif

                                                @if($registre->nombre_acte_transcrit == $registre->nombre_acte_prevu && $registre->approbation_tribunal != null)
                                                    <span class="badge light badge-warning" style="font-size: 13px;font-weight:600;" title="Ce registre est remplit">[Remplit]</span>
                                                @endif
                                                @if($registre->signature_cloture_cec != "")
                                                <span class="badge light badge-danger" style="font-size: 13px;font-weight:600;" title="Ce registre est clôturé">Clôturé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                    @if($registre->sceau == null)
                                                        @can('module.fonctionnalites.parapher')
                                                            <a href="{{ $registre->code_registre }}" class="dropdown-item show-validation-modal">Parapher</a>
                                                        @endcan
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0001")
                                                        <a  href="{{ route('registre.naissance', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0002")
                                                        <a  href="{{ route('registre.mariage', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->typeRegistre->code_type_registre == "TPRG_0004")
                                                        <a  href="{{ route('registre.deces', $registre->code_registre) }}" target="_blank" class="dropdown-item">Consulter</i></a>
                                                    @endif
                                                    @if($registre->statut == 1)
                                                        {{-- <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-cloturer-modal">Clôturer</a> --}}
                                                    @endif
                                                    @if(($registre->nombre_acte_prevu - $registre->nombre_acte_transcrit) == 0)
                                                        {{-- @can('module.fonctionnalites.parapher') --}}
                                                            <a href="{{ $registre->code_registre }}" typeregistre="{{ $registre->typeRegistre->lib_type_registre }}" class="dropdown-item show-add-leaflet-modal">Ajouter des feuillets</a>
                                                        {{-- @endcan --}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>N°</th>
                                            <th>Registre</th>
                                            <th>Type registre</th>
                                            <th>Date ouverture</th>
                                            <th>Date fermeture</th>
                                            <th>Nombre d'acte prévu</th>
                                            <th>Nombre d'acte transcrit</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


      <!-- Large modal -->
    <div class="modal fade" id="modalCEC" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        {{-- <div class="modal-dialog modal-lg"> --}}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Information du régistre</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form  action="{{ route("registre.store") }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Type régistre <span class="text-danger">*</span></label>
                                <select name="code_type_registre" class="form-control form-control wide" id="codetyperegistre">
                                    <option disabled selected>Choisissez</option>
                                    @if(Auth::user()->affectationActive()->institution->lieu->localiteParent->pompes_funebres == 0)
                                        @foreach (Modules\Referentiel\Entities\TypeRegistre::all() as $item)
                                            <option value="{{ $item->code_type_registre }}">{{$item->lib_type_registre}}</option>
                                        @endforeach
                                        @else
                                        @foreach ($typeRegistres as $item)
                                            <option value="{{ $item->code_type_registre }}">{{$item->lib_type_registre}}</option>
                                        @endforeach
                                    @endif
                                </select>

                            {{-- </div>
                            <div class="mb-2 col-md-6"> --}}
                                <label class="form-label">Libéllé <span class="text-danger">*</span></label>
                                <input id="typeregistre" type="text" class="form-control" readonly class="form-control @error('lib_registre') is-invalid @enderror" value="{{ old("lib_registre") }}" required  name="lib_registre">
                                <input type="hidden" id="prefix" name="prefix">
                                @error("lib_registre")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- </div>
                            <div class="row"> --}}
                            <div class="mb-2 col-md-12">
                                <label class="form-label">Nombre d'acte prévu <span class="text-danger">*</span></label>
                                <input  class="form-control form-control-sm @error("nbre_acte_prevu") is-invalid @enderror " name="nbre_acte_prevu" type="number" >
                                @error("nbre_acte_prevu")
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-2 col-md-12 d-none">
                                <label class="form-label">Etat <span class="text-danger">*</span></label>
                                <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                    <option value="0" {{"statut"==old("statut") ? "selected":""}}>Désactivé</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-sm btn-primary">Valider</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- DEBUT VALIDATION REGISTRE (OTP paraphe — charte SIFEC + compteurs) --}}
    <div class="modal fade" id="modal-registre-paraphage" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-semibold">Validation du registre (paraphe)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer">
                    </button>
                </div>
                <div class="modal-body pt-3">
                    <input type="hidden" id="code_registre">
                    <p class="small text-muted mb-3">
                        Le code reçu par SMS est valable <strong>1 minute</strong>.
                        Vous disposez de <strong>3 tentatives</strong> par code.
                        Après 3 échecs, attendez <strong>3 minutes</strong> avant de pouvoir en demander un nouveau.
                    </p>
                    <div id="paraphe-otp-alert" class="alert alert-secondary small py-2 mb-3 d-none" role="status"></div>

                    {{-- Jauge seule sur une ligne (fond neutre) + libellé sous la jauge (masqués ensemble en temporisation) --}}
                    <div class="mb-3" id="otp-expiry-meter-section">
                        <div class="sifec-otp-meter sifec-otp-meter--surface w-100"
                             id="otp-expiry-progress-wrap"
                             role="progressbar"
                             aria-label="Temps restant avant expiration du code OTP"
                             aria-live="polite"
                             aria-valuemin="0"
                             aria-valuemax="60"
                             aria-valuenow="60"
                             aria-valuetext="60 secondes restantes">
                            <div class="sifec-otp-meter__track">
                                <div class="sifec-otp-meter__fill" id="otp-expiry-progress-bar" style="width: 100%;"></div>
                            </div>
                        </div>
                        <p class="small text-muted mt-2 mb-0" id="otp-expiry-hint"></p>
                        <span id="otp-countdown-expiry" class="visually-hidden">—</span>
                    </div>

                    {{-- Tentatives : simple ligne de texte, sans encart coloré --}}
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3 pb-2 border-bottom" id="otp-attempts-line">
                        <span class="small text-muted mb-0">Tentatives (code incorrect ou renvoi du code)</span>
                        <span class="fw-bold sifec-otp-attempts-count mb-0">
                            <span id="otp-attempts-used">0</span><span class="text-muted fw-normal"> / </span><span id="otp-max-attempts">3</span>
                        </span>
                    </div>
                    <div id="otp-lockout-wrap" class="alert alert-warning small py-2 mb-3 d-none" role="alert">
                        <strong>Temporisation active.</strong>
                        Nouveau code ou nouvelle saisie possible dans
                        <span class="sifec-otp-badge sifec-otp-badge--timer sifec-otp-badge--compact d-inline-flex ms-1 align-middle" role="status">
                            <span class="sifec-otp-badge__mesh" aria-hidden="true">
                                <span class="sifec-otp-badge__orb sifec-otp-badge__orb--light"></span>
                                <span class="sifec-otp-badge__blob sifec-otp-badge__blob--1"></span>
                            </span>
                            <span class="sifec-otp-badge__label"><span id="otp-lockout-countdown">—</span></span>
                        </span>.
                    </div>
                    <div id="otp-expired-wrap" class="alert alert-danger small py-2 mb-3 d-none" role="alert">
                        Ce code a expiré. Utilisez «&nbsp;Renvoyer le code&nbsp;» pour en recevoir un nouveau (sauf pendant une temporisation).
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium" for="otp_paraphage">Code de validation (6 chiffres)<span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control form-control-lg text-center fw-bold mx-auto"
                               id="otp_paraphage"
                               maxlength="6"
                               minlength="6"
                               inputmode="numeric"
                               pattern="[0-9]*"
                               autocomplete="one-time-code"
                               placeholder="• • • • • •"
                               title="Saisissez uniquement 6 chiffres (0 à 9)"
                               style="letter-spacing: 0.45em; font-size: 1.35rem; max-width: 22rem;"
                               required>
                    </div>
                    <p class="small text-muted mb-2">Saisissez le code reçu par SMS (et par e-mail si configuré).</p>
                    <p class="small mb-0">
                        <span class="text-muted">Code non reçu ?</span>
                        <a href="#" id="resend-otp-link" class="fw-semibold" style="color: #009E49;">Renvoyer le code</a>
                        <span id="resend-otp-disabled" class="text-muted d-none"></span>
                    </p>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-sm text-white px-4" id="btn-validate" style="background: linear-gradient(135deg, #006B31 0%, #009E49 55%, #21B931 100%); border: none;">Valider</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE VALIDATION REGISTRE --}}

    {{-- DEBUT CLÔTURER REGISTRE --}}
    <div class="modal fade" id="modal-registre-cloturer" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Clôture du registre </label>
                            <input type="text" readonly class="form-control" id="type_registre">
                        </div>
                        <div class="mb-2 col-md-12">
                            <input type="hidden" id="coderegistre">
                            <label class="form-label">Date de clôture<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="date_cloture" required>
                        </div>

                        <span class="text-success"><i>Veuillez saisir la date de clôture du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-cloturer">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE CLÔTURER REGISTRE --}}




     {{-- DEBUT AJOUT FEUILLETS DU REGISTRE --}}
     <div class="modal fade" id="modal-registre-add-leaflet" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="module-title" id="libtyperegistre"> </span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-2 col-md-12">
                            <label class="form-label">Ajouter des feuillets du registre </label>
                            <input type="number" class="form-control" id="nbreFeuillets" min="1">
                        </div>

                        <span id="msg_erreur"><i style="color: red">Veuillez saisir le nombre de feuillets du registre.</i></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info btn-sm text-white" id="btn-add-feuillets">Valider</button>
                    <button type="button" class="btn btn-sm btn-danger text-white" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    {{-- FIN DE AJOUT FEUILLETS DU REGISTRE --}}
</div>
</div>
</div>
@endsection
@section('scripts')
      <!-- Datatable -->
      <script src="{{ asset('tpl/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('tpl/js/plugins-init/datatables.init.js') }}"></script>

      <script>
        $(function() {
            $("#codetyperegistre").on("change", function() {

                var codetyperegistre = $(this).val();

                    if(codetyperegistre != null || codetyperegistre != ''){

                        var lib = $("#codetyperegistre option:selected").text();
                        $("#typeregistre").val("REGISTRE DE "+lib);
                        $("#prefix").val("R.A."+lib.substr(0,1)+"_");
                    }
                });

                var paraphOtpExpiryTimer = null;
                var paraphOtpLockoutTimer = null;
                var paraphOtpMaxAttempts = 3;
                var paraphOtpExpiryTotalSec = 60;

                function clearRegistreParaphTimers() {
                    if (paraphOtpExpiryTimer) {
                        clearInterval(paraphOtpExpiryTimer);
                        paraphOtpExpiryTimer = null;
                    }
                    if (paraphOtpLockoutTimer) {
                        clearInterval(paraphOtpLockoutTimer);
                        paraphOtpLockoutTimer = null;
                    }
                }

                function formatParaphMmSs(totalSeconds) {
                    totalSeconds = Math.max(0, parseInt(totalSeconds, 10) || 0);
                    var m = Math.floor(totalSeconds / 60);
                    var s = totalSeconds % 60;
                    return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                }

                function updateOtpExpiryProgressVisual(left) {
                    var total = paraphOtpExpiryTotalSec > 0 ? paraphOtpExpiryTotalSec : 60;
                    var safeLeft = Math.max(0, parseInt(left, 10) || 0);
                    var pct = total > 0 ? Math.max(0, Math.min(100, (safeLeft / total) * 100)) : 0;
                    var $bar = $('#otp-expiry-progress-bar');
                    var $wrap = $('#otp-expiry-progress-wrap');
                    $bar.css('width', pct + '%');
                    $wrap.attr('aria-valuenow', safeLeft);
                    $wrap.attr('aria-valuemax', total);
                    var vt = safeLeft <= 0 ? 'Code expiré' : (safeLeft + (safeLeft > 1 ? ' secondes restantes' : ' seconde restante'));
                    $wrap.attr('aria-valuetext', vt);
                    $('#otp-countdown-expiry').text(formatParaphMmSs(safeLeft));
                    var urgent = safeLeft > 0 && safeLeft <= 15;
                    $bar.toggleClass('sifec-otp-meter__fill--urgent', urgent);
                    var $hint = $('#otp-expiry-hint');
                    if (safeLeft > 0) {
                        $hint.text(safeLeft + (safeLeft > 1 ? ' secondes restantes' : ' seconde restante'))
                            .removeClass('text-danger fw-semibold')
                            .addClass('text-muted');
                    } else {
                        $hint.text('Délai écoulé — demandez un nouveau code.')
                            .removeClass('text-muted')
                            .addClass('text-danger fw-semibold');
                    }
                }

                /** Affiche actions déjà comptées / maximum (ex. 0/3 puis 1/3 … 3/3) : mauvais code ou renvoi avec code encore valide. */
                function setParaphAttemptsUsedDisplay(used, max) {
                    var $m = $('#modal-registre-paraphage');
                    var u = parseInt(used, 10);
                    var m = parseInt(max, 10);
                    if (isNaN(m) || m < 1) {
                        m = paraphOtpMaxAttempts;
                    }
                    if (isNaN(u) || u < 0) {
                        u = 0;
                    }
                    if (u > m) {
                        u = m;
                    }
                    $m.find('#otp-attempts-used').first().text(u);
                    $m.find('#otp-max-attempts').first().text(m);
                }

                function applyParaphAttemptsFromResponse(response) {
                    if (!response || typeof response !== 'object') {
                        return;
                    }
                    var max = parseInt(response.otp_max_attempts, 10);
                    if (isNaN(max) || max < 1) {
                        max = paraphOtpMaxAttempts;
                    }
                    var used = NaN;
                    if (Object.prototype.hasOwnProperty.call(response, 'attempts_used')) {
                        used = parseInt(response.attempts_used, 10);
                    } else if (Object.prototype.hasOwnProperty.call(response, 'remaining_attempts')) {
                        var rem = parseInt(response.remaining_attempts, 10);
                        if (!isNaN(rem) && rem >= 0) {
                            used = max - rem;
                        }
                    }
                    if (!isNaN(used) && used >= 0) {
                        setParaphAttemptsUsedDisplay(used, max);
                    }
                }

                /** Accepte code API renvoyé en string ou nombre (JSON PHP / jQuery). */
                function paraphOtpResponseCode(response) {
                    if (!response || response.code === undefined || response.code === null) {
                        return NaN;
                    }
                    return parseInt(response.code, 10);
                }

                function showParaphAlert(type, text) {
                    var $a = $('#paraphe-otp-alert');
                    $a.removeClass('d-none alert-secondary alert-success alert-danger alert-warning');
                    if (type === 'success') {
                        $a.addClass('alert-success');
                    } else if (type === 'danger') {
                        $a.addClass('alert-danger');
                    } else if (type === 'warning') {
                        $a.addClass('alert-warning');
                    } else {
                        $a.addClass('alert-secondary');
                    }
                    $a.text(text);
                }

                function hideParaphAlert() {
                    $('#paraphe-otp-alert').addClass('d-none').text('');
                }

                function enableParaphResendUi() {
                    $('#resend-otp-link').removeClass('d-none disabled text-muted');
                    $('#resend-otp-disabled').addClass('d-none').text('');
                }

                function disableParaphResendUi() {
                    $('#resend-otp-link').addClass('d-none');
                    $('#resend-otp-disabled').removeClass('d-none');
                }

                function startParaphExpiryCountdown(totalSec) {
                    clearRegistreParaphTimers();
                    paraphOtpExpiryTotalSec = parseInt(totalSec, 10) || 60;
                    $('#otp-lockout-wrap').addClass('d-none');
                    $('#otp-expired-wrap').addClass('d-none');
                    $('#otp-expiry-meter-section').removeClass('d-none');
                    $('#otp-attempts-line').removeClass('d-none');
                    $('#btn-validate').prop('disabled', false);
                    $('#otp_paraphage').prop('disabled', false).val('');
                    enableParaphResendUi();
                    hideParaphAlert();

                    var left = paraphOtpExpiryTotalSec;
                    updateOtpExpiryProgressVisual(left);
                    paraphOtpExpiryTimer = setInterval(function () {
                        left--;
                        updateOtpExpiryProgressVisual(left);
                        if (left <= 0) {
                            clearInterval(paraphOtpExpiryTimer);
                            paraphOtpExpiryTimer = null;
                            updateOtpExpiryProgressVisual(0);
                            $('#otp-expired-wrap').removeClass('d-none');
                            $('#btn-validate').prop('disabled', true);
                            $('#otp_paraphage').prop('disabled', true);
                            showParaphAlert('warning', 'Le délai de validité du code est écoulé. Renvoyez un nouveau code (si aucune temporisation ne s’affiche).');
                        }
                    }, 1000);
                }

                function startParaphLockoutCountdown(totalSec) {
                    clearRegistreParaphTimers();
                    $('#otp-expiry-meter-section').addClass('d-none');
                    $('#otp-attempts-line').addClass('d-none');
                    $('#otp-expired-wrap').addClass('d-none');
                    $('#otp-lockout-wrap').removeClass('d-none');
                    $('#btn-validate').prop('disabled', true);
                    $('#otp_paraphage').prop('disabled', true).val('');
                    disableParaphResendUi();
                    showParaphAlert('danger', 'Trop de tentatives incorrectes ou temporisation serveur. Patientez avant un nouveau code.');

                    var left = totalSec;
                    $('#otp-lockout-countdown').text(formatParaphMmSs(left));
                    $('#resend-otp-disabled').text('Nouveau code disponible dans ' + formatParaphMmSs(left));

                    paraphOtpLockoutTimer = setInterval(function () {
                        left--;
                        if (left <= 0) {
                            clearInterval(paraphOtpLockoutTimer);
                            paraphOtpLockoutTimer = null;
                            $('#otp-lockout-wrap').addClass('d-none');
                            enableParaphResendUi();
                            $('#btn-validate').prop('disabled', false);
                            $('#otp_paraphage').prop('disabled', false);
                            hideParaphAlert();
                            showParaphAlert('secondary', 'Vous pouvez demander un nouveau code.');
                        } else {
                            $('#otp-lockout-countdown').text(formatParaphMmSs(left));
                            $('#resend-otp-disabled').text('Nouveau code disponible dans ' + formatParaphMmSs(left));
                        }
                    }, 1000);
                }

                function requestParaphOtpForRegistre(code_registre, openModalOnSuccess, isResend) {
                    var url = "{{ route('registre.send.otp', ':id') }}";
                    url = url.replace(':id', code_registre);
                    if (isResend) {
                        url += (url.indexOf('?') === -1 ? '?' : '&') + 'resend=1';
                    }
                    $(".over-loader-page").fadeIn(600);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        dataType: 'json'
                    }).done(function (response) {
                        $(".over-loader-page").fadeOut(600);
                        if (String(response.code) === '200') {
                            $("#code_registre").val(code_registre);
                            paraphOtpMaxAttempts = parseInt(response.otp_max_attempts, 10) || 3;
                            var sentUsed = parseInt(response.attempts_used, 10);
                            setParaphAttemptsUsedDisplay(!isNaN(sentUsed) ? sentUsed : 0, paraphOtpMaxAttempts);
                            var validSec = parseInt(response.valid_for_seconds, 10) || 60;
                            if (openModalOnSuccess) {
                                $("#modal-registre-paraphage").modal('show');
                            }
                            startParaphExpiryCountdown(validSec);
                            if (openModalOnSuccess) {
                                showParaphAlert('success', response.message || 'Code envoyé. Saisissez-le avant la fin du compte à rebours.');
                            } else {
                                flashAlert('Code OTP', 'success', response.message || 'Nouveau code envoyé.');
                            }
                        } else if (String(response.code) === '184' && response.retry_after_seconds) {
                            if (openModalOnSuccess) {
                                $("#code_registre").val(code_registre);
                                $("#modal-registre-paraphage").modal('show');
                            }
                            applyParaphAttemptsFromResponse(response);
                            startParaphLockoutCountdown(parseInt(response.retry_after_seconds, 10) || 180);
                            flashAlert('Réponse', 'error', response.message || 'Temporisation active.');
                        } else {
                            flashAlert('Réponse', 'error', response.message || 'Erreur.');
                        }
                    }).fail(function (xhr) {
                        $(".over-loader-page").fadeOut(600);
                        var msg = 'Impossible d\'envoyer le code.';
                        if (xhr.status === 429) {
                            msg = 'Trop de demandes. Réessayez dans une minute.';
                        }
                        flashAlert('Réponse', 'error', msg);
                    });
                }

                function sanitizeOtpParaphInput($field) {
                    var v = ($field.val() || '').replace(/\D/g, '').slice(0, 6);
                    if ($field.val() !== v) {
                        $field.val(v);
                    }
                }

                $('#otp_paraphage').on('input.otpDigits', function () {
                    sanitizeOtpParaphInput($(this));
                });
                $('#otp_paraphage').on('keydown.otpDigits', function (e) {
                    if ($.inArray(e.keyCode, [8, 9, 13, 27, 46, 35, 36, 37, 38, 39, 40]) !== -1) {
                        return;
                    }
                    if (e.ctrlKey || e.metaKey) {
                        return;
                    }
                    var ch = e.key;
                    if (ch && ch.length === 1 && !/[0-9]/.test(ch)) {
                        e.preventDefault();
                    }
                });
                $('#otp_paraphage').on('paste.otpDigits', function (e) {
                    e.preventDefault();
                    var clip = (e.originalEvent && e.originalEvent.clipboardData)
                        ? e.originalEvent.clipboardData.getData('text')
                        : (window.clipboardData ? window.clipboardData.getData('Text') : '');
                    var v = (clip || '').replace(/\D/g, '').slice(0, 6);
                    $(this).val(v);
                });

                $('#modal-registre-paraphage').on('hidden.bs.modal', function () {
                    clearRegistreParaphTimers();
                    hideParaphAlert();
                    $('#otp_paraphage').val('');
                });

                $("a.show-validation-modal").on("click", function () {
                    var code_registre = $(this).attr("href");
                    requestParaphOtpForRegistre(code_registre, true);
                    return false;
                });

                $('#resend-otp-link').on('click', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('disabled')) {
                        return false;
                    }
                    var code_registre = $('#code_registre').val();
                    if (!code_registre) {
                        return false;
                    }
                    requestParaphOtpForRegistre(code_registre, false, true);
                    return false;
                });

                $("#btn-validate").on("click", function () {
                    var code_registre = $("#code_registre").val();
                    var otp_paraphage = ($("#otp_paraphage").val() || '').replace(/\D/g, '');
                    if (code_registre === "" || otp_paraphage === "") {
                        alert("Veuillez saisir le code à 6 chiffres reçu par SMS.");
                        return false;
                    }
                    if (otp_paraphage.length !== 6 || !/^\d{6}$/.test(otp_paraphage)) {
                        alert("Le code doit comporter exactement 6 chiffres (0 à 9), sans lettre ni symbole.");
                        return false;
                    }
                    var $btn = $(this);
                    $btn.prop("disabled", true);
                    $btn.html("Traitement en cours ...");
                    var url = "{{ route('registre.validate.otp') }}";
                    var data = {
                        code_registre: code_registre,
                        otp_paraphage: otp_paraphage
                    };

                    function handleParaphValidateResponse(response) {
                        if (!response || typeof response !== 'object') {
                            flashAlert("Réponse", "error", "Réponse serveur invalide.");
                            return;
                        }
                        var rc = paraphOtpResponseCode(response);
                        if (rc === 200) {
                            clearRegistreParaphTimers();
                            flashAlert("Réponse", "success", response.message);
                            $("#modal-registre-paraphage").modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 4000);
                            return;
                        }
                        if (rc === 184) {
                            applyParaphAttemptsFromResponse(response);
                            if (response.retry_after_seconds) {
                                startParaphLockoutCountdown(parseInt(response.retry_after_seconds, 10) || 180);
                            }
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 185) {
                            clearRegistreParaphTimers();
                            $('#otp-expiry-meter-section').addClass('d-none');
                            $('#otp-expired-wrap').removeClass('d-none');
                            $('#btn-validate').prop('disabled', true);
                            $('#otp_paraphage').prop('disabled', true);
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 183) {
                            applyParaphAttemptsFromResponse(response);
                            flashAlert("Réponse", "error", response.message);
                            return;
                        }
                        if (rc === 180) {
                            flashAlert("Réponse", "error", response.message || 'Données invalides.');
                            return;
                        }
                        flashAlert("Réponse", "error", response.message || 'Erreur inattendue.');
                    }

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        dataType: 'json',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json'
                        }
                    }).done(function (response) {
                        $btn.prop("disabled", false);
                        $btn.html("Valider");
                        handleParaphValidateResponse(response);
                    }).fail(function (xhr) {
                        $btn.prop("disabled", false);
                        $btn.html("Valider");
                        var parsed = xhr.responseJSON;
                        if (!parsed && xhr.responseText) {
                            try {
                                parsed = JSON.parse(xhr.responseText);
                            } catch (e) {
                                parsed = null;
                            }
                        }
                        if (parsed && typeof parsed === 'object') {
                            var rc = paraphOtpResponseCode(parsed);
                            if (rc === 183) {
                                applyParaphAttemptsFromResponse(parsed);
                                flashAlert("Réponse", "error", parsed.message || 'Code OTP incorrect.');
                                return;
                            }
                            if (rc === 180) {
                                flashAlert("Réponse", "error", parsed.message || 'Données invalides.');
                                return;
                            }
                        }
                        var msg = 'Erreur lors de la validation.';
                        if (xhr.status === 429) {
                            msg = 'Trop de tentatives. Patientez quelques instants.';
                        }
                        flashAlert('Réponse', 'error', msg);
                    });

                    return false;
                });

            $("a.show-cloturer-modal").on("click", function(){
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#type_registre").val(typeregistre);

                $("#modal-registre-cloturer").modal("show");
                return false;
            });

            $("#btn-cloturer").on("click",function(){
                var codereg = $("#coderegistre").val();
                var datecloture = $("#date_cloture").val();
                var route = "{{ route('registre.cloture') }}";
                var data = {
                    code_registre:codereg,
                    date_cloture:datecloture
                };

                // $(this).attr("disabled",true);
                // $(this).html("Traitement en cours ...");
                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-cloturer").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 4000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });



             //Affichage modal ajout de feuillets du registre
             $("a.show-add-leaflet-modal").on("click", function(){
                $("#msg_erreur").hide();
                var coderegistre = $(this).attr("href");
                var typeregistre = $(this).attr("typeregistre");
                $("#coderegistre").val(coderegistre);
                $("#libtyperegistre").html("REGISTRE DE "+typeregistre);

                $("#modal-registre-add-leaflet").modal("show");
                return false;
            });

            //Traitement ajout de feuillets du registre
            $("#btn-add-feuillets").on("click",function(){
                var codereg = $("#coderegistre").val();
                var nbrefeuillets = $("#nbreFeuillets").val();
                var route = "{{ route('registre.add.feuillets') }}";
                var data = {
                    code_registre:codereg,
                    nbrefeuillets:nbrefeuillets
                };
                if(nbrefeuillets == "" || nbrefeuillets == null){
                    $("#msg_erreur").show(300);
                    return false;
                }

                $.post(route, data, function(response){

                    if(response.code == "200"){
                        // notification("success",response.message);
                        flashAlert("Réponse","success",response.message);
                        $("#modal-registre-add-leaflet").modal('hide');
                        setTimeout(() => {
                            location.reload();
                        }, 6000);
                    }else{
                        // notification("error",response.message);
                        flashAlert("Réponse","error",response.message);
                    }
                });

                return false;
            });

        });
      </script>




@endsection
