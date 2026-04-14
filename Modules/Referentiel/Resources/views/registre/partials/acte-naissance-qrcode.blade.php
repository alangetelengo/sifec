@if ($acte->approbation_mairie != '')
    @php
        $acteVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute('verification.acte', ['niupp' => $acte->niupp]);
    @endphp
    <div class="registre-acte-qrcode" style="width: 92px; max-width: 100%; flex-shrink: 0;">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(88)->margin(0)->errorCorrection('H')->generate($acteVerificationUrl) !!}
    </div>
@endif
