<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification de la déclaration de mariage</title>
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
                    <h1>Vérification de la déclaration de mariage</h1>
                    <span class="fw-semibold">Déclaration n° {{ $declaration->code_declaration_mariage }}</span>
                </div>
                <span class="mt-3 mt-md-0 badge bg-light text-dark text-uppercase px-3 py-2">
                    {{ $declaration->type_mariage ?? '—' }}
                </span>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 mb-4" role="alert">
                    <strong>Document scanné :</strong> <span class="text-uppercase">Formulaire de déclaration de mariage</span>
                </div>
                <p class="text-muted mb-4">
                    Information vérifiée à partir de la base de données de l'état civil. Merci de vérifier les détails ci-dessous.
                </p>
                <dl class="row">
                    <dt class="col-sm-5 col-md-4">Acte établi</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if(!$declaration->acte)
                            <span class="badge bg-secondary">Aucun acte établi</span>
                            <small class="d-block text-muted mt-1">Cette déclaration n'a pas encore donné lieu à un acte de mariage.</small>
                        @elseif(!$declaration->acte->approbation_mairie || $declaration->acte->approbation_mairie === '' || $declaration->acte->approbation_mairie === null)
                            <span class="badge bg-warning text-dark">Acte établi, en attente de signature</span>
                            <small class="d-block text-muted mt-1">L'acte a été généré mais n'a pas encore été signé par l'officier d'état civil.</small>
                        @elseif($declaration->acte->retrait)
                            <span class="badge bg-success">Acte signé et rétiré</span>
                            <small class="d-block text-muted mt-1">Rétiré le {{ $declaration->acte->retrait->created_at ? $declaration->acte->retrait->created_at->format('d/m/Y à H:i') : '—' }}</small>
                        @else
                            <span class="badge bg-info">Acte signé, non encore rétiré</span>
                            <small class="d-block text-muted mt-1">L'acte a été signé et peut être rétiré par les époux.</small>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Date de déclaration</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->date_declaration_mariage ? date('d/m/Y H:i', strtotime($declaration->date_declaration_mariage)) : '-' }}</dd>

                    <dt class="col-sm-5 col-md-4">Date prévue du mariage</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->date_prevue_mariage ? date('d/m/Y', strtotime($declaration->date_prevue_mariage)) : '-' }}</dd>

                    <dt class="col-sm-5 col-md-4">Époux</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->epoux?->nomcomplet() ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Épouse</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->epouse?->nomcomplet() ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Lieu de la cérémonie</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->lieu_ceremonie_mariage ?? '—' }}</dd>
                </dl>

                {{-- ── Signature électronique du centre d'état civil ── --}}
                <hr class="my-3" style="border-color:#009E49; opacity:.3;">
                <h6 class="fw-bold mb-3" style="color:#006B31;">
                    <i class="fa fa-shield-alt me-2"></i>Signature &amp; Traçabilité (Centre d'état civil)
                </h6>
                @if(!filled($declaration->sig_cec_proof_id))
                    <p class="text-muted fst-italic mb-0">Cette déclaration n'a pas encore été signée électroniquement.</p>
                @else
                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-md-4">Signataire</dt>
                        <dd class="col-sm-7 col-md-8">{{ $declaration->sig_cec_actor_nom ?: '—' }}</dd>

                        <dt class="col-sm-5 col-md-4">Signé le</dt>
                        <dd class="col-sm-7 col-md-8">
                            @php $sigDeclDate = $declaration->sig_cec_signed_at ?? $declaration->sig_cec_doc_sig_signed_at; @endphp
                            {{ $sigDeclDate ? \Carbon\Carbon::parse($sigDeclDate)->format('d/m/Y à H:i:s') : '—' }}
                        </dd>

                        <dt class="col-sm-5 col-md-4">Identifiant de preuve (proof_id)</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $declaration->sig_cec_proof_id }}</code></dd>

                        @if(filled($declaration->sig_cec_doc_sig_id))
                            <dt class="col-sm-5 col-md-4">Identifiant signature (L2)</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $declaration->sig_cec_doc_sig_id }}</code></dd>
                        @endif

                        @if(filled($declaration->sig_cec_doc_seal_id))
                            <dt class="col-sm-5 col-md-4">Cachet institutionnel (L3)</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $declaration->sig_cec_doc_seal_id }}</code></dd>
                        @endif

                        @if(filled($declaration->sig_cec_certificate_ref))
                            <dt class="col-sm-5 col-md-4">Réf. certificat</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $declaration->sig_cec_certificate_ref }}</code></dd>
                        @endif

                        @php $empreinteDecl = $declaration->sig_cec_pdf_content_hash ?? $declaration->sig_cec_payload_hash; @endphp
                        @if(filled($empreinteDecl))
                            <dt class="col-sm-5 col-md-4">Empreinte document</dt>
                            <dd class="col-sm-7 col-md-8">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="fa fa-check-circle text-success"></i>
                                    <span class="small text-success fw-semibold">Empreinte SHA-256 enregistrée</span>
                                </div>
                                <code class="small" style="word-break:break-all;">{{ $empreinteDecl }}</code>
                            </dd>
                        @endif
                    </dl>
                @endif
            </div>

            <div class="card-footer bg-light py-3 text-center">
                <small class="text-muted">Système Intégré des Faits d'État Civil (SIFEC)</small>
            </div>
        </div>
    </div>

    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
</body>
</html>
