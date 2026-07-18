@extends('layout.app')

@section('titre')
Gestion des demandes de documents
@endsection

@section('styles')
<link href="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">
<link href="{{ asset('tpl/wizard/dist/css/style.min.css') }}" rel="stylesheet">
<style>
    @include('authentification::partials.sifec-swal-delete-styles')

    .page-sifec-index .dd-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 991px) {
        .page-sifec-index .dd-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 575px) {
        .page-sifec-index .dd-stats { grid-template-columns: 1fr; }
    }
    .page-sifec-index .dd-stat {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.95rem 1rem;
        border-radius: var(--si-radius-sm);
        border: 1px solid var(--si-line);
        background: #fff;
        box-shadow: 0 2px 10px rgba(26, 46, 38, 0.04);
        min-height: 88px;
    }
    .page-sifec-index .dd-stat__icon {
        width: 2.6rem;
        height: 2.6rem;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.05rem;
        color: #fff;
    }
    .page-sifec-index .dd-stat__val {
        font-size: 1.45rem;
        font-weight: 700;
        color: var(--si-ink);
        line-height: 1.1;
    }
    .page-sifec-index .dd-stat__lbl {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-weight: 600;
        color: var(--si-muted);
        margin-top: 0.15rem;
    }
    .page-sifec-index .dd-stat--warn .dd-stat__icon { background: linear-gradient(135deg, #c75c5a, #e07a78); }
    .page-sifec-index .dd-stat--info .dd-stat__icon { background: linear-gradient(135deg, #1b6f4a, #2d9b6a); }
    .page-sifec-index .dd-stat--ok .dd-stat__icon { background: linear-gradient(135deg, #0f5132, #21b931); }
    .page-sifec-index .dd-stat--muted .dd-stat__icon { background: linear-gradient(135deg, #5c6d66, #8a9a93); }

    .page-sifec-index .dd-code {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.78rem;
        font-weight: 700;
        color: #0f5132;
        background: rgba(15, 81, 50, 0.08);
        padding: 0.2rem 0.45rem;
        border-radius: 6px;
        text-decoration: none;
        white-space: nowrap;
        display: inline-block;
    }
    .page-sifec-index .dd-code:hover {
        background: rgba(15, 81, 50, 0.14);
        color: #006b31;
    }
    .page-sifec-index .dd-date {
        white-space: nowrap;
        line-height: 1.35;
    }
    .page-sifec-index .dd-date__d { font-weight: 600; color: var(--si-ink); }
    .page-sifec-index .dd-date__t { font-size: 0.78rem; color: var(--si-muted); }
    .page-sifec-index .dd-demandeur {
        font-weight: 600;
        color: var(--si-ink);
        max-width: 14rem;
        line-height: 1.35;
    }
    .page-sifec-index .dd-acte-num {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
        color: #2d3d35;
    }
    .page-sifec-index .dd-prix {
        font-weight: 700;
        white-space: nowrap;
        color: var(--si-ink);
    }
    .page-sifec-index .dd-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.3rem 0.55rem;
        border-radius: 999px;
        white-space: nowrap;
        line-height: 1.2;
    }
    .page-sifec-index .dd-badge--type {
        background: rgba(27, 111, 74, 0.1);
        color: #0f5132;
    }
    .page-sifec-index .dd-badge--doc {
        background: rgba(39, 129, 213, 0.12);
        color: #1a5a8a;
    }
    .page-sifec-index .dd-badge--portail {
        background: rgba(39, 129, 213, 0.14);
        color: #1a5a8a;
    }
    .page-sifec-index .dd-badge--site {
        background: rgba(201, 162, 39, 0.18);
        color: #6c4a00;
    }
    .page-sifec-index .dd-badge--paiement { background: #fff3cd; color: #856404; }
    .page-sifec-index .dd-badge--traitement { background: rgba(39, 129, 213, 0.14); color: #1a5a8a; }
    .page-sifec-index .dd-badge--signature { background: rgba(15, 81, 50, 0.12); color: #0f5132; }
    .page-sifec-index .dd-badge--traitee { background: rgba(33, 185, 49, 0.16); color: #0f5132; }
    .page-sifec-index .dd-badge--livree { background: #343a40; color: #fff; }
    .page-sifec-index .dd-badge--rejetee { background: rgba(199, 92, 90, 0.16); color: #7a2e2c; }
    .page-sifec-index .dd-badge--expiree { background: #e9ecef; color: #495057; }

    .page-sifec-index .dd-actions {
        display: inline-flex;
        gap: 0.35rem;
        align-items: center;
        flex-wrap: nowrap;
    }
    .page-sifec-index .dd-actions .btn {
        width: 2rem;
        height: 2rem;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid transparent;
    }
    .page-sifec-index .dd-actions .btn-view {
        background: rgba(15, 81, 50, 0.1);
        color: #0f5132;
        border-color: rgba(15, 81, 50, 0.18);
    }
    .page-sifec-index .dd-actions .btn-view:hover { background: rgba(15, 81, 50, 0.18); color: #006b31; }
    .page-sifec-index .dd-actions .btn-pdf {
        background: rgba(200, 80, 60, 0.1);
        color: #a94442;
        border-color: rgba(200, 80, 60, 0.2);
    }
    .page-sifec-index .dd-actions .btn-pdf:hover { background: rgba(200, 80, 60, 0.18); }
    .page-sifec-index .dd-actions .btn-dl {
        background: rgba(33, 185, 49, 0.12);
        color: #0f5132;
        border-color: rgba(33, 185, 49, 0.25);
    }
    .page-sifec-index .dd-actions .btn-dl:hover { background: rgba(33, 185, 49, 0.2); }

    .page-sifec-index .dd-batch {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.75rem 1rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--si-line);
    }
    .page-sifec-index .dd-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--si-muted);
    }
    .page-sifec-index .dd-empty i {
        font-size: 1.75rem;
        display: block;
        margin-bottom: 0.5rem;
        opacity: 0.55;
    }
    .page-sifec-index #table-demandes-document {
        width: 100% !important;
        table-layout: auto;
    }
    .page-sifec-index #table-demandes-document th.dd-col-check,
    .page-sifec-index #table-demandes-document td.dd-col-check {
        width: 2.25rem;
        text-align: center;
    }
    .page-sifec-index #table-demandes-document th.dd-col-actions,
    .page-sifec-index #table-demandes-document td.dd-col-actions {
        width: 1%;
        white-space: nowrap;
    }
</style>
@endsection

@section('corps')
<div class="page-sifec-index">
<div class="an-shell">
    <div class="an-hero an-hero--sifec-green">
        <div class="an-hero-text">
            <h1><i class="fas fa-file-alt an-hero-icon"></i> Demandes de documents</h1>
            <p>Copies et extraits d’actes — suivi, génération PDF et signature électronique de délivrance.</p>
        </div>
        <div class="an-toolbar">
            <a href="{{ route('demandeDocument.create') }}" class="btn an-hero-btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Nouvelle demande sur site
            </a>
        </div>
    </div>

    <div class="an-body">
        <div class="dd-stats">
            <div class="dd-stat dd-stat--warn">
                <div class="dd-stat__icon"><i class="fas fa-spinner"></i></div>
                <div>
                    <div class="dd-stat__val">{{ $stats['en_traitement'] }}</div>
                    <div class="dd-stat__lbl">En traitement</div>
                </div>
            </div>
            <div class="dd-stat dd-stat--info">
                <div class="dd-stat__icon"><i class="fas fa-signature"></i></div>
                <div>
                    <div class="dd-stat__val">{{ $stats['en_attente_signature'] }}</div>
                    <div class="dd-stat__lbl">À signer</div>
                </div>
            </div>
            <div class="dd-stat dd-stat--ok">
                <div class="dd-stat__icon"><i class="fas fa-check"></i></div>
                <div>
                    <div class="dd-stat__val">{{ $stats['traitees_aujourdhui'] }}</div>
                    <div class="dd-stat__lbl">Traitées aujourd’hui</div>
                </div>
            </div>
            <div class="dd-stat dd-stat--muted">
                <div class="dd-stat__icon"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="dd-stat__val">{{ $stats['expirees'] }}</div>
                    <div class="dd-stat__lbl">Expirées</div>
                </div>
            </div>
        </div>

        <div class="an-filter-card card mb-3">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-filter me-2"></i>Filtres</span>
            </div>
            <div class="card-body">
                @include('demande-document._filters')
            </div>
        </div>

        <div class="an-tabs mb-3">
            <ul class="nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $origine == 'portail' ? 'active' : '' }}"
                       href="{{ route('demandeDocument.index', array_merge(request()->except('page'), ['origine' => 'portail'])) }}">
                        <i class="fas fa-globe"></i> Demandes portail
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $origine == 'sur_site' ? 'active' : '' }}"
                       href="{{ route('demandeDocument.index', array_merge(request()->except('page'), ['origine' => 'sur_site'])) }}">
                        <i class="fas fa-building"></i> Demandes sur site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $origine == 'tous' ? 'active' : '' }}"
                       href="{{ route('demandeDocument.index', array_merge(request()->except('page'), ['origine' => 'tous'])) }}">
                        <i class="fas fa-list"></i> Toutes
                    </a>
                </li>
            </ul>
        </div>

        <div class="an-table-wrap">
            <table id="table-demandes-document" class="table table-hover an-data-table mb-0">
                <thead>
                    <tr>
                        <th class="dd-col-check"><input type="checkbox" id="select-all" title="Tout sélectionner"></th>
                        <th>Code</th>
                        <th>Date</th>
                        <th>Demandeur</th>
                        <th>Type acte</th>
                        <th>Document</th>
                        <th>N° acte</th>
                        <th>Origine</th>
                        <th>Statut</th>
                        <th class="text-end">Prix</th>
                        <th class="dd-col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($demandes as $demande)
                        <tr>
                            <td class="dd-col-check">
                                @if($demande->estEnAttenteSignature() && Gate::allows($demande->getPermissionSignature()))
                                    <input type="checkbox" class="demande-checkbox" value="{{ $demande->code_demande_document }}">
                                @endif
                            </td>
                            <td>
                                <a class="dd-code" href="{{ route('demandeDocument.show', $demande->code_demande_document) }}">
                                    {{ $demande->code_demande_document }}
                                </a>
                            </td>
                            <td>
                                @if($demande->date_demande)
                                    <div class="dd-date">
                                        <div class="dd-date__d">{{ $demande->date_demande->format('d/m/Y') }}</div>
                                        <div class="dd-date__t">{{ $demande->date_demande->format('H:i') }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="dd-demandeur" title="{{ $demande->getNomCompletDemandeur() }}">
                                    {{ $demande->getNomCompletDemandeur() }}
                                </div>
                            </td>
                            <td>
                                <span class="dd-badge dd-badge--type">{{ $demande->getLibelleTypeActe() }}</span>
                            </td>
                            <td>
                                <span class="dd-badge dd-badge--doc">{{ $demande->getLibelleTypeDocument() }}</span>
                            </td>
                            <td>
                                <span class="dd-acte-num">{{ $demande->numero_acte ?: '—' }}</span>
                            </td>
                            <td>
                                @if($demande->estPortail())
                                    <span class="dd-badge dd-badge--portail"><i class="fas fa-globe"></i> Portail</span>
                                @else
                                    <span class="dd-badge dd-badge--site"><i class="fas fa-building"></i> Sur site</span>
                                @endif
                            </td>
                            <td>
                                @if($demande->estEnAttentePaiement())
                                    <span class="dd-badge dd-badge--paiement">En attente paiement</span>
                                @elseif($demande->estEnTraitement())
                                    <span class="dd-badge dd-badge--traitement">En traitement</span>
                                @elseif($demande->estEnAttenteSignature())
                                    <span class="dd-badge dd-badge--signature">À signer</span>
                                @elseif($demande->estTraitee())
                                    <span class="dd-badge dd-badge--traitee">Traitée</span>
                                @elseif($demande->estLivree())
                                    <span class="dd-badge dd-badge--livree">Livrée</span>
                                @elseif($demande->estRejetee())
                                    <span class="dd-badge dd-badge--rejetee">Rejetée</span>
                                @elseif($demande->estExpiree())
                                    <span class="dd-badge dd-badge--expiree">Expirée</span>
                                @else
                                    <span class="dd-badge dd-badge--expiree">{{ $demande->statut ?? '—' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <span class="dd-prix">{{ number_format($demande->prix, 0, ',', ' ') }} <small class="text-muted fw-normal">FCFA</small></span>
                            </td>
                            <td class="dd-col-actions">
                                <div class="dd-actions">
                                    <a href="{{ route('demandeDocument.show', $demande->code_demande_document) }}"
                                       class="btn btn-view" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($demande->estEnTraitement())
                                        <button type="button"
                                                class="btn btn-pdf btn-generer-pdf"
                                                data-code="{{ $demande->code_demande_document }}"
                                                title="Générer PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </button>
                                    @endif

                                    @if($demande->chemin_document && file_exists($demande->chemin_document))
                                        <a href="{{ route('demandeDocument.pdf', $demande->code_demande_document) }}"
                                           class="btn btn-dl" title="Télécharger PDF" target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11">
                                <div class="dd-empty">
                                    <i class="fas fa-inbox"></i>
                                    Aucune demande trouvée pour ces critères.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="text-muted small">
                @if(method_exists($demandes, 'total'))
                    {{ $demandes->total() }} demande(s)
                @endif
            </div>
            <div>{{ $demandes->links() }}</div>
        </div>

        @if(
            Gate::allows('module.acteNaissance.signature.extrait') ||
            Gate::allows('module.acteNaissance.signature.copie') ||
            Gate::allows('module.acteMariage.signature.extrait') ||
            Gate::allows('module.acteMariage.signature.copie') ||
            Gate::allows('module.acteDeces.signature.extrait') ||
            Gate::allows('module.acteDeces.signature.copie') ||
            Gate::allows('module.acteDivorce.signature.extrait') ||
            Gate::allows('module.acteDivorce.signature.copie')
        )
            <div class="dd-batch">
                <button type="button" id="btn-signer-batch" class="btn btn-success" disabled>
                    <i class="fas fa-signature"></i> Signer les demandes sélectionnées
                </button>
                <small class="text-muted">Seules les demandes pour lesquelles vous avez les droits seront signées</small>
            </div>
        @endif
    </div>
</div>
</div>

{{-- Modal de signature électronique .p12 --}}
@include('demande-document._modal_signature')

@endsection

@section('scripts')
<script src="{{ asset('tpl/wizard/assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/vendor/forge.min.js') }}"></script>
<script src="{{ asset('js/vendor/elliptic.min.js') }}"></script>
<script src="{{ asset('js/sifec-p12-sign.js') }}?v=20260718a"></script>
<script>
function showDemandeSignError(msg) {
    $('#otp-feedback').removeClass('d-none').text(msg);
    if (typeof Swal !== 'undefined') {
        Swal.fire({ icon: 'error', title: 'Erreur', text: msg });
    }
}

function openDemandeSignModal(codes) {
    $('#codes-demandes-signature').val(JSON.stringify(codes));
    $('#nb-demandes-signature').text(codes.length);
    $('#otp-feedback').addClass('d-none').empty();
    $('#demande_p12_file').val('');
    $('#demande_p12_pin').val('');
    $('#btn-valider-otp').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
    $('#modal-signature-otp').modal('show');
}

async function runDemandeP12Sign($btn) {
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Préparation…');
    $('#otp-feedback').addClass('d-none').empty();

    let codes = [];
    try { codes = JSON.parse($('#codes-demandes-signature').val() || '[]'); } catch (e) { codes = []; }
    if (!codes.length) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Aucune demande à signer.');
        return;
    }

    const fileInput = document.getElementById('demande_p12_file');
    const pin = $('#demande_p12_pin').val();
    if (!fileInput || !fileInput.files || !fileInput.files[0]) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Sélectionnez votre fichier certificat (.p12).');
        return;
    }
    if (!pin || !String(pin).trim()) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Saisissez la passphrase de votre certificat.');
        return;
    }
    if (typeof window.SifecP12Sign === 'undefined') {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        showDemandeSignError('Bibliothèque de signature non chargée. Rechargez la page.');
        return;
    }

    try {
        const prep = await $.ajax({
            url: '{{ route("demandeDocument.sign.prepare") }}',
            type: 'POST',
            data: { demandes: codes, _token: '{{ csrf_token() }}' }
        });
        if (!prep.success || !prep.token || !prep.items || !prep.items.length) {
            $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
            showDemandeSignError((prep && prep.message) ? prep.message : 'Échec de la préparation.');
            return;
        }

        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Signature locale…');
        const p12Binary = await window.SifecP12Sign.readP12File(fileInput.files[0]);
        const signatures = [];
        for (let i = 0; i < prep.items.length; i++) {
            const item = prep.items[i];
            const signatureHex = await window.SifecP12Sign.signHashHex(
                p12Binary, pin, item.document_hash, prep.expected_serial || null
            );
            signatures.push({ code_demande: item.code_demande, signature_hex: signatureHex });
        }

        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i> Validation…');
        const fin = await $.ajax({
            url: '{{ route("demandeDocument.sign.finalize") }}',
            type: 'POST',
            data: { token: prep.token, signatures: signatures, _token: '{{ csrf_token() }}' }
        });

        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        if (fin.success) {
            $('#modal-signature-otp').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Signature effectuée',
                text: fin.message || 'Documents signés électroniquement',
                timer: 2200,
                showConfirmButton: false
            }).then(function() { location.reload(); });
        } else {
            showDemandeSignError(fin.message || 'Échec de la signature.');
        }
    } catch (err) {
        $btn.prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
        let emsg = 'Erreur lors de la signature électronique';
        if (err && err.responseJSON && err.responseJSON.message) emsg = err.responseJSON.message;
        else if (err && err.message) emsg = err.message;
        showDemandeSignError(emsg);
    }
}
$(document).ready(function() {
    function updateSignerButton() {
        const nbSelected = $('.demande-checkbox:checked').length;
        $('#btn-signer-batch').prop('disabled', nbSelected === 0);
        if (nbSelected > 0) {
            $('#btn-signer-batch').html(`<i class="fas fa-signature"></i> Signer ${nbSelected} demande(s) sélectionnée(s)`);
        } else {
            $('#btn-signer-batch').html('<i class="fas fa-signature"></i> Signer les demandes sélectionnées');
        }
    }

    $('#select-all').on('change', function() {
        $('.demande-checkbox').prop('checked', $(this).prop('checked'));
        updateSignerButton();
    });
    $(document).on('change', '.demande-checkbox', updateSignerButton);

    $('#btn-signer-batch').on('click', function() {
        const codes = $('.demande-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (codes.length === 0) return;
        openDemandeSignModal(codes);
    });

    $('#btn-valider-otp').on('click', function() {
        runDemandeP12Sign($(this));
    });

    $('#modal-signature-otp').on('hidden.bs.modal', function() {
        $('#otp-feedback').addClass('d-none').empty();
        $('#demande_p12_file').val('');
        $('#demande_p12_pin').val('');
        $('#btn-valider-otp').prop('disabled', false).html('<i class="fas fa-signature me-1"></i> Signer électroniquement');
    });

    $(document).on('click', '.btn-generer-pdf', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const code = $btn.data('code');
        const originalHtml = $btn.html();
        const url = '{{ route("demandeDocument.genererPdf", ":code") }}'.replace(':code', code);

        Swal.fire({
            title: 'Générer le PDF ?',
            html: "Le document sera généré <strong>sans signature</strong>, puis passera en "
                + "<strong>attente de signature de délivrance</strong> par l’officier en fonction.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, générer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'PDF généré',
                            text: (response && response.message)
                                ? response.message
                                : 'Document généré. En attente de signature de l\'officier en fonction.',
                            timer: 2200,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalHtml);

                        let errorMsg = 'Une erreur est survenue';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMsg = response.message || errorMsg;
                            } catch(e) {
                                console.error('Erreur parsing:', e);
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: errorMsg
                        });

                        console.error('Erreur AJAX:', xhr);
                    }
                });
            }
        });
    });
});
</script>
@endsection
