<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification du registre</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/icons/font-awesome/css/fontawesome-all.min.css') }}">
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
        dt { color: #6c757d; font-weight: 600; }
        dd { color: #1c1e21; }
        @media (max-width: 576px) {
            .verification-wrapper { padding: 1.5rem 1rem; }
        }
    </style>
</head>
<body>
    <div class="verification-wrapper">
        <div class="text-center mb-4 logo-wrapper">
            <img src="{{ asset('assets-login/images/logo-sifec-app.gif') }}" alt="SIFEC">
        </div>

        <div class="card verification-card">
            <div class="card-header verification-header py-4">
                <h1>Vérification du registre</h1>
                <span class="fw-semibold">{{ $registre->lib_registre ?: ($registre->typeRegistre?->lib_type_registre ?? 'Registre') }}</span>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-success border-0 mb-4" role="alert">
                    <strong>Document authentique :</strong>
                    registre d’état civil paraphé par le tribunal.
                </div>

                <dl class="row mb-0">
                    <dt class="col-sm-5 col-md-4">Statut</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if ((int) $registre->statut === 1)
                            <span class="badge bg-success">Activé / paraphé</span>
                        @else
                            <span class="badge bg-warning text-dark">Non activé</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Code registre</dt>
                    <dd class="col-sm-7 col-md-8"><code>{{ $registre->code_registre }}</code></dd>

                    <dt class="col-sm-5 col-md-4">Type</dt>
                    <dd class="col-sm-7 col-md-8">{{ $registre->typeRegistre?->lib_type_registre ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Centre d’état civil</dt>
                    <dd class="col-sm-7 col-md-8">{{ $registre->institutionUser?->institution?->lib_institution ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Tribunal</dt>
                    <dd class="col-sm-7 col-md-8">{{ $registre->institutionUser?->institution?->institutionParent?->lib_institution ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Feuillets</dt>
                    <dd class="col-sm-7 col-md-8">
                        {{ (int) ($registre->nombre_acte_transcrit ?? 0) }} / {{ (int) ($registre->nombre_acte_prevu ?? 0) }}
                    </dd>

                    @php
                        $fonctionSignataire = $registre->actor_fonction
                            ?: $registre->signataire?->fonction?->lib_fonction;
                    @endphp
                    <dt class="col-sm-5 col-md-4">{{ $fonctionSignataire ?: 'Signataire' }}</dt>
                    <dd class="col-sm-7 col-md-8">{{ $registre->actor_nom ?: '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Date de paraphe</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if ($registre->signed_at)
                            {{ \Carbon\Carbon::parse($registre->signed_at)->format('d/m/Y à H:i:s') }}
                        @elseif ($registre->updated_at)
                            {{ \Carbon\Carbon::parse($registre->updated_at)->format('d/m/Y à H:i:s') }}
                        @else
                            —
                        @endif
                    </dd>
                </dl>

                @if (filled($registre->doc_sig_id) || filled($registre->doc_seal_id) || filled($registre->pdf_content_hash))
                    <hr class="my-3" style="border-color:#009E49; opacity:.3;">
                    <h6 class="fw-bold mb-3" style="color:#006B31;">
                        <i class="fa fa-shield-alt me-2"></i>Signature électronique
                    </h6>
                    <dl class="row mb-0">
                        @if (filled($registre->doc_sig_id))
                            <dt class="col-sm-5 col-md-4">Identifiant signature</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $registre->doc_sig_id }}</code></dd>
                        @endif
                        @if (filled($registre->doc_seal_id))
                            <dt class="col-sm-5 col-md-4">Identifiant cachet</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $registre->doc_seal_id }}</code></dd>
                        @endif
                        @if (filled($registre->pdf_content_hash))
                            <dt class="col-sm-5 col-md-4">Empreinte document</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $registre->pdf_content_hash }}</code></dd>
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
