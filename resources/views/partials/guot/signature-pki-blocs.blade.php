{{--
  Blocs de traçabilité PKI pour PDF Html2Pdf.
  Variables :
    $blocs   = list of [titre, couleur, role, nom, date, empreinte, certificat, proof_id]
    $compact = bool (optionnel) : version densifiée pour actes / pages courtes
--}}
@if(! empty($blocs))
@php
    $compact = (bool) ($compact ?? false);
    $chunk = $compact ? 64 : 48;
    $wrapHex = static function (?string $value, int $chunk): string {
        if (! filled($value)) {
            return '';
        }

        return implode('<br>', str_split((string) $value, $chunk));
    };
    $fsTitle = $compact ? '7.5pt' : '9pt';
    $fsBody = $compact ? '6.5pt' : '9pt';
    $fsHash = $compact ? '6pt' : '8pt';
    $pad = $compact ? '0.8mm 1.2mm' : '2mm 2.5mm';
    $gap = $compact ? '0.8mm' : '2mm';
    $mt = $compact ? '1.5mm' : '3mm';
    $width = $compact ? '100%' : '95%';
@endphp
<table cellspacing="0" cellpadding="0" style="width: {{ $width }}; margin-top: {{ $mt }}; table-layout: fixed;">
    <col style="width: 100%">
    @foreach($blocs as $i => $bloc)
        @php $couleur = $bloc['couleur'] ?? '#006B31'; @endphp
        @if($i > 0)
        <tr>
            <td style="height: {{ $gap }};"></td>
        </tr>
        @endif
        <tr>
            <td style="border: 0.35mm solid {{ $couleur }}; padding: {{ $pad }}; background-color: #FFFFFF;">
                <table cellspacing="0" cellpadding="0" style="width: 100%; table-layout: fixed; font-family: Arial; color: #000000; line-height: 1.15;">
                    <tr>
                        <td style="font-size: {{ $fsTitle }}; font-weight: bold; color: {{ $couleur }}; padding-bottom: 0.3mm;">
                            {{ $bloc['titre'] }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: {{ $fsBody }}; color: #000000; padding-bottom: 0.15mm;">
                            <b>{{ $bloc['role'] ?? 'Signataire' }} :</b>
                            {{ $bloc['nom'] ?: '—' }}
                            @if(! empty($bloc['date']))
                                — {{ \Carbon\Carbon::parse($bloc['date'])->format('d/m/Y H:i:s') }}
                            @endif
                        </td>
                    </tr>
                    @if(filled($bloc['proof_id'] ?? null))
                    <tr>
                        <td style="font-size: {{ $fsBody }}; color: #000000; padding-bottom: 0.15mm;">
                            <b>Identifiant de preuve (proof_id) :</b> {{ $bloc['proof_id'] }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-size: {{ $fsBody }}; color: #000000; padding-bottom: 0.15mm;">
                            <b>Algorithme :</b> SHA256withRSA
                            @if(filled($bloc['certificat'] ?? null))
                                &nbsp;|&nbsp;<b>Certificat :</b> {{ $bloc['certificat'] }}
                            @endif
                        </td>
                    </tr>
                    @if(filled($bloc['empreinte'] ?? null))
                    <tr>
                        <td style="font-size: {{ $fsHash }}; color: #000000;">
                            <b>Empreinte SHA-256 :</b> {!! $wrapHex($bloc['empreinte'], $chunk) !!}
                        </td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    @endforeach
</table>
@endif
