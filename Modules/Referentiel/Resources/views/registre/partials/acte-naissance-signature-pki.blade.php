{{--
  Blocs PKI d'un acte de naissance (livret / feuillet registre).
  Aligné sur Modules/Naissance/Resources/views/etats/acte.blade.php
  Variable : $acte — ActeNaissance (declaration optionnelle)
--}}
@php
    $decl = $acte->declaration;
    $blocsPki = [];
    if ($decl) {
        $blocsPki = array_values(array_filter([
            \App\Support\GuotSignatureAffichage::blocPki(
                $decl,
                'sig_cec_',
                'PKI — DÉCLARATION',
                "Officier d'état civil",
                '#1a5fb4',
                '#f5f9fc',
            ),
            \App\Support\GuotSignatureAffichage::blocPki(
                $acte,
                '',
                'PKI — ACTE',
                "L'officier de l'état civil",
                '#006B31',
                '#f4faf6',
            ),
        ]));
    } else {
        $blocActe = \App\Support\GuotSignatureAffichage::blocPki(
            $acte,
            '',
            'PKI — ACTE',
            "L'officier de l'état civil",
            '#006B31',
            '#f4faf6',
        );
        $blocsPki = $blocActe ? [$blocActe] : [];
    }
@endphp
@if(! empty($blocsPki))
    <div class="registre-acte-pki" style="clear: both; margin-top: 10px; text-align: left;">
        @include('partials.guot.signature-pki-blocs', ['blocs' => $blocsPki, 'compact' => true])
    </div>
@endif
