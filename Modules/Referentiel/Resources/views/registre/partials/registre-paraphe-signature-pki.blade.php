{{--
  Bloc PKI du paraphe tribunal (page de garde du livret).
  Variables :
    $registre  — Registre
    $contexte  — 'naissance' | 'mariage' | 'deces' (pour la mention légale)
--}}
@php
    $contexteParaphe = $contexte ?? 'naissance';
    $registre->loadMissing(['signataire.fonction']);
    $roleFallback = $registre->signataire?->fonction?->lib_fonction
        ?: 'Président du tribunal';
    // roleSignataire : actor_fonction persisté → approbation_tribunal → repli
    $blocParaphe = \App\Support\GuotSignatureAffichage::blocPki(
        $registre,
        '',
        'RÉFÉRENCES DE LA SIGNATURE ÉLECTRONIQUE PKI — PARAPHE TRIBUNAL',
        $roleFallback,
        '#006B31',
        '#f4faf6',
    );
    $typeMention = match ($contexteParaphe) {
        'mariage' => 'registre_mariage',
        'deces' => 'registre_deces',
        default => 'registre_naissance',
    };
@endphp
@if($blocParaphe)
    <div class="registre-paraphe-pki" style="clear: both; margin: 18px 24px 8px 24px; text-align: left;">
        @include('partials.guot.signature-pki-blocs', ['blocs' => [$blocParaphe], 'compact' => true])
        <div style="margin-top: 8px;">
            @include('partials.guot.mention-legale-pied', ['typeDocument' => $typeMention])
        </div>
    </div>
@endif
