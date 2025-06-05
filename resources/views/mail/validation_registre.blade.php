@component('mail::message')
M (Mme) {{ $tribunal }},
Votre code pour parapher le registre numero {{ $code_registre }} est {{ $otp }}

Merci,<br>
{{ config('app.name') }}
@endcomponent
