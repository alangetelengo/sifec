@component('mail::message')
Bonjour,

Le **{{ $tribunalLib }}** a validé (paraphé) le registre suivant :

- **Type :** {{ $typeRegistre }}
- **Référence :** {{ $numeroOrdreRegistre }}
- **Centre d'état civil :** {{ $cecLib }}

Vous pouvez poursuivre les enregistrements d'actes sur ce registre dans SIFEC.

@component('mail::button', ['url' => route('registre.index')])
Accéder aux registres
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
