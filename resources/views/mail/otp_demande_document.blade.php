@component('mail::message')
M (Mme) {{ $nomSignataire }},

votre code pour signer {{ $nbDemandes }} demande(s) de document(s) est {{ $codeOtp }}

Ce code est valable pendant 2 minutes.

Merci,<br>
{{ config('app.name') }}
@endcomponent

