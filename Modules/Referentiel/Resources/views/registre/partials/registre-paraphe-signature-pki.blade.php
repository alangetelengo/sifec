{{--
  Pied de page de garde (paraphe tribunal) — sans blocs PKI techniques.
  Variables :
    $registre  — Registre
    $contexte  — 'naissance' | 'mariage' | 'deces'
--}}
@php
    $contexteParaphe = $contexte ?? 'naissance';
    $registre->loadMissing(['signataire.fonction', 'signataire.user.personne']);

    $estParaphe = filled($registre->approbation_tribunal)
        || filled($registre->proof_id)
        || filled($registre->sceau);

    $dateParaphe = $registre->signed_at
        ?? $registre->doc_sig_signed_at
        ?? $registre->updated_at;

    $roleParaphe = \App\Support\GuotSignatureAffichage::roleSignataire(
        $registre,
        '',
        $registre->signataire?->fonction?->lib_fonction ?: 'Président du tribunal'
    );

    $personneParaphe = optional(optional($registre->signataire)->user)->personne;
    $nomParaphe = $registre->actor_nom
        ?: ($personneParaphe ? $personneParaphe->nomcomplet() : null);

    $sigRel = ltrim(str_replace('\\', '/', (string) ($registre->signature_tribunal ?? '')), '/');
    if (str_starts_with($sigRel, 'app/')) {
        $sigRel = substr($sigRel, 4);
    }
    if ($sigRel === '' && $personneParaphe?->signature) {
        $sigRel = ltrim(str_replace('\\', '/', (string) $personneParaphe->signature), '/');
        if (str_starts_with($sigRel, 'app/')) {
            $sigRel = substr($sigRel, 4);
        }
    }
    $sigUrl = ($sigRel !== '' && is_file(public_path('app/'.$sigRel)))
        ? asset('app/'.$sigRel)
        : null;

    $sceauRel = ltrim(str_replace('\\', '/', (string) ($registre->sceau ?? '')), '/');
    if (str_starts_with($sceauRel, 'app/')) {
        $sceauRel = substr($sceauRel, 4);
    }
    $sceauUrl = ($sceauRel !== '' && is_file(public_path('app/'.$sceauRel)))
        ? asset('app/'.$sceauRel)
        : null;

    $villeParaphe = mb_strtoupper((string) (
        $registre->institutionUser->institution->lieu->localiteParent->lib_localite
        ?? 'BRAZZAVILLE'
    ), 'UTF-8');

    $typeMention = match ($contexteParaphe) {
        'mariage' => 'registre_mariage',
        'deces' => 'registre_deces',
        default => 'registre_naissance',
    };
@endphp

@if($estParaphe)
    <div style="clear: both; margin: 24px 24px 8px 24px;">
        <div class="d-flex align-items-start justify-content-between" style="gap: 16px; width: 100%;">
            <div style="flex: 1;"></div>
            <div class="text-center">
                <div class="d-inline-flex align-items-center justify-content-center" style="gap: 12px;">
                    @include('referentiel::registre.partials.registre-paraphe-qrcode', ['registre' => $registre])
                    @if ($sceauUrl)
                        <img src="{{ $sceauUrl }}" alt="Sceau du tribunal" style="max-height: 120px; max-width: 120px; object-fit: contain;">
                    @endif
                </div>
            </div>
            <div class="text-end" style="min-width: 240px; flex: 1;">
                <p class="mb-1" style="font-size: 14px;">
                    Fait à <strong>{{ $villeParaphe }}</strong>, le
                    <strong>{{ date('d-m-Y', strtotime((string) $dateParaphe)) }}</strong>
                </p>
                @if ($sigUrl)
                    <img src="{{ $sigUrl }}" alt="Signature" style="max-height: 70px; max-width: 160px; margin: 6px 0;"><br>
                @endif
                <span>{{ $roleParaphe }}</span><br>
                @if (filled($nomParaphe))
                    <strong>{{ $nomParaphe }}</strong>
                @endif
            </div>
        </div>
        <div style="margin-top: 16px; text-align: left;">
            @include('partials.guot.mention-legale-pied', ['typeDocument' => $typeMention])
        </div>
    </div>
@endif
