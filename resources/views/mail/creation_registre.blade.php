@component('mail::message')
M.(Mme) {{ $tribunal }},
un registre de {{ $type_registre }} numero {{ $code_registre }} provenance {{ $cec }} est en attente de validation


Merci,<br>
{{ config('app.name') }}
@endcomponent
