@php
    $registreVerificationUrl = \Illuminate\Support\Facades\URL::signedRoute(
        'verification.registre',
        ['code' => $registre->code_registre]
    );
@endphp
<div class="registre-paraphe-qrcode" style="display:inline-block; vertical-align:middle; text-align:center; margin-left:8px;">
    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->margin(1)->errorCorrection('H')->generate($registreVerificationUrl) !!}
    <div style="font-size:9px; color:#555; margin-top:4px; max-width:130px; line-height:1.2;">
        Scanner pour authentifier
    </div>
</div>
