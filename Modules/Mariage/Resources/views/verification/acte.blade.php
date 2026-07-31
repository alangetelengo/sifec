<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification de l'acte de mariage</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(33,185,49,0.12), rgba(68,157,68,0.2));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Nunito", sans-serif;
        }
        .verification-wrapper {
            width: 100%;
            max-width: 720px;
            padding: 2rem;
        }
        .verification-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.12);
            overflow: hidden;
            background: #fff;
        }
        .verification-header {
            background: linear-gradient(135deg, #21B931, #449D44);
            color: #fff;
        }
        .verification-header h1 {
            font-size: 1.4rem;
            margin: 0;
            letter-spacing: 0.03em;
        }
        .logo-wrapper img {
            max-height: 60px;
        }
        dt {
            color: #6c757d;
            font-weight: 600;
        }
        dd {
            color: #1c1e21;
        }
        @media (max-width: 576px) {
            .verification-wrapper {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="verification-wrapper">
        <div class="text-center mb-4 logo-wrapper">
            <img src="{{ asset('assets-login/images/logo-sifec-app.gif') }}" alt="SIFEC">
        </div>

        <div class="card verification-card">
            <div class="card-header verification-header py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h1>Vérification de l'acte de mariage</h1>
                    <span class="fw-semibold">Acte n° {{ $acte->code_acte_mariage }}</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 mb-4" role="alert">
                    <strong>Document scanné :</strong> <span class="text-uppercase">Acte de mariage</span> (acte authentifié délivré par l'officier d'état civil)
                </div>
                <p class="text-muted mb-4">
                    Information issue du registre national de l'état civil. Veuillez examiner les détails ci-dessous.
                </p>
                <dl class="row">
                    <dt class="col-sm-5 col-md-4">Statut</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($acte->statut ?? false)
                            <span class="badge bg-danger">Acte annulé</span>
                        @else
                            <span class="badge bg-success">Acte valide</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Signature et retrait</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if(!$acte->approbation_mairie || $acte->approbation_mairie === '' || $acte->approbation_mairie === null)
                            <span class="badge bg-warning text-dark">Non encore signé</span>
                            <small class="d-block text-muted mt-1">L'acte est en attente de signature par l'officier d'état civil.</small>
                        @elseif($acte->retrait)
                            <span class="badge bg-success">Signé et rétiré</span>
                            <small class="d-block text-muted mt-1">Rétiré le {{ $acte->retrait->created_at ? $acte->retrait->created_at->format('d/m/Y à H:i') : '—' }}</small>
                        @else
                            <span class="badge bg-info">Signé, non encore rétiré</span>
                            <small class="d-block text-muted mt-1">L'acte a été signé et peut être rétiré par les époux.</small>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Époux</dt>
                    <dd class="col-sm-7 col-md-8">{{ $acte->declaration?->epoux?->nomcomplet() ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Épouse</dt>
                    <dd class="col-sm-7 col-md-8">{{ $acte->declaration?->epouse?->nomcomplet() ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Date du mariage</dt>
                    <dd class="col-sm-7 col-md-8">{{ $acte->declaration?->date_prevue_mariage ? date('d/m/Y', strtotime($acte->declaration->date_prevue_mariage)) : '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Date de déclaration</dt>
                    <dd class="col-sm-7 col-md-8">{{ $acte->declaration?->date_heure_declaration ? date('d/m/Y H:i', strtotime($acte->declaration->date_heure_declaration)) : '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Date d'émission</dt>
                    <dd class="col-sm-7 col-md-8">{{ $acte->date_emission ? date('d/m/Y H:i', strtotime($acte->date_emission)) : '—' }}</dd>
                </dl>

                {{-- ── Bloc signature & traçabilité ── --}}
                <hr class="my-3" style="border-color:#009E49; opacity:.3;">
                <h6 class="fw-bold mb-3" style="color:#006B31;">
                    <i class="fa fa-shield-alt me-2"></i>Signature &amp; Traçabilité
                </h6>
                <dl class="row">
                    <dt class="col-sm-5 col-md-4">Officier signataire</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($acte->signataire)
                            {{ $acte->signataire->user?->personne->nomcomplet() ?? '//' }}
                        @else
                            <span class="text-muted fst-italic">Non encore signé</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Date de signature</dt>
                    <dd class="col-sm-7 col-md-8">
                        @php $sigDate = $acte->signed_at ?? $acte->doc_sig_signed_at ?? $acte->date_heure_approbation_mairie; @endphp
                        {{ $sigDate
                            ? \Carbon\Carbon::parse($sigDate)->format('d/m/Y \à H:i:s')
                            : '—' }}
                    </dd>

                    @if(filled($acte->proof_id))
                        <dt class="col-sm-5 col-md-4">Identifiant de preuve (proof_id)</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $acte->proof_id }}</code></dd>
                    @endif

                    @if(filled($acte->doc_sig_id))
                        <dt class="col-sm-5 col-md-4">Identifiant signature (L2)</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $acte->doc_sig_id }}</code></dd>
                    @endif

                    @if(filled($acte->doc_seal_id))
                        <dt class="col-sm-5 col-md-4">Cachet institutionnel (L3)</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $acte->doc_seal_id }}</code></dd>
                    @endif

                    @if(filled($acte->certificate_ref))
                        <dt class="col-sm-5 col-md-4">Réf. certificat</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $acte->certificate_ref }}</code></dd>
                    @endif

                    @php $empreinteActe = $acte->pdf_content_hash ?? $acte->payload_hash; @endphp
                    @if(filled($empreinteActe))
                        <dt class="col-sm-5 col-md-4">Empreinte document</dt>
                        <dd class="col-sm-7 col-md-8">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="fa fa-check-circle text-success"></i>
                                <span class="small text-success fw-semibold">Empreinte SHA-256 enregistrée</span>
                            </div>
                            <code class="small" style="word-break:break-all;">{{ $empreinteActe }}</code>
                        </dd>
                    @endif

                    <dt class="col-sm-5 col-md-4">Adresse IP</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($acte->adresse_mac_approbation)
                            <code class="text-dark" style="font-size:.88rem;">{{ $acte->adresse_mac_approbation }}</code>
                        @else
                            <span class="text-muted fst-italic">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Appareil utilisé</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($acte->nom_appareil_approbation)
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="fa fa-laptop" style="color:#2781d5;"></i>
                                {{ $acte->nom_appareil_approbation }}
                            </span>
                        @else
                            <span class="text-muted fst-italic">—</span>
                        @endif
                    </dd>
                </dl>
            </div>

            <div class="card-footer bg-light py-3 text-center">
                <small class="text-muted">Système Intégré des Faits d'État Civil (SIFEC)</small>
            </div>
        </div>
    </div>

    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
</body>
</html>
