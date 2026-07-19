<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification du certificat de naissance</title>
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
            background: linear-gradient(135deg, #0d9488, #0f766e);
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
                    <h1>Vérification du certificat de naissance</h1>
                    <span class="fw-semibold">Dossier n° {{ $certificat->code_declaration_naissance }}</span>
                </div>
                <span class="mt-3 mt-md-0 badge bg-light text-dark text-uppercase px-3 py-2">
                    {{ $certificat->libelleAffichageType() }}
                </span>
            </div>
            <div class="card-body p-4">

                <div class="alert alert-info border-0 mb-4" role="alert">
                    <strong>Document concerné :</strong> certificat de naissance délivré par une formation sanitaire,
                    transmis au centre d’état civil aux fins de transcription en déclaration / acte de naissance.
                </div>

                @if(($certificat->type_declaration_origine ?? '') === 'CERTIFICAT DE NAISSANCE' && $certificat->type_declaration !== 'CERTIFICAT DE NAISSANCE')
                    <div class="alert alert-success border-0 mb-4" role="alert">
                        Ce dossier provient d’un <strong>certificat de naissance</strong> désormais
                        <strong>enregistré comme déclaration de naissance</strong> au centre d’état civil après validation.
                    </div>
                @endif

                <p class="text-muted mb-4">
                    Informations vérifiées à partir de la base de données de l’état civil (SIFEC).
                </p>

                <dl class="row">
                    <dt class="col-sm-5 col-md-4">Référence certificat</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if($certificat->numero_certificat)
                            <span class="fw-semibold">{{ $certificat->numero_certificat }}</span>
                            <span class="text-muted">(année {{ $certificat->date_heure_declaration ? date('Y', strtotime($certificat->date_heure_declaration)) : '—' }})</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Établissement délivrant</dt>
                    <dd class="col-sm-7 col-md-8">{{ optional($certificat->institution)->lib_institution ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Transmission au centre d’état civil</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if(!filled($certificat->code_institution_destinataire))
                            <span class="badge bg-secondary">Non transmis</span>
                            <small class="d-block text-muted mt-1">Le certificat n’a pas encore été envoyé au centre d’état civil destinataire.</small>
                        @elseif(($certificat->cec_approuver ?? '') !== 'OUI' && $certificat->type_declaration === 'CERTIFICAT DE NAISSANCE')
                            <span class="badge bg-warning text-dark">Transmis — en attente de validation</span>
                            <small class="d-block text-muted mt-1">
                                Destinataire : {{ optional($certificat->institutionDestinataire)->lib_institution ?? '—' }}
                            </small>
                        @else
                            <span class="badge bg-success">Transmis et pris en charge</span>
                            <small class="d-block text-muted mt-1">
                                @if(optional($certificat->institutionDestinataire)->lib_institution)
                                    Centre : {{ $certificat->institutionDestinataire->lib_institution }}
                                @endif
                            </small>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Acte de naissance</dt>
                    <dd class="col-sm-7 col-md-8">
                        @if(!$certificat->acte)
                            <span class="badge bg-secondary">Aucun acte établi</span>
                            <small class="d-block text-muted mt-1">Aucun acte de naissance issu de ce dossier dans le système.</small>
                        @elseif(!$certificat->acte->approbation_mairie || $certificat->acte->approbation_mairie === '')
                            <span class="badge bg-warning text-dark">Acte établi, en attente de signature</span>
                            <small class="d-block text-muted mt-1">L’acte a été généré mais n’a pas encore été signé par l’officier d’état civil.</small>
                        @elseif($certificat->acte->retrait)
                            <span class="badge bg-success">Acte signé et rétiré</span>
                            <small class="d-block text-muted mt-1">Rétiré le {{ $certificat->acte->retrait->created_at ? $certificat->acte->retrait->created_at->format('d/m/Y à H:i') : '—' }}</small>
                        @else
                            <span class="badge bg-info">Acte signé, non encore rétiré</span>
                            <small class="d-block text-muted mt-1">L’acte a été signé et peut être rétiré par le déclarant.</small>
                        @endif
                    </dd>

                    <dt class="col-sm-5 col-md-4">Date du certificat / déclaration</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->date_heure_declaration ? date('d/m/Y H:i', strtotime($certificat->date_heure_declaration)) : '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Enfant</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->enfant?->nomcomplet() }}</dd>

                    <dt class="col-sm-5 col-md-4">Date de naissance</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->enfant?->date_naissance ? date('d/m/Y', strtotime($certificat->enfant->date_naissance)) : '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Déclarant</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->declarant?->nomcomplet() }}</dd>

                    <dt class="col-sm-5 col-md-4">Père</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->pere?->nomcomplet() ?? '—' }}</dd>

                    <dt class="col-sm-5 col-md-4">Mère</dt>
                    <dd class="col-sm-7 col-md-8">{{ $certificat->mere?->nomcomplet() ?? '—' }}</dd>
                </dl>

                @include('naissance::verification.partials.signature-mention', [
                    'doc' => $certificat,
                    'prefix' => 'sig_fs_',
                    'titre' => 'Signature électronique du certificat (formation sanitaire)',
                ])

                @if(filled($certificat->sig_cec_proof_id))
                    @include('naissance::verification.partials.signature-mention', [
                        'doc' => $certificat,
                        'prefix' => 'sig_cec_',
                        'titre' => "Signature électronique de la déclaration (centre d'état civil)",
                    ])
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
