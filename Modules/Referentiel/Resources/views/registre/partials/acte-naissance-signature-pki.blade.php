{{--
  Pied d’acte dans le livret registre (sans blocs PKI techniques).
  Aligné sur Modules/Naissance/Resources/views/etats/acte.blade.php
  Variable : $acte — ActeNaissance
--}}
@php
    $decl = $acte->declaration;
    $estSigneActe = filled($acte->approbation_mairie) || filled($acte->proof_id);

    $dateSignatureActe = $acte->signed_at
        ?? $acte->doc_sig_signed_at
        ?? $acte->date_heure_approbation_mairie;

    $roleActe = \App\Support\GuotSignatureAffichage::roleSignataire(
        $acte,
        '',
        "L'officier de l'état civil"
    );

    $personneSig = optional(optional($acte->signataire)->user)->personne;
    $sigNomActe = $acte->actor_nom
        ?: ($personneSig ? $personneSig->nomcomplet() : null);

    $signaturePath = filled($acte->signature_mairie)
        ? $acte->signature_mairie
        : ($personneSig?->signature);

    $sigRel = ltrim(str_replace('\\', '/', (string) ($signaturePath ?? '')), '/');
    if (str_starts_with($sigRel, 'app/')) {
        $sigRel = substr($sigRel, 4);
    }
    $sigUrl = ($sigRel !== '' && is_file(public_path('app/'.$sigRel)))
        ? asset('app/'.$sigRel)
        : null;

    $institutionCec = optional(optional($acte->institutionUser)->institution)
        ?? optional(optional($decl)->institutionUser)->institution
        ?? optional($decl)->institution;
    $sceauRel = ltrim(str_replace('\\', '/', (string) (optional($institutionCec)->sceau ?? '')), '/');
    if (str_starts_with($sceauRel, 'app/')) {
        $sceauRel = substr($sceauRel, 4);
    }
    $sceauUrl = ($sceauRel !== '' && is_file(public_path('app/'.$sceauRel)))
        ? asset('app/'.$sceauRel)
        : null;

    $communeDistrict = null;
    try {
        $communeDistrict = optional(optional($acte->institutionUser)->institution)->lieu?->localiteParent;
    } catch (\Throwable $e) {
        $communeDistrict = null;
    }
    $lieuFait = $communeDistrict?->lib_localite
        ? ucfirst(strtolower(trans($communeDistrict->lib_localite)))
        : 'Brazzaville';
@endphp

<div class="d-flex align-items-start justify-content-between" style="gap: 12px; width: 100%; margin-top: 12px;">
    <div style="min-width: 90px; padding-top: 8px;">
        Le déclarant
        @if($estSigneActe && $decl?->declarant)
            <br><span style="font-size: 9px; font-weight: bold;">{{ \App\Sifec\Sifec::formatNomPrenomPourActe($decl->declarant->nom, $decl->declarant->prenom) }}</span>
        @endif
    </div>
    <div class="text-center">
        @if($estSigneActe)
            <div class="d-inline-flex align-items-center justify-content-center" style="gap: 10px;">
                @include('referentiel::registre.partials.acte-naissance-qrcode', ['acte' => $acte])
                @if ($sceauUrl)
                    <img src="{{ $sceauUrl }}" alt="Sceau du CEC" style="width: 88px; height: 88px; object-fit: contain;">
                @endif
            </div>
        @endif
    </div>
    <div class="text-end" style="min-width: 200px; flex: 1;">
        @if ($estSigneActe && filled($dateSignatureActe))
            <p class="mb-1">
                Fait à {{ $lieuFait }}, le {{ utf8_encode(strftime('%d %B %Y', strtotime((string) $dateSignatureActe))) }}
            </p>
            @if ($sigUrl)
                <img src="{{ $sigUrl }}" alt="Signature de l'officier" style="max-height: 56px; max-width: 140px;"><br>
            @endif
            <span>{{ $roleActe }}</span><br>
            <strong>
                @if($personneSig)
                    {{ \App\Sifec\Sifec::formatNomPrenomPourActe($personneSig->nom, $personneSig->prenom) }}
                @else
                    {{ $sigNomActe }}
                @endif
            </strong>
        @endif
    </div>
</div>
