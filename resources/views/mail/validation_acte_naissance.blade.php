@component('mail::message')
M (Mme) {{ $maire }},

votre code pour valider {{ $nombre }} acte(s) de naissance est {{ $code_otp }}


Merci,<br>
{{ config('app.name') }}
@endcomponent
