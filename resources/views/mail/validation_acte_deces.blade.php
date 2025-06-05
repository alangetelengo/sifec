@component('mail::message')
M (Mme) {{ $directeur_pompe_funebre }},

votre code pour valider {{ $nombre }} acte(s) de décès est {{ $code_otp }}


Merci,<br>
{{ config('app.name') }}
@endcomponent
