<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIFEC | Vérification document décès</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">
    <style>
        body { background: linear-gradient(135deg, rgba(33,185,49,0.12), rgba(68,157,68,0.2)); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: "Nunito", sans-serif; }
        .verification-wrapper { width: 100%; max-width: 720px; padding: 2rem; }
        .verification-card { border: none; border-radius: 1rem; box-shadow: 0 20px 40px rgba(0,0,0,0.12); overflow: hidden; background: #fff; }
        .verification-header { background: linear-gradient(135deg, #21B931, #449D44); color: #fff; }
        .verification-header h1 { font-size: 1.4rem; margin: 0; letter-spacing: 0.03em; }
        .logo-wrapper img { max-height: 60px; }
        dt { color: #6c757d; font-weight: 600; }
        dd { color: #1c1e21; }
    </style>
</head>
<body>
@php
    use App\Support\GuotSignatureAffichage;

    $titres = [
        'certificat' => 'Certificat de décès',
        'constatation' => 'Certificat de constatation de décès',
        'declaration' => 'Déclaration de décès',
    ];
    $prefixes = [
        'certificat' => ['sig_fs_', 'Chef de service (formation sanitaire)'],
        'constatation' => filled($declaration->sig_cec_proof_id)
            ? ['sig_cec_', 'Responsable pompe funèbre / CEC']
            : ['sig_ch_', 'Médecin responsable du constat'],
        'declaration' => ['sig_cec_', 'Responsable pompe funèbre / CEC'],
    ];
    [$prefix, $roleSigFallback] = $prefixes[$type] ?? ['sig_cec_', 'Signataire'];
    $roleSig = GuotSignatureAffichage::roleSignataire($declaration, $prefix, $roleSigFallback);
    $titre = $titres[$type] ?? 'Document de décès';
@endphp
    <div class="verification-wrapper">
        <div class="text-center mb-4 logo-wrapper">
            <img src="{{ asset('assets-login/images/logo-sifec-app.gif') }}" alt="SIFEC">
        </div>
        <div class="card verification-card">
            <div class="card-header verification-header py-4">
                <h1>Vérification — {{ $titre }}</h1>
                <span class="fw-semibold">N° {{ $declaration->code_declaration_deces }}</span>
            </div>
            <div class="card-body p-4">
                <dl class="row">
                    <dt class="col-sm-5 col-md-4">Défunt</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->defunt?->nomcomplet() ?? '—' }}</dd>
                    <dt class="col-sm-5 col-md-4">Date de décès</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->date_heure_deces ? date('d/m/Y H:i', strtotime($declaration->date_heure_deces)) : '—' }}</dd>
                    <dt class="col-sm-5 col-md-4">Déclarant</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->declarant?->nomcomplet() ?? '—' }}</dd>
                    <dt class="col-sm-5 col-md-4">Institution</dt>
                    <dd class="col-sm-7 col-md-8">{{ $declaration->institution?->lib_institution ?? '—' }}</dd>
                </dl>
                <hr class="my-3" style="border-color:#009E49; opacity:.3;">
                <h6 class="fw-bold mb-3" style="color:#006B31;"><i class="fa fa-shield-alt me-2"></i>Signature &amp; Traçabilité</h6>
                @if(!filled($declaration->{$prefix.'proof_id'}))
                    <p class="text-muted fst-italic mb-0">Ce document n'a pas encore été signé électroniquement.</p>
                @else
                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-md-4">Rôle</dt>
                        <dd class="col-sm-7 col-md-8">{{ $roleSig }}</dd>
                        <dt class="col-sm-5 col-md-4">Signataire</dt>
                        <dd class="col-sm-7 col-md-8">{{ $declaration->{$prefix.'actor_nom'} ?: '—' }}</dd>
                        <dt class="col-sm-5 col-md-4">Signé le</dt>
                        <dd class="col-sm-7 col-md-8">
                            @php $sigDate = $declaration->{$prefix.'signed_at'} ?? $declaration->{$prefix.'doc_sig_signed_at'}; @endphp
                            {{ $sigDate ? \Carbon\Carbon::parse($sigDate)->format('d/m/Y à H:i:s') : '—' }}
                        </dd>
                        <dt class="col-sm-5 col-md-4">Identifiant de preuve (proof_id)</dt>
                        <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $declaration->{$prefix.'proof_id'} }}</code></dd>
                        @if(filled($declaration->{$prefix.'doc_sig_id'}))
                            <dt class="col-sm-5 col-md-4">Identifiant signature (L2)</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $declaration->{$prefix.'doc_sig_id'} }}</code></dd>
                        @endif
                        @if(filled($declaration->{$prefix.'doc_seal_id'}))
                            <dt class="col-sm-5 col-md-4">Cachet institutionnel (L3)</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small">{{ $declaration->{$prefix.'doc_seal_id'} }}</code></dd>
                        @endif
                        @php $empreinte = $declaration->{$prefix.'pdf_content_hash'} ?? $declaration->{$prefix.'payload_hash'}; @endphp
                        @if(filled($empreinte))
                            <dt class="col-sm-5 col-md-4">Empreinte document</dt>
                            <dd class="col-sm-7 col-md-8"><code class="small" style="word-break:break-all;">{{ $empreinte }}</code></dd>
                        @endif
                    </dl>
                @endif
                @if($type === 'constatation' && filled($declaration->sig_cec_proof_id) && filled($declaration->sig_ch_proof_id))
                    <hr class="my-3">
                    <h6 class="fw-bold mb-2" style="color:#006B31;">Signature d'origine (centre d'hygiène)</h6>
                    <dl class="row mb-0">
                        <dt class="col-sm-5 col-md-4">Médecin / signataire CH</dt>
                        <dd class="col-sm-7 col-md-8">{{ $declaration->sig_ch_actor_nom ?: '—' }}</dd>
                    </dl>
                @endif
            </div>
            <div class="card-footer bg-light py-3 text-center">
                <small class="text-muted">Système Intégré des Faits d'État Civil (SIFEC)</small>
            </div>
        </div>
    </div>
</body>
</html>
