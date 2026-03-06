<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification du certificat de décès</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/icons/font-awesome/css/fontawesome-all.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, rgba(220,36,31,0.08), rgba(155,25,21,0.15));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Nunito", sans-serif;
        }
        .verification-wrapper { width:100%; max-width:720px; padding:2rem; }
        .verification-card {
            border:none; border-radius:1rem;
            box-shadow:0 20px 40px rgba(0,0,0,0.12);
            overflow:hidden; background:#fff;
        }
        .verification-header {
            background: linear-gradient(135deg, #9b1915, #DC241F);
            color:#fff;
        }
        .verification-header h1 { font-size:1.4rem; margin:0; letter-spacing:.03em; }
        .logo-wrapper img { max-height:60px; }
        dt { color:#6c757d; font-weight:600; }
        dd { color:#1c1e21; }
        @media (max-width:576px) { .verification-wrapper { padding:1.5rem 1rem; } }
    </style>
</head>
<body>
<div class="verification-wrapper">
    <div class="text-center mb-4 logo-wrapper">
        <img src="{{ asset('assets-login/images/logo-sifec-app.gif') }}" alt="SIFEC">
    </div>

    @php
        $typeLabel = ($certificat->type_declaration && $certificat->type_declaration !== 'DECLARATION DE DECES')
            ? $certificat->type_declaration
            : 'CERTIFICAT DE DÉCÈS';
        $numRef = $certificat->numero_certificat ?? $certificat->code_declaration_deces;
    @endphp

    <div class="card verification-card">
        <div class="card-header verification-header py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <h1>Vérification — {{ ucfirst(strtolower($typeLabel)) }}</h1>
                <span class="fw-semibold">Réf. n° {{ $numRef }}</span>
            </div>
            <span class="mt-3 mt-md-0 badge bg-light text-dark text-uppercase px-3 py-2">
                {{ $typeLabel }}
            </span>
        </div>

        <div class="card-body p-4">
            <div class="alert alert-info border-0 mb-4" role="alert">
                <strong>Document scanné :</strong>
                <span class="text-uppercase">{{ $typeLabel }}</span>
                (document authentifié délivré par les Pompes Funèbres)
            </div>
            <p class="text-muted mb-4">
                Information issue du registre national de l'état civil. Veuillez examiner les détails ci-dessous.
            </p>

            <dl class="row">
                <dt class="col-sm-5 col-md-4">Statut</dt>
                <dd class="col-sm-7 col-md-8">
                    @if($certificat->acte?->statut)
                        <span class="badge bg-danger">Acte annulé</span>
                    @else
                        <span class="badge bg-success">Acte valide</span>
                    @endif
                </dd>

                <dt class="col-sm-5 col-md-4">Défunt(e)</dt>
                <dd class="col-sm-7 col-md-8">
                    <strong>{{ $certificat->defunt->nom }} {{ $certificat->defunt->prenom }}</strong>
                </dd>

                <dt class="col-sm-5 col-md-4">Sexe</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->defunt->sexe == 'M' ? 'Masculin' : 'Féminin' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Date de naissance</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->defunt->date_naissance ? date('d/m/Y', strtotime($certificat->defunt->date_naissance)) : '—' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Lieu de naissance</dt>
                <dd class="col-sm-7 col-md-8">{{ $certificat->defunt->lieu_naissance ?? '—' }}</dd>

                <dt class="col-sm-5 col-md-4">Situation matrimoniale</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->situationMat?->lib_situation_matrimoniale ?? '—' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Lieu de survenance</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->lieuSurvenance?->lib_lieu_survenance ?? '—' }}
                </dd>

                @if($certificat->conjoint)
                <dt class="col-sm-5 col-md-4">Conjoint(e)</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->conjoint->nom }} {{ $certificat->conjoint->prenom }}
                </dd>

                <dt class="col-sm-5 col-md-4">N° acte de mariage</dt>
                <dd class="col-sm-7 col-md-8">{{ $certificat->num_acte_mariage ?? '—' }}</dd>

                <dt class="col-sm-5 col-md-4">CEC de mariage</dt>
                <dd class="col-sm-7 col-md-8">{{ $certificat->cec_mariage ?? '—' }}</dd>
                @endif

                <dt class="col-sm-5 col-md-4">Déclarant</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->declarant ? $certificat->declarant->nom.' '.$certificat->declarant->prenom : '—' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Date de déclaration</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->date_heure_declaration ? date('d/m/Y H:i', strtotime($certificat->date_heure_declaration)) : '—' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Date d'émission</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->acte?->date_emission ? date('d/m/Y H:i', strtotime($certificat->acte->date_emission)) : '—' }}
                </dd>
            </dl>

            {{-- ── Bloc signature & traçabilité ── --}}
            <hr class="my-3" style="border-color:#DC241F; opacity:.3;">
            <h6 class="fw-bold mb-3" style="color:#9b1915;">
                <i class="fa fa-shield-alt me-2"></i>Signature &amp; Traçabilité
            </h6>
            <dl class="row">
                <dt class="col-sm-5 col-md-4">Officier signataire</dt>
                <dd class="col-sm-7 col-md-8">
                    @if($certificat->acte?->signataire)
                        {{ $certificat->acte->signataire->user?->name ?? $certificat->acte->approbation_pompe_funebre }}
                    @else
                        <span class="text-muted fst-italic">Non encore signé</span>
                    @endif
                </dd>

                <dt class="col-sm-5 col-md-4">Date de validation</dt>
                <dd class="col-sm-7 col-md-8">
                    {{ $certificat->acte?->date_heure_approbation_pompe_funebre
                        ? \Carbon\Carbon::parse($certificat->acte->date_heure_approbation_pompe_funebre)->format('d/m/Y \à H:i:s')
                        : '—' }}
                </dd>

                <dt class="col-sm-5 col-md-4">Code OTP utilisé</dt>
                <dd class="col-sm-7 col-md-8">
                    @if($certificat->acte?->otp_approbation_pompe_funebre)
                        <span class="badge text-white font-monospace px-3 py-2"
                              style="background:#DC241F; letter-spacing:.15em; font-size:.9rem;">
                            {{ $certificat->acte->otp_approbation_pompe_funebre }}
                        </span>
                    @else
                        <span class="text-muted fst-italic">—</span>
                    @endif
                </dd>

                <dt class="col-sm-5 col-md-4">Adresse MAC</dt>
                <dd class="col-sm-7 col-md-8">
                    @if($certificat->acte?->adresse_mac_approbation)
                        <code class="text-dark" style="font-size:.88rem;">{{ $certificat->acte->adresse_mac_approbation }}</code>
                    @else
                        <span class="text-muted fst-italic">—</span>
                    @endif
                </dd>

                <dt class="col-sm-5 col-md-4">Appareil utilisé</dt>
                <dd class="col-sm-7 col-md-8">
                    @if($certificat->acte?->nom_appareil_approbation)
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa fa-laptop" style="color:#2781d5;"></i>
                            {{ $certificat->acte->nom_appareil_approbation }}
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
