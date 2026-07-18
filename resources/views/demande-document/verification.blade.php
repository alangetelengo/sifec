<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification du document délivré</title>
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
        .logo-wrapper img { max-height: 60px; }
        dt { color: #6c757d; font-weight: 600; }
        dd { color: #1c1e21; }
        @media (max-width: 576px) {
            .verification-wrapper { padding: 1.5rem 1rem; }
        }
    </style>
</head>
<body>
    @php
        $typeDoc = $demande->getLibelleTypeDocument() ?: ($demande->estCopie() ? 'Copie' : 'Extrait');
        $typeActe = $demande->getLibelleTypeActe() ?: 'Acte';
        $sigNom = $demande->signataire?->user?->personne?->nomcomplet();
        $acte = $acte ?? null;
        $enfantNom = null;
        if ($acte && method_exists($acte, 'declaration') && $acte->declaration?->enfant) {
            $enfantNom = $acte->declaration->enfant->nomcomplet();
        }
    @endphp
    <div class="verification-wrapper">
        <div class="text-center mb-4 logo-wrapper">
            <img src="{{ asset('assets-login/images/logo-sifec-app.gif') }}" alt="SIFEC">
        </div>

        <div class="card verification-card">
            <div class="card-header verification-header py-4">
                <h1>Vérification du document délivré</h1>
                <span class="fw-semibold">{{ $typeDoc }} — {{ $typeActe }}</span>
            </div>
            <div class="card-body p-4">
                @if($demande->estSignee() && $demande->documentEstEncoreValide())
                    <div class="alert alert-success border-0 mb-4" role="alert">
                        <strong>Document authentique :</strong>
                        {{ strtolower($typeDoc) }} délivré(e) et signé(e) par l’officier d’état civil en fonction.
                    </div>
                @elseif($demande->estSignee() && $demande->documentPerimeSansChangementStatut())
                    <div class="alert alert-warning border-0 mb-4" role="alert">
                        <strong>Document signé mais périmé :</strong>
                        la période de validité de cette délivrance est dépassée.
                    </div>
                @elseif($demande->estSignee())
                    <div class="alert alert-info border-0 mb-4" role="alert">
                        <strong>Document signé :</strong>
                        délivrance authentifiée (vérifiez la période de validité ci-dessous).
                    </div>
                @else
                    <div class="alert alert-warning border-0 mb-4" role="alert">
                        <strong>Non encore signé :</strong>
                        ce document est en attente de signature de délivrance par l’officier en fonction.
                    </div>
                @endif

                <p class="text-muted small mb-4">
                    La signature de ce document est celle de l’officier ayant traité la demande ;
                    elle peut différer de l’officier qui a signé l’acte d’origine.
                </p>

                <dl class="row">
                    <dt class="col-sm-5 col-md-4">N° demande</dt>
                    <dd class="col-sm-7 col-md-8"><code>{{ $demande->code_demande_document }}</code></dd>

                    <dt class="col-sm-5 col-md-4">Type</dt>
                    <dd class="col-sm-7 col-md-8">{{ $typeDoc }} d’{{ strtolower($typeActe) }}</dd>

                    <dt class="col-sm-5 col-md-4">N° acte</dt>
                    <dd class="col-sm-7 col-md-8">{{ $demande->numero_acte ?: ($acte->niupp ?? '—') }}</dd>

                    @if($enfantNom)
                        <dt class="col-sm-5 col-md-4">Titulaire</dt>
                        <dd class="col-sm-7 col-md-8">{{ $enfantNom }}</dd>
                    @endif

                    <dt class="col-sm-5 col-md-4">Centre d’état civil</dt>
                    <dd class="col-sm-7 col-md-8">{{ $demande->institution?->lib_institution ?? '—' }}</dd>
                </dl>

                <hr class="my-3" style="border-color:#009E49; opacity:.3;">
                <h6 class="fw-bold mb-3" style="color:#006B31;">
                    <i class="fa fa-shield-alt me-2"></i>Signature de délivrance
                </h6>
                <dl class="row mb-0">
                    <dt class="col-sm-5 col-md-4">Signataire</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($sigNom)
                            {{ $sigNom }}
                        @else
                            <span class="text-muted fst-italic">Non encore signé</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Signé le</dt>
                    <dd class="col-sm-7 col-md-8">
                        {{ $demande->date_signature
                            ? $demande->date_signature->format('d/m/Y à H:i:s')
                            : '—' }}
                    </dd>

                    @if($demande->document_valide_de && $demande->document_valide_jusquau)
                        <dt class="col-sm-5 col-md-4">Validité</dt>
                        <dd class="col-sm-7 col-md-8">
                            du {{ $demande->document_valide_de->format('d/m/Y') }}
                            au {{ $demande->document_valide_jusquau->format('d/m/Y') }}
                            @if($demande->documentEstEncoreValide())
                                <span class="badge bg-success ms-1">En cours</span>
                            @else
                                <span class="badge bg-secondary ms-1">Échue</span>
                            @endif
                        </dd>
                    @endif

                    @if(filled($demande->doc_sig_id))
                        <dt class="col-sm-5 col-md-4">Identifiant signature</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $demande->doc_sig_id }}</code></dd>
                    @endif
                    @if(filled($demande->doc_seal_id))
                        <dt class="col-sm-5 col-md-4">Identifiant cachet</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $demande->doc_seal_id }}</code></dd>
                    @endif
                    @if(filled($demande->pdf_content_hash))
                        <dt class="col-sm-5 col-md-4">Empreinte document</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $demande->pdf_content_hash }}</code></dd>
                    @endif
                    @if(filled($demande->ip_signature))
                        <dt class="col-sm-5 col-md-4">Adresse IP</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small">{{ $demande->ip_signature }}</code></dd>
                    @endif
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
