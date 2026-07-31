@php
    use App\Support\GuotSignatureAffichage;

    $ctxSig = $contexteForcage ?? $ddc->contexte_affichage ?? null;
    if ($ctxSig === 'formation_sanitaire') {
        $prefixSig = 'sig_fs_';
        $roleSigFallback = 'Chef de service';
    } elseif ($ctxSig === 'centre_hygiene') {
        if (filled($ddc->sig_cec_proof_id)) {
            $prefixSig = 'sig_cec_';
            $roleSigFallback = 'Responsable pompe funèbre / CEC';
        } else {
            $prefixSig = 'sig_ch_';
            $roleSigFallback = 'Médecin responsable du constat';
        }
    } elseif ($ctxSig === 'pompe_funebre') {
        $prefixSig = 'sig_cec_';
        $roleSigFallback = 'Responsable pompe funèbre / CEC';
    } elseif (filled($ddc->sig_cec_proof_id)) {
        $prefixSig = 'sig_cec_';
        $roleSigFallback = 'Responsable pompe funèbre / CEC';
    } elseif (filled($ddc->sig_fs_proof_id)) {
        $prefixSig = 'sig_fs_';
        $roleSigFallback = 'Chef de service';
    } elseif (filled($ddc->sig_ch_proof_id)) {
        $prefixSig = 'sig_ch_';
        $roleSigFallback = 'Médecin responsable du constat';
    } else {
        $prefixSig = 'sig_fs_';
        $roleSigFallback = 'Chef de service';
    }

    $roleSig = GuotSignatureAffichage::roleSignataire($ddc, $prefixSig, $roleSigFallback);
    $signataireNom = $ddc->{$prefixSig.'actor_nom'} ?? null;
    $signeLe = $ddc->{$prefixSig.'signed_at'} ?? ($ddc->{$prefixSig.'doc_sig_signed_at'} ?? null);
    $estSigne = filled($ddc->{$prefixSig.'proof_id'} ?? null);
    $dateDocument = $signeLe ?: $ddc->created_at;
    $mentionSignature = 'Signé électroniquement';
    if ($signeLe) {
        $mentionSignature .= ' le '.\Carbon\Carbon::parse($signeLe)->format('d/m/Y à H:i');
    }
    $afficherQr = ($forceSignatureQr ?? false) || $estSigne || ($ddc->declarant_approuver ?? '') === 'OUI';
    $locAffichage = $localisation ?? 'Brazzaville';

    $titrePki = match ($prefixSig) {
        'sig_fs_' => 'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — CERTIFICAT',
        'sig_ch_' => 'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — CONSTAT',
        default => 'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — DÉCLARATION',
    };
    $blocPkiDeces = GuotSignatureAffichage::blocPki(
        $ddc,
        $prefixSig,
        $titrePki,
        $roleSigFallback,
        $prefixSig === 'sig_fs_' ? '#1a5fb4' : '#006B31',
        $prefixSig === 'sig_fs_' ? '#f5f9fc' : '#f4faf6',
    );
@endphp
<div style="bottom:0;margin-left:8px;margin-top:5px">
    <table class="historique" cellspacing="0" style="width: 95%; font-size: 12px; table-layout: fixed;">
        <col style="width: 34%">
        <col style="width: 32%">
        <col style="width: 34%">
        <tbody>
            <tr>
                <td style="text-align: left; vertical-align: top;">
                    Lu et approuvé<br>
                    <strong>(<span style="color: red;">{{ $ddc->declarant_approuver ?? 'NON' }}</span>)</strong><br>
                    Le déclarant
                </td>
                <td style="text-align: center; vertical-align: top;">
                    @if($afficherQr && isset($qrCode))
                        <qrcode value="{{ $qrCode }}" ec="H" style="width: 24mm; border: none;"></qrcode>
                        <br>
                        <span style="font-size: 6.5px; color: #555;">Scanner pour authentifier</span>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: top;">
                    Fait à {{ ucfirst(strtolower($locAffichage)) }}, le {{ utf8_encode(strftime('%d %B %Y', strtotime((string) $dateDocument))) }}<br>
                    {{ $roleSig }}
                    @if($estSigne)
                        <br><span style="font-size: 9px;">{{ $signataireNom ?: '' }}</span>
                        <br><span style="font-size: 8px; color:#006B31;">{{ $mentionSignature }}</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @if($blocPkiDeces)
        @include('partials.guot.signature-pki-blocs', ['blocs' => [$blocPkiDeces], 'compact' => true])
    @endif
</div>
