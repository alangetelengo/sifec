@php
    use App\Support\GuotSignatureAffichage;

    /**
     * Bloc de mention de signature électronique pour les pages de vérification.
     * Variables attendues : $doc (Declarationnaissance), $prefix ('sig_fs_'|'sig_cec_'), $titre.
     */
    $proofId = $doc->{$prefix.'proof_id'} ?? null;
    $signataireNom = $doc->{$prefix.'actor_nom'} ?? null;
    $signataireFonction = GuotSignatureAffichage::roleSignataire($doc, $prefix);
    $signeLe = $doc->{$prefix.'signed_at'} ?? ($doc->{$prefix.'doc_sig_signed_at'} ?? null);
    $docSigId = $doc->{$prefix.'doc_sig_id'} ?? null;
    $docSealId = $doc->{$prefix.'doc_seal_id'} ?? null;
    $certRef = $doc->{$prefix.'certificate_ref'} ?? null;
    $empreinte = $doc->{$prefix.'pdf_content_hash'} ?? ($doc->{$prefix.'payload_hash'} ?? null);
@endphp

<hr class="my-3" style="border-color:#009E49; opacity:.3;">
<h6 class="fw-bold mb-3" style="color:#006B31;">
    <i class="fa fa-shield-alt me-2"></i>{{ $titre }}
</h6>

@if(!filled($proofId))
    <p class="text-muted fst-italic mb-0">Ce document n'a pas encore été signé électroniquement.</p>
@else
    <dl class="row mb-0">
        @if(filled($signataireFonction))
            <dt class="col-sm-5 col-md-4">Fonction</dt>
            <dd class="col-sm-7 col-md-8">{{ $signataireFonction }}</dd>
        @endif
        <dt class="col-sm-5 col-md-4">Signataire</dt>
        <dd class="col-sm-7 col-md-8">{{ $signataireNom ?: '—' }}</dd>

        <dt class="col-sm-5 col-md-4">Signé le</dt>
        <dd class="col-sm-7 col-md-8">{{ $signeLe ? \Carbon\Carbon::parse($signeLe)->format('d/m/Y à H:i:s') : '—' }}</dd>

        @if(filled($docSigId))
            <dt class="col-sm-5 col-md-4">Identifiant signature (L2)</dt>
            <dd class="col-sm-7 col-md-8"><code class="small">{{ $docSigId }}</code></dd>
        @endif

        @if(filled($docSealId))
            <dt class="col-sm-5 col-md-4">Cachet institutionnel (L3)</dt>
            <dd class="col-sm-7 col-md-8"><code class="small">{{ $docSealId }}</code></dd>
        @endif

        @if(filled($certRef))
            <dt class="col-sm-5 col-md-4">Réf. certificat</dt>
            <dd class="col-sm-7 col-md-8"><code class="small">{{ $certRef }}</code></dd>
        @endif

        @if(filled($empreinte))
            <dt class="col-sm-5 col-md-4">Empreinte document</dt>
            <dd class="col-sm-7 col-md-8">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fa fa-check-circle text-success"></i>
                    <span class="small text-success fw-semibold">Empreinte SHA-256 enregistrée</span>
                </div>
                <code class="small" style="word-break:break-all;">{{ $empreinte }}</code>
            </dd>
        @endif
    </dl>
@endif
