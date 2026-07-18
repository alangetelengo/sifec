@if ($acte->approbation_mairie != '' && filled($acte->niupp))
    @php
        $acteVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
    @endphp
    <div class="registre-acte-qrcode" style="width: 92px; max-width: 100%; flex-shrink: 0; text-align: center;">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(88)->margin(0)->errorCorrection('H')->generate($acteVerificationUrl) !!}
        <div style="font-size: 9px; color: #555; margin-top: 4px; line-height: 1.2;">
            Scanner pour authentifier
        </div>
    </div>
@endif
