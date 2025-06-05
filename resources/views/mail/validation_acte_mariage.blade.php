@component('mail::message')
M (Mme) {{ $maire }},

votre code pour valider l'acte de mariage {{ $code_declaration_mariage }} est {{ $code_otp }}


Merci,<br>
{{ config('app.name') }}
@endcomponent
